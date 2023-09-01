<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';
    if($myaccount == 22){
    include '../../../config/connnk.php';

    $empcode = addslashes($_REQUEST['empcode']);

if(isset($_REQUEST['loc'])){ $val = addslashes($_REQUEST['loc']); $sqlq = "mr_loc"; }
else if(isset($_REQUEST['skill'])){ $val = addslashes($_REQUEST['skill']); $sqlq = "mr_skill"; }
else if(isset($_REQUEST['pbrep'])){ $val = addslashes($_REQUEST['pbrep']); $sqlq = "mr_pbreps"; }
else if(isset($_REQUEST['focusg'])){ $val = addslashes($_REQUEST['focusg']); $sqlq = "mr_focusg"; }
else if(isset($_REQUEST['zendid'])){ $val = addslashes($_REQUEST['zendid']); $sqlq = "mr_zendeskid"; }

    $emploc=$dbticket->query("SELECT `".$sqlq."` From `vidaxl_masterlist` WHERE `mr_emp_code`='$empcode' LIMIT 1");
        if(mysqli_num_rows($emploc)>0){
            $locrow=$emploc->fetch_array();
            $dbticket->query("UPDATE `vidaxl_masterlist` SET `".$sqlq."`='".$val."' Where `mr_emp_code`='".$empcode."'");
        }
    $dbticket->close(); } $link->close();
?>