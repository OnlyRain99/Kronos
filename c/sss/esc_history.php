<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "Escalate Request History";

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
                                    <strong class="card-title mb-3">History <small class="pull-right" style="font-style: italic;"><span style="color: blue;">20</span> results</small></strong>
                                </div>
                                <div class="card-body">
                                    <form method="post" enctype="multipart/form-data" action="redirect_manager" onsubmit="validateForm(this)"> 
                                    <div class="row">
                                        <div class="col-md-3"> 
                                            <div class="form-group">
                                                <label>From</label>
                                                <input type="date" class="form-control" name="esc_datefrom" id="datefrom" onchange="daterange()" required>
                                            </div>  
                                        </div> 
                                        <div class="col-md-3"> 
                                            <div class="form-group">
                                                <label>To</label>
                                                <input type="date" class="form-control" name="esc_dateto" id="dateto" onchange="daterange()" required>
                                            </div>  
                                        </div> 
                                        <div class="col-md-3"> 
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select name="esc_filter" class="form-control">
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
                                                            <th style="padding: 5px;" class="text-center">Requested By</th>
                                                            <th style="padding: 5px;" class="text-center">Submitted</th>
                                                            <th style="padding: 5px; color: blue;" class="text-center" title="Type of Request">TOR</th>
                                                            <th style="padding: 5px;" class="text-center">Requested For</th>
                                                            <th style="padding: 5px;" class="text-center">Date Affected</th>
                                                            <th style="padding: 5px;" class="text-center">Status</th>
                                                            <th style="padding: 5px;" class="text-center" title="View"><i class="fa fa-eye"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php  
                                                            //get escalate requests
                                                            $request=$link->query("SELECT * From `gy_escalate` LEFT JOIN `gy_user` ON `gy_escalate`.`gy_usercode`=`gy_user`.`gy_user_code` Where `gy_escalate`.`gy_esc_status`!=0 AND `gy_user`.`gy_user_type`<=5 AND `gy_user`.`gy_user_type`!=3 Order By `gy_escalate`.`gy_esc_date` DESC LIMIT 20");
                                                            while ($escrow=$request->fetch_array()) {

                                                                if ($escrow['gy_esc_deny'] != "") {
                                                                    $status = "Denied";
                                                                    $mybg = "mybg_red";
                                                                }else{
                                                                    $status = "Approved";
                                                                    $mybg = "mybg_green";
                                                                }

                                                        ?>

                                                        <tr class="<?= $mybg; ?>">
                                                            <td style="padding: 3px;" class="text-center"><?= getuserfullname($escrow['gy_esc_by']); ?></td>
                                                            <td style="padding: 3px;" class="text-center"><?= date("m/d/Y", strtotime($escrow['gy_esc_date'])); ?></td>
                                                            <td style="padding: 3px; color: blue;" class="text-center"><?php if($escrow['gy_esc_type']==6){ echo "Escalate My Overtime (OT)"; }else{ echo escalate_type($escrow['gy_esc_type']); } ?></td>
                                                            <td style="padding: 3px;;" class="text-center"><?= get_escalate_req_name($escrow['gy_tracker_id']); ?></td>
                                                            <td style="padding: 3px;" class="text-center"><?= date("m/d/Y", strtotime($escrow['gy_tracker_date'])); ?></td>
                                                            <td style="padding: 3px;" class="text-center"><?= $status; ?></td>
                                                            <td style="padding: 0px;" ><a href="view_escalate?cd=<?= $escrow['gy_esc_id']; ?>" class="btn btn-warning btn-block btn-sm" onclick="window.open(this.href, 'mywin',
'left=20,top=20,width=1024,height=720,toolbar=1,resizable=0'); return false;"><button  type="button" title="click to view ..."><i class="fa fa-eye"></i></button></a></td>
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
