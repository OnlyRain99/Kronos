<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

	$scdid = addslashes($_REQUEST['scdid']);
	$day7ah = date("Y-m-d", strtotime($onlydate." + 7 days"));

	if($scdid!=""){
		$dltsql=$link->query("DELETE FROM `gy_schedule` Where `gy_sched_id`='$scdid' AND `gy_sched_day`>='$day7ah' ");
		if($dltsql){ echo "success"; }else{ echo"error"; }
	}else{ echo"nochange"; }
	
	$link->close();
?>