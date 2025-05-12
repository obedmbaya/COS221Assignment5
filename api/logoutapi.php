<?php

header("Content-Type", "application/json");
require_once "config.php";


//user did not signup with correct method
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(401);
    echo json_encode([
        "status" => "failed",
        "data" => "Unauthorized request method"
        ]);
    die();
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data['type'] === "Logout") {
    if (empty($data['api_key'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "failed", 
            "data" => "All fields must be filled in before loging out."
        ]);
        exit();
    }

    $api_key = htmlspecialchars($data["api_key"]);

    $query = "SELECT * FROM users WHERE api_key = ?";
    $stmt = $database->prepare($query);
    $stmt->bind_param("s", $api_key);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $usr = $result->fetch_assoc();
    
    $stmt->close();

    if ($usr) {

        session_start();
        session_destroy();
        
        echo json_encode([
            "status" => "success",
            "timestamp" => round(microtime(true) * 1000),
            "data" => "User logged out successfully"
        ]);
    } 
    
    else {
        http_response_code(401);
        echo json_encode([
            "status" => "failed",
            "data" => "Invalid API key"
        ]);
    }
}