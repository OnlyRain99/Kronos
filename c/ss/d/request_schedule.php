<?php  
	include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    if (isset($_POST['reqcode'])) {

        $reqcode = words($_POST['reqcode']);

        //check data
        $check=$link->query("SELECT `gy_req_id` From `gy_request` Where `gy_req_code`='$reqcode' AND `gy_req_status`='0'");
        $count=$check->num_rows;

        if ($count > 0) {
            //update request
            $updatadata=$link->query("UPDATE `gy_request` SET `gy_req_status`='1' Where `gy_req_code`='$reqcode'");

            if ($updatadata) {
                $notetext = "Schedule Request Sent";
                $notetype = "update";
                $noteucode = $user_code;
                $noteuser = $user_info;
                my_notify($notetext, $notetype , $noteucode , $noteuser);
                header("location: create_schedule?note=send");
            }else{
                header("location: create_schedule?note=error");
            }
        }else{
            header("location: create_schedule?note=norequest");
        }
    }
?>