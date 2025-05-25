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

if ($data === null) {
    sendResponse("failed", "Invalid JSON format", 400);
}

if ($data["type"] === "editUser") { 

  
  if (empty($data['api_key']) || empty($data['edit_email'])) {

      sendResponse("failed", "API key and email must be provided to edit a user.", 400);
  }

  $email = $data['edit_email'];
  $ApiKey = $data['api_key'];

  $regexEmail = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/i";
  if (!preg_match($regexEmail, $email)) {
      sendResponse("failed", "Please enter a valid email address.", 400);
  }

  $queryAPI = "SELECT * FROM User WHERE ApiKey = ? AND UserType = 'Admin';";
  $stmt_API = $database->prepare($queryAPI);
  $stmt_API->bind_param("s", $ApiKey);
  $stmt_API->execute();
  $result_API = $stmt_API->get_result();
  $userAPI = $result_API->fetch_assoc();

  if (!$userAPI) {

      sendResponse("failed", "Unauthorized, User must be an Admin to process this request", 401);
  }


  $query = "SELECT * FROM User WHERE email = ?;";
  $stmt_email = $database->prepare($query);
  $stmt_email->bind_param("s", $email);
  $stmt_email->execute();
  $result = $stmt_email->get_result();
  $user = $result->fetch_assoc();

  if (!$user) {

      sendResponse("failed", "User does not exist", 404);
      
  }


  $allowedFields = [
    "RetailerName", "SiteReference"
  ];

  $updates = array_intersect_key($data["updates"], array_flip($allowedFields));

  if (empty($updates)) {
      sendResponse("error", "No valid fields to update", 400);
  }

  $updateQuery = "UPDATE User SET UserType = 'Retailer' WHERE Email = ?;";
  $updateStmt = $database->prepare($updateQuery);
  $updateStmt->bind_param("s", $email);
  $updateStmt->execute();

  $stmt_API->close();
  $stmt_email->close();
  $updateStmt->close();


  $setClauses = [];
  $params = [];
  $types = "";
  $inserts = [];

  foreach($updates as $field => $value) {
    $setClauses[] = $field;
    $params[] = $value;

    $types .= "s";
  }

  $setClauses[] = "Email";
  $params[] = $email;
  $types .= "s";

  $placeholders = array_fill(0, count($setClauses), '?');
  $queryRetailer = "INSERT INTO Retailer (" . implode(", ", $setClauses) . ") VALUES (" . implode(", ", $placeholders) . ");";

  $stmtRetailer = $database->prepare($queryRetailer);
  $stmtRetailer->bind_param($types, ...$params);


  if ($stmtRetailer->execute()) {
      $stmtRetailer->close();
      sendResponse("success", "Retailer Added Succesfully");
   } 

  else {
      $stmtRetailer->close();
      sendResponse("failed", "Failed to add Retailer", 500);
    }



}
else {

  sendResponse("failed", "Incorrect type", 400);
  
}