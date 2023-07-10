<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include '../config/Database.php';
include '../product/ProductType.php';
include '../product/Product.php';
include '../product/Book.php';
include '../product/Dvd.php';
include '../product/Furniture.php';

$database = new Database();
$conn = $database->getConnection();

$products = ProductType::readAllProducts($conn);

if (!empty($products)) {
    http_response_code(200);
    echo json_encode($products);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No products found."));
}