<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    $datef = @$_GET['f'];
    $datet = @$_GET['t'];

    $datefrom = date("m/d/Y", strtotime($datef));
    $dateto = date("m/d/Y", strtotime($datet));

    if ($datef == $datet) {
        $finaldate = $datefrom;
    }else{
        $finaldate = $datefrom." - ".$dateto;
    }

    $title = "Escalate Schedule Summary Search: ".$finaldate;

    $query_one = "SELECT * From `gy_schedule_escalate` Where `gy_req_by`='$user_id' AND date(`gy_req_date`) BETWEEN '$datef' AND '$datet' Order By `gy_sched_esc_id` DESC";

    $query_two = "SELECT COUNT(`gy_sched_esc_id`) From `gy_schedule_escalate` Where `gy_req_by`='$user_id' AND date(`gy_req_date`) BETWEEN '$datef' AND '$datet'";

    $query_three = "SELECT * From `gy_schedule_escalate` Where `gy_req_by`='$user_id' AND date(`gy_req_date`) BETWEEN '$datef' AND '$datet' Order By `gy_sched_esc_id` DESC ";

    $my_num_rows = 20;

    include 'my_pagination_dates.php';

    $countres=$link->query($query_one)->num_rows;
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
                            <h2 class="title-1 m-b-25"><?php echo $title; ?> <span style="font-size: 15px; text-transform: lowercase;" class="badge badge-success"><?php echo 0 + $countres; ?> results</span></h2>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <strong class="card-title mb-3">Data Table</strong>
                                </div>
                                <div class="card-body">
                                    <form method="post" enctype="multipart/form-data" action="redirect_manager" onsubmit="validateForm(this)">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>From</label>
                                                <input type="date" name="sched_datefrom" id="datefrom" onchange="daterange()" value="<?= $datef; ?>" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>To</label>
                                                <input type="date" name="sched_dateto" id="dateto" onchange="daterange()" value="<?= $datet; ?>" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label style="color: blue;">*search</label>
                                                <button type="submit" name="submit" id="submit" class="btn btn-primary" title="click to search ..."><i class="fa fa-search"></i> Search</button>
                                            </div>
                                        </div>
                                    </div>
                                    </form>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive m-b-40">
                                                <table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                                    <thead>
                                                        <tr class="mybg" style="text-transform: uppercase;">
                                                            <th rowspan="2" style="padding: 5px; color: blue;" class="text-center">SiBS ID</th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center">Name</th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center">Date</th>
                                                            <th colspan="2" style="padding: 5px;" class="text-center">Current</th>
                                                            <th colspan="2" style="padding: 5px; color: blue;" class="text-center">
                                                            Escalate</th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center">Reason</th>
                                                            <th rowspan="2" style="padding: 5px;" class="text-center"><i class="fa fa-photo"></i></th>
                                                        </tr>
                                                        <tr class="mybg" style="text-transform: uppercase;">
                                                            <th style="padding: 5px;" class="text-center">LI-LO</th>
                                                            <th style="padding: 5px;" class="text-center">Break</th>
                                                            <th style="padding: 5px; color: blue;" class="text-center">LI-LO</th>
                                                            <th style="padding: 5px; color: blue;" class="text-center">Break</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php  
                                                            //get schedules requests
                                                            while ($reqrow=$query->fetch_array()) {

                                                                if ($reqrow['gy_sched_mode'] == 0) {
                                                                    $datacolor = "red";
                                                                    $optstat = "disabled";

                                                                    $login = "OFF";
                                                                    $breakout = "OFF";
                                                                    $breakin = "OFF";
                                                                    $logout = "OFF";
                                                                }else{
                                                                    $datacolor = "#000";
                                                                    $optstat = "";

                                                                    $login = date("g:i A", strtotime($reqrow['gy_sched_login']));
                                                                    $breakout = date("g:i A", strtotime($reqrow['gy_sched_breakout']));
                                                                    $breakin = date("g:i A", strtotime($reqrow['gy_sched_breakin']));
                                                                    $logout = date("g:i A", strtotime($reqrow['gy_sched_logout']));
                                                                }

                                                                if ($reqrow['gy_req_status'] == 0) {
                                                                    $mystatus = "PENDING";
                                                                    $mybg = "mybg";
                                                                }else if ($reqrow['gy_req_status'] == 1){
                                                                    $mystatus = "APPROVED";
                                                                    $mybg = "mybg_green";
                                                                }else if ($reqrow['gy_req_status'] == 2){
                                                                    $mystatus = "DENIED";
                                                                    $mybg = "mybg_red";
                                                                }
                                                        ?>

                                                        <tr class="<?= $mybg; ?>">
                                                            <td style="padding: 1px; color: blue;" class="text-center"><?= $reqrow['gy_emp_code']; ?></td>
                                                            <td style="padding: 1px;" class="text-center"><?= $reqrow['gy_emp_fullname']; ?></td>
                                                            <td style="padding: 1px;" class="text-center"><?= date("m/d/Y", strtotime($reqrow['gy_sched_day'])); ?></td>
                                                            <td style="padding: 1px; font-style: italic;" class="text-center"><?= getlilo($reqrow['gy_sched_day'], $reqrow['gy_emp_code']); ?></td>
                                                            <td style="padding: 1px; font-style: italic;" class="text-center"><?= getbibo($reqrow['gy_sched_day'], $reqrow['gy_emp_code']); ?></td>
                                                            <td style="padding: 1px; color: blue;" class="text-center"><?= $login." - ".$logout; ?></td>
                                                            <td style="padding: 1px; color: blue;" class="text-center"><?= $breakout." - ".$breakin; ?></td>
                                                            <td style="padding: 1px; font-style: italic;" class="text-center"><?= $reqrow['gy_req_reason']; ?></td>
                                                            <td style="padding: 1px; font-style: italic;" class="text-center"><?= $mystatus; ?></td>
                                                            <td style="padding: 1px; font-style: italic;" class="text-center"><button type="button" data-toggle="modal" data-target="#pic_<?= $reqrow['gy_sched_esc_id']; ?>" class="btn btn-success btn-sm"><i class="fa fa-photo"></i></button></td>
                                                        </tr>

                                                        <?php include 'modal_esc.php'; ?>

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
            formObj.submit.innerHTML = "search results ...";
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
