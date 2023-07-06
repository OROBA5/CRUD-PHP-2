<?php 

include './config/Database.php';
include './product/ProductType.php';
include './product/Product.php';
include './product/Book.php';
include './product/Dvd.php';
include './product/Furniture.php';

/* $book = new Book(null, 'addbook', 'addbook', 5.55, 1, 0.5);
$result = $book->create();

if ($result) {
    echo "Book created and inserted into the database.";
} else {
    echo "Failed to create book or insert into the database.";
}

$dvd = new DVD(null, 'adddvd', 'adddvd', 9.99, 2, '2.4 GB');
$result = $dvd->create();

if ($result) {
    echo "DVD created and inserted into the database.";
} else {
    echo "Failed to create DVD or insert into the database.";
}

$furniture = new Furniture(null, 'addfurniture', 'addfurniture', 99.99, 3, 100, 80, 120);
$result = $furniture->create();

if ($result) {
    echo "Furniture created and inserted into the database.";
} else {
    echo "Failed to create furniture or insert into the database.";
} */



/* $book = new Book(null, 'testlast', 'testlast', 19.99, 3, 1.5);

try {
    $product = ProductType::createProduct(3, $book->getSku(), $book->getName(), $book->getPrice(), $book->getWeight());
    echo "Product created successfully!";
} catch (Exception $e) {
    echo "Error creating product: " . $e->getMessage();
} */



/* // Simulate randomly received form parameters
$typeId = mt_rand(1, 3); // Random product type ID
$sku = 'sku' . mt_rand(1000, 9999); // Random SKU
$name = 'Product ' . mt_rand(100, 999); // Random name
$price = mt_rand(10, 100); // Random price
$weight = mt_rand(1, 10); // Random weight

try {
    $product = ProductType::createProduct($typeId, $sku, $name, $price, $weight);
    echo "Product created successfully!";
} catch (Exception $e) {
    echo "Error creating product: " . $e->getMessage();
} */




/* 
// Simulate random form input data
$randomType = rand(1, 3); // Random product type
$randomSKU = 'sku' . rand(100, 999); // Random SKU
$randomName = 'Product Example ' . rand(1, 100); // Random product name
$randomPrice = rand(10, 100) + (rand(0, 99) / 100); // Random price

// Random optional fields based on the product type
$randomFields = [];
switch ($randomType) {
    case 1: // DVD
        $randomFields['weight'] = rand(1, 10) + (rand(0, 99) / 100); // Random weight
        break;
    case 2: // Furniture
        $randomFields['size'] = 'Large'; // Random size
        break;
    case 3: // Book
        $randomFields['height'] = rand(10, 50) + (rand(0, 99) / 100); // Random height
        $randomFields['width'] = rand(10, 30) + (rand(0, 99) / 100); // Random width
        $randomFields['length'] = rand(20, 60) + (rand(0, 99) / 100); // Random length
        break;
}

// Merge random fields with required fields
$formData = array_merge([
    'typeId' => $randomType,
    'sku' => $randomSKU,
    'name' => $randomName,
    'price' => $randomPrice
], $randomFields);

try {
    // Prepare the arguments for createProduct
    $typeId = $formData['typeId'];
    $sku = $formData['sku'];
    $name = $formData['name'];
    $price = $formData['price'];

    // Optional fields
    $weight = $formData['weight'] ?? null;
    $size = $formData['size'] ?? null;
    $height = $formData['height'] ?? null;
    $width = $formData['width'] ?? null;
    $length = $formData['length'] ?? null;

    // Call the createProduct method
    $product = ProductType::createProduct($typeId, $sku, $name, $price, $weight, $size, $height, $width, $length);
    echo "Product created successfully!";
} catch (Exception $e) {
    echo "Error creating product: " . $e->getMessage();
}
 */

 header("Content-Type: application/json; charset=UTF-8");

$apiUrl = "http://localhost/scandiweb-api/actions/create.php"; // Replace with the actual API endpoint URL

$data = json_encode([
    'typeId' => 1,
    'sku' => '25',
    'name' => 'product',
    'price' => 10.99,
    'product_type_id' => 1,
    'weight' => 20,
    'size' => 50,
    'height' => 50,
    'width' => 50,
    'length' => 40
]);

$options = [
    'http' => [
        'header' => "Content-Type: application/json\r\n",
        'method' => 'POST',
        'content' => $data
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($apiUrl, false, $context);

echo $response;