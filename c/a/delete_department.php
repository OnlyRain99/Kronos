<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];
    $dept=$link->query("SELECT * From `gy_department` Where `id_department`='$redirect'");
    $acc=$dept->fetch_array();

    if ($redirect == "") {
        header("location: users?note=error");
    }else{
        $deletedata=$link->query("DELETE FROM `gy_department` Where `id_department`='$redirect'");

        if ($deletedata) {
            $notetext = $acc['name_department']." has been removed from the Company Account List";
            $notetype = "delete";
            $noteucode = $user_code;
            $noteuser = $user_info;
            my_notify($notetext, $notetype , $noteucode , $noteuser);
            header("location: accounts?note=delete");
        }else{
            header("location: accounts?note=error");
        }
    }
$link->close();
?>