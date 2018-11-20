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

//Blog estudiante query
$result = $horariogrupoactivo->read();
//Get row count
$num= $result->rowCount();
//Check if any estudiante
if($num >0){
    // Post array
    $horariogrupoactivo_arr = array();
    //$posts_arr['data']= array();
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $horariogrupoactivo_item = array( 
            'CodTema'=>$CodTema,
            'CodTP'=>$CodTP,
            'NumGrupo'=>$NumGrupo,
            'CodCampus'=>$CodCampus,
            'AnoAcad'=>$AnoAcad,
            'NumPer'=>$NumPer,
            'HoraInicio'=>$HoraInicio,
            'Horafin'=>$Horafin,
            'Sal_CodCampus'=>$Sal_CodCampus,
            'Sal_CodEdif'=>$Sal_CodEdif,
            'Sal_CodSalon'=>$Sal_CodSalon,
           
        );
        // Push to "data"
        array_push($horariogrupoactivo_arr, $horariogrupoactivo_item);
        //array_push($posts_arr['data'],$post_item);
    }

    // Turn to JSON & output
    echo json_encode($horariogrupoactivo_arr);
}else{
    //No Posts
    echo json_encode(
        array('message'=>'No Posts Found')
    );
}
?>