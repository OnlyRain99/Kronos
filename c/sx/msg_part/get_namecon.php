<?php
include '../../../config/conn.php';
include '../../../config/function.php';
include '../session.php';

$file = addslashes($_REQUEST['file']);
$typid = addslashes($_REQUEST['typid']);
$strsql = "";
if($typid==0 || $typid==1 || $typid==2 || $typid==8){
$schsql=$link->query("SELECT `msg_usercode` FROM `gy_schedule_escalate` where `gy_sched_esc_id`='$file'");
$schrow=$schsql->fetch_array();
if($schrow['msg_usercode']!=""){ $strsql = $schrow['msg_usercode'].",".$user_code; }else{ $strsql = $user_code; }
	if(in_array($user_code , explode(",", $schrow['msg_usercode']))!=1){
		$link->query("UPDATE `gy_schedule_escalate` SET `msg_usercode`='$strsql' Where `gy_sched_esc_id`='$file'");
	}
}else if($typid==5 || $typid==6 || $typid==7){
$escsql=$link->query("SELECT `msg_usercode` FROM `gy_escalate` where `gy_esc_id`='$file'");
$escrow=$escsql->fetch_array();
if($escrow['msg_usercode']!=""){ $strsql = $escrow['msg_usercode'].",".$user_code; }else{ $strsql = $user_code; }
	if(in_array($user_code , explode(",", $escrow['msg_usercode']))!=1){
		$link->query("UPDATE `gy_escalate` SET `msg_usercode`='$strsql' Where `gy_esc_id`='$file'");
	}
}else if($typid=="loa"){
$dssql=$link->query("SELECT * FROM `gy_leave` WHERE `gy_leave_id`='$file' ");
$dssrow=$dssql->fetch_array();
if($dssrow['msg_usercode']!=""){ $strsql = $dssrow['msg_usercode'].",".$user_code;
	if(in_array($user_code , explode(",", $dssrow['msg_usercode']))!=1){
		$link->query("UPDATE `gy_leave` SET `msg_usercode`='$strsql' Where `gy_leave_id`='$file' ");
}}
}

$ucsql=$link->query("SELECT `gy_user`.`gy_user_code` as `gyucode`, `gy_user`.`gy_full_name` as `gyflname` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where (`gy_user`.`gy_user_code`='$user_code' OR `gy_employee`.`gy_acc_id`=11 OR `gy_user`.`gy_user_type`=4) AND `gy_user`.`gy_user_status`=0 ");
$i=0;
echo '[';
while($ucrow=$ucsql->fetch_array()){
if($i>0){ echo ','; }
echo '{ "ucode":"'.$ucrow['gyucode'].'", "fullname":"'.$ucrow['gyflname'].'" }';
$i++;
}
echo ']';

$link->close();
?>