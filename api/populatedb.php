<?php

    require ("config.php");

    function populateDB($data){

        validate($data);

    }

    function validate($data){

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
        }

    }

?>