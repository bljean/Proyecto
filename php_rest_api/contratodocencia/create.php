<?php
 // Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

include_once '/xampp/htdocs/Proyecto/php_rest_api/config/Database.php';
include_once '/xampp/htdocs/Proyecto/php_rest_api/models/Contratodocencia.php';

// Instantiate DB & connect
$database = new Database();
$db= $database->connect();

// Instantiate blog post object
$contratodocencia= new contratodocencia($db);
//Get raw estuiante date
$data= json_decode(file_get_contents("php://input"));

$contratodocencia->CodTema = $data->CodTema;
$contratodocencia->CodTp = $data->CodTp;
$contratodocencia->Numgrupo = $data->Numgrupo;
$contratodocencia->CodCampus = $data->CodCampus;
$contratodocencia->AnoAcad = $data->AnoAcad;
$contratodocencia->NumPer = $data->NumPer;
$contratodocencia->NumCedula = $data->NumCedula;


// Create estudiante
if($contratodocencia->create()){
    echo json_encode(
        array('message'=>'contratodocencia Created')
    );
}else{
    echo json_encode(
        array('message'=>'contratodocencia Not Created')
    );
}