<?php

/*
API Endpoint Usage Guide


Logout API (logoutapi.php): (POST)

  - Request method: POST
  - Request JSON body:
    {
      "type": "Logout",
      "api_key": "user_api_key_string"
    }

  - Success Response (HTTP 200):
    {
      "status": "success",
      "timestamp": number,
      "data": "User logged out successfully"
    }

  - Failure Responses:
    - 400: Missing API key or invalid request type
    - 401: Invalid API key
    - 405: Unauthorized request method

*/

header("Content-Type: application/json");
require_once "config.php";

// User did not use correct request method
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        "status" => "failed",
        "data" => "Unauthorized request method"
    ]);
    exit();
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data['type'] === "Logout") {
    if (empty($data['api_key'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "failed", 
            "data" => "API key must be provided before logging out."
        ]);
        exit();
    }

    $api_key = $data["api_key"];

    // Check if API key exists (only need to verify existence)
    $query = "SELECT 1 FROM users WHERE api_key = ? LIMIT 1";
    $stmt = $database->prepare($query);
    $stmt->bind_param("s", $api_key);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $api_key_exists = $result->num_rows > 0;
    $stmt->close();

    if ($api_key_exists) {
        // API key is valid, send success response
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "timestamp" => round(microtime(true) * 1000),
            "data" => "User logged out successfully"
        ]);
        exit();
    } else {
        http_response_code(401);
        echo json_encode([
            "status" => "failed",
            "data" => "Invalid API key"
        ]);
        exit();
    }
} else {
    http_response_code(400);
    echo json_encode([
        "status" => "failed",
        "data" => "Invalid request type"
    ]);
    exit();
}