<?php  
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';
    
    $title = "Leave Application";

    $notify = @$_GET['note'];

    if ($notify == "filed") {
        $note = "Your leave is on process ...";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "cancel") {
        $note = "Your leave is successfully cancelled ...";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "not_allowed") {
        $note = "Request Denied! <b>no plotted leave</b> please check your leave calendar";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "invalid") {
        $note = "Invalid";
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

    $query_one = "SELECT * From `gy_leave` Where `gy_user_id`='$user_id' Order By `gy_leave_id` DESC";

    $query_two = "SELECT COUNT(`gy_leave_id`) From `gy_leave` Where `gy_user_id`='$user_id' Order By `gy_leave_id` DESC";

    $query_three = "SELECT * From `gy_leave` Where `gy_user_id`='$user_id' Order By `gy_leave_id` DESC ";

    $my_num_rows = 5;

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
                            <h2 class="title-1 m-b-25"><?php echo $title; ?> <i class="far fa-calendar-times"></i></h2>
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
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <strong class="card-title mb-3" style="text-transform: uppercase;"><center>Summary</center></strong>
                                </div>
                                <div class="card-body">
                                    <form method="post" enctype="multipart/form-data" action="redirect_manager" onsubmit="validateForm(this)">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                                    <thead style="background: #fff; text-transform: uppercase;">
                                                        <tr class="mybg">
                                                            <th style="padding: 3px; color: #000;">Leave Credits</th>
                                                            <th style="padding: 3px; color: #000; color: blue;" class="text-center"><?= get_leave_credits($user_code); ?></th>
                                                        </tr>
                                                        <tr class="mybg">
                                                            <th style="padding: 3px; color: #000;">Pending Leave Applications</th>
                                                            <th style="padding: 3px; color: #000; color: #966100;" class="text-center"><?= get_leave_pending_count($user_id) ?></th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <strong class="card-title mb-3" style="text-transform: uppercase;">My Leaves <span class="pull-right"><button type="button" data-toggle="modal" data-target="#add" class="btn btn-success btn-sm" title="click to file a leave ..."><i class="fa fa-plus"></i> File a Leave</button></span></strong>
                                </div>
                                <div class="card-body">
                                    <form method="post" enctype="multipart/form-data" action="redirect_manager" onsubmit="validateForm(this)">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                                    <thead style="background: #fff; text-transform: uppercase;">
                                                        <tr class="mybg">
                                                            <th style="padding: 3px; color: #000;" class="text-center">date filed</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center">type</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center">date from</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center">date to</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center">no. of days</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center">status</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center"><i class="fa fa-eye"></i></th>
                                                            <th style="padding: 3px; color: #000;" class="text-center"><i class="fa fa-times"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php  
                                                        //get leave info
                                                        while ($leave=$query->fetch_array()) {

                                                            if ($leave['gy_leave_status'] == 0) {
                                                                $my_leave_status = "Pending";
                                                                $my_bg = "mybg";
                                                                $my_date_app = "";

                                                                if ($leave['gy_leave_type'] == 2) {
                                                                    $cancel_btn = "";
                                                                    $modal = "#cancel_".$leave['gy_leave_id'];
                                                                }else if (strtotime($onlydate) >= strtotime($leave['gy_leave_date_from'])) {
                                                                    $cancel_btn = "disabled";
                                                                    $modal = "#";
                                                                }else{
                                                                    $cancel_btn = "";
                                                                    $modal = "#cancel_".$leave['gy_leave_id'];
                                                                }

                                                            }else if ($leave['gy_leave_status'] == 1) {
                                                                $my_leave_status = "Approved";
                                                                $my_bg = "mybg_green";
                                                                $my_date_app = date("M d, Y g:i A", strtotime($leave['gy_leave_date_approved']));

                                                                if ($leave['gy_leave_type'] == 2) {
                                                                    $cancel_btn = "";
                                                                    $modal = "#cancel_".$leave['gy_leave_id'];
                                                                }else if (strtotime($onlydate) >= strtotime($leave['gy_leave_date_from'])) {
                                                                    $cancel_btn = "disabled";
                                                                    $modal = "#";
                                                                }else{
                                                                    $cancel_btn = "";
                                                                    $modal = "#cancel_".$leave['gy_leave_id'];
                                                                }

                                                            }else if ($leave['gy_leave_status'] == 2) {
                                                                $my_leave_status = "Rejected";
                                                                $my_bg = "mybg_red";
                                                                $my_date_app = date("M d, Y g:i A", strtotime($leave['gy_leave_date_approved']));
                                                                $cancel_btn = "disabled";
                                                                $modal = "#";
                                                            }else {
                                                                $my_leave_status = "Cancelled";
                                                                $my_bg = "mybg_yellow";
                                                                $my_date_app = date("M d, Y g:i A", strtotime($leave['gy_leave_date_approved']));
                                                                $cancel_btn = "disabled";
                                                                $modal = "#";
                                                            }
                                                    ?>
                                                        <tr class="<?= $my_bg; ?>">
                                                            <td style="padding: 0px;" class="text-center" title="<?= 'Time: '.date('g:i A', strtotime($leave['gy_leave_filed'])); ?>"><?= date("M d, Y", strtotime($leave['gy_leave_filed'])); ?></td>
                                                            <td style="padding: 0px;" class="text-center"><?= get_leave_type($leave['gy_leave_type']); ?></td>
                                                            <td style="padding: 0px;" class="text-center"><?= date("M d, Y", strtotime($leave['gy_leave_date_from'])); ?></td>
                                                            <td style="padding: 0px;" class="text-center"><?= date("M d, Y", strtotime($leave['gy_leave_date_to'])); ?></td>
                                                            <td style="padding: 0px;" class="text-center"><?= get_no_of_days($leave['gy_leave_date_from'], $leave['gy_leave_date_to']); ?></td>
                                                            <td style="padding: 0px;" class="text-center" title="<?= $my_date_app; ?>"><?= $my_leave_status; ?></td>
                                                            <td style="padding: 0px;" class="text-center"><button type="button" data-toggle="modal" data-target="#reason_<?php echo $leave['gy_leave_id']; ?>" class="btn btn-warning btn-sm" title="click to view ..."><i class="fa fa-eye"></i></button></td>
                                                            <td style="padding: 0px;" class="text-center"><button type="button" data-toggle="modal" data-target="<?= $modal; ?>" class="btn btn-danger btn-sm" title="click to cancel ..." <?= $cancel_btn; ?> ><i class="fa fa-times"></i></button></td>
                                                        </tr>

                                                        <?php include 'modal_leave.php'; ?>

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

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div id="calendar"></div>
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

    <!-- Modals -->

    <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white" id="mediumModalLabel"><i class="fa fa-plus"></i> File a Leave</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" enctype="multipart/form-data" action="file_leave" onsubmit="return validateForm(this);">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Leave Credits: <span style="color: blue; font-weight: bold;"><?= get_leave_credits($user_code); ?></span> credits</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Type</label>
                                <select name="leave_type" id="leave_type" onchange="leave_date_restriction()" class="form-control" required>
                                    <option></option>
                                    <option value="1">Vacation/Personal Leave</option>
                                    <option value="2">Sick Leave</option>
                                    <option value="3">Maternal Leave</option>
                                    <option value="4">Paternal Leave</option>
                                    <option value="5">Solo Parent Leave</option>
                                    <option value="6">Force Leave</option>
                                    <option value="7">Indifinite Leave</option>
                                    <option value="8">Quarantine Leave</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date (From)</label>
                                <input type="date" class="form-control" name="leave_date_from" id="datefrom" onchange="daterange()" autofocus required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date (To)</label>
                                <input type="date" class="form-control" name="leave_date_to" id="dateto" onchange="daterange()" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Reason</label>
                                <textarea name="leave_reason" class="form-control" rows="3" placeholder="type your reason here ..." required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Attachment <i>(required for applying Sick Leave)</i></label>
                                <input type="file" name="file" id="file" class="form-control" accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="add" id="submit" class="btn btn-success"><i class="fa fa-paper-plane"></i> File Now</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'scripts.php'; ?>

    <script type="text/javascript">  
        function leave_date_restriction(){
            var l_type = _getID('leave_type').value;

            if (l_type == "") {
                _getID('datefrom').min = "<?= date('Y-m-d', strtotime('+14 days')); ?>";
            }else if ((l_type == 2) || (l_type == 3) || (l_type == 4) || (l_type == 6) || (l_type == 7) || (l_type == 8)) {
                _getID('datefrom').min = "";
            }else{
                _getID('datefrom').min = "<?= date('Y-m-d', strtotime('+14 days')); ?>";
            }

            if (l_type == 2) {
                _getID('file').required = true;
            }else{
                _getID('file').required = false;
            }
        }

        function validateForm(formObj) {
            formObj.submit.disabled = true;
            formObj.submit.innerHTML = "Filing Leave ...";
            return true;  
        }  

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

        $("#activate-alert").fadeTo(5000, 500).slideUp(500, function(){
            $("#activate-alert").slideUp(500);
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <script>
      $(document).ready(function() {
       var calendar = $('#calendar').fullCalendar({
        editable:false,
        header:{
         left:'prev,next today',
         center:'title',
         right:'month,agendaWeek,agendaDay'
        },
        events: 'load.php',
        selectable:true,
        selectHelper:true,
        select: function(start, end, allDay){},

       });
      });
    </script>

</body>

</html>
<!-- end document-->
