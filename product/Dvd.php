<?php

/* include '../config/Database.php';
include '../connection.php';
include './Product.php'; */


// DVD subclass for Product
class DVD extends Product {
    // Declare DVD specific fields
    public $size;
    private $conn;

    // Declare constructor for the DVD class
    public function __construct($id, $sku, $name, $price, $product_type_id, $size)
    {
        parent::__construct($id, $sku, $name, $price, $product_type_id);
        $this->size = $size;
    }
    
    
    // Setters and getters for the class specific fields
    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    function create()
    {
        // Establish database connection
        $database = new Database();
        $this->conn = $database->getConnection();
    
        // Insert data into the "product" table
        $productStmt = $this->conn->prepare("
            INSERT INTO product(`sku`, `name`, `price`, `product_type_id`)
            VALUES(?, ?, ?, ?)");

        $sku = $this->getSku();
        $name = $this->getName();
        $price = $this->getPrice();
        $productTypeId = $this->getProductTypeId();
        
        $sku = htmlspecialchars(strip_tags($sku));
        $name = htmlspecialchars(strip_tags($name));
        $price = htmlspecialchars(strip_tags($price));
        $productTypeId = htmlspecialchars(strip_tags($productTypeId));
        
        $productStmt->bind_param("ssii", $sku, $name, $price, $productTypeId);
        
        // Insert data into the "product" table first
        if ($productStmt->execute()) {
            // Get the generated product ID
            $product_id = $productStmt->insert_id;
            $productStmt->close();

            // Insert data into the "dvd" table
            $dvdStmt = $this->conn->prepare("
                INSERT INTO dvd(`product_id`, `size`)
                VALUES(?, ?)");

            $size = $this->getSize();
            /* $dvdStmt->bind_param("id", $product_id, $size); */
            $dvdStmt->bind_param("id", $product_id, $this->size);


            // Execute the dvd query
            if ($dvdStmt->execute()) {
                $dvdStmt->close();
                $this->conn->close();
                return true;
            }

            $dvdStmt->close();
        }
        
        $productStmt->close();
        $this->conn->close();
        return false;
    }
}

