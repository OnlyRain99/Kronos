<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "Schedule Escalate Requests";

    $notify = @$_GET['note'];

    if ($notify == "invalid") {
        $note = "Invalid ...";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "approve") {
        $note = "Request Approved";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "deny") {
        $note = "Request Denied";
        $notec = "success";
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

    function gettrcklilo($trckcode){
        include '../../config/conn.php';
        $trksql=$link->query("SELECT `gy_tracker_login`,`gy_tracker_logout` FROM `gy_tracker` WHERE `gy_tracker_code`='$trckcode' LIMIT 1");
        $trkrow=$trksql->fetch_array();
        if($trksql->num_rows>0){
            $lilo = simptime($trkrow['gy_tracker_login'])." - ".simptime($trkrow['gy_tracker_logout']);
        }else{ $lilo = "no_curr_sched"; }
        $link->close();
        return $lilo;
    }

    $datestrt = date("Y-m-d");
    if(date("d")<=5){ $datestrt = date("Y-m-16", strtotime("-1 Month")); }
    else if(date("d")>=1 && date("d")<=20){ $datestrt = date("Y-m-01"); }
    else if(date("d")>=16){ $datestrt = date("Y-m-16"); }
    
    $request=$link->query("SELECT * From `gy_schedule_escalate` LEFT JOIN `gy_user` ON `gy_schedule_escalate`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_schedule_escalate`.`gy_req_status`='0' AND `gy_user`.`gy_user_type`<=5 AND `gy_user`.`gy_user_type`!=3 AND `gy_schedule_escalate`.`gy_sched_day`>='$datestrt' Order By `gy_schedule_escalate`.`gy_sched_esc_id` DESC");
    $countres=$request->num_rows;
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
                                    <strong class="card-title mb-3"><?= 0 + $countres; ?> in Queue</strong>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive m-b-40">
                                                <table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                                    <thead>
                                                        <tr class="mybg" style="text-transform: uppercase;">
                                                            <th rowspan="2" style="padding: 5px; color: blue;" class="text-center">SiBS ID</th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center">Name</th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center">Date</th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center">Status</th>
                                                            <th colspan="2" style="padding: 5px;" class="text-center">Current</th>
                                                            <th colspan="2" style="padding: 5px; color: blue;" class="text-center">Escalate</th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center"><i class="fa fa-comments"></i></th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center"><i class="fa fa-photo"></i></th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center"><i class="fa fa-check"></i></th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center"><i class="fa fa-times"></i></th>
                                                        </tr>
                                                        <tr class="mybg" style="text-transform: uppercase;">
                                                            <th style="padding: 5px;" class="text-center">Schedule</th>
                                                            <th style="padding: 5px;" class="text-center">Actual Logs</th>
                                                            <th style="padding: 5px; color: blue;" class="text-center">Schedule</th>
                                                            <th style="padding: 5px; color: blue;" class="text-center">Logs Duration</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php  
                                                            //get schedules requests
                                                            while ($reqrow=$request->fetch_array()) {

                                                                if ($reqrow['gy_sched_mode'] == 0) {
                                                                    $datacolor = "red";
                                                                    $optstat = "disabled";

                                                                    $schdin = "OFF";
                                                                    $dlin = "OFF";
                                                                    $dlout = "OFF";
                                                                    $schdout = "OFF";
                                                                }else{
                                                                    $datacolor = "#000";
                                                                    $optstat = "";

                                                                    $schdin = date("g:i A", strtotime($reqrow['gy_sched_login']));
                                                                    $dlin = simptime($reqrow['gy_tracker_login']);
                                                                    $dlout = simptime($reqrow['gy_tracker_logout']);
                                                                    $schdout = date("g:i A", strtotime($reqrow['gy_sched_logout']));
                                                                }
                                                        ?>

                                                        <tr class="mybg">
                                                            <td style="padding: 2px; color: blue;" class="text-center text-nowrap"><?= $reqrow['gy_emp_code']; ?></td>
                                                            <td style="padding: 2px;" class="text-center text-nowrap"><?= $reqrow['gy_emp_fullname']; ?></td>
                                                            <td style="padding: 2px;" class="text-center text-nowrap"><?= date("m/d/Y", strtotime($reqrow['gy_sched_day'])); ?></td>
                                                            <td style="padding: 2px;" class="text-center text-nowrap"><?= get_sched_status($reqrow['gy_sched_mode']); ?></td>
                                                            <td style="padding: 2px; font-style: italic;" class="text-center text-nowrap"><?= getlilo($reqrow['gy_sched_day'], $reqrow['gy_emp_code']); ?></td>
                                                            <td style="padding: 2px; font-style: italic;" class="text-center text-nowrap"><?= gettrcklilo($reqrow['gy_sched_esc_code']); ?></td>
                                                            <td style="padding: 2px; color: blue;" class="text-center text-nowrap"><?= $schdin." - ".$schdout; ?></td>
                                                            <td style="padding: 2px; color: blue;" class="text-center text-nowrap"><?= $dlin." - ".$dlout; ?></td>
                                                            <td style="padding: 0px; font-style: italic;" class="text-center"><button type="button" data-toggle="modal" data-target="#reason_<?= $reqrow['gy_sched_esc_id']; ?>" class="btn btn-warning btn-sm btn-block" title="click to show reason ..."><i class="fa fa-comments"></i></button></td>
                                                            <td style="padding: 0px; font-style: italic;" class="text-center"><button type="button" data-toggle="modal" data-target="#pic_<?= $reqrow['gy_sched_esc_id']; ?>" class="btn btn-primary btn-sm btn-block" title="click to show attachment ..."><i class="fa fa-photo"></i></button></td>
                                                            <td style="padding: 0px; font-style: italic;" class="text-center"><button type="button" data-toggle="modal" data-target="#approve_<?= $reqrow['gy_sched_esc_id']; ?>" class="btn btn-success btn-sm btn-block" title="click to approve request ..."><i class="fa fa-check"></i></button></td>
                                                            <td style="padding: 0px; font-style: italic;" class="text-center"><button type="button" data-toggle="modal" data-target="#deny_<?= $reqrow['gy_sched_esc_id']; ?>" class="btn btn-danger btn-sm btn-block" title="click to deny request ..."><i class="fa fa-times"></i></button></td>
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
        $("#activate-alert").fadeTo(5000, 500).slideUp(500, function(){
            $("#activate-alert").slideUp(500);
        });
    </script>

</body>

</html>
<!-- end document-->
