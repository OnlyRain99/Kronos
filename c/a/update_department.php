<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];
    $dept=$link->query("SELECT * From `gy_department` Where `id_department`='$redirect'");
    $acc=$dept->fetch_array();

    if (isset($_POST['editdept'])) {
        $department = words($_POST['editdept']);
    	$updatedata=$link->query("UPDATE `gy_department` SET `name_department`='$department' Where `id_department`='$redirect'");
    	if ($updatedata) {
            $my_a = compare_update($acc['name_department'] , $department , "Department Name");

            $notetext = "Department Update -> ".$my_a;
    		$notetype = "update";
    		$noteucode = $user_code;
    		$noteuser = $user_info;
    		my_notify($notetext, $notetype , $noteucode , $noteuser);
    		header("location: accounts?note=update");
    	}else{
			header("location: accounts?note=error");
    	}
    }
$link->close(); ?>