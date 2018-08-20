<?php
if (isset($_POST['key'])){
    
$user='root';
$pass='';
$db='proyectofinal';
$conn= new mysqli('localhost',$user, $pass, $db);

if($_POST['key'] == 'getestgrupo'){
    $NumGrupo=$conn->real_escape_string($_POST['NumGrupo']);
    $CodTema=$conn->real_escape_string($_POST['CodTema']);
    $CodTP=$conn->real_escape_string($_POST['CodTP']);
    $CodCampus=$conn->real_escape_string($_POST['CodCampus']);
    $AnoAcad=$conn->real_escape_string($_POST['AnoAcad']);
    $NumPer=$conn->real_escape_string($_POST['NumPer']);
    //$sql = $conn->query("SELECT ID, Name, CardNumber FROm student LIMIT $start,$limit");
    $sql = $conn->query("SELECT Matricula FROM   ");
    if($sql->num_rows >0){
        $response ="";
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


}
?>