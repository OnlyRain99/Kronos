<?php  
	include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect=words($_GET['cd']);

    if ($redirect != "") {

        $delete_data=$link->query("DELETE FROM `gy_leave_available` Where `gy_leave_avail_id`='$redirect'");

        if ($delete_data) {
        	header("location: leave_plot?note=delete");
        }else{
            header("location: leave_plot?note=error");
        }
    }else{
        header("location: 404");
    }
?>