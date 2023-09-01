<?php  
	include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect=words($_GET['cd']);

    if (isset($_POST['account'])) {
    	
    	$account=words($_POST['account']);
        $plot_slot=words($_POST['plot_slot']);
        $plot_justify=words($_POST['plot_justify']);

        $update_data=$link->query("UPDATE `gy_leave_available` SET 
        								`gy_leave_avail_plotted`='$plot_slot', 
        								`gy_leave_avail_justify`='$plot_justify', 
        								`gy_acc_id`='$account' Where `gy_leave_avail_id`='$redirect'");
        if ($update_data) {
        	header("location: leave_plot?note=update");
        }else{
        	header("location: leave_plot?note=error");
        }
    }
?>