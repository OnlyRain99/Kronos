<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];
    $accounts=$link->query("SELECT * From `gy_accounts` Where `gy_acc_id`='$redirect'");
    $acc=$accounts->fetch_array();

    if (isset($_POST['account'])) {
    	$account = words($_POST['account']);
        $accdpt = words($_POST['accdpt']);
        $sttacc = words($_POST['sttacc']);
        if($sttacc!=0){ $sttacc=1; }
    	$updatedata=$link->query("UPDATE `gy_accounts` SET `gy_acc_name`='$account',`gy_dept_id`='$accdpt',`gy_acc_status`='$sttacc' Where `gy_acc_id`='$redirect'");
    	if ($updatedata) {
            $my_a = compare_update($acc['gy_acc_name'] , $account , "Account Name");
            $notetext = "Account Update -> ".$my_a;
    		$notetype = "update";
    		$noteucode = $user_code;
    		$noteuser = $user_info;
    		my_notify($notetext, $notetype , $noteucode , $noteuser);
    		header("location: accounts?note=update");
    	}else{
			header("location: accounts?note=error");
    	}
    }
$link->close();
?>