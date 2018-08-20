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
                </tr>
                ';
            }
        }
        
        exit($response);
   
}

}

?>