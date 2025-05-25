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


function getRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function checkAPI($currapi, $database) {
    $query_api = "SELECT 1 FROM User WHERE ApiKey = ? LIMIT 1;";
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

    sendResponse("failed", "Unauthorized request method.", 405);
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data === null) {
    sendResponse("failed", "Invalid JSON format", 400);
}

// Correct type
if ($data["type"] === "Register") {
    
    $firstName = $data["name"];
    $surname = $data["surname"];
    $email = $data["email"];
    $password = $data["password"];

    $regexName = "/^[a-zA-Z]+$/";
    $regexSymbol = "/[^a-zA-Z0-9]+/";
    $regexNum = "/[0-9]+/";
    $regexHigh = "/[A-Z]+/";
    $regexlow = "/[a-z]+/";
    $regexEmail = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/i";

    if (empty($firstName) || empty($surname) || empty($email) || empty($password)) {

        sendResponse("failed", "All fields must be filled in before signing up.", 400); 
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

        sendResponse("failed", $errors, 400);
    }

    // Check if the user's email is in the database already
    $emailquery = "SELECT 1 FROM User WHERE email = ? LIMIT 1";
    $stmt_email = $database->prepare($emailquery);
    $stmt_email->bind_param("s", $email);
    $stmt_email->execute();
    
    $result = $stmt_email->get_result();
    $email_exists = $result->num_rows > 0;
    $stmt_email->close();
    
    if ($email_exists) {

        sendResponse("failed", "Unsuccessful, " . $email . " is already taken.", 400);
    }

    // No errors, proceed to registering the user
    $query = "INSERT INTO User(`FirstName`, `LastName`, `Email`, `Password`, `Salt`, `ApiKey`, `UserType`) VALUES (?, ?, ?, ?, ?, ?, 'Standard');";
    $stmt = $database->prepare($query);
    if (!$stmt) {

        sendResponse("failed", "Database error: " . $database->error, 500);
    }
    
    $salt = bin2hex(random_bytes(16));
    $hash = hash("sha256", $salt . $password);
    $api_key = checkAPI(getRandomString(32), $database);
    
    $stmt->bind_param("ssssss", $firstName, $surname, $email, $hash, $salt, $api_key);
    $stmt->execute();
    $stmt->close();


    sendResponse("success", ["apikey" => $api_key]);

} 

else {

    sendResponse("failed", "Incorrect type", 400);
}

