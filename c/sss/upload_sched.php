<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "Upload Schedules";

    $notify = @$_GET['note'];

    if ($notify == "upload_success") {
        $note = "Schedule Upload Successful";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "file_not_allowed") {
        $note = "Only CSV file is allowed to upload";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "missingdata") {
        $note = "Invalid Data Uploaded, Incomplete Data or Schedule not having a 12hours gap!";
        $notec = "warning";
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

    //get employee info
    $getemp=$link->query("SELECT `gy_emp_id`,`gy_emp_code`,`gy_emp_email`,`gy_emp_fullname` From `gy_employee` Where `gy_emp_code`='$user_code'");
    $emprow=$getemp->fetch_array();

$link->close();
?>

<!DOCTYPE html>
<html lang="en">

<?php  
    include 'head.php';
?>

<style type="text/css">
    .files input {
        outline: 2px dashed #92b0b3;
        outline-offset: -10px;
        -webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
        transition: outline-offset .15s ease-in-out, background-color .15s linear;
        padding: 120px 0px 85px 35%;
        text-align: center !important;
        margin: 0;
        width: 100% !important;
    }
    .files input:focus{     outline: 2px dashed #92b0b3;  outline-offset: -10px;
        -webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
        transition: outline-offset .15s ease-in-out, background-color .15s linear; border:1px solid #92b0b3;
     }
    .files{ position:relative}
    .files:after {  pointer-events: none;
        position: absolute;
        top: 60px;
        left: 0;
        width: 50px;
        right: 0;
        height: 56px;
        content: "";
        background-image: url(../../images/upload.png);
        display: block;
        margin: 0 auto;
        background-size: 100%;
        background-repeat: no-repeat;
    }
    .color input{ background-color:#f1f1f1;}
    .files:before {
        position: absolute;
        bottom: 10px;
        left: 0;  pointer-events: none;
        width: 100%;
        right: 0;
        height: 57px;
        content: " or drag it here. ";
        display: block;
        margin: 0 auto;
        color: #2ea591;
        font-weight: 600;
        text-transform: capitalize;
        text-align: center;
    }
</style>

<body class="" id="mybody">
    <div class="page-wrapper">
        
        <?php include 'header-m.php'; ?>

        <?php include 'sidebar.php'; ?>

        <!-- PAGE CONTAINER-->
        <div class="page-container">

            <!-- MAIN CONTENT-->
            <div class="main-content" style="padding: 20px;">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="title-1 m-b-25"><?php echo $title; ?> <i class="fa-solid fa-calendar-plus"></i></h2>
                            <div style="<?php echo $notes; ?>" id="<?php echo $noteid; ?>" class="sufee-alert alert with-close alert-<?php echo $notec; ?> alert-dismissible fade show">
                                <span class="badge badge-pill badge-<?php echo $notec; ?>">Alert</span>
                                <?php echo $note; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>

					<ul class="nav nav-tabs ">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="upload_sched"><strong>Upload Here</strong></a>
                        </li>
                        <li class="nav-item">
							<a class="nav-link" href="uploaded_sched">Edit Here</a>
                        </li>
                    </ul>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <strong class="card-title mb-3"><center>UPLOAD <span style="color: green;">.csv</span> file</center></strong>
                                </div>
                                <div class="card-body">
                                    <div class="login-logo">
                                        <form method="post" enctype="multipart/form-data" action="upload_ini" onsubmit="validateForm(this);">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group files">
                                                      <input type="file" name="file" class="form-control" accept=".csv" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">From</div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="date" class="form-control" name="from" id="from" title="from" onchange="daterange()" min="<?= date('Y-m-d', strtotime('+7 days')); ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">To</div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="date" class="form-control" name="to" id="to" title="to" onchange="daterange()" min="<?= date('Y-m-d', strtotime('+7 days')); ?>" required>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <button type="submit" name="submit" class="btn btn-success btn-block">PRESS HERE TO UPLOAD</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <form ethod="post" enctype="multipart/form-data" action="schedfrmt" target="_blank" onsubmit="return validateFormxprt(this);">
                                            <div class="row">
                                                <div class="col-md-8"></div>
                                                <div class="col-md-4">
                                                    <button type="submit" class="btn btn-primary btn-block">Download Format</button>
                                                </div>
                                            </div>
                                        </form>
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
        function daterange(){
            var from = _getID("from").value;
            var to = _getID("to").value;

            if (from) {
                _getID("to").min = from;
            }

            if (to) {
                _getID("from").max = to;
            }
        }
    </script>

    <script type="text/javascript">
        $("#activate-alert").fadeTo(5000, 500).slideUp(500, function(){
            $("#activate-alert").slideUp(500);
        });
    </script>

    <script type="text/javascript">  
        function validateForm(formObj) {
            formObj.submit.disabled = true;
            formObj.submit.innerHTML = "UPLOADING SCHEDULES ...";
            return true;  
        }  
		
        function validateFormxprt(formObj) {
            formObj.submit.disabled = true; 
            return true;
        }
    </script>
	
</body>

</html>
<!-- end document-->
