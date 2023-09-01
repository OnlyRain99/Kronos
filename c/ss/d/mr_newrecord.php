<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';
    if($myaccount == 22){
    include '../../../config/connnk.php';

$empcode = addslashes($_REQUEST['empcode']);
$zendeskid = addslashes($_REQUEST['zendeskid']);
$loc = addslashes($_REQUEST['site']);
$skill = addslashes($_REQUEST['skill']);
$pbrep = addslashes($_REQUEST['pbreps']);
$focusg = addslashes($_REQUEST['focusg']);

if($empcode != "" || $empcode > 0){

        if($zendeskid == "" || $zendeskid < 0){ $zendeskid = 0; }

        $nklist=$dbticket->query("SELECT `id` From `vidaxl_masterlist` WHERE `mr_emp_code`='$empcode' LIMIT 1");
        if(mysqli_num_rows($nklist)<=0){
            $namesql = $link->query("SELECT `gy_emp_fullname` From `gy_employee` WHERE `gy_emp_code`='$empcode'");
            $namerow=$namesql->fetch_array();
			$name = $namerow['gy_emp_fullname'];
            $dbticket->query("INSERT INTO `vidaxl_masterlist`(`mr_loc`,`mr_emp_code`,`mr_status`,`mr_zendeskid`,`mr_skill`,`mr_pbreps`,`mr_focusg`,`mr_emp_name`)Values('$loc','$empcode',1,'$zendeskid','$skill','$pbrep','$focusg','$name')");
        }
}

 $dbticket->close(); } $link->close(); ?>