<?php
	include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

	$nameid = addslashes($_REQUEST['empcode']);
	$trackid = addslashes($_REQUEST['trackid']);
	$athval = addslashes($_REQUEST['athval']);

    $ifcor=$link->query("SELECT `gy_emp_code` From `gy_employee` Where `gy_emp_supervisor`='$user_id' AND `gy_emp_code`='$nameid' LIMIT 1");
	if(mysqli_num_rows($ifcor) > 0){
		$ifcor=$link->query("SELECT `gy_tracker_id` From `gy_tracker` Where `gy_emp_code`='$nameid' AND `gy_tracker_id`='$trackid' AND `gy_tracker_request`='' LIMIT 1");
		if(mysqli_num_rows($ifcor) > 0){
			$ifcor=$link->query("SELECT `gy_emp_code`, `gy_tracker_id`, `gy_tracker_login`, `gy_tracker_logout`, `gy_tracker_breakout`, `gy_tracker_breakin`, `gy_tracker_bh`, `gy_tracker_ot`, `gy_tracker_ath` From `gy_tracker` Where `gy_emp_code`='$nameid' AND `gy_tracker_id`='$trackid' AND `gy_tracker_request`='' LIMIT 1");
			$fcrow=$ifcor->fetch_array();
			$thewh = array(0,0, false, "0000-00-00 00:00:00", "0000-00-00 00:00:00");
			$theutl = array(0,0);
			$thewh = getthewh($fcrow['gy_emp_code'], $fcrow['gy_tracker_login'], $fcrow['gy_tracker_breakout'], $fcrow['gy_tracker_breakin'], $fcrow['gy_tracker_logout']);
			$theutl = gettheundert($fcrow['gy_emp_code'], $thewh[0], $fcrow['gy_tracker_login'], $fcrow['gy_tracker_logout']);
			$thisath = getath($fcrow['gy_tracker_logout'], $thewh[1], $fcrow['gy_tracker_bh'], $fcrow['gy_tracker_ot'], $theutl, $fcrow['gy_tracker_ath']);

			if($thisath <= gettwh($thewh[1] - $fcrow['gy_tracker_bh'])){ $athval = $thisath; }

//		if(mysqli_num_rows($ifcor) > 0 && $athval <= $thisath && $athval > 0 && $fcrow['gy_tracker_ath'] == 0){
		if(mysqli_num_rows($ifcor) > 0 && $thewh[2]){
		$updatedata=$link->query("UPDATE `gy_tracker` SET `gy_tracker_ath`='$athval',`gy_tracker_request`='approve',`gy_tracker_om`='$user_id' Where `gy_tracker_id`='$trackid'");
		echo "approve"; }else if($thewh[2]==false){ echo"duporerr"; }
		}
	}
	
function getthewh($dbemp, $dblogin, $dbbreo, $dbbrei, $dblogo){
	include '../../../config/conn.php';
	$today = date("Y-m-d", strtotime($dblogin));
	$yesterday = date("Y-m-d", strtotime($dblogin.' -1 day'));
	$tomorrow = date("Y-m-d", strtotime($dblogin.' +1 day'));
	$theemp = getempid($dbemp);
	$arrsched = array($today,0,false, "0000-00-00 00:00:00", "0000-00-00 00:00:00");
	$sqlemp="`gy_sched_day`>='".$yesterday."' AND `gy_sched_day`<='".$tomorrow."'";
	if($dblogo != "0000-00-00 00:00:00"){ $endday = $dblogo; }
	else if($dbbrei != "0000-00-00 00:00:00"){ $endday = $dbbrei; }
	else if($dbbreo != "0000-00-00 00:00:00"){ $endday = $dbbreo; }
	else { $endday = $dblogin; $sqlemp="`gy_sched_day`='".$today."'"; }

	$empsch=$link->query("SELECT `gy_sched_day`, `gy_sched_login`, `gy_sched_logout` FROM `gy_schedule` WHERE ".$sqlemp." AND `gy_emp_id`='".$theemp."' AND `gy_sched_mode`!=0 ORDER BY `gy_sched_day` ASC");
	if(mysqli_num_rows($empsch) > 0){
		while ($scrow=$empsch->fetch_array()) {
			if(date("H:i:s", strtotime(convert24to0($scrow['gy_sched_login']))) > date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout'])))) {
				$schedlout = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout']))).' +1 day');
			}else{
				$schedlout = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout']))));
			}
			$schedin = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_login']))));
			if(strtotime($dblogin) <= $schedlout && strtotime($endday) >= $schedin || $sqlemp=="`gy_sched_day`='".$today."'"){ $arrsched[0] = $scrow['gy_sched_day'];
			$arrsched[1] = getwh(date("Y-m-d", strtotime($arrsched[0]))." ".convert24to0($scrow['gy_sched_login']), date("Y-m-d H:i:s", $schedlout));
			$arrsched[3]=date("Y-m-d H:i:s", $schedin);
			$arrsched[4]=date("Y-m-d H:i:s", $schedlout);
			break; }
		}
	}

    if($dblogin!="0000-00-00 00:00:00"&&$dblogo!="0000-00-00 00:00:00"){$arrsched[2]=true;}else{$arrsched[2]=false;}
	
	if($arrsched[3]!="0000-00-00 00:00:00" && $arrsched[4]!="0000-00-00 00:00:00"){
		$ifxst=$link->query("SELECT `gy_tracker_id` From `gy_tracker` Where (`gy_tracker_login`<'".$arrsched[4]."' AND `gy_tracker_logout`>'".$arrsched[3]."') AND (`gy_tracker_request`='approve' OR `gy_tracker_request`='overtime') AND `gy_emp_code`='$dbemp' ");
		if($ifxst->num_rows>0){ $arrsched[2]=false; }
	}else{ $arrsched[2]=false; }
	
	$link->close();
	return $arrsched;
}

function gettheundert($dbemp, $correctdate, $dblogin, $dblogout){
	include '../../../config/conn.php';
	$theemp = getempid($dbemp);
	$ut = array(0,0);
	$empsch=$link->query("SELECT `gy_sched_day`, `gy_sched_login`, `gy_sched_logout` FROM `gy_schedule` WHERE `gy_sched_day`='".$correctdate."' AND `gy_emp_id`='".$theemp."' LIMIT 1");
	if(mysqli_num_rows($empsch) > 0){
		while ($scrow=$empsch->fetch_array()) {
			$schedin = date("Y-m-d H:i:s", strtotime($scrow['gy_sched_day']." ".convert24to0($scrow['gy_sched_login'])));
			if(date("H:i:s", strtotime($scrow['gy_sched_login'])) > date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout'])))){
			$schedout = date("Y-m-d H:i:s", strtotime($scrow['gy_sched_day']." ".convert24to0($scrow['gy_sched_logout']).' +1 day'));
			}else{ $schedout = date("Y-m-d H:i:s", strtotime($scrow['gy_sched_day']." ".convert24to0($scrow['gy_sched_logout']))); }
			$dblogin = date("Y-m-d H:i:s", strtotime($dblogin));
			if($dblogout != "0000-00-00 00:00:00"){ $dblogout = date("Y-m-d H:i:s", strtotime($dblogout)); }
			if($dblogin > $schedin){ $ut[0] = getmindif($schedin, $dblogin, "in"); }
			if($dblogout < $schedout && $dblogout != "0000-00-00 00:00:00"){ $ut[1] = getmindif($dblogout, $schedout, "out"); }
		}
	}
	return $ut;
}

function gettwh($wh){
if($wh < 0){ return 0; }
else { return $wh; }
}
?>