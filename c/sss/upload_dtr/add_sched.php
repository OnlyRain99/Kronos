<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

	$cdnm = addslashes($_REQUEST['cdnm']);
	$dfrom = addslashes($_REQUEST['dfrom']);
	$dto = addslashes($_REQUEST['dto']);
	$dscdtyp = addslashes($_REQUEST['dscdtyp']);
	$dscdin = addslashes($_REQUEST['dscdin']);
	$dscdout = addslashes($_REQUEST['dscdout']);
	$error=0; $errnm="";
	$day7ah = date("Y-m-d", strtotime($onlydate." + 7 days"));
	if($dscdtyp==0){ $dscdin="00:00:00"; $dscdout="00:00:00"; }
	if($dfrom=="" || $dfrom<$day7ah){ $error++; $errnm.="dfrom,"; }
	if($dto=="" || $dto<$day7ah || $dto<$dfrom){ $error++; $errnm.="dto,"; }
	if($dscdtyp=="" || $dscdtyp<0 || $dscdtyp>2){ $error++; $errnm.="dscdtyp,"; }
	if($dscdin==""){ $error++; $errnm.="dscdin,"; }
	if($dscdout==""){ $error++; $errnm.="dscdout,"; }

	$selempid = "";
	$idsql=$link->query("SELECT `gy_emp_id` FROM `gy_employee` WHERE `gy_emp_code`='$cdnm' LIMIT 1");
	if($idsql->num_rows>0){
		$idrow=$idsql->fetch_array();
		$selempid = $idrow['gy_emp_id'];
	}else{ $error++; $errnm.="cdnm,"; }

	if($error==0){
		$cntdate = date('Y-m-d', strtotime($dfrom));
		$updtd=0; $insrt=0; $faild=0;
		while($cntdate<=date("Y-m-d", strtotime($dto))){
		$allowed=1;

		if($dscdtyp==1 || $dscdtyp==2){
		$ystrday=date("Y-m-d", strtotime($cntdate."-1 day"));
		$schdsql=$link->query("SELECT * From `gy_schedule` Where `gy_sched_day`='$ystrday' AND `gy_emp_id`='$selempid' AND (`gy_sched_mode`='1' OR `gy_sched_mode`='2') ");
		while($scdrow=$schdsql->fetch_array()){
            $schdin = $scdrow['gy_sched_day']." ".$scdrow['gy_sched_login'];
            $schdout = $scdrow['gy_sched_day']." ".$scdrow['gy_sched_logout'];
            if($schdin>$schdout){ $schdout = date("Y-m-d H:i:s", strtotime($schdout." +1 day")); }
            $scdin1 = $cntdate." ".$dscdin;
            if(date("Y-m-d H:i:s", strtotime($schdout." +12 hours"))>$scdin1){ $allowed=0; $faild++; }
		}
		if($allowed!=0){
		$tmrday=date("Y-m-d", strtotime($cntdate."+1 day"));
		$schdsql=$link->query("SELECT * From `gy_schedule` Where `gy_sched_day`='$tmrday' AND `gy_emp_id`='$selempid' AND (`gy_sched_mode`='1' OR `gy_sched_mode`='2') ");
		while($scdrow=$schdsql->fetch_array()){
			$scdin = $scdrow['gy_sched_day']." ".$scdrow['gy_sched_login'];
            $scdin1 = $cntdate." ".$dscdin;
            $scdout1 = $cntdate." ".$dscdout;
			if($scdin1>$scdout1){ $scdout1 = date("Y-m-d H:i:s", strtotime($scdout1." +1 day")); }
			if(date("Y-m-d H:i:s", strtotime($scdout1." +12 hours"))>$scdin){ $allowed=0; $faild++; }
		} } }

		if($allowed==1){
			$ifupdt = 0;
			$scdsql=$link->query("SELECT * FROM `gy_schedule` WHERE `gy_emp_id`=$selempid AND `gy_sched_day`='$cntdate' ");
			if($scdsql->num_rows>0){ $ifupdt = 1; }
			if($ifupdt==1){
				$procsql=$link->query("UPDATE `gy_schedule` SET `gy_sched_mode`='$dscdtyp',`gy_sched_login`='$dscdin',`gy_sched_logout`='$dscdout',`gy_sched_reg`='$onlydate',`gy_sched_by`='$user_id' WHERE `gy_emp_id`='$selempid' AND `gy_sched_day`='$cntdate' ");
				if($procsql){ $updtd++; }else{ $faild++; }
			}else if($ifupdt==0){
				$procsql=$link->query("INSERT INTO `gy_schedule`(`gy_emp_id`,`gy_sched_day`,`gy_sched_mode`,`gy_sched_login`,`gy_sched_logout`,`gy_sched_reg`,`gy_sched_by`)values('$selempid','$cntdate','$dscdtyp','$dscdin','$dscdout','$onlydate','$user_id')");
				if($procsql){ $insrt++; }else{ $faild++; }
			}
		}
			$cntdate = date('Y-m-d', strtotime($cntdate.' +1 day'));
		}
		echo "success,".$insrt.",".$updtd.",".$faild;
	}else{ echo $errnm; }
	
	$link->close();
?>