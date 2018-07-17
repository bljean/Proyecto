
<?php 
//require '../vendor/autoload.php';
require '/xampp/htdocs/Proyecto/vendor/autoload.php';

use Goutte\Client;
//connection
$url = "http://169.254.65.123/";

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
var_dump($message);
getSwipeInfo($message);
//-------------------------------------------------------------
//function
function getSwipeInfo($text){
list($id, $cardnumber, $name,$status,$DataTime) = explode('</td>', $text);
echo "<br />\nid: $id;<br />\n cardnumber: $cardnumber;<br />\n name: $name;<br />\n status: $status;<br />\n datatime: $DataTime<br />\n";
echo compareInfo($cardnumber,$status,$DataTime);
}
function compareInfo($cardnumber,$Status,$DataTime){
    /*$user='root';
    $pass='';
    $db='proyectofinal';
    $conn= new mysqli('localhost',$user, $pass, $db);
    */
    $cardN = eraseTd($cardnumber);
    $status = eraseTd($Status);
    $status1= eraseTd1($status);
    $datatime = eraseTd($DataTime);
    
    $sqlStudentName = connectBd()->query( "SELECT nombre, apellido, matricula FROM estudiante WHERE CardNumber='$cardN'");
    $sqlProfessorName = connectBd()->query( "SELECT nombre, apellido, usuario FROM profesor WHERE CardNumber='$cardN'");
    $sqlWorkersName = connectBd()->query( "SELECT nombre, apellido, cedula FROM trabajadores WHERE CardNumber='$cardN'");
    $sqlDataTime = connectBd()->query( "SELECT dataTime FROM swipe WHERE dataTime='$datatime'");

    if($sqlStudentName->num_rows > 0 OR $sqlProfessorName->num_rows > 0 OR $sqlWorkersName->num_rows > 0)
    {
        if($sqlStudentName->num_rows > 0 AND $sqlDataTime->num_rows == 0)
        {
            $index=1;       
            swipeRecord($cardN,$sqlStudentName,$sqlProfessorName,$sqlWorkersName, $status1,$datatime,$index);
            exit("Card with this number exist :$cardN ,$datatime ");
        }
        if($sqlProfessorName->num_rows > 0 AND $sqlDataTime->num_rows == 0)
        {
            $index=1;
            swipeRecord($cardN,$sqlStudentName,$sqlProfessorName,$sqlWorkersName,$status1,$datatime,$index);
            exit("Card with this number exist :$cardN ,$datatime ");
        }
        if( $sqlWorkersName->num_rows > 0 AND $sqlDataTime->num_rows == 0)
        {
            $index=1; 
            swipeRecord($cardN,$sqlStudentName,$sqlProfessorName,$sqlWorkersName,$status1,$datatime,$index);
            exit("Card with this number exist :$cardN ,$datatime ");
        }
    }else{
            if($sqlDataTime->num_rows == 0)
            {
                $index=0;
                swipeRecord($cardN,$sqlStudentName,$sqlProfessorName,$sqlWorkersName,$status1,$datatime,$index);
                exit("This card number do not exist: $cardN, $datatime");
            }
        }
    exit ("This report exist: $cardN, $datatime");
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
function eraseTd($text){
    list($td,$new) = explode('<td>', $text);
    return $new;
}
function eraseTd1($text){
    list($new) = explode('<td>', $text);
    return $new;
}

function swipeRecord($cardN,$sqlStudentName,$sqlProfessorName,$sqlWorkersName,$status1,$datatime,$index){
    $ID=setRowId();
    if($index== 1 AND $status1!="Reboot"){
        
        if($sqlStudentName->num_rows > 0 ){
            while($data= $sqlStudentName->fetch_array()){
                $name=$data["nombre"];
                $apellido=$data["apellido"];
                $personid=$data["matricula"];
               
            }
          
        }
        if($sqlProfessorName->num_rows > 0){
            while($data= $sqlProfessorName->fetch_array()){
                $name=$data["nombre"];
                $apellido=$data["apellido"];
                $personid=$data["usuario"];
            }
        }
        if($sqlWorkersName->num_rows > 0){
            while($data= $sqlWorkersName->fetch_array()){
                $name=$data["nombre"];
                $apellido=$data["apellido"];
                $personid=$data["cedula"];
            }
        }
        reconigtion($personid);
        connectBd()->query("INSERT INTO swipe (id, cardnumber,name, status, dataTime) VALUES('$ID','$cardN','$name $apellido','$status1','$datatime')");
    } else if($index == 0 AND $status1!="Reboot"){
        connectBd()->query("INSERT INTO swipe (id, cardnumber,name, status, dataTime) VALUES('$ID','$cardN','N/A','$status1','$datatime')");
    }
}
function setRowId(){
    /*$user='root';
    $pass='';
    $db='proyectofinal';
    $conn= new mysqli('localhost',$user, $pass, $db);*/

    $sql = connectBd()->query( "SELECT * FROM swipe");
        if($sql->num_rows > 0){
            $ID= $sql->num_rows + 1;
        }else{
            $ID= 1 ;
        }
        return $ID;
}

function connectBd(){
    $user='root';
    $pass='';
    $db='proyectofinal';
    $conn= new mysqli('localhost',$user, $pass, $db);
    return $conn;
}

?>