<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];

    $title = "PROCESS ID ".$redirect;

    $notify = @$_GET['note'];

    if ($notify == "added") {
        $note = "Schedule Added";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "delete") {
        $note = "Schedule removed";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "send") {
        $note = "Schedule Request Sent";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "norequest") {
        $note = "Nothing to send ...";
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

    $query_one = "SELECT `gy_req_id`,`gy_emp_code`,`gy_emp_fullname`,`gy_sched_day`,`gy_sched_mode`,`gy_sched_login`,`gy_sched_breakout`,`gy_sched_breakin`,`gy_sched_logout`,`gy_req_reason` From `gy_request` Where `gy_req_code`='$redirect' Order By `gy_emp_fullname` ASC";

    $query_two = "SELECT COUNT(`gy_req_id`) From `gy_request` Where `gy_req_code`='$redirect'";

    $query_three = "SELECT `gy_req_id`,`gy_emp_code`,`gy_emp_fullname`,`gy_sched_day`,`gy_sched_mode`,`gy_sched_login`,`gy_sched_breakout`,`gy_sched_breakin`,`gy_sched_logout`,`gy_req_reason` From `gy_request` Where `gy_req_code`='$redirect' Order By `gy_emp_fullname` ASC ";

    $my_num_rows = 20;

    include 'my_pagination_custom.php';
?>

<!DOCTYPE html>
<html lang="en">

<?php include 'head.php'; ?>

<style type="text/css">
    body{
        color: #000;
    }

    @media print{
        .no-print{
            display: none;
        }
    }
</style>

<body>

    <div class="page-wrapper">
        <div class="container">

            <!-- MAIN CONTENT-->
            <div class="main-content" style="padding: 20px;">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="title-1 m-b-25"><?php echo $title; ?></h2>
                            <div style="<?php echo $notes; ?>" id="<?php echo $noteid; ?>" class="sufee-alert alert with-close alert-<?php echo $notec; ?> alert-dismissible fade show">
                                <span class="badge badge-pill badge-<?php echo $notec; ?>">Alert</span>
                                <?php echo $note; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <strong class="card-title mb-3">Data Table <small style="font-style: italic;"><span class="pull-right"><span style="color: blue;">20</span> rows per page</span></small></strong>
                                </div>
                                <div class="card-body">
                                    <form method="post" enctype="multipart/form-data" action="redirect_manager?cd=<?= $redirect; ?>">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>SiBS ID / Name</label>
                                                <input type="text" name="sibsid" id="getsibsid" list="mysibsid" class="form-control" autofocus required>
                                                <datalist id="mysibsid"></datalist>
                                            </div>
                                        </div>
                                    </div>
                                    </form>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive m-b-40">
                                                <table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                                    <thead>
                                                        <tr class="mybg">
                                                            <th rowspan="2" style="padding: 5px; color: blue;" class="text-center">SiBS ID</th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center">Name</th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center">Date</th>
                                                            <th colspan="2" style="padding: 5px;" class="text-center">Current</th>
                                                            <th colspan="2" style="padding: 5px; color: blue;" class="text-center">Request</th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center">Reason</th>
                                                        </tr>
                                                        <tr class="mybg">
                                                            <th style="padding: 5px;" class="text-center">LI-LO</th>
                                                            <th style="padding: 5px;" class="text-center">Break</th>
                                                            <th style="padding: 5px; color: blue;" class="text-center">LI-LO</th>
                                                            <th style="padding: 5px; color: blue;" class="text-center">Break</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php  
                                                            //get schedules requests
                                                            while ($reqrow=$query->fetch_array()) {

                                                                if ($reqrow['gy_sched_mode'] == 0) {
                                                                    $datacolor = "red";
                                                                    $optstat = "disabled";

                                                                    $login = "OFF";
                                                                    $breakout = "OFF";
                                                                    $breakin = "OFF";
                                                                    $logout = "OFF";
                                                                }else if ($reqrow['gy_sched_mode'] == 2){
                                                                    $datacolor = "blue";
                                                                    $optstat = "";

                                                                    $login = date("g:i A", strtotime($reqrow['gy_sched_login']));
                                                                    $breakout = date("g:i A", strtotime($reqrow['gy_sched_breakout']));
                                                                    $breakin = date("g:i A", strtotime($reqrow['gy_sched_breakin']));
                                                                    $logout = date("g:i A", strtotime($reqrow['gy_sched_logout']));
                                                                }else{
                                                                    $datacolor = "#000";
                                                                    $optstat = "";

                                                                    $login = date("g:i A", strtotime($reqrow['gy_sched_login']));
                                                                    $breakout = date("g:i A", strtotime($reqrow['gy_sched_breakout']));
                                                                    $breakin = date("g:i A", strtotime($reqrow['gy_sched_breakin']));
                                                                    $logout = date("g:i A", strtotime($reqrow['gy_sched_logout']));
                                                                }

                                                        ?>

                                                        <tr class="mybg">
                                                            <td style="padding: 1px; color: blue;" class="text-center"><?= $reqrow['gy_emp_code']; ?></td>
                                                            <td style="padding: 1px;" class="text-center"><?= $reqrow['gy_emp_fullname']; ?></td>
                                                            <td style="padding: 1px;" class="text-center"><?= date("m/d/Y", strtotime($reqrow['gy_sched_day'])); ?></td>
                                                            <td style="padding: 1px; font-style: italic;" class="text-center"><?= getlilo($reqrow['gy_sched_day'], $reqrow['gy_emp_code']); ?></td>
                                                            <td style="padding: 1px; font-style: italic;" class="text-center"><?= getbibo($reqrow['gy_sched_day'], $reqrow['gy_emp_code']); ?></td>
                                                            <td style="padding: 1px; color: blue;" class="text-center"><?= $login." - ".$logout; ?></td>
                                                            <td style="padding: 1px; color: blue;" class="text-center"><?= $breakout." - ".$breakin; ?></td>
                                                            <td style="padding: 1px; font-style: italic;" class="text-center"><?= $reqrow['gy_req_reason']; ?></td>
                                                        </tr>


                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="text-center"> 
                                             <ul class="pagination">
                                                <?php echo $paginationCtrls; ?>
                                             </ul>
                                        </div>
                                     </div>
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
        var timer;
        $(document).ready(function(){
            $("#getsibsid").keyup(function(){
                clearTimeout(timer);
                var ms = 200; // milliseconds
                $.get("live_search", {sibsid: $(this).val()}, function(data){
                    timer = setTimeout(function() {
                        $("datalist").empty();
                        $("datalist").html(data);
                    }, ms);
                });
            });
        });
    </script>

    <script type="text/javascript">
        $("#activate-alert").fadeTo(5000, 500).slideUp(500, function(){
            $("#activate-alert").slideUp(500);
        });
    </script>

    <script type="text/javascript">  
        function validateForm(formObj) {
            formObj.submit.disabled = true; 
            return true;  
        }  
    </script>

</body>

</html>
<!-- end document-->