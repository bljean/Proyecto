<?php
 // Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '/xampp/htdocs/Proyecto/php_rest_api/config/Database.php';
include_once '/xampp/htdocs/Proyecto/php_rest_api/models/Periodoacademico.php';

// Instantiate DB & connect
$database = new Database();
$db= $database->connect();

// Instantiate blog post object
$periodoacademico= new periodoacademico($db);

//Blog estudiante query
$result = $periodoacademico->read();
//Get row count
$num= $result->rowCount();
//Check if any estudiante
if($num >0){
    // Post array
    $periodoacademico_arr = array();
    //$posts_arr['data']= array();
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $periodoacademico_item = array( 
            'AnoAcad'=>$AnoAcad,
            'NumPer'=>$NumPer,
           
        );
        // Push to "data"
        array_push($periodoacademico_arr, $periodoacademico_item);
        //array_push($posts_arr['data'],$post_item);
    }

    // Turn to JSON & output
    echo json_encode($periodoacademico_arr);
}else{
    //No Posts
    echo json_encode(
        array('message'=>'No Posts Found')
    );
}
?>