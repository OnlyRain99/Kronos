<?php  
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    //update timekeep will not add edit logs here in admin ...

    $redirect = @$_GET['cd'];
    $change = @$_GET['c'];
    $mode = @$_GET['mode'];
    $pn = @$_GET['pn'];
    $s = @$_GET['s'];
    $f = @$_GET['f'];
    $t = @$_GET['t'];
    $fil = @$_GET['fil'];

    $gettrack=$link->query("SELECT `gy_emp_code`,`gy_emp_fullname`,`gy_tracker_date`,`gy_tracker_login`,`gy_tracker_breakout`,`gy_tracker_breakin`,`gy_tracker_logout` From `gy_tracker` Where `gy_tracker_id`='$redirect'");
    $trackrow=$gettrack->fetch_array();
    $empcode = words($trackrow['gy_emp_code']);
    $trackerdate = words($trackrow['gy_tracker_date']);
    $trackeronlydate = words(date("Y-m-d", strtotime($trackrow['gy_tracker_date'])));

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
                header("location: requests?pn=$pn&note=nochange");
            }else{
                header("location: search_request?note=nochange&s=$s&f=$f&t=$t&fil=$fil&pn=$pn");
            }
        }else{
            //update timekeep
            $updatedata=$link->query("UPDATE `gy_tracker` SET `gy_tracker_login`='$login',`gy_tracker_breakout`='$breakout',`gy_tracker_breakin`='$breakin',`gy_tracker_logout`='$logout',`gy_tracker_wh`='$wh',`gy_tracker_bh`='$bh',`gy_tracker_ot`='$ot',`gy_tracker_history`=CONCAT(`gy_tracker_history`,'$updates') Where `gy_tracker_id`='$redirect'");

            if ($updatedata) {

                if ($user_id == 1) {
                    # code...
                }else{
                    $notetext = $trackrow['gy_emp_fullname']." time keep updated";
                    $notetype = "update";
                    $noteucode = $user_code;
                    $noteuser = $user_info;
                    my_notify($notetext, $notetype, $noteucode, $noteuser);
                }
                
                if ($mode == "normal") {
                    header("location: requests?pn=$pn&note=update");
                }else{
                    header("location: search_request?note=update&s=$s&f=$f&t=$t&fil=$fil&pn=$pn");
                }

            }
        }
    }
?>