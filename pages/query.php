<?php
date_default_timezone_set('America/Santo_Domingo');
$CodCampus = "ST";
$CodEdif = "A1";
$CodSalon = 14;

//getStudentGroup(20131036);
getProfesorGroup(14785236985);
/*$timezone = date_default_timezone_get();
echo "\n The current server timezone is:  $timezone \n";
$date = date('H:i:s');
echo " probando time: $date \n";
echo  getWeekday($date);
*/





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
    }else echo"abrir puerta al trabajador";
    return  $sqlProfessorGrupo;
}
function attendEstRecord($matricula,$date,$horaini,$time,$horafin,$day,$Codtema,$CodTP,$CodCampus,$NumGrupo,$AnoAcad,$NumPer){
    $sqlStudentattend = connectBd()->query( "SELECT * FROM asistencia WHERE ID='$matricula' AND Fecha='$date' AND Horaini='$horaini'");
    if($sqlStudentattend->num_rows > 0){
        echo "ya esta precente";
    }else connectBd()->query("INSERT INTO asistencia (ID,Fecha,Horaini,Horaentrada,Horafin,Diasemana,Presencia,NumGrupo,CodTema,CodTP,CodCampus,AnoAcad,NumPer) VALUES('$matricula','$date','$horaini','$time','$horafin','$day','P','$NumGrupo','$Codtema','$CodTP','$CodCampus','$AnoAcad','$NumPer')");
    
}
function getWeekday($date) {
    return date('w', strtotime($date));
}


?>