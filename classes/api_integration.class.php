<?php

Class Api_integration {

    private $db_obj;

    // Methods
    function __construct($pdo_db_obj) {
        $this->db_obj = $pdo_db_obj;
    }


    public function getDataFromAPI($page_no){
        
        $client = new GuzzleHttp\Client();
        $res = $client->request('GET', 'https://trial.craig.mtcserver15.com/api/properties?api_key=' . API_KEY . '&page[number]=' . $page_no . '&page[size]='.PAGE_SIZE);

        $body = json_decode( $res->getBody() , true);
        $this->saveDataIntoDB($body);
        return 1;
    }



    private function saveDataIntoDB($body){
        
        foreach($body['data'] AS $data){

            $county         = $data['county'];
            $country        = $data['country'];
            $town           = $data['town'];
            $description    = $data['description'];
            $address        = $data['address'];
            $image          = $data['image_full'];
            $thumbnail      = $data['image_thumbnail'];
            $latitude       = $data['latitude'];
            $longitude      = $data['longitude'];
            $number_of_bedrooms     = $data['num_bedrooms'];
            $number_of_bathrooms    = $data['num_bathrooms'];
            $price          = $data['price'];
            $property_type  = $data['property_type_id'];
            $sale_rent_type = $data['type'];
    
            $sql_ins =  " INSERT INTO  `property` SET 
                            `county`   = '". addslashes( $county )."',
                            `country`   = '". addslashes( $country )."',
                            `town`   = '". addslashes( $town )."',
                            `description`   = '". addslashes( $description )."',
                            `address`   = '". addslashes( $address )."',
                            `image`   = '". addslashes( $image )."',
                            `thumbnail`   = '". addslashes( $thumbnail )."',
                            `latitude`   = '".  $latitude ."',
                            `longitude`   = '". $longitude ."',
                            `number_of_bedrooms`   = '".  $number_of_bedrooms ."',
                            `number_of_bathrooms`   = '". $number_of_bathrooms ."',
                            `price`   = '".  $price ."',
                            `property_type`   = '".  $property_type ."',
                            `sale_rent_type`   = '". addslashes( $sale_rent_type )."'
                        "; 
            $stmt = $this->db_obj->prepare($sql_ins);
            $ret = $stmt->execute();

            //Checking and adding property type
            $sql_chk_type = " SELECT type_id FROM `property_type` WHERE type_id=".$property_type;
            $stmt = $this->db_obj->prepare($sql_chk_type);
            $stmt->execute();
            $ret_property_type = $stmt->fetchAll();
            if(count($ret_property_type) == 0){
                $sql_property_type_ins =    " INSERT INTO `property_type` SET 
                                                type_id = ".$property_type.",
                                                title = '" . addslashes( $data['property_type']['title'] ) . "',
                                                description = '". addslashes( $data['property_type']['description'] ) ."' 
                                            ";
                $stmt = $this->db_obj->prepare($sql_property_type_ins);
                $ret = $stmt->execute();
            }

        }
            
        return 1;
    }


}
?>