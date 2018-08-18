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
            $count []= getCountGruposDia($data["DiaSem"],$conn);
            
            }    
        }
        $jsonArray = array(
            'body'=> $response,
            'count'=> $count,     
        );
        exit(json_encode($jsonArray));
        
}  
}
function getCountGruposDia($dia,$conn){
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
    if($sqlGDia->num_rows>0){
        while($data=$sqlGDia->fetch_array()){
            $CodTema=$data["CodTema"];
            $CodTP=$data["CodTP"];
            $NumGrupo=$data["NumGrupo"];
            $CodCampus=$data["CodCampus"];
            $AnoAcad=$data["AnoAcad"];
            $NumPer=$data["NumPer"];
            }
        $sqlCanProfDia=$conn->query("SELECT COUNT(*) as Cantidad FROM grupoinsest INNER JOIN estudiante on estudiante.Matricula=grupoinsest.Matricula where CodTema='$CodTema' AND CodTP='$CodTP' AND Numgrupo='$NumGrupo'  AND CodCampus='$CodCampus' AND AnoAcad='$AnoAcad'  AND NumPer='$NumPer' ");
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







?>