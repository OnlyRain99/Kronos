<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    if (isset($_POST['leave_type'])) {
    	
        $leave_type = words($_POST['leave_type']);
        $leave_date_from = words($_POST['leave_date_from']);
        $leave_date_to = words($_POST['leave_date_to']);
        $leave_reason = words($_POST['leave_reason']);

        $file = strtotime(date("Y-m-d H:i:s"))."_".$_FILES['file']['name'];

        if ($_FILES['file']['name'] != "") {
            $fileTmpLoc = $_FILES["file"]["tmp_name"];
            $fileSize = $_FILES["file"]["size"];
            $file_download_dir = "../../../kronos_file_store/".$file;

            if ($fileSize > 5000000) {
                header("location: escalate_sched?cd=$redirect&note=sizelimit");
            }else{
                move_uploaded_file($fileTmpLoc, $file_download_dir);
            }
        }else{
            $file_download_dir = "";
        }

        $insertdata=$link->query("INSERT INTO `gy_leave`(`gy_user_id`, `gy_leave_filed`, `gy_leave_type`, `gy_leave_paid`, `gy_leave_date_from`, `gy_leave_date_to`, `gy_leave_reason`, `gy_leave_status`, `gy_leave_approver`, `gy_leave_attachment`) Values('$user_id','$datenow','$leave_type','0','$leave_date_from','$leave_date_to','$leave_reason','0','0','$file')");

        if ($insertdata) {
            $notetext = $user_info." filed a leave";
            $notetype = "insert";
            $noteucode = $user_code;
            $noteuser = $user_info;
            my_notify($notetext, $notetype , $noteucode , $noteuser);
            header("location: leave?note=filed");
        }else{
            header("location: leave?note=error");
        }
    }
?>