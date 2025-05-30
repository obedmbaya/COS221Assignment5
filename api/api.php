<?php
require_once "config.php";
require_once "endpointsauthentication.php";
require_once "endpoints.php";


$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data === null) {
    sendResponse("failed", "Invalid JSON format", 400);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $data["type"] ?? "";

    switch ($type) {
        case "Signup":
            handleSignup($data);
            break;
        case "Login":
            handleLogin($data); 
            break;

        case "Logout":
            handleLogOut($data);
            break;
        case "removeUser":
            handleRemoveUser($data);
            break;
        case "editUser":
            handleEditUser($data);
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
         case "editProductAdmin":
            editProductAdmin($data);
            break;
        case "deleteProduct":
            deleteProduct($data);
            break;
        case "deleteProductAdmin":
            deleteProductAdmin($data);
            break;
        case "ViewProduct":
            handleViewProduct($data);
            break;
        case "compare":
            compare($data);
            break;
        case "GetTopRated":
            handleTopRated($data);
            break;
        case "GetRetailerRatings":
            handleGetRetailerRatingsByApikey($data);
            break;
        case "GetRetailerProducts":
            handleGetRetailerProducts($data);
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
        case "LoadUsers":
            handleGetUsers($data);
            break;
        case "editInfo":
            handleEditInfo($data);
            break;
        case "GetRetailerById":
            handleGetRetailerById($data);
            break;
        
        case "getUserReviews":
            getUserReviews($data);
            break;
        case "getAllBrands":
            getAllBrands();
            break;
        case "getAllCategories":
            getAllCategories();
            break;
        case "handleCountUsers":
            handleCountUsers($data);
            break;

        case "handleCountRetailers":
            handleCountRetailers($data);
            break;
        case "handleCountProducts":
            handleCountProducts($data);
            break;

        case "handleCountReviews":
            handleCountReviews($data);
            break;
        case "getUserDashboard":
            getUserDashboard($data);
            break;

        case "updateUserProfile":
            updateUserProfile($data);
            break;
        
        default:
            sendResponse("failed", "Unknown or missing type", 400);
    }
} 
