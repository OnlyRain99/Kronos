<?php
include '../../../config/conn.php';
include '../../../config/function.php';
include '../session.php';

function changetozero($num){
	if($num==""){ $num=0; }
	return $num;
}

function expldchngtozero($num){
	$num = explode(",", $num);
	for($i=0;$i<count($num);$i++){
		$num[$i]=changetozero($num[$i]);
	}
	return $num;
}

$sibsid = addslashes($_REQUEST['sibsid']);
$year = addslashes($_REQUEST['year']);
$month = addslashes($_REQUEST['month']);
$cutoff = addslashes($_REQUEST['cutoff']);
$dmrate = addslashes($_REQUEST['dmrate']);
$cmputetyp = addslashes($_REQUEST['cmputetyp']);

$dtrnof = addslashes($_REQUEST['dtrnof']);
$dtrltut = addslashes($_REQUEST['dtrltut']);
$dtrabcs = addslashes($_REQUEST['dtrabcs']);

$dtrregot = changetozero(addslashes($_REQUEST['dtrregot']));
$dtrrdreg = changetozero(addslashes($_REQUEST['dtrrdreg']));
$dtrrdot = changetozero(addslashes($_REQUEST['dtrrdot']));

$dtrholreg = expldchngtozero(addslashes($_REQUEST['dtrholreg']));
$dtrholot = expldchngtozero(addslashes($_REQUEST['dtrholot']));
$dtrholrdreg = expldchngtozero(addslashes($_REQUEST['dtrholrdreg']));
$dtrholrdot = expldchngtozero(addslashes($_REQUEST['dtrholrdot']));

$dtrndreg = changetozero(addslashes($_REQUEST['dtrndreg']));
$dtrndregot = changetozero(addslashes($_REQUEST['dtrndregot']));
$dtrndrdreg = changetozero(addslashes($_REQUEST['dtrndrdreg']));
$dtrndrdot = changetozero(addslashes($_REQUEST['dtrndrdot']));

$dtrholnd = expldchngtozero(addslashes($_REQUEST['dtrholnd']));
$dtrholndot = expldchngtozero(addslashes($_REQUEST['dtrholndot']));
$dtrholndrd = expldchngtozero(addslashes($_REQUEST['dtrholndrd']));
$dtrholndrdot = expldchngtozero(addslashes($_REQUEST['dtrholndrdot']));

$pblshdtr=$link->query("INSERT INTO `dtr_publish`(`dtr_year`,`dtr_month`,`dtr_cutoff`,`gy_emp_code`,`dtr_noofhours`,`dtr_lateut`,`dtr_absences`,`dtr_regot`,`dtr_rdreg`,`dtr_rdot`,`dtr_shreg`,`dtr_shot`,`dtr_shrdreg`,`dtr_shrdot`,`dtr_lhreg`,`dtr_lhot`,`dtr_lhrdreg`,`dtr_lhrdot`,`dtr_ndreg`,`dtr_ndregot`,`dtr_ndrdreg`,`dtr_ndrdot`,`dtr_ndsh`,`dtr_ndshot`,`dtr_ndshrd`,`dtr_ndshrdot`,`dtr_ndlh`,`dtr_ndlhot`,`dtr_ndlhrd`,`dtr_ndlhrdot`,`dtr_publisher`,`dtr_mdrate`,`dtr_cmpute`)VALUES($year,$month,$cutoff,'$sibsid',$dtrnof,$dtrltut,$dtrabcs,$dtrregot,$dtrrdreg,$dtrrdot,$dtrholreg[0],$dtrholot[0],$dtrholrdreg[0],$dtrholrdot[0],$dtrholreg[1],$dtrholot[1],$dtrholrdreg[1],$dtrholrdot[1],$dtrndreg,$dtrndregot,$dtrndrdreg,$dtrndrdot,$dtrholnd[0],$dtrholndot[0],$dtrholndrd[0],$dtrholndrdot[0],$dtrholnd[1],$dtrholndot[1],$dtrholndrd[1],$dtrholndrdot[1],$user_id,$dmrate,$cmputetyp)");
if($pblshdtr){echo"success";}else{echo"error";}
$link->close(); ?>