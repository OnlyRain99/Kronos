<?php  
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';
    
    $title = "Plot Leave";

    $notify = @$_GET['note'];

    if ($notify == "added") {
        $note = "Plotted Successfully";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "update") {
        $note = "Update Successfully";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "delete") {
        $note = "Deleted";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "duplicate") {
        $note = "Duplicate Entry is not allowed";
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

    $query_one = "SELECT * From `gy_leave_available` Where `gy_leave_avail_date`>='$onlydate' Order By `gy_leave_avail_id` ASC";

    $query_two = "SELECT COUNT(`gy_leave_avail_id`) From `gy_leave_available` Where `gy_leave_avail_date`>='$onlydate' Order By `gy_leave_avail_id` ASC";

    $query_three = "SELECT * From `gy_leave_available` Where `gy_leave_avail_date`>='$onlydate' Order By `gy_leave_avail_id` ASC ";

    $my_num_rows = 25;

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
                            <h2 class="title-1 m-b-25"><?php echo $title; ?> <i class="far fa-calendar-plus"></i></h2>
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
                                    <strong class="card-title mb-3" style="text-transform: uppercase;">leave data status <span class="pull-right"><button type="button" data-toggle="modal" data-target="#add" class="btn btn-success btn-sm" title="click to plot ..."><i class="far fa-calendar-plus"></i> plot leave</button></span></strong>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                                    <thead style="background: #fff; text-transform: uppercase;">
                                                        <tr class="mybg">
                                                            <th style="padding: 3px; color: #000;" class="text-center">date</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center">account</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center">plotted</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center">approved</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center">remaining</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center"><i class="fa fa-eye"></i></th>
                                                            <th style="padding: 3px; color: #000;" class="text-center"><i class="fa fa-edit"></i></th>
                                                            <th style="padding: 3px; color: #000;" class="text-center"><i class="fa fa-trash"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php  
                                                        //get leave info
                                                        while ($avail=$query->fetch_array()) {
                                                    ?>
                                                        <tr class="mybg">
                                                            <td style="padding: 0px;" class="text-center"><?= date("M d, Y", strtotime($avail['gy_leave_avail_date'])); ?></td>
                                                            <td style="padding: 0px;" class="text-center"><?= get_acc_name($avail['gy_acc_id']); ?></td>
                                                            <td style="padding: 0px;" class="text-center"><?= $avail['gy_leave_avail_plotted']; ?></td>
                                                            <td style="padding: 0px;" class="text-center"><?= $avail['gy_leave_avail_approved']; ?></td>
                                                            <td style="padding: 0px;" class="text-center"><?= ($avail['gy_leave_avail_plotted'] - $avail['gy_leave_avail_approved']); ?></td>
                                                            <td style="padding: 0px;" class="text-center"><button type="button" data-toggle="modal" data-target="#show_<?php echo $avail['gy_leave_avail_id']; ?>" class="btn btn-warning btn-sm" title="click to show more ..."><i class="fa fa-eye"></i></button></td>
                                                            <td style="padding: 0px;" class="text-center"><button type="button" data-toggle="modal" data-target="#edit_<?= $avail['gy_leave_avail_id']; ?>" class="btn btn-info btn-sm" title="click to edit ..."><i class="fa fa-edit"></i></button></td>
                                                            <td style="padding: 0px;" class="text-center"><button type="button" data-toggle="modal" data-target="#delete_<?= $avail['gy_leave_avail_id']; ?>" class="btn btn-danger btn-sm" title="click to edit ..."><i class="fa fa-trash"></i></button></td>
                                                        </tr>

                                                        <?php include 'modal_plot.php'; ?>

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

                    <div class="row">
                        <div class="col-md-12">
                            <div class="container" style="color: #000;">
                                <div id="calendar"></div>
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
                <div class="modal-header">
                    <h5 class="modal-title" id="mediumModalLabel"><i class="far fa-calendar-alt"></i> Plot Leave</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" enctype="multipart/form-data" action="plot" onsubmit="return validateForm(this);">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Account</label>
                                <select name="account" class="form-control" autofocus required>
                                    <option></option>
                                    <?php  
                                        //get accounts
                                        $getaccounts=$link->query("SELECT * From `gy_accounts` Order By `gy_acc_name` ASC");
                                        while ($accrow=$getaccounts->fetch_array()) {
                                    ?>
                                    <option value="<?= $accrow['gy_acc_id']; ?>"><?= $accrow['gy_acc_name']; ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" class="form-control" name="plot_date" min="<?= date('Y-m-d', strtotime('+14 days')); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Slots</label>
                                <input type="number" class="form-control" name="plot_slot" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Justification</label>
                                <textarea name="plot_justify" class="form-control" rows="3" placeholder="type your reason here ..." required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="add" id="submit" class="btn btn-success"><i class="fa fa-paper-plane"></i> Execute</button>
                </div>
                </form>
            </div>
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
        $("#activate-alert").fadeTo(5000, 500).slideUp(500, function(){
            $("#activate-alert").slideUp(500);
        });
    </script>

</body>

</html>
<!-- end document-->
