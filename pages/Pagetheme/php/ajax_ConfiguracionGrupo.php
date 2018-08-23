<?php
if (isset($_POST['key'])){
    
$user='root';
$pass='';
$db='proyectofinal';
$conn= new mysqli('localhost',$user, $pass, $db);

    

    if($_POST['key'] == 'getExistingData'){
        $start = $conn->real_escape_string($_POST['start']);
        $limit = $conn->real_escape_string($_POST['limit']);

        
        $sql = $conn->query("SELECT CodTema, CodTP, Numgrupo, CodCampus, AnoAcad, Numper,NumCedula FROM contratodocencia  LIMIT $start,$limit");
        if($sql->num_rows >0){
            $response ="";
            while($data= $sql->fetch_array()){
                $NumCedula   = $data["NumCedula"];
                $CodTema   = $data["CodTema"];
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
                <div class="col-md-12">
                <input type="button" onclick="edit(\''.$CodCampus.'\',\''.$CodTema.'\',\''.$CodTP.'\',\''.$Numgrupo.'\',\''.$AnoAcad.'\',\''.$Numper.'\')" value="Configuracion" class="btn btn-primary">
                </div> 
                </td>
                </tr>
                 
                ';
            }
            exit($response);
        } else
            exit('reachedMax');
    }
    
    if($_POST['key'] == 'edit'){
        $CodCampus = $conn->real_escape_string($_POST['CodCampus']);
        $CodTema = $conn->real_escape_string($_POST['CodTema']);
        $CodTP = $conn->real_escape_string($_POST['CodTP']);
        $Numgrupo = $conn->real_escape_string($_POST['Numgrupo']);
        $AnoAcad = $conn->real_escape_string($_POST['AnoAcad']);
        $Numper = $conn->real_escape_string($_POST['Numper']);
        $sql = $conn->query("SELECT PTLimiteH FROM configuraciongrupo WHERE CodTema='$CodTema' AND CodTp='$CodTP' AND NumGrupo='$Numgrupo' AND AnoAcad='$AnoAcad' AND NumPer='$Numper'");
        $tiempo=0;
        if($sql->num_rows >0){
            while($data= $sql->fetch_array()){
                $tiempo= $data['PTLimiteH'];
            }
        }
        $grupo=''.$CodCampus.'-'.$CodTema.'-'.$CodTP.'-'.$Numgrupo.'';
        $jsonArray = array(
            'Grupo'=>$grupo,
            'Tardanza'=>$tiempo ,
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