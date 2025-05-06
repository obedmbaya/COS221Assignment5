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

//correct type
if ($data["type"] === "Register") {
    $firstName = htmlspecialchars($data["name"]);
    $surname = htmlspecialchars($data["surname"]);
    $email = htmlspecialchars($data["email"]);
    $password = htmlspecialchars($data["password"]);
    $userType = htmlspecialchars($data["user_type"]);

    
    $regexName = "/^[a-zA-Z]+$/";
    $regexSymbol = "/[^a-zA-Z0-9]+/";
    $regexNum = "/[0-9]+/";
    $regexHigh = "/[A-Z]+/";
    $regexlow = "/[a-z]+/";
    $regexEmail = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/i";

    if (empty($firstName) || empty($surname) || empty($email) || empty($password) || empty($userType)) {
        http_response_code(400);
        echo json_encode([
            "status" => "failed", 
            "data" => "All fields must be filled in before signing up."
        ]);
        exit(); 
    }
}

else {
    http_response_code(401);
    echo json_encode([
        "status" => "failed",
        "data" => "Incorrect type"
        ]);
    die();
}

