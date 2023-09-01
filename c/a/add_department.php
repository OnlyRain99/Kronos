<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    if (isset($_POST['department'])) {
    	
    	$department = words($_POST['department']);

    	$insertdata=$link->query("INSERT INTO `gy_department`(`name_department`) Values('$department')");

    	if ($insertdata) {
            $notetext = $department." has been added to Company Accounts List";
    		$notetype = "insert";
    		$noteucode = $user_code;
    		$noteuser = $user_info;
    		my_notify($notetext, $notetype , $noteucode , $noteuser);
    		header("location: accounts?note=added");
    	}else{
			header("location: accounts?note=error");
    	}
    }
$link->close(); ?>