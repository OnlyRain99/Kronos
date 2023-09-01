<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    if (isset($_POST['email'])) {
    	
        $idcode = words($_POST['idcode']);
    	$email = words($_POST['email']);
        $hire_date = words($_POST['hire_date']);
        $rate_type = words($_POST['rate_type']);
        $workfrom_oh = words($_POST['workfrom_oh']);
        $fname = words($_POST['fname']);
        $lname = words($_POST['lname']);
        $mname = words($_POST['mname']);
    	$fullname = words($fname." ".$lname);
    	$account = words($_POST['account']);
        $type = words($_POST['type']);
        $function_type = words($_POST['function_type']);
        $mysup = words($_POST['mysup']);

        $accname = get_acc_name($account);

        //duplicate
        if (checkempcode($idcode) == "no") {
            header("location: add_employee?note=exist");
        }else if(checkemail($email) == "no"){
            header("location: add_employee?note=email_exist");
        }else{
            $rnd_password = my_rand_str(8);
            $unq_password = words(cryptbsc($rnd_password));
            $insertdata=$link->query("INSERT INTO `gy_employee`(`gy_emp_code`,`gy_emp_type`,`gy_emp_rate`,`gy_work_from`,`gy_emp_email`,`gy_emp_lname`,`gy_emp_fname`,`gy_emp_mname`,`gy_emp_fullname`,`gy_acc_id`,`gy_emp_account`,`gy_emp_supervisor`,`gy_emp_hiredate`) Values('$idcode','$type','$rate_type','$workfrom_oh','$email','$lname','$fname','$mname','$fullname','$account','$accname','$mysup','$hire_date')");

            $insertuserdata=$link->query("INSERT INTO `gy_user`(`gy_user_code`, `gy_full_name`, `gy_username`, `gy_password`, `gy_user_type`, `gy_user_function`, `gy_user_status`) VALUES('$idcode','$fullname','$email','$unq_password','$type','$function_type','0')");

            if ($insertdata) {

                $emailto = $email;
                $emailsubject = "SiBS Kronos Account Details";
                $emailmessage = 
                "Information below is your SiBS Time Keeping login details. \r\n <br>
                Username: ".$email." or ".$idcode." \r\n <br>
                Password: ".$rnd_password." \r\n <br>
                URL: https://kronos.mysibs.info \r\n <br>
                Note: This is a system generated email. Do not reply.";
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8"."\r\n";
                $headers .= "From: <kronos@mysibs.info>"."\r\n";
                $headers .= 'Cc: it@thesiblingssolutions.com'."\r\n";
                //$themail = mail($emailto, $emailsubject, $emailmessage, $headers);

                //include 'mailer.php';

                $notetext = $email." is added to Employees List";
                $notetype = "insert";
                $noteucode = $user_code;
                $noteuser = $user_info;
                my_notify($notetext, $notetype , $noteucode , $noteuser);
                //header("location: add_employee?note=added");
                //if($themail){ ?>
    <form id="hidpds" method="POST" action="pds?note=added" >
    <input type="hidden" name="emphidcode" value="<?php echo $idcode; ?>">
    </form>
    <script> document.getElementById("hidpds").submit(); </script>
<?php    //}
            }else{
                header("location: add_employee?note=error");
            }
        }
    } $link->close();
?>