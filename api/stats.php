<?php
//We will delete this file and move everything into endpoints.php, I just want it separate to avoid conflicts on GitHub
    /*
    input:
    {

        "type": "GetTopRated"

    }

    output:
    {

        "status": "failed" or "success"
        "data" : "error message" or 
            [
                {
                    "ProductID": 1234,
                    "ProductName" : "name",
                    "Description": "nbhjjhvhjhb",
                    "Brand": "Apple",
                    "IMG_Reference": "https://hghuiuiuh",
                    "Price": 1234.34,
                    "Retailer": "Takealot"
                },
                ... with 4 other products
            ]

    
    }

    */
    require_once("config.php");

    function handleTopRated($data){

        $conn = Database::instance()->getConnection();
        $stmt = $conn->prepare("SELECT r.ProductID, p.ProductName, p.Description, p.Brand, p.IMG_Reference, p.Price, p.Retailer, AVG(r.Rating) AS Rating
                        FROM Review r
                        JOIN Product p ON r.ProductID = p.ProductID
                        GROUP BY r.ProductID
                        ORDER BY Rating DESC
                        LIMIT 5");

        if (!$stmt) {
            die("Failed to prepare query: " . $conn->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        header("HTTP/1.1 200 OK");
        header("Content-type: application/json");
        echo json_encode([
            "status" => "success",
            "data" => $products
        ]);

    }

    //Imma also put getRetailerById in here even tho it's not a stats endpoint
    function handleGetRetailerById(){

        validateApikey($data);

        if (!isset($data["userID"])){
            header("HTTP/1.1 400 Bad Request");
            header("Content-type: application/json");
            echo json_encode([
                "status" => "failed",
                "data" => "Please provide a userID."
            ]);
            exit;
        }

        $conn = Database::instance()->getConnection();
        $stmt = $conn->prepare("SELECT u.UserID, u.FirstName, u.LastName, u.Email, r.RetailerID, r.RetailerName, r.SiteReference, r.Email
                                FROM Retailer as r
                                JOIN User as u ON r.Email = u.Email
                                WHERE u.UserID = ? AND u.UserType = 'Retailer");
        if (!$stmt){
            die("Failed to prepare query: " . $conn->error);
        }

        $stmt->bind_param(s, $data["userID"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $retailer = $result->fetch_assoc();
        $stmt->close();

        if (!$retailer) {
            header("HTTP/1.1 404 Not Found");
            header("Content-type: application/json");
            echo json_encode([
                "status" => "failed",
                "data" => "Retailer not found with the provided ID."
            ]);
            exit;
        }

        header("HTTP/1.1 200 OK");
        header("Content-type: application/json");
        echo json_encode([
            "status" => "success",
            "data" => $retailer
        ]);

    }

    function validateApikey($data){

        $output = true;

        if (!isset($data["apikey"])){

            $output = false;

        } else {

            $conn = Database::instance()->getConnection();
            $apikey = $data["apikey"];

            $stmt = $conn->prepare("SELECT 1
                            FROM  ApiKey
                            WHERE KeyValue = ?");
            if (!$stmt){
                die("Failed to prepare apikey validation query");
            }

            $stmt->bind_param("s", $apikey);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0){
                $output = false;
            }

            $stmt->close();

        }

        if (!$output){
            header("HTTP/1.1 401 Unauthorized");
            header("Content-type: application/json");
            echo json_encode([
                "status" => "failed",
                "data" => "Invalid API Key or no API Key was provided."
            ]);
            exit;
        }

    }


?>