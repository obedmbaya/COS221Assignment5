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


    if ($user['UserType'] === "Standard" || $user['UserType'] === "Admin") 
    {
        apiResponse("success", ["apikey" => $user['ApiKey'], "userType" => $user['UserType'], "email" => $user["Email"], "name" => $user["FirstName"], "surname" => $user["LastName"], "UserID" => $user["UserID"]]);
    }

    else {

        $query = "SELECT * FROM Retailer WHERE email = ?;";
        $stmt_retail = $database->prepare($query);
        $stmt_retail->bind_param("s", $email);
        $stmt_retail->execute();
        $result = $stmt_retail->get_result();
        $retailer = $result->fetch_assoc();

        $stmt_retail->close();
        
        apiResponse("success", ["apikey" => $user['ApiKey'], "userType" => $user['UserType'], "email" => $user["Email"], "RetailerName" => $retailer["RetailerName"], "UserID" => $user["UserID"]]);
    }
    
    
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
    $firstName = isset($data["name"]) ? $data['name'] : null;
    $surname = isset($data["surname"]) ? $data['surname'] : null;
    $email = isset($data["email"]) ? $data['email'] : null;
    $password = isset($data["password"]) ? $data['password'] : null;
    $userType = isset($data['user_type']) ? $data['user_type'] : null;
    $RetailerName = isset($data["RetailerName"]) ? $data["RetailerName"] : null;
    $SiteReference = isset($data["SiteReference"]) ? $data["SiteReference"] : null;

    $regexName = "/^[a-zA-Z]+$/";
    $regexSymbol = "/[^a-zA-Z0-9]+/";
    $regexNum = "/[0-9]+/";
    $regexHigh = "/[A-Z]+/";
    $regexlow = "/[a-z]+/";
    $regexEmail = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/i";

    if (empty($firstName) || empty($surname) || empty($email) || empty($password) || empty($userType)) {

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


    $salt = bin2hex(random_bytes(16));
    $hash = hash("sha256", $salt . $password);
    $api_key = apiChecker(randomizeString(32), $database);

    if ($userType === "Standard") {
        $query = "INSERT INTO User(`FirstName`, `LastName`, `Email`, `Password`, `Salt`, `ApiKey`, `UserType`) VALUES (?, ?, ?, ?, ?, ?, 'Standard');";
        $stmt = $database->prepare($query);
        if (!$stmt) {
            apiResponse("failed", "Database error: " . $database->error, 500);
        }
        
        $stmt->bind_param("ssssss", $firstName, $surname, $email, $hash, $salt, $api_key);
        $stmt->execute();
        $stmt->close();
    }
    else if($userType === "Retailer") {
        if (empty($RetailerName) || empty($SiteReference)) {
            apiResponse("failed", "Retailer name and site reference are required for Retailer users.", 400);
        }

   
        $query = "INSERT INTO User(`FirstName`, `LastName`, `Email`, `Password`, `Salt`, `ApiKey`, `UserType`) VALUES (?, ?, ?, ?, ?, ?, 'Retailer');";
        $stmt = $database->prepare($query);
        if (!$stmt) {
            apiResponse("failed", "Database error: " . $database->error, 500);
        }
        
        $stmt->bind_param("ssssss", $firstName, $surname, $email, $hash, $salt, $api_key);
        $stmt->execute();
        $stmt->close();

        // Insert retailer
        $queryRetailer = "INSERT INTO Retailer (`RetailerName`, `SiteReference`, `Email`) VALUES (?, ?, ?);";
        $stmtRetailer = $database->prepare($queryRetailer);
        if (!$stmtRetailer) {
            apiResponse("failed", "Database error: " . $database->error, 500);
        }
        
        $stmtRetailer->bind_param("sss", $RetailerName, $SiteReference, $email);
        if (!$stmtRetailer->execute()) {
            $stmtRetailer->close();
            apiResponse("failed", "Failed to add Retailer", 500);
        }
        $stmtRetailer->close();
    }
    else {
        apiResponse("failed", "Invalid user type.", 400);
    }

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

function handleGetUsers($data) {

    /*
        input 
        {
            "type" : "LoadUsers",
            "api_key" : api_key
        }

        output success
        {
            "status" : "success",
            "data" : [{}, {}, {}, ....]
        }
    */ 
    $database = Database::instance()->getConnection();
    if (empty($data['api_key'])) {

        apiResponse("failed", "API key must be provided to load users.", 400);
    }

    $ApiKey = $data['api_key'];

    $queryAPI = "SELECT 1 FROM User WHERE ApiKey = ? AND UserType = 'Admin' LIMIT 1;";
    $stmt_API = $database->prepare($queryAPI);
    $stmt_API->bind_param("s", $ApiKey);
    $stmt_API->execute();
    $result_API = $stmt_API->get_result();
    $adminUser = $result_API->fetch_assoc();

    $stmt_API->close();

    if (!$adminUser) {
        
        apiResponse("failed", "Unauthorized, User must be a Admin to process this request.", 401);
    }

    $query = "SELECT * FROM User";
    $stmtQuery = $database->prepare($query);
    $stmtQuery->execute();
    $resultUser = $stmtQuery->get_result();
    $Users = $resultUser->fetch_all(MYSQLI_ASSOC);

    apiResponse("success", $Users);

}