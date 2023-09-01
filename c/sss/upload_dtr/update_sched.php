<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

	$hidid = addslashes($_REQUEST['hidid']);
	$nswr = addslashes($_REQUEST['nswr']);
	$nsin = addslashes($_REQUEST['nsin']);
	$nsout = addslashes($_REQUEST['nsout']);
	$swr = addslashes($_REQUEST['swr']);
	
	$sqlupd = "";
	if($nswr!=""){ $sqlupd.="`gy_sched_mode`='$nswr'"; }
	if($nsin!="" || $nswr=="0"){ if($sqlupd!=""){ $sqlupd.=","; } if($swr==0){ $nsin="00:00:00"; } $sqlupd.="`gy_sched_login`='$nsin'"; }
	if($nsout!="" || $nswr=="0"){ if($sqlupd!=""){ $sqlupd.=","; } if($swr==0){ $nsout="00:00:00"; } $sqlupd.="`gy_sched_logout`='$nsout'"; }
	
	if($sqlupd!="" && $hidid!=""){
		$day7ah = date("Y-m-d", strtotime($onlydate." + 7 days"));
		$updsql=$link->query("UPDATE `gy_schedule` SET ".$sqlupd.",`gy_sched_reg`='$onlydate',`gy_sched_by`='$user_id' WHERE `gy_sched_id`='$hidid' AND `gy_sched_day`>='$day7ah' ");
		if($updsql){ echo "success"; }else{ echo"error"; }
	}else{ echo"nochange"; }
	
	$link->close();
?>