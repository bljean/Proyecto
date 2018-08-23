<?php
if (isset($_POST['key'])){
    
$user='root';
$pass='';
$db='proyectofinal';
$conn= new mysqli('localhost',$user, $pass, $db);

    

    if($_POST['key'] == 'getExistingData'){
        $start = $conn->real_escape_string($_POST['start']);
        $limit = $conn->real_escape_string($_POST['limit']);

        
        $sql = $conn->query("SELECT CodTema, CodTP, Numgrupo, CodCampus, AnoAcad, Numper,NumCedula FROM contratodocencia  LIMIT $start,$limit");
        if($sql->num_rows >0){
            $response ="";
            while($data= $sql->fetch_array()){
                $NumCedula   = $data["NumCedula"];
                $CodTema   = $data["CodTema"];
                $CodTP   = $data["CodTP"];
                $Numgrupo   = $data["Numgrupo"];
                $CodCampus   = $data["CodCampus"];
                $AnoAcad   = $data["AnoAcad"];
                $Numper   = $data["Numper"];
                $sql1 = $conn->query("SELECT nombre, apellido_1 FROM trabajadores WHERE NumCedula='$NumCedula'");
                if($sql1->num_rows >0){
                while($data= $sql1->fetch_array()){
                    $nombreprof   = $data["nombre"];
                    $apellido   = $data["apellido_1"];
                }
                }
                $sql2 = $conn->query("SELECT Nombre, NumCreditos FROM asignatura WHERE CodTema='$CodTema' AND CodTp='$CodTP'");
                if($sql2->num_rows >0){
                while($data= $sql2->fetch_array()){
                    $Nombre   = $data["Nombre"];
                    $NumCreditos   = $data["NumCreditos"];
                }    
                }
                $response .='
                <tr>
                <td>'.$CodCampus.'-'.$CodTema.'-'.$CodTP.'-'.$Numgrupo.'</td>
                <td>'. $Nombre.'</td>
                <td>'. $NumCreditos.'</td>
                <td>'.$nombreprof.' '.$apellido.'</td>
                <td>'.$AnoAcad.'/'.$Numper.'</td>
                </tr>
                 
                ';
            }
            exit($response);
        } else
            exit('reachedMax');
    }
    
    

  
}
?>