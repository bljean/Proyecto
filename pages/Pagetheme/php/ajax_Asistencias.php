<?php
if (isset($_POST['key'])){
    
$user='root';
$pass='';
$db='proyectofinal';
$conn= new mysqli('localhost',$user, $pass, $db);
if($_POST['key'] == 'getEstGroupData'){
    $studentID=$conn->real_escape_string($_POST['studentID']);
    $privilegio=$conn->real_escape_string($_POST['privilegio']);
    //$sql = $conn->query("SELECT ID, Name, CardNumber FROm student LIMIT $start,$limit");
    //SELECT grupo.idgrupo as idgrupo,codigo FROM grupoestudiante, grupo WHERE grupoestudiante.idestudiante='$studentID' and grupoestudiante.idgrupo=grupo.idgrupo
    $sql = $conn->query("SELECT CodTema,CodTP,Numgrupo,CodCampus,AnoAcad,NumPer FROM grupoinsest WHERE grupoinsest.Matricula='$studentID'");
    if($sql->num_rows >0){
        $response ="";
        $groupCodigo="";
        $i=0;
        while($data= $sql->fetch_array()){

            if($i==0){
            $response .='
            <li class="active"><a>'.$data["CodTema"].'-'.$data["CodTP"].'-'.$data["Numgrupo"].'</a></li>
            ';
            $NumGrupo   = $data["Numgrupo"];
            $CodTema    = $data["CodTema"];
            $CodTP      = $data["CodTP"];
            $CodCampus  = $data["CodCampus"];
            $AnoAcad    = $data["AnoAcad"];
            $NumPer     = $data["NumPer"];
            $groupCodigo.=''.$data["CodTema"].'-'.$data["CodTP"].'-'.$data["Numgrupo"].'
            ';
            //$groupCodigo=$data["CodTema"];
            
            }else{
            $response .='
            <li onclick="activeGroup(\''.$studentID.'\',\''.$data["Numgrupo"].'\',\''.$data["CodTema"].'\',\''.$data["CodTP"].'\',\''.$data["CodCampus"].'\',\''.$data["AnoAcad"].'\',\''.$data["NumPer"].'\',\''.$privilegio.'\')"><a>'.$data["CodTema"].'-'.$data["CodTP"].'-'.$data["Numgrupo"].'</a></li>
            ';   
            }
            ++$i;
            
        }
    } 
    $jsonArray = array(
        'body'=> $response,
        'NumGrupo'=> $NumGrupo,
        'CodTema'=> $CodTema,
        'CodTP'=> $CodTP,
        'CodCampus'=> $CodCampus,
        'AnoAcad'=> $AnoAcad,
        'NumPer'=> $NumPer,
        'groupCodigo'=>$groupCodigo,
    );  
    exit(json_encode($jsonArray));
    }
if($_POST['key'] == 'getProfGroupData'){
        $response ="";
        $groupCodigo="";
        $ProfID=$conn->real_escape_string($_POST['ProfID']);
        $privilegio=$conn->real_escape_string($_POST['privilegio']);
        $sql = $conn->query("SELECT CodTema,CodTP,Numgrupo,CodCampus,AnoAcad,NumPer FROM contratodocencia WHERE contratodocencia.NumCedula='$ProfID'");
        if($sql->num_rows >0){
            
            $i=0;
            while($data= $sql->fetch_array()){
    
                if($i==0){
                $response .='
                <li class="active"><a>'.$data["CodTema"].'-'.$data["CodTP"].'-'.$data["Numgrupo"].'</a></li>
                ';
                $NumGrupo   = $data["Numgrupo"];
                $CodTema    = $data["CodTema"];
                $CodTP      = $data["CodTP"];
                $CodCampus  = $data["CodCampus"];
                $AnoAcad    = $data["AnoAcad"];
                $NumPer     = $data["NumPer"];
                $groupCodigo.=''.$data["CodTema"].'-'.$data["CodTP"].'-'.$data["Numgrupo"].'
                ';
                //$groupCodigo=$data["CodTema"];
                
                }else{
                $response .='
                <li onclick="activeProfGroup(\''.$ProfID.'\',\''.$data["Numgrupo"].'\',\''.$data["CodTema"].'\',\''.$data["CodTP"].'\',\''.$data["CodCampus"].'\',\''.$data["AnoAcad"].'\',\''.$data["NumPer"].'\',\''.$privilegio.'\')"><a>'.$data["CodTema"].'-'.$data["CodTP"].'-'.$data["Numgrupo"].'</a></li>
                ';   
                }
                ++$i;
                
            }
        } 
        $jsonArray = array(
            'body'=> $response,
            'NumGrupo'=> $NumGrupo,
            'CodTema'=> $CodTema,
            'CodTP'=> $CodTP,
            'CodCampus'=> $CodCampus,
            'AnoAcad'=> $AnoAcad,
            'NumPer'=> $NumPer,
            'groupCodigo'=>$groupCodigo,
        );  
        exit(json_encode($jsonArray));
        }    
if($_POST['key'] == 'getAsisData'){
        $start = $conn->real_escape_string($_POST['start']);
        $limit = $conn->real_escape_string($_POST['limit']);
        $studentID=$conn->real_escape_string($_POST['studentID']);
        $NumGrupo=$conn->real_escape_string($_POST['NumGrupo']);
        $CodTema=$conn->real_escape_string($_POST['CodTema']);
        $CodTP=$conn->real_escape_string($_POST['CodTP']);
        $CodCampus=$conn->real_escape_string($_POST['CodCampus']);
        $AnoAcad=$conn->real_escape_string($_POST['AnoAcad']);
        $NumPer=$conn->real_escape_string($_POST['NumPer']);
        $privilegio=$conn->real_escape_string($_POST['privilegio']);

        //$sql = $conn->query("SELECT ID, Name, CardNumber FROm student LIMIT $start,$limit");
        $sql = $conn->query("SELECT Fecha,Horaini,Horafin,Horaentrada,Diasemana,Presencia FROM asistencia where ID='$studentID' AND NumGrupo='$NumGrupo' AND CodTema='$CodTema' AND CodTP='$CodTP' AND CodCampus='$CodCampus' AND  AnoAcad='$AnoAcad' AND NumPer='$NumPer' LIMIT $start,$limit");
        if($sql->num_rows >0){
            $response ="";
            if($privilegio!=0){
                while($data= $sql->fetch_array()){
                    $response .='
                    <tr>
                        <td>'.$data["Fecha"].'</td>
                        <td>'.$data["Diasemana"].'</td>
                        <td>'.$data["Horaini"].'</td>
                        <td>'.$data["Horafin"].'</td>
                        <td>'.$data["Horaentrada"].'</td>
                        <td>'.$data["Presencia"].'</td>
                    </tr>
                    ';
                }
            }else{
                while($data= $sql->fetch_array()){
                    $response .='
                    <tr>
                        <td>'.$data["Fecha"].'</td>
                        <td>'.$data["Diasemana"].'</td>
                        <td>'.$data["Horaini"].'</td>
                        <td>'.$data["Horafin"].'</td>
                        <td>'.$data["Horaentrada"].'</td>
                        <td>'.$data["Presencia"].'</td>
                        <td>
                        <div class="col-md-2">
                        <input type="button" onclick="edit('.$data["Fecha"].')" value="Editar" class="btn btn-primary">
                        </div>
                        </td>
                    </tr>
                    ';
                }
            }
            
            exit($response);
        } else
            exit('reachedMax');
    }
if($_POST['key'] == 'getActiveGroup'){
        $studentID=$conn->real_escape_string($_POST['studentID']);
        $NumGrupo=$conn->real_escape_string($_POST['NumGrupo']);
        $CodTema=$conn->real_escape_string($_POST['CodTema']);
        $CodTP=$conn->real_escape_string($_POST['CodTP']);
        $CodCampus=$conn->real_escape_string($_POST['CodCampus']);
        $AnoAcad=$conn->real_escape_string($_POST['AnoAcad']);
        $NumPer=$conn->real_escape_string($_POST['NumPer']);
        $privilegio=$conn->real_escape_string($_POST['privilegio']);
        //$sql = $conn->query("SELECT ID, Name, CardNumber FROm student LIMIT $start,$limit");
        $sql = $conn->query("SELECT CodTema,CodTP,Numgrupo,CodCampus,AnoAcad,NumPer FROM grupoinsest WHERE grupoinsest.Matricula='$studentID'");
        if($sql->num_rows >0){
            $response ="";
            $groupCodigo="";
            while($data= $sql->fetch_array()){

                if($data["CodTema"]== $CodTema and $data["CodTP"]== $CodTP and $data["Numgrupo"]== $NumGrupo and $data["CodCampus"]== $CodCampus and $data["AnoAcad"]==$AnoAcad and $data["NumPer"]==$NumPer ){
                   
                    $response .='
                    <li class="active"><a>'.$data["CodTema"].'-'.$data["CodTP"].'-'.$data["Numgrupo"].'</a></li>
                    ';
                    $NumGrupo   = $data["Numgrupo"];
                    $CodTema    = $data["CodTema"];
                    $CodTP      = $data["CodTP"];
                    $CodCampus  = $data["CodCampus"];
                    $AnoAcad    = $data["AnoAcad"];
                    $NumPer     = $data["NumPer"];
                    $groupCodigo.=''.$data["CodTema"].'-'.$data["CodTP"].'-'.$data["Numgrupo"].'
                    ';
                    
                   
                }else{
                    $response .='
                    <li onclick="activeGroup(\''.$studentID.'\',\''.$data["Numgrupo"].'\',\''.$data["CodTema"].'\',\''.$data["CodTP"].'\',\''.$data["CodCampus"].'\',\''.$data["AnoAcad"].'\',\''.$data["NumPer"].'\',\''.$privilegio.'\')"><a>'.$data["CodTema"].'-'.$data["CodTP"].'-'.$data["Numgrupo"].'</a></li>
                    ';   
                    }
                    
            }
            $jsonArray = array(
                'body'=> $response,
                'groupCodigo'=>$groupCodigo,
                'NumGrupo'=> $NumGrupo,
                'CodTema'=> $CodTema,
                'CodTP'=> $CodTP,
                'CodCampus'=> $CodCampus,
                'AnoAcad'=> $AnoAcad,
                'NumPer'=> $NumPer,
                
                
            );  
            exit(json_encode($jsonArray));
        } else exit('Se jodio');
            
    }
if($_POST['key'] == 'activeProfGroup'){
        $ProfID=$conn->real_escape_string($_POST['ProfID']);
        $NumGrupo=$conn->real_escape_string($_POST['NumGrupo']);
        $CodTema=$conn->real_escape_string($_POST['CodTema']);
        $CodTP=$conn->real_escape_string($_POST['CodTP']);
        $CodCampus=$conn->real_escape_string($_POST['CodCampus']);
        $AnoAcad=$conn->real_escape_string($_POST['AnoAcad']);
        $NumPer=$conn->real_escape_string($_POST['NumPer']);
        $privilegio=$conn->real_escape_string($_POST['privilegio']);
        //$sql = $conn->query("SELECT ID, Name, CardNumber FROm student LIMIT $start,$limit");
        $sql = $conn->query("SELECT CodTema,CodTP,Numgrupo,CodCampus,AnoAcad,NumPer FROM contratodocencia WHERE contratodocencia.NumCedula='$ProfID'");
        if($sql->num_rows >0){
            $response ="";
            $groupCodigo="";
            while($data= $sql->fetch_array()){

                if($data["CodTema"]== $CodTema and $data["CodTP"]== $CodTP and $data["Numgrupo"]== $NumGrupo and $data["CodCampus"]== $CodCampus and $data["AnoAcad"]==$AnoAcad and $data["NumPer"]==$NumPer ){
                   
                    $response .='
                    <li class="active"><a>'.$data["CodTema"].'-'.$data["CodTP"].'-'.$data["Numgrupo"].'</a></li>
                    ';
                    $NumGrupo   = $data["Numgrupo"];
                    $CodTema    = $data["CodTema"];
                    $CodTP      = $data["CodTP"];
                    $CodCampus  = $data["CodCampus"];
                    $AnoAcad    = $data["AnoAcad"];
                    $NumPer     = $data["NumPer"];
                    $groupCodigo.=''.$data["CodTema"].'-'.$data["CodTP"].'-'.$data["Numgrupo"].'
                    ';
                    
                   
                }else{
                    $response .='
                    <li onclick="activeProfGroup(\''.$ProfID.'\',\''.$data["Numgrupo"].'\',\''.$data["CodTema"].'\',\''.$data["CodTP"].'\',\''.$data["CodCampus"].'\',\''.$data["AnoAcad"].'\',\''.$data["NumPer"].'\',\''.$privilegio.'\')"><a>'.$data["CodTema"].'-'.$data["CodTP"].'-'.$data["Numgrupo"].'</a></li>
                    ';   
                    }
                    
            }
            $jsonArray = array(
                'body'=> $response,
                'groupCodigo'=>$groupCodigo,
                'NumGrupo'=> $NumGrupo,
                'CodTema'=> $CodTema,
                'CodTP'=> $CodTP,
                'CodCampus'=> $CodCampus,
                'AnoAcad'=> $AnoAcad,
                'NumPer'=> $NumPer,
                
                
            );  
            exit(json_encode($jsonArray));
        } else exit('Se jodio');
            
    }
if($_POST['key'] == 'getRowData'){
        $rowID = $conn->real_escape_string($_POST['rowID']);
        //$sql= $conn->query("SELECT ID,Name,CardNumber FROM student WHERE id ='$rowID'");
        $sql = $conn->query("SELECT fecha, horasasi FROM asistencia WHERE idasistencia='$rowID'");
        $data= $sql->fetch_array();
        $jsonArray = array(
            'fecha'=> $data["fecha"],
            'horasasi'=> $data["horasasi"],
        );
        exit(json_encode($jsonArray));
    }   
if ($_POST['key'] == 'updateRow' or $_POST['key'] == 'addNew'){
        $rowID = $conn->real_escape_string($_POST['rowID']);
        $horas = $conn->real_escape_string($_POST['horas']);
        if ($_POST['key'] == 'updateRow'){
        $conn->query("UPDATE asistencia SET horasasi='$horas'WHERE idasistencia='$rowID'");
        exit('success');
        }
    }
}
?>