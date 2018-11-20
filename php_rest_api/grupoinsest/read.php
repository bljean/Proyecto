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

//Blog estudiante query
$result = $grupoinsest->read();

//Get row count
$num= $result->rowCount();
//Check if any gruop
if($num >0){
    // Post array
    $grupoinsest_arr = array();
    //$posts_arr['data']= array();
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $grupoinsest_item = array( 
            'Matricula'=>$Matricula,
            'CodTema'=>$CodTema,
            'CodTP'=>$CodTP,
            'Numgrupo'=>$Numgrupo,
            'CodCampus'=>$CodCampus,
            'AnoAcad'=>$AnoAcad,
            'NumPer'=>$NumPer,
            'NumAusencias'=>$NumAusencias,
        );
        // Push to "data"
        array_push($grupoinsest_arr, $grupoinsest_item);
        //array_push($posts_arr['data'],$post_item);
    }

    // Turn to JSON & output
    echo json_encode($grupoinsest_arr);
}else{
    //No Posts
    echo json_encode(
        array('message'=>'No Posts Found')
    );
}
?>