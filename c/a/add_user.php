<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    if (isset($_POST['name'])) {
    	
    	$name = words($_POST['name']);
    	$username = words($_POST['username']);
    	$password1 = words($_POST['password1']);
        $password2 = words($_POST['password2']);
        $ucodes = words(my_rand_str(8));

        if ($password1 == $password2) {
            $npass = cryptbsc($password1);

        	$insertdata=$link->query("INSERT INTO `gy_user`(`gy_user_code`,`gy_full_name`,`gy_username`,`gy_password`) Values('$ucodes','$name','$username','$npass')");

        	if ($insertdata) {
                $notetext = $name." is added to User List";
        		$notetype = "insert";
        		$noteucode = $user_code;
        		$noteuser = $user_info;
        		my_notify($notetext, $notetype , $noteucode , $noteuser);
        		header("location: users?note=added");
        	}else{
    			header("location: users?note=error");
        	}
        }else{
            header("location: users?note=mismatch");
        }
    }
?>