<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];
    $accounts=$link->query("SELECT * From `gy_accounts` Where `gy_acc_id`='$redirect'");
    $acc=$accounts->fetch_array();

    if ($redirect == "") {
        header("location: users?note=error");
    }else{
        $deletedata=$link->query("DELETE FROM `gy_accounts` Where `gy_acc_id`='$redirect'");

        if ($deletedata) {
            $notetext = $acc['gy_acc_name']." has been removed from the Company Account List";
            $notetype = "delete";
            $noteucode = $user_code;
            $noteuser = $user_info;
            my_notify($notetext, $notetype , $noteucode , $noteuser);
            header("location: accounts?note=delete");
        }else{
            header("location: accounts?note=error");
        }
    }
?>