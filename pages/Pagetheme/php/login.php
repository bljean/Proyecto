<?php
session_start();
if(isset($_POST['login'])){
    $userdb='root';
    $pass='';
    $db='proyectofinal';
    $conn= new mysqli('localhost',$userdb, $pass, $db);

    $user= $conn->real_escape_string($_POST['userPHP']);
    $password=md5($conn->real_escape_string($_POST['passwordPHP']));

    $data= $conn->query("SELECT userid FROM users WHERE username='$user'AND userpassword='$password'");
    if($data->num_rows >0){
        $_SESSION['loggedIN'] = '1';
        $_SESSION['user']= $user;
        exit('1');
    }else
        exit('2');



}
?>