<?php
    include '../../../config/conn.php';
	include '../../../config/function.php';
    include '../session.php';

$lid = addslashes($_REQUEST['lid']);
$val = addslashes($_REQUEST['val']);
$rmrks = addslashes($_REQUEST['rmrks']);
$thisdate = addslashes($_REQUEST['date']);


$ctlsql=$link->query("SELECT * FROM `gy_leave` LEFT JOIN `gy_user` ON `gy_leave`.`gy_user_id`=`gy_user`.`gy_user_id` JOIN `gy_employee` ON `gy_user`.`gy_user_code`=`gy_employee`.`gy_emp_code` WHERE `gy_leave`.`gy_user_id`!='$user_id' AND (`gy_employee`.`gy_emp_supervisor`='$user_id' OR `gy_leave`.`gy_acc_id`='$myaccount') AND `gy_leave`.`gy_leave_date_from`='$thisdate' AND `gy_leave`.`gy_leave_id`='$lid' AND `gy_leave`.`gy_leave_status`=0 AND `gy_user`.`gy_user_type`<".$_SESSION['fus_user_type']);
$tmlvcnt=$ctlsql->num_rows;
$leave=$ctlsql->fetch_array();

if($tmlvcnt==1){
 if($val==0 && $rmrks!=""){
    $update_leave=$link->query("UPDATE `gy_leave` SET `gy_leave_approver`='$user_id',`gy_leave_date_approved`='$datenow',`gy_leave_status`='2',`gy_leave_remarks`='$rmrks' Where `gy_user_id`!='$user_id' AND `gy_acc_id`='".$leave['gy_acc_id']."' AND `gy_leave_date_from`='$thisdate' AND `gy_leave_id`='$lid'");
    if($update_leave){ echo"sucrej"; }else{ echo "erraprv"; }
}else{ echo"invalidrejreason"; }
}else{ echo"reqnotfound"; }

$link->close();
?>