<?php
if (isset($_POST['key'])){
    
$user='root';
$pass='';
$db='proyectofinal';
$conn= new mysqli('localhost',$user, $pass, $db);
date_default_timezone_set('America/Santo_Domingo');

if($_POST['key'] == 'getEstGroupData'){
    
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
        $semanal[]=ausenciaporsemanaEST($fecha,$ID,$conn);
    
        }
        }

        $semestralpormateria[]=asistenciasemestralEST($ID,$conn);
      
        $materias1=getestdiadia($ID,$conn);

    $jsonArray = array( 
        'materias1'=>$materias1,
        'semestral'=> $semestralpormateria,
        'semanal'=> $semanal,   
    );
    exit(json_encode($jsonArray));
   
}

if($_POST['key'] == 'getExistingData'){
    
    $ID=$conn->real_escape_string($_POST['ID']);
    $sql = $conn->query("SELECT mensaje,estado,autor,fecha,Hora FROM notificaciones  WHERE ID='$ID' ORDER BY fecha DESC,hora DESC limit 0,10 ");
    if($sql->num_rows >0){
        $response ="";
        while($data= $sql->fetch_array()){
            $mensaje=$data["mensaje"];
            $autor=$data["autor"];
            $fecha=$data["fecha"];
            $Hora=$data["Hora"];
            $response .='
                
            <li class="list-group-item">'.$mensaje.',('.$autor.','.$fecha.' '.$Hora.')</li>
                
                ';
        }
    }
    $jsonArray = array(
        'body'=> $response,
    );
    exit(json_encode($jsonArray));
   
}

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

function getestdiadia($ID,$conn){
    $sqlest=$conn->query("SELECT CodTema, CodTP,Numgrupo, CodCampus, AnoAcad, NumPer FROM grupoinsest WHERE Matricula='$ID'");
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
            $response []=''.$CodTema.'-'.$CodTP.'-'.$NumPer.'';
        }

    } return $response;

}



function ausenciaporsemanaEST($fecha,$ID,$conn){
    $diasemana=getWeekday($fecha);
    $sqlest=$conn->query("SELECT Numgrupo, CodTema,CodTP,CodCampus,AnoAcad,NumPer  from grupoinsest where Matricula='$ID'");
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
            $sqlasistencia=$conn->query("SELECT COUNT(*) as cont from asistencia WHERE CodTema='$CodTema' AND CodTP='$CodTP' AND Numgrupo='$NumGrupo' AND CodCampus='$CodCampus' AND AnoAcad='$AnoAcad' AND NumPer='$NumPer' AND Fecha='$fecha' and Presencia='A' and ID='$ID' ");
            if($sqlasistencia->num_rows>0){
                while($data= $sqlasistencia->fetch_array())
                {
                    $count1+=$data["cont"];
                }
            } 
        }
    $calculo=(int)$count1;
    } return $calculo;

}

function asistenciasemestralEST($ID,$conn){
    
    $sqlest=$conn->query("SELECT Numgrupo, CodTema,CodTP,CodCampus,AnoAcad,NumPer from grupoinsest where Matricula='$ID'");
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
            $sqlasistencia=$conn->query("SELECT COUNT(*) as cont from asistencia WHERE CodTema='$CodTema' AND CodTP='$CodTP' AND Numgrupo='$NumGrupo' AND CodCampus='$CodCampus' AND AnoAcad='$AnoAcad' AND NumPer='$NumPer' AND Presencia='A' AND ID='$ID'");
            if($sqlasistencia->num_rows>0){
                while($data= $sqlasistencia->fetch_array())
                {
                    $count1+=$data["cont"];
                }
            } 
        }
    $calculo=(int)$count1;
    } return $calculo;

}





?>