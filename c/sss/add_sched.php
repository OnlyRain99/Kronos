<?php  
	include("../../config/conn.php");
	include("../../config/function.php");
	include("session.php");

	include 'check_schedule.php';

	if (isset($_POST['sibsid'])) {

		$sibsid = words($_POST['sibsid']);
		$datefrom = words($_POST['datefrom']);
		$dateto = words($_POST['dateto']);
		$status = words($_POST['status']);
		$login = words($_POST['login']);
		$breakout = words($_POST['breakout']);
		$breakin = words($_POST['breakin']);
		$logout = words($_POST['logout']);
		$reason = words($_POST['reason']);

		$checkempcode = checkempcode($sibsid);

		if ($checkempcode == "yes") {
			header("location: schedule?note=nodata");
		}else if ($datefrom < $onlydate) {
		    header("location: schedule?note=backplot");
		}else{
			//identify ID
			$getinfo=$link->query("SELECT `gy_emp_fullname` From `gy_employee` Where `gy_emp_code`='$sibsid'");
			$countres=$getinfo->num_rows;
			$inforow=$getinfo->fetch_array();

			$empname=words($inforow['gy_emp_fullname']);

			if ($countres > 0) {
				//proceed here
				$period = new DatePeriod(
				     new DateTime($datefrom),
				     new DateInterval('P1D'),
				     new DateTime(date("Y-m-d", strtotime($dateto . "+1 day")))
				);

				foreach ($period as $dates) {
			        $sched_date =  $dates->format('Y-m-d');

			        if ($status == 1) {
			        	$statement = "INSERT INTO `gy_request`(`gy_req_code`, `gy_req_date`, `gy_req_status`, `gy_req_by`, `gy_emp_code`, `gy_emp_fullname`, `gy_sched_day`, `gy_sched_mode`, `gy_sched_login`, `gy_sched_breakout`, `gy_sched_breakin`, `gy_sched_logout`,`gy_req_reason`) Values('$myreqcode','$datenow','0','$user_id','$sibsid','$empname','$sched_date','$status','$login','$breakout','$breakin','$logout','$reason')";
			        }else{
			        	$statement = "INSERT INTO `gy_request`(`gy_req_code`, `gy_req_date`, `gy_req_status`, `gy_req_by`, `gy_emp_code`, `gy_emp_fullname`, `gy_sched_day`, `gy_sched_mode`, `gy_sched_login`, `gy_sched_breakout`, `gy_sched_breakin`, `gy_sched_logout`,`gy_req_reason`) Values('$myreqcode','$datenow','0','$user_id','$sibsid','$empname','$sched_date','$status','00:00:00','00:00:00','00:00:00','00:00:00','$reason')";
			        }

			        //insert here
			        $insertdata=$link->query($statement);
			    }

			    if ($insertdata) {
			    	header("location: schedule?note=added");
			    }else{
			    	header("location: schedule?note=error");
			    }
			}
		}
	}
?>