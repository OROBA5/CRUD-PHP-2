<?php

/* include '../config/Database.php';
include '../connection.php';
include './Product.php'; */


//book subclass for product
class Book extends Product {
    // declare book specific field
    public $weight;
    private $conn;

    //declares constructor for the book class
    public function __construct($id, $sku, $name, $price, $product_type_id, $weight)
    {
        parent::__construct($id, $sku, $name, $price, $product_type_id);
        $this->weight = $weight;
    }
    
    
    //setters and getters for the class specific fields
    public function getWeight()
    {
        return $this->weight;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    function create(){
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

        // Insert data into the "book" table
        $bookStmt = $this->conn->prepare("
            INSERT INTO book(`product_id`, `weight`)
            VALUES(?, ?)");

        $weight = $this->getWeight();
        /* $bookStmt->bind_param("id", $product_id, $weight); */
        $bookStmt->bind_param("id", $product_id, $this->weight);


        // Execute the book query
        if ($bookStmt->execute()) {
            $bookStmt->close();
            $this->conn->close();
            return true;
        }

        $bookStmt->close();

         }
     }

     
     function read($conn) {
        if ($this->id) {
            $stmt = $conn->prepare("
                SELECT b.*, p.sku, p.name, p.price, p.product_type_id
                FROM book b
                INNER JOIN product p ON b.product_id = p.id
                WHERE b.product_id = ?
            ");
            $stmt->bind_param("i", $this->id);
        } else {
            $stmt = $conn->prepare("
                SELECT b.*, p.sku, p.name, p.price, p.product_type_id
                FROM book b
                INNER JOIN product p ON b.product_id = p.id
            ");
        }
    
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    
        return $result;
    }
    
}


