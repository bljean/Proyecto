<?php
if (isset($_POST['key'])){
    
$user='root';
$pass='';
$db='proyectofinal';
$conn= new mysqli('localhost',$user, $pass, $db);


if($_POST['key'] == 'getprivilegio'){
    $user = $conn->real_escape_string($_POST['user']);
    $password=md5($conn->real_escape_string($_POST['passwordPHP']));
    $sql = $conn->query("SELECT privilegio FROM users WHERE username='$user' and userpassword='$password'");
        if($sql->num_rows >0){ 
        while($data= $sql->fetch_array()){
           
                $privilegio=$data["privilegio"];
            
           
        }

    }else $privilegio="E";
   
    $jsonArray = array(
        'privilegio'=> $privilegio,
        
    );
    exit(json_encode($jsonArray));
}





}


?>