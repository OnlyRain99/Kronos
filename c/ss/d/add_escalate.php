<?php  
	include("../../../config/conn.php");
	include("../../../config/function.php");
	include("session.php");

	if (isset($_POST['sibsid'])) {

		$mycode = words($_POST['mycode']);
		$sibsid = words($_POST['sibsid']);
		$mydate = words($_POST['mydate']);
		$status = words($_POST['status']);
		$login = words($_POST['login']);
		$breakout = words($_POST['breakout']);
		$breakin = words($_POST['breakin']);
		$logout = words($_POST['logout']);
		$reason = words($_POST['reason']);

		$file = strtotime(date("Y-m-d H:i:s"))."_".$_FILES['file']['name'];

        if ($_FILES['file']['name'] != "") {
            $fileTmpLoc = $_FILES["file"]["tmp_name"];
            $fileSize = $_FILES["file"]["size"];
            $file_download_dir = "../../../kronos_file_store/".$file;

            if ($fileSize > 5000000) {
                header("location: escalate_sched?cd=$redirect&note=sizelimit");
            }else{
                move_uploaded_file($fileTmpLoc, $file_download_dir);
            }
        }else{
            $file_download_dir = "";
        }

		$checkempcode = checkempcode($sibsid);

		if ($checkempcode == "yes") {
			header("location: escalate_sched?note=nodata");
		}else if ($mydate >= $onlydate) {
		    header("location: escalate_sched?note=forwardplot");
		}else{
			//identify ID
			$getinfo=$link->query("SELECT `gy_emp_fullname`,`gy_emp_supervisor` From `gy_employee` Where `gy_emp_code`='$sibsid'");
			$countres=$getinfo->num_rows;
			$inforow=$getinfo->fetch_array();

			$empname=words($inforow['gy_emp_fullname']);
			
			$empsuper=words(get_supervisor($user_code));

	        if ($status != 0) {
	        	$statement = "INSERT INTO `gy_schedule_escalate`(`gy_sched_esc_code`, `gy_req_date`, `gy_req_status`, `gy_req_by`, `gy_req_to`, `gy_emp_code`, `gy_emp_fullname`, `gy_sched_day`, `gy_sched_mode`, `gy_sched_login`, `gy_sched_breakout`, `gy_sched_breakin`, `gy_sched_logout`,`gy_req_reason`,`gy_req_photodir`) Values('$mycode','$datenow','0','$user_id','$empsuper','$sibsid','$empname','$mydate','$status','$login','$breakout','$breakin','$logout','$reason','$file')";
	        }else{
	        	$statement = "INSERT INTO `gy_schedule_escalate`(`gy_sched_esc_code`, `gy_req_date`, `gy_req_status`, `gy_req_by`, `gy_req_to`, `gy_emp_code`, `gy_emp_fullname`, `gy_sched_day`, `gy_sched_mode`, `gy_sched_login`, `gy_sched_breakout`, `gy_sched_breakin`, `gy_sched_logout`,`gy_req_reason`,`gy_req_photodir`) Values('$mycode','$datenow','0','$user_id','$empsuper','$sibsid','$empname','$mydate','$status','00:00:00','00:00:00','00:00:00','00:00:00','$reason','$file')";
	        }

	        //insert here
	        $insertdata=$link->query($statement);

		    if ($insertdata) {
		    	header("location: escalate_sched?note=added");
		    }else{
		    	header("location: escalate_sched?note=error");
		    }
		}
	}
?>