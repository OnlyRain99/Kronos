<?php 
    session_start([
        'cookie_lifetime' => 86400,
    ]);

    if(!isset($_SESSION['fus_user_id'])){
        header("location: ../../");
    }else if ($_SESSION['fus_user_type'] != 3) {
        header("location: ../../");
    }

    $user_id = $_SESSION['fus_user_id'];
    $user_type = $_SESSION['fus_user_type'];

    //find user
    $identify_user=$link->query("Select * From `gy_user` Where `gy_user_id`='$user_id'");
    $row=$identify_user->fetch_array();

    $user_info = words($row['gy_full_name']);
    $user_code = words($row['gy_user_code']);
    $user_sign = words($row['gy_username']);
    $user_lock = words($row['gy_password']);
    $user_id = words($row['gy_user_id']);
    $user_function = words($row['gy_user_function']);
    $datenow = words(date("Y-m-d H:i:s"));
    $onlydate = words(date("Y-m-d"));
    $myaccount = get_account_id($user_code);

    if ($user_function == 0) {
        header("location: d/");
    }else if ($user_function == 1) {
        header("location: sc/");
    }else if ($user_function == 2) {
        header("location: cb/");
    }else if ($user_function == 3) {
        header("location: etc/");
    }else{
        header("location: logout");
    }
?>