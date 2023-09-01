<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    if (isset($_POST['account'])) {
    	$account = words($_POST['account']);
        $accdpt = words($_POST['accdpt']);
    	$insertdata=$link->query("INSERT INTO `gy_accounts`(`gy_acc_name`,`gy_dept_id`) Values('$account','$accdpt')");
    	if ($insertdata) {
            $notetext = $account." has been added to Company Accounts List";
    		$notetype = "insert";
    		$noteucode = $user_code;
    		$noteuser = $user_info;
    		my_notify($notetext, $notetype , $noteucode , $noteuser);
    		header("location: accounts?note=added");
    	}else{
			header("location: accounts?note=error");
    	}
    }
$link->close();
?>