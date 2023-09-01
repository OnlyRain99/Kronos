<?php  
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];

    //current month
    $thismonth = words(date("m"));

    //get info
    $info=$link->query("SELECT `gy_emp_code`,`gy_emp_email`,`gy_emp_fullname` From `gy_employee` Where `gy_emp_id`='$redirect'");
    $inforow=$info->fetch_array();

    $empcode = words($inforow['gy_emp_code']);
    $title = $inforow['gy_emp_fullname'];

    $schedules=$link->query("SELECT * From `gy_schedule` Where `gy_emp_id`='$redirect' Order By `gy_sched_day` ASC LIMIT 35");

    $notify = @$_GET['note'];

    if ($notify == "duplicate") {
        $note = "Duplicate entries denied";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "added") {
        $note = "Schedule Added";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "update") {
        $note = "Schedule Updated";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "delete") {
        $note = "Schedule removed";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "empty") {
        $note = "Empty search";
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
            <div class="row">
                <div class="col-lg-12">
                    <br>
                    <div style="<?php echo $notes; ?>" id="<?php echo $noteid; ?>" class="sufee-alert alert with-close alert-<?php echo $notec; ?> alert-dismissible fade show no-print">
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
                            <strong><?php echo $inforow['gy_emp_code']." - ".$inforow['gy_emp_fullname']; ?> </strong>schedule <span class="pull-right" style="font-style: italic;"><span style="color: blue;">35</span> rows only</span>
                        </div>
                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data" action="redirect_manager?cd=<?= $redirect; ?>" onsubmit="validateForm(this)">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <input type="date" name="datefrom" id="datefrom" onchange="daterange()" class="form-control" title="from" required>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <input type="date" name="dateto" id="dateto" onchange="daterange()" class="form-control" title="to" required>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <button type="submit" name="submit" id="submit" class="btn btn-primary" title="click to search ..."><i class="fa fa-search"></i> Search Schedule</button>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group pull-right">
                                        <button type="button" class="btn btn-success" name="search_dates_emp" data-toggle="modal" data-target="#add" title="click to search records ..."><i class="fa fa-plus"></i> Add Schedule</button>
                                    </div>
                                </div>
                            </form>

                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                            <thead style="background: #fff;">
                                                <tr class="mybg">
                                                    <th style="padding: 3px; color: #000;" class="text-center">No.</th>
                                                    <th style="padding: 3px; color: #000;" class="text-center">Date</th>
                                                    <th style="padding: 3px; color: #000;" class="text-center">Day</th>
                                                    <th style="padding: 3px; color: #000;" class="text-center">Login</th>
                                                    <th style="padding: 3px; color: #000;" class="text-center">Logout</th>
                                                    <th style="padding: 3px; color: #000;" class="text-center">Break-Out</th>
                                                    <th style="padding: 3px; color: #000;" class="text-center">Break-In</th>
                                                    <th style="padding: 3px; color: #000;" class="text-center"><i class="fa fa-edit"></i></th>
                                                    <th style="padding: 3px; color: #000;" class="text-center"><i class="fa fa-trash"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $num=0;
                                                    while ($schedrow=$schedules->fetch_array()) {

                                                        $num++;

                                                        if ($schedrow['gy_sched_mode'] == 0) {
                                                            $datacolor = "red";
                                                            $optstat = "disabled";

                                                            $login = "OFF";
                                                            $breakout = "OFF";
                                                            $breakin = "OFF";
                                                            $logout = "OFF";

                                                            $loginopt = "";
                                                            $breakoutopt = "";
                                                            $breakinopt = "";
                                                            $logoutopt = "";
                                                        }else if ($schedrow['gy_sched_mode'] == 2) {
                                                            $datacolor = "blue";
                                                            $optstat = "";

                                                            $login = date("g:i A", strtotime($schedrow['gy_sched_login']));
                                                            $breakout = date("g:i A", strtotime($schedrow['gy_sched_breakout']));
                                                            $breakin = date("g:i A", strtotime($schedrow['gy_sched_breakin']));
                                                            $logout = date("g:i A", strtotime($schedrow['gy_sched_logout']));

                                                            $loginopt = $login;
                                                            $breakoutopt = $breakout;
                                                            $breakinopt = $breakin;
                                                            $logoutopt = $logout;
                                                        }else{
                                                            $datacolor = "#000";
                                                            $optstat = "";

                                                            $login = date("g:i A", strtotime($schedrow['gy_sched_login']));
                                                            $breakout = date("g:i A", strtotime($schedrow['gy_sched_breakout']));
                                                            $breakin = date("g:i A", strtotime($schedrow['gy_sched_breakin']));
                                                            $logout = date("g:i A", strtotime($schedrow['gy_sched_logout']));

                                                            $loginopt = $login;
                                                            $breakoutopt = $breakout;
                                                            $breakinopt = $breakin;
                                                            $logoutopt = $logout;
                                                        }

                                                    if ($breakout == "12:00 AM" && $breakin == "12:00 AM") {
                                                        $breakout = "00:00";
                                                        $breakin = "00:00";
                                                    }else{
                                                        $breakout = $breakout;
                                                        $breakin = $breakin;
                                                    }

                                                ?>
                                                <tr class="mybg">
                                                    <td style="padding: 0px; color: <?= $datacolor; ?>;" class="text-center"><?= $num; ?></td>
                                                    <td style="padding: 0px; color: <?= $datacolor; ?>;" class="text-center"><?= date("m/d/Y", strtotime($schedrow['gy_sched_day'])); ?></td>
                                                    <td style="padding: 0px; color: <?= $datacolor; ?>;" class="text-center"><?= date("D", strtotime($schedrow['gy_sched_day'])); ?></td>
                                                    <td style="padding: 0px; color: <?= $datacolor; ?>;" class="text-center"><?= $login; ?></td>
                                                    <td style="padding: 0px; color: <?= $datacolor; ?>;" class="text-center"><?= $logout; ?></td>
                                                    <td style="padding: 0px; color: <?= $datacolor; ?>;" class="text-center"><?= $breakout; ?></td>
                                                    <td style="padding: 0px; color: <?= $datacolor; ?>;" class="text-center"><?= $breakin; ?></td>
                                                    <td style="padding: 0px;" class="text-center"><button type="button" data-toggle="modal" data-target="#edit_<?= $schedrow['gy_sched_id'] ?>" class="btn-info btn-sm" title="click to edit ..."><i class="fa fa-edit"></i></button></i></td>
                                                    <td style="padding: 0px;" class="text-center"><button type="button" data-toggle="modal" data-target="#delete_<?= $schedrow['gy_sched_id'] ?>" class="btn-danger btn-sm" title="click to delete ..."><i class="fa fa-trash"></i></button></i></td>
                                                </tr>

                                                    <?php
                                                        $edit_link = "update_sched?cd=$redirect=&dir=".$schedrow['gy_sched_id']."&mode=normal";
                                                        $delete_link = "delete_sched?cd=$redirect=&dir=".$schedrow['gy_sched_id']."&mode=normal";

                                                        include 'modal_schedule.php'; 
                                                    ?>

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
        </div>
    </div>

    <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediumModalLabel"><i class="fa fa-plus"></i> Add/Overwrite schedule</span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" action="add_sched?cd=<?= $redirect; ?>" onsubmit="validateForm(this);">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label style="font-style: italic;">From</label>
                                <input type="date" name="datefrom" id="datefrom2" onchange="daterange()" class="form-control" autofocus required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label style="font-style: italic;">To</label>
                                <input type="date" name="dateto" id="dateto2" onchange="daterange()" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label style="font-style: italic;">Status</label>
                                <select class="form-control" name="status" id="mymode" onchange="work_off()" required>
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
                                    <?php  hourdisplay(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label style="font-style: italic;">Break-Out</label>
                                    <select name="breakout" id="breakout" class="form-control" >
                                    <option></option>
                                    <?php hourdisplay(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label style="font-style: italic;">Break-In</label>
                                <select name="breakin" id="breakin" class="form-control" >
                                    <option></option>
                                    <?php hourdisplay(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label style="font-style: italic;">Logout</label>
                                <select name="logout" id="logout" class="form-control" required>
                                    <option></option>
                                    <?php  hourdisplay(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group pull-right">
                                <button type="submit" name="submit" id="submit" class="btn btn-success" title="click to Add ..."><i class="fa fa-plus"></i> Add Schedule</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include 'scripts.php'; ?>

    <script type="text/javascript">
        function daterange(){
            var from = _getID("datefrom").value;
            var to = _getID("dateto").value;


            var from2 = _getID("datefrom2").value;
            var to2 = _getID("dateto2").value;


            if (from) {
                _getID("dateto").min = from;
            }

            if (to) {
                _getID("datefrom").max = to;
            }

            if (from2) {
                _getID("dateto2").min = from2;
            }

            if (to2) {
                _getID("datefrom2").max = to2;
            }
        }
    </script>

    <script type="text/javascript">

        $("#login").prop("disabled", true);
        $("#breakout").prop("disabled", true);
        $("#breakin").prop("disabled", true);
        $("#logout").prop("disabled", true);

        function work_off(){

            var mode = _getID('mymode').value;

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