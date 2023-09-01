<?php  
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "Masterlist Record - ".date("m/d/Y");

    $sdate = words(date("Y-m-d"));

    $mode = @$_GET['mode'];
    $s = @$_GET['s'];
    $f = @$_GET['f'];
    $t = @$_GET['t'];
    $a = @$_GET['a'];

    if ($mode == "normal") {
        //get accounts
        $logs=$link->query("SELECT * From `gy_logs` Where date(`gy_log_date`)='$sdate' Order By `gy_log_date` DESC");
    }else{
        $datefrom = date("m/d/Y", strtotime($f));
        $dateto = date("m/d/Y", strtotime($t));

        if ($f == $t) {
            $finaldate = $datefrom;
        }else{
            $finaldate = $datefrom." - ".$dateto;
        }

        if ($s != "" && $f != "" && $t != "" && $a != "") {

            //all fields search
            $title = "Masterlist Record Search: ".$s." - ".$finaldate." - ".$a;

            $logs=$link->query("SELECT * From `gy_logs` Where CONCAT(`gy_log_email`,`gy_log_fullname`,`gy_log_code`) LIKE '%$s%' AND `gy_log_account`='$a' AND date(`gy_log_date`) BETWEEN '$f' AND '$t' Order By `gy_log_date` DESC");
        }else if ($s != "" && $f != "" && $t != "" && $a == "") {

            //search and dates
            $title = "Masterlist Record Search: ".$s." - ".$finaldate;

            $logs=$link->query("SELECT * From `gy_logs` Where CONCAT(`gy_log_email`,`gy_log_fullname`,`gy_log_code`) LIKE '%$s%' AND date(`gy_log_date`) BETWEEN '$f' AND '$t' Order By `gy_log_date` DESC");
        }else if ($s != "" && $f == "" && $t == "" && $a != "") {

            //search and account
            $title = "Masterlist Record Search: ".$s." - ".$a;

            $logs=$link->query("SELECT * From `gy_logs` Where CONCAT(`gy_log_email`,`gy_log_fullname`,`gy_log_code`) LIKE '%$s%' AND `gy_log_account`='$a' Order By `gy_log_date` DESC");
        }else if ($s == "" && $f != "" && $t != "" && $a != "") {

            //dates and account
            $title = "Masterlist Record Search: ".$finaldate." - ".$a;

            $logs=$link->query("SELECT * From `gy_logs` Where `gy_log_account`='$a' AND date(`gy_log_date`) BETWEEN '$f' AND '$t' Order By `gy_log_date` DESC");
        }if ($s != "" && $f == "" && $t == "" && $a == "") {

            //search
            $title = "Masterlist Record Search: ".$s;

            $logs=$link->query("SELECT * From `gy_logs` Where CONCAT(`gy_log_email`,`gy_log_fullname`,`gy_log_code`) LIKE '%$s%' Order By `gy_log_date` DESC");
        }else if ($s == "" && $f == "" && $t == "" && $a != "") {

            //account
            $title = "Masterlist Record Search: ".$a;

            $logs=$link->query("SELECT * From `gy_logs` Where `gy_log_account`='$a' Order By `gy_log_date` DESC");
        }if ($s == "" && $f != "" && $t != "" && $a == "") {

            //dates
            $title = "Masterlist Record Search: ".$finaldate;

            $logs=$link->query("SELECT * From `gy_logs` Where date(`gy_log_date`) BETWEEN '$f' AND '$t' Order By `gy_log_date` DESC");
        }
    }

    $countlogs=$logs->num_rows;

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
                        <h2 class="title-3 m-b-25 text-center"><?php echo $title; ?></h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr style="background: #fff; color: #000;">
                                        <th style="padding: 10px" class="text-center">Date</th>
                                        <th style="padding: 10px" class="text-center">Time</th>
                                        <th style="padding: 10px" class="text-center">Status</th>
                                        <th style="padding: 10px">ID</th>
                                        <th style="padding: 10px">Email</th>
                                        <th style="padding: 10px">Fullname</th>
                                        <th style="padding: 10px">Account</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php  
                                        while ($logrow=$logs->fetch_array()) {

                                            if ($logrow['gy_log_status'] == "Log Out") {
                                                $markings = "bold";
                                            }else{
                                                $markings = "normal";
                                            }
                                    ?>
                                    <tr style="font-family: 'Calibri'; font-size: 17px; font-weight: <?php echo $markings; ?>;">
                                        <td style="padding: 0px; background: #fff; color: #000;" class="text-center"><?php echo date("m/d/Y", strtotime($logrow['gy_log_date'])); ?></td>
                                        <td style="padding: 0px; background: #fff; color: #000;" class="text-center"><?php echo date("g:i A", strtotime($logrow['gy_log_date'])); ?></td>
                                        <td style="padding: 0px; background: #fff; color: #000;" class="text-center"><?php echo $logrow['gy_log_status']; ?></td>
                                        <td style="padding: 0px; background: #fff; color: #000;"><?php echo $logrow['gy_log_code']; ?></td>
                                        <td style="padding: 0px; background: #fff; color: #000;"><?php echo $logrow['gy_log_email']; ?></td>
                                        <td style="padding: 0px; background: #fff; color: #000;"><?php echo $logrow['gy_log_fullname']; ?></td>
                                        <td style="padding: 0px; background: #fff; color: #000;"><?php echo $logrow['gy_log_account']; ?></td>
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