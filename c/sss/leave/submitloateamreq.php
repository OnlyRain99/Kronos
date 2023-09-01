<?php
    include '../../../config/conn.php';
	include '../../../config/function.php';
    include '../session.php';

$lid = addslashes($_REQUEST['lid']);
$val = addslashes($_REQUEST['val']);
$rmrks = addslashes($_REQUEST['rmrks']);
$thisdate = addslashes($_REQUEST['date']);


$ctlsql=$link->query("SELECT * FROM `gy_leave` LEFT JOIN `gy_user` ON `gy_leave`.`gy_user_id`=`gy_user`.`gy_user_id` JOIN `gy_employee` ON `gy_user`.`gy_user_code`=`gy_employee`.`gy_emp_code` WHERE `gy_leave`.`gy_user_id`!='$user_id' AND `gy_leave`.`gy_leave_date_from`='$thisdate' AND `gy_leave`.`gy_leave_id`='$lid' AND `gy_leave`.`gy_leave_status`=0 AND `gy_employee`.`gy_emp_supervisor`='$user_id' ");
$tmlvcnt=$ctlsql->num_rows;
$leave=$ctlsql->fetch_array();

$levsql=$link->query("SELECT `gy_leave_avail_id`,`gy_leave_avail_plotted`,`gy_leave_avail_approved` From `gy_leave_available` WHERE `gy_acc_id`='".$leave['gy_acc_id']."' AND `gy_leave_avail_plotted`>0 AND `gy_leave_avail_date`<='$thisdate' AND `gy_leave_avail_dateto`>='$thisdate' ");
$levcnt=$levsql->num_rows;

if($tmlvcnt==1){
$levrow=$levsql->fetch_array();

 if($val==0 && $rmrks!=""){
    $update_leave=$link->query("UPDATE `gy_leave` SET `gy_leave_approver`='$user_id',`gy_leave_date_approved`='$datenow',`gy_leave_status`='2',`gy_leave_remarks`='$rmrks' Where `gy_user_id`!='$user_id' AND `gy_acc_id`='".$leave['gy_acc_id']."' AND `gy_leave_date_from`='$thisdate' AND `gy_leave_id`='$lid'");
    if($update_leave){ echo"sucrej"; }else{ echo "erraprv"; }
}else{ echo"invalidrejreason"; }
}else{ echo"reqnotfound"; }

$link->close();
?>