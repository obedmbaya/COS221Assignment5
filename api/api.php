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
            handleLogin($data); // Implement as needed
            break;
        case "Register":
            handleSearch($data); // Implement as needed
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

