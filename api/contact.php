<?php

<<<<<<< HEAD
// expect output  {
//     apikey: "someapikey",
//     type: "some type",
//     phone: "234 1232 1234",
//     email:"test@gmail.com"
//     message: "I have a problem"
// }
=======
require 'config.php';

$db = Database::Instance()->getConnection(); 

if (!$db) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Database connection failed"
    ]);
    exit;
}


$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

if (!$data) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Invalid JSON input"
    ]);
    exit;
}
>>>>>>> 6350266d6af9e91bc50a7542357636a6a69d1dc6

if (isset($data["type"]) && $data["type"] === "SaveContacts") {

    // Sanitize inputs
    $email = isset($data["email"]) ? trim($data["email"]) : "";
    $phone = isset($data["phone"]) ? trim($data["phone"]) : "";
    $apikey = isset($data["apikey"]) ? trim($data["apikey"]) : "";
    $message = isset($data["message"]) ? trim($data["message"]) : "";

<<<<<<< HEAD


    $email= $data["email"];
    $phone= $data["phone"];
    $apikey= $data["apikey"];
    $message= $data["message"];
    if(empty($email) || empty($phone) || empty($apikey) || empty($message)){
        http_response_code(401);
        echo json_encode(["status" => "error", "timestamp" => time(), "data" => "Missing required fields"]);
=======
    if (empty($email) || empty($phone) || empty($apikey) || empty($message)) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "timestamp" => time(),
            "data" => "Missing required fields"
        ]);
>>>>>>> 6350266d6af9e91bc50a7542357636a6a69d1dc6
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "timestamp" => time(),
            "data" => "Invalid email format"
        ]);
        exit;
    }

    // Prepare statement to check API key
    $stmt = $db->prepare("SELECT Firstname, Surname FROM users WHERE apikey = ?");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "timestamp" => time(),
            "data" => "Database error (prepare failed)"
        ]);
        exit;
    }

    $stmt->bind_param("s", $apikey);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "timestamp" => time(),
            "data" => "Invalid API key"
        ]);
        $stmt->close();
        exit;
    }
    $stmt->close();

    $firstname = $user["Firstname"];
    $surname = $user["Surname"];

    // Prepare insert statement
    $insert = $db->prepare("INSERT INTO contacts (Firstname, Surname, email, phone, message) VALUES (?, ?, ?, ?, ?)");
    if (!$insert) {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "timestamp" => time(),
            "data" => "Database error (prepare failed for insert)"
        ]);
        exit;
    }

<<<<<<< HEAD


?>
=======
    $insert->bind_param("sssss", $firstname, $surname, $email, $phone, $message);

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
        error_log("Insert error: " . $insert->error);
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Failed to save details"
        ]);
    }

    $insert->close();

} else {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Invalid or missing type value"
    ]);
    exit;
}
?>
>>>>>>> 6350266d6af9e91bc50a7542357636a6a69d1dc6
