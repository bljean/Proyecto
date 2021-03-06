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
            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Asistencias
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
        
        <li class="active">Asistencias</li>
      </ol>
    </div>
  </section>

  <section id="main">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
        <div id="tableManager" class="modal fade">
           <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h2 class="modal-title">Editar</h2>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-2">
                      <h4>Fecha:</h4>
                    </div>
                    <div class="col-md-10">
                      <input type="text" class="form-control"  id="fecha" readonly="readonly">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-2">
                      <h4>Horas:</h4>
                    </div>
                    <div class="col-md-10">
                      <input type="text" class="form-control"  id="horas">
                    </div>
                  </div>
                  <input type="hidden" id="editRowID" value="0">
                  </div>
                  <div class="modal-footer">
                    <input type="hidden" id="rowid">
                    <input type="button" id="manageBtn" onclick="manageData()" value="Salvar cambios" class="btn btn-primary">
                  </div>
                </div>
            </div>
          </div>
          <!-- Website Overview -->
          <div class="panel panel-default">
            <div class="panel-heading tabla-color-bg">
              <h3 class="panel-title">Historial de Asistencias</h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-4">
                  <div class="well dash-box"style=" text-align: center;">
                    <h4> <?php echo $ID;?></h4>
                    <h4>Grupos:</h4>
                    <ul id="pillsbodys" class="nav nav-pills nav-stacked pillsbody">
                    
                    </ul>
                  </div>
                </div>
                <!-- table asistencias-->
                <div class="col-md-8">
                  <div class="well dash-box" style=" text-align: center;" >
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-6">
                          <h4 id="tituloGrupo"></h4>
                        </div>
                      </div>
                      <table class="table table-striped table-hover tableAsis">
                        <thead>
                          <th>Fechas</th>
                          <th>Dia de Semana</th>
                          <th>Hora Inicio</th>
                          <th>Hora Termino</th>
                          <th>Hora Llegada</th>
                          <th>Asistencia</th>
                          <th>Opciones</th>
                        </thead>
                        <tbody class="tableAsisBody">

                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!--/table asistencias-->
                   <!-- estadisticas-->
                
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
      dataindex=0;
      var ID = "<?php echo $ID; ?>";
      var NumCedula = "<?php echo $NumCedula; ?>";
      var privilegio = "<?php echo $privilegio; ?>";
      notificacion(NumCedula);
      getProfGroupData(NumCedula,privilegio);
      $("#Logout").on('click', function () {
                <?php 

                $_SESSION['privilegio'] ='1';
                ?>
                window.location = 'php/logout.php';
                
            });
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
    function getEstGroupData(studentID,privilegio){
      $.ajax({
              url: 'php/ajax_Asistencias.php',
              method: 'POST',
              dataType: 'json',
              data: {
                    key: 'getEstGroupData',
                    studentID: studentID,
                    privilegio:privilegio,
                    }, success: function (response) {
                          $(".pillsbody").append(response.body);
                          $("#tituloGrupo").html('');
                          $("#tituloGrupo").append(response.groupCodigo);
                          getAsisData(0, 50,studentID,response.NumGrupo,response.CodTema,response.CodTP,response.CodCampus,response.AnoAcad,response.NumPer, privilegio);
                    }
                });
      }
    function getProfGroupData(ProfID,privilegio){
      $.ajax({
              url: 'php/ajax_Asistencias.php',
              method: 'POST',
              dataType: 'json',
              data: {
                    key: 'getProfGroupData',
                    ProfID: ProfID,
                    privilegio: privilegio,
                    }, success: function (response) {
                          $(".pillsbody").append(response.body);
                          $("#tituloGrupo").html('');
                          $("#tituloGrupo").append(response.groupCodigo);
                         getAsisData(0, 50,ProfID,response.NumGrupo,response.CodTema,response.CodTP,response.CodCampus,response.AnoAcad,response.NumPer,2);
                    }
                });
      }
    function getAsisData(start,limit,studentID,NumGrupo,CodTema,CodTP,CodCampus,AnoAcad,NumPer,privilegio) {
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
                        $(".tableAsisBody").append(response);
                        start += limit;
                        getAsisData(start,limit,studentID,NumGrupo,CodTema,CodTP,CodCampus,AnoAcad,NumPer,privilegio);
                    } else {
                          dTable = $(".tableAsis").DataTable({
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
    function activeGroup(studentID,NumGrupo,CodTema,CodTP,CodCampus,AnoAcad,NumPer,privilegio){
        $.ajax({
                url: 'php/ajax_Asistencias.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    key: 'getActiveGroup',
                    studentID: studentID,
                    NumGrupo: NumGrupo,
                    CodTema:CodTema,
                    CodTP: CodTP,
                    CodCampus: CodCampus,
                    AnoAcad: AnoAcad,
                    NumPer: NumPer,
                    privilegio:privilegio,
                   
                }, success: function (response) {
                  
                  $(".pillsbody").html('');
                  $(".pillsbody").append(response.body);
                  $("#tituloGrupo").html('');
                  $("#tituloGrupo").append(response.groupCodigo);
                  //$(".tableAsisBody").html('');
                  cleartable(dTable);
                  dataindex=1;
                  getAsisData(0, 50,studentID,response.NumGrupo,response.CodTema,response.CodTP,response.CodCampus,response.AnoAcad,response.NumPer,privilegio);
                  
                }
            });
        }
    function activeProfGroup(ProfID,NumGrupo,CodTema,CodTP,CodCampus,AnoAcad,NumPer,privilegio){
        $.ajax({
                url: 'php/ajax_Asistencias.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    key: 'activeProfGroup',
                    ProfID: ProfID,
                    NumGrupo: NumGrupo,
                    CodTema:CodTema,
                    CodTP: CodTP,
                    CodCampus: CodCampus,
                    AnoAcad: AnoAcad,
                    NumPer: NumPer,
                    privilegio:privilegio,
                   
                }, success: function (response) {
                  
                  $(".pillsbody").html('');
                  $(".pillsbody").append(response.body);
                  $("#tituloGrupo").html('');
                  $("#tituloGrupo").append(response.groupCodigo);
                  //$(".tableAsisBody").html('');
                  cleartable(dTable);
                  dataindex=1;
                  getAsisData(0, 50,ProfID,response.NumGrupo,response.CodTema,response.CodTP,response.CodCampus,response.AnoAcad,response.NumPer,2);
                  
                }
            });
        }      
    function cleartable(table){
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
    
   
   


</script>
</body>

</html>
