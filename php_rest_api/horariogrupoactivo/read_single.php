<?php
 // Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '/xampp/htdocs/Proyecto/php_rest_api/config/Database.php';
include_once '/xampp/htdocs/Proyecto/php_rest_api/models/Horariogrupoactivo.php';

// Instantiate DB & connect
$database = new Database();
$db= $database->connect();

// Instantiate blog post object
$horariogrupoactivo= new horariogrupoactivo($db);

//Get Trabajadores
$horariogrupoactivo->CodTema= isset($_GET['CodTema']) ? $_GET['CodTema'] : die();
$horariogrupoactivo->CodTP= isset($_GET['CodTP']) ? $_GET['CodTP'] : die();
$horariogrupoactivo->NumGrupo= isset($_GET['NumGrupo']) ? $_GET['NumGrupo'] : die();
$horariogrupoactivo->CodCampus= isset($_GET['CodCampus']) ? $_GET['CodCampus'] : die();
$horariogrupoactivo->AnoAcad= isset($_GET['AnoAcad']) ? $_GET['AnoAcad'] : die();
$horariogrupoactivo->NumPer= isset($_GET['NumPer']) ? $_GET['NumPer'] : die();
$horariogrupoactivo->DiaSem= isset($_GET['DiaSem']) ? $_GET['DiaSem'] : die();
$horariogrupoactivo->HoraInicio= isset($_GET['HoraInicio']) ? $_GET['HoraInicio'] : die();
$horariogrupoactivo->Horafin= isset($_GET['Horafin']) ? $_GET['Horafin'] : die();
$horariogrupoactivo->Sal_CodCampus= isset($_GET['Sal_CodCampus']) ? $_GET['Sal_CodCampus'] : die();
$horariogrupoactivo->Sal_CodEdif= isset($_GET['Sal_CodEdif']) ? $_GET['Sal_CodEdif'] : die();
$horariogrupoactivo->Sal_CodSalon= isset($_GET['Sal_CodSalon']) ? $_GET['Sal_CodSalon'] : die();

// Get Trabajadores
$horariogrupoactivo->read_single();

// Create array
$horariogrupoactivo_arr= array(
    'CodTema'=>$horariogrupoactivo->CodTema,
    'CodTP'=>$horariogrupoactivo->CodTP,
    'NumGrupo'=>$horariogrupoactivo->NumGrupo,
    'CodCampus'=>$horariogrupoactivo->CodCampus,
    'AnoAcad'=>$horariogrupoactivo->AnoAcad,
    'NumPer'=>$horariogrupoactivo->NumPer,
    'DiaSem'=>$horariogrupoactivo->DiaSem,
    'HoraInicio'=>$horariogrupoactivo->HoraInicio,
    'Horafin'=>$horariogrupoactivo->Horafin,
    'Sal_CodCampus'=>$horariogrupoactivo->Sal_CodCampus,
    'Sal_CodEdif'=>$horariogrupoactivo->Sal_CodEdif,
    'Sal_CodSalon'=>$horariogrupoactivo->Sal_CodSalon,
    
);
//Make JSON
print_r(json_encode($horariogrupoactivo_arr));

?>