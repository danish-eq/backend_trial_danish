<?php
class Homepage {

    private $db_obj;

    // Methods
    function __construct($pdo_db_obj) {
        $this->db_obj = $pdo_db_obj;
    }

    public function getAllData($pagination , $filter){

        $ret_data = array();
        $where = "";
        if($filter['is_filter'] == "Yes"){
            if( !empty( $filter['town'] ) ){
                $where .= " town LIKE '%". $filter['town'] ."%'  AND ";
            }
            if( !empty( $filter['bedrooms'] ) ){
                $where .= " number_of_bedrooms =  ".$filter['bedrooms'] . " AND ";
            }
            if( !empty( $filter['price_from'] ) && !empty( $filter['price_to'] )){
                $where .= " price BETWEEN  " . $filter['price_from'] . " AND " . $filter['price_to'] . " AND ";
            }

            if( !empty( $filter['property_type'] ) ){
                $where .= " property_type = " . $filter['property_type'] . " AND ";
            }

            if( !empty( $filter['type'] ) ){
                $where .= " sale_rent_type = '". $filter['type'] ."' AND ";
            }

            $where = rtrim($where," AND ");

        }
        
        if( !empty( $where ) ){
            $where = " WHERE  " . $where;
        }

        //Count
        $sql_count = "SELECT count(*)  
        FROM `property` 
        INNER JOIN `property_type` 
        ON `property`.`property_type` = `property_type`.`type_id` 

        " . $where;

        $stmt = $this->db_obj->prepare($sql_count);
        $stmt->execute();
        $data_count = $stmt->fetchColumn();

        $number_of_pages = ceil ($data_count / $pagination['limit']);
        $ret_data['number_of_pages'] = $number_of_pages;
        
        //Actual SQL
        
        $sql = "SELECT * 
                FROM `property` 
                INNER JOIN `property_type` 
                ON `property`.`property_type` = `property_type`.`type_id` 
                " . $where . "
                LIMIT " . $pagination['offset'] . " , " . $pagination['limit'];
        
        
        $stmt = $this->db_obj->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $ret_data['data'] = $data;
        return $ret_data;

    }



    public function getAllPropertytypes(){
        echo $sql = "SELECT * 
                FROM `property_type` 
                ORDER BY title ASC 
                ";
        
        $stmt = $this->db_obj->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
       
        return $data;
    }
}
?>