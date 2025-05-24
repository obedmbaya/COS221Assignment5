<?php

    require_once("config.php");

    //Get all prices for that product and all the other stockists
    function compare($data){

        $conn = Database::instance()->getConnection();
        
        validateApikey($data);
        
        if (!isset($data["ProductID"])){
            header("HTTP/1.1 400 Bad Request");
            header("Content-type: application/json");
            echo json_encode([
                "status" => "failed",
                "data" => "Invalid API Key or no API Key was provided."
            ]);
            exit;
        }

        $stmt = $conn->prepare("SELECT p.ProductName, p.Description, p.Brand, p.IMG_reference,
                                r.RetailerName, r.RetailerReference,
                                pp.Price, pp.URL
                                FROM Product as p
                                JOIN ProductPrice as pp ON p.ProductID = pp.ProductID
                                JOIN Retailer as r ON pp.RetailerID = r.RetailerID
                                WHERE p.ProductID = ?");
        if (!$stmt){
            die("Prepare for compare query failed");
        }

        $stmt->bind_param("i", $data["ProductID"]);
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