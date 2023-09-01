<?php  
	include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];

    if ($redirect == "") {
        header("location: esc_summary?note=invalid");
    }else{

        if (isset($_POST['reason'])) {

            $reason=words($_POST['reason']);

            $escalate=$link->query("SELECT * From `gy_escalate` Where `gy_esc_id`='$redirect'");
            $escrow=$escalate->fetch_array();
            if($escrow['gy_esc_status']==0){
            $trackid=words($escrow['gy_tracker_id']);
            $esctyp=words($escrow['gy_esc_type']);
            $login=words($escrow['gy_tracker_login']);
            $breakout=words($escrow['gy_tracker_breakout']);
            $breakin=words($escrow['gy_tracker_breakin']);
            $logout=words($escrow['gy_tracker_logout']);
            if($esctyp==5){ $history= "<br> Denied Early Out ".date("h:i a", strtotime($logout)); }
            else if($esctyp==6){ $history = "<br> Denied OT from ".date("h:i a", strtotime($login))." - ".date("h:i a", strtotime($logout)); }
            else if($esctyp>6){ $history = "<br> Denied Logs Update -> Login: ".date("h:i a", strtotime($login))." Breakout: ".date("h:i a", strtotime($breakout))." Breakin: ".date("h:i a", strtotime($breakin))." Logout: ".date("h:i a", strtotime($logout)); } $history=$history." by ".$user_info." at ".$datenow."<br>";
            $statement = "UPDATE `gy_tracker` SET `gy_tracker_request`='', `gy_tracker_history`=CONCAT('$history',`gy_tracker_history`) Where `gy_tracker_id`='$trackid'";

            $updatedata=$link->query($statement);

            if ($updatedata) {
                $escalation=$link->query("UPDATE `gy_escalate` SET `gy_esc_status`='2',`gy_esc_deny`='$reason',`gy_esc_to`='$user_id' Where `gy_esc_id`='$redirect'");
				$trackrec = $link->query("SELECT `gy_tracker_login` From `gy_tracker` Where `gy_tracker_login`='0000-00-00 00:00:00' AND `gy_tracker_id`='$trackid'");
                if(mysqli_num_rows($trackrec) > 0){
					$link->query("DELETE FROM `gy_tracker` WHERE `gy_tracker_id`='$trackid'");
				}//else{
				//	$link->query("UPDATE `gy_tracker` SET `gy_tracker_request`='' Where `gy_tracker_id`='$trackid'");					
				//}
                $notetext = "gy_esc_id -> ".$redirect." Escalate Denied";
                $notetype = "update";
                $noteucode = $user_code;
                $noteuser = $user_info;
                my_notify($notetext, $notetype , $noteucode , $noteuser);
                header("location: esc_summary?note=deny");
            }else{
                header("location: esc_summary?note=error");
            }
          }else{ header("location: esc_summary"); }
        }
    }
    $link->close();
?>