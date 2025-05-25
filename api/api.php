<?php
require_once(__DIR__ . "/api/config.php");
// require_once("populatedb.php");
// require_once("getProducts.php");
require_once "endpointsauthentication.php";
require_once "endpoints.php";

// Utility function for sending JSON responses
function sendResponse($status, $data, $httpCode = 200) {
    http_response_code($httpCode);
    echo json_encode([
        "status" => $status,
        "data" => $data
    ]);
    exit;
}

// Read JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data === null) {
    sendResponse("failed", "Invalid JSON format", 400);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $data["type"] ?? "";

    switch ($type) {
        case "Signup":
            registerUser($data);
            break;
        case "Login":
            // handleLogin($data); // Implement as needed
            break;
        case "Register":
            // handleSearch($data); // Implement as needed
            break;
        case "populateDB":
            populateDB($data);
            break;
        case "getAllProducts":
            getAllProducts();
            break;
        case "search":
            search($data);
            break;
        case "addProduct":
            addProduct($data);
            break;
        case "editProduct":
            editProduct($data);
            break;
        case "deleteProduct":
            deleteProduct($data);
            break;
        case "compare":
            compare($data);
            break;
        case "saveContacts":
            saveContacts($data);
            break;
        case "getReview":
            getReview($data);
            break;
        case "insertReview":
            insertReview($data);
            break;
        case "updateReview":
            updateReview($data);
            break;
        case "deleteReview":
            deleteReview($data);
            break;
        case "addProductPrice":
            addProductPrice($data);
            break;
        case "editProductPrice":
            editProductPrice($data);
            break;
        case "deleteProductPrice":
            deleteProductPrice($data);
            break;
        default:
            sendResponse("failed", "Unknown or missing type", 400);
    }
} 
// Add your new else-if blocks here
else if (isset($data["type"]) && $data["type"] === "Logout") {
    handleLogOut($data);
}
else if (isset($data["type"]) && $data["type"] === "removeUser") {
    handleRemoveUser($data);
}
else if (isset($data["type"]) && $data["type"] === "editUser") {
    handleEditUser($data);
}
else {
    sendResponse("failed", "Invalid request method", 400);
}

// --- SEARCH ENDPOINTS ---
function search($data) {
    $conn = Database::getInstance()->getConnection();

    $query = "SELECT p.ProductID, p.ProductName, p.Description, p.Brand, p.IMG_Reference, pp.Price, r.RetailerName
              FROM Product p
              JOIN ProductPrice pp ON p.ProductID = pp.ProductID
              JOIN Retailer r ON pp.RetailerID = r.RetailerID
              WHERE 1=1";
    $params = [];
    $types = "";

    if (isset($data["search"]) && is_array($data["search"])) {
        foreach ($data["search"] as $entry => $value) {
            if (in_array($entry, ["ProductName", "Brand", "Description"])) {
                if (!empty($data["fuzzy"])) {
                    $query .= " AND p.$entry LIKE ?";
                    $params[] = "%$value%";
                } else {
                    $query .= " AND p.$entry = ?";
                    $params[] = $value;
                }
                $types .= "s";
            }
        }
    }

    if (isset($data["sort"]) && in_array($data["sort"], ["Price", "ProductName"])) {
        $query .= " ORDER BY " . $data["sort"];
        if (isset($data["order"]) && in_array(strtoupper($data["order"]), ["ASC", "DESC"])) {
            $query .= " " . strtoupper($data["order"]);
        }
    }

    if (isset($data["limit"])) {
        $query .= " LIMIT ?";
        $params[] = intval($data["limit"]);
        $types .= "i";
    }

    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $stmtResult = $stmt->get_result();

    $products = [];
    while ($entries = $stmtResult->fetch_assoc()) {
        $products[] = $entries;
    }
    $stmt->close();

    sendResponse("success", $products, 200);
}

function getRetailerIdByEmail($email) {
    $conn = Database::getInstance()->getConnection();
    $stmt = $conn->prepare("SELECT RetailerID FROM Retailer WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row ? $row['RetailerID'] : null;
}

function isAdmin($apiKey) {
    $conn = Database::getInstance()->getConnection();
    $stmt = $conn->prepare("SELECT user_type FROM users WHERE api_key = ?");
    $stmt->bind_param("s", $apiKey);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return ($row && strtolower($row['user_type']) === 'admin');
}

function addProduct($data) {
    $conn = Database::getInstance()->getConnection();

    if (empty($data["apiKey"])) {
        sendResponse("error", "Missing API key", 403);
        return;
    }
    if (!isAdmin($data["apiKey"])) {
        sendResponse("error", "Unauthorized: Only admins can add products", 403);
        return;
    }

    if (
        empty($data["ProductName"]) ||
        empty($data["Description"]) ||
        empty($data["Brand"]) ||
        empty($data["IMG_Reference"]) ||
        empty($data["RetailerEmail"]) ||
        empty($data["Price"])
    ) {
        sendResponse("error", "Missing required fields", 400);
        return;
    }

    $retailerId = getRetailerIdByEmail($data["RetailerEmail"]);
    if (!$retailerId) {
        sendResponse("error", "Invalid retailer email", 403);
        return;
    }

    $query = "INSERT INTO Product (ProductName, Description, Brand, IMG_Reference) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $data["ProductName"], $data["Description"], $data["Brand"], $data["IMG_Reference"]);
    $result = $stmt->execute();
    $stmt->close();

    // getting ProductID by querying for the most recent matching product
    $query2 = "SELECT ProductID FROM Product WHERE ProductName = ? AND Description = ? AND Brand = ? AND IMG_Reference = ? ORDER BY ProductID DESC LIMIT 1";
    $stmt2 = $conn->prepare($query2);
    $stmt2->bind_param("ssss", $data["ProductName"], $data["Description"], $data["Brand"], $data["IMG_Reference"]);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $row = $result2->fetch_assoc();
    $stmt2->close();

    if ($row && $result) {
        $productId = $row['ProductID'];
        $stmt3 = $conn->prepare("INSERT INTO ProductPrice (ProductID, RetailerID, Price, URL) VALUES (?, ?, ?, ?)");
        $url = $data["URL"] ?? null;
        $stmt3->bind_param("iids", $productId, $retailerId, $data["Price"], $url);
        $stmt3->execute();
        $stmt3->close();
        sendResponse("success", "Product successfully added", 201);
    } else {
        sendResponse("error", "Failed to add product", 500);
    }
}

function editProduct($data) {
    $conn = Database::getInstance()->getConnection();

    if (empty($data["apiKey"])) {
        sendResponse("error", "Missing API key", 403);
        return;
    }
    if (!isAdmin($data["apiKey"])) {
        sendResponse("error", "Unauthorized: Only admins can edit products", 403);
        return;
    }

    if (
        empty($data["ProductID"]) ||
        empty($data["ProductName"]) ||
        empty($data["Description"]) ||
        empty($data["Brand"]) ||
        empty($data["IMG_Reference"]) ||
        empty($data["RetailerEmail"])
    ) {
        sendResponse("error", "Missing required fields", 400);
        return;
    }

    $retailerId = getRetailerIdByEmail($data["RetailerEmail"]);
    if (!$retailerId) {
        sendResponse("error", "Invalid retailer email", 403);
        return;
    }

    // checking ownership of product with retailer
    $stmt = $conn->prepare("SELECT 1 FROM ProductPrice WHERE ProductID = ? AND RetailerID = ?");
    $stmt->bind_param("ii", $data["ProductID"], $retailerId);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 0) {
        $stmt->close();
        sendResponse("error", "Unauthorized: You do not own this product", 403);
        return;
    }
    $stmt->close();

    // update product
    $query = "UPDATE Product SET ProductName = ?, Description = ?, Brand = ?, IMG_Reference = ? WHERE ProductID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $data["ProductName"], $data["Description"], $data["Brand"], $data["IMG_Reference"], $data["ProductID"]);
    $result = $stmt->execute();
    $stmt->close();

    // optionally update price and URL if provided, we can remove it if not needed
    if (!empty($data["Price"]) || !empty($data["URL"])) {
        $query2 = "UPDATE ProductPrice SET ";
        $params = [];
        $types = "";
        if (!empty($data["Price"])) {
            $query2 .= "Price = ?";
            $params[] = $data["Price"];
            $types .= "d"; // assuming Price is a decimal
        }
        if (!empty($data["URL"])) {
            if (!empty($params)) $query2 .= ", ";
            $query2 .= "URL = ?";
            $params[] = $data["URL"];
            $types .= "s";
        }
        $query2 .= " WHERE ProductID = ? AND RetailerID = ?";
        $params[] = $data["ProductID"];
        $params[] = $retailerId;
        $types .= "ii";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bind_param($types, ...$params);
        $stmt2->execute();
        $stmt2->close();
    }

    if ($result) {
        sendResponse("success", "Product successfully updated", 200);
    } else {
        sendResponse("error", "Failed to update product", 500);
    }
}

function deleteProduct($data) {
    $conn = Database::getInstance()->getConnection();

    if (empty($data["apiKey"])) {
        sendResponse("error", "Missing API key", 403);
        return;
    }
    if (!isAdmin($data["apiKey"])) {
        sendResponse("error", "Unauthorized: Only admins can delete products", 403);
        return;
    }

    if (empty($data["ProductID"]) || empty($data["RetailerEmail"])) {
        sendResponse("error", "Missing ProductID or RetailerEmail", 400);
        return;
    }

    $retailerId = getRetailerIdByEmail($data["RetailerEmail"]);
    if (!$retailerId) {
        sendResponse("error", "Invalid retailer email", 403);
        return;
    }

    // checking ownership of product with retailer
    $stmt = $conn->prepare("SELECT 1 FROM ProductPrice WHERE ProductID = ? AND RetailerID = ?");
    $stmt->bind_param("ii", $data["ProductID"], $retailerId);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 0) {
        $stmt->close();
        sendResponse("error", "Unauthorized: You do not own this product", 403);
        return;
    }
    $stmt->close();

    // delete from ProductPrice first. this way we can maintain referential integrity
    $stmt2 = $conn->prepare("DELETE FROM ProductPrice WHERE ProductID = ? AND RetailerID = ?");
    $stmt2->bind_param("ii", $data["ProductID"], $retailerId);
    $stmt2->execute();
    $stmt2->close();

    // delete product (if no other retailers are linked to it)
    $stmt3 = $conn->prepare("SELECT COUNT(*) as cnt FROM ProductPrice WHERE ProductID = ?");
    $stmt3->bind_param("i", $data["ProductID"]);
    $stmt3->execute();
    $result = $stmt3->get_result();
    $row = $result->fetch_assoc();
    $stmt3->close();

    if ($row['cnt'] == 0) {
        $stmt4 = $conn->prepare("DELETE FROM Product WHERE ProductID = ?");
        $stmt4->bind_param("i", $data["ProductID"]);
        $stmt4->execute();
        $stmt4->close();
    }

    sendResponse("success", "Product successfully deleted", 200);
}

//Get all prices for that product and all the other stockists
function compare($data){

    $conn = Database::getInstance()->getConnection();
    
    validateApikey($data);
    
    if (!isset($data["ProductID"])){
        header("HTTP/1.1 400 Bad Request");
        header("Content-type: application/json");
        echo json_encode([
            "status" => "failed",
            "data" => "Invalid API Key or no API Key was provided."
        ]);
        exit;
    }

    $stmt = $conn->prepare("SELECT p.ProductName, p.Description, p.Brand, p.IMG_reference,
                            r.RetailerName, r.RetailerReference,
                            pp.Price, pp.URL
                            FROM Product as p
                            JOIN ProductPrice as pp ON p.ProductID = pp.ProductID
                            JOIN Retailer as r ON pp.RetailerID = r.RetailerID
                            WHERE p.ProductID = ?");
    if (!$stmt){
        die("Prepare for compare query failed");
    }

    $stmt->bind_param("i", $data["ProductID"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    header("HTTP/1.1 200 OK");
    header("Content-type: application/json");
    echo json_encode([
        "status" => "success",
        "data" => $products
    ]);

}

function validateApikey($data){

    $output = true;

    if (!isset($data["apikey"])){

        $output = false;

    } else {

        $conn = Database::getInstance()->getConnection();
        $apikey = $data["apikey"];

        $stmt = $conn->prepare("SELECT 1
                        FROM  ApiKey
                        WHERE KeyValue = ?");
        if (!$stmt){
            die("Failed to prepare apikey validation query");
        }

        $stmt->bind_param("s", $apikey);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0){
            $output = false;
        }

        $stmt->close();

    }

    if (!$output){
        header("HTTP/1.1 401 Unauthorized");
        header("Content-type: application/json");
        echo json_encode([
            "status" => "failed",
            "data" => "Invalid API Key or no API Key was provided."
        ]);
        exit;
    }

}

// --- CONTACT ENDPOINT ---
function saveContacts($data) {
    $db = Database::getInstance()->getConnection();

    $email = $data["email"] ?? null;
    $phone = $data["phone"] ?? null;
    $apikey = $data["apikey"] ?? null;
    $message = $data["message"] ?? null;

    if (empty($email) || empty($phone) || empty($apikey) || empty($message)) {
        sendResponse("error", "Missing required fields", 401);
        return;
    }

    // Validate API key and get user info
    $stmt = $db->prepare("SELECT Firstname, Surname FROM users WHERE apikey=?");
    $stmt->bind_param("s", $apikey);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        sendResponse("error", "Invalid api key", 401);
        return;
    }

    $firstname = $user["Firstname"];
    $surname = $user["Surname"];

    $insert = $db->prepare("INSERT INTO contacts (Firstname, Surname, email, phone, message) VALUES (?, ?, ?, ?, ?)");
    $insert->bind_param("sssss", $firstname, $surname, $email, $phone, $message);

    if ($insert->execute()) {
        sendResponse("success", ["apikey" => $apikey], 200);
    } else {
        sendResponse("error", "Failed to Save details", 500);
    }
    $insert->close();
}

// --- POPULATE DB ENDPOINT ---
function populateDB($data) {
    $conn = Database::getInstance()->getConnection();
    $products = $data["products"] ?? [];

    foreach ($products as $product) {
        $name = $product['name'] ?? null;
        $description = $product['description'] ?? null;
        $brand = $product['brand'] ?? null;
        $img_reference = $product['img_reference'] ?? null;

        if ($name != null) {
            $stmt = $conn->prepare("INSERT INTO Product (ProductName, Description, Brand, IMG_Reference) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                sendResponse("error", "Prepare statement failed", 500);
                return;
            }
            $stmt->bind_param("ssss", $name, $description, $brand, $img_reference);
            $stmt->execute();
            $stmt->close();
        }
    }

    sendResponse("success", "Successfully added products", 200);
}

// --- SIGNUP ENDPOINT ---
function registerUser($data) {
    $database = Database::getInstance()->getConnection();

    $firstName = htmlspecialchars($data["name"] ?? "");
    $surname = htmlspecialchars($data["surname"] ?? "");
    $email = htmlspecialchars($data["email"] ?? "");
    $password = htmlspecialchars($data["password"] ?? "");
    $userType = htmlspecialchars($data["user_type"] ?? "");

    $regexName = "/^[a-zA-Z]+$/";
    $regexSymbol = "/[^a-zA-Z0-9]+/";
    $regexNum = "/[0-9]+/";
    $regexHigh = "/[A-Z]+/";
    $regexlow = "/[a-z]+/";
    $regexEmail = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/i";

    if (empty($firstName) || empty($surname) || empty($email) || empty($password) || empty($userType)) {
        sendResponse("failed", "All fields must be filled in before signing up.", 400);
        return;
    }

    $errors = [];
    $nameCleaned = preg_replace('/\s+/', '', $firstName);
    $surnameCleaned = preg_replace('/\s+/', '', $surname);
    if (!preg_match($regexName, $nameCleaned)) {
        $errors[] = "Name must contain letters only.";
    }
    if (!preg_match($regexName, $surnameCleaned)) {
        $errors[] = "Surname must contain letters only.";
    }
    if (!preg_match($regexEmail, $email)) {
        $errors[] = "Please enter a valid email address (e.g. user@example.com).";
    }
    if (strlen($password) > 8) {
        if (!preg_match($regexHigh, $password)) {
            $errors[] = "Password must contain at least one uppercase letter.";
        }
        if (!preg_match($regexlow, $password)) {
            $errors[] = "Password must contain at least one lowercase letter.";
        }
        if (!preg_match($regexNum, $password)) {
            $errors[] = "Password must contain at least one digit.";
        }
        if (!preg_match($regexSymbol, $password)) {
            $errors[] = "Password needs at least one symbol (e.g. !@#$).";
        }
    } else {
        $errors[] = "Password must be longer than 8 characters";
    }

    if (!empty($errors)) {
        sendResponse("failed", $errors, 400);
        return;
    }

    // Check if email exists
    $emailquery = "SELECT email FROM users WHERE email = ?";
    $stmt_email = $database->prepare($emailquery);
    $stmt_email->bind_param("s", $email);
    $stmt_email->execute();
    $result = $stmt_email->get_result();
    $result_email = $result->fetch_assoc();
    $stmt_email->close();

    if ($result_email) {
        sendResponse("failed", "Unsuccessful, " . htmlspecialchars($email) . " is already taken.", 409);
        return;
    }

    // Register user
    $query = "INSERT INTO users(`name`, `surname`, `email`, `password_hash`, `user_type`, `api_key`, `salt`) VALUES (?, ?, ?, ?, ?, ?, ?);";
    $stmt = $database->prepare($query);
    if (!$stmt) {
        sendResponse("failed", "Database error: " . $database->error, 500);
        return;
    }

    $salt = bin2hex(random_bytes(16));
    $dataToHash = $salt . $password;
    $hash = hash("sha256", $dataToHash);
    $api_key = checkAPI(getRandomString(32), $database);

    $stmt->bind_param("sssssss", $firstName, $surname, $email, $hash, $userType, $api_key, $salt);
    $stmt->execute();
    $stmt->close();

    sendResponse("success", ["apikey" => $api_key], 200);
}

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

// --- REVIEW ENDPOINT ---

function getReview($data){
    $conn = Database::getInstance()->getConnection();
    $product_id = $data["ProductID"];

    if (!$product_id){
        sendResponse("error", "ProductID is required", 400);
        return;
    }

    $stmt = $conn->prepare("SELECT * FROM Review WHERE ProductID = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $reviews = [];

    while($row = $result->fetch_assoc()){
        $reviews[] = $row;
    }

    $stmt->close();
    sendResponse("Success", $reviews, 200);
}

function insertReview($data){
    $conn = Database::getInstance()->getConnection();
    $product_id = $data["ProductID"];
    $user_id = $data["UserID"];
    $rating = $data["Rating"];
    $comment = $data["Comment"] ?? null;

    if ($product_id == null || $user_id == null || $rating == null){
        sendResponse("error", "ProductID, UserID and Rating are required", 400);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO Review (ProductID, UserID, Rating, Comment, ReviewDate) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiis", $product_id, $user_id, $rating, $comment);
    $result = $stmt->execute();

    if ($result){
        sendResponse("success", "Review added successfully", 201);
    }
    else{
        sendResponse("error", "Failed to add review", 500);
    }

    $stmt->close();
}

function updateReview($data){
    $conn = Database::getInstance()->getConnection();
    $review_id = $data["ReviewID"];
    $rating = $data["Rating"];
    $comment = $data["Comment"] ?? null;

    if (!$review_id || !$rating){
        sendResponse("error", "ReviewID and Rating are required", 400);
        return;
    }

    $stmt = $conn->prepare("UPDATE Review SET Rating = ?, Comment = ? WHERE ReviewID = ?");
    $stmt->bind_param("isi", $rating, $comment, $review_id);
    $result = $stmt->execute();

    if ($result){
        sendResponse("success", "Review updated successfully", 200);
    }
    else{
        sendResponse("error", "Failed to update review", 500);
    }

    $stmt->close();

}

function deleteReview($data){
    $conn = Database::getInstance()->getConnection();
    $review_id = $data["ReviewID"];

    if (!$review_id){
        sendResponse("error", "ReviewID is required", 400);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM Review WHERE ReviewID = ?");
    $stmt->bind_param("i", $review_id);
    $result = $stmt->execute();

    if ($result){
        sendResponse("success", "Review deleted successfully", 200);
    } else {
        sendResponse("error", "Failed to delete review", 500);
    }

    $stmt->close();
}

//---- PRODUCT PRICE -----
function addProductPrice($data){
    $conn = Database::getInstance()->getConnection();

    if (empty($data["ApiKey"])) {
        sendResponse("error", "Missing API key", 403);
        return;
    }
    if (!isAdmin($data["ApiKey"])) {
        sendResponse("error", "Unauthorized: Only admins can add product prices", 403);
        return;
    }

    $product_id = $data["ProductID"] ?? null;
    $retailer_id = $data["RetailerID"] ?? null;
    $price = $data["Price"] ?? null;
    $url = $data["URL"] ?? null;

    if (!$product_id || !$retailer_id || !$price) {
        sendResponse("error", "Missing required fields: ProductID, RetailerID, Price", 400);
        return;
    }

    // check if the product and the retailer exist
    $stmt = $conn->prepare("SELECT 1 FROM Product WHERE ProductID = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 0) {
        $stmt->close();
        sendResponse("error", "Invalid ProductID", 404);
        return;
    }

    $stmt = $conn->prepare("SELECT 1 FROM Retailer WHERE RetailerID = ?");
    $stmt->bind_param("i", $retailer_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 0) {
        $stmt->close();
        sendResponse("error", "Invalid RetailerID", 400);
        return;
    }

    // add product price
    $stmt = $conn->prepare("INSERT INTO ProductPrice (ProductID, RetailerID, Price, URL) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iids", $product_id, $retailer_id, $price, $url);
    if ($stmt->execute()) {
        $stmt->close();
        sendResponse("success", "Product price added successfully", 200);
    } else {
        $stmt->close();
        sendResponse("error", "Failed to add product price", 500);
    }

    $stmt->close();

}

function editProductPrice($data){
    $conn = Database::getInstance()->getConnection();

    if (empty($data["ApiKey"])) {
        sendResponse("error", "Missing API key", 400);
        return;
    }

    if (!isAdmin($data["ApiKey"])) {
        sendResponse("error", "Unauthorized: Only admins can edit product prices", 403);
        return;
    }

    $price_id = $data["PriceID"] ?? null;
    $price = $data["Price"] ?? null;
    $url = $data["URL"] ?? null;

    if (!$price_id || $price === null) {
        sendResponse("error", "Missing required fields: PriceID, Price", 400);
        return;
    }

    // determine if the price exists
    $stmt = $conn->prepare("SELECT 1 FROM ProductPrice WHERE PriceID = ?");
    $stmt->bind_param("i", $price_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 0) {
        $stmt->close();
        sendResponse("error", "Invalid PriceID", 404);
        return;
    }
    $stmt->close();

    // now we can update the price
    $query = "UPDATE ProductPrice SET Price = ?, URL = ? WHERE PriceID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("dsi", $price, $url, $price_id);
    if ($stmt->execute()) {
        $stmt->close();
        sendResponse("success", "Product price updated successfully", 200);
    } else {
        $stmt->close();
        sendResponse("error", "Failed to update product price", 500);
    }

    $stmt->close();
}

function deleteProductPrice($data){
    $conn = Database::getInstance()->getConnection();

    if (empty($data["ApiKey"])) {
        sendResponse("error", "Missing API key", 400);
        return;
    }

    if (!isAdmin($data["ApiKey"])) {
        sendResponse("error", "Unauthorized: Only admins can delete product prices", 403);
        return;
    }

    $price_id = $data["PriceID"] ?? null;

    if (!$price_id) {
        sendResponse("error", "Missing required field: PriceID", 400);
        return;
    }

    // determine if the price exists
    $stmt = $conn->prepare("SELECT 1 FROM ProductPrice WHERE PriceID = ?");
    $stmt->bind_param("i", $price_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 0) {
        $stmt->close();
        sendResponse("error", "Invalid PriceID", 404);
        return;
    }
    $stmt->close();

    // now we can delete the price
    $stmt = $conn->prepare("DELETE FROM ProductPrice WHERE PriceID = ?");
    $stmt->bind_param("i", $price_id);
    if ($stmt->execute()) {
        $stmt->close();
        sendResponse("success", "Product price deleted successfully", 200);
    } else {
        $stmt->close();
        sendResponse("error", "Failed to delete product price", 500);
    }
}