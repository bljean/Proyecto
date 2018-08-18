<?php
if (isset($_POST['key'])){
    
$user='root';
$pass='';
$db='proyectofinal';
$conn= new mysqli('localhost',$user, $pass, $db);

if($_POST['key'] == 'diasemana')
{   
    $sqlsemana=$conn->query("SELECT DiaSem,NombreLargo FROM diasemana");
    if($sqlsemana->num_rows>0){ 
        while($data=$sqlsemana->fetch_array()){
            $response []= $data["NombreLargo"];
            $count []= getCountprofesoresDia($data["DiaSem"],$conn);
            $count1 []= getCountestudiantedia($data["DiaSem"],$conn);

            }    
        }
        $jsonArray = array(
            'body'=> $response,
            'count'=> $count,  
            'count1'=> $count1,   
        );
        exit(json_encode($jsonArray));
        
}  
}
function getCountprofesoresDia($dia,$conn){
    $sqlGDia=$conn->query("SELECT COUNT(*) as can FROM horariogrupoactivo WHERE DiaSem='$dia'");
    if($sqlGDia->num_rows>0){
        $sqlCanProfDia=$conn->query("SELECT COUNT(*) as Cantidad  FROM asistencia INNER JOIN contratodocencia on asistencia.ID=contratodocencia.NumCedula WHERE Diasemana='$dia'");
        while($data=$sqlGDia->fetch_array()){
            $canGrupDia=$data["can"];
            }
        if($sqlCanProfDia->num_rows>0){
                while($data1=$sqlCanProfDia->fetch_array()){
                    $canProfDia=$data1["Cantidad"];
                    }
            if((int)$canGrupDia!=0){
                $ausencia=((int)$canGrupDia-(int)$canProfDia);
                $dividirl= ((int)$ausencia / (int)$canGrupDia);
                $multiplicarl = ($dividirl*100);
            } else $multiplicarl = 0;
            
            } else{$multiplicarl = 0;}
        
        return $multiplicarl;
        }else{return $multiplicarl=0;}       
}

function getCountestudiantedia($dia,$conn){
    $sqlGDia=$conn->query("SELECT CodTema, CodTP, NumGrupo,CodCampus,AnoAcad,NumPer, FROM horariogrupoactivo WHERE DiaSem='$dia'");
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
            $totalestudianteporgrupo +=contarestudianteporgrupo($CodTema,$CodTP,$NumGrupo,$CodCampus,$AnoAcad,$NumPer);
            $totalasistenciaporgrupo +=contarasistenciaporgrupo($CodTema,$CodTP,$NumGrupo,$CodCampus,$AnoAcad,$NumPer,$dia);
            $ausencia=((int)$totalestudianteporgrupo-(int)$totalasistenciaporgrupo);
            $dividirl= ((int)$ausencia / (int)$totalestudianteporgrupo);
            $calculo = ($dividirl*100);
        
        } 

        } else{$calculo=0} 
          return $calculo;     
}

function contarestudianteporgrupo($CodTema,$CodTP,$NumGrupo,$CodCampus,$AnoAcad,$NumPer){
    
    $sqlCanESTDia=$conn->query("SELECT  COUNT(*) as contar FROM grupoinsest WHERE CodTema='$CodTema' AND CodTP='$CodTP' AND CodCampus='$CodCampus' AND AnoAcad='$AnoAcad' AND NumPer='$NumPer' AND Numgrupo='$NumGrupo'");
    if($sqlCanESTDia->num_rows>0){
        while($data= $sqlCanESTDia->fetch_array())
        {
            $count=$data["contar"];
        }
    } return $count;

}

function contarasistenciaporgrupo($CodTema,$CodTP,$NumGrupo,$CodCampus,$AnoAcad,$NumPer,$dia){
    $sqlasistencia=$conn->query("SELECT COUNT(*) as cont FROM asistencia INNER JOIN estudiante on estudiante.Matricula=asistencia.ID WHERE CodTema='$CodTema' AND CodTP='$CodTP' AND Numgrupo='$NumGrupo' AND CodCampus='$CodCampus' AND AnoAcad='$AnoAcad' AND NumPer='$NumPer' AND Diasemana='$dia' ");
    if($sqlasistencia->num_rows>0){
        while($data= $sqlasistencia->fetch_array())
        {
            $count1=$data["cont"];
        }
    } return $count1;



}







?>