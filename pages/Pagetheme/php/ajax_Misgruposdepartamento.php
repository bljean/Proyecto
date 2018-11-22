<?php
if (isset($_POST['key'])){
    
$user='root';
$pass='';
$db='proyectofinal';
$conn= new mysqli('localhost',$user, $pass, $db);

    

    if($_POST['key'] == 'getExistingData'){
        $start = $conn->real_escape_string($_POST['start']);
        $limit = $conn->real_escape_string($_POST['limit']);
        $ID = $conn->real_escape_string($_POST['ID']);

        $sqldepartamento = $conn->query("SELECT CodTema FROM contratodirector WHERE NumCedula='$ID'");
        if($sqldepartamento->num_rows >0){
        while($data= $sqldepartamento->fetch_array()){
            $CodTema   = $data["CodTema"];
        
            
        $sql = $conn->query("SELECT CodTP, Numgrupo, CodCampus, AnoAcad, Numper,NumCedula FROM contratodocencia WHERE CodTema='$CodTema'  LIMIT $start,$limit");
        if($sql->num_rows >0){
            $response ="";
            while($data= $sql->fetch_array()){
                $NumCedula   = $data["NumCedula"];
                $CodTP   = $data["CodTP"];
                $Numgrupo   = $data["Numgrupo"];
                $CodCampus   = $data["CodCampus"];
                $AnoAcad   = $data["AnoAcad"];
                $Numper   = $data["Numper"];
                $sql1 = $conn->query("SELECT nombre, apellido_1 FROM trabajadores WHERE NumCedula='$NumCedula'");
                if($sql1->num_rows >0){
                while($data= $sql1->fetch_array()){
                    $nombreprof   = $data["nombre"];
                    $apellido   = $data["apellido_1"];
                }
                }
                $sql2 = $conn->query("SELECT Nombre, NumCreditos FROM asignatura WHERE CodTema='$CodTema' AND CodTp='$CodTP'");
                if($sql2->num_rows >0){
                while($data= $sql2->fetch_array()){
                    $Nombre   = $data["Nombre"];
                    $NumCreditos   = $data["NumCreditos"];
                }    
                }
                $response .='
                <tr>
                <td>'.$CodCampus.'-'.$CodTema.'-'.$CodTP.'-'.$Numgrupo.'</td>
                <td>'. $Nombre.'</td>
                <td>'. $NumCreditos.'</td>
                <td>'.$nombreprof.' '.$apellido.'</td>
                <td>'.$AnoAcad.'/'.$Numper.'</td>
                <td>
                <div class="col-md-6">
                <input type="button" onclick="asistencia(\''.$NumCedula.'\',\''.$Numgrupo.'\',\''.$CodTema.'\',\''.$CodTP.'\',\''.$CodCampus.'\',\''.$AnoAcad.'\',\''.$Numper.'\')" value="Asistencia" class="btn btn-primary" id="asistencia">
                </div>
                <div class="col-md-4">
                <form method="POST" action="reporte-departamento.php">
                        <input type="hidden" name="CodCampus" value="'.$CodCampus.'">
                        <input type="hidden" name="CodTema" value="'.$CodTema.'">
                        <input type="hidden" name="CodTP" value="'.$CodTP.'">
                        <input type="hidden" name="Numgrupo" value="'.$Numgrupo.'">
                        <input type="hidden" name="AnoAcad" value="'.$AnoAcad.'">
                        <input type="hidden" name="Numper" value="'.$Numper.'">
                        <input type="hidden" name="NumCreditos" value="'.$NumCreditos.'">
                        <input type="hidden" name="nombreprof" value="'.$nombreprof.'">
                        <input type="hidden" name="apellido" value="'.$apellido.'">
                        <input type="submit" value="Reportes"  class="btn btn-primary">
                    </form>
                </div> 
                </td>
                </tr>
                 
                ';
            }
            exit($response);
            
        } else
            exit('reachedMax');

        }

        }


    }
    
    if($_POST['key'] == 'getcedula'){
        $ID = $conn->real_escape_string($_POST['ID']);
        $sql = $conn->query("SELECT NumCedula FROM users WHERE username='$ID'");
        if($sql->num_rows >0){
        while($data= $sql->fetch_array()){
            $NumCedula   = $data["NumCedula"];
        }
        }
        $jsonArray = array(
            'NumCedula'=>$NumCedula,
            
        );
        exit(json_encode($jsonArray));
    }
    
    if($_POST['key'] == 'findDay'){
        $CodCampus = $conn->real_escape_string($_POST['CodCampus']);
        $CodTema = $conn->real_escape_string($_POST['CodTema']);
        $CodTP = $conn->real_escape_string($_POST['CodTP']);
        $Numgrupo = $conn->real_escape_string($_POST['Numgrupo']);
        $AnoAcad = $conn->real_escape_string($_POST['AnoAcad']);
        $Numper = $conn->real_escape_string($_POST['Numper']);
        $dayVal = $conn->real_escape_string($_POST['dayVal']);
        $sql = $conn->query("UPDATE configuraciongrupo SET PTLimiteH ='$dayVal' WHERE CodTema ='$CodTema' AND CodTp ='$CodTP' AND NumGrupo='$Numgrupo' AND CodCampus = '$CodCampus' AND AnoAcad ='$AnoAcad' AND NumPer ='$Numper'");
        
        $jsonArray = array(
            'Grupo'=>"hola",
            
        );
        exit(json_encode($jsonArray));
    }

  
}
?>