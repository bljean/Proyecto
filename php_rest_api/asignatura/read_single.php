<?php
 // Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '/xampp/htdocs/Proyecto/php_rest_api/config/Database.php';
include_once '/xampp/htdocs/Proyecto/php_rest_api/models/Asignatura.php';

// Instantiate DB & connect
$database = new Database();
$db= $database->connect();

// Instantiate blog post object
$Asignatura= new Asignatura($db);

//Get Trabajadores
$Asignatura->CodTema= isset($_GET['CodTema']) ? $_GET['CodTema'] : die();
$Asignatura->CodTp= isset($_GET['CodTp']) ? $_GET['CodTp'] : die();
$Asignatura->Nombre= isset($_GET['Nombre']) ? $_GET['Nombre'] : die();
$Asignatura->NumCreditos= isset($_GET['NumCreditos']) ? $_GET['NumCreditos'] : die();

// Get Trabajadores
$Asignatura->read_single();

// Create array
$Asignatura_arr= array(
    'CodTema'=>$Asignatura->CodTema,
    'CodTp'=>$Asignatura->CodTp,
    'Nombre'=>$Asignatura->Nombre,
    'NumCreditos'=>$Asignatura->NumCreditos,
   
);
//Make JSON
print_r(json_encode($Asignatura_arr));

?>