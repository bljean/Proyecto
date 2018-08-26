<?php
if (isset($_POST['key'])){
    
$user='root';
$pass='';
$db='proyectofinal';
$conn= new mysqli('localhost',$user, $pass, $db);
date_default_timezone_set('America/Santo_Domingo');

if($_POST['key'] == 'getprofGroupData'){
    $ID=$conn->real_escape_string($_POST['ID']);
    $sqlsemana=$conn->query("SELECT DiaSem,NombreLargo FROM diasemana");
    if($sqlsemana->num_rows>0){ 
        while($data=$sqlsemana->fetch_array()){
            if(getCountprofesoresDia($data["DiaSem"],$conn)!=-1){
            $response []= $data["NombreLargo"];
            $semestral []= contarausencia($data["DiaSem"],$ID,$conn);
            

        }

        }
    }      
        
        foreach(getsemanagraf() as $fecha){  
            if(getCountprofesoresDia(getWeekday($fecha),$conn)!=-1){
           $diasemana[]=getWeekday($fecha);
           $semanal[]=asistenciapormateriaprof($fecha,$ID,$conn);
       
           }
           }


    $materias1=getprofmateriasdiadia($ID,$conn);

    $jsonArray = array(
        //'body'=> $response,  
        //'semestral'=> $semestral,
        'semanal'=> $semanal, 
        'materias1'=> $materias1,  
    );
    exit(json_encode($jsonArray));
   
}


if($_POST['key'] == 'getESTAsisProfGroupData'){ 
    $ID=$conn->real_escape_string($_POST['ID']);
    $sql=$conn->query("SELECT  contratodocencia.CodTema as CodTema, contratodocencia.CodTp as CodTp, contratodocencia.Numgrupo as Numgrupo, contratodocencia.CodCampus as CodCampus, contratodocencia.AnoAcad as AnoAcad, contratodocencia.NumPer as NumPer, asignatura.Nombre as Nombre, asignatura.NumCreditos as NumCreditos FROM contratodocencia INNER JOIN asignatura ON asignatura.CodTema=contratodocencia.CodTema AND asignatura.CodTp=contratodocencia.CodTp WHERE contratodocencia.NumCedula='$ID'");
    if($sql->num_rows>0){ 
        while($data=$sql->fetch_array()){
            $CodTema   = $data["CodTema"];
            $CodTP   = $data["CodTp"];
            $Numgrupo   = $data["Numgrupo"];
            $CodCampus = $data["CodCampus"];
            $AnoAcad   = $data["AnoAcad"];
            $Numper   = $data["NumPer"];  
            $asignombre= $data["Nombre"];
            $NumCreditos= $data["NumCreditos"];
           
            
           $sql1=$conn->query("SELECT Matricula FROM grupoinsest WHERE CodTema='$CodTema' AND CodTP='$CodTP' AND Numgrupo='$Numgrupo' AND CodCampus='$CodCampus'  AND AnoAcad='$AnoAcad' AND NumPer='$Numper'");
            if($sql1->num_rows>0){
             while($data1=$sql1->fetch_array()){
                $Matricula   = $data1["Matricula"];
                $grupo[]= getgrupomatricula($CodTema,$CodTP,$conn);
                $calcular[]=$NumCreditos*3;
            $sql2=$conn->query("SELECT COUNT(*) as cant FROM asistencia WHERE CodTema='$CodTema' AND CodTP='$CodTP' AND Numgrupo='$Numgrupo' AND CodCampus='$CodCampus'  AND AnoAcad='$AnoAcad' AND NumPer='$Numper' AND ID='$Matricula'AND Presencia='A'");    
                if($sql2->num_rows>0){
                while($data2=$sql2->fetch_array()){
                    $cant  = $data2["cant"];
                    $canti[]=$cant*$horas[]= cantidadhoras($CodTema, $CodTP,$Numgrupo,$CodCampus,$AnoAcad,$Numper,$Matricula,$conn);;
                    $cantidadmatricula[]=$Matricula;
                    
                    
                }
            
                }   
            }
            }
            
        }
    }   

    for ($i = 0; $i <= (count($canti) -1); $i++) {
        $temp[$i] = ($canti[$i] / $calcular[$i])*100;
        
        }

        rsort($temp); 
          
        
    
    $jsonArray = array(
        'ausenciaest'=> $calcular,
        'canti'=> $canti,
        'ausenciamatricula'=>$temp,
        //'fatality'=>$mayoramenor,
        
    );
    exit(json_encode($jsonArray));
   
}


}



function totalhorasgrupo($time1,$time2){
    $time1=strtotime($time1);
    $time2=strtotime($time2);
    $totalHoras = round(abs($time2 - $time1) / 3600,2);
    return $totalHoras;
}

function getgrupomatricula($CodTema,$CodTP,$conn){
    $sql=$conn->query("SELECT Nombre FROM asignatura WHERE CodTema='$CodTema' AND CodTp='$CodTP'");    
                if($sql->num_rows>0){
                while($data=$sql->fetch_array()){
                 $Nombre   = $data["Nombre"];
                }
            }
            return $Nombre;
}

function cantidadhoras($CodTema, $CodTP,$Numgrupo,$CodCampus,$AnoAcad,$Numper,$Matricula,$conn){
    $sql2=$conn->query("SELECT Horaini , Horafin  FROM asistencia WHERE CodTema='$CodTema' AND CodTP='$CodTP' AND Numgrupo='$Numgrupo' AND CodCampus='$CodCampus'  AND AnoAcad='$AnoAcad' AND NumPer='$Numper' AND ID='$Matricula'");    
    if($sql2->num_rows>0){
    while($data=$sql2->fetch_array()){
        $Horaini   = $data["Horaini"];
        $Horafin   = $data["Horafin"];
        $hora= totalhorasgrupo($Horaini,$Horafin);
    }
    }
    return $hora;

}

function getprofmateriasdiadia($ID,$conn){
    $sqlprof=$conn->query("SELECT Numgrupo, CodTema,CodTP,CodCampus,AnoAcad,NumPer from contratodocencia where NumCedula='$ID'");
    if($sqlprof->num_rows >0){   
        while($data= $sqlprof->fetch_array()){
            $NumGrupo   = $data["Numgrupo"];
            $CodTema    = $data["CodTema"];
            $CodTP      = $data["CodTP"];
            $CodCampus  = $data["CodCampus"];
            $AnoAcad    = $data["AnoAcad"];
            $NumPer     = $data["NumPer"]; 
            $response []=''.$CodTema.'-'.$CodTP.'-'.$NumPer.'';
            
        }
    } return $response;
}

function asistenciapormateriaprof($fecha,$ID,$conn){
   
    $diasemana=getWeekday($fecha);
    $sqlest=$conn->query("SELECT Numgrupo, CodTema,CodTP,CodCampus,AnoAcad,NumPer from contratodocencia where NumCedula='$ID'");
    $count1=0;
    $count=0;
    $calculo=0;
    if($sqlest->num_rows >0){
        while($data= $sqlest->fetch_array()){
            $NumGrupo   = $data["Numgrupo"];
            $CodTema    = $data["CodTema"];
            $CodTP      = $data["CodTP"];
            $CodCampus  = $data["CodCampus"];
            $AnoAcad    = $data["AnoAcad"];
            $NumPer     = $data["NumPer"]; 
            $count+=1;
            $sqlasistencia=$conn->query("SELECT COUNT(*) as cont from asistencia WHERE CodTema='$CodTema' AND CodTP='$CodTP' AND Numgrupo='$NumGrupo' AND CodCampus='$CodCampus' AND AnoAcad='$AnoAcad' AND NumPer='$NumPer' AND Fecha='$fecha' and Presencia='P' and ID='$ID' ");
            if($sqlasistencia->num_rows>0){
                while($data= $sqlasistencia->fetch_array())
                {
                    $count1+=$data["cont"];
                }
            } 
        }
        $ausencia=((int)$count-(int)$count1);
    $dividirl= ((int)$ausencia / (int)$count);
    $calculo = ($dividirl*100);
    } return $calculo;

}

function contarausencia($DiaSem,$ID,$conn){
    $sqlest=$conn->query("SELECT grupoinsest.CodTema as CodTema , grupoinsest.CodTP as CodTP , grupoinsest.Numgrupo as Numgrupo  , grupoinsest.CodCampus as CodCampus, grupoinsest.AnoAcad as AnoAcad, grupoinsest.NumPer as NumPer FROM grupoinsest INNER JOIN horariogrupoactivo ON grupoinsest.CodTema=horariogrupoactivo.CodTema AND grupoinsest.CodTP=horariogrupoactivo.CodTP AND grupoinsest.Numgrupo=horariogrupoactivo.NumGrupo AND grupoinsest.CodCampus= horariogrupoactivo.CodCampus AND grupoinsest.AnoAcad=horariogrupoactivo.AnoAcad AND grupoinsest.NumPer=horariogrupoactivo.NumPer AND horariogrupoactivo.DiaSem='$DiaSem' WHERE grupoinsest.Matricula='$ID'");
    $count1=0;
    $count=0;
    $calculo=0;
    if($sqlest->num_rows >0){
        while($data= $sqlest->fetch_array()){
            $NumGrupo   = $data["Numgrupo"];
            $CodTema    = $data["CodTema"];
            $CodTP      = $data["CodTP"];
            $CodCampus  = $data["CodCampus"];
            $AnoAcad    = $data["AnoAcad"];
            $NumPer     = $data["NumPer"]; 
            $count+=1;
            $sqlasistencia=$conn->query("SELECT COUNT(*) as cont from asistencia WHERE CodTema='$CodTema' AND CodTP='$CodTP' AND Numgrupo='$NumGrupo' AND CodCampus='$CodCampus' AND AnoAcad='$AnoAcad' AND NumPer='$NumPer' AND Diasemana='$DiaSem' and Presencia='P' and ID='$ID' ");
            if($sqlasistencia->num_rows>0){
                while($data= $sqlasistencia->fetch_array())
                {
                    $count1+=$data["cont"];
                }
            } 
        }
        $ausencia=((int)$count-(int)$count1);
    $dividirl= ((int)$ausencia / (int)$count);
    $calculo = ($dividirl*100);
    } return $calculo;
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


function getsemanagraf(){
    $date=date('Y-m-d');
   // $date="2018-8-16";
 $diasemana= getWeekday($date); 
 $cuenta=0;
 $hola=0;
    while($diasemana!=0)
    {   $guardar[]=$diasemana;
        $cuenta++;

        $diasemana--;
    }
    while($hola!=$cuenta)
    {   $fechas[]=$date;
        $nuevafecha = strtotime ( '-1 day' , strtotime ( $date ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );
        $date=$nuevafecha;
        $hola++;
    }
  // print_r($fechas) ;
    return $fechas;
}

function getWeekday($date) {
    return date('w', strtotime($date));
            
}

function getestdiadia($fecha,$ID,$conn){
    
    $diasemana=getWeekday($fecha);
    $sqlest=$conn->query("SELECT grupoinsest.CodTema as CodTema , grupoinsest.CodTP as CodTP , grupoinsest.Numgrupo as Numgrupo  , grupoinsest.CodCampus as CodCampus, grupoinsest.AnoAcad as AnoAcad, grupoinsest.NumPer as NumPer FROM grupoinsest INNER JOIN horariogrupoactivo ON grupoinsest.CodTema=horariogrupoactivo.CodTema AND grupoinsest.CodTP=horariogrupoactivo.CodTP AND grupoinsest.Numgrupo=horariogrupoactivo.NumGrupo AND grupoinsest.CodCampus= horariogrupoactivo.CodCampus AND grupoinsest.AnoAcad=horariogrupoactivo.AnoAcad AND grupoinsest.NumPer=horariogrupoactivo.NumPer AND horariogrupoactivo.DiaSem='$diasemana' WHERE grupoinsest.Matricula='$ID'");
    $count1=0;
    $count=0;
    $calculo=0;
    if($sqlest->num_rows >0){
        while($data= $sqlest->fetch_array()){
            $NumGrupo   = $data["Numgrupo"];
            $CodTema    = $data["CodTema"];
            $CodTP      = $data["CodTP"];
            $CodCampus  = $data["CodCampus"];
            $AnoAcad    = $data["AnoAcad"];
            $NumPer     = $data["NumPer"]; 
            $count+=1;
            $sqlasistencia=$conn->query("SELECT COUNT(*) as cont from asistencia WHERE CodTema='$CodTema' AND CodTP='$CodTP' AND Numgrupo='$NumGrupo' AND CodCampus='$CodCampus' AND AnoAcad='$AnoAcad' AND NumPer='$NumPer' AND Fecha='$fecha' and Presencia='P' and ID='$ID' ");
            if($sqlasistencia->num_rows>0){
                while($data= $sqlasistencia->fetch_array())
                {
                    $count1+=$data["cont"];
                }
            } 
        }
        $ausencia=((int)$count-(int)$count1);
    $dividirl= ((int)$ausencia / (int)$count);
    $calculo = ($dividirl*100);
    } return $calculo;

}







?>