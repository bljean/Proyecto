<?php
date_default_timezone_set('America/Santo_Domingo');
$CodCampus = "ST";
$CodEdif = "A1";
$CodSalon = 14;

echo getStudentGroup(20131036);
$timezone = date_default_timezone_get();
echo "\n The current server timezone is:  $timezone \n";
$date = date('H:i:s');
echo " probando time: $date \n";
echo  getWeekday($date);






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
    $sqlStudentGrupo=connectBd()->query( "SELECT horariogrupoactivo.Codtema as Codtema FROM horariogrupoactivo INNER JOIN grupoinsest on horariogrupoactivo.Codtema=grupoinsest.Codtema AND horariogrupoactivo.CodTP=grupoinsest.CodTP AND horariogrupoactivo.NumGrupo=grupoinsest.NumGrupo AND horariogrupoactivo.CodCampus=grupoinsest.CodCampus AND horariogrupoactivo.AnoAcad=grupoinsest.AnoAcad AND horariogrupoactivo.NumPer=grupoinsest.NumPer AND grupoinsest.Matricula= $matricula AND horariogrupoactivo.Sal_CodCampus='$codcampus' AND horariogrupoactivo.Sal_CodEdif='$codedif' AND horariogrupoactivo.Sal_CodSalon=$codsalon AND horariogrupoactivo.HoraInicio<='$time' AND horariogrupoactivo.Horafin >= '$time' AND horariogrupoactivo.DiaSem='$day'");
    if($sqlStudentGrupo->num_rows >0){
        echo "fuciona";
    }else echo"no fuciona";
    return $sqlStudentGrupo;
}
function getWeekday($date) {
    return date('w', strtotime($date));
}


?>