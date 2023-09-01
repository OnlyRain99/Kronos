<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];
    $dir = @$_GET['dir'];
    $mode = @$_GET['mode'];
    $datef = @$_GET['datef'];
    $datet = @$_GET['datet'];


    $info=$link->query("SELECT `gy_emp_fullname` From `gy_employee` Where `gy_emp_id`='$redirect'");
    $inforow=$info->fetch_array();

    if (isset($_POST['mymode'])) {

        $myday = words($_POST['myday']);
    	$mymode = words($_POST['mymode']);

        if ($mymode == 0) {
            $mylogin = words("00:00:00");
            $mybreakout = words("00:00:00");
            $mybreakin = words("00:00:00");
            $mylogout = words("00:00:00");
        }else{
            $mylogin = words($_POST['mylogin']);
            $mybreakout = words($_POST['mybreakout']);
            $mybreakin = words($_POST['mybreakin']);
            $mylogout = words($_POST['mylogout']);
        }

        $updatedata=$link->query("UPDATE `gy_schedule` SET `gy_sched_day`='$myday', `gy_sched_mode`='$mymode', `gy_sched_login`='$mylogin', `gy_sched_breakout`='$mybreakout', `gy_sched_breakin`='$mybreakin', `gy_sched_logout`='$mylogout' Where `gy_sched_id`='$dir'");

        if ($updatedata) {
            $notetext = "Schedule Updated for ".$inforow['gy_emp_fullname'];
            $notetype = "update";
            $noteucode = $user_code;
            $noteuser = $user_info;
            my_notify($notetext, $notetype , $noteucode , $noteuser);

            if ($mode == "normal") {
                header("location: view_schedule?cd=$redirect&note=update");
            }else{
                header("location: search_schedule?cd=$redirect&note=update&datef=$datef&datet=$datet");
            }

        }else{

            if ($mode == "normal") {
                header("location: view_schedule?cd=$redirect&note=error");
            }else{
                header("location: search_schedule?cd=$redirect&note=error&datef=$datef&datet=$datet");
            }

        }
    }
?>