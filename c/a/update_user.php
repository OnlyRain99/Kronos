<?php  
	include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];

    if (isset($_POST['name'])) {
        $name = words($_POST['name']);
    	$username = words($_POST['username']);
    	$password1 = words($_POST['password1']);
    	$password2 = words($_POST['password2']);

    	if ($password1 == $password2) {
    		$npass = cryptbsc($password1);

    		//update profile
    		$updatedata=$link->query("UPDATE `gy_user` SET `gy_full_name`='$name',`gy_username`='$username',`gy_password`='$npass' Where `gy_user_id`='$redirect'");

    		if ($updatedata) {
    			$notetext = "User Update";
	    		$notetype = "update";
	    		$noteucode = $user_code;
	    		$noteuser = $user_info;
	    		my_notify($notetext, $notetype , $noteucode , $noteuser);
	    		header("location: users?note=update");
    		}else{
				header("location: users?note=error");
    		}
    	}else{
    		header("location: users?note=mismatch");
    	}
    }
?>