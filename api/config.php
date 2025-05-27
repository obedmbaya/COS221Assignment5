<?php
require_once _DIR_ . '/../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(_DIR_ . "/../");
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

?>