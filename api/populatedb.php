<?php

    require ("config.php");

    function populateDB($data){

        validate($data);

        $conn = Database::instance()->getConnection();
        $products = $data["products"];

        foreach ($products as $product) {
            $name         = isset($product['name']) ? $product['name'] : null;
            $description  = isset($product['description']) ? $product['description'] : null;
            $brand        = isset($product['brand']) ? $product['brand'] : null;
            $img_reference = isset($product['img_reference']) ? $product['img_reference'] : null;
            $price       = isset($product['price']) ? $product['price'] : null;
            $images       = isset($product['images']) ? $product['images'] : null;
            $retailer     = isset($product['retailer']) ? $product['retailer'] : null;
            $link         = isset($product['link']) ? $product['link'] : null;

            //Product table ==> only name, description, brand and img_reference

            $stmt = $conn->prepare("INSERT INTO Product (ProductName, Description, Brand, IMG_Reference)
                                    VALUES (?, ?, ?, ?)");
            
            if (!$stmt){
                die("Prepare statement failed");
            }

            $stmt->bind_param("ssss", $name, $description, $brand, $img_reference);
            $stmt->execute();
            $stmt->close();
            
        }

    }

    function validate($data){
        //TODO: complete the validation

        $validApiKey = true;

        if (isset($data["apikey"])){
            $apikey = $data["apikey"];
            $conn = Database::instance()->getConnection();

            $stmt = $conn->prepare("SELECT 1
                                    FROM ApiKey
                                    WHERE KeyValue = ?");
            
            $stmt->bind_param("s", $apikey);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->get_rows <= 0){
                $validApiKey = false;
            }
            $stmt->close();
        }

        if ((isset($data["apikey"]) == false) || ($validApiKey == false)){
            response("HTTP/1.1 400 Bad Request", "failed", "Invalid apikey or no apikey");
        }

    }

    function response($header, $status, $returnData){
        header();
        header("Content-type: application/json");
        echo json_encode([
            "status" => $status,
            "data" => $returnData
        ]);
        exit;
    }

?>