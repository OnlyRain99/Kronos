<?php
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $count_usr = 0;
    $accid = addslashes($_REQUEST['accid']);
if($accid=="all"){
	$i=0;
	$acntarr=array();
    //$myagtsql=$link->query("SELECT `gy_acc_id` FROM `gy_employee` WHERE `gy_emp_supervisor`='$user_id'");
    //while($myagtrow=$myagtsql->fetch_array()){
    //    if(!in_array($myagtrow['gy_acc_id'], $acntarr)){ $acntarr[$i]=$myagtrow['gy_acc_id']; $i++; }
    //}

    $dptsql=$link->query("SELECT * From `gy_department` ORDER BY `id_department` ASC");
    while($dptrow=$dptsql->fetch_array()){
        $depsql=$link->query("SELECT * From `gy_accounts` Where `gy_dept_id`='".$dptrow['id_department']."' AND `gy_acc_status`=0 ORDER BY `gy_acc_name` ASC");
        while($deprow=$depsql->fetch_array()){
            $acntarr[$i]=$deprow['gy_acc_id']; $i++;
        }
    }

	$usrsql=$link->query("SELECT `gy_emp_code`,`gy_emp_fullname` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user`.`gy_user_status`=0 AND `gy_acc_id` IN (".implode(',',$acntarr).") ORDER BY `gy_emp_fullname` ASC");
}else{
    $usrsql=$link->query("SELECT `gy_emp_code`,`gy_emp_fullname` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user`.`gy_user_status`=0 AND `gy_acc_id`='$accid' ORDER BY `gy_emp_fullname` ASC");
}
?>
<option value=""></option>
<?php if(mysqli_num_rows($usrsql) > 0){ ?>
<option value="0">All</option>
<?php } while($usrrow=$usrsql->fetch_array()){ ?>
<option value="<?php echo $usrrow['gy_emp_code']; ?>"><?php echo $usrrow['gy_emp_fullname']; ?></option>
<?php } ?>