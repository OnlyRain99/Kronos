<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "Create Schedule Adjustment";

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
    }else if ($notify == "nodata") {
        $note = "SiBS ID not recognized ...";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "norequest") {
        $note = "Nothing to send ...";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "backplot") {
        $note = "Back Plotting is not allowed ... Please input current date onwards";
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

    include 'check_schedule.php';

    $query_one = "SELECT `gy_req_id`,`gy_emp_code`,`gy_emp_fullname`,`gy_sched_day`,`gy_sched_mode`,`gy_sched_login`,`gy_sched_breakout`,`gy_sched_breakin`,`gy_sched_logout`,`gy_req_reason` From `gy_request` Where `gy_req_code`='$myreqcode' Order By `gy_req_id` DESC";

    $query_two = "SELECT COUNT(`gy_req_id`) From `gy_request` Where `gy_req_code`='$myreqcode'";

    $query_three = "SELECT `gy_req_id`,`gy_emp_code`,`gy_emp_fullname`,`gy_sched_day`,`gy_sched_mode`,`gy_sched_login`,`gy_sched_breakout`,`gy_sched_breakin`,`gy_sched_logout`,`gy_req_reason` From `gy_request` Where `gy_req_code`='$myreqcode' Order By `gy_req_id` DESC ";

    $my_num_rows = 15;

    include 'my_pagination.php';
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
                                    <strong class="card-title mb-3">PROCESS ID: <span style="color: blue;"><?= $myreqcode; ?> <span class="pull-right"><button type="button" data-toggle="modal" data-target="#send" class="btn btn-primary btn-lg" title="click to export ...">Submit Request <i class="fa fa-paper-plane"></i></button></span></strong>
                                </div>
                                <div class="card-body">
                                    <form method="post" enctype="multipart/form-data" action="add_sched?cd=<?= $myreqcode; ?>" onsubmit="validateForm(this);">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label style="font-style: italic;">SiBS ID</label>
                                                <input type="text" name="sibsid" id="getsibsid" list="mysibsid" class="form-control" autofocus required>
                                                <datalist id="mysibsid"></datalist>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label style="font-style: italic;">From</label>
                                                <input type="date" name="datefrom" id="datefrom" onchange="daterange()" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label style="font-style: italic;">To</label>
                                                <input type="date" name="dateto" id="dateto" onchange="daterange()" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label style="font-style: italic;">Status</label>
                                                <select class="form-control" name="status" id="status" onchange="work_off()" required>
                                                    <option></option>
                                                    <option value="1">WORK</option>
                                                    <option value="0">OFF</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label style="font-style: italic;">Login</label>
                                                <select name="login" id="login" class="form-control" required>
                                                    <option></option>
                                                    <?php  
                                                        for ($i=1; $i <= 24; $i++) {

                                                            if ($i > 12) {

                                                                if ($i == 24) {
                                                                    $ampm = "AM";
                                                                }else{
                                                                    $ampm = "PM";
                                                                }

                                                                $mainval = $i.":00:00";
                                                                $displayval = ($i - 12).":00 ".$ampm;
                                                            }else{

                                                                if ($i == 12) {
                                                                    $ampm = "PM";
                                                                }else{
                                                                    $ampm = "AM";
                                                                }

                                                                $mainval = $i.":00:00";
                                                                $displayval = $i.":00 ".$ampm;
                                                            }
                                                    ?>
                                                    <option value="<?= $mainval; ?>"><?= $displayval; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label style="font-style: italic;">Break-Out</label>
                                                <select name="breakout" id="breakout" class="form-control" required>
                                                    <option></option>
                                                    <?php  
                                                        for ($i=1; $i <= 24; $i++) {

                                                            if ($i > 12) {

                                                                if ($i == 24) {
                                                                    $ampm = "AM";
                                                                }else{
                                                                    $ampm = "PM";
                                                                }

                                                                $mainval = $i.":00:00";
                                                                $displayval = ($i - 12).":00 ".$ampm;
                                                            }else{

                                                                if ($i == 12) {
                                                                    $ampm = "PM";
                                                                }else{
                                                                    $ampm = "AM";
                                                                }

                                                                $mainval = $i.":00:00";
                                                                $displayval = $i.":00 ".$ampm;
                                                            }
                                                    ?>
                                                    <option value="<?= $mainval; ?>"><?= $displayval; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label style="font-style: italic;">Break-In</label>
                                                <select name="breakin" id="breakin" class="form-control" required>
                                                    <option></option>
                                                    <?php  
                                                        for ($i=1; $i <= 24; $i++) {

                                                            if ($i > 12) {

                                                                if ($i == 24) {
                                                                    $ampm = "AM";
                                                                }else{
                                                                    $ampm = "PM";
                                                                }

                                                                $mainval = $i.":00:00";
                                                                $displayval = ($i - 12).":00 ".$ampm;
                                                            }else{

                                                                if ($i == 12) {
                                                                    $ampm = "PM";
                                                                }else{
                                                                    $ampm = "AM";
                                                                }

                                                                $mainval = $i.":00:00";
                                                                $displayval = $i.":00 ".$ampm;
                                                            }
                                                    ?>
                                                    <option value="<?= $mainval; ?>"><?= $displayval; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label style="font-style: italic;">Logout</label>
                                                <select name="logout" id="logout" class="form-control" required>
                                                    <option></option>
                                                    <?php  
                                                        for ($i=1; $i <= 24; $i++) {

                                                            if ($i > 12) {

                                                                if ($i == 24) {
                                                                    $ampm = "AM";
                                                                }else{
                                                                    $ampm = "PM";
                                                                }

                                                                $mainval = $i.":00:00";
                                                                $displayval = ($i - 12).":00 ".$ampm;
                                                            }else{

                                                                if ($i == 12) {
                                                                    $ampm = "PM";
                                                                }else{
                                                                    $ampm = "AM";
                                                                }

                                                                $mainval = $i.":00:00";
                                                                $displayval = $i.":00 ".$ampm;
                                                            }
                                                    ?>
                                                    <option value="<?= $mainval; ?>"><?= $displayval; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label style="font-style: italic;">Reason</label>
                                                <textarea name="reason" class="form-control" placeholder="put your reason here ..." required></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button type="submit" name="submit" id="submit" class="btn btn-success btn-lg btn-block" title="click to Add ..."><i class="fa fa-plus"></i> Add Request</button>
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
                                                            <th colspan="2" style="padding: 5px; color: blue;" class="text-center">Request</th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center">Reason</th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center" title="click to remove ..."><i class="fa fa-times"></i></th>
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
                                                            while ($reqrow=$query->fetch_array()) {

                                                                if ($reqrow['gy_sched_mode'] == 0) {
                                                                    $datacolor = "red";
                                                                    $optstat = "disabled";

                                                                    $login = "OFF";
                                                                    $breakout = "OFF";
                                                                    $breakin = "OFF";
                                                                    $logout = "OFF";
                                                                }else if ($reqrow['gy_sched_mode'] == 2) {
                                                                    $datacolor = "blue";
                                                                    $optstat = "";

                                                                    $login = date("g:i A", strtotime($reqrow['gy_sched_login']));
                                                                    $breakout = date("g:i A", strtotime($reqrow['gy_sched_breakout']));
                                                                    $breakin = date("g:i A", strtotime($reqrow['gy_sched_breakin']));
                                                                    $logout = date("g:i A", strtotime($reqrow['gy_sched_logout']));
                                                                } else{
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
                                                            <td style="padding: 1px;" class="text-center"><button type="button" data-toggle="modal" data-target="#delete_<?php echo $reqrow['gy_req_id']; ?>" class="btn btn-danger btn-sm" title="click to remove"><i class="fa fa-times"></i></button></td>
                                                        </tr>

                                                        <div class="modal fade" id="delete_<?php echo $reqrow['gy_req_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-sm" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="staticModalLabel"><i class="fa fa-trash"></i> Delete <span style="color: blue;"><?php echo $reqrow['gy_emp_code']." - ".date("m/d/Y", strtotime($reqrow['gy_sched_day'])); ?></span></h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p>
                                                                            Do you want to delete this schedule on the list?
                                                                        </p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                        <a href="delete_sched?cd=<?php echo $reqrow['gy_req_id']; ?>"><button type="button" class="btn btn-danger">Confirm</button></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

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

                    <!-- send modal -->

                    <div class="modal fade" id="send" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticModalLabel"><i class="fa fa-paper-plane"></i> Submit Request</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="post" enctype="multipart/form-data" action="request_schedule">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label style="font-weight: bold;">PROCESS ID</label>
                                        <input type="text" name="reqcode" class="form-control" value="<?= $myreqcode; ?>" readonly required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" id="submit" name="submit" class="btn btn-primary">Confirm</button>
                                </div>
                                </form>
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

        $("#login").prop("disabled", true);
        $("#breakout").prop("disabled", true);
        $("#breakin").prop("disabled", true);
        $("#logout").prop("disabled", true);

        function work_off(){

            var mode = _getID('status').value;

            if (mode == 0) {
                $("#login").prop("disabled", true);
                $("#breakout").prop("disabled", true);
                $("#breakin").prop("disabled", true);
                $("#logout").prop("disabled", true);
            }else{
                $("#login").prop("disabled", false);
                $("#breakout").prop("disabled", false);
                $("#breakin").prop("disabled", false);
                $("#logout").prop("disabled", false);
            }
        }
    </script>

    <script type="text/javascript">  
        function validateForm(formObj) {
            formObj.submit.disabled = true;
            formObj.submit.innerHTML = "adding schedule ...";
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
