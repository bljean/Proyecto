<?php 
require '/xampp/htdocs/Proyecto/vendor/autoload.php';
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
//connection
$url = "http://169.254.65.123/";
date_default_timezone_set('America/Santo_Domingo');
$CodCampus = "ST";
$CodEdif = "A1";
$CodSalon = 14;

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
    echo compareInfo($cardnumber,$status,$newDate,$Time);
    }
function eraseTd($text){
        list($td,$new) = explode('<td>', $text);
        return $new;
    } 
function compareInfo($cardN,$status,$data,$time){
       

        $sqlStudentName = connectBd()->query( "SELECT nombre, apellido, Matricula FROM estudiante WHERE NumTarjeta='$cardN'");
        $sqlWorkersName = connectBd()->query( "SELECT nombre, apellido_1, NumCedula FROM trabajadores WHERE NumTarjeta='$cardN'");
        $sqlDataTime = connectBd()->query( "SELECT dataTime FROM swipe WHERE dataTime='$data $time'");
    
        if($sqlStudentName->num_rows > 0 OR $sqlWorkersName->num_rows > 0)
        {
            if($sqlStudentName->num_rows > 0 AND $sqlDataTime->num_rows == 0)
            {
                $index=1;       
                swipeRecord($cardN,$sqlStudentName,$sqlWorkersName, $status,$data,$time,$index);
                return("Card with this number exist :$cardN ,$data,$time \n");
            }
            if( $sqlWorkersName->num_rows > 0 AND $sqlDataTime->num_rows == 0)
            {
                $index=1; 
                swipeRecord($cardN,$sqlStudentName,$sqlWorkersName,$status,$data,$time,$index);
                return("Card with this number exist :$cardN ,$data,$time \n");
            }
        }else{
                if($sqlDataTime->num_rows == 0)
                {
                    $index=0;
                    swipeRecord($cardN,$sqlStudentName,$sqlWorkersName,$status,$data,$time,$index);
                    return("This card number do not exist: $cardN, $data,$time\n");
                }
            }
        return("This report exist: $cardN, $data $time\n");
    }
    

function swipeRecord($cardN,$sqlStudentName,$sqlWorkersName,$status1,$date,$time,$index){
    
        if($index== 1 AND $status1!="Reboot"){
            
            if($sqlStudentName->num_rows > 0 ){
                while($data= $sqlStudentName->fetch_array()){
                    $name=$data["nombre"];
                    $apellido=$data["apellido"];
                    $personid=$data["Matricula"];
                    
                }
                echo "\n $personid\n";
                getStudentGroup($personid);
            }
            if($sqlWorkersName->num_rows > 0){
                while($data= $sqlWorkersName->fetch_array()){
                    $name=$data["nombre"];
                    $apellido=$data["apellido_1"];
                    $personid=$data["NumCedula"];
                }
                echo "\n $personid\n";
                getProfesorGroup($personid);
                
            }
            
            //reconigtion($personid);
            connectBd()->query("INSERT INTO swipe (cardnumber,name, status, dataTime) VALUES('$cardN','$name $apellido','Abierta','$date $time')");
        } else if($index == 0 AND $status1!="Reboot"){
            connectBd()->query("INSERT INTO swipe (cardnumber,name, status, dataTime) VALUES('$cardN','N/A','Cerrada','$date $time')");
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
            echo "Eres: $personid\n";
            openDoor(); 
            
        }else if($output[0]=="0"){
            echo "No eres:$personid\n";
        }
    }    
function connectBd(){
        $user='root';
        $pass='';
        $db='proyectofinal';
        $conn= new mysqli('localhost',$user, $pass, $db);
        return $conn;
    }
function getStudentGroup($matricula){
    $codcampus = $GLOBALS['CodCampus'];
    $codedif =$GLOBALS['CodEdif'];
    $codsalon =$GLOBALS['CodSalon'];
    $date = date('Y/m/d');
    $time= date('H:i:s');
    $day= getWeekday($date);
    $sqlStudentGrupo=connectBd()->query( "SELECT horariogrupoactivo.CodTema as Codtema,horariogrupoactivo.CodTP as CodTP,horariogrupoactivo.HoraInicio as HoraInicio ,horariogrupoactivo.Horafin as Horafin,horariogrupoactivo.NumGrupo as NumGrupo,horariogrupoactivo.CodCampus as CodCampus,horariogrupoactivo.AnoAcad as AnoAcad,horariogrupoactivo.NumPer as NumPer FROM horariogrupoactivo INNER JOIN grupoinsest on horariogrupoactivo.Codtema=grupoinsest.Codtema AND horariogrupoactivo.CodTP=grupoinsest.CodTP AND horariogrupoactivo.NumGrupo=grupoinsest.NumGrupo AND horariogrupoactivo.CodCampus=grupoinsest.CodCampus AND horariogrupoactivo.AnoAcad=grupoinsest.AnoAcad AND horariogrupoactivo.NumPer=grupoinsest.NumPer AND grupoinsest.Matricula= $matricula AND horariogrupoactivo.Sal_CodCampus='$codcampus' AND horariogrupoactivo.Sal_CodEdif='$codedif' AND horariogrupoactivo.Sal_CodSalon=$codsalon AND horariogrupoactivo.HoraInicio<='$time' AND horariogrupoactivo.Horafin >= '$time' AND horariogrupoactivo.DiaSem='$day'");
    if($sqlStudentGrupo->num_rows >0){
        echo "fuciona \n";
        
        while($data= $sqlStudentGrupo->fetch_array()){
            $horaini=$data["HoraInicio"];
            $horafin=$data["Horafin"];
            $NumGrupo=$data["NumGrupo"];
            $Codtema=$data["Codtema"];
            $CodTP=$data["CodTP"];
            $CodCampus=$data["CodCampus"];
            $AnoAcad=$data["AnoAcad"];
            $NumPer=$data["NumPer"];
        }
        attendEstRecord($matricula,$date,$horaini,$time,$horafin,$day,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer);
    }else echo"no fuciona";
        
    return $sqlStudentGrupo;
    }
function getProfesorGroup($numCedula){
    $codcampus = $GLOBALS['CodCampus'];
    $codedif =$GLOBALS['CodEdif'];
    $codsalon =$GLOBALS['CodSalon'];
    $date = date('Y/m/d');
    $time= date('H:i:s');
    $day= getWeekday($date);
    $sqlProfessorGrupo=connectBd()->query( "SELECT horariogrupoactivo.CodTema as Codtema,horariogrupoactivo.CodTP as CodTP,horariogrupoactivo.HoraInicio as HoraInicio ,horariogrupoactivo.Horafin as Horafin,horariogrupoactivo.NumGrupo as NumGrupo,horariogrupoactivo.CodCampus as CodCampus,horariogrupoactivo.AnoAcad as AnoAcad,horariogrupoactivo.NumPer as NumPer FROM horariogrupoactivo INNER JOIN contratodocencia on horariogrupoactivo.Codtema=contratodocencia.Codtema AND horariogrupoactivo.CodTP=contratodocencia.CodTP AND horariogrupoactivo.NumGrupo=contratodocencia.NumGrupo AND horariogrupoactivo.CodCampus=contratodocencia.CodCampus AND horariogrupoactivo.AnoAcad=contratodocencia.AnoAcad AND horariogrupoactivo.NumPer=contratodocencia.NumPer AND contratodocencia.NumCedula=$numCedula AND horariogrupoactivo.Sal_CodCampus='$codcampus' AND horariogrupoactivo.Sal_CodEdif='$codedif' AND horariogrupoactivo.Sal_CodSalon=$codsalon AND horariogrupoactivo.HoraInicio<='$time' AND horariogrupoactivo.Horafin >= '$time' AND horariogrupoactivo.DiaSem='$day'");
    $sqlProfessor=connectBd()->query("SELECT Codtema FROM contratodocencia WHERE NumCedula=$numCedula");
    if($sqlProfessorGrupo->num_rows >0){
        echo "fuciona\n";
        while($data= $sqlProfessorGrupo->fetch_array()){
            $horaini=$data["HoraInicio"];
            $horafin=$data["Horafin"];
            $NumGrupo=$data["NumGrupo"];
            $Codtema=$data["Codtema"];
            $CodTP=$data["CodTP"];
            $CodCampus=$data["CodCampus"];
            $AnoAcad=$data["AnoAcad"];
            $NumPer=$data["NumPer"];
        }
        attendEstRecord($numCedula,$date,$horaini,$time,$horafin,$day,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer);
    }elseif($sqlProfessor->num_rows >0){
        echo "no abrir puerta";
    }else {
        echo"abrir puerta al trabajador";
        openDoor();
    }
    return  $sqlProfessorGrupo;
        }
function attendEstRecord($matricula,$date,$horaini,$time,$horafin,$day,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer){
    $codcampus = $GLOBALS['CodCampus'];
    $codedif =$GLOBALS['CodEdif'];
    $codsalon =$GLOBALS['CodSalon'];
    $sqlStudentattend = connectBd()->query( "SELECT * FROM asistencia WHERE ID='$matricula' AND Fecha='$date' AND Horaini='$horaini'");
    if($sqlStudentattend->num_rows > 0){
        echo "ya esta precente";
        openDoor();
    }else {
        if($time < getHorausencia($horafin)){
            connectBd()->query("INSERT INTO asistencia (ID,Fecha,Horaini,Horaentrada,Horafin,Sal_CodCampus,Sal_CodEdif,Sal_CodSalon,Diasemana,Presencia,NumGrupo,CodTema,CodTP,CodCampus,AnoAcad,NumPer) VALUES('$matricula','$date','$horaini','$time','$horafin','$codcampus','$codedif','$codsalon','$day','P','$NumGrupo','$Codtema','$CodTP','$CodCampus','$AnoAcad','$NumPer')");
            openDoor();
        }else echo "LLego tarde ya le toca ausencia";
    }        
    }
function checkGroupTime(){
 connectBd()->query("SELECT * FROM horariogrupoactivo WHERE ");
}
function getWeekday($date) {
        return date('w', strtotime($date));
    }
function getHorausencia($Horafin){
    $Horafin = strtotime($Horafin);
    $horadeAusencia = date('H:i:s', strtotime('-10 minutes', $Horafin));
    return $horadeAusencia;
}    
?>