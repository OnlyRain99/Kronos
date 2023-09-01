<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];
    $get_announce=$link->query("SELECT * From `gy_announce` Where `gy_ann_id`='$redirect'");
    $ann=$get_announce->fetch_array();

    $title = "Announcement Content";

    if ($onlydate == date("Y-m-d", strtotime($ann['gy_ann_date']))) {
        $ann_date = "Today";
    }else{
        $ann_date = date("M d, Y", strtotime($ann['gy_ann_date']));
    }$notify = @$_GET['note'];

    if ($notify == "nope") {
        $note = "Incorrect Password";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "confirm") {
        $note = "Announcement Confirmed";
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

    if (check_confirm($ann['gy_ann_id'], $user_code) == "disabled") {
        $seen = "<small title='".get_seen_date($ann['gy_ann_id'], $user_code)."'><i class='fa fa-check'></i> Confirmed</small>";
    }else{
        $seen = "";
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
                                    <strong class="card-title mb-3">Posted by: <span style="color: blue;"><?= getuserfullname($ann['gy_ann_by']) ?></span> at <?= $ann_date." | ".date("g:i A", strtotime($ann['gy_ann_date']))." ".$seen; ?>

                                        <span class="pull-right">POST<span style="color: blue;">ID</span>: <?= $ann['gy_ann_serial']; ?></span>
                                    </strong>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-<?= $ann['gy_ann_type']; ?>" role="alert">
                                                <p>
                                                <?= $ann['gy_ann_caption']; ?>
                                                </p>
                                                <br>
                                                <p>
                                                    <i class="fa fa-paperclip"></i> Attachment:
                                                </p>
                                                    <iframe src="<?= $ann['gy_ann_attachment']; ?>" width="100%" height="500px"></iframe>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-success" title="clik to confirm ..." data-toggle="modal" data-target="#confirm" <?= check_confirm($ann['gy_ann_id'], $user_code); ?> >Confirm <i class="fa fa-check"></i></button>
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

    <div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticModalLabel"><i class="fa fa-key"></i> Confirm Authentication</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" enctype="multipart/form-data" action="confirm_ann?cd=<?php echo $ann['gy_ann_id']; ?>" onsubmit="validateForm(this)">
                <div class="modal-body">
                    <input type="password" name="key" class="form-control" placeholder="my password ..." autofocus required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="confirm" id="submit" class="btn btn-success">Confirm</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'scripts.php'; ?>

    <script type="text/javascript">  
        function validateForm(formObj) {
            formObj.submit.disabled = true; 
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