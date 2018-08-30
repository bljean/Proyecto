<?php
 // Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '/xampp/htdocs/Proyecto/php_rest_api/config/Database.php';
include_once '/xampp/htdocs/Proyecto/php_rest_api/models/Estudiante.php';

// Instantiate DB & connect
$database = new Database();
$db= $database->connect();

// Instantiate blog post object
$estudiante= new Estudiante($db);

//Get Matricula
$estudiante->Matricula= isset($_GET['Matricula']) ? $_GET['Matricula'] : die();

// Get estudiante
$estudiante->read_single();

// Create array
$estudiante_arr= array(
    'Matricula'=> $estudiante->Matricula,
    'nombre'=> $estudiante->nombre,
    'apellido'=> $estudiante->apellido,
    'NumTarjeta'=> $estudiante->NumTarjeta,
);
//Make JSON
print_r(json_encode($estudiante_arr));
