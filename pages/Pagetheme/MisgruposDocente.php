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
          <a href="vista-profesor.php">Dashboard</a>
        </li>

        <li class="active">Mis Grupos</li>
      </ol>
    </div>
  </section>

  <section id="main">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
<!---->
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

          <!-- -->
          <div id="tablaconfigurar" class="modal fade">
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
                    <div class="dropdown create col-md-2 Tiempo1" id="dropdownhora">
                      <select id="tiempo" class="btn btn-primary" type="button">
                        <option selected="selected" value="val2">Tiempo</option>
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
          <div id="tablaasistente" class="modal fade">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h2 class="modal-title">Asistente</h2>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-2">
                      <h4>Grupo:</h4>
                    </div>
                    <div class="col-md-6">
                      <input type="text" class="form-control" placeholder="ID..." id="GrupoAsistente" readonly="readonly">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-2" style="text-align: left;">
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
                    <div class="col-md-2">
                      <h4>Profesores:</h4>
                    </div>
                    <div class="dropdown create col-md-2 sistituto" id="dropdownasistente">
                      <select id="profasis" class="btn btn-default" type="button">
                        <option selected="selected" value="val2">Profesores</option>
                        
                      </select>
                    </div>
                  </div>

                </div>
                
                <div class="modal-footer">
                <input type="button" id="sustiBtn" onclick="inSustituto()"  value="Save" class="btn btn-primary">
                </div>
              </div>
            </div>
          </div>
          <div id="tableeditasis" class="modal fade">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h2 class="modal-title">Editar Asistencia</h2>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-4">
                      <h4>Grupo:</h4>
                    </div>
                    <div class="col-md-3">
                      <input type="text" class="form-control" id="grupo1" readonly="readonly">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <h4>Fecha:</h4>
                    </div>
                    <div class="col-md-3">
                      <input type="text" class="form-control" id="fecha1" readonly="readonly">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <h4>Dia de Semana:</h4>
                    </div>
                    <div class="col-md-2">
                      <input type="text" class="form-control" id="semana1" readonly="readonly">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <h4>Asistencia:</h4>
                    </div>
                    <div class="col-md-2">
                      <input type="text" class="form-control" id="asistencia1" readonly="readonly">
                    </div>
                    <div class="dropdown create col-md-2 Tiempo2" id="dropdownasis">
                      <select id="idasis" class="btn btn-default" type="button">
                        <option selected="selected" value="val2">Asistencia</option>
                        <option value="P">P</option>
                        <option value="E">E</option>
                      </select>
                    </div>
                  </div>
                  <input type="hidden" id="editRowID" value="0">
                </div>
                <div class="modal-footer">
                  <input type="hidden" id="rowid">
                  <input type="button" id="botonasis" onclick="guardarasis()" value="Salvar cambios" class="btn btn-primary">
                </div>
              </div>
            </div>
          </div>
          <!-- -->
          <!-- Website Overview -->
          <div class="panel panel-default">
            <div class="panel-heading tabla-color-bg">
              <h3 class="panel-title">Historial de Asistencias</h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-4">
                  <div class="well dash-box" style=" text-align: center;">
                    <h4>
                      <?php echo $ID;?>
                    </h4>
                    <h4>Grupos:</h4>
                    <ul id="pillsbodys" class="nav nav-pills nav-stacked pillsbody">

                    </ul>
                  </div>
                </div>
                <!-- table asistencias-->
                <div class="col-md-8">
                  <div class="well dash-box" style=" text-align: center;">
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-6">
                          <h4 id="tituloGrupo"></h4>
                        </div>
                      </div>
                      <table class="table table-striped table-hover tableAsis">
                        <thead>
                          <th>Matricula</th>
                          <th>Nombre</th>
                          <th>Opciones</th>
                        </thead>
                        <tbody class="tableAsisBody">

                        </tbody>
                      </table>
                      <div class="row">
                        <div align="right" class="asistente"> 
                        <button class="btn btn-primary" onclick="asistente()" id="Asistente" type="button" style="pa">Asistente</button>
                    
                        <button class="btn btn-primary" onclick="edit1()" id="configurar1" type="button" style="pa">Configurar</button>
                        </div>
                      </div>
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
  <script type="text/javascript">
    $(function () {
            var today = new Date();
            $('#datetimepicker1').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $("#datetimepicker1").on("click", function (e) {
            $('#datetimepicker1').data("DateTimePicker").minDate(today);
            });
           
        });
    $(document).ready(function () {
      dataindex = 0;
      dataindex1 = 0;
      var ID = "<?php echo $ID; ?>";
      var NumCedula = "<?php echo $NumCedula; ?>";
      var privilegio = "<?php echo $privilegio; ?>";
      getProfGroupData(NumCedula, privilegio);
      $("#Logout").on('click', function () {
                <?php 

                $_SESSION['privilegio'] = '1';
                ?>
          window.location = 'php/logout.php';

      });
    });

    function getProfGroupData(ProfID, privilegio) {
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
          //getAsisData(0, 50,ProfID,response.NumGrupo,response.CodTema,response.CodTP,response.CodCampus,response.AnoAcad,response.NumPer,privilegio);
          getestgrupo(response.NumGrupo, response.CodTema, response.CodTP, response.CodCampus, response.AnoAcad, response.NumPer);
          $("#configurar1").attr('onclick','edit1(\''+response.CodCampus+'\',\''+response.CodTema+'\',\''+response.CodTP+'\',\''+response.NumGrupo+'\',\''+response.AnoAcad+'\',\''+response.NumPer+'\')');
          $("#Asistente").attr('onclick','asistente(\''+ProfID+'\',\''+response.CodCampus+'\',\''+response.CodTema+'\',\''+response.CodTP+'\',\''+response.NumGrupo+'\',\''+response.AnoAcad+'\',\''+response.NumPer+'\')');
        }
      });
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

    function activeProfGroup(ProfID, NumGrupo, CodTema, CodTP, CodCampus, AnoAcad, NumPer, privilegio) {
      $.ajax({
        url: 'php/ajax_Asistencias.php',
        method: 'POST',
        dataType: 'json',
        data: {
          key: 'activeProfGroup',
          ProfID: ProfID,
          NumGrupo: NumGrupo,
          CodTema: CodTema,
          CodTP: CodTP,
          CodCampus: CodCampus,
          AnoAcad: AnoAcad,
          NumPer: NumPer,
          privilegio: privilegio,

        }, success: function (response) {

          $(".pillsbody").html('');
          $(".pillsbody").append(response.body);
          $("#tituloGrupo").html('');
          $("#tituloGrupo").append(response.groupCodigo);
          //$(".tableAsisBody").html('');
          cleartable(dTable);
          getestgrupo(response.NumGrupo, response.CodTema, response.CodTP, response.CodCampus, response.AnoAcad, response.NumPer);
          $("#configurar1").attr('onclick','edit1(\''+response.CodCampus+'\',\''+response.CodTema+'\',\''+response.CodTP+'\',\''+response.NumGrupo+'\',\''+response.AnoAcad+'\',\''+response.NumPer+'\')');
          $("#Asistente").attr('onclick','asistente(\''+ProfID+'\',\''+response.CodCampus+'\',\''+response.CodTema+'\',\''+response.CodTP+'\',\''+response.NumGrupo+'\',\''+response.AnoAcad+'\',\''+response.NumPer+'\')');
        }
      });
    }
    function getestgrupo(NumGrupo, CodTema, CodTP, CodCampus, AnoAcad, NumPer) {
      $.ajax({
        url: 'php/ajax_misgruposdocentes.php',
        method: 'POST',
        dataType: 'text',
        data: {
          key: 'getestgrupo',
          NumGrupo: NumGrupo,
          CodTema: CodTema,
          CodTP: CodTP,
          CodCampus: CodCampus,
          AnoAcad: AnoAcad,
          NumPer: NumPer,

        }, success: function (response) {
          $(".tableAsisBody").append(response);
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
      });


    }
    function inSustituto(ProfID,CodCampus,CodTema,CodTP,Numgrupo,AnoAcad,Numper){
            var grupo=$("#GrupoAsistente").val();
            var fecha=$("#fecha").val();
            var eID = document.getElementById("profasis");
            var dayVal = eID.options[eID.selectedIndex].value;
            var daytxt = eID.options[eID.selectedIndex].text;
             //alert("Selected Item  " +  daytxt + ", Value " + dayVal);
             
             if(dayVal=='val2'){
                 alert("Seleccione un Profesor");
             }else{
             $.ajax({
                 url: 'php/ajax_misgruposdocentes.php',
                 method: 'POST',
                 dataType: 'json',
                 data: {
                     key:'inSustituto',
                     ProfID:ProfID,
                     CodCampus:CodCampus,
                     CodTema:CodTema,
                     CodTP:CodTP,
                     Numgrupo:Numgrupo,
                     AnoAcad:AnoAcad,
                     Numper:Numper,
                     dayVal:dayVal,
                     fecha:fecha,
                 }, success: function (response) {
                      console.log(response);  
                     $("#tablaasistente").modal('hide');
                     //$("div.Tiempo1 select").val("val2")
                 }
             });}
             
    }
    function manageData(key) {
      var horas = $("#horas");
      var rowid = $("#rowid");

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
    function cleartable(table) {
      table.clear().draw();
      table.destroy();
    }
    function cleanModal() {
      var name = $("#horas");
      var cardNumber = $("#fecha");
      var matricula = $("#rowid");
      name.val('');
      matricula.val('');
      cardNumber.val('');
    }
    function isNotEmpty(caller) {
      if (caller.val() == '') {
        caller.css('border', '1px solid red');
        return false;
      } else caller.css('border', '');
      return true;
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

    function findDay(CodCampus,CodTema,CodTP,Numgrupo,AnoAcad,Numper){
             
             var eID = document.getElementById("tiempo");
             var dayVal = eID.options[eID.selectedIndex].value;
             var daytxt = eID.options[eID.selectedIndex].text;
             //alert("Selected Item  " +  daytxt + ", Value " + dayVal);
             
             if(dayVal=='val2'){
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
                     $("#tablaconfigurar").modal('hide');
                     $("div.Tiempo1 select").val("val2")
                 }
             });}
             }

    function edit(studentID,Fecha,Horaini,NumGrupo,CodTema,CodTP,CodCampus,AnoAcad,NumPer,Diasemana,Presencia) {
        $("#grupo1").val(""+CodTema+"-"+CodTP+"-"+NumGrupo +"");
        $("#fecha1").val(Fecha);
        $("#semana1").val(Diasemana);
        $("#asistencia1").val(Presencia);
        $("#tableeditasis").modal('show');
        $("#botonasis").attr('onclick','guardarasis(\''+studentID+'\',\''+Fecha+'\',\''+Horaini+'\',\''+NumGrupo+'\',\''+CodTema+'\',\''+CodTP+'\',\''+CodCampus+'\',\''+AnoAcad+'\',\''+NumPer+'\',\''+Diasemana+'\',\''+Presencia+'\')');        
        }
 
    function edit1(CodCampus,CodTema,CodTP,Numgrupo,AnoAcad,Numper) {
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
                $("#tablaconfigurar").modal('show'); 
            }
        });
    }
    
    function asistente(ProfID,CodCampus,CodTema,CodTP,Numgrupo,AnoAcad,Numper) {
        $.ajax({
            url: 'php/ajax_misgruposdocentes.php',
            method: 'POST',
            dataType: 'json',
            data: {
                key: 'asistente',
                ProfID:ProfID,
                CodCampus:CodCampus,
                CodTema:CodTema,
                CodTP:CodTP,
                Numgrupo:Numgrupo,
                AnoAcad:AnoAcad,
                Numper:Numper,
            }, success: function (response) {
              $("#GrupoAsistente").val(response.Grupo);
              $("#profasis").html("");
              $("#profasis").html('<option selected="selected" value="val2">Profesores</option>');
              $("#profasis").append(response.trabajadores);
              $("#sustiBtn").attr('onclick','inSustituto(\''+ProfID+'\',\''+CodCampus+'\',\''+CodTema+'\',\''+CodTP+'\',\''+Numgrupo+'\',\''+AnoAcad+'\',\''+Numper+'\')');
              $("#tablaasistente").modal('show');
               
            }
        });
    }
    function guardarasis(studentID,Fecha,Horaini,NumGrupo,CodTema,CodTP,CodCampus,AnoAcad,NumPer,Diasemana,Presencia){
             
             var eID = document.getElementById("idasis");
             var dayVal = eID.options[eID.selectedIndex].value;
             var daytxt = eID.options[eID.selectedIndex].text;
             //alert("Selected Item  " +  daytxt + ", Value " + dayVal);
             
             if(dayVal=='val2'){
                 alert("Seleccione un Asistencia");
             }else{
             $.ajax({
                 url: 'php/ajax_Asistencias.php',
                 method: 'POST',
                 dataType: 'json',
                 data: {
                     key:'guardarasis',
                     dayVal:dayVal,
                     studentID:studentID,
                     Fecha:Fecha,
                     Horaini:Horaini,
                     NumGrupo:NumGrupo,
                     CodTema:CodTema,
                     CodTP:CodTP,
                     CodCampus:CodCampus,
                     AnoAcad:AnoAcad,
                     NumPer:NumPer,
                     Diasemana:Diasemana,
                     Presencia:Presencia,
                 }, success: function (response) {  
                     $("#tableeditasis").modal('hide');
                     $("div.Tiempo2 select").val("val2")
                     location.reload();
                 }
             });}
             }



  </script>
</body>

</html>