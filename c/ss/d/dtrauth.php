<?php  
	include '../../../config/conn.php';
	include '../../../config/function.php';
	include 'session.php';

	$redirect = @$_GET['cd'];

	//get employee info
	$employee=$link->query("SELECT `gy_emp_id`,`gy_emp_code`,`gy_emp_email`,`gy_emp_fullname`,`gy_emp_account`,`gy_emp_supervisor`,`gy_assignedloc` From `gy_employee` Where `gy_emp_code`='$user_code'");
	$emprow=$employee->fetch_array();

	$ccemail = getusername($emprow['gy_emp_supervisor']);

	$datetime=words(date("Y-m-d H:i:s"));

	//employee details
	$empid=words($emprow['gy_emp_id']);
	$empcode=words($emprow['gy_emp_code']);
	$empemail=words($emprow['gy_emp_email']);
	$empfullname=words($emprow['gy_emp_fullname']);
	$empaccount=words($emprow['gy_emp_account']);
	$emploc=words($emprow['gy_assignedloc']);

	if ($redirect == "login") {

		$mystatus = "Login";

		//check duplicate
		$checkduplicate=$link->query("SELECT `gy_tracker_id`,`gy_tracker_login`,`gy_tracker_status` From `gy_tracker` Where `gy_emp_code`='$user_code' AND `gy_tracker_status`='0' Order By `gy_tracker_date` DESC LIMIT 1");
		$countduplicate=$checkduplicate->num_rows;
		$drow=$checkduplicate->fetch_array();

		$trackercode = latest_code("gy_tracker", "gy_tracker_code", "10001");

		if ($countduplicate > 0) {
			//existing record
			header("location: index?note=invalid");
		}else{
			//empty record
			$insertdata=$link->query("INSERT INTO 
				`gy_tracker`(`gy_tracker_code`, `gy_tracker_date`, `gy_emp_code`, `gy_emp_email`, `gy_emp_fullname`, `gy_emp_account`, `gy_tracker_login`, `gy_tracker_breakout`, `gy_tracker_breakin`, `gy_tracker_logout`, `gy_tracker_status`, `gy_tracker_om`,`gy_tracker_loc`) 
				Values('$trackercode','$datetime','$empcode','$empemail','$empfullname','$empaccount','$datetime','0','0','0','0','0','$emploc')");

			if ($insertdata) {
				//insert data
				$insertlogdata=$link->query("INSERT INTO `gy_logs`(`gy_log_date`, `gy_emp_id`, `gy_log_code`, `gy_log_email`, `gy_log_fullname`, `gy_log_account`, `gy_log_status`) Values('$datetime', '$empid', '$empcode', '$empemail', '$empfullname', '$empaccount', '$mystatus')");

				if ($insertlogdata) {
					
					$emailto = $empemail;
		            $emailsubject = "SiBS KRONOS - ".date("F d, Y g:i A");

		            $emailmessage .= "<center>";
		            $emailmessage .= "<h1>$empfullname<h1>";
		            $emailmessage .= "<h3 style='text-transform: uppercase;'>$mystatus</h3>";
		            $emailmessage .= "<h2>".date("g:i:s A")."</h2>";
		            $emailmessage .= "<p><i>SYSTEM GENERATED EMAIL. DO NOT REPLY.</i></p>";
		            $emailmessage .= "</center>";

		            $header = "Cc: $ccemail";

		            include 'mailer.php';
		            // mail($emailto, $emailsubject, $emailmessage, $header);

		            $notetext = $empemail." - ".$mystatus." at ".date("g:i A");
		    		$notetype = "dtr";
		    		$noteucode = $user_code;
		    		$noteuser = $user_info;
		    		my_notify($notetext, $notetype , $noteucode , $noteuser);
					header("location: ./?note=login");
				}else{
					header("location: ./?note=fail");
				}
			}else{
				header("location: ./?note=fail");
			}
		}
	}else if ($redirect == "breakout") {
        buttonstatus($user_code, $myaccount);
		$mystatus = "Break-Out";

		//check duplicate
		$checkduplicate=$link->query("SELECT `gy_tracker_code`,`gy_tracker_breakout`,`gy_tracker_status` From `gy_tracker` Where `gy_emp_code`='$user_code' AND `gy_tracker_status`='0' Order By `gy_tracker_date` DESC LIMIT 1");
		$countexist=$checkduplicate->num_rows;
		$drow=$checkduplicate->fetch_array();

		//get trackercode
		$trackercode = words($drow['gy_tracker_code']);

		if ($countexist > 0) {
			//empty record
			$updatedata=$link->query("UPDATE `gy_tracker` SET `gy_tracker_breakout`='$datetime' Where `gy_tracker_code`='$trackercode'");

			if ($updatedata) {
				//insert data
				$insertlogdata=$link->query("INSERT INTO `gy_logs`(`gy_log_date`, `gy_emp_id`, `gy_log_code`, `gy_log_email`, `gy_log_fullname`, `gy_log_account`, `gy_log_status`) Values('$datetime', '$empid', '$empcode', '$empemail', '$empfullname', '$empaccount', '$mystatus')");

				if ($insertlogdata) {
					
					$emailto = $empemail;
		            $emailsubject = "SiBS KRONOS - ".date("F d, Y g:i A");

		            $emailmessage .= "<center>";
		            $emailmessage .= "<h1>$empfullname<h1>";
		            $emailmessage .= "<h3 style='text-transform: uppercase;'>$mystatus</h3>";
		            $emailmessage .= "<h2>".date("g:i:s A")."</h2>";
		            $emailmessage .= "<p><i>SYSTEM GENERATED EMAIL. DO NOT REPLY.</i></p>";
		            $emailmessage .= "</center>";

		            $header = "Cc: $ccemail";

		            include 'mailer.php';
		            // mail($emailto, $emailsubject, $emailmessage, $header);

		            $notetext = $empemail." - ".$mystatus." at ".date("g:i A");
		    		$notetype = "dtr";
		    		$noteucode = $user_code;
		    		$noteuser = $user_info;
		    		my_notify($notetext, $notetype , $noteucode , $noteuser);
					header("location: ./?note=breakout");
				}else{
					header("location: ./?note=fail");
				}
			}else{
				header("location: ./?note=fail");
			}
		}else{
			header("location: index?note=invalid");
		}
	}else if ($redirect == "breakin") {
        buttonstatus($user_code, $myaccount);
		$mystatus = "Break-In";

		//check duplicate
		$checkduplicate=$link->query("SELECT `gy_tracker_code`,`gy_tracker_breakout`,`gy_tracker_breakin`,`gy_tracker_status` From `gy_tracker` Where `gy_emp_code`='$user_code' AND `gy_tracker_status`='0' Order By `gy_tracker_date` DESC LIMIT 1");
		$countduplicate=$checkduplicate->num_rows;
		$drow=$checkduplicate->fetch_array();

		//get trackercode
		$trackercode = words($drow['gy_tracker_code']);

		//calculate break hours
		$breakhours = get_breakhours($drow['gy_tracker_breakout'], $datetime);

		if ($countduplicate > 0) {
			//empty record
			$updatedata=$link->query("UPDATE `gy_tracker` SET `gy_tracker_breakin`='$datetime',`gy_tracker_bh`='$breakhours' Where `gy_tracker_code`='$trackercode'");

			if ($updatedata) {
				//insert data
				$insertlogdata=$link->query("INSERT INTO `gy_logs`(`gy_log_date`, `gy_emp_id`, `gy_log_code`, `gy_log_email`, `gy_log_fullname`, `gy_log_account`, `gy_log_status`) Values('$datetime', '$empid', '$empcode', '$empemail', '$empfullname', '$empaccount', '$mystatus')");

				if ($insertlogdata) {
					
					$emailto = $empemail;
		            $emailsubject = "SiBS KRONOS - ".date("F d, Y g:i A");

		            $emailmessage .= "<center>";
		            $emailmessage .= "<h1>$empfullname<h1>";
		            $emailmessage .= "<h3 style='text-transform: uppercase;'>$mystatus</h3>";
		            $emailmessage .= "<h2>".date("g:i:s A")."</h2>";
		            $emailmessage .= "<p><i>SYSTEM GENERATED EMAIL. DO NOT REPLY.</i></p>";
		            $emailmessage .= "</center>";

		            $header = "Cc: $ccemail";

		            include 'mailer.php';
		            // mail($emailto, $emailsubject, $emailmessage, $header);

		            $notetext = $empemail." - ".$mystatus." at ".date("g:i A");
		    		$notetype = "dtr";
		    		$noteucode = $user_code;
		    		$noteuser = $user_info;
		    		my_notify($notetext, $notetype , $noteucode , $noteuser);
					header("location: ./?note=breakin");
				}else{
					header("location: ./?note=fail");
				}
			}else{
				header("location: ./?note=fail");
			}
		}else{
			header("location: index?note=invalid");
		}
	}else if ($redirect == "logout") {
        buttonstatus($user_code, $myaccount);
		$mystatus = "Logout";

		//check duplicate
		$checkduplicate=$link->query("SELECT `gy_tracker_code`,`gy_tracker_login`,`gy_tracker_breakout`,`gy_tracker_breakin`,`gy_tracker_logout`,`gy_tracker_bh`,`gy_tracker_status` From `gy_tracker` Where `gy_emp_code`='$user_code' AND `gy_tracker_status`='0' Order By `gy_tracker_date` DESC LIMIT 1");
		$countduplicate=$checkduplicate->num_rows;
		$drow=$checkduplicate->fetch_array();

		//get trackercode
		$trackercode = words($drow['gy_tracker_code']);
		$logindate = words(date("Y-m-d", strtotime($drow['gy_tracker_login'])));

		$schedule=$link->query("SELECT `gy_sched_id` From `gy_schedule` Where `gy_emp_id`='$user_id' AND `gy_sched_day`='$logindate'");
		$schedcount=$schedule->num_rows;

		if ($schedcount > 0) {
			//get sched login
			$schedlogin = get_schedule_login($empid, $logindate);
			//get logout sched
			$schedlogout = get_schedule_logout($empid, $logindate);

			//get workhours
			$workhours = get_workhours($schedlogin, $schedlogout, $drow['gy_tracker_login'], $drow['gy_tracker_breakout'], $drow['gy_tracker_breakin'], $datetime);

			$overtime = get_overtime($schedlogout, $datetime);
		}else{
			$workhours = rd_workhours($drow['gy_tracker_login'], $drow['gy_tracker_breakout'], $drow['gy_tracker_breakin'], $datetime);

			$overtime = rd_overtime($drow['gy_tracker_login'], $drow['gy_tracker_breakout'], $drow['gy_tracker_breakin'], $datetime);
		}

		if ($countduplicate > 0) {
			
			//empty record
			$updatedata=$link->query("UPDATE `gy_tracker` SET 
									`gy_tracker_logout`='$datetime',
									`gy_tracker_wh`='$workhours',
									`gy_tracker_ot`='$overtime',
									`gy_tracker_status`='1'
									Where `gy_tracker_code`='$trackercode'");

			if ($updatedata) {
				//insert data
				$insertlogdata=$link->query("INSERT INTO `gy_logs`(`gy_log_date`, `gy_emp_id`, `gy_log_code`, `gy_log_email`, `gy_log_fullname`, `gy_log_account`, `gy_log_status`) Values('$datetime', '$empid', '$empcode', '$empemail', '$empfullname', '$empaccount', '$mystatus')");

				if ($insertlogdata) {
					
					$emailto = $empemail;
		            $emailsubject = "SiBS KRONOS - ".date("F d, Y g:i A");

		            $emailmessage .= "<center>";
		            $emailmessage .= "<h1>$empfullname<h1>";
		            $emailmessage .= "<h3 style='text-transform: uppercase;'>$mystatus</h3>";
		            $emailmessage .= "<h2>".date("g:i:s A")."</h2>";
		            $emailmessage .= "<p><i>SYSTEM GENERATED EMAIL. DO NOT REPLY.</i></p>";
		            $emailmessage .= "</center>";

		            $header = "Cc: $ccemail";

		            include 'mailer.php';
		            // mail($emailto, $emailsubject, $emailmessage, $header);

		            $notetext = $empemail." - ".$mystatus." at ".date("g:i A");
		    		$notetype = "dtr";
		    		$noteucode = $user_code;
		    		$noteuser = $user_info;
		    		my_notify($notetext, $notetype , $noteucode , $noteuser);
					header("location: ./?note=logout");
				}else{
					header("location: ./?note=fail");
				}
			}else{
				header("location: ./?note=fail");
			}
		}else{
			header("location: index?note=invalid");
		}
	}else{
		header("location: index?note=invalid");
	}
?>