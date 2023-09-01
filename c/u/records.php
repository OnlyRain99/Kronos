<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "My Daily Logs";

    $notify = @$_GET['note'];

    if ($notify == "pro_update") {
        $note = "Profile Updated";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "rd_apply") {
        $note = "Pending request ...";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "mismatch") {
        $note = "Password Mismatch!";
        $notec = "danger";
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

    $sdate = words(date("m"));

    $query_one = "SELECT * From `gy_tracker` Where `gy_emp_code`='$user_code' Order By `gy_tracker_date` DESC";

    $query_two = "SELECT COUNT(`gy_tracker_id`) From `gy_tracker` Where `gy_emp_code`='$user_code' Order By `gy_tracker_date` DESC";

    $query_three = "SELECT * From `gy_tracker` Where `gy_emp_code`='$user_code' Order By `gy_tracker_date` DESC ";

    $my_num_rows = 20;

    //get accounts
    $tracker=$link->query("SELECT * From `gy_tracker` Where `gy_emp_code`='$user_code' Order By `gy_tracker_date` DESC");
    $counttrack=$tracker->num_rows;

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
                    <form method="post" enctype="multipart/form-data" action="redirect_manager" onsubmit="return validateForm(this);">

                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="title-1 m-b-25"><?php echo $title; ?> <span style="font-size: 15px; text-transform: lowercase;" class="badge badge-success"><?php echo 0 + $counttrack; ?> results</span></h2>
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
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>From <span style="color: red;">*required</span></label>
                                <input type="date" name="dtrfrom" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>To <span style="color: red;">*required</span></label>
                                <input type="date" name="dtrto" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label style="color: red;">*click &nbsp;</label>
                            <button type="submit" name="submit" id="submit" class="btn btn-success" title="click to search ..."><i class="fa fa-search"></i> Search</button>
                        </div>
                        <!-- <div class="col-md-2">
                            <label style="color: blue;">*print &nbsp;</label>
                            <a href="print_record?mode=normal" onclick="window.open(this.href, 'mywin',
'left=20,top=20,width=1280,height=720,toolbar=1,resizable=0'); return false;"><button type="button" class="btn btn-primary" title="click to print ..."><i class="fa fa-print"></i> Print Record</button></a>
                        </div> -->
                    </div>
                    </form>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    
                                </div>
                                <div class="card-body">
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
                                                    while ($trackrow=$query->fetch_array()) {

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

                    <div class="row">
                        <div class="col-md-12">
                            <div class="text-center"> 
                                 <ul class="pagination">
                                    <?php echo $paginationCtrls; ?>
                                 </ul>
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
