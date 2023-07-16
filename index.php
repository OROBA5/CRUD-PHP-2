<?

include "./config/Database.php";
include "./connection.php";


echo "PHP api for Scandiweb Junior Test";

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // If the connection is successful, you can perform a simple query to verify it.
    $result = $conn->query('SELECT * FROM products');
    
    if ($result) {
        echo 'Database connection successful!';
    } else {
        echo 'Database connection failed: ' . $conn->error;
    }
    
    // Don't forget to close the connection when you're done with it.
    $conn->close();
} catch (Exception $e) {
    echo 'Error connecting to the database: ' . $e->getMessage();
}
?>

