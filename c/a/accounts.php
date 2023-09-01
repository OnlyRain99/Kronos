<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "Company Division";

    $notify = @$_GET['note'];

    if ($notify == "update") {
        $note = "Item Updated";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "added") {
        $note = "Item Added";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "delete") {
        $note = "Item Deleted";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "invalid") {
        $note = "Invalid";
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

    //get active accounts
    $info=$link->query("SELECT * From `gy_accounts` LEFT JOIN `gy_department` ON `gy_accounts`.`gy_dept_id`=`gy_department`.`id_department` Where `gy_accounts`.`gy_acc_status`=0 Order By `gy_department`.`name_department` ASC, `gy_accounts`.`gy_acc_name` ASC");
    $countinfo=$info->num_rows;

    //get inactive accounts
    $i=0; $inaidarr=array(); $inanmarr=array(array());
    $inasql=$link->query("SELECT * From `gy_accounts` Where `gy_acc_status`=1 Order by `gy_acc_name` ASC");
    while ($inarow=$inasql->fetch_array()){ $inaidarr[$i]=$inarow['gy_acc_id']; $inanmarr[0][$i]=$inarow['gy_acc_name']; $inanmarr[1][$i]=$inarow['gy_dept_id']; $i++; }

    $i=0; $dptidarr=array(); $dptnmarr=array();
    $dptsql=$link->query("SELECT * From `gy_department` Order By `name_department` ASC");
    while ($dptrow=$dptsql->fetch_array()){ $dptidarr[$i]=$dptrow['id_department']; $dptnmarr[$i]=$dptrow['name_department']; $i++; }
?>

<!DOCTYPE html>
<html lang="en">
<?php  include 'head.php'; ?>

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
                            <h2 class="title-1 m-b-25"><?php echo $title; ?> <i class="fas fa-briefcase"></i></h2>
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
                        <div class="col-lg-7">
                            <div class="form-group">
                                <button type="button" data-toggle="modal" data-target="#add" class="btn btn-primary btn-block" title="click to add test ... "><i class="fa fa-plus"></i> Add Account</button>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <button type="button" data-toggle="modal" data-target="#add_dept" class="btn btn-primary btn-block"><i class="fa fa-plus"></i> Add Department</button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-7">
                            <div class="card">
                                <div class="card-header"></div>
                                <div class="card-body">
                                    <div class="table-responsive m-b-40">
                                        <table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                            <thead>
                                                <tr class="mybg">
                                                    <th class="text-center">Account</th>
                                                    <th class="text-center">Department</th>
                                                    <th class="text-center"><i class="fa fa-edit"></i></th>
                                                    <th class="text-center"><i class="fa fa-trash"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php  
                                                    //get resultset
                                                    $num=0;
                                                    while ($acc=$info->fetch_array()) {
                                                        $num++;
                                                ?>
                                                <tr class="mybg">
                                                    <td class="text-center" style="padding: 5px;"><?php echo $acc['gy_acc_name']; ?></td>
                                                    <td class="text-center" style="padding: 5px;"><?php for($i=0;$i<count($dptidarr);$i++){ if($dptidarr[$i]==$acc['gy_dept_id']){ echo $dptnmarr[$i]; break; } } ?></td>
                                                    <td class="text-center" style="padding: 0px;"><button type="button" data-toggle="modal" data-target="#edit_<?php echo $acc['gy_acc_id']; ?>" class="btn btn-info btn-sm btn-block" title="click to edit ..."><i class="fa fa-edit"></i></button></td>
                                                    <td class="text-center" style="padding: 0px;"><button type="button" data-toggle="modal" data-target="#delete_<?php echo $acc['gy_acc_id']; ?>" class="btn btn-danger btn-sm btn-block" title="click to delete ..." <?php if($acc['gy_acc_id']==22||$acc['gy_acc_id']==11){ echo "disabled"; } ?>><i class="fa fa-trash"></i></button></td>
                                                </tr>

                                                <div class="modal fade" id="edit_<?php echo $acc['gy_acc_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-sm" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="mediumModalLabel"><i class="fa fa-edit"></i> Edit </h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form method="post" enctype="multipart/form-data" action="update_account?cd=<?= $acc['gy_acc_id']; ?>" onsubmit="return validateForm(this);">
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label>Account</label>
                                                                            <input type="text" class="form-control" name="account" maxlength="255" placeholder="..." value="<?= $acc['gy_acc_name']; ?>" autofocus required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label>Department</label>
                                                                            <select name="accdpt" class="form-select" alert-label="Default select" required>
                                                                                <?php for($i=0;$i<count($dptidarr);$i++){ ?>
                                                                                <option value="<?php echo $dptidarr[$i]; ?>" <?php if($dptidarr[$i]==$acc['gy_dept_id']){ echo "selected"; } ?>><?php echo $dptnmarr[$i]; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row"> 
                                                                    <div class="col-md-12"> 
                                                                        <div class="form-group"> 
                                                                            <label>Status</label>
                                                                            <select name="sttacc" class="form-select" required>     <option value="0">Active</option>
                                                                                 <option value="1">Deactivate</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" name="update" class="btn btn-info">Confirm</button>
                                                            </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if($acc['gy_acc_id']!=22 && $acc['gy_acc_id']!=11){ ?>
                                                <div class="modal fade" id="delete_<?php echo $acc['gy_acc_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-sm" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="staticModalLabel"><?php echo $acc['gy_acc_name']; ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>
                                                                    Do you want to delete this item?
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                <a href="delete_account?cd=<?php echo $acc['gy_acc_id']; ?>"><button type="button" class="btn btn-danger">Confirm</button></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php }} ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                             <div class="card">
                                <div class="card-header"></div>
                                <div class="card-body">
                                    <div class="table-responsive m-b-40">
                                        <table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                            <thead>
                                                <tr class="mybg">
                                                    <th class="text-center">Department</th>
                                                    <th class="text-center"><i class="fa fa-edit"></i></th>
                                                    <th class="text-center"><i class="fa fa-trash"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php for($i=0;$i<count($dptidarr);$i++){ ?>
                                                <tr class="mybg">
                                                    <td class="text-center" style="padding: 5px;"><?php echo $dptnmarr[$i]; ?></td>
                                                    <td class="text-center" style="padding: 0px;"><button type="button" data-toggle="modal" data-target="#editdpt_<?php echo $dptidarr[$i]; ?>" class="btn btn-info btn-sm btn-block" title="click to edit ..."><i class="fa fa-edit"></i></button></td>
                                                    <td class="text-center" style="padding: 0px;"><button type="button" data-toggle="modal" <?php if($dptidarr[$i]!=2&&$dptidarr[$i]!=3){ ?> data-target="#deletedpt_<?php echo $dptidarr[$i]; ?>" <?php }else{ echo "disabled"; } ?> class="btn btn-danger btn-sm btn-block" title="click to delete ..."><i class="fa fa-trash"></i></button></td>
                                                </tr>

                                                <div class="modal fade" id="editdpt_<?php echo $dptidarr[$i]; ?>" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-sm" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="mediumModalLabel"><i class="fa fa-edit"></i> Edit </h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form method="post" enctype="multipart/form-data" action="update_department?cd=<?= $dptidarr[$i]; ?>" onsubmit="return validateForm(this);">
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label>Department</label>
                                                                            <input type="text" class="form-control" name="editdept" maxlength="255" placeholder="..." value="<?= $dptnmarr[$i]; ?>" autofocus required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" name="update" class="btn btn-info">Confirm</button>
                                                            </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="deletedpt_<?php echo $dptidarr[$i]; ?>" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-sm" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="staticModalLabel"><?php echo $dptnmarr[$i]; ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>
                                                                    Do you want to delete this item?
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                <a href="delete_department?cd=<?php echo $dptidarr[$i]; ?>"><button type="button" class="btn btn-danger">Confirm</button></a>
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
                            <div class="card">
                                <div class="card-header"><h5>Disabled Accounts</h5></div>
                                <div class="card-body">
                                <div class="table-responsive m-b-40">
                                    <table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                        <thead>
                                            <tr class="mybg">
                                                <th class="text-center">Account</th>
                                                <th class="text-center"><i class="fas fa-thumbs-up"></i></th>
                                                <th class="text-center"><i class="fa fa-trash"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php for($i=0;$i<count($inaidarr);$i++){ ?>
                                        <tr class="mybg">
                                            <td class="text-center" style="padding: 5px;"><?php echo $inanmarr[0][$i]; ?></td>
                                            <td class="text-center" style="padding: 0px;"><button type="button" data-toggle="modal" data-target="#editina_<?php echo $inaidarr[$i]; ?>" class="btn btn-success btn-sm btn-block" title="click to reactivate ..."><i class="fas fa-thumbs-up"></i></button></td>
                                            <td class="text-center" style="padding: 0px;"><button type="button" data-toggle="modal" data-target="#deleteina_<?php echo $inaidarr[$i]; ?>" class="btn btn-danger btn-sm btn-block" title="click to delete ..."><i class="fa fa-trash"></i></button></td>
                                        </tr>

                                        <div class="modal fade" id="editina_<?php echo $inaidarr[$i]; ?>" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-sm" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="mediumModalLabel"><i class="fas fa-thumbs-up"></i> Confirmation </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <form method="post" enctype="multipart/form-data" action="update_account?cd=<?= $inaidarr[$i]; ?>" onsubmit="return validateForm(this);">
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Do you want to reactivate the <b><?php echo $inanmarr[0][$i]; ?></b> account?</label>
                                                                <input type="hidden" name="account" value="<?php echo $inanmarr[0][$i]; ?>" required>
                                                                <input type="hidden" name="accdpt" value="0" required>
                                                                <input type="hidden" name="sttacc" value="0" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" name="update" class="btn btn-success">Activate</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="deleteina_<?php echo $inaidarr[$i]; ?>" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-sm" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticModalLabel"><?php echo $inanmarr[0][$i]; ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <div class="modal-body"><p>Do you want to delete this item?</p></div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <a href="delete_account?cd=<?php echo $inaidarr[$i]; ?>"><button type="button" class="btn btn-danger">Confirm</button></a>
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
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediumModalLabel">Add New Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" enctype="multipart/form-data" action="add_account" onsubmit="return validateForm(this);">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Account</label>
                                <input type="text" class="form-control" name="account" maxlength="255" placeholder="..." autofocus required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Department</label>
                                <select name="accdpt" class="form-select" alert-label="Default select" autofocus required>
                                    <?php for($i=0;$i<count($dptidarr);$i++){ ?>
                                    <option value="<?php echo $dptidarr[$i]; ?>"><?php echo $dptnmarr[$i]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="add" class="btn btn-primary">Confirm</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add_dept" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediumModalLabel">Add New Department</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" enctype="multipart/form-data" action="add_department" onsubmit="return validateForm(this);">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Department</label>
                                <input type="text" class="form-control" name="department" maxlength="255" placeholder="..." autofocus required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="dept" class="btn btn-primary">Confirm</button>
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
<?php $link->close();?>
</html>
<!-- end document-->
