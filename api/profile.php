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

if ($data['type'] === "ProfileChange") {
    if (empty($data['email']) || empty($data['password'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "failed", 
            "data" => "All fields must be filled in before loging in."
        ]);
        exit();
    }

    $email = htmlspecialchars($data["email"]);
    $password = htmlspecialchars($data["password"]); 
    $newPassword = htmlspecialchars($data["new_password"]);
    $newPasswordConfirm = htmlspecialchars($data["new_password_confirm"]);

}