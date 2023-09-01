<?php
include '../../../config/conn.php';
include '../../../config/function.php';

function getmode($empcode, $date, $mod){
    include '../../../config/conn.php';
	$empsch=$link->query("SELECT `gy_sched_mode` FROM `gy_schedule` WHERE `gy_sched_day`='".$date."' AND `gy_sched_mode`!=1 AND `gy_emp_id`='".getempid($empcode)."' LIMIT 1");
    if(mysqli_num_rows($empsch) > 0){
		while ($modrow=$empsch->fetch_array()){ $mod = $modrow['gy_sched_mode']; }
    }
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

$tmsheetid = addslashes($_REQUEST['tmsheetid']);
$datefrom = addslashes($_REQUEST['datefrom']);
$dateto = addslashes($_REQUEST['dateto']);
$sqlseq = "";
if($datefrom == "" && $dateto != ""){ $sqlseq = "AND `gy_tracker_login` >='".date('Y-m-d', strtotime($dateto))."' AND `gy_tracker_login` <= '".date('Y-m-d', strtotime($dateto.' +1 day'))."'"; }
else if($datefrom != "" && $dateto == ""){ $sqlseq = "AND `gy_tracker_login` >='".date('Y-m-d', strtotime($datefrom))."' AND `gy_tracker_login` <= '".date('Y-m-d', strtotime($datefrom.' +1 day'))."'"; }
else if($datefrom != "" && $dateto != ""){ $sqlseq = "AND `gy_tracker_login` >='".date('Y-m-d', strtotime($datefrom))."' AND `gy_tracker_login` <= '".date('Y-m-d', strtotime($dateto.' +1 day'))."'"; }
else { $sqlseq = "AND `gy_tracker_login` >='".date("Y-m-d")."'"; }
$i = 0;
$fullname = "";
$empcode = "";
$cntdate = date('Y-m-d', strtotime($datefrom));
$tmsht=$link->query("SELECT `gy_emp_code`, `gy_emp_fullname`, `gy_tracker_login`, `gy_tracker_logout`, `gy_tracker_wh` From `gy_tracker` Where `gy_emp_account`='$tmsheetid' AND `gy_tracker_logout`!='0000-00-00 00:00:00' ".$sqlseq." Order By `gy_emp_code`, `gy_tracker_login` ASC");
	while ($tsrow=$tmsht->fetch_array()) {
		$i++;
		$truedate = matchsched($tsrow['gy_emp_code'], $tsrow['gy_tracker_login']);

		if($empcode != $tsrow['gy_emp_code'] && $empcode != ""){
			while($cntdate<=date("Y-m-d", strtotime($dateto)) ){
				$thismode = getmode($empcode, $cntdate, 100);
				tblcntt($thismode, date("F d, Y", strtotime($cntdate)), $fullname, "No Record", "No Record", 0);
				$cntdate = date('Y-m-d', strtotime($cntdate.' +1 day'));
			}
			$cntdate = date('Y-m-d', strtotime($datefrom));
		}else if($empcode == $tsrow['gy_emp_code'] && $cntdate > date("Y-m-d", strtotime($truedate))){
			$cntdate = date("Y-m-d", strtotime($truedate));
		}
		
        $fullname = get_emp_name($tsrow['gy_emp_code']);
		$empcode = $tsrow['gy_emp_code'];
		while($cntdate!=date("Y-m-d", strtotime($truedate))){
				$thismode = getmode($empcode, $cntdate, 100);
			tblcntt($thismode, date("F d, Y", strtotime($cntdate)), $fullname, "No Record", "No Record", 0);
			$cntdate = date('Y-m-d', strtotime($cntdate.' +1 day')); }

			$thismode = getmode($empcode, $cntdate, 1);
			tblcntt($thismode, date("F d, Y", strtotime($truedate)), $fullname, date("g:i A", strtotime($tsrow['gy_tracker_login'])), date("g:i A", strtotime($tsrow['gy_tracker_logout'])), $tsrow['gy_tracker_wh']);

	$cntdate = date('Y-m-d', strtotime($cntdate.' +1 day'));
	 if(mysqli_num_rows($tmsht)==$i){
		 while($cntdate<=date("Y-m-d", strtotime($dateto))){
		 	$thismode = getmode($empcode, $cntdate, 100);
			tblcntt($thismode, date("F d, Y", strtotime($cntdate)), $fullname, "No Record", "No Record", 0);
		 $cntdate = date('Y-m-d', strtotime($cntdate.' +1 day'));
		 }
	 }
	}
	 ?>

<?php function tblcntt($mode, $date, $name, $login, $logout, $wh){ ?>
<tr>
<td class="text-center"><?php echo $date; ?></td>
<td class="text-center"><?php echo $name; ?></td>
<td class="text-center <?php if($mode==0 || $mode==2){echo 'bg-warning';}else if($mode==100){echo 'bg-danger';} ?>">
	<?php if($mode==0){echo "OFF";}else{echo $login;} ?>
</td>
<td class="text-center <?php if($mode==0 || $mode==2){echo 'bg-warning';}else if($mode==100){echo 'bg-danger';} ?>">
	<?php if($mode==0){echo "OFF";}else{echo $logout;} ?>
</td>
<td class="text-center"><?php if($mode==0){echo "0";}else{echo $wh;} ?></td>
</tr>
<?php } ?>