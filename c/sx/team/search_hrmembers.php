<?php
include '../../../config/conn.php';
include '../../../config/function.php';
include 'session.php';

 $cntrlval = addslashes($_REQUEST['cntrlval']);
 $daagent = addslashes($_REQUEST['daagent']);

 if($cntrlval==""){ echo "<option></option>"; }else{

$ifcor=$link->query("SELECT `gy_emp_code` From `gy_employee` Where `gy_emp_type`=5 OR `gy_emp_supervisor`='$user_id' AND `gy_emp_code`='$cntrlval' LIMIT 1");
if(mysqli_num_rows($ifcor) > 0){

 $getsup=$link->query("SELECT `gy_full_name`,`gy_user_id`,`gy_user_status` From `gy_user` Where `gy_user_code`='$cntrlval' LIMIT 1");
 while ($suprow=$getsup->fetch_array()){ if($suprow['gy_user_status']==0){ ?>
<option class="text-success" value="<?= $cntrlval; ?>"><?php echo $suprow['gy_full_name']; ?></option>

 <?php
 $cntrlval = $suprow['gy_user_id'];
 $getlst=$link->query("SELECT `gy_emp_fullname`, `gy_emp_code` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user`.`gy_user_status`=0 AND `gy_emp_supervisor`='$cntrlval' ");
 while ($lstrow=$getlst->fetch_array()){ ?>

<option value="<?php echo $lstrow['gy_emp_code']; ?>" <?php if($daagent==$lstrow['gy_emp_code']){ echo "selected"; }?> ><?php echo $lstrow['gy_emp_fullname']; ?></option>

<?php }}}
}else{ echo "<option>DO NOT CHANGE THE VALUE!</option>"; }} ?>