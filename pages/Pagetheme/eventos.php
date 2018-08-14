

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
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false"
                    aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>s
                </button>
                <a class="navbar-brand" href="vista-administrador.php">Control de acceso</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="swipe.html">Eventos</a>
                    </li>
                    <li>
                        <div class="dropdown create">
                            <button class="btn btn-primary" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                Gestion de tarjetas
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                <li>
                                    <a href="students.html">Estudiantes</a>
                                </li>
                                <li>
                                    <a href="professors.html">Profesores</a>
                                </li>
                                <li>
                                    <a href="workers.html">Empleados</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <button type="button" value="Log out" id="Logout" class="btn btn-primary btn-block">Logout</button>
                    </li>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </nav>

    <header id="header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <h1>
                        <span class="glyphicon glyphicon-file" aria-hidden="true"></span> Eventos
                        <small></small>
                    </h1>
                </div>
            </div>
        </div>
    </header>

    <section id="breadcrumb">
        <div class="container-fluid">
            <ol class="breadcrumb">
                <li>
                    <a href="vista-administrador.php">Dashboard</a>
                </li>
                <li class="active">Eventos</li>
            </ol>
        </div>
    </section>

    <section id="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <!-- Website Overview -->
                    <div class="panel panel-default">
                        <div class="panel-heading tabla-color-bg">
                            <h3 class="panel-title">Tabla de Eventos</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="well dash-box">
                                        <div class="panel-body">
                                            <!--Add new and Edit -->
                                            <div class="container-fluid">
                                                <div id="tableManager" class="modal fade">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                                
                                                            <div class="modal-header">
                                                                <h2 class="modal-title">Nuevo</h2>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="button" id="manageBtn" onclick="manageData('addNew')" value="Save" class="btn btn-primary">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--/Add new and Edit -->

                                                <!--Table Mysql -->
                                                <div class="row">
                                                        <div class="row">
                                                            <div id="navbar" class="collapse navbar-collapse">
                                                                <ul class="nav navbar-nav">
                                                                    <li>
                                                                        <div class="dropdown create">
                                                                            <button class="btn btn-primary campustitulo" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                                                Campus
                                                                                <span class="caret"></span>
                                                                            </button>
                                                                            <ul class="dropdown-menu bodycampus" aria-labelledby="dropdownMenu1">
                                                                            </ul>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="dropdown create">
                                                                            <button class="btn btn-primary edftitulo" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                                                Edificio
                                                                                <span class="caret"></span>
                                                                            </button>
                                                                            <ul class="dropdown-menu bodyedf" aria-labelledby="dropdownMenu1">
                                                                                
                                                        
                                                                            </ul>
                                                                        </div>
                                                                    </li>
                                                        
                                                                    <li>
                                                                        <div class="dropdown create">
                                                                            <button class="btn btn-primary aulatitulo" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                                                Aula
                                                                                <span class="caret"></span>
                                                                            </button>
                                                                            <ul class="dropdown-menu bodyaula " aria-labelledby="dropdownMenu1">
                                                                                 </ul>
                                                                        </div>
                                                                        
                                                                    </li>
                                                                    <li>
                                                                            <a class="navbar-brand btn btn-primary " href="">Enter</a>
                                                                    </li>
                                                                   
                                                                    
                                                        
                                                                </ul>
                                                        
                                                        
                                                        
                                                        
                                                            </div>
                                                        </div>
                                        
                                                </div>
                                            </div>
                                            <!--/Table Mysql -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Latest Users -->
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

    <!--Script addNew -->
    
<script type="text/javascript"> 
$(document).ready(function () {
    getCampusData();
      });
function getCampusData(){
    $.ajax({
            url: 'php/ajax_eventos.php',
            method: 'POST',
            dataType: 'text',
            data: {
                  key: 'getCampusData',
                  }, success: function (response) {
                   
                    $(".bodycampus").append(response);
                  }
              });
    }
 function getedfcampus(campus){
    $.ajax({
            url: 'php/ajax_eventos.php',
            method: 'POST',
            dataType: 'text',
            data: {
                  key: 'getedfcampus',
                  campus: campus,
                  }, success: function (response) {
                    $(".campustitulo").html('');
                    $(".campustitulo").append(campus);
                    $(".bodyedf").html('');
                    $(".bodyedf").append(response);
                    

                  }
              });


 }

 function getaulaedf(edf,campus){
    $.ajax({
            url: 'php/ajax_eventos.php',
            method: 'POST',
            dataType: 'text',
            data: {
                  key: 'getaulaedf',
                  edf: edf,
                  campus: campus,
                  }, success: function (response) {
                  
                    $(".bodyaula").html('');
                    $(".bodyaula").append(response);
                    $(".edftitulo").html('');
                    $(".edftitulo").append(edf);

                  }
              });
    }

     function getaula(edf,campus,aula){
    $.ajax({
            url: 'php/ajax_eventos.php',
            method: 'POST',
            dataType: 'text',
            data: {
                  key: 'getaulaedf',
                  edf: edf,
                  campus: campus,
                  aula: aula,
                  }, success: function (response) {
                  
                    $(".bodyaula").html('');
                    $(".bodyaula").append(response);
                    $(".edftitulo").html('');
                    $(".edftitulo").append(edf);

                  }
              });
    }





</script>


</body>

</html>