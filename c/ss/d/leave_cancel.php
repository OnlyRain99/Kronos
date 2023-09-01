<?php  
	include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];

    if ($redirect == "") {
    	header("location: 404");
    }else{
    	//get leave details
    	$details=$link->query("SELECT `gy_user_id`,`gy_leave_paid`,`gy_leave_status`,`gy_leave_date_from`,`gy_leave_date_to` From `gy_leave` Where `gy_leave_id`='$redirect'");
    	$details_count=$details->num_rows;
    	$leave=$details->fetch_array();

    	$leave_paid = words($leave['gy_leave_paid']);
    	$leave_person = get_emp_code($leave['gy_user_id']);
    	$leave_status = $leave['gy_leave_status'];

    	if ($details_count > 0) {
    		//change status to 0
	    	$update_status=$link->query("UPDATE `gy_leave` SET `gy_leave_status`='3' Where `gy_leave_id`='$redirect'");

	    	if ($update_status) {

	    		if ($leave_status == 0) {
	    			header("location: leave?note=cancel");
	    		}else if ($leave_status == 1) {
	    			//update credits by gy_leave_paid quantity
			    	$update_credits=$link->query("UPDATE `gy_employee` SET `gy_emp_leave_credits`=`gy_emp_leave_credits` + '$leave_paid' Where `gy_emp_code`='$leave_person'");

			    	if ($update_credits) {
				    	header("location: leave?note=cancel");
			    	}else{
			    		header("location: leave?cd=error");
			    	}
	    		}else{
	    			header("location: leave?cd=invalid");
	    		}
	    	}else{
	    		header("location: leave?cd=error");
	    	}
	    	
    	}else{
    		header("location: 404");
    	}
    }
?>