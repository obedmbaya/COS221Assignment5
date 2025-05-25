<?php
//We will delete this file and move everything into endpoints.php, I just want it separate to avoid conflicts on GitHub

    function handleTopRated(){

        

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