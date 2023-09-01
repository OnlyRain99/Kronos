<?php  
	include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];
    $get_announce=$link->query("SELECT `gy_ann_serial` From `gy_announce` Where `gy_ann_id`='$redirect'");
    $ann=$get_announce->fetch_array();

    if (isset($_POST['caption'])) {

        $type = words($_POST['type']);
    	$caption = words($_POST['caption']);
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

            $statement = "UPDATE `gy_announce` SET `gy_ann_type`='$type', `gy_ann_caption`='$caption', `gy_ann_end`='$end_date', `gy_ann_attachment`='$file_download_dir' Where `gy_ann_id`='$redirect'";
        }else{
            $statement = "UPDATE `gy_announce` SET `gy_ann_type`='$type', `gy_ann_caption`='$caption', `gy_ann_end`='$end_date' Where `gy_ann_id`='$redirect'";
        }

        $updatedata=$link->query($statement);

        if ($updatedata) {
        	$notetext = "Announcement Update Serial -> ".$ann['gy_ann_serial'];
            $notetype = "update";
            $noteucode = $user_code;
            $noteuser = $user_info;
            my_notify($notetext, $notetype, $noteucode, $noteuser);

        	header("location: index?note=update");
        }else{
        	header("location: index?note=error");
        }
    }
?>