<?php
include '../../../config/conn.php';
include '../../../config/function.php';
include 'session.php';

 $cntrlval = addslashes($_REQUEST['cntrlval']);
 $daagent = addslashes($_REQUEST['daagent']);

 if($cntrlval==""){ echo "<option></option>"; }else{

 $getlst=$link->query("SELECT `gy_emp_fullname`, `gy_emp_code` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user`.`gy_user_status`=0 AND `gy_employee`.`gy_acc_id`='$cntrlval' ORDER BY `gy_emp_fullname`");
 while ($lstrow=$getlst->fetch_array()){ ?>

<option value="<?php echo $lstrow['gy_emp_code']; ?>" <?php if($daagent==$lstrow['gy_emp_code']){ echo "selected"; }?> ><?php echo $lstrow['gy_emp_fullname']; ?></option>

<?php } } ?>