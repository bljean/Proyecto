<?php
 // Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '/xampp/htdocs/Proyecto/php_rest_api/config/Database.php';
include_once '/xampp/htdocs/Proyecto/php_rest_api/models/Contratodocencia.php';

// Instantiate DB & connect
$database = new Database();
$db= $database->connect();

// Instantiate blog post object
$contratodocencia= new contratodocencia($db);

//Blog estudiante query
$result = $contratodocencia->read();

//Get row count
$num= $result->rowCount();
//Check if any gruop
if($num >0){
    // Post array
    $contratodocencia_arr = array();
    //$posts_arr['data']= array();
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $contratodocencia_item = array( 
            'CodTema'=>$CodTema,
            'CodTp'=>$CodTp,
            'Numgrupo'=>$Numgrupo,
            'CodCampus'=>$CodCampus,
            'AnoAcad'=>$AnoAcad,
            'NumPer'=>$NumPer,
            'NumCedula'=>$NumCedula,
            
        );
        // Push to "data"
        array_push($contratodocencia_arr, $contratodocencia_item);
        //array_push($posts_arr['data'],$post_item);
    }

    // Turn to JSON & output
    echo json_encode($contratodocencia_arr);
}else{
    //No Posts
    echo json_encode(
        array('message'=>'No Posts Found')
    );
}
?>