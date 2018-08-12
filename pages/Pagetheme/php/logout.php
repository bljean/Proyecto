<?php
 session_start();

 
 if($_SESSION['privilegio']=='0'){
    unset($_SESSION['loggedIN']);
    header('location: ../logadmin.php');
    }

if($_SESSION['privilegio']=='1'){
    unset($_SESSION['loggedIN']);
    header('location: ../logdocentes.php');
           }

if($_SESSION['privilegio']=='2'){
    unset($_SESSION['loggedIN']);
     header('location: ../logestudiante.php');
          }
                  
  session_destroy();
 
?>