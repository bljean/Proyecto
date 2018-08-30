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
           
           
            
            if(getCountprofesoresDia($data["DiaSem"],$conn)!=-1){
                $response []= $data["NombreLargo"];
                $count []= getCountprofesoresDia($data["DiaSem"],$conn);
                $count1 []= getCountestudiantedia($data["DiaSem"],$conn);
            }
            //echo "\n", getCountprofesoresDia($data["DiaSem"],$conn),"\n";
    
            }    
        }
        $jsonArray = array(
            'body'=> $response,
            'count'=> $count,  
            'count1'=> $count1,   
        );
        exit(json_encode($jsonArray));
        
} 
if($_POST['key'] == 'getnotiData'){
    $sqlnotidata=$conn->query("SELECT mensaje,CodCampus,CodEdif,CodSalon,fecha,hora FROM notificacionesadmin ORDER BY fecha DESC,hora DESC limit 0,10 ");
    $response ="";
     if($sqlnotidata->num_rows>0){
        while($data=$sqlnotidata->fetch_array()){
            $mensaje=$data["mensaje"];
            $CodCampus=$data["CodCampus"];
            $CodEdif=$data["CodEdif"];
            $CodSalon=$data["CodSalon"];
            $fecha=$data["fecha"];
            $hora=$data["hora"];
            $response .='
                
            <li class="list-group-item">'.$mensaje.',(Sistema,'.$fecha.' '.$hora.')</li>
                
                ';
            
        }
    }
    $jsonArray = array(
        'body'=> $response,
    );
    exit(json_encode($jsonArray));
}
}
function getCountprofesoresDia($dia,$conn){
    $sqlGDia=$conn->query("SELECT CodTema, CodTP, NumGrupo,CodCampus,AnoAcad,NumPer FROM horariogrupoactivo WHERE DiaSem='$dia'");
    $sqlcounGDia=$conn->query("SELECT  COUNT(*)  as can FROM horariogrupoactivo WHERE DiaSem='$dia'");
    $canGrupDia=0;
    $canProfDia=0;
    if($sqlGDia->num_rows>0){
        while($data=$sqlcounGDia->fetch_array()){
            $canGrupDia=$data["can"];
        }
        while($data=$sqlGDia->fetch_array()){
            $CodTema=$data["CodTema"];
            $CodTP=$data["CodTP"];
            $NumGrupo=$data["NumGrupo"];
            $CodCampus=$data["CodCampus"];
            $AnoAcad=$data["AnoAcad"];
            $NumPer=$data["NumPer"];
            $sqlCanProfDia=$conn->query("SELECT COUNT(*) as cantidad FROM asistencia INNER JOIN contratodocencia on asistencia.ID=contratodocencia.NumCedula AND contratodocencia.Numgrupo='$NumGrupo' WHERE asistencia.Diasemana='$dia' AND asistencia.CodTema='$CodTema' AND asistencia.CodTP='$CodTP' AND asistencia.CodCampus='$CodCampus' AND asistencia.AnoAcad='$AnoAcad' AND asistencia.NumPer='$NumPer'");
            if($sqlCanProfDia->num_rows>0){
                while($data1=$sqlCanProfDia->fetch_array()){
                    $canProfDia+=$data1["cantidad"];
                    }
            
            }
            }
        if((int)$canGrupDia!=0){
                $ausencia=((int)$canGrupDia-(int)$canProfDia);
                $dividirl= ((int)$ausencia / (int)$canGrupDia);
                $multiplicarl = ($dividirl*100);
            } else $multiplicarl = 0;
            
       
        
        return $multiplicarl;
        }else{return $multiplicarl=-1;}       
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
    $sqlasistencia=$conn->query("SELECT COUNT(*) as cont FROM asistencia INNER JOIN estudiante on estudiante.Matricula=asistencia.ID WHERE CodTema='$CodTema' AND CodTP='$CodTP' AND Numgrupo='$NumGrupo' AND CodCampus='$CodCampus' AND AnoAcad='$AnoAcad' AND NumPer='$NumPer' AND Diasemana='$dia' and Presencia='P' ");
    if($sqlasistencia->num_rows>0){
        while($data= $sqlasistencia->fetch_array())
        {
            $count1=$data["cont"];
        }
    } return $count1;



}







?>