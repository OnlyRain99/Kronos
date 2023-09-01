<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';
    if($myaccount == 22){
    include '../../../config/connnk.php';

    $empcode = addslashes($_REQUEST['empcode']);
    $empstatus=$dbticket->query("SELECT `mr_status` From `vidaxl_masterlist` WHERE `mr_emp_code`='$empcode' LIMIT 1");
        if(mysqli_num_rows($empstatus)>0){
        $empstatusrow=$empstatus->fetch_array();
        if($empstatusrow['mr_status']==0){
            $dbticket->query("UPDATE `vidaxl_masterlist` SET `mr_status`='1' Where `mr_emp_code`='".$empcode."'");
        }else{
            $dbticket->query("UPDATE `vidaxl_masterlist` SET `mr_status`='0' Where `mr_emp_code`='".$empcode."'");
        }
        }
    }
?>