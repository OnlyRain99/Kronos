<?php
include '../../../config/conn.php';
include '../../../config/function.php';
include '../session.php';

$file = addslashes($_REQUEST['file']);
$typid = addslashes($_REQUEST['typid']);
$ucode = addslashes($_REQUEST['ucode']);
$msg = addcslashes($_REQUEST['msg'],"\"\\");
$myfile = "../../../msg_logs/".$file."_".$typid.".php";
	clearstatcache();
date_default_timezone_set('Asia/Taipei');
$time = date("Y-m-d H:i:s");
$newmsg = '
{
"msgby":"'.$user_code.'",
"datetime":"'.$time.'",
"message":"'.$msg.'"
}
';

	$fp = fopen($myfile,'r+');
	$filesize = filesize($myfile);
	$content = fread($fp, $filesize);
	if(strlen($content)>0){
		fseek($fp, -3, SEEK_END);
		fwrite($fp, ','.ltrim($newmsg).']');
	}else{
		fwrite($fp, '['.$newmsg.']');
	}

	fclose($fp);

if($typid==0 || $typid==1 || $typid==2 || $typid==8){
	$scgsql=$link->query("UPDATE `gy_schedule_escalate` SET `msg_usercode`='$user_code' Where `gy_sched_esc_id`='$file'");
}else if($typid==5 || $typid==6 || $typid==7){
    $escsql=$link->query("UPDATE `gy_escalate` SET `msg_usercode`='$user_code' Where `gy_esc_id`='$file'");
}

$link->close();
?>