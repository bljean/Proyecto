<?php
 // Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '/xampp/htdocs/Proyecto/php_rest_api/config/Database.php';
include_once '/xampp/htdocs/Proyecto/php_rest_api/models/Trabajadores.php';

// Instantiate DB & connect
$database = new Database();
$db= $database->connect();

// Instantiate blog post object
$Trabajadores= new Trabajadores($db);

//Blog estudiante query
$result = $Trabajadores->read();
//Get row count
$num= $result->rowCount();
//Check if any estudiante
if($num >0){
    // Post array
    $Trabajadores_arr = array();
    //$posts_arr['data']= array();
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $Trabajadores_item = array( 
            'NumCedula'=>$NumCedula,
            'nombre'=>$nombre,
            'apellido_1'=>$apellido_1,
            'apellido_2'=>$apellido_2,
            'usuario'=>$usuario,
            'NumTarjeta'=>$NumTarjeta,
        );
        // Push to "data"
        array_push($Trabajadores_arr, $Trabajadores_item);
        //array_push($posts_arr['data'],$post_item);
    }

    // Turn to JSON & output
    echo json_encode($Trabajadores_arr);
}else{
    //No Posts
    echo json_encode(
        array('message'=>'No Posts Found')
    );
}
?>