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

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    sendResponse("failed", "Unauthorized request method", 405);
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Check if JSON is valid
if ($data === null) {
    sendResponse("failed", "Invalid JSON format", 400);
}

if ($data["type"] === "removeUser") { 

    if (empty($data['api_key']) || empty($data['remove_email'])) {

        sendResponse("failed", "API key and email must be provided to remove a user.", 400);
    }

    $email = $data['remove_email'];
    $ApiKey = $data['api_key'];

    // Validate email format
    $regexEmail = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/i";
    if (!preg_match($regexEmail, $email)) {
        sendResponse("failed", "Please enter a valid email address to remove.", 400);
    }

    $queryAPI = "SELECT Email FROM User WHERE ApiKey = ? AND UserType = 'Admin';";
    $stmt_API = $database->prepare($queryAPI);
    $stmt_API->bind_param("s", $ApiKey);
    $stmt_API->execute();
    $result_API = $stmt_API->get_result();
    $adminUser = $result_API->fetch_assoc();

    if (!$adminUser) {

        sendResponse("failed", "Unauthorized, User must be a Admin to process this request.", 401);
    }

    // Check if admin is trying to remove themselves
    if ($adminUser['Email'] === $email) {
        sendResponse("failed", "Unauthorized Request, Admin account cannot remove itself.", 401);
    }
    

    $query = "SELECT 1 FROM User WHERE email = ?;";
    $stmt_email = $database->prepare($query);
    $stmt_email->bind_param("s", $email);
    $stmt_email->execute();
    $result = $stmt_email->get_result();

    if ($result->num_rows === 0) {

        sendResponse("failed", "User does not exist.", 404);
    }





    $queryDelete = "DELETE FROM User WHERE email = ?";
    $stmtDelete = $database->prepare($queryDelete);
    $stmtDelete->bind_param("s", $email);
    
    if ($stmtDelete->execute()) {

        sendResponse("success", "User has been removed.");

    }

    else {

      sendResponse("failed", "Failed to delete.", 500);
    }


}
else {

    sendResponse("failed", "Incorrect type.", 400);
}