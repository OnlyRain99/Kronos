<?php
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';
    if($myaccount == 22 || $user_dept == 9){
	include '../../config/connnk.php';

    $empcode = @$_GET['empcode'];
    $fdate = @$_GET['fdate'];
    $tdate = @$_GET['tdate'];
    $sqlwhere = "";

if($empcode!="" && $fdate != "" && $tdate != "" && strtotime($tdate)>=strtotime($fdate)){
    //array master list users
    $i = 0; $vxlmarr = array();
    $vxlmlist=$dbticket->query("SELECT `mr_emp_code` From `vidaxl_masterlist`");
        while($vxlmrow=$vxlmlist->fetch_array()){
            $vxlmarr[$i] = $vxlmrow['mr_emp_code'];
            $i++;
        }

    //array tickets
    if($empcode=="all"){ $sqlwhere=" AND `emp_code` IN ('".implode("','",$vxlmarr)."')"; }
    else if($empcode!=""){ $sqlwhere=" AND `emp_code`='".$empcode."'"; }
    $i = 0; $ticketarr = array(array());
    $tktlist=$dbticket->query("SELECT `emp_code`,`channel`,`ticket_date` From `ticket` Where `ticket_date`>='".date("Y-m-d H:i:s",strtotime($fdate." 00:00:00"))."' AND `ticket_date`<='".date("Y-m-d H:i:s",strtotime($tdate." 24:00:00"))."' ".$sqlwhere);
    if(mysqli_num_rows($tktlist) > 0){
        while($tktrow=$tktlist->fetch_array()){
            $ticketarr[$i][0] = $tktrow['emp_code'];
            $ticketarr[$i][1] = $tktrow['channel'];
            $ticketarr[$i][2] = $tktrow['ticket_date'];
            $i++;
        }
    }

 $sqlwhere = "";
 $dbticket->close();

function getmode($empcode, $date, $mod, $in, $out){
    include '../../config/conn.php';
    $loginout=array($in, $out);
    $empsch=$link->query("SELECT `gy_sched_mode` FROM `gy_schedule` WHERE `gy_sched_day`='".$date."' AND `gy_sched_mode`!=1 AND `gy_emp_id`='".getempid($empcode)."' LIMIT 1");
    if(mysqli_num_rows($empsch) > 0){
        while ($modrow=$empsch->fetch_array()){ $mod = $modrow['gy_sched_mode']; }
    }
    if($mod==0){
        if($in=="No Log"){ $loginout[0]="OFF"; }
        if($out=="No Log"){ $loginout[1]="OFF"; }
    }
    return $loginout;
}

function matchsched($dbemp, $dblogin, $dbbreo, $dbbrei, $dblogo){
    include '../../config/conn.php';
    if($dblogo != "0000-00-00 00:00:00"){ $endday = $dblogo; }
    else if($dbbrei != "0000-00-00 00:00:00"){ $endday = $dbbrei; }
    else if($dbbreo != "0000-00-00 00:00:00"){ $endday = $dbbreo; }
    else { $endday = $dblogin; }
    $today = date("Y-m-d", strtotime($dblogin));
    $yesterday = date("Y-m-d", strtotime($dblogin.' -1 day'));
    $tomorrow = date("Y-m-d", strtotime($dblogin.' +1 day'));
    $theemp = getempid($dbemp);
    $arrsched = array($today,0);
    $empsch=$link->query("SELECT `gy_sched_mode`,`gy_sched_day`, `gy_sched_login`, `gy_sched_logout` FROM `gy_schedule` WHERE `gy_sched_day`>='".$yesterday."' AND `gy_sched_day`<='".$tomorrow."' AND `gy_emp_id`='".$theemp."' AND `gy_sched_mode`!=0 ORDER BY `gy_sched_day` ASC");
    if(mysqli_num_rows($empsch) > 0){
        while ($scrow=$empsch->fetch_array()) {
            if(date("H:i:s", strtotime(convert24to0($scrow['gy_sched_login']))) > date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout'])))) {
                $schedlout = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout']))).' +1 day');
            }else{
                $schedlout = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout']))));
            }
            $schedin = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_login']))));
            if(strtotime($dblogin) < $schedlout && strtotime($endday) >= $schedin){ $arrsched[0] = $scrow['gy_sched_day'];
            $arrsched[1] = getwh(date("Y-m-d", strtotime($arrsched[0]))." ".convert24to0($scrow['gy_sched_login']), date("Y-m-d H:i:s", $schedlout));
            $arrsched[2] = $scrow['gy_sched_mode'];
            break; }
        }
    }
    return $arrsched;
}

function channelcount($lin, $lout, $ticketarr, $empcode){
    $channel = array(0,0,0);
    if($lin != "0000-00-00 00:00:00" && $lin != ""){
        for($i=0;$i<count($ticketarr);$i++){
            if($lout != "0000-00-00 00:00:00" && $lout != ""){
                if(strtotime($ticketarr[$i][2])>=strtotime($lin) && strtotime($ticketarr[$i][2])<=strtotime($lout) && $empcode==$ticketarr[$i][0]){
                    if($ticketarr[$i][1]=="Live Chat"){ $channel[0]++; }
                    else if($ticketarr[$i][1]=="Email"){ $channel[1]++; }
                    else if($ticketarr[$i][1]=="Phone"){ $channel[2]++; }
                }
            }else{
                if(strtotime($ticketarr[$i][2])>=strtotime($lin) && $empcode==$ticketarr[$i][0]){
                    if($ticketarr[$i][1]=="Live Chat"){ $channel[0]++; }
                    else if($ticketarr[$i][1]=="Email"){ $channel[1]++; }
                    else if($ticketarr[$i][1]=="Phone"){ $channel[2]++; }
                }
            }
        }
    }
    return $channel;
}

if($empcode=="all"){ $sqlwhere=" WHERE `gy_emp_code` IN ('".implode("','",$vxlmarr)."')"; }
else if($empcode!=""){ $sqlwhere=" WHERE `gy_emp_code`='".$empcode."'"; }

    //array employee list
    $i = 0; $gyemparr = array(array()); $gyempar = array();
    $vxlemp=$link->query("SELECT `gy_emp_code`,`gy_emp_email`,`gy_emp_fname`,`gy_emp_lname`,`gy_emp_mname`,`gy_emp_account` From `gy_employee` ".$sqlwhere." ORDER BY `gy_emp_fullname` ASC");
        while($vxlrow=$vxlemp->fetch_array()){
            $gyempar[$i] = $vxlrow['gy_emp_code'];
            $gyemparr[$i][0] = $vxlrow['gy_emp_email'];
            $gyemparr[$i][1] = $vxlrow['gy_emp_fname'];
            $gyemparr[$i][2] = $vxlrow['gy_emp_lname'];
            $gyemparr[$i][3] = $vxlrow['gy_emp_mname'];
            $gyemparr[$i][4] = $vxlrow['gy_emp_account'];
            $i++;
        }

$sqlseq = "";
if($fdate == "" && $tdate != ""){ $sqlseq = "AND `gy_tracker_date` >='".date('Y-m-d', strtotime($tdate.' -1 day'))."' AND `gy_tracker_date` <= '".date('Y-m-d', strtotime($tdate.' +1 day'))."'"; }
else if($fdate != "" && $tdate == ""){ $sqlseq = "AND `gy_tracker_date` >='".date('Y-m-d', strtotime($fdate.' -1 day'))."' AND `gy_tracker_date` <= '".date('Y-m-d', strtotime($fdate.' +1 day'))."'"; }
else if($fdate != "" && $tdate != ""){ $sqlseq = "AND `gy_tracker_date` >='".date('Y-m-d', strtotime($fdate.' -1 day'))."' AND `gy_tracker_date` <= '".date('Y-m-d', strtotime($tdate.' +1 day'))."'"; }
else { $sqlseq = "AND `gy_tracker_date` >='".date("Y-m-d",' -1 day')."' AND `gy_tracker_date` <='".date("Y-m-d",' +1 day')."'"; }

    //csv
    $filename = "Report_gen".date("ymdhis");
    header("Content-Type: text/csv; charset=utf-8");  
    header("Content-Disposition: attachment; filename=$filename.csv");
    $output = fopen("php://output", "w");  
    fputcsv($output, array('SiBS ID', 'Email', 'First Name', 'Last Name', 'Middle Name', 'Department', 'Date', 'Login', 'Logout', 'Chat', 'Email', 'Phone'));

    for($i1=0;$i1<count($gyempar);$i1++){
    $cntdate = date('Y-m-d', strtotime($fdate));
    $tmsht=$link->query("SELECT `gy_tracker_status`,`gy_tracker_date`,`gy_tracker_login`,`gy_tracker_logout`,`gy_tracker_breakout`,`gy_tracker_breakin` From `gy_tracker` Where `gy_emp_code`='".$gyempar[$i1]."' ".$sqlseq." Order By `gy_tracker_date` ASC");
        while($tsrow=$tmsht->fetch_array()){
        $truedate = array(0,0,);
        $truedate = matchsched($gyempar[$i1], $tsrow['gy_tracker_date'], $tsrow['gy_tracker_breakout'], $tsrow['gy_tracker_breakin'], $tsrow['gy_tracker_logout']);

        while($cntdate<date("Y-m-d", strtotime($truedate[0]))){
                $cnttruedate = matchsched($gyempar[$i1], $cntdate." 00:00:00", "0000-00-00 00:00:00", "0000-00-00 00:00:00", "0000-00-00 00:00:00");
                if($cnttruedate[0] == $truedate[0]){ $cnttruedate[1] = 0; }
                    $loginout = array("No Log","No Log");
                    $loginout = getmode($gyempar[$i1], $cntdate, 100, $loginout[0],$loginout[1]);
            fputcsv($output, array($gyempar[$i1], $gyemparr[$i1][0], $gyemparr[$i1][1], $gyemparr[$i1][2], $gyemparr[$i1][3], $gyemparr[$i1][4], date("m/d/Y", strtotime($cntdate)), $loginout[0], $loginout[1], 0, 0, 0));
           $cntdate = date('Y-m-d', strtotime($cntdate.' +1 day')); }
        if($cntdate>date("Y-m-d", strtotime($truedate[0]))){ $cntdate = date("Y-m-d", strtotime($truedate[0])); }
if(date("Y-m-d", strtotime($truedate[0]))>=date('Y-m-d', strtotime($fdate)) && date("Y-m-d", strtotime($truedate[0]))<=date('Y-m-d', strtotime($tdate))){
    $channelepc = array(0,0,0);
    $channelepc = channelcount($tsrow['gy_tracker_login'], $tsrow['gy_tracker_logout'], $ticketarr, $gyempar[$i1]);
    fputcsv($output, array($gyempar[$i1], $gyemparr[$i1][0], $gyemparr[$i1][1], $gyemparr[$i1][2], $gyemparr[$i1][3], $gyemparr[$i1][4], date("m/d/Y", strtotime($cntdate)), chktime($tsrow['gy_tracker_login']), chktime($tsrow['gy_tracker_logout']), $channelepc[0], $channelepc[1], $channelepc[2]));
}
    $cntdate = date('Y-m-d', strtotime($cntdate.' +1 day'));
        }
        while($cntdate<=date("Y-m-d", strtotime($tdate))){
            $loginout = array("No Log","No Log");
            $loginout = getmode($gyempar[$i1], $cntdate, 100, $loginout[0],$loginout[1]);
            fputcsv($output, array($gyempar[$i1], $gyemparr[$i1][0], $gyemparr[$i1][1], $gyemparr[$i1][2], $gyemparr[$i1][3], $gyemparr[$i1][4], date("m/d/Y", strtotime($cntdate)), $loginout[0], $loginout[1], 0, 0, 0));
            $cntdate = date('Y-m-d', strtotime($cntdate.' +1 day')); }
    }
fclose($output);
}

} $link->close(); ?>