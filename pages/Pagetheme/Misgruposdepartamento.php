<?php
session_start();
if(isset($_SESSION['loggedIN'])){
  
}else{
  header('Location: logadmin.php');
  exit();
}
$ID=$_SESSION['user'];
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
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="vista-departamento.php">Inicio</a>
      </div>
      <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
          <li>
            <a href="Misgruposdepartamento.php">Mis Grupos</a>
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
            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Mis Grupos
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
          <a href="vista-departamento.php">Dashboard</a>
        </li>
        
        
        <li class="active">Mis Grupos</li>
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
                            <h3 class="panel-title">Mis Grupos</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="well dash-box">
                                        <div class="panel-body">
                                            <!--Add new and Edit -->
                                            <div class="container-fluid">
                                            <div id="tableManager" class="modal fade">
                                                <div class="modal-dialog" style="width:1250px;">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h2 class="modal-title">Asistencia</h2>
                                                        </div>
                                                        <div class="modal-body">
                                                        <div class="well dash-box" style=" text-align: center;">
                                                            <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                <h4 id="tituloGrupo"></h4>
                                                                </div>
                                                            </div>
                                                            <table class="table table-striped table-hover tableAsistencia">
                                                                <thead>
                                                                <th>Fechas</th>
                                                                <th>Dia de Semana</th>
                                                                <th>Hora Inicio</th>
                                                                <th>Hora Termino</th>
                                                                <th>Hora Llegada</th>
                                                                <th>Horas Presente</th>
                                                                <th>Asistencia</th>
                                                                <th>Opciones</th>

                                                                </thead>
                                                                <tbody class="tableAsistenciaBody">

                                                                </tbody>
                                                        </table>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                                <!--/Add new and Edit -->

                                                <!--Table Mysql -->
                                                <div class="row">
                                                    <div class="col-md-12" >
                                                        <table class="table table-hover table-bordered table-informacion" style="background-color:white ">
                                                            <thead>
                                                              <td>Grupo</td>
                                                                <td>Nombre</td>
                                                                <td>Cred</td>
                                                                <td>Profesor</td>
                                                                <td>Periodo</td>
                                                                <td>Opciones</td>
                                                            </thead>
                                                            <tbody class="body-informacion">

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
  <script type="text/javascript">
    $(document).ready(function () {
      dataindex1=0;
      var ID = "<?php echo $ID; ?>";
      
      $("#Logout").on('click', function () {
            window.location= 'php/logout.php'
      });
      getcedula(ID);
      
      });

     function getExistingData(start, limit,ID) {
            $.ajax({
                url: 'php/ajax_Misgruposdepartamento.php',
                method: 'POST',
                dataType: 'text',
                data: {
                    key: 'getExistingData',
                    start: start,
                    limit: limit,
                    ID:ID,
                }, success: function (response) {
                    if (response != "reachedMax") {
                        $(".body-informacion").append(response);
                        start += limit;
                        getExistingData(start, limit, ID);
                    } else {

                        $(".table-informacion").DataTable({
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
        
    function getcedula(ID){
      $.ajax({
              url: 'php/ajax_Misgruposdepartamento.php',
              method: 'POST',
              dataType: 'json',
              data: {
                    key: 'getcedula',
                    ID: ID,
                    }, success: function (response) {
                        getExistingData(0, 50,response.NumCedula);
                    }
                });
      }

    function asistencia(studentID, NumGrupo, CodTema, CodTP, CodCampus, AnoAcad, NumPer) {
        if (dataindex1 != 0) {
            //$(".tableAsistenciaBody").html("");
            cleartable(dTable1);
        }
        dataindex1 = 1;
        getAsisData(0, 50, studentID, NumGrupo, CodTema, CodTP, CodCampus, AnoAcad, NumPer, 1);

        $("#tableManager").modal('show');
        }

    function getAsisData(start, limit, studentID, NumGrupo, CodTema, CodTP, CodCampus, AnoAcad, NumPer, privilegio) {
      $.ajax({
        url: 'php/ajax_Asistencias.php',
        method: 'POST',
        dataType: 'text',
        data: {
          key: 'getAsisData',
          start: start,
          limit: limit,
          studentID: studentID,
          NumGrupo: NumGrupo,
          CodTema: CodTema,
          CodTP: CodTP,
          CodCampus: CodCampus,
          AnoAcad: AnoAcad,
          NumPer: NumPer,
          privilegio: privilegio,
        }, success: function (response) {
          if (response != "reachedMax") {
            $(".tableAsistenciaBody").append(response);
            start += limit;
            getAsisData(start, limit, studentID, NumGrupo, CodTema, CodTP, CodCampus, AnoAcad, NumPer, privilegio);
          } else {
            dTable1 = $(".tableAsistencia").DataTable({
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
    
    function cleartable(table) {
      table.clear().draw();
      table.destroy();
    }

    function manageData(key) {
            var horas = $("#horas");
            var rowid=$("#rowid");

            if (isNotEmpty(horas) && isNotEmpty(rowid)) {
                $.ajax({
                    url: 'php/ajax_Asistencias.php',
                    method: 'POST',
                    dataType: 'text',
                    data: {
                        key: 'updateRow',
                        horas: horas.val(),
                        rowID: rowid.val(),
                    }, success: function (response) {
                        if (response != "success") {
                            $("#tableManager").modal('hide');
                            location.reload();
                        }
                        else {
                            cleanModal();
                            $("#tableManager").modal('hide');
                            location.reload();
                        }
                    }
                });
            }
          }
    function cleanModal() {
        var name = $("#horas");
        var cardNumber = $("#fecha");
        var matricula = $("#rowid");
        name.val('');
        matricula.val('');
        cardNumber.val('');}
    function edit(rowID) {
            $.ajax({
                url: 'php/ajax_Asistencias.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    key: 'getRowData',
                    rowID: rowID
                }, success: function (response) {
                    $("#fecha").val(response.fecha);
                    $("#horas").val(response.horasasi);
                    $("#rowid").val(rowID);
                    $("#tableManager").modal('show');
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
    function activeGroup(codigo,idestudiante){
        $.ajax({
                url: 'php/ajax_Asistencias.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    key: 'getActiveGroup',
                    ID: idestudiante,
                    groupCode:codigo
                }, success: function (response) {
                  if (response != "reachedMax") {
                    $(".pillsbody").html('');
                    $(".pillsbody").append(response.body);
                    $("#tituloGrupo").html('');
                    $("#tituloGrupo").append(response.groupCodigo);
                    $(".tableAsisBody").html('');
                    dataindex=1;
                    getAsisData(0, 50,idestudiante,codigo);
                  }
                }
            });
            }
    
    function getGroupData(idestudiante){
      $.ajax({
              url: 'php/ajax_Asistencias.php',
              method: 'POST',
              dataType: 'json',
              data: {
                    key: 'getGroupData',
                    ID: idestudiante
                    }, success: function (response) {
                        if (response != "reachedMax") {
                          $(".pillsbody").append(response.body);
                          $("#tituloGrupo").html('');
                          $("#tituloGrupo").append(response.groupCodigo);
                          getAsisData(0, 50,idestudiante,response.groupid);
                        }
                    }
                });
      }


</script>
</body>

</html>
