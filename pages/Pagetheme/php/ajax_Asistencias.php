<?php
if (isset($_POST['key'])){
    
$user='root';
$pass='';
$db='proyectofinal';
$conn= new mysqli('localhost',$user, $pass, $db);

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

    if($_POST['key'] == 'getExistingData'){
        $start = $conn->real_escape_string($_POST['start']);
        $limit = $conn->real_escape_string($_POST['limit']);
        $studentID=$conn->real_escape_string($_POST['ID']);
        $grupID=$conn->real_escape_string($_POST['grupID']);

        //$sql = $conn->query("SELECT ID, Name, CardNumber FROm student LIMIT $start,$limit");
        $sql = $conn->query("SELECT idasistencia,fecha, horasasi FROM asistencia where idestudiante='$studentID' AND idgrupo='$grupID' LIMIT $start,$limit");
        if($sql->num_rows >0){
            $response ="";
            while($data= $sql->fetch_array()){
                $response .='
                <tr>
                    <td>'.$data["fecha"].'</td>
                    <td>'.$data["horasasi"].'</td>
                    <td>
                    <div class="col-md-2">
                    <input type="button" onclick="edit('.$data["idasistencia"].')" value="Editar" class="btn btn-primary">
                    </div>
                    </td>
                </tr>
                ';
            }
            exit($response);
        } else
            exit('reachedMax');
    }
    if($_POST['key'] == 'getGroupData'){
        $studentID=$conn->real_escape_string($_POST['ID']);
        //$sql = $conn->query("SELECT ID, Name, CardNumber FROm student LIMIT $start,$limit");
        $sql = $conn->query("SELECT grupo.idgrupo as idgrupo,codigo FROM grupoestudiante, grupo WHERE grupoestudiante.idestudiante='$studentID' and grupoestudiante.idgrupo=grupo.idgrupo");
        if($sql->num_rows >0){
            $response ="";
            $i=0;
            while($data= $sql->fetch_array()){

                if($i==0){
                $response .='
                <li class="active"><a>'.$data["codigo"].'</a></li>
                ';
                $groupID=$data["idgrupo"];
                $groupCodigo=$data["codigo"];
                }else{
                $response .='
                <li onclick="activeGroup('.$data["idgrupo"].',' .$studentID.')"><a>'.$data["codigo"].'</a></li>
                ';   
                }
                ++$i;
                
            }
            $jsonArray = array(
                'body'=> $response,
                'groupid'=> $groupID,
                'groupCodigo'=> $groupCodigo,
            );
            exit(json_encode($jsonArray));
        } else
            exit('reachedMax');
    }
    if($_POST['key'] == 'getActiveGroup'){
        $studentID=$conn->real_escape_string($_POST['ID']);
        $groupCode=$conn->real_escape_string($_POST['groupCode']);
        //$sql = $conn->query("SELECT ID, Name, CardNumber FROm student LIMIT $start,$limit");
        $sql = $conn->query("SELECT idgrupo, codigo from grupo");
        

        if($sql->num_rows >0){
            $response ="";
            while($data= $sql->fetch_array()){

                if($data["idgrupo"]==$groupCode){
                $response .='
                <li class="active"><a>'.$data["codigo"].'</a></li>
                ';
                $groupCodigo=$data["codigo"];
                }else{
                $response .='
                <li onclick="activeGroup('.$data["idgrupo"].',' .$studentID.')"><a>'.$data["codigo"].'</a></li>
                ';   
                }
               
                
            }
            $jsonArray = array(
                'body'=> $response,
                'groupCodigo'=> $groupCodigo,
            );
            exit(json_encode($jsonArray));
        } else
            exit('reachedMax');
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