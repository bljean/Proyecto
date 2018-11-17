<?php
 // Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

include_once '/xampp/htdocs/Proyecto/php_rest_api/config/Database.php';
include_once '/xampp/htdocs/Proyecto/php_rest_api/models/Horariogrupoactivo.php';

// Instantiate DB & connect
$database = new Database();
$db= $database->connect();

// Instantiate blog post object
$horariogrupoactivo= new horariogrupoactivo($db);
//Get raw estuiante date
$data= json_decode(file_get_contents("php://input"));

$horariogrupoactivo->CodTema = $data->CodTema;
$horariogrupoactivo->CodTP = $data->CodTP;
$horariogrupoactivo->NumGrupo = $data->NumGrupo;
$horariogrupoactivo->CodCampus = $data->CodCampus;
$horariogrupoactivo->AnoAcad = $data->AnoAcad;
$horariogrupoactivo->NumPer = $data->NumPer;
$horariogrupoactivo->DiaSem = $data->DiaSem;
$horariogrupoactivo->HoraInicio = $data->HoraInicio;
$horariogrupoactivo->Horafin = $data->Horafin;
$horariogrupoactivo->Sal_CodCampus = $data->Sal_CodCampus;
$horariogrupoactivo->Sal_CodEdif = $data->Sal_CodEdif;
$horariogrupoactivo->Sal_CodSalon = $data->Sal_CodSalon;
// Create estudiante
if($horariogrupoactivo->create()){
    echo json_encode(
        array('message'=>'horariogrupoactivo Created')
    );
}else{
    echo json_encode(
        array('message'=>'horariogrupoactivo Not Created')
    );
}
?>