<?php  
	include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

    $redirect = @$_GET['cd'];
    $get_announce=$link->query("SELECT `gy_ann_serial` From `gy_announce` Where `gy_ann_id`='$redirect'");
    $ann=$get_announce->fetch_array();

    $deletedata=$link->query("DELETE FROM `gy_announce` Where `gy_ann_id`='$redirect'");

    if ($deletedata) {
    	$notetext = "Announcement Removed/Deleted Serial -> ".$ann['gy_ann_serial'];
        $notetype = "update";
        $noteucode = $user_code;
        $noteuser = $user_info;
        my_notify($notetext, $notetype, $noteucode, $noteuser);

    	header("location: ../index?note=delete");
    }else{
    	header("location: ../index?note=error");
    }
$link->close();
?>