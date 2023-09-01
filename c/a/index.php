<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "DTR Dashboard";

    $notify = @$_GET['note'];

    if ($notify == "pro_update") {
        $note = "Profile Updated";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "mismatch") {
        $note = "Password Mismatch!";
        $notec = "danger";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "error") {
        $note = "Something Error!";
        $notec = "danger";
        $notes = "";
        $noteid = "activate-alert";
    }else{
        $note = "";
        $notec = "";
        $notes = "display: none;";
        $noteid = "";
    }

    //get accounts
    $logs=$link->query("SELECT * From `gy_logs` Order By `gy_log_date` DESC");
    $countlogs=$logs->num_rows;
?>

<!DOCTYPE html>
<html lang="en">

<?php  
    include 'head.php';
?>

<body class="">
    <div class="page-wrapper">
        
        <?php include 'header-m.php'; ?>

        <?php include 'sidebar.php'; ?>

        <!-- PAGE CONTAINER-->
        <div class="page-container">

            <!-- MAIN CONTENT-->
            <div class="main-content" style="padding: 20px;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="overview-wrap">
                            <h2 class="title-1 m-b-25">Time Logs <i class="fa fa-clock"></i></h2>
                                <div style="<?php echo $notes; ?>" id="<?php echo $noteid; ?>" class="sufee-alert alert with-close alert-<?php echo $notec; ?> alert-dismissible fade show">
                                    <span class="badge badge-pill badge-<?php echo $notec; ?>">Alert</span>
                                    <?php echo $note; ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <strong class="card-title mb-3 text-center"><span style="color: red;"><i class="fa fa-circle"></i> LIVE</span></strong>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive m-b-40">
                                        <table class="table table-bordered" style="font-family: 'Courier New'; font-size: 15px; color: #000;">
                                            <thead>
                                                <tr class="mybg">
                                                    <th style="padding: 5px;">Status</th>
                                                    <th style="padding: 5px;">Employee</th>
                                                    <th style="padding: 5px;">Account</th>
                                                </tr>
                                            </thead>
                                            <tbody id="load_data">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php include 'footer.php'; ?>
                </div>
            </div>
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
        </div>

    </div>

    <?php include 'scripts.php'; ?>

    <script type="text/javascript">
        $("#activate-alert").fadeTo(5000, 500).slideUp(500, function(){
            $("#activate-alert").slideUp(500);
        });

        $(document).ready(function(){    
            loadstation();
        });

        function loadstation(){
            $("#load_data").load("log_results");
            setTimeout(loadstation, 3000);
        }
    </script>

</body>

</html>
<!-- end document-->
