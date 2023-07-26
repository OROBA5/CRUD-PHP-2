<?php

//code for sessions for the test of the website
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../config/Database.php';
include '../product/ProductType.php';
include '../product/Product.php';
include '../product/Book.php';
include '../product/Dvd.php';
include '../product/Furniture.php';

$database = new Database();
$conn = $database->getConnection();

$data = json_decode(file_get_contents("php://input"), true); // Ensure decoding as associative array

if (!empty($data) && !empty($data[0]['id'])) {
    $productIds = array_column($data, 'id'); // Extract IDs from the array
    $productTypes = array_column($data, 'product_type'); // Extract product types from the array

    // Delete the products based on the IDs and product types
    $deletedProductsCount = 0;
    foreach ($productIds as $index => $productId) {
        $productType = $productTypes[$index];
        
        // Add error handling and logging
        try {
            $result = ProductType::deleteProduct($conn, $productId, $productType);
            if ($result) {
                $deletedProductsCount++;
            }
        } catch (Exception $e) {
            // Log the error
            error_log('Error deleting product with ID ' . $productId . ': ' . $e->getMessage());
        }
    }

    if ($deletedProductsCount > 0) {
        http_response_code(200);
        echo json_encode(array("message" => "$deletedProductsCount item(s) were deleted."));
    } else {
        http_response_code(503);
        $errorMessage = "Unable to delete any items.";
        echo json_encode(array("message" => $errorMessage, "error" => error_get_last()["message"], "received_data" => $data));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to delete items. Data is incomplete.", "received_data" => $data));
}
?>
