<?php
 // Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '/xampp/htdocs/Proyecto/php_rest_api/config/Database.php';
include_once '/xampp/htdocs/Proyecto/php_rest_api/models/Grupoinsest.php';

// Instantiate DB & connect
$database = new Database();
$db= $database->connect();

// Instantiate blog post object
$grupoinsest= new grupoinsest($db);

//Get Trabajadores
$grupoinsest->Matricula= isset($_GET['Matricula']) ? $_GET['Matricula'] : die();
$grupoinsest->CodTema= isset($_GET['CodTema']) ? $_GET['CodTema'] : die();
$grupoinsest->CodTP= isset($_GET['CodTP']) ? $_GET['CodTP'] : die();
$grupoinsest->Numgrupo= isset($_GET['Numgrupo']) ? $_GET['Numgrupo'] : die();
$grupoinsest->CodCampus= isset($_GET['CodCampus']) ? $_GET['CodCampus'] : die();
$grupoinsest->AnoAcad= isset($_GET['AnoAcad']) ? $_GET['AnoAcad'] : die();
$grupoinsest->NumPer= isset($_GET['NumPer']) ? $_GET['NumPer'] : die();
$grupoinsest->NumAusencias= isset($_GET['NumAusencias']) ? $_GET['NumAusencias'] : die();

// Get Trabajadores
$grupoinsest->read_single();

// Create array
$grupoinsest_arr= array(
    'Matricula'=>$grupoinsest->Matricula,
    'CodTema'=>$grupoinsest->CodTema,
    'CodTP'=>$grupoinsest->CodTP,
    'Numgrupo'=>$grupoinsest->Numgrupo,
    'CodCampus'=>$grupoinsest->CodCampus,
    'AnoAcad'=>$grupoinsest->AnoAcad,
    'NumPer'=>$grupoinsest->NumPer,
    'NumAusencias'=>$grupoinsest->NumAusencias,
);
//Make JSON
print_r(json_encode($grupoinsest_arr));

?>