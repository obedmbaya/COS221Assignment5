<?php

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


?>