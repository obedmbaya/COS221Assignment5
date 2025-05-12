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

if ($data['type'] === "Login") {

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


$regexEmail = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/i";


$errors = array();


if (!preg_match($regexEmail, $email)) {
    array_push($errors, "Please enter a valid email address (e.g. user@example.com).");
}


if (!empty($errors)) {
    
    http_response_code(400);
    echo json_encode([
        "status" => "failed",
        "data" => $errors
    ]);
    exit();
}


$query = "SELECT * FROM users WHERE email = ?;";
$stmt_email = $database->prepare($query);
$stmt_email->bind_param("s",$data['email']);
$stmt_email->execute();
$result = $stmt_email->get_result();
$usr = $result->fetch_assoc();

if (!$usr) {
    http_response_code(404);

    echo json_encode([
        "status" => "failed",
        "data" => "user does not exist"
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


}

else {
    http_response_code(400);
    echo json_encode([
        "status" => "failed",
        "data" => "Invalid request type."
    ]);
    exit();
}
