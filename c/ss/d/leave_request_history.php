<?php  
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';
    
    $title = "Leave Request History";

    $notify = @$_GET['note'];

    if ($notify == "empty") {
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

    $my_emps = getall_leave_level3($user_id);

    $query_one = "SELECT * From `gy_leave` Where `gy_leave_status`!='0' AND `gy_user_id` IN ('".$my_emps."') Order By `gy_leave_filed` DESC";

    $query_two = "SELECT COUNT(`gy_leave_id`) From `gy_leave` Where `gy_leave_status`!='0' AND `gy_user_id` IN ('".$my_emps."') Order By `gy_leave_filed` DESC";

    $query_three = "SELECT * From `gy_leave` Where `gy_leave_status`!='0' AND `gy_user_id` IN ('".$my_emps."') Order By `gy_leave_filed` DESC ";

    $my_num_rows = 20;

    include 'my_pagination.php';
?>

<!DOCTYPE html>
<html lang="en">

<?php  
    include 'head.php';
?>

<style type="text/css">
    .fc-event, .fc-event-dot {
        background-color: #fff;
    }
</style>

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
                            <h2 class="title-1 m-b-25"><?php echo $title; ?> <i class="fa fa-folder-open"></i></h2>
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
                                    <strong class="card-title mb-3" style="text-transform: uppercase;"><span style="color: blue;"><?= 0 + get_leave_request_history(getall_leave_level3($user_id)); ?></span> requests</strong>
                                </div>
                                <div class="card-body">
                                    <form method="post" enctype="multipart/form-data" action="redirect_manager" onsubmit="validateForm(this)">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="text" name="search_leave_request" class="form-control" placeholder="search name and press ENTER ..." autofocus required>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                                    <thead style="background: #fff; text-transform: uppercase;">
                                                        <tr class="mybg">
                                                            <th style="padding: 3px; color: #000;" class="text-center">no.</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center">name</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center">date filed</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center">type</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center">date from</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center">date to</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center">no. of days</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center">status</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center"><i class="fa fa-eye"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php  
                                                        //get leave info
                                                        $num=0;
                                                        while ($leave=$query->fetch_array()) {

                                                            $num++;

                                                            if ($leave['gy_leave_status'] == 0) {
                                                                $my_leave_status = "Pending";
                                                                $my_bg = "mybg";
                                                                $my_date_app = "";
                                                            }else if ($leave['gy_leave_status'] == 1) {
                                                                $my_leave_status = "Approved";
                                                                $my_bg = "mybg_green";
                                                                $my_date_app = date("M d, Y g:i A", strtotime($leave['gy_leave_date_approved']));
                                                            }else if ($leave['gy_leave_status'] == 2) {
                                                                $my_leave_status = "Rejected";
                                                                $my_bg = "mybg_red";
                                                                $my_date_app = date("M d, Y g:i A", strtotime($leave['gy_leave_date_approved']));
                                                            }else {
                                                                $my_leave_status = "Cancelled";
                                                                $my_bg = "mybg_yellow";
                                                                $my_date_app = date("M d, Y g:i A", strtotime($leave['gy_leave_date_approved']));
                                                            }
                                                    ?>
                                                        <tr class="<?= $my_bg; ?>">
                                                            <td style="padding: 0px;" class="text-center"> <?= $num; ?> </td>
                                                            <td style="padding: 0px;" class="text-center"> <?= getuserfullname($leave['gy_user_id']); ?> </td>
                                                            <td style="padding: 0px;" class="text-center" title="<?= 'Time: '.date('g:i A', strtotime($leave['gy_leave_filed'])); ?>"><?= date("M d, Y", strtotime($leave['gy_leave_filed'])); ?></td>
                                                            <td style="padding: 0px;" class="text-center"><?= get_leave_type($leave['gy_leave_type']); ?></td>
                                                            <td style="padding: 0px;" class="text-center"><?= date("M d, Y", strtotime($leave['gy_leave_date_from'])); ?></td>
                                                            <td style="padding: 0px;" class="text-center"><?= date("M d, Y", strtotime($leave['gy_leave_date_to'])); ?></td>
                                                            <td style="padding: 0px;" class="text-center"><?= get_no_of_days($leave['gy_leave_date_from'], $leave['gy_leave_date_to']); ?></td>
                                                            <td style="padding: 0px;" class="text-center" title="<?= $my_date_app; ?>"><?= $my_leave_status; ?></td>
                                                            <td style="padding: 0px;" class="text-center"><button type="button" data-toggle="modal" data-target="#reason_<?php echo $leave['gy_leave_id']; ?>" class="btn btn-warning btn-sm" title="click to show more ..."><i class="fa fa-eye"></i></button></td>
                                                        </tr>

                                                        <div class="modal fade" id="reason_<?php echo $leave['gy_leave_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-sm" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-warning">
                                                                        <h5 class="modal-title" id="smallmodalLabel" style="text-transform: uppercase;">reason</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="card">
                                                                                    <div class="card-body bg-warning">
                                                                                        <p>
                                                                                            <center><b>REASON</b></center>
                                                                                            <?= $leave['gy_leave_reason']; ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="card">
                                                                                    <div class="card-body bg-info">
                                                                                        <p>
                                                                                            <center><b>REMARKS</b></center>
                                                                                            <?= $leave['gy_leave_remarks']; ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                                    </form>

                                    <div class="row">
                                        <div class="col-md-12" style="margin-top: 10px;">
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
        $("#activate-alert").fadeTo(5000, 500).slideUp(500, function(){
            $("#activate-alert").slideUp(500);
        });
    </script>

</body>

</html>
<!-- end document-->
