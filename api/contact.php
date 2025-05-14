<?php

// expect output  {
//     apikey: "someapikey",
//     type: "some type",
//     phone: "234 1232 1234",
//     email:"test@gmail.com"
//     message: "I have a problem"
// }



if ($data["type"] == "SaveContacts") {

    $email= $data["email"];
    $phone= $data["phone"];
    $apikey= $data["apikey"];
    $message= $data["message"];
    if(empty($email) || empty($phone) || empty($apikey) || empty($message)){
        http_response_code(401);
        echo json_encode(["status" => "error", "timestamp" => time(), "data" => "Missing required fields"]);
        exit;
    }
    //might need to adjust based on database structure
     $stmt = $db->prepare("SELECT Firstname, Surname FROM  users WHERE apikey=?");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["status" => "error", "timestamp" => time(), "data" => "Database error (prepare failed)"]);
        exit;
    }
    $stmt->bindParam(1, $apikey, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        http_response_code(401);
        echo json_encode(["status" => "error", "timestamp" => time(), "data" => "Invalid api key"]);
    } else {
      $firstname=  $result["Firstname"];
      $Surname = $result["Surname"];

      
        // might need to adjust based on database schema
        $insert = $db->prepare("INSERT INTO contacts (Firstname, Surname, email, phone, message) VALUES (?, ?, ?, ?, ?)");
        if (!$insert) {
            http_response_code(500);
            echo json_encode(["status" => "error", "timestamp" => time(), "data" => "Database error (prepare failed for insert)"]);
            exit;
        }
        $insert->bindParam(1, $firstname, PDO::PARAM_STR);
        $insert->bindParam(2, $Surname, PDO::PARAM_STR);
        $insert->bindParam(3, $email, PDO::PARAM_STR);
        $insert->bindParam(4, $phone, PDO::PARAM_STR);
        $insert->bindParam(5, $message, PDO::PARAM_STR);

        if ($insert->execute()) {
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "timestamp" => time(),
                "data" => [
                    "apikey" => $apikey
                ]
            ]);
        } else {
            error_log("Insert error: " . print_r($insert->errorInfo(), true));
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Failed to Save details"]);
        }

    }

}


?>