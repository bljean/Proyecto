<?php
 // Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '/xampp/htdocs/Proyecto/php_rest_api/config/Database.php';
include_once '/xampp/htdocs/Proyecto/php_rest_api/models/Salondocencia.php';

// Instantiate DB & connect
$database = new Database();
$db= $database->connect();

// Instantiate blog post object
$salondocencia= new salondocencia($db);

//Blog estudiante query
$result = $salondocencia->read();

//Get row count
$num= $result->rowCount();
//Check if any gruop
if($num >0){
    // Post array
    $salondocencia_arr = array();
    //$posts_arr['data']= array();
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $salondocencia_item = array( 
            'CodCampus'=>$CodCampus,
            'CodEdif'=>$CodEdif,
            'CodSalon'=>$CodSalon,
          
        );
        // Push to "data"
        array_push($salondocencia_arr, $salondocencia_item);
        //array_push($posts_arr['data'],$post_item);
    }

    // Turn to JSON & output
    echo json_encode($salondocencia_arr);
}else{
    //No Posts
    echo json_encode(
        array('message'=>'No Posts Found')
    );
}
?>