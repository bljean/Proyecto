<?php
if (isset($_POST['key'])){
    
$user='root';
$pass='';
$db='proyectofinal';
$conn= new mysqli('localhost',$user, $pass, $db);

    if($_POST['key'] == 'getRowData'){
        $rowID = $conn->real_escape_string($_POST['rowID']);
        //$sql= $conn->query("SELECT ID,Name,CardNumber FROM student WHERE id ='$rowID'");
        $sql = $conn->query("SELECT matricula, nombre, apellido, CardNumber FROm estudiante WHERE matricula='$rowID'");
        $data= $sql->fetch_array();
        $jsonArray = array(
            'ID'=> $data['matricula'],
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
        $sql = $conn->query("SELECT matricula, nombre, apellido, CardNumber FROm estudiante LIMIT $start,$limit");
        if($sql->num_rows >0){
            $response ="";
            while($data= $sql->fetch_array()){
                $response .='
                <tr>
                    <td>'.$data["matricula"].'</td>
                    <td id="Name_'.$data["matricula"].'">'.$data["nombre"].' '.$data["apellido"].'</td>
                    <td id="CardNumber_'.$data["matricula"].'">'.$data["CardNumber"].'</td>
                    <td>
                    <div class="col-md-2">
                        <input type="button" onclick="edit('.$data["matricula"].')" value="Editar" class="btn btn-danger">
                    </div>
                    <form action="http://localhost/proyecto/pages/Pagetheme/Asistencias.html">
                        <input type="submit" id="asisButton"  value="Asistencias" class="btn btn-danger">
                    </form>
                    </td>
                </tr>
                ';
            }
            exit($response);
        } else
            exit('reachedMax');
    }
  

    if($_POST['key'] == 'deleteRow'){
        $rowID = $conn->real_escape_string($_POST['rowID']);
        $conn->query("DELETE FROM estudiante WHERE matricula = '$rowID'");
        exit('The Row Has Been Deleted');
    }
    $rowID = $conn->real_escape_string($_POST['rowID']);
    $name =$conn->real_escape_string($_POST['name']);
    $ID = $conn->real_escape_string($_POST['matricula']);
    $cardNumber = $conn->real_escape_string($_POST['cardNumber']);
    
    if ($_POST['key'] == 'updateRow' or $_POST['key'] == 'addNew'){
    if ($_POST['key'] == 'updateRow'){
      $conn->query("UPDATE estudiante SET matricula='$ID', nombre='$name', CardNumber='$cardNumber' WHERE matricula='$rowID'");
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
}
?>