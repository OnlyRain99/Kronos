<?php
	include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

    $trackid = addslashes($_REQUEST['trackid']);
    $reason = addslashes($_REQUEST['reason']);
    $remarks = addslashes($_REQUEST['remarks']);
    $nameid0 = addslashes($_REQUEST['nameid0']);
    $nameid = addslashes($_REQUEST['nameid']);

$ifcor=$link->query("SELECT `gy_emp_code` From `gy_employee` Where `gy_emp_supervisor`='$user_id' AND `gy_emp_code`='$nameid' LIMIT 1");
$ifcornr = mysqli_num_rows($ifcor);
if($ifcornr == 0){
$ifcor=$link->query("SELECT `gy_emp_code` From `gy_employee` Where `gy_emp_supervisor`='$user_id' AND `gy_emp_code`='$nameid0' LIMIT 1");
if(mysqli_num_rows($ifcor) > 0){
    $ifcornr = getuserid($nameid0);
    $ifcor1=$link->query("SELECT `gy_emp_code` From `gy_employee` Where `gy_emp_supervisor`='$ifcornr' AND `gy_emp_code`='$nameid' LIMIT 1");
    $ifcornr = mysqli_num_rows($ifcor1);
}}

    if($ifcornr > 0 && $reason != "" && $remarks != ""){
        $ressql=$link->query("SELECT `gy_reason_name` From `gy_reason` Where `gy_reason_id`='$reason'");
        $rssql=$ressql->fetch_array();
        $reason = $rssql['gy_reason_name'];
        $ifcor=$link->query("SELECT `gy_tracker_id` From `gy_tracker` Where `gy_emp_code`='$nameid' AND `gy_tracker_id`='$trackid' AND `gy_tracker_request`='' LIMIT 1");
        if(mysqli_num_rows($ifcor) > 0){
        $updatedata=$link->query("UPDATE `gy_tracker` SET `gy_tracker_reason`='$reason',`gy_tracker_remarks`='$remarks',`gy_tracker_request`='reject',`gy_tracker_om`='$user_id' Where `gy_tracker_id`='$trackid'");
        }
    }
?>