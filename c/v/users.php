<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "System Users";

    $notify = @$_GET['note'];

    if ($notify == "update") {
        $note = "User Updated";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "added") {
        $note = "User Added";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "delete") {
        $note = "User Deleted";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "mismatch") {
        $note = "Password Mismatch!";
        $notec = "danger";
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

    $query_one = "SELECT * From `gy_user` Where `gy_user_id`!='$user_id' AND `gy_user_id`!='1' Order By `gy_full_name` ASC";

    $query_two = "SELECT COUNT(`gy_user_id`) FROM `gy_user` Where `gy_user_id`!='$user_id' AND `gy_user_id`!='1' Order By `gy_full_name` ASC";

    $query_three = "SELECT * from `gy_user` Where `gy_user_id`!='$user_id' AND `gy_user_id`!='1' Order By `gy_full_name` ASC ";

    $my_num_rows = 25;

    //get accounts
    $info=$link->query("SELECT * From `gy_user` Where `gy_user_id`!='$user_id' AND `gy_user_id`!='1' Order By `gy_full_name` ASC");
    $countinfo=$info->num_rows;

    include 'my_pagination.php';
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

                    <form method="post" enctype="multipart/form-data" action="redirect_manager">
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <button type="button" data-toggle="modal" data-target="#add" class="btn btn-primary btn-block" title="click to add test ... "><i class="fa fa-plus"></i> Add User</button>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <input type="text" name="user_search" class="form-control" placeholder="search name/username here ..." autofocus required>
                            </div>
                        </div>
                    </div>
                    </form>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive m-b-40">
                                <table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                    <thead>
                                        <tr class="mybg">
                                            <th>Name</th>
                                            <th>Username</th>
                                            <th class="text-center">edit</th>
                                            <th class="text-center">delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php  
                                            //get resultset
                                            while ($usersrow=$query->fetch_array()) {
                                        ?>
                                        <tr class="mybg">
                                            <td style="padding: 0px;"><?php echo $usersrow['gy_full_name']; ?></td>
                                            <td style="padding: 0px;"><?php echo $usersrow['gy_username']; ?></td>
                                            <td class="text-center" style="padding: 0px;"><button type="button" data-toggle="modal" data-target="#edit_<?php echo $usersrow['gy_user_id']; ?>" class="btn btn-info btn-sm" title="click to edit ..."><i class="fa fa-edit"></i></button></td>
                                            <td class="text-center" style="padding: 0px;"><button type="button" data-toggle="modal" data-target="#delete_<?php echo $usersrow['gy_user_id']; ?>" class="btn btn-danger btn-sm" title="click to delete ..."><i class="fa fa-trash"></i></button></td>
                                        </tr>

                                        <div class="modal fade" id="edit_<?php echo $usersrow['gy_user_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="mediumModalLabel"><?php echo $usersrow['gy_full_name']; ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form method="post" enctype="multipart/form-data" action="update_user?cd=<?php echo $usersrow['gy_user_id']; ?>" onsubmit="return validateForm(this);">
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Name</label>
                                                                    <input type="text" class="form-control" name="name" maxlength="255" placeholder="..." value="<?php echo $usersrow['gy_full_name']; ?>" autofocus required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Username</label>
                                                                    <input type="text" class="form-control" maxlength="16" name="username" placeholder="Ex. juandeluna" value="<?php echo $usersrow['gy_username']; ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Password</label>
                                                                    <input type="password" class="form-control" maxlength="16" name="password1" placeholder="********" value="<?php echo decryptIt($usersrow['gy_password']); ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Re-Type Password</label>
                                                                    <input type="password" class="form-control" maxlength="16" name="password2" placeholder="********" value="<?php echo decryptIt($usersrow['gy_password']); ?>" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" name="update" id="update" class="btn btn-info">Confirm</button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="delete_<?php echo $usersrow['gy_user_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true"
                                         data-backdrop="static">
                                            <div class="modal-dialog modal-sm" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticModalLabel"><?php echo $usersrow['gy_full_name']; ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>
                                                            Do you want to delete this user?
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <a href="delete_user?cd=<?php echo $usersrow['gy_user_id']; ?>"><button type="button" class="btn btn-danger">Confirm</button></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

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
                    <h5 class="modal-title" id="mediumModalLabel">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" enctype="multipart/form-data" action="add_user" onsubmit="return validateForm(this);">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" name="name" maxlength="255" placeholder="..." autofocus required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" maxlength="16" name="username" placeholder="Ex. juandeluna" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" maxlength="16" name="password1" placeholder="********" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Re-Type Password</label>
                                <input type="password" class="form-control" maxlength="16" name="password2" placeholder="********" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="add" id="update" class="btn btn-primary">Confirm</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'scripts.php'; ?>

    <script type="text/javascript">  
        function validateForm(formObj) {
            formObj.update.disabled = true; 
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
