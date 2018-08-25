<?php
session_start();
if(isset($_SESSION['loggedIN'])){
  
}else{
  header('Location: logdocentes.php');
  exit();
}
 $ID= $_SESSION['user'];
 $privilegio=$_SESSION['privilegio'];
 if($privilegio==1){
 $NumCedula =$_SESSION['NumCedula'];
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
    <link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.css" rel="stylesheet"/>
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
                    <a class="navbar-brand" href="vista-profesor.php">Inicio</a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="MisgruposDocente.php">Mis Grupos</a>
                        </li>
                        <li>
                            <a href="AsistenciaDocente.php">Asistencia</a>
                        </li>
                        <li>
                            <a href="RecuperacionDocentes.php">Recuperacion</a>
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
            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Recuperacion
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
          <a href="vista-profesor.php">Dashboard</a>
        </li>
        
        <li class="active">Recuperacion</li>
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
                            <h3 class="panel-title">Grupos a Recuperar</h3>
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
                                                                <h2 class="modal-title">Recuperacion</h2>
                                                            </div>
                                                            <div class="modal-body">
                                                            <div class="row">
                                                                    <div class="col-md-4" style="text-align: left;">
                                                                        <h4>Periodo Academico:</h4>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="text" class="form-control" placeholder="Periodo..." id="Periodo" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4" style="text-align: left;">
                                                                        <h4>Grupo:</h4>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="text" class="form-control" placeholder="ID..." id="ID" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4" style="text-align: left;">
                                                                        <h4>Fecha a Recuperar:</h4>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="text" class="form-control" placeholder="fecharecuperar..." id="fecharecuperar" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4"style="text-align: left;">
                                                                        <h4>Horas a Recuperar:</h4>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="text" class="form-control" placeholder="horas..." id="HR" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4" style="text-align: left;">
                                                                        <h4>Fecha:</h4>
                                                                    </div>
                                                                    <div class="col-sm-6" style="height: 34px;" >
                                                                            <div class="form-group">
                                                                                <div class='input-group date' id='datetimepicker1'>
                                                                                    <input type='text' id="fecha" class="form-control" />
                                                                                    <span class="input-group-addon">
                                                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4" style="text-align: left;">
                                                                        <h4>Hora:</h4>
                                                                    </div>
                                                                    <div class="col-sm-6" style="height: 34px;" >
                                                                            <div class="form-group">
                                                                                <div class='input-group date' id='datetimepicker2'>
                                                                                    <input type='text' id="hora" class="form-control" />
                                                                                    <span class="input-group-addon">
                                                                                        <span class="glyphicon glyphicon-time"></span>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4"style="text-align: left;">
                                                                        <h4>Aula:</h4>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="text" class="form-control" placeholder="Aula..." id="Aula" readonly="readonly">
                                                                    </div>
                                                                    <div class="col-md-1"style="padding-left: 0px;" >
                                                                        <input type="button" id="AulaBtn" value="Aula" class="btn btn-primary">
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="button" id="SaveBtn" value="Save" class="btn btn-primary">
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
                                                                <td>Periodo</td>
                                                                <td>Grupo</td>
                                                                <td>Materia</td>
                                                                <td>Fecha</td>
                                                                <td>Horas a Recuperar</td>
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
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
    <!-- page script -->

    <!--Script addNew -->
    <script type="text/javascript">
        $(function () {
            var today = new Date();
            $('#datetimepicker1').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $("#datetimepicker1").on("click", function (e) {
            $('#datetimepicker1').data("DateTimePicker").minDate(today);
            //$('#datetimepicker2').data("DateTimePicker").minDate(today);
            });
            $('#datetimepicker2').datetimepicker({
                format: 'HH:mm:ss'
            });
        });
        $(document).ready(function () {
            var NumCedula = "<?php echo $NumCedula; ?>";
            $("#Logout").on('click', function () {
                window.location = 'php/logout.php'
            });
            $("#AulaBtn").on('click', function () {
            var HoraRecuperar = $("#HR");
            var grupo = $("#ID");
            var fecha=$("#fecha");
            var hora=$("#hora");

            if (isNotEmpty(HoraRecuperar) && isNotEmpty(grupo) && isNotEmpty(fecha) && isNotEmpty(hora) ) {
                getaula(grupo.val(),HoraRecuperar.val(),fecha.val(),hora.val());
            }
            });
            $("SaveBtn").on('click',function(){
            var periodo=$("#Periodo");
            var grupo = $("#ID");
            var fecharecupera=$("#fecharecuperar");
            var HoraRecuperar = $("#HR");
            var fecha=$("#fecha");
            var hora=$("#hora");
            var aula=$("#Aula");
            if (isNotEmpty(periodo) && isNotEmpty(grupo) && isNotEmpty(fecharecupera) && isNotEmpty(HoraRecuperar) && isNotEmpty(fecha) && isNotEmpty(hora)&& isNotEmpty(aula) ){
                inGrupoRecuperar(periodo,grupo,fecharecupera,HoraRecuperar,fecha,hora,aula);
            }
                
            });
            getExistingData(0, 50,NumCedula);
            
        });
        function getaula(grupo,HoraRecuperar,fecha,hora){
            $.ajax({
                url: 'php/ajax_RecuperacionDocentes.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    key:'getaula',
                    grupo:grupo,
                    HoraRecuperar:HoraRecuperar,
                    fecha:fecha,
                    hora:hora,
                }, success: function (response) {  
                   $("#Aula").val(response.aula);
                }
            });
        }
        function  inGrupoRecuperar(periodo,grupo,fecharecupera,HoraRecuperar,fecha,hora,aula){
            $.ajax({
                url: 'php/ajax_RecuperacionDocentes.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    key:'inGrupoRecuperar',
                    periodo: periodo,
                    grupo:grupo,
                    fecharecupera:fecharecupera,
                    HoraRecuperar:HoraRecuperar,
                    fecha:fecha,
                    hora:hora,
                    aula: aula,
                }, success: function (response) {  
                   
                }
            });
        }
        function edit(CodCampus,CodTema,CodTP,Numgrupo,AnoAcad,Numper,Horas,FechaRecuperar,Periodo) {
            $("#ID").val(''+CodCampus+'-'+CodTema+'-'+CodTP+'-'+Numgrupo+'');
            $("#HR").val(Horas);
            $("#fecharecuperar").val(FechaRecuperar);
            $("#Periodo").val(Periodo);
            $("#tableManager").modal('show');
        }

        function getExistingData(start, limit,NumCedula) {
            $.ajax({
                url: 'php/ajax_RecuperacionDocentes.php',
                method: 'POST',
                dataType: 'text',
                data: {
                    key: 'getExistingData',
                    start: start,
                    limit: limit,
                    NumCedula:NumCedula,
                }, success: function (response) {
                    if (response != "reachedMax") {
                        $('tbody').append(response);
                        start += limit;
                        getExistingData(start, limit,NumCedula);
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
        function isNotEmpty(caller) {
            if (caller.val() == '') {
                caller.css('border', '1px solid red');
                return false;
            } else caller.css('border', '');

            return true;
        }
        
        
    
    </script>
</body>

</html>