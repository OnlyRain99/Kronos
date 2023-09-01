<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    $f = @$_GET['f'];
    $t = @$_GET['t'];
    $fil = @$_GET['fil'];

    $datefrom = date("m/d/Y", strtotime($f));
    $dateto = date("m/d/Y", strtotime($t));

    if ($f == $t) {
        $finaldate = $datefrom;
    }else{
        $finaldate = $datefrom." - ".$dateto;
    }

    if ($fil == "all") {
        $filter_title = "All";
        $filter = "`gy_esc_status`!='0'";
    }else if ($fil == 1) {
        $filter_title = "Approved";
        $filter = "`gy_esc_status`='1'";
    }else if ($fil == 2) {
        $filter_title = "Denied";
        $filter = "`gy_esc_status`='2'";
    }else{
        $filter_title = "unknown";
        $filter = "`gy_esc_status`!='0'";
    }

    $title = "Search: ".$finaldate." - ".$filter_title;

    $query_one = "SELECT * From `gy_escalate` Where `gy_esc_by`='$user_id' AND ".$filter." AND `gy_esc_date` BETWEEN '$f' AND '$t' Order By `gy_esc_date` ASC";

    $query_two = "SELECT COUNT(`gy_esc_id`) From `gy_escalate` Where `gy_esc_by`='$user_id' AND ".$filter." AND `gy_esc_date` BETWEEN '$f' AND '$t'";

    $query_three = "SELECT * From `gy_escalate` Where `gy_esc_by`='$user_id' AND ".$filter." AND `gy_esc_date` BETWEEN '$f' AND '$t' Order By `gy_esc_date` ASC ";

    $my_num_rows = 20;

    include 'my_pagination_search_history.php';

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
                                    <strong class="card-title mb-3">History <small class="pull-right" style="font-style: italic;"><span style="color: blue;"><?= 0 + $countres; ?></span> results</small></strong>
                                </div>
                                <div class="card-body">
                                    <form method="post" enctype="multipart/form-data" action="redirect_manager" onsubmit="validateForm(this)"> 
                                    <div class="row">
                                        <div class="col-md-3"> 
                                            <div class="form-group">
                                                <label>From</label>
                                                <input type="date" class="form-control" name="esc_datefrom" id="datefrom" value="<?= $f; ?>" onchange="daterange()" required>
                                            </div>  
                                        </div> 
                                        <div class="col-md-3"> 
                                            <div class="form-group">
                                                <label>To</label>
                                                <input type="date" class="form-control" name="esc_dateto" id="dateto" value="<?= $t; ?>" onchange="daterange()" required>
                                            </div>  
                                        </div> 
                                        <div class="col-md-3"> 
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select name="esc_filter" class="form-control">
                                                    <option value="<?= $fil; ?>"><?= $filter_title; ?></option>
                                                    <option value="all">All</option>
                                                    <option value="1">Approved</option>
                                                    <option value="2">Denied</option>
                                                </select>
                                            </div>  
                                        </div>
                                        <div class="col-md-2"> 
                                            <div class="form-group">
                                                <label style="color: blue;">*click search</label>
                                                <button type="submit" name="submit" id="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                                            </div>
                                        </div>
                                    </div>
                                    </form>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive m-b-40">
                                                <table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                                    <thead>
                                                        <tr class="mybg">
                                                            <th style="padding: 5px;" class="text-center">LEVEL 3</th>
                                                            <th style="padding: 5px;" class="text-center">Submitted</th>
                                                            <th style="padding: 5px; color: blue;" class="text-center" title="Type of Request">TOR</th>
                                                            <th style="padding: 5px;" class="text-center">Requested By</th>
                                                            <th style="padding: 5px;" class="text-center">Date Affected</th>
                                                            <th style="padding: 5px;" class="text-center">Status</th>
                                                            <th style="padding: 5px;" class="text-center" title="View"><i class="fa fa-eye"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php  
                                                            //get escalate requests
                                                            while ($escrow=$query->fetch_array()) {

                                                                if ($escrow['gy_esc_deny'] != "") {
                                                                    $status = "Denied<br><i>".$escrow['gy_esc_deny']."</i>";
                                                                    $mybg = "mybg_red";
                                                                }else{
                                                                    $status = "Approved";
                                                                    $mybg = "mybg_green";
                                                                }

                                                        ?>

                                                        <tr class="<?= $mybg; ?>">
                                                            <td style="padding: 1px;" class="text-center"><?= getuserfullname($escrow['gy_esc_by']); ?></td>
                                                            <td style="padding: 1px;" class="text-center"><?= date("m/d/Y", strtotime($escrow['gy_esc_date'])); ?></td>
                                                            <td style="padding: 1px; color: blue;" class="text-center"><?= escalate_type($escrow['gy_esc_type']); ?></td>
                                                            <td style="padding: 1px;;" class="text-center"><?= get_escalate_req_name($escrow['gy_tracker_id']); ?></td>
                                                            <td style="padding: 1px;" class="text-center"><?= date("m/d/Y", strtotime($escrow['gy_tracker_date'])); ?></td>
                                                            <td style="padding: 1px;" class="text-center"><?= $status; ?></td>
                                                            <td style="padding: 1px;" class="text-center"><a href="view_escalate?cd=<?= $escrow['gy_esc_id']; ?>" onclick="window.open(this.href, 'mywin',
'left=20,top=20,width=1024,height=720,toolbar=1,resizable=0'); return false;"><button type="button" class="btn btn-warning btn-sm" title="click to view ..."><i class="fa fa-eye"></i></button></a></td>
                                                        </tr>

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
            formObj.submit.innerHTML = "searching ...";
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
