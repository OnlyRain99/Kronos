<?php
include '../../../config/conn.php';
include '../../../config/function.php';
include '../session.php';

$datefrom = addslashes($_REQUEST['datefrom']);
$dateto = addslashes($_REQUEST['dateto']);
$nameid = addslashes($_REQUEST['nameid']);
$empscdid = 0;
$fullname="";

$ifcor=$link->query("SELECT `gy_emp_id`,`gy_emp_fullname` From `gy_employee` Where `gy_emp_code`='$nameid' LIMIT 1");
while ($ifrow=$ifcor->fetch_array()){
	$empscdid = $ifrow['gy_emp_id'];
	$fullname = $ifrow['gy_emp_fullname'];

$sqlseq = "";
if($datefrom == "" && $dateto != ""){ $sqlseq = "AND `gy_sched_day` ='".date('Y-m-d', strtotime($dateto))."'"; }
else if($datefrom != "" && $dateto == ""){ $sqlseq = "AND `gy_sched_day`='".date('Y-m-d', strtotime($datefrom))."'"; }
else if($datefrom != "" && $dateto != ""){ $sqlseq = "AND `gy_sched_day`>='".date('Y-m-d', strtotime($datefrom))."' AND `gy_sched_day`<='".date('Y-m-d', strtotime($dateto))."'"; }
else { $sqlseq = "AND `gy_sched_day`='".date("Y-m-d")."'"; }

$cntdate = date('Y-m-d', strtotime($datefrom));
$tmsht=$link->query("SELECT * From `gy_schedule` Where `gy_emp_id`=$empscdid ".$sqlseq." Order By `gy_sched_day` ASC");
while($tsrow=$tmsht->fetch_array()){
	while($cntdate<date("Y-m-d", strtotime($tsrow['gy_sched_day']))){
		tblcntt($fullname, $cntdate, "", "", "");
		$cntdate = date('Y-m-d', strtotime($cntdate.' +1 day'));
	}
	tblcntt($fullname, $cntdate, $tsrow['gy_sched_login'], $tsrow['gy_sched_logout'], $tsrow['gy_sched_mode']);
	$cntdate = date('Y-m-d', strtotime($cntdate.' +1 day'));
}
	while($cntdate<=date("Y-m-d", strtotime($dateto))){
		tblcntt($fullname, $cntdate, "", "", "");
		$cntdate = date('Y-m-d', strtotime($cntdate.' +1 day'));
	}
}

$link->close();

function tblcntt($name, $date, $scin, $scout, $mode){
	$scto="";
	$schin="";
	$schout="";
	if($mode=="0"){ $scto="Rest"; $schin="OFF"; $schout="OFF"; }
	else if($mode==1){ $scto="Work"; }
	else if($mode==2){ $scto="RDOT"; }
	if($scin!="" && $mode!="0"){ $schin=date("h:i a",strtotime(convert24to0($scin))); }
	if($scout!="" && $mode!="0"){ $schout=date("h:i a",strtotime(convert24to0($scout))); }
	$evnt = getdaystatus($date);
?>
<tr class="mybg <?php if($date==date("Y-m-d")){echo"table-secondary";}else if($date<date("Y-m-d")){echo"table-light";} if($mode=="0"){echo" text-primary";}else if($mode=="2"){echo" text-primary";}else if($mode==""){echo" text-danger";}else if($evnt!=""){echo" text-success";} ?>">
<td style="padding: 5px;" class="text-center text-nowrap"><?php echo $name; ?></td>
<td style="padding: 5px;" class="text-center text-nowrap"><?php echo date("F j, Y",strtotime($date)); ?></td>
<td style="padding: 5px;" class="text-center text-nowrap"><?php echo date("D",strtotime($date)); ?></td>
<td style="padding: 5px;" class="text-center text-nowrap"><?php echo $schin ?></td>
<td style="padding: 5px;" class="text-center text-nowrap"><?php echo $schout; ?></td>
<td style="padding: 5px;" class="text-center text-nowrap"><?php echo $scto; ?></td>
<td style="padding: 5px;" class="text-center text-nowrap"><?php echo $evnt; ?></td>
</tr>
<?php }
function getdaystatus($holdate){
    include '../../../config/conn.php';
    $holid = "";
    $hddate = date("Y-m-d",strtotime($holdate));
    $curyear = date("Y",strtotime($holdate));
    $curmonth = date("m",strtotime($holdate));
    $curday = date("d",strtotime($holdate));
    $dssql=$link->query("SELECT * FROM `gy_holiday_calendar` LEFT JOIN `gy_holiday_types` ON `gy_holiday_calendar`.`gy_hol_type_id`=`gy_holiday_types`.`gy_hol_type_id` WHERE (`gy_holiday_calendar`.`gy_a_year`=1 AND `gy_holiday_calendar`.`gy_hol_date`='$hddate')OR(`gy_holiday_calendar`.`gy_a_year`=0 AND Year(`gy_holiday_calendar`.`gy_hol_date`)<='$curyear' AND (Year(`gy_holiday_calendar`.`gy_hol_lastday`)='0000' OR (Year(`gy_holiday_calendar`.`gy_hol_lastday`)!='0000' AND Year(`gy_holiday_calendar`.`gy_hol_lastday`)>='$curyear' ) ) )AND(MONTH(`gy_holiday_calendar`.`gy_hol_date`)='$curmonth'AND DAY(`gy_holiday_calendar`.`gy_hol_date`)='$curday') LIMIT 1");
        while($dsrow=$dssql->fetch_assoc()){
            if($holid!=""){ $holid.="/"; }
            $holid.=strtoupper($dsrow['gy_hol_abbrv']);
			if($dsrow['gy_hol_loc']==0){ $holid.=" (Tagum Only)"; }
			else if($dsrow['gy_hol_loc']==1){ $holid.=" (Davao Only)"; }
        }
    $link->close();
    return $holid;
}
?>