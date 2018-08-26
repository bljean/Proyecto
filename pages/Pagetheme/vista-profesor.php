<?php
session_start();
if(isset($_SESSION['loggedIN'])){
  
}else{
  header('Location: logdocentes.php');
  exit();
}
$NumCedula=$_SESSION['NumCedula'];

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
    <!-- Bootstrap core CSS -->
    <script src="http://cdn.ckeditor.com/4.6.1/standard/ckeditor.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"><!--iconos google-->
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
                            <span class="glyphicon glyphicon-book" aria-hidden="true"></span> Dashboard
                            <small></small>
                        </h1>
                    </div>
                </div>
            </div>
        </header>
        <section id="breadcrumb">
            <div class="container-fluid">
                <ol class="breadcrumb">
                    <li class="active"> Dashboard</a>
                    </li>
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
                                <h3 class="panel-title">Buenas tardes</h3>
                            </div>
                            <div class="panel-body panel-body-color">
                                <div class="row">
                                    <div class="col-md-12">
        
                                        <div class="panel-body">
        
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="panel panel-noticias">
                                                        <canvas id="myChart"></canvas>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="panel panel-noticias">
                                                        <canvas id="myChart1"></canvas>
                                                    </div>
                                                </div>
                                            </div>
        
                                        </div>
        
        
                                    </div>
                                </div>
                            </div>
                        </div>
        
                        <!-- Latest Users -->
                    </div>
                </div>
                <div class="row ">
                    <div class="col-md-12">
                        <div class="panel  panel-noticias ">
                            <div class="panel-heading tabla-color-bg">
        
                                <h3 class="panel-title " style="text-align: start">
                                    <i class="glyphicon glyphicon-bell" style="align-items:flex-start"></i> NotiFicaciones</h3>
                            </div>
        
                            <div class="panel-body"> test</div>
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
    <script type="text/javascript">
        $(document).ready(function () {
            var ID= "<?php echo $NumCedula;  ?>";
            materias=[];
            semanal=[];
            getprofGroupData(ID);
            grafico();
            getESTAsisProfGroupData(ID);
            $("#Logout").on('click', function () {
                <?php 
                $_SESSION['privilegio'] ='1';
                ?>
                window.location = 'php/logout.php';
            });
        });

    
    function getprofGroupData(ID){
            $.ajax({
              url: 'php/ajax_vista-profesor.php',
              method: 'POST',
              dataType: 'json',
              data: {
                    key: 'getprofGroupData',
                    ID: ID,
                    }, success: function (response) {
                       //    materias=response.materias1;
                        //semanal=response.semanal;
                        grafico();
                        //grafico1();
                    }
                });
        }
        
        
     function getESTAsisProfGroupData(ID){
            $.ajax({
              url: 'php/ajax_vista-profesor.php',
              method: 'POST',
              dataType: 'json',
              data: {
                    key: 'getESTAsisProfGroupData',
                    ID: ID,
                    }, success: function (response) {
                        alert(response.ausenciaest);
                        alert(response.canti);
                       alert(response.ausenciamatricula);
                        //alert(response.fatality);
                    }
                });
            }
        
     function grafico(){
        //estudiante
        Chart.defaults.global.defaultFontFamily = 'Lato';
        Chart.defaults.global.defaultFontSize = 18;
        Chart.defaults.global.defaultFontColor = '#777';


        let myChart1 = document.getElementById('myChart1').getContext('2d');
        let massPopChart1 = new Chart(myChart1, {
            type: 'bar', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
            data: {
                labels: [1,2,3,4,5],
                datasets: [{
                    label: 'hola',
                    data: [1,2,3,4,5],
                    //backgroundColor:'green',
                    backgroundColor: [
                        'rgba(195, 155, 211, 1)',
                        'rgba(195, 155, 211, 1)',
                        'rgba(195, 155, 211, 1)',
                        'rgba(195, 155, 211, 1)',
                        'rgba(195, 155, 211, 1)',
                        'rgba(195, 155, 211, 1)',
                        'rgba(195, 155, 211, 1)'

                    ],
      
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Ausencia De Los Estudiantes',
                    fontSize: 30
                }, scales: {
                        yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                                }]
                            },
                legend: {
                    display: false,
                    position: 'right',
                    labels: {
                        fontColor: '#000'
                    }
                },
                layout: {
                    padding: {
                        left: 50,
                        right: 0,
                        bottom: 60,
                        top: 0
                    }
                },
                tooltips: {
                    enabled: true
                }
            }
        });

    }

    function grafico1(){
        //profesor
        Chart.defaults.global.defaultFontFamily = 'Lato';
        Chart.defaults.global.defaultFontSize = 18;
        Chart.defaults.global.defaultFontColor = '#777';

        let myChart = document.getElementById('myChart').getContext('2d');

        let massPopChart = new Chart(myChart, {
            type: 'bar', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
            data: {
                labels: materias,
                datasets: [{
                    label: 'Ausencia',
                    data: semanal,
                    //backgroundColor:'green',
                    backgroundColor: [
                        'rgba(88, 214, 141, 1)',
                        'rgba(88, 214, 141, 1)',
                        'rgba(88, 214, 141, 1)',
                        'rgba(88, 214, 141, 1)',
                        'rgba(88, 214, 141, 1)',
                        'rgba(88, 214, 141, 1)',
                        'rgba(88, 214, 141, 1)'
                    ],
                  
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Ausencia De Los Docentes',
                    fontSize: 30
                }, scales: {
                        yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                                }]
                            },
                legend: {
                    display: false,


                    labels: {
                        fontColor: '#000'
                    }
                },
                layout: {
                    padding: {
                        left: 50,
                        right: 0,
                        bottom: 80,
                        top: 0
                    }
                },
                tooltips: {
                    enabled: true
                }
            }
        });
    }     



     


    </script>

</body>

</html>