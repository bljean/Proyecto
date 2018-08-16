<?php
if (isset($_POST['key'])){
    
$user='root';
$pass='';
$db='proyectofinal';
$conn= new mysqli('localhost',$user, $pass, $db);

if($_POST['key'] == 'diasemana')
{   
    
    $sqlsemana=$conn->query("SELECT NombreLargo FROM diasemana");
   

    if($sqlsemana->num_rows>0){ 
        while($data=$sqlsemana->fetch_array()){
            $response []= $data["NombreLargo"];
            
            }  
             
        }
        $jsonArray = array(
            'body'=> $response,
           
        );
        exit(json_encode($jsonArray));
        
}





    

  
}
?>