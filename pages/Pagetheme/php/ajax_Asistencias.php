<?php
if (isset($_POST['key'])){
    
$user='root';
$pass='';
$db='proyectofinal';
$conn= new mysqli('localhost',$user, $pass, $db);

    if($_POST['key'] == 'getExistingData'){
        $start = $conn->real_escape_string($_POST['start']);
        $limit = $conn->real_escape_string($_POST['limit']);
        $studentID=$conn->real_escape_string($_POST['ID']);
        $grupID=$conn->real_escape_string($_POST['grupID']);

        //$sql = $conn->query("SELECT ID, Name, CardNumber FROm student LIMIT $start,$limit");
        $sql = $conn->query("SELECT fecha, horasasi FROM asistencia where idestudiante='$studentID'and idgrupo='$grupID' LIMIT $start,$limit");
        if($sql->num_rows >0){
            $response ="";
            while($data= $sql->fetch_array()){
                $response .='
                <tr>
                    <td>'.$data["fecha"].'</td>
                    <td>'.$data["horasasi"].'</td>
                    <td>
                    <div class="col-md-2">
                        <input type="button" value="Editar" class="btn btn-danger">
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
        $sql = $conn->query("SELECT idgrupo, codigo from grupo");
        if($sql->num_rows >0){
            $response ="";
            $i=0;
            while($data= $sql->fetch_array()){

                if($i==0){
                $response .='
                <li class="active"id="'.$data["idgrupo"].'"><a>'.$data["codigo"].'</a></li>
                ';
                }else{
                $response .='
                <li onclick="activeGroup('.$data["idgrupo"].',' .$studentID.')"><a>'.$data["codigo"].'</a></li>
                ';   
                }
                ++$i;
                
            }
            exit($response);
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
                }else{
                $response .='
                <li onclick="activeGroup('.$data["idgrupo"].',' .$studentID.')"><a>'.$data["codigo"].'</a></li>
                ';   
                }
               
                
            }
            exit($response);
        } else
            exit('reachedMax');
    }
    
    if ($_POST['key'] == 'updateRow' or $_POST['key'] == 'addNew'){
        $rowID = $conn->real_escape_string($_POST['rowID']);
        $name =$conn->real_escape_string($_POST['name']);
        $ID = $conn->real_escape_string($_POST['matricula']);
        $cardNumber = $conn->real_escape_string($_POST['cardNumber']);
    if ($_POST['key'] == 'updateRow'){
      $conn->query("UPDATE estudiante SET matricula='$ID', nombre='$name', CardNumber='$cardNumber' WHERE matricula='$rowID'");
      exit('success');
     }
    }
}
?>