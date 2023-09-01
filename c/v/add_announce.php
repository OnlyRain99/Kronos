<?php  
	include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    if (isset($_POST['caption'])) {

        $type = words($_POST['type']);
    	$caption = words($_POST['caption']);
        $serial = words(my_rand_int(15));
        $end_date = words($_POST['end_date']);
    	$file = strtotime(date("Y-m-d H:i:s"))."_".$_FILES['file']['name'];

        if ($_FILES['file']['name'] != "") {
            $fileTmpLoc = $_FILES["file"]["tmp_name"];
            $fileSize = $_FILES["file"]["size"];
            $file_download_dir = "../../kronos_file_store/".$file;

            if ($fileSize > 5000000) {
                header("location: index?cd=$redirect&note=sizelimit");
            }else{
                move_uploaded_file($fileTmpLoc, $file_download_dir);
            }
        }else{
            $file_download_dir = "";
        }

        $insertdata=$link->query("INSERT INTO `gy_announce`(`gy_ann_serial`, `gy_ann_type`, `gy_ann_date`, `gy_ann_end`, `gy_ann_by`, `gy_ann_caption`, `gy_ann_attachment`) VALUES('$serial','$type','$datenow','$end_date','$user_id','$caption','$file_download_dir')");

        if ($insertdata) {
        	$notetext = "New Announcement posted";
            $notetype = "insert";
            $noteucode = $user_code;
            $noteuser = $user_info;
            my_notify($notetext, $notetype, $noteucode, $noteuser);

        	header("location: index?note=added");
        }else{
        	header("location: index?note=error");
        }
    }
?>