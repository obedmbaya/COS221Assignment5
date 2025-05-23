<?php
<?php

require_once("config.php");

// --- SEARCH ENDPOINTS ---
function search($data) {
    $conn = Database::instance()->getConnection();

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

function editProduct($data) {
    if (empty($data["ProductID"]) ||
        empty($data["ProductName"]) ||
        empty($data["Description"]) ||
        empty($data["Brand"]) ||
        empty($data["IMG_Reference"])) {
        sendResponse("error", "Missing required fields", 400);
        return;
    }

    $conn = Database::instance()->getConnection();
    $query = "UPDATE Product SET ProductName = ?, Description = ?, Brand = ?, IMG_Reference = ? WHERE ProductID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $data["ProductName"], $data["Description"], $data["Brand"], $data["IMG_Reference"], $data["ProductID"]);
    $result = $stmt->execute();
    $stmt->close();

    if ($result) {
        sendResponse("success", "Product successfully updated", 200);
    } else {
        sendResponse("error", "Failed to update product", 500);
    }
}

function deleteProduct($data) {
    if (empty($data["ProductID"])) {
        sendResponse("error", "Missing ProductID", 400);
        return;
    }

    $conn = Database::instance()->getConnection();
    $query = "DELETE FROM Product WHERE ProductID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $data["ProductID"]);
    $result = $stmt->execute();
    $stmt->close();

    if ($result) {
        sendResponse("success", "Product successfully deleted", 200);
    } else {
        sendResponse("error", "Failed to delete product", 500);
    }
}

function viewProduct($data) {
    if (empty($data["ProductID"])) {
        sendResponse("error", "Missing ProductID", 400);
        return;
    }

    $conn = Database::instance()->getConnection();
    $product_id = $data["ProductID"];
    $query = "SELECT p.ProductID, p.ProductName, p.Description, p.Brand, pp.Price, r.RetailerName
              FROM Product p
              JOIN ProductPrice pp ON p.ProductID = pp.ProductID
              JOIN Retailer r ON pp.RetailerID = r.RetailerID
              WHERE p.ProductID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmtResult = $stmt->get_result();
    $product = $stmtResult->fetch_assoc();
    $stmt->close();

    if ($product) {
        sendResponse("success", $product, 200);
    } else {
        sendResponse("error", "Product not found", 404);
    }
}

// --- CONTACT ENDPOINT ---
function saveContacts($data) {
    $db = Database::instance()->getConnection();

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
    $conn = Database::instance()->getConnection();
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
    $database = Database::instance()->getConnection();

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

// --- RESPONSE UTILITY ---
function sendResponse($status, $data, $httpCode = 200) {
    http_response_code($httpCode);
    echo json_encode([
        "status" => $status,
        "data" => $data
    ]);
    exit;
}

?>