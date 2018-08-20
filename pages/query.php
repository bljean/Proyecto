<?php
date_default_timezone_set('America/Santo_Domingo');
$CodCampus = "ST";
$CodEdif = "A1";
$CodSalon = 14;

//getStudentGroup(20131066);
//getProfesorGroup(14785236985);
//checkGroupTime();
/*$timezone = date_default_timezone_get();
echo "\n The current server timezone is:  $timezone \n";
$date = date('H:i:s');
echo " probando time: $date \n";
echo  getWeekday($date);
*/
$sql = connectBd()->query("SELECT Fecha,Horaini,Horafin,Horaentrada,Diasemana,Presencia FROM asistencia where ID='20131036'");
while($data= $sql->fetch_array()){
    echo "Horaini: ",$data["Horaini"],"\n";
    echo "Horafin: ",$data["Horafin"],"\n";
    echo "Horaentrada: ",$data["Horaentrada"],"\n";
    echo "Presencia: ",$data["Presencia"],"\n";
    echo totalHorasAsistencia($data["Horaini"],$data["Horafin"],$data["Horaentrada"],$data["Presencia"]),"\n";
}



function totalHorasAsistencia($horaIni,$horaFin,$horaEntrada,$precencia){
    $time1 = strtotime($horaIni);
    $time2 = strtotime($horaFin);
    $time3 = strtotime($horaEntrada);
    echo "Total de horas: ",$totalHoras = round(abs($time2 - $time1) / 3600,2),"\n";
    if($precencia=="P"){
        echo "Total de Horas Presente: ",$totalHorasPresente = round(abs($time2 - $time3) / 3600,2),"\n";
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
    return intval($totalHoras);
   
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
        attendEstRecord($matricula,$date,$horaini,$time,$horafin,$day,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,'P');
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
        attendEstRecord($numCedula,$date,$horaini,$time,$horafin,$day,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,'P');
    }elseif($sqlProfessor->num_rows >0){
        echo "no abrir puerta";
    }else echo"abrir puerta al trabajador";
    return  $sqlProfessorGrupo;
    }
function attendEstRecord($matricula,$date,$horaini,$time,$horafin,$day,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,$Precencia){
    $codcampus = $GLOBALS['CodCampus'];
    $codedif =$GLOBALS['CodEdif'];
    $codsalon =$GLOBALS['CodSalon'];
    $sqlStudentattend = connectBd()->query( "SELECT * FROM asistencia WHERE ID='$matricula' AND Fecha='$date' AND Horaini='$horaini'");
    if($sqlStudentattend->num_rows > 0){
        echo "ya esta precente o ausente";
    }else connectBd()->query("INSERT INTO asistencia (ID,Fecha,Horaini,Horaentrada,Horafin,Sal_CodCampus,Sal_CodEdif,Sal_CodSalon,Diasemana,Presencia,NumGrupo,CodTema,CodTP,CodCampus,AnoAcad,NumPer) VALUES('$matricula','$date','$horaini','$time','$horafin','$codcampus','$codedif','$codsalon','$day','$Precencia','$NumGrupo','$Codtema','$CodTP','$CodCampus','$AnoAcad','$NumPer')");
    
    }
function checkGroupTime(){
    echo $codcampus = $GLOBALS['CodCampus'],"\n";
    echo $codedif =$GLOBALS['CodEdif'],"\n";
    echo $codsalon =$GLOBALS['CodSalon'],"\n";
    $date = date('Y/m/d');
    echo $time= date('H:i:s'),"\n";
    echo $day= getWeekday($date),"\n";
   $sqlHorariogrupotime= connectBd()->query("SELECT Codtema, CodTP,HoraInicio , Horafin, NumGrupo, CodCampus, AnoAcad, NumPer FROM horariogrupoactivo WHERE horariogrupoactivo.Sal_CodCampus='$codcampus' AND horariogrupoactivo.Sal_CodEdif='$codedif' AND horariogrupoactivo.Sal_CodSalon='$codsalon' AND horariogrupoactivo.HoraInicio<='$time' AND horariogrupoactivo.Horafin >= '$time' AND horariogrupoactivo.DiaSem='$day'");
   if($sqlHorariogrupotime->num_rows >0){
    while($data= $sqlHorariogrupotime->fetch_array()){
        echo $HoraInicio=$data['HoraInicio'],"\n";
        echo $Horafin=$data['Horafin'],"\n";
        $NumGrupo=$data["NumGrupo"];
        $Codtema=$data["Codtema"];
        $CodTP=$data["CodTP"];
        $CodCampus=$data["CodCampus"];
        $AnoAcad=$data["AnoAcad"];
        $NumPer=$data["NumPer"];
    }
    ausencia($HoraInicio,$Horafin,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer);
   }else echo "No grupo a esta hora";

    }
function ausencia($horaini,$horafin,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer){
    $date = date('Y-m-d');
    $time= date('H:i:s');
    $horadeAusencia = getHorausencia($horafin);
    if($time>=$horadeAusencia){
        $sqlAusentes=connectBd()->query("SELECT grupoinsest.Matricula as Matricula FROM grupoinsest LEFT JOIN asistencia ON asistencia.ID=grupoinsest.Matricula AND asistencia.CodTema=grupoinsest.CodTema AND asistencia.CodTP= grupoinsest.CodTP AND asistencia.CodCampus= grupoinsest.CodCampus AND asistencia.NumGrupo= grupoinsest.Numgrupo AND asistencia.AnoAcad=grupoinsest.AnoAcad AND asistencia.NumPer= grupoinsest.NumPer WHERE grupoinsest.CodTema='ITT' AND grupoinsest.CodTP='562' AND grupoinsest.CodCampus='ST' AND grupoinsest.Numgrupo='1' AND grupoinsest.AnoAcad='2018' AND grupoinsest.NumPer='1' AND asistencia.ID is NULL");
        if($sqlAusentes->num_rows>0){
            while($data=$sqlAusentes->fetch_array()){
                attendEstRecord($data['Matricula'],$date,$horaini,$time,$horafin,getWeekday($date),$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer,'A');
            }
        }else echo "ya estan ausentes";
        
    }else echo" todavia es tiempo de entrar \n";

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