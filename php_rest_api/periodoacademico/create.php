<?php
 // Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

include_once '/xampp/htdocs/Proyecto/php_rest_api/config/Database.php';
include_once '/xampp/htdocs/Proyecto/php_rest_api/models/Periodoacademico.php';

// Instantiate DB & connect
$database = new Database();
$db= $database->connect();

// Instantiate blog post object
$periodoacademico= new periodoacademico($db);
//Get raw estuiante date
$data= json_decode(file_get_contents("php://input"));

$periodoacademico->AnoAcad = $data->AnoAcad;
$periodoacademico->NumPer = $data->NumPer;

// Create estudiante
if($periodoacademico->create()){
    echo json_encode(
        array('message'=>'periodoacademico Created')
    );
}else{
    echo json_encode(
        array('message'=>'periodoacademico Not Created')
    );
}