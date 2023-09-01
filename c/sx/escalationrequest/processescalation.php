<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$torid = addslashes($_REQUEST['stid']);
$mode = addslashes($_REQUEST['sctm']);
$proc = addslashes($_REQUEST['proc']);

$datestrt = date("Y-m-d");
$datestre = date("Y-m-d H:i:s");
if(date("d")<=5){ $datestrt = date("Y-m-16", strtotime("-1 Month")); $datestre = date("Y-m-16 00:00:00", strtotime("-1 Month")); }
else if(date("d")>=1 && date("d")<=20){ $datestrt = date("Y-m-01"); $datestre = date("Y-m-01 00:00:00"); }
else if(date("d")>=16){ $datestrt = date("Y-m-16"); $datestre = date("Y-m-16 00:00:00"); }

if($mode==0){
        $approve=$link->query("SELECT * From `gy_schedule_escalate` Where `gy_sched_esc_id`='$torid' AND `gy_sched_day`>='$datestrt'");
        $app=$approve->fetch_array();
    if($approve->num_rows>0){
        $empid = getempid($app['gy_emp_code']);
            $day = words($app['gy_sched_day']);
            $mode = words($app['gy_sched_mode']);
            $login = words($app['gy_sched_login']);
            $breakout = words($app['gy_sched_breakout']);
            $breakin = words($app['gy_sched_breakin']);
            $logout = words($app['gy_sched_logout']);
            $trackcode = words($app['gy_sched_esc_code']);
    if($proc==1){
        if($app['gy_req_status']==0){
            $history = "<br> Approved ".get_mode($mode)." from ".date("h:i a", strtotime($login))." - ".date("h:i a", strtotime($logout))." by ".$user_info." at ".$datenow."<br>";
            $mode1=$mode;
            if($mode==3){$mode1=2;}
            if(check_sched_exist($day, $empid) > 0){
            $updatedata=$link->query("UPDATE `gy_schedule` SET `gy_sched_mode`='$mode1', `gy_sched_login`='$login', `gy_sched_breakout`='$breakout', `gy_sched_breakin`='$breakin', `gy_sched_logout`='$logout', `gy_sched_reg`='$onlydate', `gy_sched_by`='$user_id' Where `gy_sched_day`='$day' AND `gy_emp_id`='$empid'");
            }else{
            $updatedata=$link->query("INSERT INTO `gy_schedule` (`gy_emp_id`, `gy_sched_day`, `gy_sched_mode`, `gy_sched_login`, `gy_sched_breakout`, `gy_sched_breakin`, `gy_sched_logout`, `gy_sched_reg`, `gy_sched_by`) VALUES('$empid', '$day', '$mode1', '$login', '$breakout', '$breakin', '$logout', '$onlydate', '$user_id')");
            }

            if($updatedata){
                $updatecodes=$link->query("UPDATE `gy_schedule_escalate` SET `gy_req_status`='1',`gy_req_to`='$user_id' Where `gy_sched_esc_id`='$torid'");
                $trackrec = $link->query("SELECT `gy_tracker_login` From `gy_tracker` Where `gy_tracker_login`='0000-00-00 00:00:00' AND `gy_tracker_code`='$trackcode'");
                if(mysqli_num_rows($trackrec) > 0){
                    $link->query("DELETE FROM `gy_tracker` WHERE `gy_tracker_code`='$trackcode'");
                }else{
                    $link->query("UPDATE `gy_tracker` SET `gy_tracker_request`='', `gy_tracker_history`=CONCAT('$history',`gy_tracker_history`) Where `gy_tracker_code`='$trackcode'");
                }
                echo "success";
            }else{ echo "error"; }
        }else{ echo "error"; }
    }else if($proc==2){
        $comment = addslashes($_REQUEST['rmrks']);
        if($app['gy_req_status']==0 && $comment!=""){
            $statement = "UPDATE `gy_schedule_escalate` SET `gy_req_status`='2',`gy_req_deny`='$comment',`gy_req_to`='$user_id' Where `gy_sched_esc_id`='$torid'";
            $updatedata=$link->query($statement);
            if($updatedata){
                $trackrec = $link->query("SELECT `gy_tracker_login` From `gy_tracker` Where `gy_tracker_login`='0000-00-00 00:00:00' AND `gy_tracker_code`='$trackcode'");
                if(mysqli_num_rows($trackrec) > 0){
                    $link->query("DELETE FROM `gy_tracker` WHERE `gy_tracker_code`='$trackcode'");
                }else{
                    $history = "<br> Denied ".get_mode($mode)." from ".date("h:i a", strtotime($login))." - ".date("h:i a", strtotime($logout))." by ".$user_info." at ".$datenow."<br>";
                    $link->query("UPDATE `gy_tracker` SET `gy_tracker_request`='', `gy_tracker_history`=CONCAT('$history',`gy_tracker_history`) Where `gy_tracker_code`='$trackcode'");
                }
                $notetext = "gy_sched_esc_id -> ".$torid." Escalate Denied";
                $notetype = "update";
                $noteucode = $user_code;
                $noteuser = $user_info;
                my_notify($notetext, $notetype , $noteucode , $noteuser);
                echo "denysuccess";
            }else{ echo "error"; }
        }else{ echo "reqnotmet"; }
    }else{ echo "donotchng"; }
	}else{ echo "reqcancl"; }
}else if($mode==1){
    $escalate=$link->query("SELECT `gy_esc_type`,`gy_tracker_id`,`gy_tracker_date`,`gy_tracker_login`,`gy_tracker_breakout`,`gy_tracker_breakin`,`gy_tracker_logout`,`gy_tracker_wh`,`gy_tracker_bh`,`gy_tracker_ot`,`gy_esc_status` From `gy_escalate` Where `gy_esc_id`='$torid' AND `gy_tracker_date`>='$datestre'");
    $escrow=$escalate->fetch_array();
    if($escalate->num_rows>0){
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
        if($proc==1 && $escrow['gy_esc_status']==0){
            $error="error";
            if($type > 5) {
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
            if($updatedata){
                $escalation=$link->query("UPDATE `gy_escalate` SET `gy_esc_status`='1',`gy_esc_to`='$user_id' Where `gy_esc_id`='$torid'");
                $trackrec = $link->query("SELECT `gy_tracker_login` From `gy_tracker` Where `gy_tracker_login`='0000-00-00 00:00:00' AND `gy_tracker_id`='$trackid'");
                if(mysqli_num_rows($trackrec) > 0){
                    $link->query("DELETE FROM `gy_tracker` WHERE `gy_tracker_id`='$trackid'");
                }
                $notetext = "gy_esc_id -> ".$torid." Escalate Approved";
                $notetype = "update";
                $noteucode = $user_code;
                $noteuser = $user_info;
                my_notify($notetext, $notetype , $noteucode , $noteuser);
                echo "success";
            }else{ echo $error; }
        }else if($proc==2){
            $reason = addslashes($_REQUEST['rmrks']);
            if($escrow['gy_esc_status']==0 && $reason!=""){
                if($type==5){ $history= "<br> Denied Early Out ".date("h:i a", strtotime($logout)); }
                else if($type==6){ $history = "<br> Denied OT from ".date("h:i a", strtotime($login))." - ".date("h:i a", strtotime($logout)); }
                else if($type>6){ $history = "<br> Denied Logs Update -> Login: ".date("h:i a", strtotime($login))." Breakout: ".date("h:i a", strtotime($breakout))." Breakin: ".date("h:i a", strtotime($breakin))." Logout: ".date("h:i a", strtotime($logout)); } $history=$history." by ".$user_info." at ".$datenow."<br>";
                $statement = "UPDATE `gy_tracker` SET `gy_tracker_request`='', `gy_tracker_history`=CONCAT('$history',`gy_tracker_history`) Where `gy_tracker_id`='$trackid'";
                $updatedata=$link->query($statement);
                if($updatedata){
                    $escalation=$link->query("UPDATE `gy_escalate` SET `gy_esc_status`='2',`gy_esc_deny`='$reason',`gy_esc_to`='$user_id' Where `gy_esc_id`='$torid'");
                    $trackrec = $link->query("SELECT `gy_tracker_login` From `gy_tracker` Where `gy_tracker_login`='0000-00-00 00:00:00' AND `gy_tracker_id`='$trackid'");
                    if(mysqli_num_rows($trackrec) > 0){
                        $link->query("DELETE FROM `gy_tracker` WHERE `gy_tracker_id`='$trackid'");
                    }
                    $notetext = "gy_esc_id -> ".$torid." Escalate Denied";
                    $notetype = "update";
                    $noteucode = $user_code;
                    $noteuser = $user_info;
                    my_notify($notetext, $notetype , $noteucode , $noteuser);
                    echo "denysuccess";
                }else{ echo "error"; }
            }else{ echo "reqnotmet"; }
        }else{ echo "donotchng"; }
	}else{ echo "reqcancl"; }
}else{ echo "donotchng"; }

 $link->close();

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
	return $arrsched[2];
}
?>