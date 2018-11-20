<?php
if (isset($_POST['key'])){
    
$user='root';
 $pass='';
 $db='proyectofinal';
 $conn= new mysqli('localhost',$user, $pass, $db);
    
    if($_POST['key'] == 'reporte'){
        $CodCampus = $conn->real_escape_string($_POST['CodCampus']);
        $CodTema = $conn->real_escape_string($_POST['CodTema']);
        $CodTP = $conn->real_escape_string($_POST['CodTP']);
        $Numgrupo = $conn->real_escape_string($_POST['Numgrupo']);
        $AnoAcad = $conn->real_escape_string($_POST['AnoAcad']);
        $Numper = $conn->real_escape_string($_POST['Numper']);
        $NumCreditos = $conn->real_escape_string($_POST['NumCreditos']);
        $sql = $conn->query("SELECT Matricula,NumAusencias FROM grupoinsest WHERE CodTema='$CodTema' AND CodTP='$CodTP' AND CodCampus='$CodCampus'AND Numgrupo='$Numgrupo' AND AnoAcad='$AnoAcad' AND NumPer='$Numper' ");
        if($sql->num_rows >0){
            $response ="";
            while($data= $sql->fetch_array()){
                $Matricula   = $data["Matricula"];
                $cantidadausencias   = $data["NumAusencias"];
    
                $sql1 = $conn->query("SELECT nombre, apellido FROM estudiante WHERE Matricula='$Matricula'");
                if($sql1->num_rows >0){
                 while($data= $sql1->fetch_array()){
                    $nombreest   = $data["nombre"];
                    $apellidoest   = $data["apellido"];  
                }
                }

                if($cantidadausencias>$NumCreditos*3)
                {
                    $status='FN';

                }else{
                    $status='Normal';
                }
                $response .='
                <tr>
                <td>'.$CodCampus.'-'.$CodTema.'-'.$CodTP.'-'.$Numgrupo.'</td>
                <td>'.$nombreest.' '.$apellidoest.'</td>
                <td>'. $cantidadausencias.'</td>
                <td>'. $status.'</td>
                </tr>
                 
                ';
            
            }
            exit($response);
        }else
            exit('reachedMax');


    }


}

?>