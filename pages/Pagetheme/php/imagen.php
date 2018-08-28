<?php

    $userdb='root';
    $pass='';
    $db='test';
    $conn= new mysqli('localhost',$userdb, $pass, $db);

    $nombre="angenis"
    $imagen=$conn->real_escape_string(file_get_contents("/Users/Angenis/Documents/foto/WelcomeScan.jpg"));
    $sql= $conn->query("INSERT INTO foto ( nombre,imagen) VALUES ('$nombre', '$imagen')");
    




?>