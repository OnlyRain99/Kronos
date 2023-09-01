<?php  
    include '../../config/conn.php';
    include '../../config/function.php';

    $redirect = @$_GET['cd'];

    //get info
    $info=$link->query("SELECT `gy_emp_email` From `gy_employee` Where `gy_emp_id`='$redirect'");
    $inforow=$info->fetch_array();

    $myemail = words($inforow['gy_emp_email']);

    //get dtr logs
    $getlogs=$link->query("SELECT `gy_log_date`,`gy_log_email`,`gy_log_fullname`,`gy_log_account` From `gy_logs` Where `gy_log_email`='$myemail'");
    $logrow=$getlogs->fetch_array();

    $title = $logrow['gy_log_fullname'];


    $notify = @$_GET['note'];

    if ($notify == "invalid") {
        $note = "<i class='fa fa-warning'></i> invalid date range";
        $notec = "warning";
        $notes = "";
    }else if ($notify == "fail") {
        $note = "<i class='fa fa-warning'></i> system error";
        $notec = "danger";
        $notes = "";
    }else{
        $note = "";
        $notec = "";
        $notes = "display: none;";
    }
?>

<!DOCTYPE html>
<html lang="en">

<?php include 'head.php'; ?>

<body class="animsition">
    <div class="page-wrapper">
        <div class="page-content--bge5">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="#">
                                <img src="../../images/icon/logo.png" alt="CoolAdmin">
                            </a>
                            <p style="text-align: center; font-size: 20px; color: #1791d3; margin-top: 10px; text-transform: uppercase;">Daily Attendance</p>
                        </div>
                        <div class="login-form" style="margin-top: -20px;">
                            <form method="post" enctype="multipart/form-data" action="dtrauth" onsubmit="return validateForm(this);">
                                <div class="form-group">
                                    <label>Date From</label>
                                    <input class="au-input au-input--full" type="date" name="from" id="from"autofocus required>
                                </div>
                                <div class="form-group">
                                    <label>Date To</label>
                                    <input class="au-input au-input--full" type="date" name="to" id="to"autofocus required>
                                </div>
                                <button class="au-btn au-btn--blue m-b-20" name="submit" id="submit" type="submit"><i class="fa fa-search"></i> Search</button>

                                <div style="<?php echo $notes; ?>" class="alert alert-danger" role="alert"><center><?php echo $note; ?></center></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php include 'scripts.php'; ?>

    <script type="text/javascript">  
        function validateForm(formObj) {
            formObj.submit.disabled = true; 
            return true;  
        }  
    </script>

</body>

</html>
<!-- end document-->