<?php
require_once "config.php";

function handleLogin($data) {
    $database = Database::instance()->getConnection();
    if (empty($data['email']) || empty($data['password'])) {
        
        apiResponse("failed", "All fields must be filled in before logging in.", 400);
    }

    $email = $data["email"];
    $password = $data["password"];
    $regexEmail = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/i";

    if (!preg_match($regexEmail, $email)) {

        apiResponse("failed", "Please enter a valid email address (e.g. user@example.com).", 400);
    }

    $query = "SELECT * FROM User WHERE email = ?;";
    $stmt_email = $database->prepare($query);
    $stmt_email->bind_param("s", $email);
    $stmt_email->execute();
    $result = $stmt_email->get_result();
    $user = $result->fetch_assoc();

    $stmt_email->close();

    if (!$user) {
        
        apiResponse("failed", "User does not exist.", 404);
    }

    $inputHash = hash("sha256", $user["Salt"] . $password);
    if ($user['Password'] !== $inputHash) {
        apiResponse("failed", "Invalid credentials.", 401);
    }


    apiResponse("success", ["apikey" => $user['ApiKey']]);
    
} 

function handleRemoveUser($data) { 
    $database = Database::instance()->getConnection();
    if (empty($data['api_key']) || empty($data['remove_email'])) {

        apiResponse("failed", "API key and email must be provided to remove a user.", 400);
    }

    $email = $data['remove_email'];
    $ApiKey = $data['api_key'];

    // Validate email format
    $regexEmail = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/i";
    if (!preg_match($regexEmail, $email)) {
        apiResponse("failed", "Please enter a valid email address to remove.", 400);
    }

    $queryAPI = "SELECT Email FROM User WHERE ApiKey = ? AND UserType = 'Admin';";
    $stmt_API = $database->prepare($queryAPI);
    $stmt_API->bind_param("s", $ApiKey);
    $stmt_API->execute();
    $result_API = $stmt_API->get_result();
    $adminUser = $result_API->fetch_assoc();

    $stmt_API->close();

    if (!$adminUser) {
        
        apiResponse("failed", "Unauthorized, User must be a Admin to process this request.", 401);
    }

    // Check if admin is trying to remove themselves
    if ($adminUser['Email'] === $email) {
        
        apiResponse("failed", "Unauthorized Request, Admin account cannot remove itself.", 401);
    }
    

    $query = "SELECT 1 FROM User WHERE email = ?;";
    $stmt_email = $database->prepare($query);
    $stmt_email->bind_param("s", $email);
    $stmt_email->execute();
    $result = $stmt_email->get_result();

    $stmt_email->close();

    if ($result->num_rows === 0) {
        
        apiResponse("failed", "User does not exist.", 404);
    }



    $queryDelete = "DELETE FROM User WHERE email = ?";
    $stmtDelete = $database->prepare($queryDelete);
    $stmtDelete->bind_param("s", $email);
    
    if ($stmtDelete->execute()) {

        $stmtDelete->close();
        apiResponse("success", "User has been removed.");

    }

    else {

       $stmtDelete->close();
      apiResponse("failed", "Failed to delete.", 500);
    }


}

function handleEditUser($data) { 
    $database = Database::instance()->getConnection();
  
  if (empty($data['api_key']) || empty($data['edit_email'])) {

      apiResponse("failed", "API key and email must be provided to edit a user.", 400);
  }

  $email = $data['edit_email'];
  $ApiKey = $data['api_key'];

  $regexEmail = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/i";
  if (!preg_match($regexEmail, $email)) {
      apiResponse("failed", "Please enter a valid email address.", 400);
  }

  $queryAPI = "SELECT * FROM User WHERE ApiKey = ? AND UserType = 'Admin';";
  $stmt_API = $database->prepare($queryAPI);
  $stmt_API->bind_param("s", $ApiKey);
  $stmt_API->execute();
  $result_API = $stmt_API->get_result();
  $userAPI = $result_API->fetch_assoc();

  $stmt_API->close();

  if (!$userAPI) {

      apiResponse("failed", "Unauthorized, User must be an Admin to process this request", 401);
  }


  $query = "SELECT * FROM User WHERE email = ?;";
  $stmt_email = $database->prepare($query);
  $stmt_email->bind_param("s", $email);
  $stmt_email->execute();
  $result = $stmt_email->get_result();
  $user = $result->fetch_assoc();
  $stmt_email->close();
  if (!$user) {

      apiResponse("failed", "User does not exist", 404);
      
  }


  $queryRetail = "SELECT * FROM Retailer WHERE Email = ?;";
  $stmt_retail = $database->prepare($queryRetail);
  $stmt_retail->bind_param("s", $email);
  $stmt_retail->execute();
  $resultRetail = $stmt_retail->get_result();
  $userRetail = $resultRetail->fetch_assoc();
  $stmt_retail->close();
  if ($userRetail) {
      apiResponse("failed", "User is already a Retailer", 404);
      
  }


  $allowedFields = [
    "RetailerName", "SiteReference"
  ];

  $updates = array_intersect_key($data["updates"], array_flip($allowedFields));

  if (empty($updates)) {
      apiResponse("error", "No valid fields to update", 400);
  }

  $updateQuery = "UPDATE User SET UserType = 'Retailer' WHERE Email = ?;";
  $updateStmt = $database->prepare($updateQuery);
  $updateStmt->bind_param("s", $email);
  $updateStmt->execute();


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
      apiResponse("success", "Retailer Added Succesfully");
   } 

  else {
      $stmtRetailer->close();
      apiResponse("failed", "Failed to add Retailer", 500);
    }



}

function handleSignup($data) {
    $database = Database::instance()->getConnection();
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

        apiResponse("failed", "All fields must be filled in before signing up.", 400); 
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

        apiResponse("failed", $errors, 400);
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

        apiResponse("failed", "Unsuccessful, " . $email . " is already taken.", 400);
    }

    // No errors, proceed to registering the user
    $query = "INSERT INTO User(`FirstName`, `LastName`, `Email`, `Password`, `Salt`, `ApiKey`, `UserType`) VALUES (?, ?, ?, ?, ?, ?, 'Standard');";
    $stmt = $database->prepare($query);
    if (!$stmt) {
        $stmt->close();
        apiResponse("failed", "Database error: " . $database->error, 500);
    }
    
    $salt = bin2hex(random_bytes(16));
    $hash = hash("sha256", $salt . $password);
    $api_key = apiChecker(randomizeString(32), $database);
    
    $stmt->bind_param("ssssss", $firstName, $surname, $email, $hash, $salt, $api_key);
    $stmt->execute();
    $stmt->close();


    apiResponse("success", ["apikey" => $api_key]);

} 

function handleLogOut($data) {
    $database = Database::instance()->getConnection();
    if (empty($data["api_key"])) {
        apiResponse("failed", "API key must be provided before logging out.", 400);
    }

    $api_key = $data["api_key"];

    // Check if API key exists (only need to verify existence)
    $query = "SELECT 1 FROM User WHERE api_key = ? LIMIT 1";
    $stmt = $database->prepare($query);
    $stmt->bind_param("s", $api_key);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $api_key_exists = $result->num_rows > 0;
    $stmt->close();

    if ($api_key_exists) {
        // API key is valid, send success response
        apiResponse("success", "User logged out successfully.");
    } else {

        apiResponse("failed", "Invalid API key.", 401);
    }
} 



function apiResponse($status, $data, $code = 200)
{
    http_response_code($code);
    echo json_encode([
        "status" => $status,
        "data" => $data
    ]);
    exit();
}

function randomizeString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function apiChecker($currapi, $database) {
    $database = Database::instance()->getConnection();
    $query_api = "SELECT 1 FROM User WHERE ApiKey = ? LIMIT 1;";
    $stmt_api = $database->prepare($query_api);
    $stmt_api->bind_param("s", $currapi);
    $stmt_api->execute();
    
    $result = $stmt_api->get_result();
    $exists = $result->num_rows > 0;
    
    $stmt_api->close();
    
    if ($exists) {
        return apiChecker(randomizeString(32), $database);
    }

    return $currapi;
}