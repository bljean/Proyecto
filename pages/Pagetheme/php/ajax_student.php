<?php
if (isset($_POST['key'])){
    
$user='root';
$pass='';
$db='proyectofinal';
$conn= new mysqli('localhost',$user, $pass, $db);

    if($_POST['key'] == 'getRowData'){
        $rowID = $conn->real_escape_string($_POST['rowID']);
        //$sql= $conn->query("SELECT ID,Name,CardNumber FROM student WHERE id ='$rowID'");
        $sql = $conn->query("SELECT Matricula, nombre, apellido, NumTarjeta FROm estudiante WHERE Matricula='$rowID'");
        $data= $sql->fetch_array();
        $jsonArray = array(
            'ID'=> $data['Matricula'],
            'Name'=> $data['nombre'],
            'CardNumber'=>$data['NumTarjeta'],
            'Apellido'=>$data['apellido'],
        );
        exit(json_encode($jsonArray));
    }

    if($_POST['key'] == 'getExistingData'){
        $start = $conn->real_escape_string($_POST['start']);
        $limit = $conn->real_escape_string($_POST['limit']);

        //$sql = $conn->query("SELECT ID, Name, CardNumber FROm student LIMIT $start,$limit");
        $sql = $conn->query("SELECT Matricula, nombre, apellido, NumTarjeta FROM estudiante LIMIT $start,$limit");
        if($sql->num_rows >0){
            $response ="";
            while($data= $sql->fetch_array()){
                $response .='
                <tr>
                    <td>'.$data["Matricula"].'</td>
                    <td id="Name_'.$data["Matricula"].'">'.$data["nombre"].' '.$data["apellido"].'</td>
                    <td id="CardNumber_'.$data["Matricula"].'">'.$data["NumTarjeta"].'</td>
                    <td>
                    <div class="col-md-2">
                        <input type="button" onclick="edit('.$data["Matricula"].')" value="Tarjeta" class="btn btn-primary">
                    </div>
                    
                     <form method="POST" action="Asistencias.php">
                        <input type="hidden" name="ID" value="'.$data["Matricula"].'">
                        <input type="hidden" name="privilegio" value="1">
                        <input type="hidden" name="nombre" value="'.$data["nombre"].' '.$data["apellido"].'">
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
      $conn->query("UPDATE estudiante SET Matricula='$ID', nombre='$name', NumTarjeta='$cardNumber' WHERE Matricula='$rowID'");
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