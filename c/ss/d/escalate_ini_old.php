<?php  
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];

    $gettrack=$link->query("SELECT * From `gy_tracker` Where `gy_tracker_id`='$redirect' AND `gy_tracker_request`=''");
    $trackrow=$gettrack->fetch_array();
//start check if allowed
	$infocnt = mysqli_num_rows($gettrack);
	$gyempcode = $trackrow['gy_emp_code'];
	$ifcor=$link->query("SELECT `gy_emp_code` From `gy_employee` Where `gy_emp_supervisor`='$user_id' AND `gy_emp_code`='$gyempcode' LIMIT 1");
	$ifcornr = mysqli_num_rows($ifcor);
	if($ifcornr == 0){
	$ifcor=$link->query("SELECT `gy_emp_supervisor` From `gy_employee` Where `gy_emp_code`='$gyempcode' LIMIT 1");		
	$ifcrow=$ifcor->fetch_array();
	$gyempsup = get_emp_code($ifcrow['gy_emp_supervisor']);
	if(mysqli_num_rows($ifcor) > 0 && $ifcrow['gy_emp_supervisor']!= 0){
	$ifcor=$link->query("SELECT `gy_emp_code` From `gy_employee` Where `gy_emp_supervisor`='$user_id' AND `gy_emp_code`='$gyempsup' LIMIT 1");	
	$ifcornr = mysqli_num_rows($ifcor);
	}
	}
	if($ifcornr == 0 || $infocnt == 0){ ?> <script> window.close(); </script> <?php }	
//end check if allowed
    $empcode = words($trackrow['gy_emp_code']);
    $trackerdate = words($trackrow['gy_tracker_date']);
    $trackeronlydate = words(date("Y-m-d", strtotime($trackrow['gy_tracker_date'])));

    $empid = getempid($trackrow['gy_emp_code']);

    $schedule=$link->query("SELECT `gy_sched_day`,`gy_sched_login`,`gy_sched_breakout`,`gy_sched_breakin`,`gy_sched_logout` From `gy_schedule` Where `gy_emp_id`='$empid' AND `gy_sched_day`='$trackeronlydate'");
    $schedrow=$schedule->fetch_array();
    $schedcount=$schedule->num_rows;

    if (isset($_POST['type'])) {

        $type = words($_POST['type']);
        $mysuper = get_supervisor($user_code);

        if ($type == 1) {
            $logindate = "";
            $logintime = "";
            $breakoutdate = "";
            $breakouttime = "";
            $breakindate = "";
            $breakintime = "";
            $logoutdate = "";
            $logouttime = "";
            $overtime = "overtime";
        }else if ($type == 2) {
            $logindate = words($_POST['logindate']);
            $logintime = words($_POST['logintime']);
            $breakoutdate = "";
            $breakouttime = "";
            $breakindate = "";
            $breakintime = "";
            $logoutdate = words($_POST['logoutdate']);
            $logouttime = words($_POST['logouttime']);
            $overtime = "";
        }else if ($type == 3) {
            $logindate = "";
            $logintime = "";
            $breakoutdate = words($_POST['breakoutdate']);
            $breakouttime = words($_POST['breakouttime']);
            $breakindate = words($_POST['breakindate']);
            $breakintime = words($_POST['breakintime']);
            $logoutdate = "";
            $logouttime = "";
            $overtime = "";
        }else if ($type == 4) {
            $logindate = words($_POST['logindate']);
            $logintime = words($_POST['logintime']);
            $breakoutdate = words($_POST['breakoutdate']);
            $breakouttime = words($_POST['breakouttime']);
            $breakindate = words($_POST['breakindate']);
            $breakintime = words($_POST['breakintime']);
            $logoutdate = words($_POST['logoutdate']);
            $logouttime = words($_POST['logouttime']);
            $overtime = "";
        }else if ($type == 5) {
            $logindate = words($_POST['logindate']);
            $logintime = words($_POST['logintime']);
            $breakoutdate = words($_POST['breakoutdate']);
            $breakouttime = words($_POST['breakouttime']);
            $breakindate = words($_POST['breakindate']);
            $breakintime = words($_POST['breakintime']);
            $logoutdate = words($_POST['logoutdate']);
            $logouttime = words($_POST['logouttime']);
            $overtime = "all";
        }else{

        }

        $file = strtotime(date("Y-m-d H:i:s"))."_".$_FILES['file']['name'];

        if ($_FILES['file']['name'] != "") {
            $fileTmpLoc = $_FILES["file"]["tmp_name"];
            $fileSize = $_FILES["file"]["size"];
            $file_download_dir = "../../../kronos_file_store/".$file;

            if ($fileSize > 5000000) {
                header("location: escalate?cd=$redirect&note=sizelimit");
            }else{
                move_uploaded_file($fileTmpLoc, $file_download_dir);
            }
        }else{
            $file_download_dir = "";
        }

        $reason = words($_POST['reason']);

        if ($logindate == "" || $logintime == "") {
            $login = $trackrow['gy_tracker_login'];
        }else{
            $login = $logindate." ".$logintime;
        }

        if ($logoutdate == "" || $logouttime == "") {
            $logout = $trackrow['gy_tracker_logout'];
        }else{
            $logout = $logoutdate." ".$logouttime;
        }

        if ($breakindate == "" || $breakintime == "") {
            $breakin = $trackrow['gy_tracker_breakin'];
        }else{
            $breakin = $breakindate." ".$breakintime;
        }

        if ($breakoutdate == "" || $breakouttime == "") {
            $breakout = $trackrow['gy_tracker_breakout'];
        }else{
            $breakout = $breakoutdate." ".$breakouttime;
        }

        if ($overtime == "overtime") {
            $ot = words($_POST['overtime']);
        }else if ($overtime = "all") {
            $schedlogout = $logoutdate." ".$schedrow['gy_sched_logout'];

            if ($schedcount > 0) {
                $ot = get_overtime($schedlogout, $logout);
            }else{
                $ot = rd_overtime($login, $breakout, $breakin, $logout);
            }
        }else{
            $ot = 0;
        }

        //calculate work hours, break hours, over time
        $bh = get_breakhours($breakout, $breakin);

        if ($schedcount > 0) {
            //get workhours
            $schedlogin = $schedrow['gy_sched_day']." ".$schedrow['gy_sched_login'];

            $wh = get_workhours($schedlogin ,$login, $breakout, $breakin, $logout);
        }else{
            $wh = rd_workhours($login, $breakout, $breakin, $logout);
        }

        //insert request
        //$insertdata=$link->query("INSERT INTO `gy_escalate`(`gy_esc_type`, `gy_esc_reason`, `gy_esc_photodir`, `gy_esc_status`, `gy_esc_date`, `gy_esc_by`, `gy_esc_to`, `gy_tracker_id`, `gy_tracker_date`, `gy_tracker_login`, `gy_tracker_breakout`, `gy_tracker_breakin`, `gy_tracker_logout`, `gy_tracker_wh`, `gy_tracker_bh`, `gy_tracker_ot`) Values('$type','$reason','$file','0','$onlydate','$user_id','$mysuper','$redirect','$trackerdate','$login','$breakout','$breakin','$logout','$wh','$bh','$ot')");

        //if ($insertdata) {
            //update tracker status
        //    $updatedata=$link->query("UPDATE `gy_tracker` SET `gy_tracker_request`='escalate', `gy_tracker_om`='$user_id' Where `gy_tracker_id`='$redirect'");

        //    $notetext = "Escalation request for ".$trackrow['gy_emp_fullname']." dated -> ".$trackeronlydate;
        //    $notetype = "insert";
        //    $noteucode = $user_code;
        //    $noteuser = $user_info;
        //    my_notify($notetext, $notetype, $noteucode, $noteuser);
        //    header("location: escalate?cd=$redirect&note=success");
        //}else{
            header("location: escalate?cd=$redirect&note=error");
        //}
    }
?>