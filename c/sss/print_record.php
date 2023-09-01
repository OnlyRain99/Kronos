<?php  
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "Print Record";

    $sdate = words(date("m"));

    $mode = @$_GET['mode'];
    $datef = @$_GET['datef'];
    $datet = @$_GET['datet'];

    if ($mode == "normal") {
        //get records
        $logs=$link->query("SELECT * From `gy_tracker` Where month(`gy_tracker_date`)='$sdate' AND `gy_emp_code`='$user_code' Order By `gy_tracker_date` DESC");

        $remark = date("F");
    }else{
        //get records
        $logs=$link->query("SELECT * From `gy_tracker` Where `gy_emp_code`='$user_code' AND date(`gy_tracker_date`) BETWEEN '$datef' AND '$datet' Order By `gy_tracker_date` ASC");

        $remark = date("m/d/Y", strtotime($datef))." - ".date("m/d/Y", strtotime($datet));
    }

    $countlogs=$logs->num_rows;

    $info=$link->query("SELECT * From `gy_employee` Where `gy_emp_code`='$user_code'");
    $inforow=$info->fetch_array();

?>

<!DOCTYPE html>
<html lang="en">

<?php include 'head.php'; ?>

<style type="text/css">
    body{
        color: #fff;
    }

    @media print{
        .no-print{
            display: none;
        }
    }
</style>

<body onload="window.print();">
    <div class="page-wrapper">
        <div class="page-content--bge5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <br>
                        <h2 class="title-3 m-b-25 text-center no-print" style="text-transform: lowercase; color: blue;"><i>ctrl + p to print</i></h2>
                        <h2 class="title-3 m-b-25 text-center">Daily Attendance <br>
                            <i style="font-size: 18px;"><?php echo $inforow['gy_emp_code']." - ".$inforow['gy_emp_fullname']; ?> <br> 
                            <span style="color: blue; text-transform: lowercase;"><?php echo $inforow['gy_emp_email'];; ?></span> <br> 
                            <?php echo $remark; ?>
                            </i>
                        </h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-borderless table-data3">
                                    <thead>
                                        <tr>
                                            <th style="padding: 10px; background: #fff; color: #000;" class="text-center">Date</th>
                                            <th style="padding: 10px; background: #fff; color: #000;" class="text-center">IN</th>
                                            <th style="padding: 10px; background: #fff; color: #000;" class="text-center">BO</th>
                                            <th style="padding: 10px; background: #fff; color: #000;" class="text-center">BI</th>
                                            <th style="padding: 10px; background: #fff; color: #000;" class="text-center">OUT</th>
                                            <th style="padding: 10px; background: #fff; color: #000;" class="text-center">WH</th>
                                            <th style="padding: 10px; background: #fff; color: #000;" class="text-center">BH</th>
                                            <th style="padding: 10px; background: #fff; color: #000;" class="text-center">OT</th>
                                            <th style="padding: 10px; background: #fff; color: #000;" class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php  
                                            while ($trackrow=$logs->fetch_array()) {

                                                if ($trackrow['gy_tracker_om'] != 0) {
                                                    //get om details
                                                    $myom=words($trackrow['gy_tracker_om']);
                                                    $getom=$link->query("SELECT `gy_full_name` From `gy_user` Where `gy_user_id`='$myom'");
                                                    $omrow=$getom->fetch_array();

                                                    if ($trackrow['gy_tracker_request'] == "approve") {
                                                        $omname = $omrow['gy_full_name']." <i class='fa fa-check'></i>";
                                                        $statuscolor = "green";
                                                    }else if ($trackrow['gy_tracker_request'] == "reject") {
                                                        $omname = "".$omrow['gy_full_name']." <i class='fa fa-times'></i>";
                                                        $statuscolor = "#dc3545";
                                                    }else if ($trackrow['gy_tracker_request'] == "modify") {
                                                        $omname = "".$omrow['gy_full_name']." <i class='fa fa-edit'></i>";
                                                        $statuscolor = "#007bff";
                                                    }else{
                                                        $omname = "Pending";
                                                        $statuscolor = "#000";
                                                    }
                                                }else{
                                                    $omname = "Pending";
                                                    $statuscolor = "#000";
                                                }

                                        ?>
                                        <tr>
                                            <td style="padding: 0px; background: #fff; color: <?= $statuscolor; ?>;" class="text-center"><?php echo date("m/d/Y", strtotime($trackrow['gy_tracker_date'])); ?></td>
                                            <td style="padding: 0px; background: #fff; color: <?= $statuscolor; ?>;" class="text-center"><?php echo date("g:i A", strtotime($trackrow['gy_tracker_login'])); ?></td>
                                            <td style="padding: 0px; background: #fff; color: <?= $statuscolor; ?>;" class="text-center"><?php echo simptime($trackrow['gy_tracker_breakout'], $trackrow['gy_tracker_date']); ?></td>
                                            <td style="padding: 0px; background: #fff; color: <?= $statuscolor; ?>;" class="text-center"><?php echo simptime($trackrow['gy_tracker_breakin'], $trackrow['gy_tracker_date']); ?></td>
                                            <td style="padding: 0px; background: #fff; color: <?= $statuscolor; ?>;" class="text-center"><?php echo simptime($trackrow['gy_tracker_logout'], $trackrow['gy_tracker_date']); ?></td>
                                            <td style="padding: 0px; background: #fff; color: <?= $statuscolor; ?>;" class="text-center"><?php echo $trackrow['gy_tracker_wh']; ?></td>
                                            <td style="padding: 0px; background: #fff; color: <?= $statuscolor; ?>;" class="text-center"><?php echo $trackrow['gy_tracker_bh']; ?></td>
                                            <td style="padding: 0px; background: #fff; color: <?= $statuscolor; ?>;" class="text-center"><?php echo $trackrow['gy_tracker_ot']; ?></td>
                                            <td style="padding: 0px; background: #fff; color: <?= $statuscolor; ?>;" class="text-center"><i><?php echo $omname; ?></i></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php include 'scripts.php'; ?>

</body>

</html>
<!-- end document-->