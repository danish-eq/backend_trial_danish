<?php
define("BASE_URL", "http://localhost/backend_trial_git/backend_trial_danish/");

//Database Credentials

define("HOST", "localhost");
define("DB_NAME", "properties");
define("USER", "root");
define("PASSWORD", "");

/************************ */
//API Credentials
define("API_KEY", "2S7rhsaq9X1cnfkMCPHX64YsWYyfe1he");
define("PAGE_SIZE", "100");

//define("", "");
/************************ */

require_once('vendor/autoload.php');

/************************ */

//Autoload classes

spl_autoload_register(function ($class_name) {
    include "classes/".$class_name . '.class.php';
});

$db_obj_pdo = new DB_PDO(HOST , DB_NAME , USER , PASSWORD);
$ret_pdo_obj = $db_obj_pdo->db_con();
 

?>