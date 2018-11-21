<?php
session_start();
$CodCampus= $_POST['CodCampus'];
$CodTema= $_POST['CodTema'];
$CodTP= $_POST['CodTP'];
$Numgrupo= $_POST['Numgrupo'];
$AnoAcad= $_POST['AnoAcad'];
$Numper= $_POST['Numper'];
$NumCreditos= $_POST['NumCreditos'];
$nombreprof= $_POST['nombreprof'];
$apellido= $_POST['apellido'];

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
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
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
                            <button class="btn btn-primary" type="button" id="dropdownMenu1" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="true">
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
                        <span class="glyphicon glyphicon-book" aria-hidden="true"></span> Reportes
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
                <li>
                    <a href="ConfiguracionGrupo.php">Grupos</a>
                </li>
                <li class="active">Reportes</li>
            </ol>
        </div>
    </section>

    <section id="main">
        <div class="container-fluid">
            
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading tabla-color-bg">
                            <h3 class="panel-title">Profesor</h3>
                        </div>
                        <div class="panel-body">
                            <div class="well dash-box">
                            <table class="table table-hover table-bordered tableprof" style="background-color:white  ">
                                <thead>
                                    <td>Nombre</td>
                                    <td>Clases por recuperar</td>
                                    <td>Clases recuperadas</td>
                                </thead>
                                <tbody class="bodyprof">

                                </tbody>
                                </table>
                                
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading tabla-color-bg">
                        <h3 class="panel-title">Estudiantes</h3>
                    </div>
                    <div class="panel-body">
                        <div class="well dash-box">
                            <table class="table table-hover table-bordered tableest" style="background-color:white  ">
                                <thead>
                                    <td>Grupo</td>
                                    <td>Nombre</td>
                                    <td>Ausencias</td>
                                    <td>Estatus</td>
                                </thead>
                                <tbody class="bodyest">

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Latest Users -->
                </div>
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
            var Numgrupo = "<?php echo $Numgrupo; ?>";
            var CodTema = "<?php echo $CodTema; ?>";
            var CodTP = "<?php echo $CodTP; ?>";
            var CodCampus = "<?php echo $CodCampus; ?>";
            var AnoAcad = "<?php echo $AnoAcad; ?>";
            var Numper = "<?php echo $Numper; ?>";
            var NumCreditos = "<?php echo $NumCreditos; ?>";
            var nombreprof = "<?php echo $nombreprof; ?>";
            var apellido = "<?php echo $apellido; ?>";

            $("#Logout").on('click', function () {
                window.location = 'php/logout.php'
            });
            $("#addNew").on('click', function () {
                cleanModal();
                $(".modal-title").html('Add New');
                $("#tableManager").modal('show');

            });
            //notificacion("admin");
            //getExistingData(0, 50);
            reporte(Numgrupo, CodTema, CodTP, CodCampus, AnoAcad, Numper, NumCreditos);
            reporteprof(Numgrupo, CodTema, CodTP, CodCampus, AnoAcad, Numper,nombreprof,apellido);

        });
        
        function reporteprof(Numgrupo, CodTema, CodTP, CodCampus, AnoAcad, Numper,nombreprof,apellido){
            $.ajax({
                url: 'php/ajax_reporte.php',
                method: 'POST',
                dataType: 'text',
                data: {
                    key: 'reporteprof',
                    Numgrupo: Numgrupo,
                    CodTema: CodTema,
                    CodTP: CodTP,
                    CodCampus: CodCampus,
                    AnoAcad: AnoAcad,
                    Numper: Numper,
                    nombreprof:nombreprof,
                    apellido:apellido,
                }, success: function (response) {
                    console.log(response);
                    $(".bodyprof").append(response);
                    $(".tableprof").DataTable({
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

        function reporte(Numgrupo, CodTema, CodTP, CodCampus, AnoAcad, Numper, NumCreditos) {
            $.ajax({
                url: 'php/ajax_reporte.php',
                method: 'POST',
                dataType: 'text',
                data: {
                    key: 'reporte',
                    Numgrupo: Numgrupo,
                    CodTema: CodTema,
                    CodTP: CodTP,
                    CodCampus: CodCampus,
                    AnoAcad: AnoAcad,
                    Numper: Numper,
                    NumCreditos: NumCreditos,
                }, success: function (response) {
                        $(".bodyest").append(response);
                        $(".tableest").DataTable({
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