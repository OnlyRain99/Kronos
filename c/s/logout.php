<?php 

	include("../../config/conn.php");
	include("../../config/function.php");
	include("session.php");

	$activity_name = words("Logout by ".$user_info);
    $activity_date = words(date("Y-m-d H:i:s"));
    $insert_activity_log=$link->query("INSERT Into `gy_notification`(`gy_notif_type`, `gy_user_code`, `gy_notif_text`, `gy_notif_date`) values('inout','$user_code','$activity_name','$activity_date')");

	session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Logout</title>
	<meta http-equiv="refresh" content="1;url=../../">
</head>

<style type="text/css">
	.prepare{
		margin-top: 100px;
	}
</style>

<body>
	<center>
		<h3 class="prepare">Preparing to Logout ...</h3>
		<img src="../../images/loading.gif" alt="loader">
	</center>
</body>
</html>