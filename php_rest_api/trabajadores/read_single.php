<?php
 // Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '/xampp/htdocs/Proyecto/php_rest_api/config/Database.php';
include_once '/xampp/htdocs/Proyecto/php_rest_api/models/Trabajadores.php';

// Instantiate DB & connect
$database = new Database();
$db= $database->connect();

// Instantiate blog post object
$Trabajadores= new Trabajadores($db);

//Get Trabajadores
$Trabajadores->NumCedula= isset($_GET['NumCedula']) ? $_GET['NumCedula'] : die();

// Get Trabajadores
$Trabajadores->read_single();

// Create array
$Trabajadores_arr= array(
    'NumCedula'=>$Trabajadores->NumCedula,
    'nombre'=>$Trabajadores->nombre,
    'apellido_1'=>$Trabajadores->apellido_1,
    'apellido_2'=>$Trabajadores->apellido_2,
    'usuario'=>$Trabajadores->usuario,
    'NumTarjeta'=>$Trabajadores->NumTarjeta,
);
//Make JSON
print_r(json_encode($Trabajadores_arr));
