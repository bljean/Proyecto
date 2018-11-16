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
$Asignatura= new asignatura($db);

//Blog estudiante query
$result = $Asignatura->read();

//Get row count
$num= $result->rowCount();
//Check if any gruop
if($num >0){
    // Post array
    $Asignatura_arr = array();
    //$posts_arr['data']= array();
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $Asignatura_item = array( 
            'CodTema'=>$CodTema,
            'CodTp'=>$CodTp,
            'Nombre'=>$Nombre,
            'NumCreditos'=>$NumCreditos,
        );
        // Push to "data"
        array_push($Asignatura_arr, $Asignatura_item);
        //array_push($posts_arr['data'],$post_item);
    }

    // Turn to JSON & output
    echo json_encode($Asignatura_arr);
}else{
    //No Posts
    echo json_encode(
        array('message'=>'No Posts Found')
    );
}
?>