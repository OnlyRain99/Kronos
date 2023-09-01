<?php  
	include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];
    $info=$link->query("SELECT `gy_emp_fullname`,`gy_escalate`.`gy_tracker_id` From `gy_escalate` LEFT JOIN `gy_tracker` On `gy_escalate`.`gy_tracker_id`=`gy_tracker`.`gy_tracker_id` Where `gy_esc_id`='$redirect'");
    $inforow=$info->fetch_array();
    $tracker=words($inforow['gy_tracker_id']);
    
    //delete data
    $deletedata=$link->query("DELETE FROM `gy_escalate` Where `gy_esc_id`='$redirect'");

    if ($deletedata) {
        $updatedata=$link->query("UPDATE `gy_tracker` SET `gy_tracker_request`='' Where `gy_tracker_id`='$tracker'");

        $notetext = "Escalation request for ".$inforow['gy_emp_fullname']." has been removed";
        $notetype = "insert";
        $noteucode = $user_code;
        $noteuser = $user_info;
        my_notify($notetext, $notetype, $noteucode, $noteuser);

    	header("location: esc_summary?note=delete");
    }else{
    	header("location: esc_summary?note=error");
    }
?>