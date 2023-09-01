<?php  
	include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];
    
    //delete data
    $deletedata=$link->query("DELETE FROM `gy_schedule_escalate` Where `gy_sched_esc_id`='$redirect'");

    if ($deletedata) {
    	header("location: escalate_sched?note=delete");
    }else{
    	header("location: escalate_sched?note=error");
    }
?>