<?php  
	include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];

    if ($redirect == "") {
    	header("location: 404");
    }else{
    	if (isset($_POST['reject_reason'])) {

    		$reject_reason=words("<small><i class='fa fa-times'></i> ".getuserfullname($user_id)."</small>: ".$_POST['reject_reason']);

    		$update_leave=$link->query("UPDATE `gy_leave` SET `gy_leave_approver`='$user_id',`gy_leave_date_approved`='$datenow',`gy_leave_status`='2',`gy_leave_remarks`=CONCAT(`gy_leave_remarks`,'$reject_reason') Where `gy_leave_id`='$redirect'");
	    	if ($update_leave) {
	            header("location: leave_request?note=reject");
	        }else{
	            header("location: leave_request?note=error");
	        }
    	}
    }
?>