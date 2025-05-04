<?php 

    //Singleton for the database connection
    //Makes use of the .env file to get the username, password, dbname etc. of the datase

    class Database{
        //Since it is a singleton, instance is what is called when you want to get the Database object
        public static function instance(){
            static $instance = null; // remember that this only ever gets called once
            if($instance === null) $instance = new Database();
                return $instance;
        }
        
        private function __construct() {
            $connection = new mysqli($host, $username, $password);
            // Check if connected
            // Might fail if password is incorrect, server is unreachable, etc
            if($connection->connect_error)
                die("Connection failure: " . $connection->connect_error);
            else {
                $connection->select_db("216_database");
                echo "Connection success";
            }
            // Do some queries and once done close the connection
            $connection->close();

        }
        public function __destruct() { /* Disconnect from the database */ }
        public function addUser($username){ /* Add to the database */ }
        public function retrieveUser($username){ /* Retrieve from the database */ }
    }

?>