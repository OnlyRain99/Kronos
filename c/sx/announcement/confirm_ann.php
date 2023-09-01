<?php  
	include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

    $redirect = @$_GET['cd'];

    if (isset($_POST['key'])) {

    	$key=encryptIt($_POST['key']);

    	if ($key == $user_lock) {
    		$insertdata=$link->query("INSERT INTO `gy_confirm`(`gy_conf_date`, `gy_conf_by`, `gy_ann_id`) VALUES('$datenow','$user_code','$redirect')");

    		if ($insertdata) {
    			header("location: ../content?cd=$redirect&note=confirm");
    		}else{
    			header("location: ../content?cd=$redirect&note=error");
    		}
    	}else{
    		header("location: ../content?cd=$redirect&note=nope");
    	}
    }
    $link->close();
?>