<?php  
	include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

 if($user_type == 1 && $user_dept == 2){

    $redirect = @$_GET['escid'];
    $type = @$_GET['type'];

    $file="";
    $filename="filename";
    if ($type==0 || $type==1 || $type==2) {
    	$attachment=$link->query("SELECT `gy_req_photodir` From `gy_schedule_escalate` Where `gy_sched_esc_id`='$redirect'");
	    $attach=$attachment->fetch_array();
		$filename=$attach['gy_req_photodir'];
		$file = '../../../kronos_file_store/'.$attach['gy_req_photodir'];

    }else if ($type==7 || $type==6 || $type==5) {
    	$attachment=$link->query("SELECT `gy_esc_photodir` From `gy_escalate` Where `gy_esc_id`='$redirect'");
	    $attach=$attachment->fetch_array();
		$filename=$attach['gy_esc_photodir'];
		$file = '../../../kronos_file_store/'.$attach['gy_esc_photodir'];
    }

	if(!file_exists($file)){
	    die('file not found');
	} else {
	    header("Cache-Control: public");
	    header("Content-Description: File Transfer");
	    header("Content-Disposition: attachment; filename=$filename");
	    header("Content-Type: application/zip");
	    header("Content-Transfer-Encoding: binary");

	    readfile($file);
	}

}
$link->close();
?>