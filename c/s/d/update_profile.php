<?php  
	include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];

    if (isset($_POST['update_p'])) {
    	$fullname = words($_POST['fullname']);
    	$username = words($_POST['username']);
    	$password1 = words($_POST['password1']);
    	$password2 = words($_POST['password2']);

    	if ($password1 == $password2) {
    		$npass = cryptbsc($password1);

    		//update profile
    		$updateprofile=$link->query("UPDATE `gy_user` SET `gy_password`='$npass' Where `gy_user_id`='$user_id'");

    		if ($updateprofile) {
    			$notetext = "Profile Update";
	    		$notetype = "update";
	    		$noteucode = $user_code;
	    		$noteuser = $user_info;
	    		my_notify($notetext, $notetype , $noteucode , $noteuser);
	    		header("location: index?note=pro_update");
    		}else{
				header("location: index?note=error");
    		}
    	}else{
    		header("location: index?note=mismatch");
    	}
    }
?>