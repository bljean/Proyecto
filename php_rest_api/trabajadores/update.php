<?php
 // Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

include_once '/xampp/htdocs/Proyecto/php_rest_api/config/Database.php';
include_once '/xampp/htdocs/Proyecto/php_rest_api/models/Trabajadores.php';

// Instantiate DB & connect
$database = new Database();
$db= $database->connect();

// Instantiate blog post object
$trabajadores= new Trabajadores($db);
//Get raw estuiante date
$data= json_decode(file_get_contents("php://input"));

$trabajadores->NumCedula = $data->NumCedula;
$trabajadores->nombre = $data->nombre;
$trabajadores->apellido_1 = $data->apellido_1;
$trabajadores->apellido_2 = $data->apellido_2;
$trabajadores->usuario = $data->usuario;
$trabajadores->NumTarjeta = $data->NumTarjeta;

// Create estudiante
if($trabajadores->update()){
    echo json_encode(
        array('message'=>'Trabajadore Updated')
    );
}else{
    echo json_encode(
        array('message'=>'Trabajadore Not Updated')
    );
}