<?php
if(isset($_POST["timesheetsbmt"])){
include '../../../config/conn.php';
include '../../../config/function.php';
include 'session.php';

function getmode($empcode, $date, $mod){
    include '../../../config/conn.php';
	$empsch=$link->query("SELECT `gy_sched_mode` FROM `gy_schedule` WHERE `gy_sched_day`='".$date."' AND `gy_sched_mode`=0 AND `gy_emp_id`='".getempid($empcode)."' LIMIT 1");
    if(mysqli_num_rows($empsch) > 0){ $mod = "OFF"; }
    return $mod;
}

function matchsched($dbemp, $dblogin){
	include '../../../config/conn.php';
	$today = date("Y-m-d", strtotime($dblogin));
	$yesterday = date("Y-m-d", strtotime($dblogin.' -1 day'));
	$tomorrow = date("Y-m-d", strtotime($dblogin.' +1 day'));
	$theemp = getempid($dbemp);
	$thisdate = $today;
	$empsch=$link->query("SELECT `gy_sched_day`, `gy_sched_login`, `gy_sched_logout` FROM `gy_schedule` WHERE `gy_sched_day`>='".$yesterday."' AND `gy_sched_day`<='".$tomorrow."' AND `gy_emp_id`='".$theemp."' ORDER BY `gy_sched_day` ASC");
	if(mysqli_num_rows($empsch) > 0){
		while ($scrow=$empsch->fetch_array()) {
			if(date("H:i:s", strtotime($scrow['gy_sched_login'])) > date("H:i:s", strtotime($scrow['gy_sched_logout']))){
				$schedlout = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime($scrow['gy_sched_logout'])).' +1 day');
			}else{
				$schedlout = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime($scrow['gy_sched_logout'])));
			}
			if(strtotime($dblogin) < $schedlout){ $thisdate = $scrow['gy_sched_day']; break; }
		}
	}
	return $thisdate;
}

function filterData(&$str){ 
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
}

function tblcntt($initcnt, $fullname, $department, $date, $login, $logout, $wh){
if($login != "OFF" && $login != "No Record"){
	$login = date("g:i A", strtotime($login));
	$logout = date("g:i A", strtotime($logout));
}
$lineData = array($initcnt, date('F', strtotime($date)), " ".date("F d, Y", strtotime($date)), $fullname, $department, $login, $logout , $wh);
array_walk($lineData, 'filterData');
return $lineData;
}

$tmsheetid = addslashes($_POST['tmsheetid']);
$datefrom = addslashes($_POST['datefrom']);
$dateto = addslashes($_POST['dateto']);
$sqlseq = "";

$tmsacnt = $link->query("SELECT * FROM `gy_accounts` where `gy_acc_id`='$tmsheetid' LIMIT 1");
while ($acntrow=$tmsacnt->fetch_array()) {
	$tmsheetid = $acntrow['gy_acc_name'];
}

$fileName = "Timesheet_".$tmsheetid."_".$datefrom."-".$dateto."_".date('Ymdhis').".xls"; 
$fields = array('#', 'Month', 'Date', 'Name', 'Account', 'login', 'Logout', 'Hours Worked'); 
$excelData = implode("\t", array_values($fields)) . "\n";

if($datefrom == "" && $dateto != ""){ $sqlseq = "AND `gy_tracker_login` >='".date('Y-m-d', strtotime($dateto))."' AND `gy_tracker_login` <= '".date('Y-m-d', strtotime($dateto.' +1 day'))."'"; }
else if($datefrom != "" && $dateto == ""){ $sqlseq = "AND `gy_tracker_login` >='".date('Y-m-d', strtotime($datefrom))."' AND `gy_tracker_login` <= '".date('Y-m-d', strtotime($datefrom.' +1 day'))."'"; }
else if($datefrom != "" && $dateto != ""){ $sqlseq = "AND `gy_tracker_login` >='".date('Y-m-d', strtotime($datefrom))."' AND `gy_tracker_login` <= '".date('Y-m-d', strtotime($dateto.' +1 day'))."'"; }
else { $sqlseq = "AND `gy_tracker_login` >='".date("Y-m-d")."'"; }

$fullname = "";
$cntdate = date('Y-m-d', strtotime($datefrom));
$tmsht=$link->query("SELECT `gy_emp_fullname`, `gy_tracker_login`, `gy_tracker_logout`, `gy_tracker_wh`, `gy_emp_account`, `gy_emp_code` From `gy_tracker` Where `gy_emp_account`='$tmsheetid' AND `gy_tracker_logout`!='0000-00-00 00:00:00' ".$sqlseq." Order By `gy_emp_code`, `gy_tracker_login` ASC");
if($tmsht->num_rows > 0){
$initcnt = 0;
$tmpcode = "";
$i=0;
while ($tsrow=$tmsht->fetch_array()) {
$i++;
$truedate = matchsched($tsrow['gy_emp_code'], $tsrow['gy_tracker_login']);
	//For the prev User
    if($tmpcode != $tsrow['gy_emp_code'] && $tmpcode != ""){
	while($cntdate<=date("Y-m-d", strtotime($dateto)) ){
		$thismode = getmode($tmpcode, $cntdate, "No Record");
		$lineData = tblcntt($initcnt, $fullname, $tsrow['gy_emp_account'], $cntdate, $thismode, $thismode, 0);
		$excelData .= implode("\t", array_values($lineData)) . "\n";
		$cntdate = date('Y-m-d', strtotime($cntdate.' +1 day'));
	}
	$cntdate = date('Y-m-d', strtotime($datefrom));
	}else if($tmpcode == $tsrow['gy_emp_code'] && $cntdate > date("Y-m-d", strtotime($truedate))){
		$cntdate = date("Y-m-d", strtotime($truedate));
	}
	
if($tmpcode != $tsrow['gy_emp_code']){
	$tmpcode = $tsrow['gy_emp_code'];
	$initcnt++;
}
	//For the First and Middle No record of the current
	$fullname = get_emp_name($tsrow['gy_emp_code']);
	while($cntdate!=date("Y-m-d", strtotime($truedate))){
		$thismode = getmode($tmpcode, $cntdate, "No Record");
		$lineData = tblcntt($initcnt, $fullname, $tsrow['gy_emp_account'], $cntdate, $thismode, $thismode, 0);
		$excelData .= implode("\t", array_values($lineData)) . "\n";
		$cntdate = date('Y-m-d', strtotime($cntdate.' +1 day')); }

		$thismode = getmode($tsrow['gy_emp_code'], date("Y-m-d", strtotime($truedate)), "ON");
		if($thismode == "ON"){
			$lineData = tblcntt($initcnt, $fullname, $tsrow['gy_emp_account'], $truedate, $tsrow['gy_tracker_login'], $tsrow['gy_tracker_logout'], $tsrow['gy_tracker_wh']);
		}else{
			$lineData = tblcntt($initcnt, $fullname, $tsrow['gy_emp_account'], $truedate, "OFF", "OFF", 0);
		}
		$excelData .= implode("\t", array_values($lineData)) . "\n";

$cntdate = date('Y-m-d', strtotime($cntdate.' +1 day'));
	//For Last No record of the current
	if(mysqli_num_rows($tmsht)<=$i){
		while($cntdate<=date("Y-m-d", strtotime($dateto))){
			$thismode = getmode($tmpcode, $cntdate, "No Record");
			$lineData = tblcntt($initcnt, $fullname, $tsrow['gy_emp_account'], $cntdate, $thismode, $thismode, 0);
			$excelData .= implode("\t", array_values($lineData)) . "\n";
			$cntdate = date('Y-m-d', strtotime($cntdate.' +1 day'));
		}
	}
}
}else{ $excelData .= 'No records found...'. "\n"; }

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$fileName\"");

echo $excelData;
exit;
}
?>