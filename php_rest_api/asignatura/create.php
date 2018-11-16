<?php
 // Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

include_once '/xampp/htdocs/Proyecto/php_rest_api/config/Database.php';
include_once '/xampp/htdocs/Proyecto/php_rest_api/models/Asignatura.php';

// Instantiate DB & connect
$database = new Database();
$db= $database->connect();

// Instantiate blog post object
$asignatura= new asignatura($db);
//Get raw estuiante date
$data= json_decode(file_get_contents("php://input"));

$asignatura->CodTema = $data->CodTema;
$asignatura->CodTp = $data->CodTp;
$asignatura->Nombre = $data->Nombre;
$asignatura->NumCreditos = $data->NumCreditos;

// Create estudiante
if($asignatura->create()){
    echo json_encode(
        array('message'=>'grupoactivo Created')
    );
}else{
    echo json_encode(
        array('message'=>'grupoactivo Not Created')
    );
}
?>