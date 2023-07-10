<?php

class ProductType {
    public static function createProduct($formData) {
        $typeId = $formData['typeId'];

        // Create the product based on the product type ID
        $product = self::getProductInstance($typeId, $formData);

        // Save the product to the database with the product type ID
        $product->create($typeId, $formData['product_type_id']);

        return $product;
    }

    public static function readAllProducts($conn) {
        $products = [];
    
        $productTypeQuery = "SELECT * FROM product_type";
        $productTypeResult = $conn->query($productTypeQuery);
    
        while ($productType = $productTypeResult->fetch_assoc()) {
            $productClassName = ucfirst($productType['name']);
            $productClassFile = $productClassName . '.php';
            include_once $productClassFile;
    
            // Create a ReflectionClass instance for the product class
            $reflectionClass = new ReflectionClass($productClassName);
    
            // Create a new instance of the product class without invoking the constructor
            $product = $reflectionClass->newInstanceWithoutConstructor();
    
            // Call the read() method of the product object
            $result = $product->read($conn);
    
            while ($productData = $result->fetch_assoc()) {
                $products[] = $productData;
            }
        }
    
        return $products;
    }
    
    
    



    private static function getProductInstance($typeId, $formData) {
        // Create a mapping of product type IDs to their respective classes
        $classMap = [
            1 => Dvd::class,
            2 => Furniture::class,
            3 => Book::class
        ];

        // Check if the product type ID exists in the mapping
        if (!isset($classMap[$typeId])) {
            throw new Exception("Invalid product type ID: " . $typeId);
        }

        // Get the class name for the product type ID
        $className = $classMap[$typeId];

        // Prepare the arguments for creating the product instance
        $arguments = [$typeId];
        foreach ($formData as $field => $value) {
            if ($field !== 'typeId') {
                $arguments[] = $value;
            }
        }

        // Create the product instance based on the class name and arguments using ReflectionClass
        return self::createProductInstance($className, $arguments);
    }

    private static function createProductInstance($className, $arguments) {
        // Create a ReflectionClass instance for the specified class
        $reflectionClass = new ReflectionClass($className);

        // Create a new instance of the class with the arguments
        return $reflectionClass->newInstanceArgs($arguments);
    }
}
