<?php

header("Content-Type", "application/json");
require_once "config.php";

function getRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function checkAPI($currapi, $database) {
    $query_api = "SELECT api_key FROM users WHERE api_key = ?;";
    $stmt_api = $database->prepare($query_api);
    $stmt_api->bind_param("s", $currapi);
    $stmt_api->execute();

    $result = $stmt_api->get_result();
    $result_api = $result->fetch_assoc();
    
    $stmt_api->close();
    
    if ($result_api) {
        return checkAPI(getRandomString(32), $database);
    }

    return $currapi;
}

function matchAPI($currapi, $database) {
    $queryAPI = "SELECT api_key FROM users WHERE api_key = ?;";
    $stmt_api = $database->prepare($queryAPI);
    $stmt_api->bind_param("s", $currapi);
    $stmt_api->execute();

    $result = $stmt_api->get_result();
    $result_api = $result->fetch_assoc();
    
    $stmt_api->close();

    if ($result_api) {
        return true;
    }

    return false;
}


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
    
    if (strlen($password) > 8) {
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
    }

    else {
        array_push($errors, "Password must be longer than 8 characters");
    }

    if (!empty($errors)) {
        
        http_response_code(400);
        echo json_encode([
            "status" => "failed",
            "data" => $errors
        ]);
        exit();
    }

    //check if the users email is in the databse already

    $emailquery = "SELECT email FROM users WHERE email = ?";
    $stmt_email = $database->prepare($emailquery);
    $stmt_email->bind_param("s", $email);
    $stmt_email->execute(); //if false then no email is found which is good
    //echo var_dump($results_email);

    $result = $stmt_email->get_result();
    $result_email = $result->fetch_assoc();

    
    if ($result_email) {
        http_response_code(409);
        
        echo json_encode([
            "status" => "failed",
            "message" => "Unsuccessful, " . htmlspecialchars($email) . " is already taken."
        ]);

        exit();
    }
    

    $stmt_email->close();
    

    //no errors we proceed to registering the user

    
    $query = "INSERT INTO users(`name`, `surname`, `email`, `password_hash`, `user_type`, `api_key`, `salt`) VALUES (?, ?, ?, ?, ?, ?, ?);";
    $stmt = $database->prepare($query);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode([
            "status" => "failed",
            "data" => "Database error: " . $database->error
        ]);
        die();
    }
    
    $salt = bin2hex(random_bytes(16));
    $dataToHash = $salt . $password;
    $hash = hash("sha256", $dataToHash);

    //32 bit API-KEY && also checks if its already in databse
    $api_key = checkAPI(getRandomString(32), $database);

    
    $stmt->bind_param("sssssss", $firstName, $surname, $email, $hash, $userType, $api_key, $salt);
    $stmt->execute();

    $stmt->close();
    $database->close();

    
    

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "timestamp" => round(microtime(true) * 1000),
        "data" => [
            "apikey" => $api_key
        ]
        ]);
    die();

}

else {
    http_response_code(401);
    echo json_encode([
        "status" => "failed",
        "data" => "Incorrect type"
        ]);
    die();
}

