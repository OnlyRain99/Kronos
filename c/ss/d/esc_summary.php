<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    $title = "Escalate Queue";

    $notify = @$_GET['note'];

    if ($notify == "delete") {
        $note = "Request removed";
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

    $request=$link->query("SELECT `gy_esc_id`,`gy_esc_date`,`gy_escalate`.`gy_tracker_date`,`gy_emp_code`,`gy_emp_fullname`,`gy_esc_type`,`gy_esc_status` From `gy_escalate` LEFT JOIN `gy_tracker` On `gy_escalate`.`gy_tracker_id`=`gy_tracker`.`gy_tracker_id` Where `gy_esc_by`='$user_id' AND `gy_esc_status`='0' Order By `gy_esc_id` DESC");
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
                                                            <th style="padding: 5px;" class="text-center">Submitted</th>
                                                            <th style="padding: 5px;" class="text-center">Requested By</th>
                                                            <th style="padding: 5px; color: blue;" class="text-center">TOR</th>
                                                            <th style="padding: 5px;" class="text-center">Status</th>
                                                            <th style="padding: 5px;" class="text-center">Date Affected</th>
                                                            <th style="padding: 5px;" class="text-center" title="View"><i class="fa fa-eye"></i></th>
                                                            <th style="padding: 5px;" class="text-center" title="click to remove ..."><i class="fa fa-times"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php  
                                                            //get schedules requests
                                                            while ($reqrow=$request->fetch_array()) {

                                                                if ($reqrow['gy_esc_status'] == 0) {
                                                                    $status = "ESCALATING";
                                                                    $status_color = "#bf5700";
                                                                }else{
                                                                    $status = "ACTIVE";
                                                                    $status_color = "green";
                                                                }

                                                                $type = escalate_type($reqrow['gy_esc_type']);

                                                        ?>

                                                        <tr class="mybg">
                                                            <td style="padding: 1px; color: <?= $status_color; ?>" class="text-center"><?= date("m/d/Y", strtotime($reqrow['gy_tracker_date'])); ?></td>
                                                            <td style="padding: 1px; color: <?= $status_color; ?>" class="text-center"><?= $reqrow['gy_emp_fullname']; ?></td>
                                                            <td style="padding: 1px; color: <?= $status_color; ?>" class="text-center"><?= $type; ?></td>
                                                            <td style="padding: 1px; color: <?= $status_color; ?>;" class="text-center"><?= $status; ?></td>
                                                            <td style="padding: 1px; color: <?= $status_color; ?>" class="text-center"><?= date("m/d/Y", strtotime($reqrow['gy_esc_date'])); ?></td>
                                                            <td style="padding: 1px;" class="text-center"><a href="view_escalate?cd=<?= $reqrow['gy_esc_id']; ?>" onclick="window.open(this.href, 'mywin',
'left=20,top=20,width=1024,height=720,toolbar=1,resizable=0'); return false;"><button type="button" class="btn btn-warning btn-sm" title="click to review ..."><i class="fa fa-eye"></i></button></a></td>
                                                            <td style="padding: 1px;" class="text-center"><button type="button" data-toggle="modal" data-target="#delete_<?= $reqrow['gy_esc_id']; ?>" class="btn btn-danger btn-sm" title="click to delete/remove ..."><i class="fa fa-times"></i></button></td>
                                                        </tr>

                                                        <div class="modal fade" id="delete_<?= $reqrow['gy_esc_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-sm" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="staticModalLabel"><i class="fa fa-trash"></i> Delete</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p>
                                                                            Do you want to remove <span style="color: blue;"><?= $reqrow['gy_emp_fullname']; ?></span> <?= $type; ?> request? <br><br>

                                                                            NOTE: This time log (<?= date("m/d/Y", strtotime($reqrow['gy_tracker_date'])); ?>) will be marked as <span style="color: blue;">PENDING</span> once removed.
                                                                        </p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                        <a href="delete_esc?cd=<?php echo $reqrow['gy_esc_id']; ?>"><button type="button" class="btn btn-danger">Confirm</button></a>
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
