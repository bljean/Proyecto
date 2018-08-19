<?php
 session_start();

 
 if($_SESSION['privilegio']=='0'){
    unset($_SESSION['loggedIN']);
    unset($_SESSION['user']);
    unset($_SESSION['NumCedula']);
    unset($_SESSION['privilegio']);
    header('location: ../logadmin.php');
    }

if($_SESSION['privilegio']=='1'){
    unset($_SESSION['loggedIN']);
    unset($_SESSION['user']);
    unset($_SESSION['NumCedula']);
    unset($_SESSION['privilegio']);
    header('location: ../logdocentes.php');
           }

if($_SESSION['privilegio']=='2'){
    unset($_SESSION['loggedIN']);
    unset($_SESSION['user']);
    unset($_SESSION['NumCedula']);
    unset($_SESSION['privilegio']);
     header('location: ../logestudiante.php');
          }
                  
  session_destroy();
 
?>