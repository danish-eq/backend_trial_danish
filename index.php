<?php
require_once("configs.php");

$obj_api  = new Homepage($ret_pdo_obj);

$pagination = array();
if (!isset ($_GET['page']) ) {  
    $pagination['page'] = 1;  
} else {  
    $pagination['page'] = $_GET['page'];  
}

$filter = array( "is_filter" => "No" );

if(isset($_GET['btn_sub']) && ($_GET['btn_sub'] == "Submit") ){
    $filter["is_filter"] = "Yes";
    $filter["town"] = $_GET['town'];
    $filter["bedrooms"] =  $_GET['bedrooms'];
    $filter["price_from"] = $_GET['price_from'];
    $filter["price_to"] = $_GET['price_to'];
    $filter["property_type"] = $_GET['property_type'];
    $filter["type"] = $_GET['type'];
}

$limit = 50;
$offset = ($pagination['page']-1) * $limit;  

$pagination['limit'] = $limit;
$pagination['offset'] = $offset;

$data = $obj_api->getAllData($pagination , $filter);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base href="/">	
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
     	    
    <title>Assignment by Danish Ejaz</title>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="http://getbootstrap.com/dist/js/bootstrap.min.js"></script>


</head>
<body>

<div class="container">
	<div class="row">
    <div class="col-md-12">
    <h4>Properties Listing</h4>
<div>
    <form action="backend_trial/index.php" method="GET" >
        
        <span>
            <input type="text" name="town" id="town" placeholder="Enter Town"  value="<?= ( isset($_GET['town']) ? $_GET['town'] : "") ?>" />
        </span>
        
        <span>
            <select name="bedrooms" id="bedrooms">
                <option value="">Bedrooms</option>
                <?php
                for($i=0;$i<21;$i++){
                ?>
                    <option value="<?php echo $i; ?>" 
                        <?php
                            if( isset($_GET['bedrooms']) && ($_GET['bedrooms'] == $i) ) {
                                echo " selected ";
                            }
                        ?>
                    ><?php echo $i; ?></option>
                <?php
                }
                ?>
            </select>
        </span>
        
        <span>
            <b>Price Range:</b>
            <span>
                <input type="text" name="price_from" id="price_from" placeholder="From"  value="<?= ( isset($_GET['price_from']) ? $_GET['price_from'] : "") ?>" />
            </span>
            <span>
                <input type="text" name="price_to" id="price_to" placeholder="To"  value="<?= ( isset($_GET['price_to']) ? $_GET['price_to'] : "") ?>" />
            </span>
        </span>
        
        <span>
        <select name="property_type" id="property_type">
                <option value="">Property Type</option>
                <?php
                $data_property_types = $obj_api->getAllPropertytypes();
                for($i=0;$i<count($data_property_types);$i++){
                ?>
                    <option value="<?php echo $data_property_types[$i]['type_id']; ?>"
                    <?php
                        if( isset($_GET['property_type']) && ($_GET['property_type'] == $data_property_types[$i]['type_id']) ) {
                            echo " selected ";
                        }
                    ?>
                    ><?php echo $data_property_types[$i]['title']; ?></option>
                <?php
                }
                ?>
            </select>
        </span>

        <span>
        <select name="type" id="type">
                <option value="">Type</option>
                <option value="Sale"
                <?php
                    if( isset($_GET['type']) && ($_GET['type'] == "Sale") ) {
                        echo " selected ";
                    }
                ?>
                >Sale</option>
                <option value="Rent"
                <?php
                    if( isset($_GET['type']) && ($_GET['type'] == "Rent") ) {
                        echo " selected ";
                    }
                ?>
                >Rent</option>
                
            </select>
        </span>
        <span>
                <input type="submit" name="btn_sub" id="btn_sub" value="Submit" />
        </span>
    </form>
    
</div>

<?php
if(count($data['data']) > 0){?>
    <div class="table-responsive">
    <table id="mytable" class="table table-bordred table-striped">
        <thead>
        <tr>
            <th>County</th>
            <th>Country</th>
            <th>Town</th>
            <th>Address</th>
            <th>Thumbnail</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Bedrooms</th>
            <th>Bathrooms</th>
            <th>Price</th>
            <th>Type</th>
            <th>Property type</th>
        </tr>   
        </thead> 
        <tbody>
        <?php
        foreach( $data['data'] AS $property_data){
        ?>
            <tr>
                <td><?php echo $property_data['county']; ?></td>
                <td><?php echo $property_data['country']; ?></td>
                <td><?php echo $property_data['town']; ?></td>
                <td><?php echo $property_data['address']; ?></td>
                <td><img src="<?php echo $property_data['thumbnail']; ?>" /></td>
                <td><?php echo $property_data['latitude']; ?></td>
                <td><?php echo $property_data['longitude']; ?></td>
                <td><?php echo $property_data['number_of_bedrooms']; ?></td>
                <td><?php echo $property_data['number_of_bathrooms']; ?></td>
                <td><?php echo $property_data['price']; ?></td>
                <td><?php echo $property_data['sale_rent_type']; ?></td>
                <td><?php echo $property_data['title']; ?></td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
    </div>
<?php
}else{
    echo "No Record Found.";
}
?>
<div class="clearfix"></div>
<ul class="pagination pull-right">
<?php
//display the link of the pages in URL  
for($page = 1; $page <= $data['number_of_pages']; $page++) {
    if(isset($_GET['btn_sub']) && ($_GET['btn_sub'] == "Submit") ){
    ?>    
    <li><a href="backend_trial/index.php?page=<?= $page ?>&town=<?= $_GET['town'] ?>&bedrooms=<?= $_GET['bedrooms'] ?>&price_from=<?= $_GET['price_from'] ?>&price_to=<?= $_GET['price_to']?>&property_type=<?= $_GET['property_type'] ?>&type=<?= $_GET['type'] ?>&btn_sub=Submit"><?= $page ?> </a></li>
    <?php
    }  else {
    ?>
        <li><a href = "backend_trial/index.php?page=<?= $page ?>"><?=  $page ?> </a></li> 
    <?php
    }   
}  
?>
</ul>

</div>
</div>
</div>
</body>
</html>