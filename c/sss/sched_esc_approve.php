<?php  
	include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];

    if ($redirect != "") {
    	
    	$approve=$link->query("SELECT * From `gy_schedule_escalate` Where `gy_sched_esc_id`='$redirect'");
    	$app=$approve->fetch_array();
        if($approve->num_rows>0){
		$empid = getempid($app['gy_emp_code']);
		if($app['gy_req_status']==0){
		$day = words($app['gy_sched_day']);
		$mode = words($app['gy_sched_mode']);
		$login = words($app['gy_sched_login']);
		$breakout = words($app['gy_sched_breakout']);
		$breakin = words($app['gy_sched_breakin']);
		$logout = words($app['gy_sched_logout']);
		$trackcode = words($app['gy_sched_esc_code']);
		$history = "<br> Approved ".get_mode($mode)." from ".date("h:i a", strtotime($login))." - ".date("h:i a", strtotime($logout))." by ".$user_info." at ".$datenow."<br>";
		if($mode==3){ $mode=2; }
        if (check_sched_exist($day, $empid) > 0) {
            $updatedata=$link->query("UPDATE `gy_schedule` SET `gy_sched_mode`='$mode', `gy_sched_login`='$login', `gy_sched_breakout`='$breakout', `gy_sched_breakin`='$breakin', `gy_sched_logout`='$logout', `gy_sched_reg`='$onlydate', `gy_sched_by`='$user_id' Where `gy_sched_day`='$day' AND `gy_emp_id`='$empid'");
        }else{
            $updatedata=$link->query("INSERT INTO `gy_schedule` (`gy_emp_id`, `gy_sched_day`, `gy_sched_mode`, `gy_sched_login`, `gy_sched_breakout`, `gy_sched_breakin`, `gy_sched_logout`, `gy_sched_reg`, `gy_sched_by`) VALUES('$empid', '$day', '$mode', '$login', '$breakout', '$breakin', '$logout', '$onlydate', '$user_id')");
        }

        if ($updatedata) {
            $updatecodes=$link->query("UPDATE `gy_schedule_escalate` SET `gy_req_status`='1',`gy_req_to`='$user_id' Where `gy_sched_esc_id`='$redirect'");
            $trackrec = $link->query("SELECT `gy_tracker_login` From `gy_tracker` Where `gy_tracker_login`='0000-00-00 00:00:00' AND `gy_tracker_code`='$trackcode'");
			if(mysqli_num_rows($trackrec) > 0){
				$link->query("DELETE FROM `gy_tracker` WHERE `gy_tracker_code`='$trackcode'");
			}else{
				$link->query("UPDATE `gy_tracker` SET `gy_tracker_request`='', `gy_tracker_history`=CONCAT('$history',`gy_tracker_history`) Where `gy_tracker_code`='$trackcode'");
            }
            header("location: sched_esc_summary?note=approve");
        }else{
            header("location: sched_esc_summary?note=error");
        }
	  }else{ header("location: sched_esc_summary"); }
      }else{ header("location: sched_esc_summary?note=invalid"); }
    }else{
    	header("location: sched_esc_summary?note=invalid");
    }
$link-close();
?>