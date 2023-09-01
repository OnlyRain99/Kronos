<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];
    $userinfos=$link->query("SELECT * From `gy_user` Where `gy_user_id`='$redirect'");
    $trow=$userinfos->fetch_array();

    if ($redirect == "") {
        header("location: users?note=error");
    }else{
        $deletedata=$link->query("DELETE FROM `gy_user` Where `gy_user_id`='$redirect'");

        if ($deletedata) {
            $notetext = $trow['gy_full_name']." has been removed from the Users List";
            $notetype = "delete";
            $noteucode = $user_code;
            $noteuser = $user_info;
            my_notify($notetext, $notetype , $noteucode , $noteuser);
            header("location: users?note=delete");
        }else{
            header("location: users?note=error");
        }
    }
?>