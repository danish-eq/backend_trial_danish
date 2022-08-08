<?php
set_time_limit(0);
require_once("configs.php");


$obj_api  = new Api_integration($ret_pdo_obj);

if( isset($_GET['import_data']) && ($_GET['import_data'] == "yes") ){
    for($i=1;$i<=126;$i++){
        echo $i . '<br />';
        $obj_api->getDataFromAPI($i);
    }
}
?>