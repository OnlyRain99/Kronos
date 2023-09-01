<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    $datef = @$_GET['datef'];
    $datet = @$_GET['datet'];

    if ($datef == $datet) {
        $title = "Schedule Request Summary Search: ".date("m/d/Y", strtotime($datef));
    }else{
        $title = "Schedule Request Summary Search: ".date("m/d/Y", strtotime($datef))." - ".date("m/d/Y", strtotime($datet));
    }

    $request=$link->query("SELECT `gy_req_id`,`gy_req_code`,`gy_req_date`,`gy_req_status` From `gy_request` Where `gy_req_by`='$user_id' AND `gy_req_status`!='0' AND date(`gy_req_date`) BETWEEN '$datef' AND '$datet' GROUP BY (`gy_req_code`) LIMIT 20");

    $countsched=$request->num_rows;
    
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
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <strong class="card-title mb-3">Data Table - <span style="font-style: italic; color: blue;"><?= $countsched; ?> results</span> <small class="pull-right" style="font-style: italic;">limit to <span style="color: blue;">20</span> rows</small></strong>
                                </div>
                                <div class="card-body">
                                    <form method="post" enctype="multipart/form-data" action="redirect_manager">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>From</label>
                                                <input type="date" name="datefrom" id="datefrom" onchange="daterange()" value="<?= $datef; ?>" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>To</label>
                                                <input type="date" name="dateto" id="dateto" onchange="daterange()" value="<?= $datet; ?>" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label style="color: blue;">*search</label>
                                                <button type="submit" name="search_process_date" id="submit" class="btn btn-primary" title="click to search ..."><i class="fa fa-search"></i> Search</button>
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
                                                            <th style="padding: 5px; color: blue;" class="text-center">PROCESS ID</th>
                                                            <th style="padding: 5px;" class="text-center">Date</th>
                                                            <th style="padding: 5px;" class="text-center">Status</th>
                                                            <th style="padding: 5px;" class="text-center" title="click to remove ..."><i class="fa fa-eye"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php  
                                                            //get schedules requests
                                                            while ($reqrow=$request->fetch_array()) {

                                                                if ($reqrow['gy_req_status'] == 1) {
                                                                    $status = "PENDING";
                                                                    $status_color = "red";
                                                                }else{
                                                                    $status = "ACTIVE";
                                                                    $status_color = "green";
                                                                }
                                                        ?>

                                                        <tr class="mybg">
                                                            <td style="padding: 1px; color: blue;" class="text-center"><?= $reqrow['gy_req_code']; ?></td>
                                                            <td style="padding: 1px;" class="text-center"><?= date("m/d/Y", strtotime($reqrow['gy_req_date'])); ?></td>
                                                            <td style="padding: 1px; color: <?= $status_color; ?>;" class="text-center"><?= $status; ?></td>
                                                            <td style="padding: 1px;" class="text-center"><a href="view_schedule?cd=<?= $reqrow['gy_req_code'] ?>" onclick="window.open(this.href, 'mywin',
'left=20,top=20,width=1280,height=720,toolbar=1,resizable=0'); return false;"><button type="button" class="btn btn-warning btn-sm" title="click to remove"><i class="fa fa-eye"></i></button></a></td>
                                                        </tr>

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
