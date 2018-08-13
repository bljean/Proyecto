<?php
if (isset($_POST['key'])){
    
$user='root';
$pass='';
$db='proyectofinal';
$conn= new mysqli('localhost',$user, $pass, $db);

    if($_POST['key'] == 'getRowData'){
        $rowID = $conn->real_escape_string($_POST['rowID']);
        //$sql= $conn->query("SELECT ID,Name,CardNumber FROM student WHERE id ='$rowID'");
        $sql = $conn->query("SELECT usuario, nombre, apellido_1, NumCedula,NumTarjeta FROm trabajadores WHERE NumCedula='$rowID'");
        $data= $sql->fetch_array();
        if($sql->num_rows >0){
            $jsonArray = array(
                'ID'=> $data['usuario'],
                'Name'=> $data['nombre'],
                'CardNumber'=>$data['NumTarjeta'],
                'Apellido'=>$data['apellido_1'],
            );
        }else{
            $jsonArray = array(
                'ID'=> 'esto',
                'Name'=> 'se',
                'CardNumber'=>'jodio',
                'Apellido'=>'!',
            );
        }
        
        
        exit(json_encode($jsonArray));
    }

    if($_POST['key'] == 'getExistingData'){
        $start = $conn->real_escape_string($_POST['start']);
        $limit = $conn->real_escape_string($_POST['limit']);

        //$sql = $conn->query("SELECT ID, Name, CardNumber FROm student LIMIT $start,$limit");
        $sql = $conn->query("SELECT trabajadores.NumCedula as NumCedula, usuario, nombre, apellido_1, NumTarjeta FROm trabajadores INNER JOIN contratodocencia on trabajadores.NumCedula=contratodocencia.NumCedula LIMIT $start,$limit");
        if($sql->num_rows >0){
            $response ="";
            while($data= $sql->fetch_array()){
                $response .='
                <tr>
                    <td>'.$data["usuario"].'</td>
                    <td id="Name_'.$data["NumCedula"].'">'.$data["nombre"].' '.$data["apellido_1"].'</td>
                    <td id="CardNumber_'.$data["NumCedula"].'">'.$data["NumTarjeta"].'</td>
                    <td>
                    <div class="col-md-1">
                        <input type="button" onclick="edit(\''.$data["NumCedula"].'\')" value="Editar" class="btn btn-primary">
                        </div>
                    <form method="POST" action="Asistencias.php">
                        <input type="hidden" name="ID" value="'.$data["NumCedula"].'">
                        <input type="hidden" name="privilegio" value="2">
                        <input type="hidden" name="nombre" value="'.$data["nombre"].' '.$data["apellido_1"].'">
                        <input type="submit" value="Asistencias"  class="btn btn-primary">
                    </form>
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
      $conn->query("UPDATE trabajadores SET NumTarjeta='$cardNumber' WHERE NumCedula='$rowID'");
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