<?php  
	include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];
	$error="error";
    if ($redirect == "") {
        header("location: esc_summary?note=invalid");
    }else{
        $escalate=$link->query("SELECT `gy_esc_type`,`gy_tracker_id`,`gy_tracker_date`,`gy_tracker_login`,`gy_tracker_breakout`,`gy_tracker_breakin`,`gy_tracker_logout`,`gy_tracker_wh`,`gy_tracker_bh`,`gy_tracker_ot`,`gy_esc_status` From `gy_escalate` Where `gy_esc_id`='$redirect'");
        $escrow=$escalate->fetch_array();
      if($escalate->num_rows>0){
      if($escrow['gy_esc_status']==0){
        $type=$escrow['gy_esc_type'];
        $trackid=words($escrow['gy_tracker_id']);
        $trackdate=words($escrow['gy_tracker_date']);
        $login=words($escrow['gy_tracker_login']);
        $breakout=words($escrow['gy_tracker_breakout']);
        $breakin=words($escrow['gy_tracker_breakin']);
        $logout=words($escrow['gy_tracker_logout']);
        $wh=words($escrow['gy_tracker_wh']);
        $bh=words($escrow['gy_tracker_bh']);
        $ot=words($escrow['gy_tracker_ot']);

		if($type > 5){
            if($type == 6) {
				$ifcor=$link->query("SELECT `gy_emp_code`, `gy_tracker_id`, `gy_tracker_login`, `gy_tracker_logout`, gy_tracker_breakout, gy_tracker_breakin, `gy_tracker_bh`, `gy_tracker_ot`, `gy_tracker_ath` From `gy_tracker` Where `gy_tracker_id`='$trackid' ");
				$fcrow=$ifcor->fetch_array();
				$thewh = false;
				$thewh = getthewh($fcrow['gy_emp_code'], $fcrow['gy_tracker_login'], $fcrow['gy_tracker_breakout'], $fcrow['gy_tracker_breakin'], $fcrow['gy_tracker_logout']);
				if($thewh){
                    $history = "<br> Approved OT from ".date("h:i a", strtotime($login))." - ".date("h:i a", strtotime($logout))." by ".$user_info." at ".$datenow."<br>";
                    $statement = "UPDATE `gy_tracker` SET `gy_tracker_ot`='$ot',`gy_tracker_request`='overtime', `gy_tracker_history`=CONCAT('$history',`gy_tracker_history`) Where `gy_tracker_id`='$trackid'";
				}else{ $error="duplicated"; }
            }else{
                $history = "<br> Approved Logs Update -> Login: ".date("h:i a", strtotime($login))." Breakout: ".date("h:i a", strtotime($breakout))." Breakin: ".date("h:i a", strtotime($breakin))." Logout: ".date("h:i a", strtotime($logout))." by ".$user_info." at ".$datenow."<br>";
                $statement = "UPDATE `gy_tracker` SET `gy_tracker_date`='$login',`gy_tracker_login`='$login',`gy_tracker_logout`='$logout',`gy_tracker_breakout`='$breakout',`gy_tracker_breakin`='$breakin',`gy_tracker_wh`='$wh',`gy_tracker_bh`='$bh',`gy_tracker_ot`='$ot',`gy_tracker_request`='', `gy_tracker_history`=CONCAT('$history',`gy_tracker_history`) Where `gy_tracker_id`='$trackid'";
            }
		}else if($type == 5){
		    $history = "<br> Approved Early Out ".date("h:i a", strtotime($logout))." by ".$user_info." at ".$datenow."<br>";
            $statement = "UPDATE `gy_tracker` SET `gy_tracker_logout`='$logout',`gy_tracker_wh`='$wh',`gy_tracker_bh`='$bh',`gy_tracker_ot`='$ot',`gy_tracker_request`='', `gy_tracker_history`=CONCAT('$history',`gy_tracker_history`) Where `gy_tracker_id`='$trackid'";						
		}

        $updatedata=$link->query($statement);

        if ($updatedata) {
            $escalation=$link->query("UPDATE `gy_escalate` SET `gy_esc_status`='1',`gy_esc_to`='$user_id' Where `gy_esc_id`='$redirect'");
			$trackrec = $link->query("SELECT `gy_tracker_login` From `gy_tracker` Where `gy_tracker_login`='0000-00-00 00:00:00' AND `gy_tracker_id`='$trackid'");
            if(mysqli_num_rows($trackrec) > 0){
				$link->query("DELETE FROM `gy_tracker` WHERE `gy_tracker_id`='$trackid'");
			}//else{
				//$link->query("UPDATE `gy_tracker` SET `gy_tracker_request`='' Where `gy_tracker_id`='$trackid'");					
			//}
            $notetext = "gy_esc_id -> ".$redirect." Escalate Approved";
            $notetype = "update";
            $noteucode = $user_code;
            $noteuser = $user_info;
            my_notify($notetext, $notetype , $noteucode , $noteuser);
            header("location: esc_summary?note=approve");
        }else{
            header("location: esc_summary?note=".$error);
        }
      }else{ header("location: esc_summary"); }
	  }else{ header("location: esc_summary?note=invalid"); }
    }
    $link->close();

function getthewh($dbemp, $dblogin, $dbbreo, $dbbrei, $dblogo){
	include '../../config/conn.php';
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
	return $arrsched[2];
}
?>