<?php
 // Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '/xampp/htdocs/Proyecto/php_rest_api/config/Database.php';
include_once '/xampp/htdocs/Proyecto/php_rest_api/models/Estudiante.php';

// Instantiate DB & connect
$database = new Database();
$db= $database->connect();

// Instantiate blog post object
$estudiante= new Estudiante($db);

//Blog estudiante query
$result = $estudiante->read();
//Get row count
$num= $result->rowCount();
//Check if any estudiante
if($num >0){
    // Post array
    $estudiante_arr = array();
    //$posts_arr['data']= array();
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $estudiante_item = array( 
            'Matricula'=>$Matricula,
            'nombre'=>$nombre,
            'apellido'=>$apellido,
            'NumTarjeta'=>$NumTarjeta,
        );
        // Push to "data"
        array_push($estudiante_arr, $estudiante_item);
        //array_push($posts_arr['data'],$post_item);
    }

    // Turn to JSON & output
    echo json_encode($estudiante_arr);
}else{
    //No Posts
    echo json_encode(
        array('message'=>'No Posts Found')
    );
}
?>