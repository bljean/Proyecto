<?php
$ID= $_POST['ID'];
$nombre=$_POST['nombre'];
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
        <a class="navbar-brand" href="dashboard.html">Control de acceso</a>
      </div>
      <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
          <li>
            <a href="swipe.html">Eventos</a>
          </li>
          <li>
              <div class="dropdown create">
                  <button class="btn btn-danger" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Gestion de tarjetas
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <li><a href="students.html">Estudiantes</a></li>
                    <li><a href="professors.html">Profesores</a></li>
                    <li><a href="workers.html">Empleados</a></li>
                  </ul>
                </div>
          </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li>
            <button type="button" value="Log out" id="Logout" class="btn btn-danger btn-block">Logout</button>
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
          <a href="dashboard.html">Dashboard</a>
        </li>
        <li class="active">Asistencias</li>
      </ol>
    </div>
  </section>

  <section id="main">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <!-- Website Overview -->
          <div class="panel panel-default">
            <div class="panel-heading main-color-bg">
              <h3 class="panel-title">Historial de Asistencias</h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-4">
                  <div class="well dash-box"style=" text-align: center;">
                    <h4><?php echo $ID?> <?php echo $nombre?></h4>
                    <h4>Grupos:</h4>
                    <ul class="nav nav-pills nav-stacked pillsbody">
                    
                    </ul>
                  </div>
                </div>
                <!-- table asistencias-->
                <div class="col-md-8">
                  <div class="well dash-box" style=" text-align: center;" >
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-6">
                          <h4>ST-ADM-407-T-002 Emprendimiento</h4>
                        </div>
                      </div>
                      <table class="table table-striped table-hover tableAsis">
                        <thead>
                          <th>Fechas</th>
                          <th>Horas presente</th>
                          <th>Opciones</th>
                        </thead>
                        <tbody class="tableAsisBody">

                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!--/table asistencias-->
                   <!-- estadisticas-->
                  <div class="well dash-box" style=" text-align: left;">
                    <div class="row">
                      <div class="col-md-2">
                        <h4>Horas presente:</h4>
                      </div>
                      <div class="col-md-2">
                        <h4>6/70</h4>
                      </div>
                      <div class="col-md-2">
                          <h4>Horas faltadas:</h4>
                        </div>
                        <div class="col-md-2">
                          <h4>3/16</h4>
                        </div>
                        <div class="col-md-2">
                            <h4>Excusas:</h4>
                          </div>
                          <div class="col-md-2">
                            <h4>1/3</h4>
                          </div>
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
      dataindex=0;
      var ID = "<?php echo $ID; ?>";
      getGroupData(ID);
      $("#Logout").on('click', function () {
            window.location= 'php/logout.php'
      });
    });
    function activeGroup(codigo,idestudiante){
        $.ajax({
                url: 'php/ajax_Asistencias.php',
                method: 'POST',
                dataType: 'text',
                data: {
                    key: 'getActiveGroup',
                    ID: idestudiante,
                    groupCode:codigo
                }, success: function (response) {
                $(".pillsbody").html('');
                $(".pillsbody").append(response);
                $(".tableAsisBody").html('');
                dataindex=1;
                getAsisData(0, 50,idestudiante, codigo);
                }
            });
        
    }
    function getAsisData(start, limit,ID,grupID) {
            $.ajax({
                url: 'php/ajax_Asistencias.php',
                method: 'POST',
                dataType: 'text',
                data: {
                    key: 'getExistingData',
                    start: start,
                    limit: limit,
                    ID: ID,
                    grupID: grupID
                }, success: function (response) {
                    if (response != "reachedMax") {
                        $("tbody").append(response);
                        start += limit;
                        getAsisData(start, limit,ID,grupID);
                    } else {
                      if(dataindex != 0){
                            dTable.destroy();
                        }
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
function getGroupData(idestudiante){
  $.ajax({
                url: 'php/ajax_Asistencias.php',
                method: 'POST',
                dataType: 'text',
                data: {
                    key: 'getGroupData',
                    ID: idestudiante
                }, success: function (response) {
                 $(".pillsbody").append(response);

                }
            });

}

</script>
</body>

</html>
