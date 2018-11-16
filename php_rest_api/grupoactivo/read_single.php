<?php
 // Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '/xampp/htdocs/Proyecto/php_rest_api/config/Database.php';
include_once '/xampp/htdocs/Proyecto/php_rest_api/models/Grupoactivo.php';

// Instantiate DB & connect
$database = new Database();
$db= $database->connect();

// Instantiate blog post object
$Grupoactivo= new Grupoactivo($db);

//Get Trabajadores
$Grupoactivo->CodTema= isset($_GET['CodTema']) ? $_GET['CodTema'] : die();
$Grupoactivo->CodTp= isset($_GET['CodTp']) ? $_GET['CodTp'] : die();
$Grupoactivo->NumGrupo= isset($_GET['NumGrupo']) ? $_GET['NumGrupo'] : die();
$Grupoactivo->CodCampus= isset($_GET['CodCampus']) ? $_GET['CodCampus'] : die();
$Grupoactivo->AnoAcad= isset($_GET['AnoAcad']) ? $_GET['AnoAcad'] : die();
$Grupoactivo->NumPer= isset($_GET['NumPer']) ? $_GET['NumPer'] : die();
$Grupoactivo->NumCredito= isset($_GET['NumCredito']) ? $_GET['NumCredito'] : die();

// Get Trabajadores
$Grupoactivo->read_single();

// Create array
$Grupoactivo_arr= array(
    'CodTema'=>$Grupoactivo->CodTema,
    'CodTp'=>$Grupoactivo->CodTp,
    'NumGrupo'=>$Grupoactivo->NumGrupo,
    'CodCampus'=>$Grupoactivo->CodCampus,
    'AnoAcad'=>$Grupoactivo->AnoAcad,
    'NumPer'=>$Grupoactivo->NumPer,
    'NumCredito'=>$Grupoactivo->NumCredito,
);
//Make JSON
print_r(json_encode($Grupoactivo_arr));

?>