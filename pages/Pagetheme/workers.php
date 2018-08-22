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
                        <span class="glyphicon glyphicon-user" aria-hidden="true"></span> Empleados
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
                <li class="active">Empleados</li>
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
                            <h3 class="panel-title">Tabla de Empleados</h3>
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
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <h4>Cedula:</h4>
                                                                    </div>
                                                                    <div class="col-md-10">
                                                                        <input type="text" class="form-control" placeholder="ID..." id="ID" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <h4>Nombre:</h4>
                                                                    </div>
                                                                    <div class="col-md-10">
                                                                        <input type="text" class="form-control" placeholder="Name..." id="Name" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <h4>Apellido:</h4>
                                                                    </div>
                                                                    <div class="col-md-10">
                                                                        <input type="text" class="form-control" placeholder="Apellido.." id="Apellido" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <h4>Tarjeta:</h4>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="text" class="form-control" placeholder="Card Number.." id="CardNumber" readonly="readonly">
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <input type="button" id="autoRecordCardBtn" onclick="autoRecordCard()" value="Registro Automático" class="btn btn-primary">
                                                                    </div>
                                                                </div>
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
                                                    <div class="col-md-12" >
                                                        <table class="table table-hover table-bordered" style="background-color:white " >
                                                            <thead>
                                                                <td>Cedula</td>
                                                                <td>Nombre</td>
                                                                <td>Tarjeta</td>
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
                window.location= 'php/logout.php'
            });
            $("#addNew").on('click', function () {
                cleanModal();
                $(".modal-title").html('Add New');
                $("#tableManager").modal('show');

            });
            getExistingData(0, 50);
        });

        function deleteRow(rowID) {
            if (confirm('Are you sure?')) {
                $.ajax({
                    url: 'php/ajax_workers.php',
                    method: 'POST',
                    dataType: 'text',
                    data: {
                        key: 'deleteRow',
                        rowID: rowID
                    }, success: function (response) {
                        $("#Name_" + rowID).parent().remove();
                        alert(response);
                    }
                });
            }
        }
        function edit(rowID) {
            $.ajax({
                url: 'php/ajax_workers.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    key: 'getRowData',
                    rowID: rowID
                }, success: function (response) {
                    $("#editRowID").val(rowID);
                    $("#ID").val(response.ID);
                    $("#Name").val(response.Name);
                    $("#CardNumber").val(response.CardNumber);
                    $("#Apellido").val(response.Apellido);
                    $(".modal-title").html('Editar');
                    $("#tableManager").modal('show');
                    $("#manageBtn").attr('value', 'Salvar cambios').attr('onclick', "manageData('updateRow')");
                }
            });
        }

        function getExistingData(start, limit) {
            $.ajax({
                url: 'php/ajax_workers.php',
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
        function manageData(key, edit) {
            var name = $("#Name");
            var cardNumber = $("#CardNumber");
            var matricula = $("#ID");
            var editRowID = $("#editRowID");

            if (isNotEmpty(matricula) && isNotEmpty(name) && isNotEmpty(cardNumber)) {
                $.ajax({
                    url: 'php/ajax_workers.php',
                    method: 'POST',
                    dataType: 'text',
                    data: {
                        key: key,
                        name: name.val(),
                        matricula: matricula.val(),
                        cardNumber: cardNumber.val(),
                        rowID: editRowID.val()
                    }, success: function (response) {
                        if (response != "success") {
                            alert(response);
                            $("#tableManager").modal('hide');
                            location.reload();
                        }
                        else {

                            $("#Name_" + editRowID.val()).html(name.val());
                            $("#CardNumber_" + editRowID.val()).html(cardNumber.val());
                            cleanModal();
                            $("#tableManager").modal('hide');
                            $("#manageBtn").attr('value', 'Add').attr('onclick', "manageData('addNew')");
                        }
                    }
                });
            }
        }
        function autoRecordCard(){
            $.ajax({
                url: 'php/cardRecord.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    key: 'getCardNumber',
                }, success: function (response) {
                    if(response.status=="1"){
                        $("#CardNumber").val(response.body);
                    }else{alert("Tarjeta en uso")}
                    
                      
                }
            });

        }
        function cleanModal() {
            var name = $("#Name");
            var cardNumber = $("#CardNumber");
            var matricula = $("#ID");
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
    </script>
</body>

</html>