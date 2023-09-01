<?php  
	include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];
    
    //delete data
    $deletedata=$link->query("DELETE FROM `gy_request` Where `gy_req_id`='$redirect'");

    if ($deletedata) {
    	header("location: schedule?note=delete");
    }else{
    	header("location: schedule?note=error");
    }
?>