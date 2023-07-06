<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include '../config/Database.php';
include '../product/ProductType.php';
include '../product/Product.php';
include '../product/Book.php';
include '../product/Dvd.php';
include '../product/Furniture.php';

$data = json_decode(file_get_contents("php://input"));

// Log or print the received JSON data for testing
file_put_contents("received_data.log", print_r($data, true));


try {
    if (!empty($data->typeId) && !empty($data->sku) &&
        !empty($data->name) && !empty($data->price)) {

        $database = new Database();
        $db = $database->getConnection();

        // Prepare the product data
        $productData = [
            'typeId' => $data->typeId,
            'sku' => $data->sku,
            'name' => $data->name,
            'price' => $data->price,
            'product_type_id' => $data->product_type_id ?? null,
            'weight' => $data->weight ?? null,
            'size' => $data->size ?? null,
            'height' => $data->height ?? null,
            'width' => $data->width ?? null,
            'length' => $data->length ?? null
        ];

        // Create the product using the ProductType class
        $product = ProductType::createProduct($productData);

        http_response_code(201);
        echo json_encode(array("message" => "Product created successfully!"));
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array("message" => "Error creating product: " . $e->getMessage()));
}
