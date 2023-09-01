<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    $title = "Escalate Schedule";

    $notify = @$_GET['note'];

    if ($notify == "added") {
        $note = "Schedule Request Submitted";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "delete") {
        $note = "Schedule Request removed";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "sizelimit") {
        $note = "Attachment must not exceed 5MB ...";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "nodata") {
        $note = "SiBS ID not recognized ...";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "forwardplot") {
        $note = "Forward Plotting is not allowed ... Please input below current date";
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

    $statement = "SELECT `gy_sched_esc_id`,`gy_emp_code`,`gy_emp_fullname`,`gy_sched_day`,`gy_sched_mode`,`gy_sched_login`,`gy_sched_breakout`,`gy_sched_breakin`,`gy_sched_logout`,`gy_req_reason` From `gy_schedule_escalate` Where `gy_req_by`='$user_id' AND `gy_req_status`='0' AND date(`gy_req_date`)='$onlydate' Order By `gy_sched_esc_id` DESC";
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
                                    <strong class="card-title mb-3">PROCESS ID: <span id="process_id" style="color: blue;"></span></strong>
                                </div>
                                <div class="card-body">
                                    <form method="post" enctype="multipart/form-data" action="add_escalate" onsubmit="validateForm(this);">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label style="font-style: italic;">PROCESS ID</label>
                                                <input type="text" name="mycode" id="mycode" class="form-control" readonly required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label style="font-style: italic;">SiBS ID</label>
                                                <input type="text" name="sibsid" id="getsibsid" list="mysibsid" class="form-control" autofocus required>
                                                <datalist id="mysibsid"></datalist>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label style="font-style: italic;">Choose Date</label>
                                                <input type="date" name="mydate" max="<?= date('Y-m-d', strtotime('-1 day')) ?>" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label style="font-style: italic;">Status</label>
                                                <select class="form-control" name="status" id="status" onchange="work_off()" required>
                                                    <option></option>
                                                    <option value="1">WORK</option>
                                                    <option value="0">OFF</option>
                                                    <option value="2">RD DUTY</option>
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

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label style="font-style: italic;">Reason</label>
                                                <textarea name="reason" class="form-control" placeholder="put your reason here ..." required></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Attachment</label>
                                              <input type="file" name="file" class="form-control" accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps" onchange="readURL(this);" required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group text-center">
                                                <img src="#" style="width: 100px; height: 100px;" id="my-image" onerror="this.onerror=null; this.src='../../../images/icon/image.png'" style="decora">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button type="submit" name="submit" id="submit" class="btn btn-primary btn-lg btn-block" title="click to escalate ..."><i class="fa fa-arrow-circle-up"></i> Escalate</button>
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
                                                            <th colspan="9" style="color: #000;" class="text-center">PENDING ESCALATE SCHEDULE REQUESTS</th>
                                                        </tr>
                                                        <tr class="mybg" style="text-transform: uppercase;">
                                                            <th rowspan="2" style="padding: 5px; color: blue;" class="text-center">SiBS ID</th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center">Name</th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center">Date</th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center">Status</th>
                                                            <th colspan="2" style="padding: 5px;" class="text-center">Current</th>
                                                            <th colspan="2" style="padding: 5px; color: blue;" class="text-center">
                                                            Escalate</th>
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
                                                            $request=$link->query($statement);
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

                                                        ?>

                                                        <tr class="mybg">
                                                            <td style="padding: 1px; color: blue;" class="text-center"><?= $reqrow['gy_emp_code']; ?></td>
                                                            <td style="padding: 1px;" class="text-center"><?= $reqrow['gy_emp_fullname']; ?></td>
                                                            <td style="padding: 1px;" class="text-center"><?= date("m/d/Y", strtotime($reqrow['gy_sched_day'])); ?></td>
                                                            <td style="padding: 1px;" class="text-center"><?= get_sched_status($reqrow['gy_sched_mode']); ?></td>
                                                            <td style="padding: 1px; font-style: italic;" class="text-center"><?= getlilo($reqrow['gy_sched_day'], $reqrow['gy_emp_code']); ?></td>
                                                            <td style="padding: 1px; font-style: italic;" class="text-center"><?= getbibo($reqrow['gy_sched_day'], $reqrow['gy_emp_code']); ?></td>
                                                            <td style="padding: 1px; color: blue;" class="text-center"><?= $login." - ".$logout; ?></td>
                                                            <td style="padding: 1px; color: blue;" class="text-center"><?= $breakout." - ".$breakin; ?></td>
                                                            <td style="padding: 1px; font-style: italic;" class="text-center"><?= $reqrow['gy_req_reason']; ?></td>
                                                            <td style="padding: 1px;" class="text-center"><button type="button" data-toggle="modal" data-target="#delete_<?php echo $reqrow['gy_sched_esc_id']; ?>" class="btn btn-danger btn-sm" title="click to remove"><i class="fa fa-times"></i></button></td>
                                                        </tr>

                                                        <div class="modal fade" id="delete_<?php echo $reqrow['gy_sched_esc_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true">
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
                                                                            Do you want to delete this request?
                                                                        </p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                        <a href="delete_sched_esc?cd=<?php echo $reqrow['gy_sched_esc_id']; ?>"><button type="button" class="btn btn-danger">Confirm</button></a>
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

            load_process_id();
        });

        function load_process_id(){
            $.get('check_escalate.php', function(data) {
                $("#mycode").val(data);
                $("#process_id").html(data);
                setTimeout(load_process_id, 500);
            });
        }

        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#my-image')
                        .attr('src', e.target.result)
                        .width(100)
                        .height(150);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
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
