<?php

    // private
    function search($data){
        // Base SQL query to select product information with joins to related tables
        // 1=1 is used as a placeholder to easily add AND conditions later
        $query = "SELECT p.ProductID, p.ProductName, p.Description, p.Brand, p.IMG_Reference pp.Price, r.RetailerName
                  FROM Product p
                  JOIN ProductPrice pp ON p.ProductID = pp.ProductID
                  JOIN Retailer r ON pp.RetailerID = r.RetailerID
                  WHERE 1=1"; //1=1 "cleanly combine optional filters"
        
        $params = [];
        $types = ""; // String to hold parameter types (s=string, i=integer, etc)
        
        // Check if search criteria are provided and is an array. ie, check if Ange is in backend, where backend is an array
        if (isset($data["search"]) && is_array($data["search"])){
            foreach($data["search"] as $entry => $value){
                if (in_array($entry, ["ProductName", "Brand", "Description"])){ //Only allowing search functionality on these attributes, we can add more, later on if needed (prolly might have to)
                    // Check if fuzzy search is enabled 
                    if (!isset($data["fuzzy"]) || $data["fuzzy"] == true){
                        $query .= " AND p.$entry LIKE ?";
                        $params[] = "%$value$";
                    } else {
                        $query .= " AND p.$entry =?";
                        $params[] = $value;
                    }

                    $types .= "s";
                }
            }
        }

        // Add sorting if it is specified
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
        
        //An array to store the products after filtration
        $products = [];

        // Fetch each row and add to products array
        while ($entries = $stmtResult->fetch_assoc()){
            $products[] = $entries;
        }


        $stmt->close();
        $this->sendResponse("success", $products, 200);
    }

    function editProduct($data){
        if (empty($data["ProductID"]) ||
        empty($data["ProductName"]) ||
        empty($data["Description"]) ||
        empty($data["Brand"]) ||
        empty($data["IMG_Reference"])){
            $this->sendResponse("error", "Missing reqired fields", 400);
            return;
        }

        $query = "UPDATE Product SET ProductName = ?, 
                                    Description = ?, 
                                    Brand = ?, 
                                    IMG_Reference = ?, 
                                WHERE ProductID = ?";
        $stmt = $this->database->prepare($query);
        $stmt->bind_param("ssssi", $data["ProductName"], $data["Description"], $data["Brand"], $data["IMG_Reference"], $data["ProductID"]);
        $result = $stmt->execute();

        if ($result){
            $this->sendResponse("success", "Product successfully updated", 200);
        }
        else{
            $this->sendResponse("error", "Failed to update product", 500);
        }

        $stmt->close();
    }

    function deleteProduct($data){
        if (empty($data["ProductID"])){
            $this->sendResponse("error", "Missing ProductID", 400);
            return;
        }

        $query = "DELETE FROM Product WHERE ProductID = ?";
        $stmt = $this->database->prepare($query);
        $stmt->bind_param("i", $data["ProductID"]);
        $result = $stmt->execute();

        if ($result){
            $this-sendResponse("Success", "Product successfully deleted", 200);
        }
        else{
            $this->sendResponse("error", "Failed to delete product", 500);
        }

        $stmt->close();
    }

    function viewProduct($data){
        if (empty($data["ProductID"])){
            $this->sendResponse("error", "Missing ProductID", 400);
            return;
        }

        $product_id = $data["ProductID"];
        $query = "SELECT p.ProductID, p.ProductName, p.Description, p.Brand, pp.Price, r.RetailerName
                  FROM Product p
                  JOIN ProductPrice pp ON p.ProductID = pp.ProductID
                  JOIN Retailer r ON pp.RetailerID = r.RetailerID
                  WHERE p.ProductID = ?";
        $stmt = $this->database->prepare($query);
        $stmt->bind_param("i", "$product_id");
        $result = $stmt->execute();
        $stmtResult = $stmt->get_result();
        $product = $stmtResult->fetch_assoc();

        if ($product){
            $his->sendResponse("success", $product, 200);
        }
        else{
            $this->sendResponse("error", "Product not found", 404);
        }
    }

    function sendResponse($status, $data, $httpCode = 200){
        http_response_code($code);
        echo json_encode(
            [
                "status" => $status,
                "data" => $data
            ]
        );
    }


?>
