<?php  
	include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];
    $trackcode = @$_GET['trackcode'];

    if ($redirect == "") {
        header("location: sched_esc_summary?note=invalid");
    }else{
        $approve=$link->query("SELECT * From `gy_schedule_escalate` Where `gy_sched_esc_id`='$redirect'");
        $app=$approve->fetch_array();
        if($app['gy_req_status']==0){
        if (isset($_POST['comment'])) {
            $comment=words($_POST['comment']);
            $statement = "UPDATE `gy_schedule_escalate` SET `gy_req_status`='2',`gy_req_deny`='$comment',`gy_req_to`='$user_id' Where `gy_sched_esc_id`='$redirect'";
            $updatedata=$link->query($statement);
            if ($updatedata) {
				$trackrec = $link->query("SELECT `gy_tracker_login` From `gy_tracker` Where `gy_tracker_login`='0000-00-00 00:00:00' AND `gy_tracker_code`='$trackcode'");
                if(mysqli_num_rows($trackrec) > 0){
					$link->query("DELETE FROM `gy_tracker` WHERE `gy_tracker_code`='$trackcode'");
				}else{
                    $mode = words($app['gy_sched_mode']);
                    $login = words($app['gy_sched_login']);
                    $logout = words($app['gy_sched_logout']);
                    $history = "<br> Denied ".get_mode($mode)." from ".date("h:i a", strtotime($login))." - ".date("h:i a", strtotime($logout))." by ".$user_info." at ".$datenow."<br>";
					$link->query("UPDATE `gy_tracker` SET `gy_tracker_request`='', `gy_tracker_history`=CONCAT('$history',`gy_tracker_history`) Where `gy_tracker_code`='$trackcode'");					
				}

                $notetext = "gy_sched_esc_id -> ".$redirect." Escalate Denied";
                $notetype = "update";
                $noteucode = $user_code;
                $noteuser = $user_info;
                my_notify($notetext, $notetype , $noteucode , $noteuser);
                header("location: sched_esc_summary?note=deny");
            }else{
                header("location: sched_esc_summary?note=error");
            }
        }
      }else{ header("location: sched_esc_summary"); }
    }
    $link->close();
?>