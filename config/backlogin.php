<?php  
if(isset($_SERVER['HTTP_REFERER'])){
	session_start();
if($_SERVER['HTTP_REFERER'] == "https://kaizen.sibs-flow.info/"){
	include 'conn.php';
	include 'function.php';

	if(isset($_POST['email'])) {
		$username = $_POST['email'];
		$identify=$link->query("Select * From `gy_user` Where `gy_username`='$username'");
		$count=$identify->num_rows;
		$row=$identify->fetch_array();
		$ucode=$row['gy_user_code'];
		if($count > 0){
			if($row['gy_user_type'] == "1"){
				$_SESSION['fus_user_id'] = $row['gy_user_id'];
				$_SESSION['fus_user_type'] = $row['gy_user_type'];
				$activity_name = words("Login Notification by ".$row['gy_full_name']);
	            $activity_date = words(date("Y-m-d h:i:s"));
	            $insert_activity_log=$link->query("Insert Into `gy_notification`(`gy_notif_type`, `gy_user_code`, `gy_notif_text`, `gy_notif_date`) values('inout','$ucode','$activity_name','$activity_date')");
			}else if($row['gy_user_type'] == "2"){
				$_SESSION['fus_user_id'] = $row['gy_user_id'];
				$_SESSION['fus_user_type'] = $row['gy_user_type'];
				$activity_name = words("Login Notification by ".$row['gy_full_name']);
	            $activity_date = words(date("Y-m-d h:i:s"));
	            $insert_activity_log=$link->query("Insert Into `gy_notification`(`gy_notif_type`, `gy_user_code`, `gy_notif_text`, `gy_notif_date`) values('inout','$ucode','$activity_name','$activity_date')");
			}else if($row['gy_user_type'] == "3"){
				$_SESSION['fus_user_id'] = $row['gy_user_id'];
				$_SESSION['fus_user_type'] = $row['gy_user_type'];
				$activity_name = words("Login Notification by ".$row['gy_full_name']);
	            $activity_date = words(date("Y-m-d h:i:s"));
	            $insert_activity_log=$link->query("Insert Into `gy_notification`(`gy_notif_type`, `gy_user_code`, `gy_notif_text`, `gy_notif_date`) values('inout','$ucode','$activity_name','$activity_date')");
			}else if($row['gy_user_type'] == "4"){
				$_SESSION['fus_user_id'] = $row['gy_user_id'];
				$_SESSION['fus_user_type'] = $row['gy_user_type'];
				$activity_name = words("Login Notification by ".$row['gy_full_name']);
	            $activity_date = words(date("Y-m-d h:i:s"));
	            $insert_activity_log=$link->query("Insert Into `gy_notification`(`gy_notif_type`, `gy_user_code`, `gy_notif_text`, `gy_notif_date`) values('inout','$ucode','$activity_name','$activity_date')");
			}else if($row['gy_user_type'] == "5"){
				$_SESSION['fus_user_id'] = $row['gy_user_id'];
				$_SESSION['fus_user_type'] = $row['gy_user_type'];
				$activity_name = words("Login Notification by ".$row['gy_full_name']);
	            $activity_date = words(date("Y-m-d h:i:s"));
	            $insert_activity_log=$link->query("Insert Into `gy_notification`(`gy_notif_type`, `gy_user_code`, `gy_notif_text`, `gy_notif_date`) values('inout','$ucode','$activity_name','$activity_date')");
			}else if($row['gy_user_type'] >= "6" && $row['gy_user_type'] <="11"){
				$_SESSION['fus_user_id'] = $row['gy_user_id'];
				$_SESSION['fus_user_type'] = $row['gy_user_type'];
				$activity_name = words("Login Notification by ".$row['gy_full_name']);
	            $activity_date = words(date("Y-m-d h:i:s"));
	            $insert_activity_log=$link->query("Insert Into `gy_notification`(`gy_notif_type`, `gy_user_code`, `gy_notif_text`, `gy_notif_date`) values('inout','$ucode','$activity_name','$activity_date')");
			}
		}
	}
}
header("location: https://kaizen.sibs-flow.info/");
}
?>