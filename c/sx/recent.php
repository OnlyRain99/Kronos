<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "Announcements";

    $notify = @$_GET['note'];

    if ($notify == "pro_update") {
        $note = "Profile Updated";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "mismatch") {
        $note = "Password Mismatch!";
        $notec = "danger";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "added") {
        $note = "Announcement Added";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "update") {
        $note = "Announcement Updated";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "delete") {
        $note = "Announcement Removed";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "sizelimit") {
        $note = "Attachment must not exceed 5MB ...";
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
                        <div class="col-md-12">
                            <div class="overview-wrap">
                            <h2 class="title-1 m-b-25"><?= $title; ?> <i class="fa fa-bell"></i></h2>
                            </div>
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
                                    <strong class="card-title"><button type="button" class="btn btn-success" data-toggle="modal" data-target="#add" title="click to create announcements ..."><i class="fa fa-plus"></i> Create Post</button></strong>
                                </div>
                                <div class="card-body">
                                <?php  
                                    $get_announce=$link->query("SELECT * From `gy_announce` Where date(`gy_ann_end`) >= '$onlydate' AND `gy_ann_by`='$user_id' Order By `gy_ann_date` DESC");
                                    while ($ann=$get_announce->fetch_array()) {

                                        $alert = $ann['gy_ann_type'];

                                        if ($alert == "success") {
                                            $options = "Simple";
                                        }else if ($alert == "warning") {
                                            $options = "Non-critical";
                                        }else if ($alert == "danger") {
                                            $options = "Critical";
                                        }else if ($alert == "info") {
                                            $options = "Updates";
                                        }else{
                                            $options = "unknown";
                                        }

                                        if ($ann['gy_ann_caption'] == "") {
                                            $captions = "<i>No caption</i>";
                                        }else{
                                            $captions = $ann['gy_ann_caption'];
                                        }

                                        $ann_id = $ann['gy_ann_id'];

                                ?>
                                    <div class="alert alert-<?= $alert; ?>" role="alert">
                                        <span class="badge badge-pill badge-<?= $alert; ?>"><?= date("M d g:i A", strtotime($ann['gy_ann_date'])); ?>
                                        </span>
                                        <span class="badge badge-pill badge-<?= $alert; ?>"><?= getuserfullname($user_id); ?>
                                        </span>
                                        <?= wordlimit($ann['gy_ann_caption'], 60); ?> <a href="content?cd=<?= $ann['gy_ann_id']; ?>" target="_blank" title="click to read whole announcement ..." style="color: blue;" class="alert-link">read more</a>
                                        <span class="pull-right">
                                            <a href="confirmations?cd=<?= $ann_id; ?>" target="_blank"><button type="button" class="btn btn-warning btn-sm" title="click to show users confirmations ..."><i class="fa fa-eye"></i></button></a>
                                            <button type="button" data-toggle="modal" data-target="#edit_<?= $ann['gy_ann_id']; ?>" class="btn btn-info btn-sm" title="click to edit ..."><i class="fa fa-edit"></i></button>
                                            <button type="button" data-toggle="modal" data-target="#delete_<?= $ann['gy_ann_id']; ?>" class="btn btn-danger btn-sm" title="click to delete ..."><i class="fa fa-trash"></i></button>
                                        </span>
                                    </div>

                                    <?php include 'announcement/modal_announce.php';  ?>

                                <?php } ?>
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

    <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediumModalLabel"><i class="fa fa-plus"></i> Post Announcement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" enctype="multipart/form-data" action="announcement/add_announce" onsubmit="return validateForm(this);">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control" name="type" autofocus required>
                                    <option></option>
                                    <option value="success">Simple</option>
                                    <option value="warning">Non-critical</option>
                                    <option value="danger">Critical</option>
                                    <option value="info">Updates</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Post until (date)</label>
                                <input type="date" name="end_date" min="<?= $onlydate; ?>" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Caption</label>
                                <textarea name="caption" class="form-control" placeholder="type your caption here ..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Attachment</label>
                              <input type="file" name="file" class="form-control" accept="image/jpeg,image/gif,image/png,image/x-eps" onchange="readURL(this);">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group text-center">
                                <img src="#" style="width: 150px; height: 200px;" id="my-image" onerror="this.onerror=null; this.src='../../images/icon/image.png'">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="add" id="submit" class="btn btn-success">Post</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'scripts.php'; $link->close(); ?>

    <script type="text/javascript">
        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#my-image')
                        .attr('src', e.target.result)
                        .width(150)
                        .height(200);
                };

                reader.readAsDataURL(input.files[0]);
            }
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
