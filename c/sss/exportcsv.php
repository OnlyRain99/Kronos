<?php  
	include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];
    $filename = "kronos_schedule_".$user_code;

    header("Content-Type: text/csv; charset=utf-8");  
    header("Content-Disposition: attachment; filename=$filename.csv");

    $output = fopen("php://output", "w");  
    fputcsv($output, array('SiBS ID', 'Name', 'Date', 'Status', 'Login', 'Break-Out', 'Break-In', 'Logout'));  
    $query = "SELECT `gy_emp_code`, `gy_emp_fullname`, `gy_sched_day`, `gy_sched_mode`, `gy_sched_login`, `gy_sched_breakout`, `gy_sched_breakin`, `gy_sched_logout` From `gy_request` Where `gy_req_code`='$redirect'";

    $result = mysqli_query($link, $query);  
    while($row = mysqli_fetch_assoc($result))  {  
       fputcsv($output, $row);  
    }  
      
    fclose($output);
?>