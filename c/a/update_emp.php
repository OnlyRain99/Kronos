<?php  
	include '../../config/conn.php';
    include '../../config/connnk.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];

    //get employee details
    $getemp=$link->query("SELECT `gy_emp_code`,`gy_emp_email`,`gy_emp_fullname`,`gy_emp_account` From `gy_employee` Where `gy_emp_id`='$redirect'");
    $emprow=$getemp->fetch_array();

    $unqcode = words($emprow['gy_emp_code']);

    if (isset($_POST['email'])) {
        $idcode = words($_POST['idcode']);
        $rate_type = words($_POST['rate_type']);
        $workfrom_oh = words($_POST['workfrom_oh']);
        $hire_date = words($_POST['hire_date']);
    	$email = words($_POST['email']);
        $fname = words($_POST['fname']);
        $lname = words($_POST['lname']);
        $mname = words($_POST['mname']);
        $fullname = words($fname." ".$lname);
    	$account = words($_POST['account']);
        $type = words($_POST['type']);
        $function_type = words($_POST['function_type']);
        $mysup = words($_POST['mysup']);
        $mystatus = words($_POST['hidstatus']);
        if($mystatus>1){ $mystatus=1; }

        $accname = get_acc_name($account);

        //update user info
        $updateuser=$link->query("UPDATE `gy_user` SET `gy_user_code`='$idcode',`gy_username`='$email',`gy_full_name`='$fullname',`gy_user_type`='$type',`gy_user_function`='$function_type',`gy_user_status`='$mystatus' Where `gy_user_code`='$unqcode'");

        if (!$updateuser) {
            header("location: edit_employee?cd=$redirect&note=userupdatefail");
        }else{
            //update emp details
            $updatedata=$link->query("UPDATE `gy_employee` SET `gy_emp_rate`='$rate_type',`gy_work_from`='$workfrom_oh',`gy_emp_code`='$idcode',`gy_emp_type`='$type',`gy_emp_email`='$email',`gy_emp_lname`='$lname',`gy_emp_fname`='$fname',`gy_emp_mname`='$mname',`gy_emp_fullname`='$fullname',`gy_acc_id`='$account',`gy_emp_account`='$accname',`gy_emp_supervisor`='$mysup',`gy_emp_hiredate`='$hire_date' Where `gy_emp_id`='$redirect'");
            $dbticket->query("UPDATE `vidaxl_masterlist` SET `mr_emp_name`='$fullname' where `mr_emp_code`='$idcode' ");
            if ($updatedata) {

                $my_a = compare_update($emprow['gy_emp_email'] , $email , "Email");
                $my_b = compare_update($emprow['gy_emp_fullname'] , $fullname , "Name");
                $my_c = compare_update($emprow['gy_emp_account'] , $accname , "Account");
                $my_d = compare_update($emprow['gy_emp_code'] , $idcode , "ID");

                $notetext = "Employee Update -> ".$my_a."".$my_b."".$my_c."".$my_d;
                $notetype = "update";
                $noteucode = $user_code;
                $noteuser = $user_info;
                my_notify($notetext, $notetype , $noteucode , $noteuser);
                header("location: edit_employee?cd=$redirect&note=update");
            }else{
                header("location: edit_employee?cd=$redirect&note=error");
            }
        }		
    }
    $dbticket->close();
    $link->close();
?>