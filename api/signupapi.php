<?php

/*
API Endpoint Usage Guide


Register API (signupapi.php): (POST)

  - Request method: POST
  - Request JSON body:
    {
      "type": "Register",
      "name": "FirstName",
      "surname": "LastName",
      "email": "user@example.com",
      "password": "StrongPassword1!",
      "user_type": "TypeOfUser"    // e.g., "admin", "user"
    }

  - Success Response (HTTP 200):
    {
      "status": "success",
      "timestamp": number,
      "data": {
        "apikey": "newly_generated_api_key_string"
      }
    }

  - Failure Responses:
    - 400: Missing fields or validation errors (returns array of errors)
    - 409: Email already registered
    - 405: Unauthorized request method
    - 400: Incorrect request type

---
*/

header("Content-Type: application/json");
require_once "config.php";

function getRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function checkAPI($currapi, $database) {
    $query_api = "SELECT 1 FROM users WHERE api_key = ? LIMIT 1;";
    $stmt_api = $database->prepare($query_api);
    $stmt_api->bind_param("s", $currapi);
    $stmt_api->execute();
    
    $result = $stmt_api->get_result();
    $exists = $result->num_rows > 0;
    
    $stmt_api->close();
    
    if ($exists) {
        return checkAPI(getRandomString(32), $database);
    }

    return $currapi;
}

// User did not signup with correct method
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

// Correct type
if ($data["type"] === "Register") {
    
    $firstName = $data["name"];
    $surname = $data["surname"];
    $email = $data["email"];
    $password = $data["password"];
    $userType = $data["user_type"];

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

    $errors = array();
    $nameCleaned = preg_replace('/\s+/', '', $firstName);
    $surnameCleaned = preg_replace('/\s+/', '', $surname);
    
    if (!preg_match($regexName, $nameCleaned)) {
        array_push($errors, "Name must contain letters only.");
    }

    if (!preg_match($regexName, $surnameCleaned)) {
        array_push($errors, "Surname must contain letters only.");
    }

    if (!preg_match($regexEmail, $email)) {
        array_push($errors, "Please enter a valid email address (e.g. user@example.com).");
    }
    
    if (strlen($password) >= 8) {
        if (!preg_match($regexHigh, $password)) {
            array_push($errors, "Password must contain at least one uppercase letter.");
        }

        if (!preg_match($regexlow, $password)) {
            array_push($errors, "Password must contain at least one lowercase letter.");
        }

        if (!preg_match($regexNum, $password)) {
            array_push($errors, "Password must contain at least one digit.");
        }

        if (!preg_match($regexSymbol, $password)) {
            array_push($errors, "Password needs at least one symbol (e.g. !@#$).");
        }
    } else {
        array_push($errors, "Password must be at least 8 characters");
    }

    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            "status" => "failed",
            "data" => $errors
        ]);
        exit();
    }

    // Check if the user's email is in the database already
    $emailquery = "SELECT 1 FROM users WHERE email = ? LIMIT 1";
    $stmt_email = $database->prepare($emailquery);
    $stmt_email->bind_param("s", $email);
    $stmt_email->execute();
    
    $result = $stmt_email->get_result();
    $email_exists = $result->num_rows > 0;
    $stmt_email->close();
    
    if ($email_exists) {
        http_response_code(409);
        echo json_encode([
            "status" => "failed",
            "data" => "Unsuccessful, " . $email . " is already taken."
        ]);
        exit();
    }

    // No errors, proceed to registering the user
    $query = "INSERT INTO users(`name`, `surname`, `email`, `password_hash`, `user_type`, `api_key`, `salt`) VALUES (?, ?, ?, ?, ?, ?, ?);";
    $stmt = $database->prepare($query);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode([
            "status" => "failed",
            "data" => "Database error: " . $database->error
        ]);
        exit();
    }
    
    $salt = bin2hex(random_bytes(16));
    $hash = hash("sha256", $salt . $password);
    $api_key = checkAPI(getRandomString(32), $database);
    
    $stmt->bind_param("sssssss", $firstName, $surname, $email, $hash, $userType, $api_key, $salt);
    $stmt->execute();
    $stmt->close();

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "timestamp" => round(microtime(true) * 1000),
        "data" => [
            "apikey" => $api_key
        ]
    ]);
    exit();
} else {
    http_response_code(400);
    echo json_encode([
        "status" => "failed",
        "data" => "Incorrect type"
    ]);
    exit();
}

