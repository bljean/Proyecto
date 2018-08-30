<?php
 // Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

include_once '/xampp/htdocs/Proyecto/php_rest_api/config/Database.php';
include_once '/xampp/htdocs/Proyecto/php_rest_api/models/Estudiante.php';

// Instantiate DB & connect
$database = new Database();
$db= $database->connect();

// Instantiate blog post object
$estudiante= new Estudiante($db);
//Get raw estuiante date
$data= json_decode(file_get_contents("php://input"));

$estudiante->Matricula = $data->Matricula;
$estudiante->nombre = $data->nombre;
$estudiante->apellido = $data->apellido;
$estudiante->NumTarjeta = $data->NumTarjeta;

// Create estudiante
if($estudiante->create()){
    echo json_encode(
        array('message'=>'Estudiante Created')
    );
}else{
    echo json_encode(
        array('message'=>'Estudiante Not Created')
    );
}