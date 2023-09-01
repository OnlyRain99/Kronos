<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    $title = "Escalate Schedule Summary";

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
                                    <strong class="card-title mb-3">Data Table <small class="pull-right" style="font-style: italic;">limit to <span style="color: blue;">20</span> rows</small></strong>
                                </div>
                                <div class="card-body">
                                    <form method="post" enctype="multipart/form-data" action="redirect_manager" onsubmit="validateForm(this)">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>From</label>
                                                <input type="date" name="sched_datefrom" id="datefrom" onchange="daterange()" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>To</label>
                                                <input type="date" name="sched_dateto" id="dateto" onchange="daterange()" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label style="color: blue;">*search</label>
                                                <button type="submit" name="submit" id="submit" class="btn btn-primary" title="click to search ..."><i class="fa fa-search"></i> Search</button>
                                            </div>
                                        </div>
                                    </div>
                                    </form>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive m-b-40">
                                                <table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                                    <thead>
                                                        <tr class="mybg" style="text-transform: uppercase;">
                                                            <th rowspan="2" style="padding: 5px; color: blue;" class="text-center">SiBS ID</th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center">Name</th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center">Date</th>
                                                            <th colspan="2" style="padding: 5px;" class="text-center">Current</th>
                                                            <th colspan="2" style="padding: 5px; color: blue;" class="text-center">
                                                            Escalate</th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center">Reason</th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center">status</th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center"><i class="fa fa-photo"></i></th>
                                                        </tr>
                                                        <tr class="mybg" style="text-transform: uppercase;">
                                                            <th style="padding: 5px;" class="text-center">LI-LO</th>
                                                            <th style="padding: 5px;" class="text-center">Break</th>
                                                            <th style="padding: 5px; color: blue;" class="text-center">LI-LO</th>
                                                            <th style="padding: 5px; color: blue;" class="text-center">Break</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php  
                                                            //get schedules requests
                                                            $request=$link->query("SELECT * From `gy_schedule_escalate` Where `gy_req_by`='$user_id' Order By `gy_sched_esc_id` DESC LIMIT 20");
                                                            while ($reqrow=$request->fetch_array()) {

                                                                if ($reqrow['gy_sched_mode'] == 0) {
                                                                    $datacolor = "red";
                                                                    $optstat = "disabled";

                                                                    $login = "OFF";
                                                                    $breakout = "OFF";
                                                                    $breakin = "OFF";
                                                                    $logout = "OFF";
                                                                }else{
                                                                    $datacolor = "#000";
                                                                    $optstat = "";

                                                                    $login = date("g:i A", strtotime($reqrow['gy_sched_login']));
                                                                    $breakout = date("g:i A", strtotime($reqrow['gy_sched_breakout']));
                                                                    $breakin = date("g:i A", strtotime($reqrow['gy_sched_breakin']));
                                                                    $logout = date("g:i A", strtotime($reqrow['gy_sched_logout']));
                                                                }

                                                                if ($reqrow['gy_req_status'] == 0) {
                                                                    $mystatus = "PENDING";
                                                                    $mybg = "mybg";
                                                                }else if ($reqrow['gy_req_status'] == 1){
                                                                    $mystatus = "APPROVED";
                                                                    $mybg = "mybg_green";
                                                                }else if ($reqrow['gy_req_status'] == 2){
                                                                    $mystatus = "DENIED";
                                                                    $mybg = "mybg_red";
                                                                }
                                                        ?>

                                                        <tr class="<?= $mybg; ?>">
                                                            <td style="padding: 1px; color: blue;" class="text-center"><?= $reqrow['gy_emp_code']; ?></td>
                                                            <td style="padding: 1px;" class="text-center"><?= $reqrow['gy_emp_fullname']; ?></td>
                                                            <td style="padding: 1px;" class="text-center"><?= date("m/d/Y", strtotime($reqrow['gy_sched_day'])); ?></td>
                                                            <td style="padding: 1px; font-style: italic;" class="text-center"><?= getlilo($reqrow['gy_sched_day'], $reqrow['gy_emp_code']); ?></td>
                                                            <td style="padding: 1px; font-style: italic;" class="text-center"><?= getbibo($reqrow['gy_sched_day'], $reqrow['gy_emp_code']); ?></td>
                                                            <td style="padding: 1px; color: blue;" class="text-center"><?= $login." - ".$logout; ?></td>
                                                            <td style="padding: 1px; color: blue;" class="text-center"><?= $breakout." - ".$breakin; ?></td>
                                                            <td style="padding: 1px; font-style: italic;" class="text-center"><?= $reqrow['gy_req_reason']; ?></td>
                                                            <td style="padding: 1px; font-style: italic;" class="text-center"><?= $mystatus; ?></td>
                                                            <td style="padding: 1px; font-style: italic;" class="text-center"><button type="button" data-toggle="modal" data-target="#pic_<?= $reqrow['gy_sched_esc_id']; ?>" class="btn btn-success btn-sm"><i class="fa fa-photo"></i></button></td>
                                                        </tr>

                                                        <?php include 'modal_esc.php'; ?>

                                                    <?php } ?>
                                                    </tbody>
                                                </table>
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
        function daterange(){
            var from = _getID("datefrom").value;
            var to = _getID("dateto").value;

            if (from) {
                _getID("dateto").min = from;
            }

            if (to) {
                _getID("datefrom").max = to;
            }
        }
    </script>

    <script type="text/javascript">  
        function validateForm(formObj) {
            formObj.submit.disabled = true;
            formObj.submit.innerHTML = "search results ...";
            return true;  
        }  
    </script>

    <script type="text/javascript">
        $("#activate-alert").fadeTo(5000, 500).slideUp(500, function(){
            $("#activate-alert").slideUp(500);
        });
    </script>

</body>

</html>
<!-- end document-->
