<?php
session_start();
if(isset($_SESSION['loggedIN'])){
    header('Location: vista-profesor.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Area | Asistencias</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styletest.css" rel="stylesheet">
    <script src="http://cdn.ckeditor.com/4.6.1/standard/ckeditor.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
</head>

<body>

    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false"
                    aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Control de acceso</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">

            </div>
            <!--/.nav-collapse -->
        </div>
    </nav>

    <header id="header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-center"> Docentes
                        <small> Login</small>
                    </h1>
                </div>
            </div>
        </div>
    </header>

    <section id="main">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <form method="post" action="login.php" class="well">
                        <div class="form-group">
                            <label>Usuario:</label>
                            <input type="text" id="user" class="form-control" placeholder="Usuario...">
                        </div>
                        <div class="form-group">
                            <label>Contraseña:</label>
                            <input type="password" id="password" class="form-control" placeholder="Contraseña...">
                        </div>
                        <button type="button" value="Log in" id="login" class="btn btn-default btn-block">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Modals -->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
    <!-- page script -->
    <script type="text/javascript">
        $(document).ready(function () {
            $("#login").on('click', function () {
                var user = $("#user").val();
                var password = $("#password").val();
                if (user == "" || password == "") {
                    alert("Usuario o Contraseña incorrectos.")
                } else {
                    $.ajax(
                        {
                            url: 'php/login.php',
                            method: 'POST',
                            dataType: 'text',
                            data: {
                                login: 1,
                                privilegio:1,
                                userPHP: user,
                                passwordPHP: password

                            },
                            success: function (response) {
                                if(response=="1"){
                                    location.reload(); 
                                }else if(response=="2"){
                                    alert("Usuario o contrasena incorrectos");
                                }
                                   
                            },
                            dataType: 'text'
                        }
                    );
                }

            });
        });
    </script>
</body>

</html>