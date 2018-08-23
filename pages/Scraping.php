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
    checkGroupTime();
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
    $date = date('Y-m-d');
    $time= date('H:i:s');

        $sqlStudentName = connectBd()->query( "SELECT nombre, apellido, Matricula FROM estudiante WHERE NumTarjeta='$cardN'");
        $sqlWorkersName = connectBd()->query( "SELECT nombre, apellido_1, NumCedula FROM trabajadores WHERE NumTarjeta='$cardN'");
        $sqlDataTime = connectBd()->query( "SELECT * FROM swipe WHERE Fecha='$date' AND Tiempo='$time'");
    
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
                getStudentGroup($personid,$cardN,$name,$apellido);
            }
            if($sqlWorkersName->num_rows > 0){
                while($data= $sqlWorkersName->fetch_array()){
                    $name=$data["nombre"];
                    $apellido=$data["apellido_1"];
                    $personid=$data["NumCedula"];
                }
                echo "\n $personid\n";
                getProfesorGroup($personid,$cardN,$name,$apellido);
                
            }
            //reconigtion($personid);
            //connectBd()->query("INSERT INTO swipe (cardnumber,name, status, dataTime) VALUES('$cardN','$name $apellido','Permitido','$date $time')");
        } else if($index == 0 AND $status1!="Reboot"){
            insertSwipeRecord($cardN,$personid,$Nombre,$apellido,'Denegado');
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
            return 1;
        }else if($output[0]=="0"){
            echo "No eres:$personid\n";
            return 0;
        }
    }    
function connectBd(){
        $user='root';
        $pass='';
        $db='proyectofinal';
        $conn= new mysqli('localhost',$user, $pass, $db);
        return $conn;
    }
function getStudentGroup($matricula,$cardN,$name,$apellido){
    $codcampus = $GLOBALS['CodCampus'];
    $codedif =$GLOBALS['CodEdif'];
    $codsalon =$GLOBALS['CodSalon'];
    $date = date('Y-m-d');
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
        attendEstRecord($matricula,$date,$horaini,$time,$horafin,$day,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,'P',$cardN,$name,$apellido);
    }else {
        echo"no fuciona";
        insertSwipeRecord($cardN,$matricula,$name,$apellido,'Denegado');
    }
    }
function getProfesorGroup($numCedula,$cardN,$name,$apellido){
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
        attendEstRecord($numCedula,$date,$horaini,$time,$horafin,$day,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,'P',$cardN,$name,$apellido);
    }elseif($sqlProfessor->num_rows >0){
        echo "no abrir puerta";
        insertSwipeRecord($cardN,$numCedula,$name,$apellido,'Denegado');
    }else {
        echo"abrir puerta al trabajador";
        insertSwipeRecord($cardN,$numCedula,$name,$apellido,'Permitido');
        openDoor();
    }
    return  $sqlProfessorGrupo;
        }
function attendEstRecord($matricula,$date,$horaini,$time,$horafin,$day,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,$Precencia,$cardN,$name,$apellido){
    $codcampus = $GLOBALS['CodCampus'];
    $codedif =$GLOBALS['CodEdif'];
    $codsalon =$GLOBALS['CodSalon'];
    $horasPresente=totalHorasAsistencia($horaini,$horafin,$time,$Precencia);
    $sqlStudentattend = connectBd()->query( "SELECT * FROM asistencia WHERE ID='$matricula' AND Fecha='$date' AND Horaini='$horaini'");
    //if(){

    //}
    if($sqlStudentattend->num_rows > 0){
        echo "ya esta precente";
        reconigtion($matricula);
        insertSwipeRecord($cardN,$matricula,$name,$apellido,'Permitido');
    }else {
        if($Precencia=='A' OR $Precencia=='R'){
            //reconigtion($matricula);
            connectBd()->query("INSERT INTO asistencia (ID,Fecha,Horaini,Horafin,HorasPresente,Sal_CodCampus,Sal_CodEdif,Sal_CodSalon,Diasemana,Presencia,NumGrupo,CodTema,CodTP,CodCampus,AnoAcad,NumPer) VALUES('$matricula','$date','$horaini','$horafin','$horasPresente','$codcampus','$codedif','$codsalon','$day','$Precencia','$NumGrupo','$Codtema','$CodTP','$CodCampus','$AnoAcad','$NumPer')");
        }else {
            if(reconigtion($matricula)==1){
                connectBd()->query("INSERT INTO asistencia (ID,Fecha,Horaini,Horaentrada,Horafin,HorasPresente,Sal_CodCampus,Sal_CodEdif,Sal_CodSalon,Diasemana,Presencia,NumGrupo,CodTema,CodTP,CodCampus,AnoAcad,NumPer) VALUES('$matricula','$date','$horaini','$time','$horafin','$horasPresente','$codcampus','$codedif','$codsalon','$day','$Precencia','$NumGrupo','$Codtema','$CodTP','$CodCampus','$AnoAcad','$NumPer')");
            }
            insertSwipeRecord($cardN,$matricula,$name,$apellido,'Permitido');
            
        } 
    }        
    }
function checkGroupTime(){
    $codcampus = $GLOBALS['CodCampus'];
    $codedif =$GLOBALS['CodEdif'];
    $codsalon =$GLOBALS['CodSalon'];
    $date = date('Y/m/d');
    $time= date('H:i:s');
    $day= getWeekday($date);
    $sqlHorariogrupotime= connectBd()->query("SELECT Codtema, CodTP,HoraInicio , Horafin, NumGrupo, CodCampus, AnoAcad, NumPer FROM horariogrupoactivo WHERE horariogrupoactivo.Sal_CodCampus='$codcampus' AND horariogrupoactivo.Sal_CodEdif='$codedif' AND horariogrupoactivo.Sal_CodSalon='$codsalon' AND horariogrupoactivo.HoraInicio<='$time' AND horariogrupoactivo.Horafin >= '$time' AND horariogrupoactivo.DiaSem='$day'");
       if($sqlHorariogrupotime->num_rows >0){
        while($data= $sqlHorariogrupotime->fetch_array()){
            $HoraInicio=$data['HoraInicio'];
            $Horafin=$data['Horafin'];
            $NumGrupo=$data["NumGrupo"];
            $Codtema=$data["Codtema"];
            $CodTP=$data["CodTP"];
            $CodCampus=$data["CodCampus"];
            $AnoAcad=$data["AnoAcad"];
            $NumPer=$data["NumPer"];
        }
        ausencia($HoraInicio,$Horafin,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer);
       }//else echo "No grupo a esta hora";
    }
function ausencia($horaini,$horafin,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer){
    $date = date('Y-m-d');
    $time= date('H:i:s');
    $horadeAusencia = getHorapresencia($horaini,$horafin,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer);
    if($time > $horadeAusencia){
        $sqlAusentesEst=connectBd()->query("SELECT grupoinsest.Matricula as Matricula FROM grupoinsest LEFT JOIN asistencia ON asistencia.ID=grupoinsest.Matricula AND asistencia.CodTema=grupoinsest.CodTema AND asistencia.CodTP= grupoinsest.CodTP AND asistencia.CodCampus= grupoinsest.CodCampus AND asistencia.NumGrupo= grupoinsest.Numgrupo AND asistencia.AnoAcad=grupoinsest.AnoAcad AND asistencia.NumPer= grupoinsest.NumPer AND asistencia.Fecha= '$date' WHERE grupoinsest.CodTema='$Codtema' AND grupoinsest.CodTP='$CodTP' AND grupoinsest.CodCampus='$CodCampus' AND grupoinsest.Numgrupo='$NumGrupo' AND grupoinsest.AnoAcad='$AnoAcad' AND grupoinsest.NumPer='$NumPer' AND asistencia.ID is NULL");
        $sqlAusenteProf=connectBd()->query("SELECT contratodocencia.NumCedula as NumCedula FROM contratodocencia LEFT JOIN asistencia ON asistencia.ID=contratodocencia.NumCedula AND asistencia.CodTema=contratodocencia.CodTema AND asistencia.CodTP= contratodocencia.CodTp AND asistencia.CodCampus= contratodocencia.CodCampus AND asistencia.NumGrupo= contratodocencia.Numgrupo AND asistencia.AnoAcad=contratodocencia.AnoAcad AND asistencia.NumPer= contratodocencia.NumPer AND asistencia.Fecha= '$date' WHERE contratodocencia.CodTema='$Codtema' AND contratodocencia.CodTP='$CodTP' AND contratodocencia.CodCampus='$CodCampus' AND contratodocencia.Numgrupo='$NumGrupo' AND contratodocencia.AnoAcad='$AnoAcad' AND contratodocencia.NumPer='$NumPer' AND asistencia.ID is NULL");
        if($sqlAusenteProf->num_rows>0){
            $sqlPresentesEst=connectBd()->query("SELECT asistencia.ID as Matricula FROM grupoinsest LEFT JOIN asistencia ON asistencia.ID=grupoinsest.Matricula AND asistencia.CodTema=grupoinsest.CodTema AND asistencia.CodTP= grupoinsest.CodTP AND asistencia.CodCampus= grupoinsest.CodCampus AND asistencia.NumGrupo= grupoinsest.Numgrupo AND asistencia.AnoAcad=grupoinsest.AnoAcad AND asistencia.NumPer= grupoinsest.NumPer AND asistencia.Fecha= '$date' WHERE grupoinsest.CodTema='$Codtema' AND grupoinsest.CodTP='$CodTP' AND grupoinsest.CodCampus='$CodCampus' AND grupoinsest.Numgrupo='$NumGrupo' AND grupoinsest.AnoAcad='$AnoAcad' AND grupoinsest.NumPer='$NumPer' AND asistencia.Presencia='P' ");
            
            while($data=$sqlAusenteProf->fetch_array()){
                attendEstRecord($data['NumCedula'],$date,$horaini,$time,$horafin,getWeekday($date),$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,'R','1111','nada','nada');
            }
            if($sqlPresentesEst->num_rows>0){
                while($data=$sqlPresentesEst->fetch_array()){
                    $matricula=$data['Matricula'];
                    connectBd()->query("UPDATE asistencia SET Presencia = 'R' WHERE asistencia.ID = '$matricula' AND asistencia.Fecha = '$date' AND asistencia.Horaini = '$horaini' AND asistencia.NumGrupo =$NumGrupo AND asistencia.CodTema = '$Codtema' AND asistencia.CodTP = $CodTP AND asistencia.CodCampus = '$CodCampus' AND asistencia.AnoAcad = $AnoAcad AND asistencia.NumPer = $NumPer");
                }
                
            }
            if($sqlAusentesEst->num_rows>0){
                while($data=$sqlAusentesEst->fetch_array()){
                    attendEstRecord($data['Matricula'],$date,$horaini,$time,$horafin,getWeekday($date),$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,'R','1111','nada','nada');
                }
            }    
        }else{
            if($sqlAusentesEst->num_rows>0){
                while($data=$sqlAusentesEst->fetch_array()){
                    attendEstRecord($data['Matricula'],$date,$horaini,$time,$horafin,getWeekday($date),$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,'A','1111','nada','nada');
                }
            }
        }
       //else echo "ya estan ausentes";
            
    }//else echo" todavia es tiempo de entrar \n";
    }
function getWeekday($date) {
        return date('w', strtotime($date));
    }
function getHorausencia($Horafin){
    $Horafin = strtotime($Horafin);
    $horadeAusencia = date('H:i:s', strtotime('-10 minutes', $Horafin));
    return $horadeAusencia;
    }
function getHorapresencia($horaini,$horafin,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer){
        $time1 = strtotime($horaini);
        $time2 = strtotime($horafin);
        $sqltiempolimite= connectBd()->query("SELECT PTLimiteH FROM configuraciongrupo WHERE CodTema='$Codtema'AND CodTp='$CodTP' AND NumGrupo='$NumGrupo' AND CodCampus='$CodCampus' AND AnoAcad='$AnoAcad' AND NumPer='$NumPer' ");
        if($sqltiempolimite->num_rows >0){
            while($data=$sqltiempolimite->fetch_array()){
                $tiempolimite=$data["PTLimiteH"];
            }
    
        }
        $totalHoras = round(abs($time2 - $time1) / 3600,2);
        $whole = floor($totalHoras);
        $tiempolimite*=$whole;
        $horadeAusencia = date('H:i:s', strtotime('+'.$tiempolimite.' minutes', $time1));
        return $horadeAusencia;
    
    }
function insertSwipeRecord($NumTarjeta,$ID,$Nombre,$apellido,$Acceso){
    
    $codcampus = $GLOBALS['CodCampus'];
    $codedif =$GLOBALS['CodEdif'];
    $codsalon =$GLOBALS['CodSalon'];
    $date = date('Y-m-d');
    $time= date('H:i:s');
    $day= getWeekday($date);
    echo "\n$NumTarjeta,$Nombre,$apellido,$Acceso, $codcampus,$codedif,$codsalon,$date,$time\n";
    connectBd()->query("INSERT INTO swipe (NumTarjeta,ID,Nombre,Acceso,Sal_CodCampus,Sal_CodEdif,Sal_CodSalon,Fecha,Tiempo) VALUES('$NumTarjeta','$ID','$Nombre $apellido','$Acceso','$codcampus','$codedif','$codsalon','$date','$time')");
    } 
function totalHorasAsistencia($horaIni,$horaFin,$horaEntrada,$precencia){
        $time1 = strtotime($horaIni);
        $time2 = strtotime($horaFin);
        $time3 = strtotime($horaEntrada);
        $totalHoras = round(abs($time2 - $time1) / 3600,2);
        if($precencia=="P"){
            $totalHorasPresente = round(abs($time2 - $time3) / 3600,2);
            $whole = floor($totalHorasPresente);      
            $fraction = $totalHorasPresente - $whole;
           if($fraction < 0.7){
            $totalHorasPresente= intval($totalHorasPresente);
           }else if($fraction >= 0.7){
            $totalHorasPresente += 0.5;
            $totalHorasPresente= intval($totalHorasPresente);
           } 
           return $totalHorasPresente;
        }
        return totalhorasgrupo($time1,$time2);
       
    } 
function totalhorasgrupo($time1,$time2){
        $totalHoras = round(abs($time2 - $time1) / 3600,2);
        $t = $totalHoras;
        $whole = floor($t);      
        $fraction = $t - $whole;
        $minute = ($fraction * 0.6)*100;
        echo intval($t),"h", $minute,"\n";
        $thorastime=mktime(intval($t),$minute ); 
        $horas=date("h:i", $thorastime);
        
        return $horas;
    }
?>