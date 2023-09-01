<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $f = @$_GET['f'];
    $t = @$_GET['t'];
    $fil = @$_GET['fil'];

    $datefrom = date("m/d/Y", strtotime($f));
    $dateto = date("m/d/Y", strtotime($t));

    if ($f == $t) {
        $finaldate = $datefrom;
    }else{
        $finaldate = $datefrom." - ".$dateto;
    }

    if ($fil == "all") {
        $filter_title = "All";
        $filter = "`gy_schedule_escalate`.`gy_req_status`!='0'";
    }else if ($fil == 1) {
        $filter_title = "Approved";
        $filter = "`gy_schedule_escalate`.`gy_req_status`='1'";
    }else if ($fil == 2) {
        $filter_title = "Denied";
        $filter = "`gy_schedule_escalate`.`gy_req_status`='2'";
    }else{
        $filter_title = "unknown";
        $filter = "`gy_schedule_escalate`.`gy_req_status`!='0'";
    }

    $title = "Escalate Schedule Summary: ".$finaldate." - ".$filter_title;

    $query_one = "SELECT * From `gy_schedule_escalate` LEFT JOIN `gy_user` ON `gy_schedule_escalate`.`gy_emp_code`=`gy_user`.`gy_user_code` Where ".$filter." AND `gy_user`.`gy_user_type`<=5 AND `gy_schedule_escalate`.`gy_req_date` BETWEEN '$f' AND '$t' Order By `gy_schedule_escalate`.`gy_req_date` ASC";
    $query_two = "SELECT COUNT(`gy_sched_esc_id`) From `gy_schedule_escalate` LEFT JOIN `gy_user` ON `gy_schedule_escalate`.`gy_emp_code`=`gy_user`.`gy_user_code` Where ".$filter." AND `gy_user`.`gy_user_type`<=5 AND `gy_schedule_escalate`.`gy_req_date` BETWEEN '$f' AND '$t'";
    $query_three = "SELECT * From `gy_schedule_escalate` LEFT JOIN `gy_user` ON `gy_schedule_escalate`.`gy_emp_code`=`gy_user`.`gy_user_code` Where ".$filter." AND `gy_user`.`gy_user_type`<=5 AND `gy_schedule_escalate`.`gy_req_date` BETWEEN '$f' AND '$t' Order By `gy_schedule_escalate`.`gy_req_date` ASC ";

    $my_num_rows = 20;

    include 'my_pagination_search_history.php';

    $countres=$link->query($query_one)->num_rows;

    function esshtimecnvrt($keeptime){
        if($keeptime == "24:00:00"){ $keeptime = "00:00:00"; }
        if ($keeptime == "") { $simptime = "--:--"; }
        else{ $simptime = date("g:i A", strtotime($keeptime)); }
        return $simptime;
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
                            <h2 class="title-1 m-b-25"><?php echo $title; ?> <span style="font-size: 15px; text-transform: lowercase;" class="badge badge-success"><?php echo 0 + $countres; ?> results</span></h2>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <strong class="card-title mb-3">Data Table <small class="pull-right" style="font-style: italic;"><span style="color: blue;"><?= 0 + $countres; ?></span> results</small></strong>
                                </div>
                                <div class="card-body">
                                    <form method="post" enctype="multipart/form-data" action="redirect_manager" onsubmit="validateForm(this)">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>From</label>
                                                <input type="date" name="sched_datefrom" id="datefrom" onchange="daterange()" value="<?= $f; ?>" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>To</label>
                                                <input type="date" name="sched_dateto" id="dateto" onchange="daterange()" value="<?= $t; ?>" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3"> 
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select name="sched_filter" class="form-control">
                                                    <option value="<?= $fil; ?>"><?= $filter_title; ?></option>
                                                    <option value="all">All</option>
                                                    <option value="1">Approved</option>
                                                    <option value="2">Denied</option>
                                                </select>
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
                                                            <th rowspan="2" style="padding: 5px;" class="text-center">status</th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center"><i class="fa-solid fa-note-sticky"></i></th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center"><i class="fa fa-photo"></i></th>
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
                                                            while ($reqrow=$query->fetch_array()) {

                                                                if ($reqrow['gy_sched_mode'] == 0) {
                                                                    $datacolor = "red";
                                                                    $optstat = "disabled";

                                                                    $schin = "OFF";
                                                                    $dlin = "OFF";
                                                                    $dlout = "OFF";
                                                                    $schout = "OFF";
                                                                }else{
                                                                    $datacolor = "#000";
                                                                    $optstat = "";

                                                                    $schin = date("g:i A", strtotime($reqrow['gy_sched_login']));
                                                                    $dlin = simptime($reqrow['gy_tracker_login']);
                                                                    $dlout = simptime($reqrow['gy_tracker_logout']);
                                                                    $schout = date("g:i A", strtotime($reqrow['gy_sched_logout']));
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
                                                            <td style="padding: 3px; color: blue;" class="text-center text-nowrap"><?= $reqrow['gy_emp_code']; ?></td>
                                                            <td style="padding: 3px;" class="text-center text-nowrap"><?= $reqrow['gy_emp_fullname']; ?></td>
                                                            <td style="padding: 3px;" class="text-center text-nowrap"><?= date("m/d/Y", strtotime($reqrow['gy_sched_day'])); ?></td>
                                                            <td style="padding: 3px; font-style: italic;" class="text-center text-nowrap"><?= esshtimecnvrt($reqrow['old_sched_login'])." - ".esshtimecnvrt($reqrow['old_sched_logout']); ?></td>
                                                            <td style="padding: 3px; font-style: italic;" class="text-center text-nowrap"><?= simptime($reqrow['old_tracker_login'])." - ".simptime($reqrow['old_tracker_logout']); ?></td>
                                                            <td style="padding: 3px; color: blue;" class="text-center text-nowrap"><?= $schin." - ".$schout; ?></td>
                                                            <td style="padding: 3px; color: blue;" class="text-center text-nowrap"><?= $dlin." - ".$dlout; ?></td>
                                                            <td style="padding: 3px; font-style: italic;" class="text-center text-nowrap"><?= $mystatus; ?></td>
                                                            <td style="padding: 0px; font-style: italic;" class="text-center"><button type="button" data-toggle="modal" data-target="#reason_<?= $reqrow['gy_sched_esc_id']; ?>" class="btn btn-warning btn-sm btn-block" title="click to show reason ..."><i class="fa-solid fa-note-sticky"></i></button></td>
                                                            <td style="padding: 0px; font-style: italic;" class="text-center"><button type="button" data-toggle="modal" data-target="#pic_<?= $reqrow['gy_sched_esc_id']; ?>" class="btn btn-success btn-sm btn-block"><i class="fa fa-photo"></i></button></td>
                                                        </tr>

                                                        <?php include 'modal_esc.php'; ?>

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
