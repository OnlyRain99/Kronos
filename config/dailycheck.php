<?php
	include 'conn.php';
    include 'function.php';
    date_default_timezone_set('Asia/Taipei');
	$ntype = 0;

	$dtoday = date("Y-m-d");
	while($ntype < 3){
		$lday = date("Y-m-d", strtotime($dtoday.' -1 day'));
		//if(date("w", strtotime($lday)) != 0 && date("w", strtotime($lday)) != 6){
		//	$ntype++;
		//}
		$ntype++;
		$dtoday = $lday;
	}

	$ifcor=$link->query("SELECT `gy_emp_code`, `gy_tracker_id`, `gy_tracker_date`, `gy_tracker_login`, `gy_tracker_logout`, `gy_tracker_breakout`, `gy_tracker_breakin`, `gy_tracker_bh`, `gy_tracker_ot`, `gy_tracker_ath` From `gy_tracker` Where `gy_tracker_request`='' AND `gy_tracker_ath`='0' ORDER BY `gy_tracker_id` ASC");
	while ($fcrow=$ifcor->fetch_array()) {
	$trackid = $fcrow['gy_tracker_id'];

	$truedate = array(0,0,false, "0000-00-00 00:00:00", "0000-00-00 00:00:00");
	$theutl = array(0,0);
	$truedate = getthewh($fcrow['gy_emp_code'], $fcrow['gy_tracker_date'], $fcrow['gy_tracker_breakout'], $fcrow['gy_tracker_breakin'], $fcrow['gy_tracker_logout']);
	$theutl = gettheundert($fcrow['gy_emp_code'], $truedate[0], $fcrow['gy_tracker_login'], $fcrow['gy_tracker_logout']);
	$thisath = getathnoot($fcrow['gy_tracker_logout'], $truedate[1], $fcrow['gy_tracker_bh'], $theutl, $fcrow['gy_tracker_ath']);
	$notdup=true;
	if($truedate[3]!="0000-00-00 00:00:00" && $truedate[4]!="0000-00-00 00:00:00"){
		$ifxst=$link->query("SELECT `gy_tracker_id` From `gy_tracker` Where (`gy_tracker_login`<'".$truedate[4]."' AND `gy_tracker_logout`>'".$truedate[3]."') AND (`gy_tracker_request`='approve' OR `gy_tracker_request`='overtime') AND `gy_emp_code`='".$fcrow['gy_emp_code']."' ");
		if($ifxst->num_rows>0){ $notdup=false; }
	}
	$remarks = 'System Automatic Validation';
		if($truedate[0] < $dtoday){
			if((($truedate[1] > 0 && $fcrow['gy_tracker_logout']!="0000-00-00 00:00:00")||$truedate[2])&&$notdup){
		    	$updatedata=$link->query("UPDATE `gy_tracker` SET `gy_tracker_ath`='$thisath',`gy_tracker_request`='approve',`gy_tracker_om`='1',`gy_tracker_remarks`='$remarks' Where `gy_tracker_id`='$trackid'");
			}else{
				if($truedate[1] > 0 && $fcrow['gy_tracker_logout']=="0000-00-00 00:00:00"){ $reason = 'No Logs'; }else if($notdup==false){ $reason = 'Duplicate Logs'; }else{ $reason = 'Not Scheduled'; }
			    
			    $updatedata=$link->query("UPDATE `gy_tracker` SET `gy_tracker_reason`='$reason',`gy_tracker_remarks`='$remarks',`gy_tracker_request`='reject',`gy_tracker_om`='1' Where `gy_tracker_id`='$trackid'");
			}
		}
	}

function getthewh($dbemp, $dblogin, $dbbreo, $dbbrei, $dblogo){
	include 'conn.php';
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
	
	$lvsql=$link->query("SELECT `gy_user_type` From `gy_user` Where `gy_user_code`='$dbemp' limit 1");
    $lvrow=$lvsql->fetch_array();
    if(($lvrow['gy_user_type']==10 || $lvrow['gy_user_type']>14)&&($dblogin!="0000-00-00 00:00:00" && $dblogo!="0000-00-00 00:00:00")){ $arrsched[2] = true; }
	
	$link->close();
	return $arrsched;
}

function gettheundert($dbemp, $correctdate, $dblogin, $dblogout){
	include 'conn.php';
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
			if($dblogout < $schedout && $dblogout == "0000-00-00 00:00:00"){ $ut[1] = getmindif($dblogout, $schedout, "out"); }
		}
	}
	$link->close();
	return $ut;
}

//	$link->query("INSERT Into `cronjob`(`cronval`) values('$ntype')");
//  $datecron = date("Y-m-d H:i:s");
	$link->query("UPDATE `cronjob` SET `cronval`='$ntype',`crondate`='".date("Y-m-d H:i:s")."' Where `cronid`=1");
//updateloc
	$yestoday=date("Y-m-d");
	$link->query("UPDATE `gy_employee` SET `gy_assignedloc`=0 WHERE (`gy_tagumdate`!='0000-00-00' AND `gy_tagumdate`<='$yestoday') AND `gy_assignedloc`=1 AND (`gy_davaodate`<`gy_tagumdate` OR `gy_davaodate`>'$yestoday') ");
	$link->query("UPDATE `gy_employee` SET `gy_assignedloc`=1 WHERE (`gy_davaodate`!='0000-00-00' AND `gy_davaodate`<='$yestoday') AND `gy_assignedloc`=0 AND (`gy_tagumdate`<`gy_davaodate` OR `gy_tagumdate`>'$yestoday') ");
	$link->close();
?>