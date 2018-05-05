<?php
if (isset($_POST['key'])){
    
$user='root';
$pass='';
$db='proyectofinal';
$conn= new mysqli('localhost',$user, $pass, $db);

    if($_POST['key'] == 'getRowData'){
        $rowID = $conn->real_escape_string($_POST['rowID']);
        //$sql= $conn->query("SELECT ID,Name,CardNumber FROM student WHERE id ='$rowID'");
        $sql = $conn->query("SELECT usuario, nombre, apellido, CardNumber FROm profesor WHERE idprofesor='$rowID'");
        $data= $sql->fetch_array();
        $jsonArray = array(
            'ID'=> $data['usuario'],
            'Name'=> $data['nombre'],
            'CardNumber'=>$data['CardNumber'],
            'Apellido'=>$data['apellido'],
        );
        exit(json_encode($jsonArray));
    }

    if($_POST['key'] == 'getExistingData'){
        $start = $conn->real_escape_string($_POST['start']);
        $limit = $conn->real_escape_string($_POST['limit']);

        //$sql = $conn->query("SELECT ID, Name, CardNumber FROm student LIMIT $start,$limit");
        $sql = $conn->query("SELECT idprofesor, usuario, nombre, apellido, CardNumber FROm profesor LIMIT $start,$limit");
        if($sql->num_rows >0){
            $response ="";
            while($data= $sql->fetch_array()){
                $response .='
                <tr>
                    <td>'.$data["usuario"].'</td>
                    <td id="Name_'.$data["idprofesor"].'">'.$data["nombre"].' '.$data["apellido"].'</td>
                    <td id="CardNumber_'.$data["idprofesor"].'">'.$data["CardNumber"].'</td>
                    <td>
                        <input type="button" onclick="edit('.$data["idprofesor"].')" value="Editar" class="btn btn-danger">
                        <!-- <input type="button" onclick="deleteRow('.$data["usuario"].')" value="Delete" class="btn btn-danger"> -->
                    </td>
                </tr>
                ';
            }
            exit($response);
        } else
            exit('reachedMax');
    }
    $rowID = $conn->real_escape_string($_POST['rowID']);

    if($_POST['key'] == 'deleteRow'){
        $conn->query("DELETE FROM estudiante WHERE matricula = '$rowID'");
        exit('The Row Has Been Deleted');
    }
    $name =$conn->real_escape_string($_POST['name']);
    $ID = $conn->real_escape_string($_POST['matricula']);
    $cardNumber = $conn->real_escape_string($_POST['cardNumber']);
    

    if ($_POST['key'] == 'updateRow'){
      $conn->query("UPDATE profesor SET CardNumber='$cardNumber' WHERE idprofesor='$rowID'");
      exit('success');
     }
    if ($_POST['key'] == 'addNew'){
        $sql = $conn->query( "SELECT ID FROM student WHERE Name='$name'");
        if($sql->num_rows > 0)
            exit("Student with this name alredy exit!");
        else{
            $conn->query("INSERT INTO student (ID, Name , CardNumber) VALUES('$ID','$name','$cardNumber')");
            exit('Student has benn inserted!');
        }
        
    }
}
?>