<?php
class DB_PDO {
    // Properties
    private $pdo;

    private $host ; 
    private $db_name ;  
    private $user  ;
    private $pass ;
    
    // Methods
    function __construct($host , $db_name , $user , $pass) {
        
        $this->host = $host ; 
        $this->db_name = $db_name ;  
        $this->user = $user ;
        $this->pass = $pass ;
    }
    
    public function db_con(){

        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=UTF8";

        try {
            $this->pdo = new PDO($dsn, $this->user , $this->pass);

            if ($this->pdo) {
                //echo "Connected to the " . $this->db_name . " database successfully!";
                return $this->pdo;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

    }



  }

?>