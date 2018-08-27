<?php
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
                    </td>
                </tr>
                ';
            }
        }
        
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
    );
    exit(json_encode($jsonArray));
}



}

?>