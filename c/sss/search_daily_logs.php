<?php
include '../../config/conn.php';
include '../../config/function.php';
include 'session.php';

function getmode($empcode, $date, $mod){
    include '../../config/conn.php';
	$empsch=$link->query("SELECT `gy_sched_mode` FROM `gy_schedule` WHERE `gy_sched_day`='".$date."' AND `gy_sched_mode`!=1 AND `gy_emp_id`='".getempid($empcode)."' LIMIT 1");
    if(mysqli_num_rows($empsch) > 0){
		while ($modrow=$empsch->fetch_array()){ $mod = $modrow['gy_sched_mode']; }
    }
    $link->close();
    return $mod;
}

function matchsched($dbemp, $dblogin, $dbbreo, $dbbrei, $dblogo){
	include '../../config/conn.php';
	$today = date("Y-m-d", strtotime($dblogin));
	$yesterday = date("Y-m-d", strtotime($dblogin.' -1 day'));
	$tomorrow = date("Y-m-d", strtotime($dblogin.' +1 day'));
	$theemp = getempid($dbemp);
	$arrsched = array($today,0);
	$sqlemp="`gy_sched_day`>='".$yesterday."' AND `gy_sched_day`<='".$tomorrow."'";
    if($dblogo != "0000-00-00 00:00:00"){ $endday = $dblogo; }
    else if($dbbrei != "0000-00-00 00:00:00"){ $endday = $dbbrei; }
    else if($dbbreo != "0000-00-00 00:00:00"){ $endday = $dbbreo; }
    else { $endday = $dblogin; $sqlemp="`gy_sched_day`='".$today."'"; }
	$empsch=$link->query("SELECT `gy_sched_day`, `gy_sched_login`, `gy_sched_logout` FROM `gy_schedule` WHERE ".$sqlemp." AND `gy_emp_id`='".$theemp."' AND `gy_sched_mode`!=0 ORDER BY `gy_sched_day` ASC");
	if(mysqli_num_rows($empsch) > 0){
		while ($scrow=$empsch->fetch_array()) {
			if(date("H:i:s", strtotime(convert24to0($scrow['gy_sched_login']))) > date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout'])))){
				$schedlout = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout']))).' +1 day');
			}else{
				$schedlout = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout']))));
			}
            $schedin = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_login']))));
			if(strtotime($dblogin) < $schedlout && strtotime($endday) >= $schedin || $sqlemp=="`gy_sched_day`='".$today."'"){ $arrsched[0] = $scrow['gy_sched_day']; 
			$arrsched[1] = getwh(date("Y-m-d", strtotime($arrsched[0]))." ".convert24to0($scrow['gy_sched_login']), date("Y-m-d H:i:s", $schedlout));
			break; }
		}
	}
    $link->close();
	return $arrsched;
}

function getundert($dbemp, $correctdate, $dblogin, $dblogout, $trkid, $trkcd){
	include '../../config/conn.php';
	$theemp = getempid($dbemp);
	$ut = array(0,0,0);
	$empsch=$link->query("SELECT `gy_sched_day`, `gy_sched_login`, `gy_sched_logout` FROM `gy_schedule` WHERE `gy_sched_day`='".$correctdate."' AND `gy_emp_id`='".$theemp."' LIMIT 1");
	if(mysqli_num_rows($empsch) > 0){
		while ($scrow=$empsch->fetch_array()) {
			$schedin = date("Y-m-d H:i:s", strtotime($scrow['gy_sched_day']." ".convert24to0($scrow['gy_sched_login'])));
			if(date("H:i:s", strtotime(convert24to0($scrow['gy_sched_login']))) > date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout'])))){
			$schedout = date("Y-m-d H:i:s", strtotime($scrow['gy_sched_day']." ".convert24to0($scrow['gy_sched_logout']).' +1 day'));
			}else{ $schedout = date("Y-m-d H:i:s", strtotime($scrow['gy_sched_day']." ".$scrow['gy_sched_logout'])); }
			$dblogin = date("Y-m-d H:i:s", strtotime($dblogin));
			if($dblogout != "0000-00-00 00:00:00"){ $dblogout = date("Y-m-d H:i:s", strtotime($dblogout)); }
			if($dblogin > $schedin){ $ut[0] = getdtrwh($schedin, $dblogin); }
			if($dblogout < $schedout && $dblogout != "0000-00-00 00:00:00"){ $ut[1] = getdtrwh($dblogout, $schedout); }

			$requestdate="";
			$escsql=$link->query("SELECT `gy_tracker_login`, `gy_tracker_breakout`, `gy_tracker_breakin`, `gy_tracker_logout`, `gy_esc_date` FROM `gy_escalate` WHERE `gy_esc_type`=6 AND `gy_esc_status`=1 AND `gy_tracker_id`='$trkid' ORDER BY `gy_esc_id` desc Limit 1");
			while($escrow=$escsql->fetch_array()){
			    if($escrow['gy_tracker_login']<$schedin){ $tmpot=getdtrwh($escrow['gy_tracker_login'], $schedin); if($tmpot>=0.5){ $ut[2]+=$tmpot; } $tmpot=0; }
			    if($escrow['gy_tracker_logout']>$schedout){ $tmpot=getdtrwh($schedout, $escrow['gy_tracker_logout']); if($tmpot>=0.5){ $ut[2]+=$tmpot; } $tmpot=0; }
			    $requestdate=$escrow['gy_esc_date'];
			}
        	$schsql=$link->query("SELECT `gy_tracker_login`,`gy_tracker_logout`,`gy_sched_login`,`gy_sched_logout`,`gy_sched_day`,`gy_req_date` FROM `gy_schedule_escalate` WHERE `gy_req_status`=1 AND `gy_sched_mode`=2 AND `gy_tracker_login`!='0000-00-00 00:00:00' AND `gy_tracker_logout`!='0000-00-00 00:00:00' AND `gy_sched_esc_code`=$trkcd ORDER BY `gy_sched_esc_id` desc Limit 1");
            while($schrow=$schsql->fetch_array()){
                $tmpin = date("Y-m-d H:i:s", strtotime(date("Y-m-d", strtotime($schrow['gy_sched_day']))." ".date("H:i:s",strtotime($schrow['gy_sched_login']))));
                $tmpout= date("Y-m-d H:i:s", strtotime(date("Y-m-d", strtotime($schrow['gy_sched_day']))." ".date("H:i:s",strtotime($schrow['gy_sched_logout']))));
                if(strtotime($tmpin)>strtotime($tmpout)){ $tmpout=date("Y-m-d H:i:s", strtotime($tmpout."+1 day")); }
                
                if($schrow['gy_tracker_login']<$tmpin){ $tmpot=getdtrwh($schrow['gy_tracker_login'], $tmpin); if($tmpot>=0.5){ $ut[2]+=$tmpot; } $tmpot=0; }
                if($schrow['gy_tracker_logout']>$tmpout){ $tmpot=getdtrwh($tmpout, $schrow['gy_tracker_logout']); if($tmpot>=0.5){ $ut[2]+=$tmpot; } $tmpot=0; }
            }
		}
	}
    $link->close();
	return $ut;
}

function getloa($sibid, $thisdate){
    include '../../config/conn.php';
    $tmlvcnt = array(0, 0, 0, 0, 0);
    $ctlsql=$link->query("SELECT * FROM `gy_leave` LEFT JOIN `gy_user` ON `gy_leave`.`gy_user_id`=`gy_user`.`gy_user_id` WHERE `gy_leave`.`gy_leave_date_from`='$thisdate' AND `gy_user`.`gy_user_code`='$sibid'");
        if($ctlsql->num_rows>0){
        $ctlrow=$ctlsql->fetch_array();
            $tmlvcnt[0]=1;
            $tmlvcnt[1]=$ctlrow['gy_leave_status'];
            $tmlvcnt[2]=getomname($ctlrow['gy_leave_approver']);
            $tmlvcnt[3]=$ctlrow['gy_leave_reason'];
            $tmlvcnt[4]=$ctlrow['gy_leave_remarks'];
            $tmlvcnt[5]="Filed Date : ".$ctlrow['gy_leave_filed'];
            if($tmlvcnt[1]==1){ $tmlvcnt[5].=" , Approved Date : ".$ctlrow['gy_leave_date_approved']; }
            else if($tmlvcnt[1]==2){ $tmlvcnt[5].=" , Rejected Date : ".$ctlrow['gy_leave_date_approved']; }
        }
    $link->close();
    return $tmlvcnt;
}

function getomname($om){
    include '../../config/conn.php';
	$getom=$link->query("SELECT `gy_full_name` From `gy_user` Where `gy_user_id`='$om'");
	$omrow=$getom->fetch_array();
	return $omrow['gy_full_name'];
    $link->close();
}

function getcolor($mode, $color, $val){
	if($mode == 1 || $mode == 2){
		if($val < 8){ $color = 'text-danger'; }
		else if($val > 8.5){ $color = 'text-blue'; }
	}
	return $color;
}
function gettwh($wh){
if($wh < 0){ return 0; }
else { return $wh; }
}

function getdtrwh($sdate, $adate){
    $tosec = (strtotime($adate) - strtotime($sdate))/60;
    $hour = floor($tosec / 60);
    $min = floor($tosec % 60);

    $total = $hour*60;
    $total += $min;
    return $total/60;
}

$datefrom = addslashes($_REQUEST['datefrom']);
$dateto = addslashes($_REQUEST['dateto']);
$sqlseq = "";
if($datefrom == "" && $dateto != ""){ $sqlseq = "AND `gy_tracker_date` >='".date('Y-m-d', strtotime($dateto.' -1 day'))."' AND `gy_tracker_date` <= '".date('Y-m-d', strtotime($dateto.' +1 day'))."'"; }
else if($datefrom != "" && $dateto == ""){ $sqlseq = "AND `gy_tracker_date` >='".date('Y-m-d', strtotime($datefrom.' -1 day'))."' AND `gy_tracker_date` <= '".date('Y-m-d', strtotime($datefrom.' +1 day'))."'"; }
else if($datefrom != "" && $dateto != ""){ $sqlseq = "AND `gy_tracker_date` >='".date('Y-m-d', strtotime($datefrom.' -1 day'))."' AND `gy_tracker_date` <= '".date('Y-m-d', strtotime($dateto.' +1 day'))."'"; }
else { $sqlseq = "AND `gy_tracker_date` >='".date("Y-m-d",' -1 day')."' AND `gy_tracker_date` <='".date("Y-m-d",' +1 day')."'"; }
$i = 0;
$cntdate = date('Y-m-d', strtotime($datefrom));
$tmsht=$link->query("SELECT * From `gy_tracker` Where `gy_emp_code`='$user_code' ".$sqlseq." Order By `gy_tracker_date` ASC");
	while ($tsrow=$tmsht->fetch_array()) {
		$i++;
		$undertime = array(0,0);
		$truedate = array(0,0);
		$truedate = matchsched($tsrow['gy_emp_code'], $tsrow['gy_tracker_date'], $tsrow['gy_tracker_breakout'], $tsrow['gy_tracker_breakin'], $tsrow['gy_tracker_logout']);
		while($cntdate<date("Y-m-d", strtotime($truedate[0]))){
				$thismode = getmode($tsrow['gy_emp_code'], $cntdate, 100);
				$cnttruedate = matchsched($tsrow['gy_emp_code'], $cntdate." 00:00:00", "0000-00-00 00:00:00", "0000-00-00 00:00:00", "0000-00-00 00:00:00");
				if($cnttruedate[0] == $truedate[0]){ $cnttruedate[1] = 0; }
			tblcntt($thismode, date("m/d/Y", strtotime($cntdate)), "No Log", "No Log", "No Log", "No Log", $cnttruedate[1], 0, 0, $undertime, 0, "No Logs", 0, "", "", "", "", "", $tsrow['gy_emp_code']);
			$cntdate = date('Y-m-d', strtotime($cntdate.' +1 day')); }
            if($cntdate>date("Y-m-d", strtotime($truedate[0]))){ $cntdate = date("Y-m-d", strtotime($truedate[0])); }
			
			$thismode = getmode($tsrow['gy_emp_code'], $cntdate, 1);
			
			if ($tsrow['gy_tracker_status'] == 1) {
                if ($thismode == 1 || $thismode == 2) {
                	$undertime = getundert($tsrow['gy_emp_code'], $truedate[0], $tsrow['gy_tracker_login'], $tsrow['gy_tracker_logout'], $tsrow['gy_tracker_id'], $tsrow['gy_tracker_code']);
                }
            }
        if(date("Y-m-d", strtotime($truedate[0]))>=date('Y-m-d', strtotime($datefrom)) && date("Y-m-d", strtotime($truedate[0]))<=date('Y-m-d', strtotime($dateto))){
			tblcntt($thismode, date("m/d/Y", strtotime($truedate[0])), $tsrow['gy_tracker_login'], $tsrow['gy_tracker_logout'], $tsrow['gy_tracker_breakout'], $tsrow['gy_tracker_breakin'], $truedate[1], $tsrow['gy_tracker_bh'], $undertime[2], $undertime, $tsrow['gy_tracker_ath'], "Pending", $tsrow['gy_tracker_om'], $tsrow['gy_tracker_request'], $tsrow['gy_tracker_id'], $tsrow['gy_tracker_reason'], $tsrow['gy_tracker_remarks'], $tsrow['gy_tracker_history'], $tsrow['gy_emp_code']);
        }
	$cntdate = date('Y-m-d', strtotime($cntdate.' +1 day'));
	}
	$fullname = get_emp_name($user_code);
	while($cntdate<=date("Y-m-d", strtotime($dateto))){
        $undertime = array(0,0);
		$truedate = matchsched($user_code, $cntdate." 00:00:00", "0000-00-00 00:00:00", "0000-00-00 00:00:00", "0000-00-00 00:00:00");
		$thismode = getmode($user_code, $cntdate, 100);
		if(strtotime($truedate[0]) == strtotime($cntdate.' -1 day')){ $truedate[1] = 0; }
		tblcntt($thismode, date("m/d/Y", strtotime($cntdate)), "No Log", "No Log", "No Log", "No Log", $truedate[1], 0, 0, $undertime, 0, "No Logs", 0, "", "", "", "", "", $user_code);
		$cntdate = date('Y-m-d', strtotime($cntdate.' +1 day'));
	}
	if($i == 0){ echo "No Logs Found"; }
    $link->close();
 function tblcntt($mode, $date, $in, $out, $bout, $bin, $wh, $bh, $ot, $utl, $ath, $omname, $om, $request, $trackid, $reason, $remarks, $history, $sbsid){
 $myom = "";
 if(($bout!="No Log" && $bin!="No Log") && ($bout!="0000-00-00 00:00:00" && $bin!="0000-00-00 00:00:00") && ($bout!="" && $bin!="")){
     $bh=round(getdtrwh($bout, $bin),2);
     if($bh<1){ $bh=1; }
 }
 if($wh<5){ $bh=0; }
 $txtcolor = array("","","","","","","","","","text-danger");
 $loa = getloa($sbsid, date("Y-m-d", strtotime($date)));
 if($mode==2){$omname='RDOT'; $txtcolor=array_fill(0, 9, 'text-blue'); $txtcolor[9] = '';}
 if($mode==0){
     if($in=='No Log'){$in='OFF';}
     if($out=='No Log'){$out='OFF';}
     if($bout=='No Log'){$bout='OFF';}
     if($bin=='No Log'){$bin='OFF';}
     $wh=0; $bh=0; $ot=0; $omname='RD'; $txtcolor=array_fill(0, 9, 'text-blue'); $txtcolor[9] = '';}
 else if(($mode==1 || $mode==2 ) && $om!=0){
 	$myom = getomname($om);
 	if($request == "approve" || ($request=="overtime"&&$mode==2)){ if($omname=="RDOT"){ $omname="Approved RDOT <i class='fa fa-check'></i>"; }else{ $omname="Approved <i class='fa fa-check'></i>"; $ot=0; }
 		$txtcolor=array_fill(0, 9, 'text-success'); $txtcolor[9] = '';}
 	else if($request == "overtime"){ $omname = "Approved OT <i class='fa fa-check'></i>";
 		$txtcolor=array_fill(0, 9, 'text-success'); $txtcolor[9] = '';}
 	else if($request == "reject"){ $omname = "Rejected <i class='fa fa-times'></i>";
 		$txtcolor=array_fill(0, 10, 'text-danger'); $ot=0; }
 	else if($request == "escalate"){ $omname = "Escalating <i class='fa fa-arrow-up'></i>";
 		$txtcolor=array_fill(0, 9, 'text-esc'); $txtcolor[9] = 'text-danger'; $ot=0; }
 }
 else if($mode==100){$txtcolor=array_fill(0, 10, 'text-danger'); }
 if($loa[0]==1){ $reason=$loa[3]; $history=$loa[5];
 	if($loa[1]==0){ $txtcolor=array_fill(0, 9, 'text-warning'); $txtcolor[9]=''; $omname="Pending LOA <i class='fa-solid fa-chalkboard-user'></i>"; $ot=0; }
 	else if($loa[1]==1){ $txtcolor=array_fill(0, 9, 'text-success'); $txtcolor[9]=''; $omname="Approved LOA <i class='fa-solid fa-calendar-check'></i>"; $myom = $loa[2]; $ot=0; }
 	else if($loa[1]==2){ $txtcolor=array_fill(0, 9, 'text-danger'); $txtcolor[9]=''; $omname="Rejected LOA <i class='fa-solid fa-handshake-slash'></i>"; $myom = $loa[2]; $remarks=$loa[4]; $ot=0; }
 	else { $txtcolor=array_fill(0, 9, 'text-secondary'); $omname="Cancelled LOA <i class='fa-solid fa-circle-xmark'></i>"; $ot=0; }
 }

 if($utl[0] > 0){ $txtcolor[1] = 'text-danger'; }
 if($utl[1] > 0){ $txtcolor[4] = 'text-danger'; }
 if($in=="0000-00-00 00:00:00" || $out=="0000-00-00 00:00:00"){ $ot=0; }
 if($mode!=0){ $theath = getath($out, $wh, $bh, $ot, $utl, $ath); }else{ $theath = 0; }
?>
<tr class="mybg">
<td style="padding: 5px;" class="text-center <?php echo $txtcolor[0]; ?>"><?php echo $date; ?></td>
<td style="padding: 5px;" class="text-center <?php echo $txtcolor[1]; ?>" title="<?php echo simpdate($in); ?>">
	<?php echo chktime($in); ?>
</td>
<td style="padding: 5px;" class="text-center <?php echo $txtcolor[2]; ?>" title="<?php echo simpdate($bout); ?>">
	<?php echo chktime($bout); ?>
</td>
<td style="padding: 5px;" class="text-center <?php echo $txtcolor[3]; ?>" title="<?php echo simpdate($bin); ?>">
	<?php echo chktime($bin); ?>
</td>
<td style="padding: 5px;" class="text-center <?php echo $txtcolor[4]; ?>" title="<?php echo simpdate($out); ?>">
	<?php echo chktime($out); ?>
</td>
<td style="padding: 5px;" class="text-center <?php echo getcolor($mode, $txtcolor[5], $wh - $bh); ?>"><?php if($bh>1 && $wh>=5){echo gettwh($wh - 1);}else{ echo gettwh($wh - $bh); } ?></td>
<td style="padding: 5px;" class="text-center <?php echo $txtcolor[6]; ?>"><?php echo $bh; ?></td>
<td style="padding: 5px;" class="text-center <?php echo $txtcolor[7]; ?>"><?php echo round($ot,2); ?></td>
<td style="padding: 5px;" class="text-center text-danger"><?php echo round($utl[0]+$utl[1],2); ?></td>
<td style="padding: 5px;" class="text-center <?php echo $txtcolor[9]; ?>"><?php echo round($theath, 2); ?></td>
<td style="padding: 5px;" class="text-center <?php echo $txtcolor[8]; ?>"><i><?php echo $omname; ?></i></td>
<td style="padding: 1px;" class="text-center">
	<?php if($trackid!="" || $loa[0]==1){?>
	<button type="button" data-toggle="modal" data-target="#info_<?= $trackid;  ?>" class="btn btn-warning btn-sm btn-block" title="click to show details ..."><i class="fa fa-eye"></i></button>
	<?php } ?>
</td>
</tr>
<?php if($trackid!="" || $loa[0]==1){ ?>
<div class="modal fade" id="info_<?php echo $trackid; ?>" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="smallmodalLabel"><i class="fa fa-eye"></i> Remarks/Update Log</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>
                                <strong style="text-transform: uppercase;">
                                <?= $omname; ?> <small style="font-weight: normal;"><?= $myom; ?></small><br>
                                <?= $reason; ?>
                                </strong>
                                <br>
                                <?= $remarks; ?>
                            </label>
                            <hr>
                            <center><p>-Updates-</p></center>
                            <label style="font-size: 12px;">
                                <?php 
                                    $history = explode(",", $history);

                                    foreach ($history as $logs) {
                                        echo $logs."<br>";
                                    }
                                ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php }} ?>