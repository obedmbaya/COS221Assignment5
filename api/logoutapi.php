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


// User did not use correct request method
if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    sendResponse("failed", "Unauthorized request method", 405);
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data === null) {
    sendResponse("failed", "Invalid JSON format", 400);
}

if ($data['type'] === "Logout") {
    if (empty($data["api_key"])) {
        sendResponse("failed", "API key must be provided before logging out.", 400);
    }

    $api_key = $data["api_key"];

    // Check if API key exists (only need to verify existence)
    $query = "SELECT 1 FROM User WHERE api_key = ? LIMIT 1";
    $stmt = $database->prepare($query);
    $stmt->bind_param("s", $api_key);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $api_key_exists = $result->num_rows > 0;
    $stmt->close();

    if ($api_key_exists) {
        // API key is valid, send success response
        sendResponse("success", "User logged out successfully.");
    } else {

        sendResponse("failed", "Invalid API key.", 401);
    }
} else {
    // Invalid request type
    sendResponse("failed", "Invalid request type", 400);
}