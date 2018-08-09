<?php 
require '/xampp/htdocs/Proyecto/vendor/autoload.php';
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
//connection
$url = "http://169.254.65.123/";
$CodCampus = "ST";
$CodEdif = "A2";
$CodSalon = 12;

//-------------------------------------------------------------
//get the page: 
$client = new Client();
$guzzleClient = new GuzzleClient(array(
    'timeout' => 60,
));
$client->setClient($guzzleClient);
$crawler = $client->request('GET', $url);
//-------------------------------------------------------------
//login and sumbit:
$form = $crawler->selectButton('Login')->form();
$form['username'] = 'abc';
$form['pwd'] = '654321';
$crawler = $client->submit($form);
//-------------------------------------------------------------
//get the swipe page:
$form = $crawler->selectButton('Swipe')->form();
$crawler = $client->submit($form);
$compare=getinfo($crawler);
getSwipeInfo($compare);
//-------------------------------------------------------------
//get the swipe info:
//$compare = getinfo($crawler);
//getSwipeInfo($compare);
/*$compare1 = getinfo($crawler);
echo $compare1;*/
//getSwipeInfo($compare1);
while(1){
    
    $message = getinfo($crawler);
    if($compare !== $message){
        
        //code-----------------------------------------
        getSwipeInfo($message);
        //end------------------------------------------
        $compare=$message;
    }
    $crawler=refresh($crawler, $client);
}

function refresh($crawler,$client){
    $form = $crawler->selectButton('Users')->form();
    $crawler = $client->submit($form);

    $form = $crawler->selectButton('Swipe')->form();
    $crawler = $client->submit($form);
    return  $crawler;
    }
function getinfo($crawler){
   return $crawler->filter('tr.N')->first()->html();
    }
function getSwipeInfo($text){
    list($id, $cardnumber, $name,$status,$DataTime) = explode('</td>', $text);
    $id= eraseTd($id);
    $cardnumber= eraseTd($cardnumber);
    $status= eraseTd($status);
    $DataTime = eraseTd($DataTime);
    list($Date,$Time) = explode(' ', $DataTime);
        $Date= strtotime($Date);
        $newDate = date('Y-m-d',$Date);
    
    echo "\nid: $id\ncardnumber:$cardnumber\nstatus:$status\ndatatime: $newDate + $Time\n";
    //echo compareInfo($cardnumber,$status,$newDate,$Time);
    }
function eraseTd($text){
        list($td,$new) = explode('<td>', $text);
        return $new;
    } 
function compareInfo($cardN,$status,$data,$time){
       

        $sqlStudentName = connectBd()->query( "SELECT nombre, apellido, Matricula FROM estudiante WHERE NumTarjeta='$cardN'");
        $sqlWorkersName = connectBd()->query( "SELECT nombre, apellido_1, NumCedula FROM trabajadores WHERE NumTarjeta='$cardN'");
        $sqlDataTime = connectBd()->query( "SELECT dataTime FROM swipe WHERE dataTime='$datatime'");
    
        if($sqlStudentName->num_rows > 0 OR $sqlWorkersName->num_rows > 0)
        {
            if($sqlStudentName->num_rows > 0 AND $sqlDataTime->num_rows == 0)
            {
                $index=1;       
                swipeRecord($cardN,$sqlStudentName,$sqlWorkersName, $status,$datatime,$index);
                return("Card with this number exist :$cardN ,$datatime ");
            }
            if( $sqlWorkersName->num_rows > 0 AND $sqlDataTime->num_rows == 0)
            {
                $index=1; 
                swipeRecord($cardN,$sqlStudentName,$sqlWorkersName,$status,$datatime,$index);
                return("Card with this number exist :$cardN ,$datatime ");
            }
        }else{
                if($sqlDataTime->num_rows == 0)
                {
                    $index=0;
                    swipeRecord($cardN,$sqlStudentName,$sqlWorkersName,$status,$datatime,$index);
                    return("This card number do not exist: $cardN, $datatime");
                }
            }
        return("This report exist: $cardN, $datatime");
    }
    

function swipeRecord($cardN,$sqlStudentName,$sqlWorkersName,$status1,$datatime,$index){
        if($index== 1 AND $status1!="Reboot"){
            
            if($sqlStudentName->num_rows > 0 ){
                while($data= $sqlStudentName->fetch_array()){
                    $name=$data["nombre"];
                    $apellido=$data["apellido"];
                    $personid=$data["Matricula"];
                   
                }
              
            }
            if($sqlWorkersName->num_rows > 0){
                while($data= $sqlWorkersName->fetch_array()){
                    $name=$data["nombre"];
                    $apellido=$data["apellido_1"];
                    $personid=$data["NumCedula"];
                }
            }
            openDoor();
            //reconigtion($personid);
            connectBd()->query("INSERT INTO swipe (cardnumber,name, status, dataTime) VALUES('$cardN','$name $apellido','$status1','$datatime')");
        } else if($index == 0 AND $status1!="Reboot"){
            connectBd()->query("INSERT INTO swipe (cardnumber,name, status, dataTime) VALUES('$cardN','N/A','$status1','$datatime')");
        }
    }
    
function openDoor(){
        $url = "http://169.254.65.123/";
        $client = new Client();
        $crawler = $client->request('GET', $url);
        //-------------------------------------------------------------
        //login and sumbit:
        $form = $crawler->selectButton('Login')->form();
        $form['username'] = 'abc';
        $form['pwd'] = '654321';
        $crawler = $client->submit($form);
    
        $form = $crawler->selectButton('Remote Open #1 Door m001-1')->form();
        $crawler = $client->submit($form);
    }
function reconigtion($personid){
        exec("python /xampp/htdocs/Proyecto/pages/Pagetheme/PythonCode/takePhoto.py $personid",$output);
        if($output[0]=="1"){
            openDoor(); 
            
        }else if(output[0]=="0"){
            
        }
    }    
function connectBd(){
        $user='root';
        $pass='';
        $db='proyectofinal';
        $conn= new mysqli('localhost',$user, $pass, $db);
        return $conn;
    }
?>