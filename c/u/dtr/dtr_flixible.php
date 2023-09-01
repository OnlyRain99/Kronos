<?php
include '../../../config/conn.php';
include '../../../config/function.php';
include '../session.php';
date_default_timezone_set('Asia/Taipei');

function ifot($trkid, $trkcd, $lilo, $scdate, $scin, $scout){
$sftin = strtotime(date("Y-m-d", strtotime($scdate))." ".convert24to0($scin));
$sftout = strtotime(date("Y-m-d", strtotime($scdate))." ".convert24to0($scout));
if($sftin>$sftout){ $sftout = strtotime(date("Y-m-d", strtotime($scdate))." ".$scout."+1 day"); }

if($sftin>strtotime($lilo[0])){ $lilo[0]=date("Y-m-d H:i:s", $sftin); }
if($sftout<strtotime($lilo[3])){ $lilo[3]=date("Y-m-d H:i:s", $sftout); }
$requestdate="";
    include '../../../config/conn.php';
    $escsql=$link->query("SELECT `gy_tracker_login`, `gy_tracker_breakout`, `gy_tracker_breakin`, `gy_tracker_logout`, `gy_esc_date` FROM `gy_escalate` WHERE `gy_esc_type`=6 AND `gy_esc_status`=1 AND `gy_tracker_id`='$trkid' ORDER BY `gy_esc_id` desc Limit 1");
        while($escrow=$escsql->fetch_array()){
            if(strtotime($escrow['gy_tracker_login'])<$sftin && strtotime($escrow['gy_tracker_logout'])>$sftout && getdtrwh($escrow['gy_tracker_login'], date("Y-m-d H:i:s", $sftin))>=0.5 && getdtrwh(date("Y-m-d H:i:s", $sftout), $escrow['gy_tracker_logout'])>=0.5){
                $lilo[0]=$escrow['gy_tracker_login'];
                $lilo[3]=$escrow['gy_tracker_logout'];
                $lilo[4]="Approved OT ".date("h:iA", strtotime($lilo[0]))." and ".date("h:ia", strtotime($lilo[3])); }
            else if(strtotime($escrow['gy_tracker_login'])<$sftin && getdtrwh($escrow['gy_tracker_login'], date("Y-m-d H:i:s", $sftin))>=0.5){ $lilo[0]=$escrow['gy_tracker_login']; $lilo[4]="Approved Early OT from ".date("h:iA", strtotime($lilo[0])); }
            else if(strtotime($escrow['gy_tracker_logout'])>$sftout && getdtrwh(date("Y-m-d H:i:s", $sftout), $escrow['gy_tracker_logout'])>=0.5){ $lilo[3]=$escrow['gy_tracker_logout']; $lilo[4]="Approved OT until ".date("h:iA", strtotime($lilo[3])); }
            $requestdate=$escrow['gy_esc_date'];
        }

    $schsql=$link->query("SELECT `gy_tracker_login`,`gy_tracker_logout`,`gy_sched_login`,`gy_sched_logout`,`gy_sched_day`,`gy_req_date` FROM `gy_schedule_escalate` WHERE `gy_req_status`=1 AND `gy_sched_mode`=2 AND `gy_tracker_login`!='0000-00-00 00:00:00' AND `gy_tracker_logout`!='0000-00-00 00:00:00' AND `gy_sched_esc_code`=$trkcd ORDER BY `gy_sched_esc_id` desc Limit 1");
        while($schrow=$schsql->fetch_array()){
          if(strtotime($schrow['gy_req_date'])>strtotime($requestdate)){
            $tmpin = date("Y-m-d H:i:s", strtotime(date("Y-m-d", strtotime($schrow['gy_sched_day']))." ".date("H:i:s",strtotime($schrow['gy_sched_login']))));
            $tmpout= date("Y-m-d H:i:s", strtotime(date("Y-m-d", strtotime($schrow['gy_sched_day']))." ".date("H:i:s",strtotime($schrow['gy_sched_logout']))));
             if(strtotime($tmpin)>strtotime($tmpout)){ $tmpout=date("Y-m-d H:i:s", strtotime($tmpout."+1 day")); }
             if(strtotime($schrow['gy_tracker_login'])<strtotime($tmpin) && strtotime($schrow['gy_tracker_logout'])>strtotime($tmpout) && getdtrwh($schrow['gy_tracker_login'], $tmpin)>=0.5 && getdtrwh($tmpout, $schrow['gy_tracker_logout'])>=0.5){
                 $lilo[0]=$schrow['gy_tracker_login'];
                 $lilo[3]=$schrow['gy_tracker_logout'];
                 $lilo[4]="Approved OT ".date("h:iA", strtotime($lilo[0]))." and ".date("h:iA", strtotime($lilo[3])); }
             else if(strtotime($schrow['gy_tracker_login'])<strtotime($tmpin) && getdtrwh($schrow['gy_tracker_login'], $tmpin)>=0.5){ $lilo[0]=$schrow['gy_tracker_login']; $lilo[4]="Approved Early OT from ".date("h:iA", strtotime($lilo[0])); }
             else if(strtotime($schrow['gy_tracker_logout'])>strtotime($tmpout) && getdtrwh($tmpout, $schrow['gy_tracker_logout'])>=0.5){ $lilo[3]=$schrow['gy_tracker_logout']; $lilo[4]="Approved OT until ".date("h:iA", strtotime($lilo[3])); }
          }
        }
    $link->close();
    return $lilo;
}

function getmode($empcode, $date, $mod){
    include '../../../config/conn.php';
    $empsch=$link->query("SELECT `gy_sched_mode` FROM `gy_schedule` WHERE `gy_sched_day`='".$date."' AND `gy_emp_id`='".getempid($empcode)."' LIMIT 1");
        while ($modrow=$empsch->fetch_array()){ $mod = $modrow['gy_sched_mode']; }
    $link->close();
    return $mod;
}

function matchsched($dbemp, $dblogin, $dbbreo, $dbbrei, $dblogo){
    $today = date("Y-m-d", strtotime($dblogin));
    $arrsched = array($today,0,"","");
    if($dblogin!="0000-00-00 00:00:00" && $dblogo!="0000-00-00 00:00:00"){
    $arrsched[1]=getdtrwh(date("Y-m-d H:i:s", strtotime($dblogin)), date("Y-m-d H:i:s", strtotime($dblogo)));
    $arrsched[2]=date("H:i:s", strtotime($dblogin));
    $arrsched[3]=date("H:i:s", strtotime($dblogo));
	}
    return $arrsched;
}

function getundert($dbemp, $correctdate, $dblogin, $dblogout, $dbbrkout, $dbbrkin){
    include '../../../config/conn.php';
    $theemp = getempid($dbemp);
    $ut = array(0,0,0);
    $empsch=$link->query("SELECT `gy_sched_day`, `gy_sched_login`, `gy_sched_logout` FROM `gy_schedule` WHERE `gy_sched_day`='".$correctdate."' AND `gy_emp_id`='".$theemp."' LIMIT 1");
    if(mysqli_num_rows($empsch) > 0){
        while ($scrow=$empsch->fetch_array()) {
            $schedin = date("Y-m-d H:i:s", strtotime($scrow['gy_sched_day']." ".convert24to0($scrow['gy_sched_login'])));
            if(date("H:i:s", strtotime($scrow['gy_sched_login'])) > date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout'])))){
            $schedout = date("Y-m-d H:i:s", strtotime($scrow['gy_sched_day']." ".convert24to0($scrow['gy_sched_logout']).' +1 day'));
            }else{ $schedout = date("Y-m-d H:i:s", strtotime($scrow['gy_sched_day']." ".convert24to0($scrow['gy_sched_logout']))); }
            $dblogin = date("Y-m-d H:i:s", strtotime($dblogin));
            if($dblogout != "0000-00-00 00:00:00"){ $dblogout = date("Y-m-d H:i:s", strtotime($dblogout)); }
            if($dblogin > $schedin){ $ut[0] = getdtrmindif($schedin, $dblogin, "in"); }
            if($dblogout < $schedout && $dblogout != "0000-00-00 00:00:00"){ $ut[1] = getdtrmindif($dblogout, $schedout, "out"); }

            if($dblogin < $schedin){ $ut[2] = getdtrmindif($dblogin, $schedin, "in"); }
            if($dblogout > $schedout && $dblogout != "0000-00-00 00:00:00"){ $ut[2] += getdtrmindif($schedout, $dblogout, "out"); }
        }
    }
    $ut[2]=getdtrwh($dbbrkout, $dbbrkin);
    $link->close();
    return $ut;
}

function getholstatus($mode, $holdate, $shiftin, $shiftout, $lin, $lout, $bout, $bin, $hlarr, $asloc){
    include '../../../config/conn.php';
    $holarr = array(array());
    for($i=0;$i<=count($hlarr);$i++){ for($i1=0;$i1<8;$i1++){ $holarr[$i][$i1]=""; }}
    return $holarr;    
}

function getdaystatus($holdate){
    include '../../../config/conn.php';
    $holid = array("","","");
    $hddate = date("Y-m-d",strtotime($holdate));
    $curyear = date("Y",strtotime($holdate));
    $curmonth = date("m",strtotime($holdate));
    $curday = date("d",strtotime($holdate));
    $dssql=$link->query("SELECT * FROM `gy_holiday_calendar` LEFT JOIN `gy_holiday_types` ON `gy_holiday_calendar`.`gy_hol_type_id`=`gy_holiday_types`.`gy_hol_type_id` WHERE (`gy_holiday_calendar`.`gy_a_year`=1 AND `gy_holiday_calendar`.`gy_hol_date`='$hddate')OR(`gy_holiday_calendar`.`gy_a_year`=0 AND Year(`gy_holiday_calendar`.`gy_hol_date`)<='$curyear' AND (Year(`gy_holiday_calendar`.`gy_hol_lastday`)='0000' OR (Year(`gy_holiday_calendar`.`gy_hol_lastday`)!='0000' AND Year(`gy_holiday_calendar`.`gy_hol_lastday`)>='$curyear' ) ) )AND(MONTH(`gy_holiday_calendar`.`gy_hol_date`)='$curmonth'AND DAY(`gy_holiday_calendar`.`gy_hol_date`)='$curday') ");
        while($dsrow=$dssql->fetch_assoc()){
            if($holid[0]!=""){ $holid[0].="/"; }
            $holid[0].=strtoupper($dsrow['gy_hol_abbrv']);
            $holid[1]=$dsrow['lateut'];
            $holid[2]=$dsrow['absnt'];
        }
    $link->close();
    return $holid;
}

    function getloa($sibid, $thisdate){
    include '../../../config/conn.php';
    $tmlvcnt = array(0, "", 0, 0, 0);
    $ctlsql=$link->query("SELECT * FROM `gy_leave` LEFT JOIN `gy_user` ON `gy_leave`.`gy_user_id`=`gy_user`.`gy_user_id` WHERE `gy_leave`.`gy_leave_date_from`='$thisdate' AND `gy_user`.`gy_user_code`='$sibid' AND `gy_leave`.`gy_leave_status`=1 AND `gy_leave`.`gy_publish`=1 ");
        if($ctlsql->num_rows>0){
        $ctlrow=$ctlsql->fetch_array();
            $tmlvcnt[0]=1;
            if($ctlrow['gy_leave_paid']==0 && $ctlrow['gy_leave_day']==1){ $tmlvcnt[1]="Approved LOA (No Pay)"; }
            else if($ctlrow['gy_leave_paid']==1 && $ctlrow['gy_leave_day']==1){ $tmlvcnt[1]="Approved LOA (With Pay)"; }
            else if($ctlrow['gy_leave_paid']==0 && $ctlrow['gy_leave_day']==0.5){ $tmlvcnt[1]="Approved Half Day LOA (No Pay)"; }
            else if($ctlrow['gy_leave_paid']==1 && $ctlrow['gy_leave_day']==0.5){ $tmlvcnt[1]="Approved Half Day LOA (With Pay)"; }
            $tmlvcnt[2]=$ctlrow['gy_leave_paid'];
            $tmlvcnt[3]=$ctlrow['gy_emp_rate'];
            $tmlvcnt[4]=$ctlrow['gy_leave_day'];
        }
    $link->close();
    return $tmlvcnt;
    }

    function getdtrmindif($sdate, $adate, $mod){
        $tosec = strtotime($adate) - strtotime($sdate);
        $hour = floor($tosec / 3600);
        if($mod == "in"){ $min = floor(($tosec - 3600 * $hour)/60); }
        else if($mod == "out"){ $min = ceil(($tosec - 3600 * $hour)/60); }
        $total = $hour*60;
        $total += $min;
        return $total/60;
    }

    function getdtrwh($sdate, $adate){
        $tosec = (strtotime($adate) - strtotime($sdate))/60;
        $hour = floor($tosec / 60);
        $min = floor($tosec % 60);

        $total = $hour*60;
        $total += $min;
        return $total/60;
    }

    function getdstrdf($strdtm){
        $tosec = (int)$strdtm/60;
        $hour = floor($tosec / 60);
        $min = floor($tosec % 60);

        $total = $hour*60;
        $total += $min;
        $total = round($total/60,9);
        if($total==0){$total="";}
        return $total;
    }

$sibsid = addslashes($_REQUEST['sibsid']);
$year = addslashes($_REQUEST['year']);
$month = addslashes($_REQUEST['month']);
$cutoff = addslashes($_REQUEST['cutoff']);
$emprate = addslashes($_REQUEST['dmrate']);

$empsql=$link->query("SELECT `gy_emp_fullname`,`gy_emp_rate`,`gy_assignedloc` From `gy_employee` Where `gy_emp_code`='$sibsid' LIMIT 1");
$emprow=$empsql->fetch_array();
$fullname = $emprow['gy_emp_fullname'];
$asloc = $emprow['gy_assignedloc'];

$htrw = 1; $htarr = array(); $hlarr = array();
$holtsql=$link->query("SELECT `gy_hol_type_id`,`gy_hol_abbrv` FROM `gy_holiday_types` WHERE `gy_hol_status`=1 ORDER BY `gy_hol_type_id` desc");
while ($holtrow=$holtsql->fetch_array()){
    $hlarr[$htrw] = $holtrow['gy_hol_type_id'];
    $htarr[$htrw] = strtoupper($holtrow['gy_hol_abbrv']);
    $htrw++;
}
$lastrow = array();
?>
    <div class="table-responsive">
       <table class="table  table-hover table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
            <thead>
                <tr style="padding:4px;" class="text-center text-nowrap bg-primary text-white">
                   <th scope="col" >SIBS-<?php echo $sibsid; ?></th>
                   <th scope="col" ><?php echo $fullname; ?></th>
                   <th scope="col" style="position:sticky; left:0; top:0;" class="bg-primary">Date</th>
                   <th scope="col" >Shift</th>
                   <th scope="col" >Log IN</th>
                   <th scope="col" >Log OUT</th>

                   <th scope="col" >No Of Hours<?php $lastrow[0]=""; ?></th>
                   <th scope="col" >Late|UT<?php $lastrow[1]=""; ?></th>
                   <th scope="col" >Absences<?php $lastrow[2]=""; ?></th>

                   <th scope="col" >Reg|OT<?php $lastrow[3]=""; ?></th>
                   <th scope="col" >RD|Reg<?php $lastrow[4]=""; ?></th>
                   <th scope="col" >RD|OT<?php $lastrow[5]=""; ?></th>

                <?php $lrc=6; for($i=1;$i<$htrw;$i++){?>
                    <th scope="col" ><?php $lastrow[$lrc]="";$lrc++; echo $htarr[$i]; ?>|Reg</th>
                   <th scope="col" ><?php $lastrow[$lrc]="";$lrc++; echo $htarr[$i]; ?>|OT</th>
                   <th scope="col" ><?php $lastrow[$lrc]="";$lrc++; echo $htarr[$i]; ?>|RD|Reg</th>
                   <th scope="col" ><?php $lastrow[$lrc]="";$lrc++; echo $htarr[$i]; ?>|RD|OT</th>
                <?php } ?>
                   <th scope="col" ><?php $lastrow[$lrc]="";$lrc++; ?>ND|Reg</th>
                   <th scope="col" ><?php $lastrow[$lrc]="";$lrc++; ?>ND|Reg|OT</th>
                   <th scope="col" ><?php $lastrow[$lrc]="";$lrc++; ?>ND|RD|Reg</th>
                   <th scope="col" ><?php $lastrow[$lrc]="";$lrc++; ?>ND|RD|OT</th>
                <?php for($i=1;$i<$htrw;$i++){?>
                   <th scope="col" >ND|<?php $lastrow[$lrc]="";$lrc++; echo $htarr[$i]; ?></th>
                   <th scope="col" >ND|<?php $lastrow[$lrc]="";$lrc++; echo $htarr[$i]; ?>|OT</th>
                   <th scope="col" >ND|<?php $lastrow[$lrc]="";$lrc++; echo $htarr[$i]; ?>|RD</th>
                   <th scope="col" >ND|<?php $lastrow[$lrc]="";$lrc++; echo $htarr[$i]; ?>|RD|OT</th>
                <?php } ?>
                </tr>
            </thead>
        	<tbody>
<?php
$sqlseq = "";
if($cutoff==1){ $datefrom=date('Y-m-d', strtotime($year.'-'.$month.'-1')); $dateto=date('Y-m-d', strtotime($year.'-'.$month.'-15')); $sqlseq="AND `gy_tracker_date` >='".date("Y-m-d", strtotime($datefrom.' -1 day'))."' AND `gy_tracker_date` <= '".date("Y-m-d", strtotime($dateto.' + 1 day'))."'"; }
else if($cutoff==2){ $datefrom=date('Y-m-d', strtotime($year.'-'.$month.'-16')); $dateto=date('Y-m-t', strtotime($year.'-'.$month.'-16')); $sqlseq="AND `gy_tracker_date` >='".date("Y-m-d", strtotime($datefrom.' -1 day'))."' AND `gy_tracker_date` <= '".date("Y-m-d", strtotime($dateto.' + 1 day'))."'"; }

$i = 0; $dtrarr = array(array());
$cntdate = date('Y-m-d', strtotime($datefrom));
$tmsht=$link->query("SELECT * From `gy_tracker` Where `gy_emp_code`='".$sibsid."' AND `gy_tracker_request`!='reject' AND `gy_tracker_request`!='' AND `gy_tracker_status`=1 ".$sqlseq." Order By `gy_tracker_date` ASC");
    while ($tsrow=$tmsht->fetch_array()) {
        $i++;
        $undertime = array(0,0,0);
        $truedate = array(0,0,"","");
		$asloc = $tsrow['gy_tracker_loc'];
        $lilo = array($tsrow['gy_tracker_login'],$tsrow['gy_tracker_breakout'],$tsrow['gy_tracker_breakin'],$tsrow['gy_tracker_logout'], "");
        $truedate = matchsched($tsrow['gy_emp_code'], $tsrow['gy_tracker_date'], $lilo[1], $lilo[2], $lilo[3]);
        $lilo = ifot($tsrow['gy_tracker_id'], $tsrow['gy_tracker_code'], $lilo, $truedate[0], $truedate[2], $truedate[3]);

        while($cntdate<date("Y-m-d", strtotime($truedate[0]))){
                $cnttruedate = matchsched($tsrow['gy_emp_code'], $cntdate." 00:00:00", "0000-00-00 00:00:00", "0000-00-00 00:00:00", "0000-00-00 00:00:00");
                $thismode = getmode($tsrow['gy_emp_code'], $cntdate, 100);
                if($cnttruedate[0] == $truedate[0]){ $cnttruedate[1] = 0;  $cnttruedate[2] = ""; $cnttruedate[3] = ""; }
                $absent="";
            $dayholsta = array(array());
            $lastrow=tblcntt($thismode, $tsrow['gy_emp_code'], date("m/d/Y", strtotime($cntdate)), chktime($cnttruedate[2]).' - '.chktime($cnttruedate[3]), "0000-00-00 00:00:00", "0000-00-00 00:00:00", $cnttruedate[1], 0, $undertime, $absent, $htrw, $dayholsta, $lastrow, "", $emprate);  unset($dayholsta);
            $cntdate = date('Y-m-d', strtotime($cntdate.' +1 day')); }
            if($cntdate>date("Y-m-d", strtotime($truedate[0]))){ $cntdate = date("Y-m-d", strtotime($truedate[0])); }
    
            $thismode = getmode($tsrow['gy_emp_code'], $cntdate, 1);
            if ($tsrow['gy_tracker_status'] == 1) {
                if ($thismode == 1 || $thismode == 2) {
                    $undertime = getundert($tsrow['gy_emp_code'], $truedate[0], $tsrow['gy_tracker_login'], $tsrow['gy_tracker_logout'],$tsrow['gy_tracker_breakout'],$tsrow['gy_tracker_breakin']);
                }
            }
            $absent="";

        if(date("Y-m-d", strtotime($truedate[0]))>=date('Y-m-d', strtotime($datefrom)) && date("Y-m-d", strtotime($truedate[0]))<=date('Y-m-d', strtotime($dateto))){
            $dayholsta = array(array());
            $dayholsta = getholstatus($thismode, date("Y-m-d", strtotime($truedate[0])), $truedate[2], $truedate[3], $lilo[0], $lilo[3], $lilo[1], $lilo[2], $hlarr, $asloc);
            $lastrow=tblcntt($thismode, $tsrow['gy_emp_code'], date("m/d/Y", strtotime($truedate[0])), chktime($truedate[2]).' - '.chktime($truedate[3]), $tsrow['gy_tracker_login'], $tsrow['gy_tracker_logout'], $truedate[1], $tsrow['gy_tracker_bh'], $undertime, $absent, $htrw,$dayholsta, $lastrow, $lilo[4],$emprate); unset($dayholsta);
        }
            $cntdate = date('Y-m-d', strtotime($cntdate.' +1 day'));
            unset($lilo);
    }
    while($cntdate<=date("Y-m-d", strtotime($dateto))){
          $undertime = array(0,0,0);
          $truedate = matchsched($sibsid, $cntdate." 00:00:00", "0000-00-00 00:00:00", "0000-00-00 00:00:00", "0000-00-00 00:00:00");
        $thismode = getmode($sibsid, $cntdate, 100);
        $absent="";
    if(strtotime($truedate[0]) == strtotime($cntdate.' -1 day')){ $truedate[1] = 0; $truedate[2] = ""; $truedate[3] = "";  }
	$dayholsta = array(array());
	$lastrow=tblcntt($thismode, $sibsid, date("m/d/Y", strtotime($cntdate)), chktime($truedate[2]).' - '.chktime($truedate[3]), "0000-00-00 00:00:00", "0000-00-00 00:00:00", $truedate[1], 0, $undertime, $absent, $htrw, $dayholsta, $lastrow, "",$emprate); unset($dayholsta);
    $cntdate = date('Y-m-d', strtotime($cntdate.' +1 day'));
    }

$link->close();

function ifzero($numval){
    if($numval<=0){ $numval=""; }
    return $numval;
}

function tblcntt($mode, $sbsid, $date, $shift, $lin, $lout, $wh, $bh, $utl, $absnt, $htrw, $holarr, $lrow, $aprvnote, $empryt){
    $rcolor=""; $txtcolor=array("","");
    $abrv = getdaystatus(date("Y-m-d", strtotime($date)));
    $swh = $wh - $utl[2];
    if($utl[2]>=1){ $utl[2] = $utl[2] - 1; }else{ $utl[2]=0; }
    if($mode==0 || $mode==2){ $rcolor="bg-warning"; $swh=""; $utl[0]=0; $utl[1]=0; $utl[2]=0; }
    $in=chktime($lin);
    $out=chktime($lout);
    if($in=="--:--"){ $in=""; $swh=""; $utl[0]=0; $utl[1]=0; $utl[2]=0; unset($holarr); }
    if($out=="--:--"){ $out=""; $swh=""; $utl[0]=0; $utl[1]=0; $utl[2]=0; unset($holarr); }
    if($shift=="--:-- - --:--"){ $shift=""; $swh=""; $utl[0]=0; $utl[1]=0; $utl[2]=0; unset($holarr); }
?>
    <tr class="text-nowrap <?php echo $rcolor; ?>">
        <td><?php echo $abrv[0];
        if($mode==0){ echo"RD"; }else if($mode==2){ echo"RDOT"; }?></td>
        <td style="font-size:<?php if(strlen($aprvnote)>26){echo'12px';}else{echo'16px';}?>; padding-left:0px; padding-right:0px;"><?php echo $aprvnote; ?></td>
        <td style="position:sticky; left:0;" class="<?php if($rcolor!=""){echo $rcolor;}else{echo "bg-light";} ?>"><?php echo $date; ?></td>
        <td><?php if($shift!=""){echo $shift;}else if($mode==0 || $mode==2){echo "Rest Day";} ?></td>
        <td class="<?php echo $txtcolor[0]; ?>" title="<?php echo simpdate($lin); ?>"><?php echo $in; ?></td>
        <td class="<?php echo $txtcolor[1]; ?>" title="<?php echo simpdate($lout); ?>"><?php echo $out; ?></td>

        <td><?php $tmprw = ifzero(round($swh,2)); $lrow[0]=(double)$lrow[0]+(double)$tmprw; echo $tmprw; ?></td>
        <td><?php $tmprw = ifzero(round(0,9)); $lrow[1]=(double)$lrow[1]+(double)$tmprw; echo $tmprw; ?></td>
        <td><?php $tmprw = $absnt; $lrow[2]=(double)$lrow[2]+(double)$tmprw; echo $absnt; ?></td>

     <?php $lrw=3; for($i=1;$i<4;$i++){ ?>
        <td><?php if(isset($holarr[0][$i])){ $lrow[$lrw]=(double)$lrow[$lrw]+(double)$holarr[0][$i]; echo getdstrdf($holarr[0][$i]); } $lrw++; ?></td>
     <?php } for($i=1;$i<$htrw;$i++){ for($i1=0;$i1<4;$i1++){ ?>
        <td><?php if(isset($holarr[$i][$i1])){ $lrow[$lrw]=(double)$lrow[$lrw]+(double)$holarr[$i][$i1]; echo getdstrdf($holarr[$i][$i1]); } $lrw++; ?></td>
    <?php }} for($i=4;$i<8;$i++){ ?>
        <td><?php if(isset($holarr[0][$i])){ $lrow[$lrw]=(double)$lrow[$lrw]+(double)$holarr[0][$i]; echo getdstrdf($holarr[0][$i]); } $lrw++; ?></td>
    <?php } for($i=1;$i<$htrw;$i++){ for($i1=4;$i1<8;$i1++){ ?>
        <td><?php if(isset($holarr[$i][$i1])){ $lrow[$lrw]=(double)$lrow[$lrw]+(double)$holarr[$i][$i1]; echo getdstrdf($holarr[$i][$i1]); } $lrw++; ?></td>
    <?php }} ?>
    </tr>
<?php return $lrow; } ?>
    <tr class="text-center text-nowrap bg-primary text-white">
        <th style="padding-top:2px;padding-bottom:2px;" scope="col">SIBS-<?php echo $sibsid; ?></th>
        <td style="padding-top:2px;padding-bottom:2px;"><?php echo $fullname; ?></td>
        <td></td>
        <td></td>
        <td></td>
        <td><input type="hidden" id="dtrhol" value="<?php echo $htrw; ?>"></td>

        <td style="padding-top:2px;padding-bottom:2px;"><?php echo $lastrow[0]; ?>
        <input type="hidden" id="dtrnof" value="<?php echo $lastrow[0]; ?>"></td>
        <td style="padding-top:2px;padding-bottom:2px;"><?php echo $lastrow[1]; ?>
        <input type="hidden" id="dtrltut" value="<?php echo $lastrow[1]; ?>"></td>
        <td style="padding-top:2px;padding-bottom:2px;"><?php echo $lastrow[2]; ?>
        <input type="hidden" id="dtrabcs" value="<?php echo $lastrow[2]; ?>"></td>

        <td style="padding-top:2px;padding-bottom:2px;"><?php echo getdstrdf($lastrow[3]); ?>
        <input type="hidden" id="dtrregot" value="<?php echo getdstrdf($lastrow[3]); ?>"></th><!-- Reg|OT --></td>
        <td style="padding-top:2px;padding-bottom:2px;"><?php echo getdstrdf($lastrow[4]); ?>
        <input type="hidden" id="dtrrdreg" value="<?php echo getdstrdf($lastrow[4]); ?>"><!-- RD|Reg --></td>
        <td style="padding-top:2px;padding-bottom:2px;"><?php echo getdstrdf($lastrow[5]); ?>
        <input type="hidden" id="dtrrdot" value="<?php echo getdstrdf($lastrow[5]); ?>"><!-- RD|OT --></td>
    <?php $lrc=6; for($i=1;$i<$htrw;$i++){?>
        <td style="padding-top:2px;padding-bottom:2px;"><?php echo getdstrdf($lastrow[$lrc]); ?>
        <input type="hidden" id="dtrholreg_<?php echo $i; ?>" value="<?php echo getdstrdf($lastrow[$lrc]); $lrc++; ?>"><!--|Reg--></td>
        <td style="padding-top:2px;padding-bottom:2px;"><?php echo getdstrdf($lastrow[$lrc]); ?>
        <input type="hidden" id="dtrholot_<?php echo $i; ?>" value="<?php echo getdstrdf($lastrow[$lrc]); $lrc++; ?>"><!--|OT--></td>
        <td style="padding-top:2px;padding-bottom:2px;"><?php echo getdstrdf($lastrow[$lrc]); ?>
        <input type="hidden" id="dtrholrdreg_<?php echo $i; ?>" value="<?php echo getdstrdf($lastrow[$lrc]); $lrc++; ?>"><!--|RD|Reg--></td>
        <td style="padding-top:2px;padding-bottom:2px;"><?php echo getdstrdf($lastrow[$lrc]); ?>
        <input type="hidden" id="dtrholrdot_<?php echo $i; ?>" value="<?php echo getdstrdf($lastrow[$lrc]); $lrc++; ?>"><!--|RD|OT--></td>
    <?php } ?>
        <td style="padding-top:2px;padding-bottom:2px;"><?php echo getdstrdf($lastrow[$lrc]); ?>
        <input type="hidden" id="dtrndreg" value="<?php echo getdstrdf($lastrow[$lrc]); $lrc++; ?>"><!--ND|Reg--></td>
        <td style="padding-top:2px;padding-bottom:2px;"><?php echo getdstrdf($lastrow[$lrc]); ?>
        <input type="hidden" id="dtrndregot" value="<?php echo getdstrdf($lastrow[$lrc]); $lrc++; ?>"><!--ND|Reg|OT--></td>
        <td style="padding-top:2px;padding-bottom:2px;"><?php echo getdstrdf($lastrow[$lrc]); ?>
        <input type="hidden" id="dtrndrdreg" value="<?php echo getdstrdf($lastrow[$lrc]); $lrc++; ?>"><!--ND|RD|Reg--></td>
        <td style="padding-top:2px;padding-bottom:2px;"><?php echo getdstrdf($lastrow[$lrc]); ?>
        <input type="hidden" id="dtrndrdot" value="<?php echo getdstrdf($lastrow[$lrc]); $lrc++; ?>"><!--ND|RD|OT--></td>
    <?php for($i=1;$i<$htrw;$i++){?>
        <td style="padding-top:2px;padding-bottom:2px;"><?php echo getdstrdf($lastrow[$lrc]); ?>
        <input type="hidden" id="dtrholnd_<?php echo $i; ?>" value="<?php echo getdstrdf($lastrow[$lrc]); $lrc++; ?>"><!--ND|--></td>
        <td style="padding-top:2px;padding-bottom:2px;"><?php echo getdstrdf($lastrow[$lrc]); ?>
        <input type="hidden" id="dtrholndot_<?php echo $i; ?>" value="<?php echo getdstrdf($lastrow[$lrc]); $lrc++; ?>"><!--ND||OT--></td>
        <td style="padding-top:2px;padding-bottom:2px;"><?php echo getdstrdf($lastrow[$lrc]); ?>
        <input type="hidden" id="dtrholndrd_<?php echo $i; ?>" value="<?php echo getdstrdf($lastrow[$lrc]); $lrc++; ?>"><!--ND||RD--></td>
        <td style="padding-top:2px;padding-bottom:2px;"><?php echo getdstrdf($lastrow[$lrc]); ?>
        <input type="hidden" id="dtrholndrdot_<?php echo $i; ?>" value="<?php echo getdstrdf($lastrow[$lrc]); $lrc++; ?>"><!--ND||RD|OT--></td>
    <?php } ?>
    </tr>
        	</tbody>
       </table>
    </div>