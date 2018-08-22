<?php
session_start();
if(isset($_SESSION['loggedIN'])){
  
}else{
  header('Location: logadmin.php');
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
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false"
                    aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>s
                </button>
                <a class="navbar-brand" href="vista-administrador.php">Inicio</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="eventos.php">Eventos</a>
                    </li>
                    <li>
                        <div class="dropdown create">
                            <button class="btn btn-primary" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                Gestion de tarjetas
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                <li>
                                    <a href="students.php">Estudiantes</a>
                                </li>
                                <li>
                                    <a href="professors.php">Profesores</a>
                                </li>
                                <li>
                                    <a href="workers.php">Empleados</a>
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
                        <div class="panel-body">
                        
                            <div class="row">
                                <div id="navbar" class="collapse navbar-collapse">
                                    <ul class="nav navbar-nav">
                                        <li>
                                            <div class="dropdown create">
                                                <button class="btn btn-primary campustitulo" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="true">
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
                                            <a class="navbar-brand btn btn-primary  " id="boton1" onclick="llenartabla()" disabled >Enter</a>
                                        </li>
                        
                        
                        
                                    </ul>
                        
                        
                        
                        
                                </div>
                            </div>
                        
                        
                        
                        </div>
                            <div class="row " >
                                <div class="col-md-12">
                                    <div class="well dash-box " id="esconder">
                                        <div class="panel-body">
                                            <!--Add new and Edit -->
                                            <div class="container-fluid">
                                                <div id="tableManager" class="modal fade">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content ">
                                                                
                                                            <div class="modal-header">
                                                                <h2 class="modal-title">Nuevo</h2>
                                                            </div>
                                                            <div class="modal-body">
                                                                <span>Cedula:</span>
                                                                <input type="text" class="form-control" placeholder="ID..." id="ID" readonly="readonly">
                                                                <br>
                                                                <span>Nombre:</span>
                                                                <input type="text" class="form-control" placeholder="Name..." id="Name" readonly="readonly">
                                                                <br>
                                                                <span>Apellido:</span>
                                                                <input type="text" class="form-control" placeholder="Apellido.." id="Apellido" readonly="readonly">
                                                                <br>
                                                                <span>Tarjeta:</span>
                                                                <input type="text" class="form-control" placeholder="Card Number.." id="CardNumber">
                                                                <input type="hidden" id="editRowID" value="0">
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
                                                    <div class="col-md-12 " >
                                                        <table id="tableswipe" class="table table-hover table-bordered" style="background-color:white ">
                                                            <thead>
                                                                <td>Tarjeta</td>
                                                                <td>ID</td>
                                                                <td>Nombre</td>
                                                                <td>Estado</td>
                                                                <td>Fecha</td>
                                                            </thead>
                                                            <tbody class="bodyeventos">

                                                            </tbody>
                                                        </table>
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
                    dataindex=0;
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

                    $(".edftitulo").html('Edificio');
                    $(".aulatitulo").html('Aula');
                    $(".campustitulo").html('');
                    $(".campustitulo").append(campus);
                    $(".bodyedf").html('');
                    $(".bodyaula").html('');
                    $(".bodyedf").append(response);
                    $("#boton1").attr("disabled", "disabled");

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
                    $(".aulatitulo").html('Aula');
                    $(".bodyaula").html('');
                    $(".bodyaula").append(response);
                    $(".edftitulo").html('');
                    $(".edftitulo").append(edf);
                    $("#boton1").attr("disabled", "disabled");

                  }
              });
    }

function getaula(aula,campus,edf){
        $(".aulatitulo").html('');
        $(".aulatitulo").append(aula);
        $("#boton1").removeAttr("disabled");
      //  $("#esconder").modal('show');
       
    }

function llenartabla(){
        
        $("#esconderrow").modal('show');
         var aula= $(".aulatitulo").text();
         var edf= $(".edftitulo").text();
         var campus= $(".campustitulo").text();
         $.ajax({
            url: 'php/ajax_eventos.php',
            method: 'POST',
            dataType: 'text',
            data: {
                  key: 'llenartabla',
                  edf: edf,
                  campus: campus,
                  aula: aula,
                  }, success: function (response) {
                        if(dataindex != 0){
                            dTable.destroy();
                        }
                        $(".bodyeventos").html('');
                        $(".bodyeventos").append(response);
                        
                        dataindex=1;
                        dTable = $(".table").DataTable({
                            "language": {
                                "sProcessing": "Procesando...",
                                "sLengthMenu": "Mostrar _MENU_ registros",
                                "sZeroRecords": "No se encontraron resultados",
                                "sEmptyTable": "Ningún dato disponible en esta tabla",
                                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                                "sInfoPostFix": "",
                                "sSearch": "Buscar:",
                                "sUrl": "",
                                "sInfoThousands": ",",
                                "sLoadingRecords": "Cargando...",
                                "oPaginate": {
                                    "sFirst": "Primero",
                                    "sLast": "Último",
                                    "sNext": "Siguiente",
                                    "sPrevious": "Anterior"
                                },
                                "oAria": {
                                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                                }
                            },
                            "lengthChange": false
                        });
                  }
              });


    }






</script>


</body>

</html>