<?php
include '../../../../config/conn.php';
include '../../../../config/function.php';
include '../session.php';

$tsdate = addslashes($_REQUEST['tsdate']);
$tscode = addslashes($_REQUEST['tscode']);

$empsql=$link->query("SELECT `gy_emp_code` FROM `gy_employee` WHERE `gy_emp_code`='$tscode' AND `gy_emp_supervisor`='$user_id' LIMIT 1");
while ($emprow=$empsql->fetch_array()) {
	$tscode = $emprow['gy_emp_code'];
	$tssql=$link->query("SELECT `temp_sup_id` FROM `gy_temp_sup` WHERE `temp_sup_code`='$tscode' AND `temp_sup_date`='$tsdate' AND `temp_sup_by`='$user_id'");
	if($tssql->num_rows<=0){
		$tsnsrt = $link->query("INSERT INTO `gy_temp_sup`(`temp_sup_code`,`temp_sup_date`,`temp_sup_by`) Values('$tscode','$tsdate','$user_id')");
		if($tsnsrt){ echo "tsnew"; }else{ echo "error";}
	}else{ echo "conflicted"; }
}

$link->close();
?>