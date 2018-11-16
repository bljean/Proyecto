<?php
 // Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

include_once '/xampp/htdocs/Proyecto/php_rest_api/config/Database.php';
include_once '/xampp/htdocs/Proyecto/php_rest_api/models/Grupoactivo.php';

// Instantiate DB & connect
$database = new Database();
$db= $database->connect();

// Instantiate blog post object
$grupoactivo= new Grupoactivo($db);
//Get raw estuiante date
$data= json_decode(file_get_contents("php://input"));

$grupoactivo->CodTema = $data->CodTema;
$grupoactivo->CodTp = $data->CodTp;
$grupoactivo->NumGrupo = $data->NumGrupo;
$grupoactivo->CodCampus = $data->CodCampus;
$grupoactivo->AnoAcad = $data->AnoAcad;
$grupoactivo->NumPer = $data->NumPer;
$grupoactivo->NumCredito = $data->NumCredito;
// Create estudiante
if($grupoactivo->create()){
    echo json_encode(
        array('message'=>'grupoactivo Created')
    );
}else{
    echo json_encode(
        array('message'=>'grupoactivo Not Created')
    );
}
?>