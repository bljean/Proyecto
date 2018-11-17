<?php
 // Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

include_once '/xampp/htdocs/Proyecto/php_rest_api/config/Database.php';
include_once '/xampp/htdocs/Proyecto/php_rest_api/models/Salondocencia.php';

// Instantiate DB & connect
$database = new Database();
$db= $database->connect();

// Instantiate blog post object
$salondocencia= new salondocencia($db);
//Get raw estuiante date
$data= json_decode(file_get_contents("php://input"));

$salondocencia->CodCampus = $data->CodCampus;
$salondocencia->CodEdif = $data->CodEdif;
$salondocencia->CodSalon = $data->CodSalon;

// Create estudiante
if($salondocencia->create()){
    echo json_encode(
        array('message'=>'salondocencia Created')
    );
}else{
    echo json_encode(
        array('message'=>'salondocencia Not Created')
    );
}