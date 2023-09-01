<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    $title = "My Team";

    $notify = @$_GET['note'];

    if ($notify == "app") {
        $note = "Request Approved";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "rej") {
        $note = "Request Rejected";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "modify") {
        $note = "Request Modified";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "update") {
        $note = "Time Keep Updated";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "nochange") {
        $note = "No data change - update cancelled";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "limit") {
        $note = "Multiple Edit Detected";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "s_space") {
        $note = "White spaces is not allowed";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "dateinvalid") {
        $note = "Invalid dates";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "s_zero") {
        $note = "Only 0 is not allowed";
        $notec = "warning";
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


    //3 days prior
    $threedate = words(date("Y-m-d", strtotime("-2 day")));
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
                            <h2 class="title-1 m-b-25"> <?php echo $title; ?></h2>
                            <div style="<?php echo $notes; ?>" id="<?php echo $noteid; ?>" class="sufee-alert alert with-close alert-<?php echo $notec; ?> alert-dismissible fade show">
                                <span class="badge badge-pill badge-<?php echo $notec; ?>">Alert</span>
                                <?php echo $note; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <form method="post" enctype="multipart/form-data" action="redirect_manager" onsubmit="return validateForm(this);">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <input type="hidden" name="master_search" class="form-control" placeholder="search email/name/id number ...">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>From <span style="color: red;">*required</span></label>
                                <input type="date" name="from" id="datefrom" onchange="daterange()" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>To <span style="color: red;">*required</span></label>
                                <input type="date" name="to" id="dateto" onchange="daterange()" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Select <span style="color: green;">L2</span></label>
                                <select class="form-control" name="level">
                                    <option value="0">All</option>
                                    <?php  
                                        //get level 2 accounts
                                        $getlevels=$link->query("SELECT `gy_employee`.`gy_emp_fullname`,`gy_user`.`gy_user_id` From `gy_employee` LEFT JOIN `gy_user` On `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_emp_supervisor`='$user_id'");
                                        while ($levelrow=$getlevels->fetch_array()) {
                                    ?>
                                    <option value="<?= $levelrow['gy_user_id']; ?>"><?= $levelrow['gy_emp_fullname']; ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label style="color: red;">*click &nbsp;</label>
                            <button type="submit" name="s_request" id="submit" class="btn btn-success" title="click to search ..."><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                    </form>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body card-block" id="accordion">

                                    <?php  
                                        $level=$link->query("SELECT `gy_employee`.`gy_emp_id`,`gy_employee`.`gy_emp_fullname`,`gy_employee`.`gy_emp_account`,`gy_user`.`gy_user_id`,`gy_user`.`gy_user_type` From `gy_employee` LEFT JOIN `gy_user` On `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_emp_supervisor`='$user_id'");
                                        while($lvlrow=$level->fetch_array()) {

                                            if ($lvlrow['gy_user_type'] == 2) {
                                                $condition="(`gy_employee`.`gy_emp_supervisor`='".$lvlrow['gy_user_id']."' OR `gy_employee`.`gy_emp_id`='".$lvlrow['gy_emp_id']."')";
                                            }else{
                                                $condition="`gy_employee`.`gy_emp_id`='".$lvlrow['gy_emp_id']."'";
                                            }
                                    ?>

                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_<?= $lvlrow['gy_user_id'];  ?>" style="text-decoration: none;"><?= $lvlrow['gy_emp_fullname'] ?> -> <?= $lvlrow['gy_emp_account']; ?></a>
                                            </h5>
                                        </div>
                                        <div id="collapse_<?= $lvlrow['gy_user_id'];  ?>" class="panel-collapse collapse">
                                            <div class="card-body card-block">
                                                <div class="table-responsive m-b-40">
                                                    <table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                                        <thead>
                                                            <tr class="mybg">
                                                                <th style="padding: 10px;">Name</th>
                                                                <th style="padding: 10px;" class="text-center">Date</th>
                                                                <th style="padding: 10px;" class="text-center">IN</th>
                                                                <th style="padding: 10px;" class="text-center">BO</th>
                                                                <th style="padding: 10px;" class="text-center">BI</th>
                                                                <th style="padding: 10px;" class="text-center">OUT</th>
                                                                <th style="padding: 10px;" class="text-center">WH</th>
                                                                <th style="padding: 10px;" class="text-center">BH</th>
                                                                <th style="padding: 10px;" class="text-center">OT</th>
                                                                <th style="padding: 10px; color: red;" class="text-center">UT/L</th>
                                                                <th style="padding: 10px;" class="text-center">Status</th>
                                                                <th style="padding: 10px;" class="text-center"><i class="fa fa-eye"></i></th>
                                                                <th style="padding: 10px;" class="text-center"><i class="fa fa-thumbs-up"></i></th>
                                                                <th style="padding: 10px;" class="text-center"><i class="fa fa-thumbs-down"></i></th>
                                                                <th style="padding: 10px;" class="text-center"><i class="fa fa-arrow-circle-up"></i></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php  
                                                                $statement = "SELECT `gy_employee`.`gy_emp_id`, `gy_employee`.`gy_emp_code`, `gy_employee`.`gy_emp_supervisor`, `gy_employee`.`gy_emp_lastedit`,`gy_tracker`.`gy_tracker_id`, `gy_tracker`.`gy_tracker_code`, `gy_tracker`.`gy_tracker_date`, `gy_tracker`.`gy_emp_email`, `gy_tracker`.`gy_emp_fullname`, `gy_tracker`.`gy_emp_account`, `gy_tracker`.`gy_tracker_login`, `gy_tracker`.`gy_tracker_breakout`, `gy_tracker`.`gy_tracker_breakin`, `gy_tracker`.`gy_tracker_logout`, `gy_tracker`.`gy_tracker_wh`, `gy_tracker`.`gy_tracker_bh`, `gy_tracker`.`gy_tracker_ot`, `gy_tracker`.`gy_tracker_status`, `gy_tracker`.`gy_tracker_request`, `gy_tracker`.`gy_tracker_reason`, `gy_tracker`.`gy_tracker_remarks`, `gy_tracker`.`gy_tracker_om`, `gy_tracker`.`gy_tracker_history` FROM `gy_employee` LEFT JOIN `gy_tracker` On `gy_employee`.`gy_emp_code`=`gy_tracker`.`gy_emp_code` Where ".$condition." AND date(`gy_tracker`.`gy_tracker_date`) BETWEEN '$threedate' AND '$onlydate' Order By `gy_tracker`.`gy_tracker_request` != '',`gy_tracker`.`gy_emp_fullname` ASC";
                                                                $tracks=$link->query($statement);
                                                                while ($trackrow=$tracks->fetch_array()) {

                                                                    if ($trackrow['gy_tracker_om'] != 0) {

                                                                        //get om details
                                                                        $myom=words($trackrow['gy_tracker_om']);
                                                                        $getom=$link->query("SELECT `gy_full_name` From `gy_user` Where `gy_user_id`='$myom'");
                                                                        $omrow=$getom->fetch_array();

                                                                        if ($trackrow['gy_tracker_request'] == "approve") {
                                                                            $omname = "Approved <i class='fa fa-check'></i>";
                                                                            $statuscolor = "green";
                                                                            $myom = $omrow['gy_full_name'];
                                                                            $btn_status = "disabled";
                                                                        }else if ($trackrow['gy_tracker_request'] == "overtime") {
                                                                            $omname = "Approved OT <i class='fa fa-check'></i>";
                                                                            $statuscolor = "green";
                                                                            $myom = $omrow['gy_full_name'];
                                                                            $btn_status = "disabled";
                                                                        }else if ($trackrow['gy_tracker_request'] == "reject") {
                                                                            $omname = "Rejected <i class='fa fa-times'></i>";
                                                                            $statuscolor = "red";
                                                                            $myom = $omrow['gy_full_name'];
                                                                            $btn_status = "disabled";
                                                                        }else if ($trackrow['gy_tracker_request'] == "escalate") {
                                                                            $omname = "Escalating <i class='fa fa-arrow-up'></i>";
                                                                            $statuscolor = "#bf5700";
                                                                            $myom = $omrow['gy_full_name'];
                                                                            $btn_status = "disabled";
                                                                        }else{
                                                                            $omname = "Pending";
                                                                            $statuscolor = "#000";
                                                                            $myom = "";
                                                                            $btn_status = "";
                                                                        }
                                                                    }else{
                                                                        $omname = "Pending";
                                                                        $statuscolor = "#000";
                                                                        $myom = "";
                                                                        $btn_status = "";
                                                                    }

                                                                    //wh status

                                                                    if ($trackrow['gy_tracker_wh'] == 0) {
                                                                        $wh_status = $statuscolor;
                                                                    }else if ($trackrow['gy_tracker_wh'] < 8) {
                                                                        $wh_status = "red";
                                                                    }else if ($trackrow['gy_tracker_wh'] > 8.5) {
                                                                        $wh_status = "blue";
                                                                    }else{
                                                                        $wh_status = $statuscolor;
                                                                    }

                                                                    //login status
                                                                    $empid = getempid($trackrow['gy_emp_code']);
                                                                    $schedlogin = getshcedlogin($empid, $trackrow['gy_tracker_date']);

                                                                    if ($trackrow['gy_tracker_login'] > date("Y-m-d", strtotime($trackrow['gy_tracker_date']))." ".$schedlogin) {
                                                                        $loginstatus = "red";
                                                                    }else{
                                                                        $loginstatus = $statuscolor;
                                                                    }

                                                                    $schedlogout = getshcedlogout($empid, $trackrow['gy_tracker_date']);

                                                                    if ($trackrow['gy_tracker_status'] == 1) {
                                                                        if ($schedlogin != "") {
                                                                            $undertime = get_ut(date("Y-m-d", strtotime($trackrow['gy_tracker_date']))." ".$schedlogin, date("Y-m-d", strtotime($trackrow['gy_tracker_logout']))." ".$schedlogout, $trackrow['gy_tracker_login'], $trackrow['gy_tracker_logout']);
                                                                        }else{
                                                                            $undertime = "0";
                                                                        }
                                                                    }else{
                                                                        $undertime = "0";
                                                                    }
                                                            ?>
                                                            <tr class="mybg">
                                                                <td style="padding: 0px; color: <?php echo $statuscolor; ?>;"><i><?php echo $trackrow['gy_emp_fullname']; ?></i></td>
                                                                <td style="padding: 0px; color: <?php echo $statuscolor; ?>;" class="text-center"><?php echo date("m/d/Y", strtotime($trackrow['gy_tracker_date'])); ?></td>
                                                                <td style="padding: 0px; color: <?php echo $loginstatus; ?>;" class="text-center" title="<?= simpdate($trackrow['gy_tracker_login']); ?>"><?php echo date("g:i A", strtotime($trackrow['gy_tracker_login'])); ?></td>
                                                                <td style="padding: 0px; color: <?php echo $statuscolor; ?>;" class="text-center" title="<?= simpdate($trackrow['gy_tracker_breakout']); ?>"><?php echo simptime($trackrow['gy_tracker_breakout']); ?></td>
                                                                <td style="padding: 0px; color: <?php echo $statuscolor; ?>;" class="text-center" title="<?= simpdate($trackrow['gy_tracker_breakin']); ?>"><?php echo simptime($trackrow['gy_tracker_breakin']); ?></td>
                                                                <td style="padding: 0px; color: <?php echo $statuscolor; ?>;" class="text-center" title="<?= simpdate($trackrow['gy_tracker_logout']); ?>"><?php echo simptime($trackrow['gy_tracker_logout']); ?></td>
                                                                <td style="padding: 0px; color: <?php echo $wh_status; ?>;" class="text-center"><?php echo $trackrow['gy_tracker_wh']; ?></td>
                                                                <td style="padding: 0px; color: <?php echo $statuscolor; ?>;" class="text-center"><?php echo $trackrow['gy_tracker_bh']; ?></td>
                                                                <td style="padding: 0px; color: <?php echo $statuscolor; ?>;" class="text-center"><?php echo $trackrow['gy_tracker_ot']; ?></td>
                                                                <td style="padding: 0px; color: red;" class="text-center"><?php echo $undertime; ?></td>
                                                                <td style="padding: 0px; color: <?php echo $statuscolor; ?>;" class="text-center"><i><?php echo $omname; ?></i></td>
                                                                <td style="padding: 0px; color: #fff;" class="text-center"><button type="button" data-toggle="modal" data-target="#info_<?= $trackrow['gy_tracker_id'];  ?>" class="btn btn-warning btn-sm" title="click to show details ..."><i class="fa fa-eye"></i></button></td>
                                                                <td style="padding: 0px; color: #fff;" class="text-center"><button type="button" data-toggle="modal" data-target="#app_<?php echo $trackrow['gy_tracker_id']; ?>" class="btn btn-success btn-sm" title="click to approve ..." <?= $btn_status; ?> ><i class="fa fa-thumbs-up"></i></button></td>
                                                                <td style="padding: 0px; color: #fff;" class="text-center"><button type="button" data-toggle="modal" data-target="#rej_<?php echo $trackrow['gy_tracker_id']; ?>" class="btn btn-danger btn-sm" title="click to reject ..." <?= $btn_status; ?> ><i class="fa fa-thumbs-down"></i></button></td>
                                                                <td style="padding: 0px; color: #fff;" class="text-center">
                                                                    <a href="escalate?cd=<?= $trackrow['gy_tracker_id']; ?>" onclick="window.open(this.href, 'mywin',
                    'left=20,top=20,width=1280,height=720,toolbar=1,resizable=0'); return false;"><button type="button" class="btn btn-info btn-sm" title="click to escalate ..."><i class="fa fa-arrow-circle-up"></i></button></a>
                                                                </td>
                                                            </tr>

                                                            <?php 
                                                                $timekeep = "update_timekeep?cd=".$trackrow['gy_tracker_id']."&mode=normal";
                                                                $statuschange = "statuschange?cd=".$trackrow['gy_tracker_id']."&mode=normal";

                                                                include 'modal_master.php';
                                                            ?>

                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php } ?>
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
