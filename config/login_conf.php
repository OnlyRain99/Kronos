<?php  
	session_start();

    if (isset($_SESSION['fus_user_id'])) {
        if($_SESSION['fus_user_type'] == "0"){
            header("location: ../c/a/");
        }else if($_SESSION['fus_user_type'] == "1"){
            header("location: ../c/u/");
        }else if($_SESSION['fus_user_type'] == "2"){
            header("location: ../c/s/");
        }else if($_SESSION['fus_user_type'] == "3"){
            header("location: ../c/ss/");
        }else if($_SESSION['fus_user_type'] == "4"){
            header("location: ../c/sss/");
        }else if($_SESSION['fus_user_type'] == "5"){
            header("location: ../c/v/");
        }else if($_SESSION['fus_user_type'] >= "6" && $_SESSION['fus_user_type'] <= "18"){
            header("location: ../c/sx/");
        }
        exit();
    }

	include 'conn.php';
	include 'function.php';

	if(isset($_POST['username'])){
		$username = words($_POST['username']);
		$password = cryptbsc(words($_POST['password']));

		$identify=$link->query("Select * From `gy_user` Where (`gy_username`='$username' OR `gy_user_code`='$username') AND `gy_password`='$password' AND `gy_user_status`=0");
		$count=$identify->num_rows;
		$row=$identify->fetch_array();

		$ucode=$row['gy_user_code'];

		if($count > 0){
			if($row['gy_user_type'] == "0"){
				$_SESSION['fus_user_id'] = $row['gy_user_id'];
				$_SESSION['fus_user_type'] = $row['gy_user_type'];

				if ($row['gy_user_id'] == 1) {
					header("location: ../c/a/" , true, 301); exit();
				}else{
					$activity_name = words("Login Notification by ".$row['gy_full_name']);
		            $activity_date = words(date("Y-m-d h:i:s"));
		            $insert_activity_log=$link->query("Insert Into `gy_notification`(`gy_notif_type`, `gy_user_code`, `gy_notif_text`, `gy_notif_date`) values('inout','$ucode','$activity_name','$activity_date')");
					header("location: ../c/a/", true, 301); exit();
				}				
			}else if($row['gy_user_type'] == "1"){
				$_SESSION['fus_user_id'] = $row['gy_user_id'];
				$_SESSION['fus_user_type'] = $row['gy_user_type'];
				$activity_name = words("Login Notification by ".$row['gy_full_name']);
	            $activity_date = words(date("Y-m-d h:i:s"));
	            $insert_activity_log=$link->query("Insert Into `gy_notification`(`gy_notif_type`, `gy_user_code`, `gy_notif_text`, `gy_notif_date`) values('inout','$ucode','$activity_name','$activity_date')");
				header("location: ../c/u/");
			}else if($row['gy_user_type'] == "2"){
				$_SESSION['fus_user_id'] = $row['gy_user_id'];
				$_SESSION['fus_user_type'] = $row['gy_user_type'];
				$activity_name = words("Login Notification by ".$row['gy_full_name']);
	            $activity_date = words(date("Y-m-d h:i:s"));
	            $insert_activity_log=$link->query("Insert Into `gy_notification`(`gy_notif_type`, `gy_user_code`, `gy_notif_text`, `gy_notif_date`) values('inout','$ucode','$activity_name','$activity_date')");
				header("location: ../c/s/");
			}else if($row['gy_user_type'] == "3"){
				$_SESSION['fus_user_id'] = $row['gy_user_id'];
				$_SESSION['fus_user_type'] = $row['gy_user_type'];
				$activity_name = words("Login Notification by ".$row['gy_full_name']);
	            $activity_date = words(date("Y-m-d h:i:s"));
	            $insert_activity_log=$link->query("Insert Into `gy_notification`(`gy_notif_type`, `gy_user_code`, `gy_notif_text`, `gy_notif_date`) values('inout','$ucode','$activity_name','$activity_date')");
				header("location: ../c/ss/");
			}else if($row['gy_user_type'] == "4"){
				$_SESSION['fus_user_id'] = $row['gy_user_id'];
				$_SESSION['fus_user_type'] = $row['gy_user_type'];
				$activity_name = words("Login Notification by ".$row['gy_full_name']);
	            $activity_date = words(date("Y-m-d h:i:s"));
	            $insert_activity_log=$link->query("Insert Into `gy_notification`(`gy_notif_type`, `gy_user_code`, `gy_notif_text`, `gy_notif_date`) values('inout','$ucode','$activity_name','$activity_date')");
				header("location: ../c/sss/");
			}else if($row['gy_user_type'] == "5"){
				$_SESSION['fus_user_id'] = $row['gy_user_id'];
				$_SESSION['fus_user_type'] = $row['gy_user_type'];
				$activity_name = words("Login Notification by ".$row['gy_full_name']);
	            $activity_date = words(date("Y-m-d h:i:s"));
	            $insert_activity_log=$link->query("Insert Into `gy_notification`(`gy_notif_type`, `gy_user_code`, `gy_notif_text`, `gy_notif_date`) values('inout','$ucode','$activity_name','$activity_date')");
				header("location: ../c/v/");
			}else if($row['gy_user_type'] >= "6" && $row['gy_user_type'] <="18"){
				$_SESSION['fus_user_id'] = $row['gy_user_id'];
				$_SESSION['fus_user_type'] = $row['gy_user_type'];
				$activity_name = words("Login Notification by ".$row['gy_full_name']);
	            $activity_date = words(date("Y-m-d h:i:s"));
	            $insert_activity_log=$link->query("Insert Into `gy_notification`(`gy_notif_type`, `gy_user_code`, `gy_notif_text`, `gy_notif_date`) values('inout','$ucode','$activity_name','$activity_date')");
				header("location: ../c/sx/");
			}else{
				session_destroy();
				header("location: ../?note=notfound&input=$username"); exit();
			}
		}else{
			session_destroy();
			header("location: ../?note=notfound&input=$username"); exit();
		}
	}
?>