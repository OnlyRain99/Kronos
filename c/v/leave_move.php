<?php  
	include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];

    if ($redirect == "") {
    	header("location: 404");
    }else{

    	$leave_details=$link->query("SELECT `gy_user_id` From `gy_leave` Where `gy_leave_id`='$redirect'");
    	$leave=$leave_details->fetch_array();

	    if (isset($_POST['trans_date_from'])) {
	    	$trans_from = words($_POST['trans_date_from']);
	    	$trans_to = words($_POST['trans_date_to']);
	    	$trans_reason = words("<small><i class='fa fa-edit'></i> ".getuserfullname($user_id)."</small>: ".$_POST['trans_reason']);
	    	$leave_days = get_no_of_days($_POST['trans_date_from'], $_POST['trans_date_to']);
	    	$leave_person = get_emp_code($leave['gy_user_id']);
	        $leave_credits = get_leave_credits($leave_person);
        	$leave_user_type = get_user_type_num($leave['gy_user_id']);

        	if ($leave_user_type == 1) {

        		if (check_leave_availability($trans_from, $trans_to, $myaccount) == "not_allowed") {
		            header("location: leave_request?note=not_allowed");
		        }else{
		        	//check leave credits and update leave table

		            if ($leave_credits >= $leave_days) {
		                $leave_paid = $leave_days;
		                $credit_update = "`gy_emp_leave_credits`=`gy_emp_leave_credits` - '$leave_days'";
		            }else{
		                $leave_paid = $leave_credits;
		                $credit_update = "`gy_emp_leave_credits`='0'";
		            } 

			    	$update_leave=$link->query("UPDATE `gy_leave` SET `gy_leave_date_from`='$trans_from',`gy_leave_date_to`='$trans_to',`gy_leave_approver`='$user_id',`gy_leave_date_approved`='$datenow',`gy_leave_status`='1',`gy_leave_remarks`=CONCAT(`gy_leave_remarks`,'$trans_reason') Where `gy_leave_id`='$redirect'");

			    	if ($update_leave) {
		                // if gy_emp_leave_credits 0 -> stay 0

			    		$update_credits=$link->query("UPDATE `gy_employee` SET ".$credit_update." Where `gy_emp_code`='$leave_person'");

				    	if ($update_credits) {
				    		//get date intervals
					    	$period = new DatePeriod(
					    	         new DateTime($trans_from),
					    	         new DateInterval('P1D'),
					    	         new DateTime(date("Y-m-d", strtotime($trans_to . "+1 day")))
					    	    );

					    	foreach ($period as $dates) {

					    	    $leave_date = $dates->format('Y-m-d');
					    	    //update available slots
					    		$update_available=$link->query("UPDATE `gy_leave_available` SET `gy_leave_avail_approved`=`gy_leave_avail_approved` + 1 Where `gy_leave_avail_date`='$leave_date' AND `gy_acc_id`='$myaccount'");
					    	}

			                if ($update_available) {
			                    header("location: leave_request?note=move");
			                }else{
			                    header("location: leave_request?note=error");
			                }
				    	}else{
				    		header("location: leave_request?note=error");
				    	}
			    	}else{
			    		header("location: leave_request?note=error");
			    	}
		        }
        	}else{
        		//level 2 and up here
	        	//check and update leave credits

	            if ($leave_credits >= $leave_days) {
	                $leave_paid = $leave_days;
	                $credit_update = "`gy_emp_leave_credits`=`gy_emp_leave_credits` - '$leave_days'";
	            }else{
	                $leave_paid = $leave_credits;
	                $credit_update = "`gy_emp_leave_credits`='0'";
	            } 

		    	$update_leave=$link->query("UPDATE `gy_leave` SET `gy_leave_date_from`='$trans_from',`gy_leave_date_to`='$trans_to',`gy_leave_approver`='$user_id',`gy_leave_date_approved`='$datenow',`gy_leave_status`='1',`gy_leave_remarks`=CONCAT(`gy_leave_remarks`,'$trans_reason') Where `gy_leave_id`='$redirect'");

		    	if ($update_leave) {
	                // if gy_emp_leave_credits 0 -> stay 0

		    		$update_credits=$link->query("UPDATE `gy_employee` SET ".$credit_update." Where `gy_emp_code`='$leave_person'");

			    	if ($update_credits) {
		                header("location: leave_request?note=move");
			    	}else{
			    		header("location: leave_request?note=error");
			    	}
		    	}else{
		    		header("location: leave_request?note=error");
		    	}
        	}
	    }
    }

    
?>