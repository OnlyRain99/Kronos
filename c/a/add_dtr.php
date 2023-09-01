<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];

    //get employee details
    $getemp=$link->query("SELECT `gy_emp_code`,`gy_emp_email`,`gy_emp_fullname`,`gy_emp_account` From `gy_employee` Where `gy_emp_id`='$redirect'");
    $emprow=$getemp->fetch_array();

    if (isset($_POST['mydate'])) {
    	
        $idcode = words($emprow['gy_emp_code']);
    	$email = words($emprow['gy_emp_email']);
    	$fullname = words($emprow['gy_emp_fullname']);
    	$account = words($emprow['gy_emp_account']);

        $mydate = $_POST['mydate'];
        $mytime = $_POST['mytime'];

        $mydatetime = words($mydate." ".$mytime);
        $mystatus = words($_POST['mystatus']);

        $reference = latest_code("gy_logs","gy_log_ref","10000001");

    	$insertdata=$link->query("INSERT INTO `gy_logs`(`gy_log_date`, `gy_emp_id`, `gy_log_code`, `gy_log_email`, `gy_log_fullname`, `gy_log_account`, `gy_log_status`, `gy_log_ref`) Values('$mydatetime','$redirect','$idcode','$email','$fullname','$account','$mystatus','$reference')");

    	if ($insertdata) {
            $notetext = $email." - (admin) ".$mystatus." at ".$mytime;
    		$notetype = "dtr";
    		$noteucode = $user_code;
    		$noteuser = $user_info;
    		my_notify($notetext, $notetype , $noteucode , $noteuser);
    		header("location: view_record?cd=$redirect&note=added");
    	}else{
			header("location: view_record?cd=$redirect&note=error");
    	}
    }
?>