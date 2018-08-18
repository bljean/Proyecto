<?php
$user='root';
$pass='';
$db='proyectofinal';
$conn= new mysqli('localhost',$user, $pass, $db);

$sqlsemana=$conn->query("SELECT DiaSem,NombreLargo FROM diasemana");
if($sqlsemana->num_rows>0){ 
    while($data=$sqlsemana->fetch_array()){
        $response []= $data["NombreLargo"];
        $count []= getCountGruposDia($data["DiaSem"],$conn);
        echo "\n",getCountGruposDia($data["DiaSem"],$conn),"\n";
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
                $dividirl= ((int)$canProfDia / (int)$canGrupDia);
                $multiplicarl = $dividirl*100;
            } else $multiplicarl = 0;
            
            } else{$multiplicarl = 0;}
        
        return $multiplicarl;
        }else{return $multiplicarl=0;}       
}
?>