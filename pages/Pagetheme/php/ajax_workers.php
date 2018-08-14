<?php
if (isset($_POST['key'])){
    
$user='root';
$pass='';
$db='proyectofinal';
$conn= new mysqli('localhost',$user, $pass, $db);

    //SELECT* FROM trabajadores LEFT JOIN contratodocencia ON trabajadores.NumCedula = contratodocencia.NumCedula WHERE contratodocencia.NumCedula is NULL

    if($_POST['key'] == 'getRowData'){
        $rowID = $conn->real_escape_string($_POST['rowID']);
        //$sql= $conn->query("SELECT ID,Name,CardNumber FROM student WHERE id ='$rowID'");
        $sql = $conn->query("SELECT NumCedula, nombre, apellido_1, NumTarjeta FROm trabajadores WHERE NumCedula='$rowID'");
        $data= $sql->fetch_array();
        $jsonArray = array(
            'ID'=> $data['NumCedula'],
            'Name'=> $data['nombre'],
            'CardNumber'=>$data['NumTarjeta'],
            'Apellido'=>$data['apellido_1'],
        );
        exit(json_encode($jsonArray));
    }

    if($_POST['key'] == 'getExistingData'){
        $start = $conn->real_escape_string($_POST['start']);
        $limit = $conn->real_escape_string($_POST['limit']);

        //$sql = $conn->query("SELECT ID, Name, CardNumber FROm student LIMIT $start,$limit");
        $sql = $conn->query("SELECT trabajadores.NumCedula as NumCedula, trabajadores.nombre as nombre ,trabajadores.apellido_1 as apellido_1, trabajadores.NumTarjeta as  NumTarjeta FROM trabajadores LEFT JOIN contratodocencia ON trabajadores.NumCedula = contratodocencia.NumCedula WHERE contratodocencia.NumCedula is NULL LIMIT $start,$limit");
        if($sql->num_rows >0){
            $response ="";
            while($data= $sql->fetch_array()){
                $response .='
                <tr>
                    <td>'.$data["NumCedula"].'</td>
                    <td id="Name_'.$data["NumCedula"].'">'.$data["nombre"].' '.$data["apellido_1"].'</td>
                    <td id="CardNumber_'.$data["NumCedula"].'">'.$data["NumTarjeta"].'</td>
                    <td>
                        <input type="button" onclick="edit(\''.$data["NumCedula"].'\')" value="Editar" class="btn btn-primary">
                        <!-- <input type="button" onclick="deleteRow('.$data["NumCedula"].')" value="Delete" class="btn btn-primary"> -->
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