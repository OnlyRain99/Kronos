<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "Escalate Requests";

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
    }else if ($notify == "duplicated") {
       $note = "The requested date was already approved by their supervisor and anymore changes is not allowed.";
        $notec = "danger";
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

    $datestrt = date("Y-m-d H:i:s");
    if(date("d")<=5){ $datestrt = date("Y-m-16 00:00:00", strtotime("-1 Month")); }
    else if(date("d")>=1 && date("d")<=20){ $datestrt = date("Y-m-01 00:00:00"); }
    else if(date("d")>=16){ $datestrt = date("Y-m-16 00:00:00"); }

    $request=$link->query("SELECT * From `gy_escalate` LEFT JOIN `gy_user` ON `gy_escalate`.`gy_usercode`=`gy_user`.`gy_user_code` Where `gy_escalate`.`gy_esc_status`='0' AND `gy_user`.`gy_user_type`<=5  AND `gy_user`.`gy_user_type`!=3 AND `gy_escalate`.`gy_tracker_date`>='$datestrt' Order By `gy_escalate`.`gy_esc_date` ASC");
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
                                                        <tr class="mybg">
                                                            <th style="padding: 5px;" class="text-center">Requested By</th>
                                                            <th style="padding: 5px;" class="text-center">Submitted</th>
                                                            <th style="padding: 5px; color: blue;" class="text-center" title="Type of Request">Type Of Request</th>
                                                            <th style="padding: 5px;" class="text-center">Request For</th>
                                                            <th style="padding: 5px;" class="text-center">Date Affected</th>
                                                            <th style="padding: 0px;" title="View"><button class="btn btn-block btn-sm"><i class="fa fa-eye"></i></button></th>
                                                            <th style="padding: 0px;" title="Approve"><button class="btn btn-block btn-sm"><i class="fa fa-check"></i></button></th>
                                                            <th style="padding: 0px;" title="Deny"><button class="btn btn-block btn-sm"><i class="fa fa-times"></i></button></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php  
                                                            //get escalate requests
                                                            while ($escrow=$request->fetch_array()) {

                                                        ?>

                                                        <tr class="mybg">
                                                            <td style="padding: 3px;" class="text-center"><?= getuserfullname($escrow['gy_esc_by']); ?></td>
                                                            <td style="padding: 3px;" class="text-center"><?= date("m/d/Y", strtotime($escrow['gy_esc_date'])); ?></td>
                                                            <td style="padding: 3px; color: blue;" class="text-center"><?php if($escrow['gy_esc_type']==6){ echo "Escalate My Overtime (OT)"; }else{ echo escalate_type($escrow['gy_esc_type']); } ?></td>
                                                            <td style="padding: 3px;;" class="text-center"><?= get_escalate_req_name($escrow['gy_tracker_id']); ?></td>
                                                            <td style="padding: 3px;" class="text-center"><?= date("m/d/Y", strtotime($escrow['gy_tracker_date'])); ?></td>
                                                            <td style="padding: 0px;"><a href="view_escalate?cd=<?= $escrow['gy_esc_id']; ?>" class="btn btn-warning btn-sm btn-block" onclick="window.open(this.href, 'mywin',
'left=20,top=20,width=1024,height=720,toolbar=1,resizable=0'); return false;"><button type="button" title="click to view ..."><i class="fa fa-eye"></i></button></a></td>
                                                            <td style="padding: 0px;"><button type="button" data-toggle="modal" data-target="#approve_<?= $escrow['gy_esc_id']; ?>" class="btn btn-success btn-sm btn-block" title="click to approve ..."><i class="fa fa-check"></i></button></td>
                                                            <td style="padding: 0px;"><button type="button" data-toggle="modal" data-target="#deny_<?= $escrow['gy_esc_id']; ?>" class="btn btn-danger btn-sm btn-block" title="click to deny ..."><i class="fa fa-times"></i></button></td>
                                                        </tr>

                                                        <?php include 'modal_escalate.php'; ?>

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
