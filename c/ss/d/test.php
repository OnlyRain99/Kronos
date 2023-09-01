<?php  
	include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';
    
    $date = "2021-05-01";

    echo date("Y-m-d", strtotime($date. "+14 days"));
    	
?>