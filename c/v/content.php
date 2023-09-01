<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];
    $get_announce=$link->query("SELECT * From `gy_announce` Where `gy_ann_id`='$redirect'");
    $ann=$get_announce->fetch_array();

    $title = "Announcement Content";

    if ($onlydate == date("Y-m-d", strtotime($ann['gy_ann_date']))) {
        $ann_date = "Today";
    }else{
        $ann_date = date("M d, Y", strtotime($ann['gy_ann_date']));
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
        $("#activate-alert").fadeTo(5000, 500).slideUp(500, function(){
            $("#activate-alert").slideUp(500);
        });
    </script>

</body>

</html>
<!-- end document-->