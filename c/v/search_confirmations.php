<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];
    $filter = @$_GET['filter'];
    $get_announce=$link->query("SELECT * From `gy_announce` Where `gy_ann_id`='$redirect'");
    $ann=$get_announce->fetch_array();

    $title = "Announcement Users Confirmations";

    if ($onlydate == date("Y-m-d", strtotime($ann['gy_ann_date']))) {
        $ann_date = "Today";
    }else{
        $ann_date = date("M d, Y", strtotime($ann['gy_ann_date']));
    }

    if ($filter == "all") {
        $condition = "(`gy_ann_id` IS NULL OR `gy_ann_id`='$redirect') AND";
        $filter_title = "All";
    }else if ($filter == "seen") {
        $condition = "`gy_ann_id`='$redirect' AND";
        $filter_title = "Confirmed";
    }else if ($filter == "unread") {
        $condition = "`gy_ann_id` IS NULL AND";
        $filter_title = "Unread";
    }else{
        $condition = "(`gy_ann_id` IS NULL OR `gy_ann_id`='$redirect') AND";
        $filter_title = "All";
    }

    $query_one = "SELECT `gy_emp_code`,`gy_emp_fullname`,`gy_emp_supervisor`,`gy_conf_date`,`gy_conf_by`,`gy_ann_id` From `gy_employee` LEFT JOIN `gy_confirm` On `gy_employee`.`gy_emp_code`=`gy_confirm`.`gy_conf_by` Where ".$condition." `gy_emp_type` IN ('1','2','3','11','12')";
    $query_two = "SELECT COUNT(`gy_emp_id`) From `gy_employee` LEFT JOIN `gy_confirm` On `gy_employee`.`gy_emp_code`=`gy_confirm`.`gy_conf_by` Where ".$condition." `gy_emp_type` IN ('1','2','3','11','12')";
    $query_three = "SELECT `gy_emp_code`,`gy_emp_fullname`,`gy_emp_supervisor`,`gy_conf_date`,`gy_conf_by`,`gy_ann_id` From `gy_employee` LEFT JOIN `gy_confirm` On `gy_employee`.`gy_emp_code`=`gy_confirm`.`gy_conf_by` Where ".$condition." `gy_emp_type` IN ('1','2','3','11','12') Order By `gy_emp_fullname` ASC ";
    $my_num_rows = 25;

    include 'my_pagination_custom.php';

    //get confirmations
    $seen=0;
    $alerts=$link->query("SELECT `gy_emp_code`,`gy_emp_fullname`,`gy_emp_supervisor`,`gy_conf_date`,`gy_conf_by`,`gy_ann_id` From `gy_employee` LEFT JOIN `gy_confirm` On `gy_employee`.`gy_emp_code`=`gy_confirm`.`gy_conf_by` Where `gy_emp_type` IN ('1','2','3','11','12')");
    $countalerts=$alerts->num_rows;
    while ($alert=$alerts->fetch_array()) {

        if ($alert['gy_ann_id'] == $redirect) {
            $seen++;
        }else{
            $seen=$seen+0;
        }
    }

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

            <!-- MAIN CONTENT-->
            <div class="main-content" style="padding: 20px;">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="title-1 m-b-25"><?php echo $title; ?></h2>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <strong class="card-title mb-3">
                                        Posted by: <span style="color: blue;"><?= getuserfullname($ann['gy_ann_by']) ?></span> at <?= $ann_date." | ".date("g:i A", strtotime($ann['gy_ann_date'])) ?>

                                        <span class="pull-right">POST<span style="color: blue;">ID</span>: <?= $ann['gy_ann_serial']; ?></span>
                                    </strong>
                                </div>
                                <div class="card-body">
                                    <form method="post" enctype="multipart/form-data" action="redirect_manager?cd=<?= $redirect; ?>">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Filters</label>
                                                <select name="filter" class="form-control" onchange="this.form.submit()">
                                                    <option value="<?= $filter; ?>"><?= $filter_title; ?></option>
                                                    <option value="all">All</option>
                                                    <option value="seen">Confirmed</option>
                                                    <option value="unread">Unread</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="table-responsive m-b-40">
                                                <table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                                    <thead>
                                                        <tr class="mybg">
                                                            <th style="padding: 5px;"><i class="fa fa-user"></i> Name (L1, L2, L3)</th>
                                                            <th style="padding: 5px;" class="text-center">Status</th>
                                                            <th style="padding: 5px;" class="text-center">Supervisor</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php  
                                                            //get confirmations
                                                            while ($cons=$query->fetch_array()) {

                                                                if ($cons['gy_ann_id'] == $redirect) {
                                                                    $my_status = "<i class='fa fa-check'></i> Seen ".date("M-d-Y g:i A", strtotime($cons['gy_conf_date']));
                                                                    $my_bg = "mybg_green";
                                                                }else{
                                                                    $my_status = "-";
                                                                    $my_bg = "mybg";
                                                                }

                                                        ?>
                                                        <tr class="<?= $my_bg; ?>">
                                                            <td style="padding: 0px;"><?= $cons['gy_emp_fullname']; ?></td>
                                                            <td style="padding: 0px;" class="text-center"><?= $my_status; ?></td>
                                                            <td style="padding: 0px;" class="text-center"><?= get_supervisor_name($cons['gy_emp_supervisor']); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="table-responsive m-b-40">
                                                <table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                                    <thead>
                                                        <tr class="mybg">
                                                            <th style="padding: 5px;" class="text-center"><i class="fa fa-check"></i> Confirmed</th>
                                                            <th style="padding: 5px;" class="text-center"><i class="fa fa-times"></i> Unread</th>
                                                            <th style="padding: 5px;" class="text-center"><i class="fa fa-user"></i> All</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="mybg">
                                                            <td style="padding: 0px;" class="text-center"><span style="font-size: 20px;" class="badge badge-danger"><?= $seen; ?></span></td>
                                                            <td style="padding: 0px;" class="text-center"><span style="font-size: 20px;" class="badge badge-success"><?= 0 + ($countalerts - $seen); ?></span></td>
                                                            <td style="padding: 0px;" class="text-center"><span style="font-size: 20px;" class="badge badge-info"><?= 0 + $countalerts; ?></span></td>
                                                        </tr>
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

                    <?php include 'footer.php'; ?>
                </div>
            </div>
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
        </div>
    </div>

    <?php include 'scripts.php'; ?>

</body>

</html>
<!-- end document-->