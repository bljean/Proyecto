<?php
require '/xampp/htdocs/Proyecto/vendor/autoload.php';
date_default_timezone_set('America/Santo_Domingo');
if (isset($_POST['key'])){
    
$user='root';
$pass='';
$db='proyectofinal';
$conn= new mysqli('localhost',$user, $pass, $db);

if($_POST['key'] == 'getestgrupo'){
    $NumGrupo=$conn->real_escape_string($_POST['NumGrupo']);
    $CodTema=$conn->real_escape_string($_POST['CodTema']);
    $CodTP=$conn->real_escape_string($_POST['CodTP']);
    $CodCampus=$conn->real_escape_string($_POST['CodCampus']);
    $AnoAcad=$conn->real_escape_string($_POST['AnoAcad']);
    $NumPer=$conn->real_escape_string($_POST['NumPer']);
    $response ="";
    //$sql = $conn->query("SELECT ID, Name, CardNumber FROm student LIMIT $start,$limit");
    $sql = $conn->query("SELECT grupoinsest.Matricula,estudiante.nombre,estudiante.apellido FROM grupoinsest INNER JOIN estudiante on estudiante.Matricula=grupoinsest.Matricula WHERE CodTema='$CodTema' and CodTP='$CodTP' AND Numgrupo='$NumGrupo' AND CodCampus='$CodCampus' AND AnoAcad='$AnoAcad' AND NumPer='$NumPer'");
    if($sql->num_rows >0){
        $response ="";
            while($data= $sql->fetch_array()){
                $response .='
                <tr>
                    <td>'.$data["Matricula"].'</td>
                    <td>'.$data["nombre"].''.$data["apellido"].'</td>
                    <td>
                    <input type="button" onclick="asistencia(\''.$data["Matricula"].'\',\''.$NumGrupo.'\',\''.$CodTema.'\',\''.$CodTP.'\',\''.$CodCampus.'\',\''.$AnoAcad.'\',\''.$NumPer.'\')" value="Asistencia" class="btn btn-primary" id="asistencia">
                    <input type="button" onclick="reporte(\''.$data["Matricula"].'\',\''.$NumGrupo.'\',\''.$CodTema.'\',\''.$CodTP.'\',\''.$CodCampus.'\',\''.$AnoAcad.'\',\''.$NumPer.'\')" value="Reporte" class="btn btn-primary" >
                    </td>
                    

                </tr>
                ';
            }
        }
        
        exit($response);
   
}

if($_POST['key'] == 'reporte'){

    $studentID=$conn->real_escape_string($_POST['studentID']);
    $NumGrupo=$conn->real_escape_string($_POST['NumGrupo']);
    $CodTema=$conn->real_escape_string($_POST['CodTema']);
    $CodTP=$conn->real_escape_string($_POST['CodTP']);
    $CodCampus=$conn->real_escape_string($_POST['CodCampus']);
    $AnoAcad=$conn->real_escape_string($_POST['AnoAcad']);
    $NumPer=$conn->real_escape_string($_POST['NumPer']);
    $sql = $conn->query("SELECT nombre, apellido FROM estudiante WHERE Matricula='$studentID'");
    if($sql->num_rows >0){
        $response ="";
        while($data= $sql->fetch_array()){
            $nombre   = $data["nombre"];
            $apellido   = $data["apellido"];

        }
    }

    $sql2 = $conn->query("SELECT NumCreditos FROM asignatura WHERE CodTema='$CodTema' AND CodTp='$CodTP'");
    if($sql2->num_rows >0){
       
        while($data= $sql2->fetch_array()){
            $NumCreditos   = $data["NumCreditos"];
        }
       
    }

    $sql1 = $conn->query("SELECT NumAusencias FROM grupoinsest WHERE CodTema='$CodTema' AND CodTP='$CodTP' AND CodCampus='$CodCampus'AND NumGrupo='$NumGrupo' AND AnoAcad='$AnoAcad' AND NumPer='$NumPer' and Matricula='$studentID'");
    if($sql1->num_rows >0){
        while($data= $sql1->fetch_array()){
            $cantidadausencias   = $data["NumAusencias"];

        }

    }
        if($cantidadausencias>$NumCreditos*3)
            {
                $status='FN';

        }else{
             $status='Normal';
            }
    
            $response .='
            <tr>
                <td>'.$nombre.''.$apellido.'</td>
                <td>'.$cantidadausencias.'</td>
                <td>'.$status.'</td>
            </tr>
            ';
    exit($response);  
}




if($_POST['key'] == 'asistente'){
    $CodCampus = $conn->real_escape_string($_POST['CodCampus']);
    $CodTema = $conn->real_escape_string($_POST['CodTema']);
    $CodTP = $conn->real_escape_string($_POST['CodTP']);
    $Numgrupo = $conn->real_escape_string($_POST['Numgrupo']);
    $AnoAcad = $conn->real_escape_string($_POST['AnoAcad']);
    $Numper = $conn->real_escape_string($_POST['Numper']);
    $ProfID = $conn->real_escape_string($_POST['ProfID']);
    $sql = $conn->query("SELECT  DISTINCT trabajadores.nombre as nombre, trabajadores.NumCedula as NumCedula, trabajadores.apellido_1 as apellido FROM contratodocencia inner JOIN trabajadores ON trabajadores.NumCedula=contratodocencia.NumCedula WHERE CodTema='$CodTema' AND CodCampus='$CodCampus' AND AnoAcad='$AnoAcad' AND NumPer='$Numper'");
    if($sql->num_rows >0){
        $response ="";
            while($data= $sql->fetch_array()){
                if($ProfID!=$data["NumCedula"]){
                    $response .='
                    <option value="'.$data["NumCedula"].'">'.$data["nombre"].' '.$data["apellido"].'</option>
                    ';
                }
               
            }
        }
        
        $grupo=''.$CodCampus.'-'.$CodTema.'-'.$CodTP.'-'.$Numgrupo.'';
        $jsonArray = array(
            'Grupo'=>$grupo,
            'trabajadores'=>$response ,
        );
        exit(json_encode($jsonArray));
   
}
if($_POST['key'] == 'inSustituto'){
    $CodCampus = $conn->real_escape_string($_POST['CodCampus']);
    $CodTema = $conn->real_escape_string($_POST['CodTema']);
    $CodTP = $conn->real_escape_string($_POST['CodTP']);
    $Numgrupo = $conn->real_escape_string($_POST['Numgrupo']);
    $AnoAcad = $conn->real_escape_string($_POST['AnoAcad']);
    $Numper = $conn->real_escape_string($_POST['Numper']);
    $ProfID = $conn->real_escape_string($_POST['ProfID']);
    $dayVal = $conn->real_escape_string($_POST['dayVal']);
    $fecha = $conn->real_escape_string($_POST['fecha']);
    $conn->query("INSERT INTO sustituto (CodTema,CodTp,Numgrupo,CodCampus,AnoAcad,NumPer,NumCedula,NumCedulaSusti,Fecha) VALUES ('$CodTema', '$CodTP', '$Numgrupo', '$CodCampus', '$AnoAcad', '$Numper', '$ProfID', '$dayVal', '$fecha')");
    $mensaje='se le ha asignado un profesor sustituto al grupo: '.$CodCampus.'-'.$CodTema.'-'.$CodTP.' para la fecha: '.$fecha.'';
    notificargrupo($CodCampus,$CodTema,$CodTP,$Numgrupo,$AnoAcad,$Numper,$mensaje);
    $jsonArray = array(
        'CodCampus'=>$CodCampus,
        'CodTema'=>$CodTema,
        'CodTP'=>$CodTP,
        'Numgrupo'=>$Numgrupo,
        'AnoAcad'=>$AnoAcad,
        'Numper'=>$Numper,
        'ProfID'=>$ProfID,
        'dayVal'=>$dayVal,
        'fecha'=>$fecha,
        'mensaje'=>$mensaje,
    );
    exit(json_encode($jsonArray));
}





}
function connectBd(){
    $user='root';
    $pass='';
    $db='proyectofinal';
    $conn= new mysqli('localhost',$user, $pass, $db);
    return $conn;
}
function notificacion(){
    $options = array(
        'cluster' => 'mt1',
        'encrypted' => true
    );
    $pusher = new Pusher\Pusher(
        '8b7b30cb5814aead90c6',
        '487f91e47b4bbf226e84',
        '583885',
        $options
    );
    return $pusher;
}
function notificargrupo($CodCampus,$CodTema,$CodTP,$Numgrupo,$AnoAcad,$Numper,$mensaje){
    $pusher=notificacion();
    $message['message'] = $mensaje;
    $date = date('Y-m-d');
    $time= date('H:i:s');
    // send email
    $sqlestudiantes= connectBd()->query("SELECT Matricula FROM grupoinsest WHERE CodTema='$CodTema' AND CodTP='$CodTP' AND Numgrupo='$Numgrupo' AND CodCampus='$CodCampus' AND AnoAcad='$AnoAcad' AND NumPer='$Numper'");
    if($sqlestudiantes->num_rows >0){
        while($data=$sqlestudiantes->fetch_array()){
            $pusher->trigger(''.$data["Matricula"].'', 'my-event', $message);
            $matricula=$data["Matricula"];
            
            mail(''.$matricula.'@ce.pucmm.edu.do',"Sistema",$mensaje);
            connectBd()->query("INSERT INTO notificaciones (ID,mensaje,estado,autor,fecha,Hora,CodTema,CodTp,NumGrupo,CodCampus,AnoAcad,NumPer) VALUES ('$matricula', '$mensaje', '0','Sistema', '$date', '$time','$CodTema','$CodTP','$Numgrupo','$CodCampus','$AnoAcad','$Numper')");
        }
    }
    $sqlprofesores=connectBd()->query("SELECT NumCedula FROM contratodocencia WHERE CodTema='$CodTema' AND CodTP='$CodTP' AND Numgrupo='$Numgrupo' AND CodCampus='$CodCampus' AND AnoAcad='$AnoAcad' AND NumPer='$Numper'");
    if($sqlprofesores->num_rows >0){
        while($data=$sqlprofesores->fetch_array()){
            $pusher->trigger(''.$data["NumCedula"].'', 'my-event', $message);
            $NumCedula=$data["NumCedula"];
            $sqlemail=connectBd()->query("SELECT usuario FROM trabajadores WHERE NumCedula='$NumCedula'");
            if($sqlemail->num_rows > 0){
                while($data= $sqlemail->fetch_array()){
                // send email
                $usuario=$data["usuario"];
                //mail(''.$usuario.'@ce.pucmm.edu.do',"Sistema",$mensaje);
                }
             }
            connectBd()->query("INSERT INTO notificaciones (ID,mensaje,estado,autor,fecha,Hora,CodTema,CodTp,NumGrupo,CodCampus,AnoAcad,NumPer) VALUES ('$NumCedula', '$mensaje', '0','Sistema', '$date', '$time','$CodTema','$CodTP','$Numgrupo','$CodCampus','$AnoAcad','$Numper')");
        }
    }
}

?>