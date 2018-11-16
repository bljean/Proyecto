<?php
 // Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '/xampp/htdocs/Proyecto/php_rest_api/config/Database.php';
include_once '/xampp/htdocs/Proyecto/php_rest_api/models/Grupoactivo.php';

// Instantiate DB & connect
$database = new Database();
$db= $database->connect();

// Instantiate blog post object
$grupoactivo= new grupoactivo($db);

//Blog estudiante query
$result = $grupoactivo->read();

//Get row count
$num= $result->rowCount();
//Check if any gruop
if($num >0){
    // Post array
    $grupoactivo_arr = array();
    //$posts_arr['data']= array();
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $grupoactivo_item = array( 
            'CodTema'=>$CodTema,
            'CodTp'=>$CodTp,
            'NumGrupo'=>$NumGrupo,
            'CodCampus'=>$CodCampus,
            'AnoAcad'=>$AnoAcad,
            'NumPer'=>$NumPer,
            'NumCredito'=>$NumCredito,
        );
        // Push to "data"
        array_push($grupoactivo_arr, $grupoactivo_item);
        //array_push($posts_arr['data'],$post_item);
    }

    // Turn to JSON & output
    echo json_encode($grupoactivo_arr);
}else{
    //No Posts
    echo json_encode(
        array('message'=>'No Posts Found')
    );
}
?>