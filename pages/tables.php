<?php
session_start();

if(isset($_SESSION['user'])) {?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Bootstrap Admin Theme</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="../vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">AC</a>
            </div>
           

            <div class="navbar-default sidebar" role="navigation">

               
                       
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">

            <!--Add new and Edit -->
            <div class="container" style="margin-top: 20px;">
                <div id="tableManager" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content"> 

                            
                            <div class="modal-header">
                                <h2 class="modal-title">Add New</h2>
                            </div>
                            <div class="modal-body">
                              <span>ID:</span><input type="text" class="form-control" placeholder="ID..." id="ID"><br>
                              <span>Name:</span>  <input type="text" class="form-control" placeholder="Name..." id="Name"><br>
                              <span>Card Number:</span> <input type="text" class="form-control" placeholder="Card Number.." id="CardNumber" >
                                <input type="hidden" id="editRowID" value="0">
                            </div>
                            <div class="modal-footer">
                                <input type="button"id="manageBtn" onclick="manageData('addNew')" value="Save" class="btn btn-primary">
                            </div>
                        </div>
                    </div>
                </div>
              <!--/Add new and Edit -->

                <!--Table Mysql -->
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <h2>STUDENTS</h2>
                            <input style="float: right " type="button" class="btn btn-primary" id="addNew" value="Add New">
                        <br><br>
                        <table class="table table-hover table-bordered">
                            <thead>
                                <td>ID</td>
                                <td>Name</td>
                                <td>Card Number</td>
                                <td>Options</td>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--/Table Mysql -->
            
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!--Script addNew -->
    <script type="text/javascript">
        $(document).ready(function(){
            $("#addNew").on('click',function(){
                cleanModal();
                $(".modal-title").html('Add New');
                $("#tableManager").modal('show');
                
            });
            getExistingData(0,50);
        });

        function deleteRow(rowID){
            if(confirm('Are you sure?')){
                $.ajax({
                   url:'ajax.php',
                   method: 'POST',
                   dataType: 'text',
                   data:{
                       key: 'deleteRow',
                       rowID: rowID
                   }, success: function(response){
                       $("#Name_"+rowID).parent().remove();
                       alert(response);
                   }
                });
            }
        }
        function edit(rowID){
            $.ajax({
                   url:'ajax.php',
                   method: 'POST',
                   dataType: 'json',
                   data:{
                       key: 'getRowData',
                       rowID: rowID
                   }, success: function(response){
                       $("#editRowID").val(rowID);
                       $("#ID").val(response.ID);
                       $("#Name").val(response.Name);
                       $("#CardNumber").val(response.CardNumber);
                       $(".modal-title").html('Edit');
                       $("#tableManager").modal('show');
                       $("#manageBtn").attr('value','Save Changes').attr('onclick',"manageData('updateRow')");
                   }
               });
        }

        function getExistingData(start,limit){
            $.ajax({
                url:'ajax.php',
                method: 'POST',
                dataType: 'text',
                data:{
                    key:'getExistingData',
                    start: start,
                    limit: limit
                }, success: function(response){
                       if(response != "reachedMax"){
                           $('tbody').append(response);
                           start+= limit;
                           getExistingData(start,limit);
                       } else{
                           
                           $(".table").DataTable();
                       }

                   }
            });
        }
        function manageData(key,edit){
            var name=$("#Name");
            var cardNumber=$("#CardNumber");
            var matricula=$("#ID");
            var editRowID=$("#editRowID");

            if(isNotEmpty(matricula) && isNotEmpty(name) && isNotEmpty(cardNumber)){
               $.ajax({
                   url:'ajax.php',
                   method: 'POST',
                   dataType: 'text',
                   data:{
                       key: key,
                       name: name.val(),
                       matricula: matricula.val(),
                       cardNumber: cardNumber.val(),
                       rowID: editRowID.val()
                   }, success: function(response){
                       if(response!="success"){
                         alert(response);
                         $("#tableManager").modal('hide');
                         location.reload();
                         }
                       else{
                          
                           $("#Name_"+editRowID.val()).html(name.val());
                           $("#CardNumber_"+editRowID.val()).html(cardNumber.val());
                           cleanModal();
                           $("#tableManager").modal('hide');
                           $("#manageBtn").attr('value','Add').attr('onclick',"manageData('addNew')");
                       }
                   }
               });
            }
            
            
        }
        function cleanModal(){
            var name=$("#Name");
            var cardNumber=$("#CardNumber");
            var matricula=$("#ID");
            name.val('');
            matricula.val('');
            cardNumber.val('');
           
        }
        function isNotEmpty(caller){
            if(caller.val() == ''){
                caller.css('border','1px solid red');
                return false;
            }else caller.css('border','');

            return true;
        }
    </script>

</body>

</html>
<?php
}else{
	echo '<script> window.location="index.php"; </script>';
}
?>
