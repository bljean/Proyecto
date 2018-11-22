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
        }


    }

    if($_POST['key'] == 'reporteprof'){
        $CodCampus = $conn->real_escape_string($_POST['CodCampus']);
        $CodTema = $conn->real_escape_string($_POST['CodTema']);
        $CodTP = $conn->real_escape_string($_POST['CodTP']);
        $Numgrupo = $conn->real_escape_string($_POST['Numgrupo']);
        $AnoAcad = $conn->real_escape_string($_POST['AnoAcad']);
        $Numper = $conn->real_escape_string($_POST['Numper']);
        $nombreprof = $conn->real_escape_string($_POST['nombreprof']);
        $apellido = $conn->real_escape_string($_POST['apellido']);
        $response="";
        $sql = $conn->query("SELECT COUNT(*) as cont FROM gruporecuperar WHERE CodTema='$CodTema' AND CodTp='$CodTP'  AND NumGrupo='$Numgrupo' AND CodCampus='$CodCampus' AND AnoAcad='$AnoAcad' AND PR_o_R='PR'");
        if($sql->num_rows >0){
        while($data= $sql->fetch_array()){ 
            $cont = $data["cont"];
        }
        
        }
        $sql1 = $conn->query("SELECT COUNT(*) as cont1 FROM gruporecuperar WHERE CodTema='$CodTema' AND CodTp='$CodTP'  AND NumGrupo='$Numgrupo' AND CodCampus='$CodCampus' AND AnoAcad='$AnoAcad' AND PR_o_R='E'");
        if($sql1->num_rows >0){
        while($data1= $sql1->fetch_array()){ 
            $cont1 = $data1["cont1"];
            
        }
        
        }

        $sql2 = $conn->query("SELECT COUNT(*) as cont2 FROM gruporecuperar WHERE CodTema='$CodTema' AND CodTp='$CodTP'  AND NumGrupo='$Numgrupo' AND CodCampus='$CodCampus' AND AnoAcad='$AnoAcad' AND PR_o_R='R'");
        if($sql2->num_rows >0){
        while($data2= $sql2->fetch_array()){ 
            $cont2 = $data2["cont2"];
           
        }
        
        }
       
        $suma = $cont + $cont1;
/*
        $suma = intval($cont) + intval($cont1);
        $suma1=strval($suma);
        $suma2=strval($cont2); */
 
        $response .='
        <tr>
        <td>'.$nombreprof.''.$apellido.'</td>
        <td>'.$suma.'</td>
        <td>'.$cont2.'</td>
        </tr>
         ';
         

        exit($response); 


    }


}

?>