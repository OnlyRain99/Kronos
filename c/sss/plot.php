<?php  
	include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    if (isset($_POST['account'])) {

    	$account=words($_POST['account']);
        $plot_date=words($_POST['plot_date']);
        $plot_slot=words($_POST['plot_slot']);
        $plot_justify=words($_POST['plot_justify']);

        if (check_plotted($plot_date, $account) == "no") {
            header("location: leave_plot?note=duplicate");
        }else{
            $insertdata=$link->query("INSERT INTO `gy_leave_available`(`gy_leave_avail_date`, `gy_leave_avail_plotted`, `gy_leave_avail_approved`, `gy_user_id`, `gy_leave_avail_justify`, `gy_acc_id`) VALUES('$plot_date','$plot_slot','0','$user_id','$plot_justify','$account')");

            if ($insertdata) {
                header("location: leave_plot?note=added");
            }else{
                header("location: leave_plot?note=error");
            }
        }
    }
?>