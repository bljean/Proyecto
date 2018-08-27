<?php 
//require '../vendor/autoload.php';
require '/xampp/htdocs/Proyecto/vendor/autoload.php';

use Goutte\Client;
//connection
$url = "http://169.254.65.123/";
date_default_timezone_set('America/Santo_Domingo');
if (isset($_POST['key'])){ 
    if($_POST['key'] == 'getCardNumber'){
        //-------------------------------------------------------------
        //get the page: 
        $client = new Client();
        $crawler = $client->request('GET', $url);
        //-------------------------------------------------------------
        //login and sumbit:
        $form = $crawler->selectButton('Login')->form();
        $form['username'] = 'abc';
        $form['pwd'] = '654321';
        $crawler = $client->submit($form);
        //-------------------------------------------------------------
        //get the swipe info:
        $form = $crawler->selectButton('Swipe')->form();
        $crawler = $client->submit($form);
        $uri = $form->getUri();
        $method = $form->getMethod();
        $values = $form->getValues();
        $message = $crawler->filter('tr.N')->first()->html();
        //-------------------------------------------------------------
        //print the value
        getSwipeInfo($message);
        
       
        
    }
}
//-------------------------------------------------------------
//function
function getSwipeInfo($text){
    list($id, $cardnumber, $name,$status,$DataTime) = explode('</td>', $text);
    compareInfo($cardnumber,$status,$DataTime);

    }
function compareInfo($cardnumber,$Status,$DataTime){
    $date = date('Y-m-d');
    $time= date('H:i:s');
    $cardN = eraseTd($cardnumber);
    $status = eraseTd($Status);
    $datatime = eraseTd($DataTime);
        
    $sqlStudentName = connectBd()->query( "SELECT * FROM estudiante WHERE NumTarjeta='$cardN'");
    $sqlWorkersName = connectBd()->query( "SELECT * FROM trabajadores WHERE NumTarjeta='$cardN'");
    $sqlDataTime = connectBd()->query( "SELECT * FROM swipe WHERE Fecha='$date' AND Tiempo='$time'");
    
    if($sqlStudentName->num_rows > 0 OR $sqlWorkersName->num_rows > 0){
        
        $jsonArray = array(
            'body'=> $cardN,
            'status'=> '0',
        );
        exit(json_encode($jsonArray));
    }else{
            /*if($sqlDataTime->num_rows == 0)
             {
                exit($cardN);
            }else{
                exit("This report exist: $cardN, $datatime");
            }*/
            $jsonArray = array(
                'body'=> $cardN,
                'status'=> '1',
            );
            exit(json_encode($jsonArray));
        }
    
}

function eraseTd($text){
    list($td,$new) = explode('<td>', $text);
    return $new;
}
function connectBd(){
    $user='root';
    $pass='';
    $db='proyectofinal';
    $conn= new mysqli('localhost',$user, $pass, $db);
    return $conn;
}
?>