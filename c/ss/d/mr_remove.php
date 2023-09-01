<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';
    if($myaccount == 22){
    include '../../../config/connnk.php';
    
	$empcode = addslashes($_REQUEST['empcode']);
	if($empcode!=""){
	$dbticket->query("DELETE FROM `vidaxl_masterlist` Where `mr_emp_code`='$empcode'");
	$dbticket->query("DELETE FROM `shop_emp` Where `emp_code`='$empcode'");
	}
$dbticket->close(); } $link->close();
?>