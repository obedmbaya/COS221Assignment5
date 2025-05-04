<?php 

    /* Ekse lady and gents, you need a program called Composer installed in order to get the .env file in php
     
     You can do that by running the following command (if it doesn't work hit Jared up and ask for help):
     composer install

     then ensure that the following line appears in your .gitignore file:
     /vendor/

     Then errthing should be ayt

    */

    //Accessing the .env file
    require_once __DIR__ . '/vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    //Singleton for the database connection
    //Makes use of the .env file to get the username, password, dbname etc. of the datase
    class Database {
        private static $instance = null;
        private $connection = null;
    
        private function __construct() {

            $host = $_ENV['DB_HOST'];
            $port = $_ENV['DB_PORT'];
            $db   = $_ENV['DB_DATABASE'];
            $user = $_ENV['DB_USERNAME'];
            $pass = $_ENV['DB_PASSWORD'];
            // $host = getenv('DB_HOST');
            // $port = getenv('DB_PORT');
            // $db   = getenv('DB_NAME');
            // $user = getenv('DB_USER');
            // $pass = getenv('DB_PASS');

            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

            try {
                
                $this->connection = new PDO($dsn, $user, $pass);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                die("DB Connection failed: " . $e->getMessage());
            }
    
        }
    
        public function __destruct() {
            $this->connection = null;
            self::$instance = null;
        }
    
        public static function instance() {
            if (self::$instance === null) {
                self::$instance = new Database();
            }
    
            return self::$instance;
        }
    
        public function getConnection() {
            return $this->connection;
        }
    }
    
    $database = Database::instance()->getConnection();

    $query = "SELECT * FROM products";

    try {
        $statement = $database->prepare($query);
        $statement->execute();

        // Fetch all rows as an associative array
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Check if any products were found
        if (count($products) > 0) {
            // Loop through each product and display the data
            foreach ($products as $product) {
                echo "Product ID: " . $product['product_id'] . "<br>";
                echo "Product Name: " . $product['product_name'] . "<br>";
                echo "Category: " . $product['category'] . "<br>";
                echo "Description: " . $product['description'] . "<br>";
                echo "Price: " . $product['price'] . "<br>";
                echo "Stock Quantity: " . $product['stock_quantity'] . "<br>";
                echo "Release Date: " . $product['release_date'] . "<br>";
                echo "Manufacturer: " . $product['manufacturer'] . "<br>";
                echo "Model Number: " . $product['model_number'] . "<br>";
                echo "Screen Size: " . $product['screen_size'] . "<br>";
                echo "Processor: " . $product['processor'] . "<br>";
                echo "RAM (GB): " . $product['ram_gb'] . "<br>";
                echo "Storage (GB): " . $product['storage_gb'] . "<br>";
                echo "Camera Resolution: " . $product['camera_resolution'] . "<br>";
                echo "Battery Capacity (mAh): " . $product['battery_capacity_mah'] . "<br>";
                echo "Operating System: " . $product['operating_system'] . "<br>";
                echo "Connectivity: " . $product['connectivity'] . "<br>";
                echo "Weight (grams): " . $product['weight_grams'] . "<br>";
                echo "Dimensions: " . $product['dimensions'] . "<br>";
                echo "Warranty (months): " . $product['warranty_months'] . "<br>";
                echo "<br>"; // Add a line break for better readability
            }
        } else {
            echo "No products found in the database.";
        }
    } catch (PDOException $e) {
        echo "Error fetching products: " . $e->getMessage();
    }

?>