<?php
session_start();
if(isset($_POST['login'])){
    $userdb='root';
    $pass='';
    $db='proyectofinal';
    $conn= new mysqli('localhost',$userdb, $pass, $db);

    $user= $conn->real_escape_string($_POST['userPHP']);
    $password=md5($conn->real_escape_string($_POST['passwordPHP']));
    $privilegio = $conn->real_escape_string($_POST['privilegio']);

    $sql= $conn->query("SELECT userid,	NumCedula,privilegio FROM users WHERE username='$user'AND userpassword='$password'AND privilegio='$privilegio'");
    if($sql->num_rows >0){
        while($data=$sql->fetch_array()){
            $NumCedula=$data["NumCedula"];
            $privilegio=$data["privilegio"];
        }
        $_SESSION['loggedIN'] = '1';
        $_SESSION['user']= $user;
        $_SESSION['NumCedula']=$NumCedula;
        $_SESSION['Privilegio']=$privilegio;
   
        exit('1');
        
    }else
        exit('2');



}
?>