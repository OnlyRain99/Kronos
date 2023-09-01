<?php
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "Event Calendar";  
    $notes = "display: none;";

if ($user_type == 6 && $user_dept == 2) {

    if(isset($_POST['addholtype'])){
        $holtypnym = words($_POST['addholtype']);
        if($holtypnym != ""){
            $newsql = $link->query("INSERT INTO `gy_holiday_types`(`gy_hol_type_name`) Values ('$holtypnym')");
            if($newsql){
                $note = "New Holiday Type was Added";
                $notec = "success";
                $notes = "";
                $noteid = "activate-alert";
            }else{
                $note = "Error Adding a New Holiday Type";
                $notec = "danger";
                $notes = "";
                $noteid = "activate-alert";
            }
        }
    }else if(isset($_POST['holtypnmid'])){
        $holtypnmid = words($_POST['holtypnmid']);
        $holtypnm = words($_POST['holtypnm']);
        $delsql=$link->query("DELETE FROM `gy_holiday_types` Where `gy_hol_type_id`='$holtypnmid' AND `gy_hol_type_id`!=1 AND `gy_hol_type_id`!=2 ");
        if($delsql && $holtypnmid!=1 && $holtypnmid!=2){
                $note = $holtypnm." has been removed";
                $notec = "info";
                $notes = "";
                $noteid = "activate-alert";
            }else{
                $note = "Error removing ".$holtypnm;
                $notec = "warning";
                $notes = "";
                $noteid = "activate-alert";
            }
    }
?>
<!DOCTYPE html>
<html lang="en">
<?php  include 'head.php'; ?>
<body>
    <div class="page-wrapper">
        <?php include 'header-m.php'; ?>
        <?php include 'sidebar.php'; ?>
        <div class="page-container">
            <div class="main-content" style="padding: 20px;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12"><h2 class="title-1 m-b-25"><i class="fas fa-calendar-alt"></i> <?php echo $title; ?></h2></div>
                            <div style="<?php echo $notes; ?>" id="<?php echo $noteid; ?>" class="sufee-alert alert with-close alert-<?php echo $notec; ?> alert-dismissible fade show">
                                <span class="badge badge-pill badge-<?php echo $notec; ?>">Alert</span>
                                <?php echo $note; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        <ul class="nav nav-tabs">
                            <li class="nav-item"><a class="nav-link active" href="eventcalendar">Calendar</a></li>
                            <li class="nav-item"><a class="nav-link active" aria-current="page" href="plot_calendar">Plot Holiday</a></li>
                            <li class="nav-item"><a class="nav-link active" href="#"><strong>Holiday Type</strong></a></li>
                            <li class="nav-item"><a class="nav-link active" href="event_rules">Holiday Policy</a></li>
                        </ul>

                        <div class="card">
                        <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <form method="post" enctype="multipart/form-data" action="event_type">
                                <div class="input-group mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="addholtype" name="addholtype" placeholder="Holiday Type Name" required>
                                    <label for="addholtype">Holiday Type Name</label>
                                </div>
                                <button name="hidbtn_submit" class="btn btn-outline-secondary" type="submit"><i class="fas fa-plus"></i> ADD</button>
                                </div>
                                </form>
                            </div>
                        <?php $htsql=$link->query("SELECT * FROM `gy_holiday_types` WHERE `gy_hol_status`!=0"); while($htrow=$htsql->fetch_array()){ ?>
                            <div class="col-md-4">
                            <div class="alert alert-secondary alert-dismissible fade show" role="alert">
                                <?php echo $htrow['gy_hol_type_name']; ?>
                                <button type="button" class="btn-close" aria-label="Close" onclick="confrmrem(<?php echo "'".$htrow['gy_hol_type_id']."', '".$htrow['gy_hol_type_name']."'"; ?>)"></button>
                            </div>
                            </div>
                        <?php } ?>
                        </div>
                        </div>
                        </div>
                        </div>
                    </div>
                    <?php include 'footer.php'; } $link->close(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php include 'scripts.php'; ?>
    <script type="text/javascript">
        function validateForm(formObj) {
            formObj.submit.disabled = true; 
            return true;  
        }

        function confrmrem(id, name){
            Swal.fire({
                title: 'Confirm remove '+name+'?',
                html: '<form method="post" id="sbmtfrm" enctype="multipart/form-data" action="event_type"><input type="hidden" name="holtypnmid" value="'+id+'"><input type="hidden" name="holtypnm" value="'+name+'"></form>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Remove',
                width: 'auto'
            }).then((result) => {
                if (result.isConfirmed){
                    document.getElementById('sbmtfrm').submit();
                }
            })
        }
    </script>
</body>
</html>