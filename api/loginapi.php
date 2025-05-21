<?php

/*
API Endpoint Usage Guide

1) Login API (loginapi.php): (POST)

  - Request method: POST
  - Request JSON body:
    {
      "type": "Login",
      "email": "user@example.com",
      "password": "your_password"
    }

  - Success Response (HTTP 200):
    {
      "status": "success",
      "timestamp": number,
      "data": {
        "apikey": "user_api_key_string"
      }
    }

  - Failure Responses:
    - 400: Missing fields, invalid email format
    - 401: Invalid credentials or unauthorized method
    - 404: User does not exist
    - 400: Invalid request type
*/

header("Content-Type: application/json");
require_once "config.php";

// User did not signup with correct method
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(401);
    echo json_encode([
        "status" => "failed",
        "data" => "Unauthorized request method"
    ]);
    exit();
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data['type'] === "Login") {
    if (empty($data['email']) || empty($data['password'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "failed", 
            "data" => "All fields must be filled in before logging in."
        ]);
        exit();
    }

    $email = $data["email"];
    $password = $data["password"];
    $regexEmail = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/i";

    if (!preg_match($regexEmail, $email)) {
        http_response_code(400);
        echo json_encode([
            "status" => "failed",
            "data" => ["Please enter a valid email address (e.g. user@example.com)."]
        ]);
        exit();
    }

    $query = "SELECT * FROM users WHERE email = ?;";
    $stmt_email = $database->prepare($query);
    $stmt_email->bind_param("s", $email);
    $stmt_email->execute();
    $result = $stmt_email->get_result();
    $usr = $result->fetch_assoc();

    if (!$usr) {
        http_response_code(404);
        echo json_encode([
            "status" => "failed",
            "data" => "User does not exist"
        ]);
        exit();
    }

    $inputHash = hash("sha256", $usr["salt"] . $password);
    if ($usr['password_hash'] !== $inputHash) {
        http_response_code(401);
        echo json_encode([
            "status" => "failed",
            "data" => "Invalid credentials."
        ]);
        exit();
    }

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "timestamp" => round(microtime(true) * 1000),
        "data" => [
            "apikey" => $usr['api_key'],
        ]
    ]);
    exit();
} else {
    http_response_code(400);
    echo json_encode([
        "status" => "failed",
        "data" => "Invalid request type."
    ]);
    exit();
}
