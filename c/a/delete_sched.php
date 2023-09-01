<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];
    $dir = @$_GET['dir'];
    $mode = @$_GET['mode'];
    $datef = @$_GET['datef'];
    $datet = @$_GET['datet'];

    //get employee details
    $getemp=$link->query("SELECT `gy_emp_fullname` From `gy_employee` Where `gy_emp_id`='$redirect'");
    $emprow=$getemp->fetch_array();

    //get shced row
    $getsched=$link->query("SELECT `gy_sched_day` From `gy_schedule` Where `gy_sched_id`='$dir'");
    $schedrow=$getsched->fetch_array();

    if ($redirect == "") {
        header("location: view_schedule?note=error");
    }else{
        $deletedata=$link->query("DELETE FROM `gy_schedule` Where `gy_sched_id`='$dir'");

        if ($deletedata) {
            $notetext = $schedrow['gy_sched_day']." has been removed from ".$emprow['gy_emp_fullname']." schedule";
            $notetype = "delete";
            $noteucode = $user_code;
            $noteuser = $user_info;
            my_notify($notetext, $notetype , $noteucode , $noteuser);

            if ($mode == "normal") {
                header("location: view_schedule?cd=$redirect&note=delete");
            }else{
                header("location: search_schedule?cd=$redirect&note=delete&datef=$datef&datet=$datet");
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