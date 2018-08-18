<?php
$user='root';
$pass='';
$db='proyectofinal';
$conn= new mysqli('localhost',$user, $pass, $db);

$sqlsemana=$conn->query("SELECT DiaSem,NombreLargo FROM diasemana");
if($sqlsemana->num_rows>0){ 
    while($data=$sqlsemana->fetch_array()){
        //$response []= $data["NombreLargo"];
        //$count []= getCountprofesoresDia($data["DiaSem"],$conn);
        echo "\n", getCountestudiantedia($data["DiaSem"],$conn),"\n";

        }    
    }

function getCountestudiantedia($dia,$conn){
    $sqlGDia=$conn->query("SELECT CodTema, CodTP, NumGrupo,CodCampus,AnoAcad,NumPer FROM horariogrupoactivo WHERE DiaSem='$dia'");
    $totalestudianteporgrupo=0;
    $totalasistenciaporgrupo=0;
    if($sqlGDia->num_rows>0){
        while($data=$sqlGDia->fetch_array()){
            $CodTema=$data["CodTema"];
            $CodTP=$data["CodTP"];
            $NumGrupo=$data["NumGrupo"];
            $CodCampus=$data["CodCampus"];
            $AnoAcad=$data["AnoAcad"];
            $NumPer=$data["NumPer"];
            $totalestudianteporgrupo +=contarestudianteporgrupo($CodTema,$CodTP,$NumGrupo,$CodCampus,$AnoAcad,$NumPer,$conn);
            $totalasistenciaporgrupo +=contarasistenciaporgrupo($CodTema,$CodTP,$NumGrupo,$CodCampus,$AnoAcad,$NumPer,$dia,$conn);
          
        
        } 
        $ausencia=((int)$totalestudianteporgrupo-(int)$totalasistenciaporgrupo);
        $dividirl= ((int)$ausencia / (int)$totalestudianteporgrupo);
        $calculo = ($dividirl*100);

        } else{$calculo=0;
        } 
          return $calculo;     
}

function contarestudianteporgrupo($CodTema,$CodTP,$NumGrupo,$CodCampus,$AnoAcad,$NumPer,$conn){
    
    $sqlCanESTDia=$conn->query("SELECT  COUNT(*) as contar FROM grupoinsest WHERE CodTema='$CodTema' AND CodTP='$CodTP' AND CodCampus='$CodCampus' AND AnoAcad='$AnoAcad' AND NumPer='$NumPer' AND Numgrupo='$NumGrupo'");
    if($sqlCanESTDia->num_rows>0){
        while($data= $sqlCanESTDia->fetch_array())
        {
            $count=$data["contar"];
        }
    } return $count;

}

function contarasistenciaporgrupo($CodTema,$CodTP,$NumGrupo,$CodCampus,$AnoAcad,$NumPer,$dia,$conn){
    $sqlasistencia=$conn->query("SELECT COUNT(*) as cont FROM asistencia INNER JOIN estudiante on estudiante.Matricula=asistencia.ID WHERE CodTema='$CodTema' AND CodTP='$CodTP' AND Numgrupo='$NumGrupo' AND CodCampus='$CodCampus' AND AnoAcad='$AnoAcad' AND NumPer='$NumPer' AND Diasemana='$dia' ");
    if($sqlasistencia->num_rows>0){
        while($data= $sqlasistencia->fetch_array())
        {
            $count1=$data["cont"];
        }
    } return $count1;



}







?>