<?php
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';
    include '../../config/connnk.php';

	$ticketid = addslashes($_REQUEST['ticketid']);
	$channel = addslashes($_REQUEST['channel']);
	date_default_timezone_set('Asia/Taipei');
	$datets = date("Y-m-d H:i:s");
	if($ticketid != "" && $channel != "" && $user_code != "" && strlen($ticketid) < 20){
	$dbticket->query("INSERT INTO `ticket`(`emp_code`,`ticket_id`,`channel`,`ticket_date`)values('$user_code','$ticketid','$channel','$datets')");
	}
	$dbticket->close();
	$link->close();
?>