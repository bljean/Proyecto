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
    <script src="https://js.pusher.com/4.3/pusher.min.js"></script>
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
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="vista-administrador.php">Inicio</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="eventos.php">Eventos</a>
                    </li>
                    <li>
                        <a href="ConfiguracionGrupo.php">Grupos</a>
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
                        <span class="glyphicon glyphicon-book" aria-hidden="true"></span> Grupos
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
                <li class="active">Grupos</li>
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
                            <h3 class="panel-title">Grupos</h3>
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
                                                                <h2 class="modal-title">Configuracion</h2>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <h4>Grupo:</h4>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="text" class="form-control" placeholder="ID..." id="ID" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <h4>Tardanza:</h4>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="text" class="form-control" placeholder="Card Number.." id="CardNumber" readonly="readonly">
                                                                    </div>
                                                                    <div class="dropdown create col-md-2 Tiempo1" id="dropdownhora" >
                                                                    <select id="tiempo" class="btn btn-primary" type="button">
                                                                        <option selected="selected" value="val2" >Tiempo</option>
                                                                        <option value="5">5</option>
                                                                        <option value="10">10</option>
                                                                        <option value="15">15</option>
                                                                        <option value="20">20</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="button" id="manageBtn" onclick="findDay()" value="Save" class="btn btn-primary">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--/Add new and Edit -->
                                              
                                                <!--Table Mysql -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-hover table-bordered" style="background-color:white ">
                                                            <thead>
                                                                <td>Grupo</td>
                                                                <td>Nombre</td>
                                                                <td>Cred</td>
                                                                <td>Profesor</td>
                                                                <td>Periodo</td>
                                                                <td>Opciones</td>
                                                            </thead>
                                                            <tbody>

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
            $("#Logout").on('click', function () {
                window.location = 'php/logout.php'
            });
            $("#addNew").on('click', function () {
                cleanModal();
                $(".modal-title").html('Add New');
                $("#tableManager").modal('show');

            });
            notificacion("admin");
            getExistingData(0, 50);
            
        });
        function notificacion(ID){
             //notificaciones
            // Enable pusher logging - don't include this in production
            Pusher.logToConsole = true;
            var pusher = new Pusher('8b7b30cb5814aead90c6', {
            cluster: 'mt1',
            encrypted: true
            });
            var channel = pusher.subscribe(''+ID+'');
            channel.bind('my-event', function(data) {
            alert(JSON.stringify(data));
           
            });
            //final de notificaciones
        }
        function findDay(CodCampus,CodTema,CodTP,Numgrupo,AnoAcad,Numper){
             
            var eID = document.getElementById("tiempo");
            var dayVal = eID.options[eID.selectedIndex].value;
            var daytxt = eID.options[eID.selectedIndex].text;
            //alert("Selected Item  " +  daytxt + ", Value " + dayVal);
            
            if(dayVal=='Tiempo'){
                alert("Seleccione un tiempo");
            }else{
            $.ajax({
                url: 'php/ajax_ConfiguracionGrupo.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    key:'findDay',
                    dayVal:dayVal,
                    CodCampus:CodCampus,
                    CodTema:CodTema,
                    CodTP:CodTP,
                    Numgrupo:Numgrupo,
                    AnoAcad:AnoAcad,
                    Numper:Numper,
                }, success: function (response) {  
                    $("#tableManager").modal('hide');
                    $("div.Tiempo1 select").val("val2")
                }
            });}
            }

        function edit(CodCampus,CodTema,CodTP,Numgrupo,AnoAcad,Numper) {
            $.ajax({
                url: 'php/ajax_ConfiguracionGrupo.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    key: 'edit1',
                    CodCampus:CodCampus,
                    CodTema:CodTema,
                    CodTP:CodTP,
                    Numgrupo:Numgrupo,
                    AnoAcad:AnoAcad,
                    Numper:Numper,
                }, success: function (response) {
                    $("#ID").val(response.Grupo);
                    $("#CardNumber").val(response.Tardanza);
                    //alert('findDay(\''+CodCampus+'\',\''+CodTema+'\',\''+CodTP+'\',\''+Numgrupo+'\',\''+AnoAcad+'\',\''+Numper+'\')');
                    $("#manageBtn").attr('onclick','findDay(\''+CodCampus+'\',\''+CodTema+'\',\''+CodTP+'\',\''+Numgrupo+'\',\''+AnoAcad+'\',\''+Numper+'\')');
                    $("div.Tiempo1 select").val("val2")
                    $("#tableManager").modal('show'); 
                }
            });
        }
        function getExistingData(start, limit) {
            $.ajax({
                url: 'php/ajax_ConfiguracionGrupo.php',
                method: 'POST',
                dataType: 'text',
                data: {
                    key: 'getExistingData',
                    start: start,
                    limit: limit
                }, success: function (response) {
                    if (response != "reachedMax") {
                        $('tbody').append(response);
                        start += limit;
                        getExistingData(start, limit);
                    } else {

                        $(".table").DataTable({
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

                }
            });
        }
        
        
    
    </script>
</body>

</html>