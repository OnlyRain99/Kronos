<?php  
	include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $idcode = @$_GET['idcode'];

    $check=$link->query("SELECT `gy_emp_id` From `gy_employee` Where `gy_emp_code`='$idcode'");
    $count=$check->num_rows;

    if ($count > 0) {
    	echo "no";
    }else{
    	echo "yes";
    }
?>