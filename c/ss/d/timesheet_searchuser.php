<?php
    include '../../../config/conn.php';
    $count_usr = 0;
    $accid = addslashes($_REQUEST['accid']);
    $usrsql=$link->query("SELECT `gy_emp_code`,`gy_emp_fullname` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user`.`gy_user_status`=0 AND `gy_acc_id`='$accid' ORDER BY `gy_emp_fullname` ASC");
    if(mysqli_num_rows($usrsql) > 0){ ?>
<option value=""></option>
<option value="0">All</option>
<?php } while($usrrow=$usrsql->fetch_array()){ ?>
<option value="<?php echo $usrrow['gy_emp_code']; ?>"><?php echo $usrrow['gy_emp_fullname']; ?></option>
<?php } ?>