<?php 

	session_start([
        'cookie_lifetime' => 86400,
    ]);

	if(!isset($_SESSION['fus_user_id'])){
        header("location: ../../");
		echo "<script> window.location.href = 'https://kronos.mysibs.info' </script>";
    }else if ($_SESSION['fus_user_type'] != 0) {
        header("location: ../../");
		echo "<script> window.location.href = 'https://kronos.mysibs.info' </script>";
    }

    $user_id = $_SESSION['fus_user_id'];
    $user_type = $_SESSION['fus_user_type'];

    //find user
    $identify_user=$link->query("Select * From `gy_user` Where `gy_user_id`='$user_id'");
    $row=$identify_user->fetch_array();

    $user_info = $row['gy_full_name'];
    $user_code = $row['gy_user_code'];
    $user_sign = $row['gy_username'];
    $user_lock = $row['gy_password'];
    $user_id = $row['gy_user_id'];
    $datenow = words(date("Y-m-d H:i:s"));
    $onlydate = words(date("Y-m-d"));
    $myaccount = get_account_id($user_code);

?>