<?php

    require_once ("config.php");

    function populateDB($data){

        if (!isset($data["data"]["products"])) {
            response("HTTP/1.1 400 Bad Request", "failed", "No products data found");
        }

        $conn = Database::instance()->getConnection();
        $products = $data["data"]["products"];

        foreach ($products as $productGroup) {
            // $productGroup is an associative array where the key is something like "Samsung Galaxy S25 Ultra"
            foreach ($productGroup as $productKey => $productListings) {
                // $productListings is the array of product details

                // Example: Extract the first word from the product name
                $brand = explode(" ", $productKey)[0] ?? null;
                $name = $productKey;
                
                foreach ($productListings as $listing) {
                    // Extract specific values from each listing
                    $category       = $listing["category"]        ?? null;
                    $thumbnail      = $listing["thumbnail"]       ?? null;
                    $source         = $listing["source"]          ?? null;
                    $title          = $listing["title"]           ?? null;
                    $rating         = $listing["rating"]          ?? null;
                    $extractedPrice = $listing["extracted_price"] * 18.91 ?? null;

                    if ($name != null){
                        $stmt = $conn->prepare("INSERT INTO Product (ProductName, Brand, IMG_Reference)
                                               VALUES (?, ?, ?)");
            
                        if (!$stmt){
                            die("Prepare statement failed");
                        }

                        $stmt->bind_param("sss", $name, $brand, $thumbnail);
                        $stmt->execute();
                        $productID = $conn->insert_id;
                        $stmt->close();

                        //checks whether the retailer already exists 
                        $stmt = $conn->prepare("SELECT RetailerID, RetailerName
                                                FROM Retailer
                                                WHERE RetailerName LIKE ?
                                                LIMIT 1");
                        if (!$stmt){
                            die("Prepare statement failed");
                        }
                        $likeBrand = "%$brand%";
                        $stmt->bind_param("s", $likeBrand);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $retailer = $result->fetch_assoc();
                        $stmt->close();

                        $retailerID = null;

                        if (empty($retailer)){
                            
                            $stmt = $conn->prepare("INSERT INTO Retailer (RetailerName)
                                                    VALUES (?)");
                            if (!$stmt){
                                die("Prepare statement failed");
                            }
                            $stmt->bind_param("s", $brand);
                            $stmt->execute();
                            $retailerID = $conn->insert_id;
                            $stmt->close();

                        } else {

                            $retailerID = $retailer["RetailerID"];
                        
                        }

                        if ($productID && $retailerID && $extractedPrice !== null) {
                            $stmt = $conn->prepare("INSERT INTO ProductPrice (ProductID, RetailerID, Price) VALUES (?, ?, ?)");
                            if (!$stmt) {
                                die("Prepare statement failed");
                            }
                            $stmt->bind_param("iid", $productID, $retailerID, $extractedPrice);
                            $stmt->execute();
                            $stmt->close();
                        }

                    }
                }
            }
        }
        response("HTTP/1.1 200 OK", "success", "Successfully added products");
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
            
            if ($result->num_rows <= 0){
                $validApiKey = false;
            }
            $stmt->close();
        }

        if ((isset($data["apikey"]) == false) || ($validApiKey == false)){
            response("HTTP/1.1 400 Bad Request", "failed", "Invalid apikey or no apikey");
        }

    }

    function response($header, $status, $returnData){
        header($header);
        header("Content-type: application/json");
        echo json_encode([
            "status" => $status,
            "data" => $returnData
        ]);
        exit;
    }

?>