<?php  
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];
    $mode = @$_GET['mode'];
    $pn = @$_GET['pn'];
    $s = @$_GET['s'];
    $f = @$_GET['f'];
    $t = @$_GET['t'];

    $gettrack=$link->query("SELECT `gy_emp_code`,`gy_emp_fullname`,`gy_tracker_date`,`gy_tracker_login`,`gy_tracker_breakout`,`gy_tracker_breakin`,`gy_tracker_logout` From `gy_tracker` Where `gy_tracker_id`='$redirect'");
    $trackrow=$gettrack->fetch_array();
    $empcode = words($trackrow['gy_emp_code']);
    $trackerdate = words($trackrow['gy_tracker_date']);
    $trackeronlydate = words(date("Y-m-d", strtotime($trackrow['gy_tracker_date'])));

    $empid = getempid($trackrow['gy_emp_code']);

    $schedule=$link->query("SELECT `gy_sched_day`,`gy_sched_login`,`gy_sched_breakout`,`gy_sched_breakin`,`gy_sched_logout` From `gy_schedule` Where `gy_emp_id`='$empid' AND `gy_sched_day`='$trackeronlydate'");
    $schedrow=$schedule->fetch_array();
    $schedcount=$schedule->num_rows;

    if (isset($_POST['logindate'])) {
        $logindate = words($_POST['logindate']);
        $logintime = words($_POST['logintime']);

        $breakoutdate = words($_POST['breakoutdate']);
        $breakouttime = words($_POST['breakouttime']);

        $breakindate = words($_POST['breakindate']);
        $breakintime = words($_POST['breakintime']);

        $logoutdate = words($_POST['logoutdate']);
        $logouttime = words($_POST['logouttime']);

        $login = $logindate." ".$logintime;
        $breakout = $breakoutdate." ".$breakouttime;
        $breakin = $breakindate." ".$breakintime;
        $logout = $logoutdate." ".$logouttime;

        if ($breakindate == "" || $breakintime == "") {
            $breakin = "0000-00-00 00:00:00";
        }else{
            $breakin = $breakin;
        }

        if ($breakoutdate == "" || $breakouttime == "") {
            $breakout = "0000-00-00 00:00:00";
        }else{
            $breakout = $breakout;
        }

        //calculate work hours, break hours, over time
        $bh = get_breakhours($breakout, $breakin);

        if ($schedcount > 0) {
            //get workhours
            $schedlogin = $schedrow['gy_sched_day']." ".$schedrow['gy_sched_login'];
            $schedlogout = $logoutdate." ".$schedrow['gy_sched_logout'];

            $wh = get_workhours($schedlogin ,$login, $breakout, $breakin, $logout);

            $ot = get_overtime($schedlogout, $logout);
        }else{
            $wh = rd_workhours($login, $breakout, $breakin, $logout);

            $ot = rd_overtime($login, $breakout, $breakin, $logout);
        }

        $clogin = compare_update(date("g:i:s A", strtotime($trackrow['gy_tracker_login'])) , date("g:i:s A", strtotime($logintime)) , "Login");
        $cbreakout = compare_update(date("g:i:s A", strtotime($trackrow['gy_tracker_breakout'])) , date("g:i:s A", strtotime($breakouttime)) , "Break-Out");
        $cbreakin = compare_update(date("g:i:s A", strtotime($trackrow['gy_tracker_breakin'])) , date("g:i:s A", strtotime($breakintime)) , "Break-In");
        $clogout = compare_update(date("g:i:s A", strtotime($trackrow['gy_tracker_logout'])) , date("g:i:s A", strtotime($logouttime)) , "Logout");

        $updates = $clogin." ".$cbreakout." ".$cbreakin." ".$clogout." by ".$user_info." at ".date("m/d/Y")."<br>";

        if ($clogin == "" && $cbreakout == "" && $cbreakin == "" && $clogout == "") {
            if ($mode == "normal") {
                header("location: requests?note=nochange&pn=$pn");
            }else{
                header("location: search_request?note=nochange&s=$s&f=$f&t=$t");
            }
        }else{

            //check if edit is allowed (1 per cut off)
            $check=$link->query("SELECT `gy_emp_lastedit`,`gy_emp_id` From `gy_employee` Where `gy_emp_code`='$empcode'");
            $crow=$check->fetch_array();

            $checkallow = checklastedit($crow['gy_emp_id'] ,date("Y-m-d", strtotime($trackerdate)), $edit_allow);

            if ($checkallow == "yes") {
                //update timekeep
                $updatedata=$link->query("UPDATE `gy_tracker` SET `gy_tracker_login`='$login',`gy_tracker_breakout`='$breakout',`gy_tracker_breakin`='$breakin',`gy_tracker_logout`='$logout',`gy_tracker_wh`='$wh',`gy_tracker_bh`='$bh',`gy_tracker_ot`='$ot',`gy_tracker_history`=CONCAT(`gy_tracker_history`,'$updates') Where `gy_tracker_id`='$redirect'");

                $status = "nice";
            }else{
                $status = "bad";
            }

            if ($status == "nice") {

                //insert edit log
                $empid=words($crow['gy_emp_id']);
                $inserteditlog=$link->query("INSERT INTO `gy_editlog`(`gy_emp_id`,`gy_edit_date`) Values('$empid','$trackeronlydate')");

                $notetext = $trackrow['gy_emp_fullname']." time keep updated";
                $notetype = "update";
                $noteucode = $user_code;
                $noteuser = $user_info;
                my_notify($notetext, $notetype, $noteucode, $noteuser);

                if ($mode == "normal") {
                    header("location: requests?note=update&pn=$pn");
                }else{
                    header("location: search_request?note=update&s=$s&f=$f&t=$t");
                }

            }else{
                if ($mode == "normal") {
                    header("location: requests?note=limit&pn=$pn");
                }else{
                    header("location: search_request?note=limit&s=$s&f=$f&t=$t");
                }
            }
        }
    }
?>