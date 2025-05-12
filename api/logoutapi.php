<?php

header("Content-Type", "application/json");
require_once "/config.php";


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
    if (empty($data['email'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "failed", 
            "data" => "All fields must be filled in before loging out."
        ]);
        exit();
    }

    $email = htmlspecialchars($data["email"]);

    $query = "SELECT * FROM users WHERE email = ?;";
    $stmt_pwd = $database->prepare($query);
    $stmt_pwd->execute([$data['email']]);
    $usr = $stmt_pwd->fetch(PDO::FETCH_ASSOC);

    if ($usr) {
        session_start();
        session_destroy();
        echo json_encode([
            "status" => "success",
            "data" => "User logged out successfully"
        ]);
    } else {
        http_response_code(401);
        echo json_encode([
            "status" => "failed",
            "data" => "User not found"
        ]);
    }
}