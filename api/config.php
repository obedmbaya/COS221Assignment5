<?php 

    /* Ekse lady and gents, you need a program called Composer installed in order to get the .env file in php
     
     You can do that by running the following command (if it doesn't work hit Jared up and ask for help):
     composer install

     then ensure that the following line appears in your .gitignore file:
     /vendor/

     Then errthing should be ayt

    */

    //Accessing the .env file
    require_once __DIR__ . '/../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
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

    $query = "SELECT * FROM products LIMIT 10";

    try {
        $statement = $database->prepare($query);
        $statement->execute();

        // Fetch all rows as an associative array
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Check if any products were found
        if (count($products) > 0) {
            // Loop through each product and display the data
            foreach ($products as $product) {
                echo "ID: " . $product['id'] . "<br>";
                echo "Title: " . $product['title'] . "<br>";
                echo "Brand: " . $product['brand'] . "<br>";
                echo "Description: " . $product['description'] . "<br>";
                echo "Initial Price: " . $product['initial_price'] . "<br>";
                echo "Final Price: " . $product['final_price'] . "<br>";
                echo "Currency: " . $product['currency'] . "<br>";
                echo "Categories: " . $product['categories'] . "<br>";
                echo "Image URL: " . $product['image_url'] . "<br>";
                echo "Product Dimensions: " . $product['product_dimensions'] . "<br>";
                echo "Date First Available: " . $product['date_first_available'] . "<br>";
                echo "Manufacturer: " . $product['manufacturer'] . "<br>";
                echo "Department: " . $product['department'] . "<br>";
                echo "Features: " . $product['features'] . "<br>";
                echo "Is Available: " . $product['is_available'] . "<br>";
                echo "Images: " . $product['images'] . "<br>";
                echo "Country of Origin: " . $product['country_of_origin'] . "<br>";
                echo "Created At: " . $product['created_at'] . "<br>";
                echo "Updated At: " . $product['updated_at'] . "<br>";
            }
        } else {
            echo "No products found in the database.";
        }
    } catch (PDOException $e) {
        echo "Error fetching products: " . $e->getMessage();
    }

?>