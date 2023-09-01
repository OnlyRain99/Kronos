<?php  
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';
    
    $title = "Leave Requests";

    $notify = @$_GET['note'];

    if ($notify == "approve") {
        $note = "Leave Approved";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "move") {
        $note = "Leave successfully transfered";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "reject") {
        $note = "Leave successfully rejected";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "not_allowed") {
        $note = "Request Denied! <b>no plotted leave</b> please check your leave calendar";
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
                            <h2 class="title-1 m-b-25"><?php echo $title; ?> <i class="fa fa-list"></i></h2>
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
                                    <strong class="card-title mb-3" style="text-transform: uppercase;"><span style="color: blue;"><?= 0 + get_leave_pending_requests(getall_leave_level3($user_id)); ?></span> pending requests <small class="pull-right"><i style="color: red;">filed below 14 days (marks red)</i></small></strong>
                                </div>
                                <div class="card-body">
                                    <div class="row">
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
                                                            <th style="padding: 3px; color: #000;" class="text-center"><i class="fa fa-eye"></i></th>
                                                            <th style="padding: 3px; color: #000;" class="text-center"><i class="fa fa-check"></i></th>
                                                            <th style="padding: 3px; color: #000;" class="text-center"><i class="fa fa-edit"></i></th>
                                                            <th style="padding: 3px; color: #000;" class="text-center"><i class="fa fa-times"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php  
                                                        //get leave info
                                                        $num=0;
                                                        $leaves=$link->query("SELECT * From `gy_leave` Where `gy_leave_status`='0' AND `gy_user_id` IN ('".$my_emps."') Order By `gy_leave_filed` ASC");
                                                        while ($leave=$leaves->fetch_array()) {

                                                            $num++;
                                                            
                                                            if (strtotime(date("Y-m-d", strtotime($leave['gy_leave_filed']. "+14 days"))) > strtotime($leave['gy_leave_date_from'])) {
                                                                $my_bg = "mybg_red";
                                                            }else{
                                                                $my_bg = "mybg";
                                                            }

                                                            if ($leave['gy_leave_type'] == "1") {
                                                                $move_btn = "";
                                                                $move_modal = "#move_".$leave['gy_leave_id'];
                                                            }else{
                                                                $move_btn = "disabled";
                                                                $move_modal = "#";
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
                                                            <td style="padding: 0px;" class="text-center"><button type="button" data-toggle="modal" data-target="#reason_<?php echo $leave['gy_leave_id']; ?>" class="btn btn-warning btn-sm" title="click to show more ..."><i class="fa fa-eye"></i></button></td>
                                                            <td style="padding: 0px;" class="text-center"><button type="button" data-toggle="modal" data-target="#approve_<?php echo $leave['gy_leave_id']; ?>" class="btn btn-success btn-sm" title="click to approve ..."><i class="fa fa-check"></i></button></td>
                                                            <td style="padding: 0px;" class="text-center"><button type="button" data-toggle="modal" data-target="<?= $move_modal; ?>" class="btn btn-info btn-sm" title="click to move request ..." <?= $move_btn; ?> ><i class="fa fa-edit"></i></button></td>
                                                            <td style="padding: 0px;" class="text-center"><button type="button" data-toggle="modal" data-target="#reject_<?php echo $leave['gy_leave_id']; ?>" class="btn btn-danger btn-sm" title="click to deny ..."><i class="fa fa-times"></i></button></td>
                                                        </tr>

                                                        <?php include 'modal_leave_request.php'; ?>

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
        function validateForm(formObj) {
            formObj.submit.disabled = true;
            formObj.submit.innerHTML = "processing ...";
            return true;  
        }  
    </script>

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

</body>

</html>
<!-- end document-->
