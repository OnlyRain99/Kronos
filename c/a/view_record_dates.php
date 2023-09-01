<?php  
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];

    //search emp data by dates
    if (isset($_POST['search_dates_emp'])) {
        $emp_from = words($_POST['emp_from']);
        $emp_to = words($_POST['emp_to']);

        if (strtotime($emp_from) > strtotime($emp_to)) {

            header("location: view_record?cd=$redirect&note=invalid");

        }else if ($emp_from != "0000-00-00" && $emp_to != "0000-00-00") {

            //get info
            $info=$link->query("SELECT `gy_emp_code`,`gy_emp_email`,`gy_emp_fullname` From `gy_employee` Where `gy_emp_id`='$redirect'");
            $inforow=$info->fetch_array();

            $empcode = words($inforow['gy_emp_code']);

        //get dtr logs
        $tracks=$link->query("SELECT * From `gy_tracker` Where `gy_emp_code`='$empcode' AND date(`gy_tracker_date`) BETWEEN '$emp_from' AND '$emp_to' Order By `gy_tracker_date` ASC");

        }else{
            header("location: view_record?cd=$redirect&note=empty");
        }
    }else{
        header("location: view_record?cd=$redirect&note=empty");
    }

    if ($emp_from == $emp_to) {
        $search = date("m/d/Y", strtotime($emp_from));
    }else{
        $search = date("m/d/Y", strtotime($emp_from))." to ".date("m/d/Y", strtotime($emp_to));
    }

    $title = $inforow['gy_emp_fullname'];
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
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong><?php echo $inforow['gy_emp_code']." - ".$inforow['gy_emp_fullname']; ?> </strong>record <span class="pull-right" style="font-style: italic;"><span style="color: blue;"><?= $search; ?></span> record</span>
                        </div>
                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data" action="view_record_dates?cd=<?php echo $redirect; ?>">
                            <div class="row no-print">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>From:</label>
                                        <input type="date" name="emp_from" id="datefrom" onchange="daterange()" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>To:</label>
                                        <input type="date" name="emp_to" id="dateto" onchange="daterange()" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label style="color: red;">*search</label>
                                        <button type="submit" class="btn btn-success" name="search_dates_emp" title="click to search records ..."><i class="fa fa-search"></i> Search Records</button>
                                    </div>
                                </div>
                            </div>
                            </form>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-responsive m-b-40">
                                        <table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                            <thead>
                                                <tr class="mybg">
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
                                                    <th style="padding: 10px;" class="text-center">Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php  
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
                                                            }else if ($trackrow['gy_tracker_request'] == "overtime") {
                                                                $omname = "Approved OT <i class='fa fa-check'></i>";
                                                                $statuscolor = "green";
                                                                $myom = $omrow['gy_full_name'];
                                                            }else if ($trackrow['gy_tracker_request'] == "reject") {
                                                                $omname = "Rejected <i class='fa fa-times'></i>";
                                                                $statuscolor = "red";
                                                                $myom = $omrow['gy_full_name'];
                                                            }else if ($trackrow['gy_tracker_request'] == "escalate") {
                                                                $omname = "Escalating <i class='fa fa-arrow-up'></i>";
                                                                $statuscolor = "#bf5700";
                                                                $myom = $omrow['gy_full_name'];
                                                            }else{
                                                                $omname = "Pending";
                                                                $statuscolor = "#000";
                                                                $myom = "";
                                                            }
                                                        }else{
                                                            $omname = "Pending";
                                                            $statuscolor = "#000";
                                                            $myom = "";
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

                                                        $schedlogout = getshcedlogout($empid, $trackrow['gy_tracker_logout']);

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
                                                    <td style="padding: 0px; color: <?= $statuscolor; ?>;" class="text-center"><?php echo date("m/d/Y", strtotime($trackrow['gy_tracker_date'])); ?></td>
                                                    <td style="padding: 0px; color: <?= $loginstatus; ?>;" class="text-center" title="<?= simpdate($trackrow['gy_tracker_login']) ?>"><?php echo date("g:i A", strtotime($trackrow['gy_tracker_login'])); ?></td>
                                                    <td style="padding: 0px; color: <?= $statuscolor; ?>;" class="text-center" title="<?= simpdate($trackrow['gy_tracker_breakout']) ?>"><?php echo simptime($trackrow['gy_tracker_breakout'], $trackrow['gy_tracker_date']); ?></td>
                                                    <td style="padding: 0px; color: <?= $statuscolor; ?>;" class="text-center" title="<?= simpdate($trackrow['gy_tracker_breakin']) ?>"><?php echo simptime($trackrow['gy_tracker_breakin'], $trackrow['gy_tracker_date']); ?></td>
                                                    <td style="padding: 0px; color: <?= $statuscolor; ?>;" class="text-center" title="<?= simpdate($trackrow['gy_tracker_logout']) ?>"><?php echo simptime($trackrow['gy_tracker_logout'], $trackrow['gy_tracker_date']); ?></td>
                                                    <td style="padding: 0px; color: <?= $wh_status; ?>;" class="text-center"><?php echo $trackrow['gy_tracker_wh']; ?></td>
                                                    <td style="padding: 0px; color: <?= $statuscolor; ?>;" class="text-center"><?php echo $trackrow['gy_tracker_bh']; ?></td>
                                                    <td style="padding: 0px; color: <?= $statuscolor; ?>;" class="text-center"><?php echo $trackrow['gy_tracker_ot']; ?></td>
                                                    <td style="padding: 0px; color: red;" class="text-center"><?php echo $undertime; ?></td>
                                                    <td style="padding: 0px; color: <?= $statuscolor; ?>;" class="text-center"><i><?php echo $omname; ?></i></td>
                                                    <td style="padding: 0px; color: #fff;" class="text-center"><button type="button" data-toggle="modal" data-target="#info_<?= $trackrow['gy_tracker_id'];  ?>" class="btn btn-warning btn-sm" title="click to show details ..."><i class="fa fa-eye"></i></button></td>
                                                </tr>

                                                <?php include 'modal_record.php'; ?>
                                                
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
            }  _getID("datefrom2").max = to2;
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