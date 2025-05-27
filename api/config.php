<?php 

    /* Ekse lady and gents, you need a program called Composer installed in order to get the .env file in php
     
    1. You can download it by going to the following website:
        https://getcomposer.org/download/
    2. Run the installer
        - And when it asks for the PHP path, point it to your PHP executable, typically:
            C:\xampp\php\php.exe
        - Allow the installer to add Composer to your system’s PATH so you can use composer from the terminal.

    3. Verify the installation
        Close and reopen your terminal (Either Command Prompt or PowerShell), then run this:
        composer --version
    
        You should see something like:
        Composer version 2.7.2 2024-04-10 16:08:44 etc.

    4. then ensure that the following line appears in your .gitignore file:
    /vendor/

    Then errthing should be able to run fine. And you'll be able to access the .env files

    */

    //Accessing the .env file
    require_once __DIR__ . '/../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
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

            $this->connection = new mysqli($host, $user, $pass, $db, $port);

            if ($this->connection->connect_error) {

                die("DB Connection failed: " . $this->connection->connect_error);
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