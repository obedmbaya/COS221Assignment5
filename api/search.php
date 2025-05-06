<?php

header('Content-Type' : 'application/json');

//connect to our database
require_once 'config.php';

class APIHandler
{
    private $database;

    public function __construct($conn){
        $this->database = $conn;
    }

    private function validateAPIKey($key){
        $stmt = $this->database->prepare("SELECT ApiKey FROM ApiKey WHERE ApiKey = ?");
        $stmt->bind_param("s", $key);
        $stmt->execute();
        $stmt->store_result();

        //check if there is a matching key in the database
        return $stmt->num_rows > 0;
    }

    public function requestType(){
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);

        if (!$this->validateAPIKey($data["apiKey"]) || !isset($data["apikey"])){
            $this->sendResponse("error", "Invalid API Key", 403);
            return;
        }

        //different types still need to be implemented. 
        //including: register, login, logout, ect...

        $type = $data["type"];

        if ($type === "search"){
            $this->search($input);
        }

        //-------------->>>>>>>>>>> implementation for different request types

        else{
            $this->sendResponse("error", "Invalid Request Type", 400);
        }

    }

    private function search($data){
        $query = "SELECT p.ProductID, p.ProductName, p.Description, p.Brand, p.IMG_Reference pp.Price, r.RetailerName
                  FROM Product p
                  JOIN ProductPrice pp ON p.ProductID = pp.ProductID
                  JOIN Retailer r ON pp.RetailerID = r.RetailerID
                  WHERE 1=1"; //1=1 "cleanly combine optional filters"
        
        $params = [];
        $types = "";

        if (isset($data["search"]) && is_array($data["search"])){
            foreach($data["search"] as $entry => $value){
                if (in_array($entry, ["ProductName", "Brand", "Description"])){
                    if (!empty($data["fuzzy"])){
                        $query .= " AND p.$entry LIKE ?";
                        $params[] = "%$value$";
                    }
                    else{
                        $query .= " AND p.$entry =?";
                        $params[] = $value;
                    }
                    $types .= "s";
                }
            }
        }
        
        if (isset($data["sort"]) && in_array($data["sort"], ["Price", "ProductName"])){
            $query .= " ORDER BY " . $data["sort"];
            if (isset($data["order"]) && in_array(strtolower($data["order"]), ["ASC", "DESC"])){
                $query .= " " . strtoupper($data["order"]);
            }
        }

        if (isset($data["limit"])){
            $query .= " LIMIT ?";
            $params[] = intval($data["limit"]);
            $types .= "i";
        }

        $stmt = $this->database->prepare($query);

        if (!empty($params)){
            $stmt->bind_params($types, ...$params);
        }

        $stmt->execute();

        $stmtResult = $stmt->get_result();
        
        $products = [];

        while ($entries = $stmtResult->fetch_assoc()){
            $products[] = $entries;
        }


        $stmt->close();
        $this->sendResponse("success", $products, 200);
    }

    private function sendResponse($status, $data, $httpCode = 200){
        http_response_code($code);
        echo json_encode(
            [
                "status" => $status,
                "data" => $data
            ]
        );
    }


}

try{
    $apiHandler = new APIHandler($conn);
    $apiHandler->requestType();
}
catch (Exception $e){
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "timestamp" => time(),
        "data" => "Internal Server Error" . $e;
    ])
}

?>