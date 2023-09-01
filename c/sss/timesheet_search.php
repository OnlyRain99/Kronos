<?php
include '../../config/conn.php';
include '../../config/function.php';
include 'session.php';

if($user_type == 3 || $user_type == 4 || $user_type == 18 || $user_type == 10){

function matchsched($dbemp, $dblogin, $dbbreo, $dbbrei, $dblogo){
	include '../../config/conn.php';
	$today = date("Y-m-d", strtotime($dblogin));
	$yesterday = date("Y-m-d", strtotime($dblogin.' -1 day'));
	$tomorrow = date("Y-m-d", strtotime($dblogin.' +1 day'));
	$theemp = getempid($dbemp);
	$arrsched = array($today,0,"","",0);
	$sqlemp="`gy_sched_day`>='".$yesterday."' AND `gy_sched_day`<='".$tomorrow."'";
	if($dblogo != "0000-00-00 00:00:00"){ $endday = $dblogo; }
	else if($dbbrei != "0000-00-00 00:00:00"){ $endday = $dbbrei; }
	else if($dbbreo != "0000-00-00 00:00:00"){ $endday = $dbbreo; }
	else { $endday = $dblogin; $sqlemp="`gy_sched_day`='".$today."'"; }
	$empsch=$link->query("SELECT `gy_sched_day`, `gy_sched_login`, `gy_sched_logout`,`gy_sched_mode` FROM `gy_schedule` WHERE ".$sqlemp." AND `gy_emp_id`='".$theemp."' AND `gy_sched_mode`!=0 ORDER BY `gy_sched_day` ASC");
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
			$arrsched[2] = $scrow['gy_sched_login'];
			$arrsched[3] = $scrow['gy_sched_logout'];
			$arrsched[4] = $scrow['gy_sched_mode'];
			break; }
		}
	}
	$link->close();
	return $arrsched;
}

function channelcount($lin, $lout, $ticketarr, $tcnt, $empcode){
    $channel = 0;
    if($lin != "0000-00-00 00:00:00" && $lin != ""){
        for($i=0;$i<$tcnt;$i++){
            if($lout != "0000-00-00 00:00:00" && $lout != ""){
                if(strtotime($ticketarr[$i][2])>=strtotime($lin) && strtotime($ticketarr[$i][2])<=strtotime($lout) && $empcode==$ticketarr[$i][0]){
                    if($ticketarr[$i][1]=="Live Chat" || $ticketarr[$i][1]=="Email" || $ticketarr[$i][1]=="Phone"){ $channel++; }
                }
            }else{
                if(strtotime($ticketarr[$i][2])>=strtotime($lin) && $empcode==$ticketarr[$i][0]){
                    if($ticketarr[$i][1]=="Live Chat" || $ticketarr[$i][1]=="Email" || $ticketarr[$i][1]=="Phone"){ $channel++; }
                }
            }
        }
    }
    return $channel;
}

function gettwh($wh){
	if($wh <= 0){ return 0; }
	else { return round($wh,2); }
}

function getdtrwh($sdate, $adate){
    $tosec = (strtotime($adate) - strtotime($sdate))/60;
    $hour = floor($tosec / 60);
    $min = floor($tosec % 60);

    $total = $hour*60;
    $total += $min;
    return $total/60;
}

$deptid = addslashes($_REQUEST['dept']);
$usrcode = addslashes($_REQUEST['user']);
$fdate = addslashes($_REQUEST['dfro']);
$tdate = addslashes($_REQUEST['dato']);

$sqlemp = "";
$usrarr = array(); $userarr = array(array());
if($usrcode=='0'){
	$i = 0;
	if($deptid=="all"){
		$i2=0;
		$acntarr=array();
		$myagtsql=$link->query("SELECT `gy_acc_id` FROM `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` WHERE `gy_user`.`gy_user_status`=0 AND `gy_emp_supervisor`='$user_id'");
		while($myagtrow=$myagtsql->fetch_array()){
			if(!in_array($myagtrow['gy_acc_id'], $acntarr)){ $acntarr[$i2]=$myagtrow['gy_acc_id']; $i2++; }
		}

		$empsql=$link->query("SELECT `gy_emp_code`,`gy_emp_fullname`,`gy_emp_supervisor`,`gy_emp_account` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user`.`gy_user_status`=0 AND `gy_acc_id` IN (".implode(',',$acntarr).") ORDER BY `gy_emp_fullname` ASC");
	}else{
		$empsql=$link->query("SELECT `gy_emp_code`,`gy_emp_fullname`,`gy_emp_supervisor`,`gy_emp_account` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user`.`gy_user_status`=0 AND `gy_acc_id`='$deptid' ORDER BY `gy_emp_fullname`");
	}
		while($usrrow=$empsql->fetch_array()){
			$usrarr[$i] = $usrrow['gy_emp_code'];
			$userarr[0][$i] = $usrrow['gy_emp_fullname'];
			$userarr[1][$i] = get_supervisor_name($usrrow['gy_emp_supervisor']);
        	$userarr[2][$i] = $usrrow['gy_emp_account'];
			$i++;
		}
	$sqlwhere=" AND `emp_code` IN ('".implode("','",$usrarr)."')";
}else if($usrcode!=""){
	$empsql=$link->query("SELECT `gy_emp_code`,`gy_emp_fullname`,`gy_emp_supervisor`,`gy_emp_account` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user`.`gy_user_status`=0 AND `gy_emp_code`='$usrcode' LIMIT 1");
	$usrrow=$empsql->fetch_array();
	$usrarr[0] = $usrcode;
	$userarr[0][0] = $usrrow['gy_emp_fullname'];
	$userarr[1][0] = get_supervisor_name($usrrow['gy_emp_supervisor']);
	$userarr[2][0] = $usrrow['gy_emp_account'];
	$sqlwhere=" AND `emp_code`='".$usrcode."'";
}

$tckcnt = 0; $ticketarr = array(array());
if($deptid==22){
 include '../../config/connnk.php';
	$tktlist=$dbticket->query("SELECT `emp_code`,`channel`,`ticket_date` From `ticket` Where `ticket_date`>='".date("Y-m-d H:i:s",strtotime($fdate." 00:00:00"))."' AND `ticket_date`<='".date("Y-m-d H:i:s",strtotime($tdate." 24:00:00"))."' ".$sqlwhere);
    if(mysqli_num_rows($tktlist) > 0){
        while($tktrow=$tktlist->fetch_array()){
            $ticketarr[$tckcnt][0] = $tktrow['emp_code'];
            $ticketarr[$tckcnt][1] = $tktrow['channel'];
            $ticketarr[$tckcnt][2] = $tktrow['ticket_date'];
            $tckcnt++;
        }
    }
 $sqlwhere = "";
 $dbticket->close();
}

$sqlseq = "";
if($fdate == "" && $tdate != ""){ $sqlseq = "AND `gy_tracker_date` >='".date('Y-m-d', strtotime($tdate.' -1 day'))."' AND `gy_tracker_date` <= '".date('Y-m-d', strtotime($tdate.' +1 day'))."'"; }
else if($fdate != "" && $tdate == ""){ $sqlseq = "AND `gy_tracker_date` >='".date('Y-m-d', strtotime($fdate.' -1 day'))."' AND `gy_tracker_date` <= '".date('Y-m-d', strtotime($fdate.' +1 day'))."'"; }
else if($fdate != "" && $tdate != ""){ $sqlseq = "AND `gy_tracker_date` >='".date('Y-m-d', strtotime($fdate.' -1 day'))."' AND `gy_tracker_date` <= '".date('Y-m-d', strtotime($tdate.' +1 day'))."'"; }
else { $sqlseq = "AND `gy_tracker_date` >='".date("Y-m-d",' -1 day')."' AND `gy_tracker_date` <='".date("Y-m-d",' +1 day')."'"; }

for($i1=0;$i1<count($usrarr);$i1++){
$cntdate = date('Y-m-d', strtotime($fdate));
$tmsht=$link->query("SELECT * From `gy_tracker` Where `gy_emp_code`='".$usrarr[$i1]."' AND (`gy_tracker_request`='approve' OR `gy_tracker_request`='overtime') ".$sqlseq." Order By `gy_tracker_date` ASC");
while($tmsrow=$tmsht->fetch_array()){
	$truedate = array(0,0,"","");
	$truedate = matchsched($tmsrow['gy_emp_code'], $tmsrow['gy_tracker_date'], $tmsrow['gy_tracker_breakout'], $tmsrow['gy_tracker_breakin'], $tmsrow['gy_tracker_logout']);

	while($cntdate<date("Y-m-d", strtotime($truedate[0]))){
		$cnttruedate = matchsched($tmsrow['gy_emp_code'], $cntdate." 00:00:00", "0000-00-00 00:00:00", "0000-00-00 00:00:00", "0000-00-00 00:00:00");
        if($cnttruedate[0] == $truedate[0]){ $cnttruedate[1] = 0;  $cnttruedate[2] = ""; $cnttruedate[3] = ""; }
		tblcntt($cnttruedate[4], $tmsrow['gy_emp_code'], $userarr[0][$i1], $userarr[2][$i1], $userarr[1][$i1], $cntdate, $cnttruedate[2], $cnttruedate[3], "", "", "", "", 0);
		$cntdate = date('Y-m-d', strtotime($cntdate.' +1 day'));
	}if($cntdate>date("Y-m-d", strtotime($truedate[0]))){ $cntdate = date("Y-m-d", strtotime($truedate[0])); }

if(date("Y-m-d", strtotime($truedate[0]))>=date('Y-m-d', strtotime($fdate)) && date("Y-m-d", strtotime($truedate[0]))<=date('Y-m-d', strtotime($tdate))){
	//$tracnt = channelcount($tmsrow['gy_tracker_login'], $tmsrow['gy_tracker_logout'], $ticketarr, $tckcnt, $tmsrow['gy_emp_code']);
	tblcntt($truedate[4], $tmsrow['gy_emp_code'], $userarr[0][$i1], $userarr[2][$i1], $userarr[1][$i1], $truedate[0], $truedate[2], $truedate[3], $tmsrow['gy_tracker_login'], $tmsrow['gy_tracker_logout'], $tmsrow['gy_tracker_breakout'], $tmsrow['gy_tracker_breakin'], $tmsrow['gy_tracker_bh']);
}
	$cntdate = date('Y-m-d', strtotime($cntdate.' +1 day'));
}
	while($cntdate<=date("Y-m-d", strtotime($tdate))){
		$truedate = matchsched($usrarr[$i1], $cntdate." 00:00:00", "0000-00-00 00:00:00", "0000-00-00 00:00:00", "0000-00-00 00:00:00");
		if(strtotime($truedate[0]) == strtotime($cntdate.' -1 day')){ $truedate[1] = 0; $truedate[2] = ""; $truedate[3] = "";  }
		tblcntt($truedate[4], $usrarr[$i1], $userarr[0][$i1], $userarr[2][$i1], $userarr[1][$i1], $cntdate, $truedate[2], $truedate[3], "", "", "", "", 0);
		$cntdate = date('Y-m-d', strtotime($cntdate.' +1 day'));
	}
}
} $link->close();
?>

<?php function tblcntt($mod, $id, $name, $accnm, $tl, $date, $schin, $schout, $login, $logout, $bout, $bin, $bh){
	$ah=0; $th=0;
	if(($mod==1 || $mod==2)&&($schin!="" && $schout!="")){
		$fscdin=$date." ".date("H:i:s", strtotime(convert24to0($schin)));
		$fscdout=$date." ".date("H:i:s", strtotime(convert24to0($schout)));
		if($fscdin>=$fscdout){ $fscdout=date("Y-m-d H:i:s", strtotime($fscdout." +1 day")); }
		$th = getdtrwh($fscdin, $fscdout);

		if(($login!="" && $logout!="")&&($login!="0000-00-00 00:00:00" && $logout!="0000-00-00 00:00:00")){
			$tmpahin = $fscdin;
			$tmpahout= $fscdout;
			if($login>$fscdin){ $tmpahin = $login; }
			if($logout<$fscdout){ $tmpahout = $logout; }
			$ah = getdtrwh($tmpahin, $tmpahout);
		}
	}
	if(($bin!="" && $bout!="")&&($bin!="0000-00-00 00:00:00" && $bout!="0000-00-00 00:00:00")){
		$bh = getdtrwh($bout, $bin);
		if($bh<1 && $th>5){ $bh=1; }
		else if($bh<1 && $th<5){ $bh=0; }
	}
	if($th>=5){ $th-=1; $ah-=$bh; }

	$ccolor = ""; if($mod==0){ $schin="OFF"; $schout="OFF"; $login="OFF"; $logout="OFF"; $bin="OFF"; $bout="OFF"; $ccolor="bg-warning"; }
	if($bh<1){ $bh=1; } if($th<5){ $bh=0; }
	if(($schin!="" &&  $schout!="") && ($login == "0000-00-00 00:00:00" || $login == "" || $logout == "0000-00-00 00:00:00" || $logout == "") && strtotime($date) < strtotime(date("Y-m-d"))){
	$ccolor="bg-warning"; $login="ABSENT"; $logout="ABSENT"; $bin="ABSENT"; $bout="ABSENT"; $th=0; $bh=0; $ah=0; }
	if(strtotime($date) >= strtotime(date("Y-m-d"))){ $th=0; $bh=0; $ah=0; }
	if((gettwh($ah)<$th || $th<8)&&$mod!="0"){$ccolor.=" text-danger";}else if(gettwh($ah)>$th){$ccolor.=" text-primary";}
	?>
<tr>
	<td class="text-center text-nowrap <?php echo $ccolor; ?>"><?php echo $id; ?></td>
	<td class="text-center text-nowrap <?php echo $ccolor; ?>"><?php echo $name; ?></td>
	<td class="text-center text-nowrap <?php echo $ccolor; ?>"><?php echo $accnm; ?></td>
	<td class="text-center text-nowrap <?php echo $ccolor; ?>"><?php echo $tl; ?></td>
	<td class="text-center <?php echo $ccolor; ?>"><?php echo date("m/d/Y", strtotime($date)); ?></td>
	<td class="text-center text-nowrap <?php echo $ccolor; ?>" title="<?php echo simpdate($login); ?>"><?php echo chktime($login); ?></td>
	<td class="text-center text-nowrap <?php echo $ccolor; ?>" title="<?php echo simpdate($bout); ?>"><?php echo chktime($bout); ?></td>
	<td class="text-center text-nowrap <?php echo $ccolor; ?>" title="<?php echo simpdate($bin); ?>"><?php echo chktime($bin); ?></td>
	<td class="text-center text-nowrap <?php echo $ccolor; ?>" title="<?php echo simpdate($logout); ?>"><?php echo chktime($logout); ?></td>
	<td class="text-center text-nowrap <?php echo $ccolor; ?>"><?php echo chktime($schin); ?></td>
	<td class="text-center text-nowrap <?php echo $ccolor; ?>"><?php echo chktime($schout); ?></td>
	<td class="text-center <?php echo $ccolor; ?>"><?php echo $th; ?></td>
	<td class="text-center <?php echo $ccolor; ?>"><?php echo gettwh($ah); ?></td>
	<td class="text-center <?php echo $ccolor; ?>"><?php echo gettwh($bh); ?></td>
</tr>
<?php } ?>