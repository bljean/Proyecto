<?php
 // Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '/xampp/htdocs/Proyecto/php_rest_api/config/Database.php';
include_once '/xampp/htdocs/Proyecto/php_rest_api/models/Periodoacademico.php';

// Instantiate DB & connect
$database = new Database();
$db= $database->connect();

// Instantiate blog post object
$periodoacademico= new periodoacademico($db);

//Get Trabajadores
$periodoacademico->AnoAcad= isset($_GET['AnoAcad']) ? $_GET['AnoAcad'] : die();
$periodoacademico->NumPer= isset($_GET['NumPer']) ? $_GET['NumPer'] : die();
// Get Trabajadores
$periodoacademico->read_single();

// Create array
$periodoacademico_arr= array(
    'AnoAcad'=>$periodoacademico->AnoAcad,
    'NumPer'=>$periodoacademico->NumPer,
    
);
//Make JSON
print_r(json_encode($periodoacademico_arr));