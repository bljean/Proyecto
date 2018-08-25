<?php
if (isset($_POST['key'])){
    
$user='root';
$pass='';
$db='proyectofinal';
$conn= new mysqli('localhost',$user, $pass, $db);

    

    if($_POST['key'] == 'getExistingData'){
        $start = $conn->real_escape_string($_POST['start']);
        $limit = $conn->real_escape_string($_POST['limit']);
        $NumCedula = $conn->real_escape_string($_POST['NumCedula']);
        $sql = $conn->query("SELECT contratodocencia.CodTema AS CodTema , contratodocencia.CodTp as CodTP, contratodocencia.Numgrupo as Numgrupo , contratodocencia.CodCampus as CodCampus , contratodocencia.AnoAcad as AnoAcad , contratodocencia.NumPer as NumPer , asignatura.Nombre as Nombre , asignatura.NumCreditos as NumCreditos, trabajadores.nombre as nombret, trabajadores.apellido_1 as apellido_1,gruporecuperar.Fecha_Recuperar as Fecha_Recuperar,gruporecuperar.Horas as Horas FROM contratodocencia INNER JOIN gruporecuperar ON gruporecuperar.CodTema=contratodocencia.CodTema AND gruporecuperar.CodTp=contratodocencia.CodTp AND gruporecuperar.NumGrupo=contratodocencia.Numgrupo AND gruporecuperar.CodCampus=contratodocencia.CodCampus AND gruporecuperar.AnoAcad =contratodocencia.AnoAcad AND gruporecuperar.NumPer=contratodocencia.NumPer INNER JOIN asignatura ON contratodocencia.CodTema=asignatura.CodTema AND contratodocencia.CodTp=asignatura.CodTp INNER JOIN trabajadores ON trabajadores.NumCedula=contratodocencia.NumCedula WHERE contratodocencia.NumCedula='$NumCedula'  LIMIT $start,$limit");
        if($sql->num_rows >0){
            $response ="";
            while($data= $sql->fetch_array()){
                $CodTema   = $data["CodTema"];
                $CodTP   = $data["CodTP"];
                $Numgrupo   = $data["Numgrupo"];
                $CodCampus   = $data["CodCampus"];
                $AnoAcad   = $data["AnoAcad"];
                $Numper   = $data["NumPer"];
                $nombret   = $data["nombret"];
                $apellido   = $data["apellido_1"];
                $Nombre   = $data["Nombre"];
                $NumCreditos   = $data["NumCreditos"];
                $Fecha_Recuperar= $data["Fecha_Recuperar"];
                $Horas=$data["Horas"];
                $response .='
                <tr>
                <td>'.$AnoAcad.'/'.$Numper.'</td>
                <td>'.$CodCampus.'-'.$CodTema.'-'.$CodTP.'-'.$Numgrupo.'</td>
                <td>'.$Nombre.'</td>
                <td>'.$Fecha_Recuperar.'</td>
                <td>'.$Horas.'</td>
                <td>
                <div class="col-md-12">
                <input type="button" onclick="edit(\''.$CodCampus.'\',\''.$CodTema.'\',\''.$CodTP.'\',\''.$Numgrupo.'\',\''.$AnoAcad.'\',\''.$Numper.'\',\''.$Horas.'\')" value="Recuperar" class="btn btn-primary">
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
    if($_POST['key'] == 'getaula'){
        $grupo = $conn->real_escape_string($_POST['grupo']);
        $HoraRecuperar = $conn->real_escape_string($_POST['HoraRecuperar']);
        $fecha = $conn->real_escape_string($_POST['fecha']);
        $hora = $conn->real_escape_string($_POST['hora']);
        $day=getWeekday($fecha);
        list($CodCampus,$CodTema,$CodTP,$Numgrupo) = explode('-', $grupo);
        $sqlgetaula = $conn->query("SELECT salondocencia.CodCampus as CodCampus,salondocencia.CodEdif as CodEdif,salondocencia.CodSalon as CodSalon FROM salondocencia LEFT JOIN horariogrupoactivo on salondocencia.CodCampus= horariogrupoactivo.Sal_CodCampus AND salondocencia.CodEdif= horariogrupoactivo.Sal_CodEdif AND salondocencia.CodSalon=horariogrupoactivo.Sal_CodSalon AND horariogrupoactivo.DiaSem=3 AND horariogrupoactivo.HoraInicio='$hora' WHERE horariogrupoactivo.CodTema is NULL");
        if($sqlgetaula->num_rows >0){
            while($data= $sqlgetaula->fetch_array()){
               $CodCampus =$data['CodCampus'];
               $CodEdif =$data['CodEdif'];
               $CodSalon =$data['CodSalon'];
               $sqlaula = $conn->query("SELECT * FROM gruporecuperarhoras WHERE Fecha='$fecha' AND Sal_CodCampus='$CodCampus' AND Sal_CodEdif='$CodEdif' AND Sal_CodSalon='$CodSalon' AND HoraInicio='$hora'");
               if($sqlaula->num_rows > 0){
               
                     $aula='No Aula';
                    
              
               }else{
                    $aula =''.$CodCampus.'-'.$CodEdif.'-'.$CodSalon.'';
                    break;
                }
            }

        }else{
            $aula='se jodio';
        }
        $jsonArray = array(
            'aula'=>$aula,
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
function getWeekday($date) {
    return date('w', strtotime($date));
}
?>