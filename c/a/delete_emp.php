<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];
    $search_text = @$_GET['search_text'];
    $mode = @$_GET['mode'];

    if ($mode == "normal") {
        $my_directory = "stats?";
    }else{
        $my_directory = "search_stats?search_text=$search_text&";
    }
    
    //get employee details
    $getemp=$link->query("SELECT `gy_emp_code`,`gy_emp_email`,`gy_emp_fullname`,`gy_emp_account` From `gy_employee` Where `gy_emp_id`='$redirect'");
    $emprow=$getemp->fetch_array();
    $empcode=words($emprow['gy_emp_code']);

    if ($redirect == "") {
        header("location: ".$my_directory."note=error");
    }else{
        $deletedata=$link->query("DELETE FROM `gy_employee` Where `gy_emp_id`='$redirect'");
        $deletetracks=$link->query("DELETE FROM `gy_tracker` Where `gy_emp_code`='$empcode'");
        $deletelog=$link->query("DELETE FROM `gy_logs` Where `gy_emp_id`='$redirect'");
        $deleteuser=$link->query("DELETE FROM `gy_user` Where `gy_user_code`='$empcode'");

        if ($deletedata && deletetracks && $deletelog && $deleteuser) {
            $notetext = $emprow['gy_emp_fullname']." has been removed from the Employee List";
            $notetype = "delete";
            $noteucode = $user_code;
            $noteuser = $user_info;
            my_notify($notetext, $notetype , $noteucode , $noteuser);
            header("location: ".$my_directory."note=delete");
        }else{
            header("location: ".$my_directory."note=error");
        }
    }

	
?>