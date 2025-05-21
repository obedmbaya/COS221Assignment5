<?php function getAllProducts() {
    $conn = Database::instance()->getConnection();

    $query = "SELECT ProductID, ProductName, Description, Brand, IMG_Reference FROM Product";
    $result = $conn->query($query);

    if (!$result) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            "status" => "error",
            "timestamp" =>time(),
            "products" => []
        ]);
        exit;
    }

    $products = [];

    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode([
        "status" => "success",
        "timestamp" => time(),
        "products" => $products
    ]);
    exit;
}
?>