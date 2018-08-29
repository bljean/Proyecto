<?php

    $userdb='root';
    $pass='';
    $db='test';
    $conn= new mysqli('localhost',$userdb, $pass, $db);

    //$nombre="angenis"
    //$imagen=$conn->real_escape_string(file_get_contents("/Users/Angenis/Documents/foto/WelcomeScan.jpg"));

    foreach ($_FILES['photos']['name'] as $name => $value)
    {
	
        $filename = $_FILES['photos']['name'][$name];
        $imagetmp=addslashes(file_get_contents($_FILES['photos']['tmp_name'][$name]));
        $sql= $conn->query("INSERT INTO foto ( nombre,imagen) VALUES ('$filename', '$imagetmp')");
    }
    /*
    $imagename=$_FILES["myimage"]["name"]; 

    //Get the content of the image and then add slashes to it 
    $imagetmp= (file_get_contents($_FILES['myimage']['tmp_name']));
    

    $sql= $conn->query("INSERT INTO foto ( nombre,imagen) VALUES ('$imagename', '$imagetmp')");
    
    */



?>