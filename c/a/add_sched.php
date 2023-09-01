<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];
    $info=$link->query("SELECT `gy_emp_fullname`,`gy_emp_code` From `gy_employee` Where `gy_emp_id`='$redirect'");
    $inforow=$info->fetch_array();

    if (isset($_POST['datefrom'])) {

        $sibsid = words($inforow['gy_emp_code']);
        $datefrom = words($_POST['datefrom']);
        $dateto = words($_POST['dateto']);
        $status = words($_POST['status']);

        if ($status == 0) {
            $login = words("00:00:00");
            $breakout = words("00:00:00");
            $breakin = words("00:00:00");
            $logout = words("00:00:00");
        }else{
            $login = words($_POST['login']);
            $breakout = words($_POST['breakout']);
            $breakin = words($_POST['breakin']);
            $logout = words($_POST['logout']);
        }

        //identify ID
        $getinfo=$link->query("SELECT `gy_emp_id`,`gy_emp_fullname` From `gy_employee` Where `gy_emp_code`='$sibsid'");
        $inforow=$getinfo->fetch_array();

        $empname=words($inforow['gy_emp_fullname']);

        //proceed here
        $period = new DatePeriod(
             new DateTime($datefrom),
             new DateInterval('P1D'),
             new DateTime(date("Y-m-d", strtotime($dateto . "+1 day")))
        );

        $duplicates=0;

        foreach ($period as $dates) {

            $sched_date = $dates->format('Y-m-d');

            //search for existing record
            $getexist=$link->query("SELECT `gy_sched_id` From `gy_schedule` Where `gy_emp_id`='$redirect' AND `gy_sched_day`='$sched_date'");
            $schedrow=$getexist->fetch_array();
            $countexist=$getexist->num_rows;

            if ($countexist > 0) {

                //insert here
                $updatedata=$link->query("UPDATE `gy_schedule` SET `gy_sched_mode`='$status',`gy_sched_login`='$login',`gy_sched_breakout`='$breakout',`gy_sched_breakin`='$breakin',`gy_sched_logout`='$logout',`gy_sched_reg`='$onlydate',`gy_sched_by`='$user_id' Where `gy_sched_id`='".$schedrow['gy_sched_id']."'");

            }else{

                //insert here
                $updatedata=$link->query("INSERT INTO `gy_schedule`(`gy_emp_id`, `gy_sched_day`, `gy_sched_mode`, `gy_sched_login`, `gy_sched_breakout`, `gy_sched_breakin`, `gy_sched_logout`, `gy_sched_reg`, `gy_sched_by`) Values('$redirect','$sched_date','$status','$login','$breakout','$breakin','$logout','$datenow','$user_id')");
            }
        }

        if ($updatedata) {
            $notetext = "New Schedules is added for ".$inforow['gy_emp_fullname'];
            $notetype = "insert";
            $noteucode = $user_code;
            $noteuser = $user_info;
            my_notify($notetext, $notetype , $noteucode , $noteuser);
            header("location: view_schedule?cd=$redirect&note=added");
        }else{
            header("location: view_schedule?cd=$redirect&note=error");
        }
        
    }
?>