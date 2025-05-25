<?php

header("Content-Type: application/json");
require_once "config.php";

function sendResponse($status, $data, $code = 200)
{
    http_response_code($code);
    echo json_encode([
        "status" => $status,
        "timestamp" => time(),
        "data" => $data
    ]);
    exit();
}

// User did not signup with correct method
if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    sendResponse("failed", "Unauthorized request method", 405);
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data === null) {
    sendResponse("failed", "Invalid JSON format", 400);
}

if ($data['type'] === "Login") {
    if (empty($data['email']) || empty($data['password'])) {
        
        sendResponse("failed", "All fields must be filled in before logging in.", 400);
    }

    $email = $data["email"];
    $password = $data["password"];
    $regexEmail = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/i";

    if (!preg_match($regexEmail, $email)) {

        sendResponse("failed", "Please enter a valid email address (e.g. user@example.com).", 400);
    }

    $query = "SELECT * FROM User WHERE email = ?;";
    $stmt_email = $database->prepare($query);
    $stmt_email->bind_param("s", $email);
    $stmt_email->execute();
    $result = $stmt_email->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
 
        sendResponse("failed", "User does not exist.", 404);
    }

    $inputHash = hash("sha256", $user["Salt"] . $password);
    if ($user['Password'] !== $inputHash) {

        sendResponse("failed", "Invalid credentials.", 401);
    }


    sendResponse("success", ["apikey" => $user['ApiKey']]);
    
} 

else {

    sendResponse("failed", "Invalid request type.",400);
}
