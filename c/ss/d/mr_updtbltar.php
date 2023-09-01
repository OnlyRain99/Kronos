<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';
    if($myaccount == 22){
    include '../../../config/connnk.php';

    $sqlq = "";
    if(isset($_REQUEST['skill'])){ $val = addslashes($_REQUEST['skill']); $sqlq = "skill"; }
    else if(isset($_REQUEST['operator'])){ $val = addslashes($_REQUEST['operator']); $sqlq = "operator"; }
    else if(isset($_REQUEST['month1'])){ $val = addslashes($_REQUEST['month1']); $sqlq = "month_first"; }
    else if(isset($_REQUEST['month2'])){ $val = addslashes($_REQUEST['month2']); $sqlq = "month_last"; }
    else if(isset($_REQUEST['target'])){ $val = addslashes($_REQUEST['target']); $sqlq = "hourly_target"; }

    if(isset($_REQUEST['targetid'])&&$sqlq!=""){
    $targetid = addslashes($_REQUEST['targetid']);
    $tarsql=$dbticket->query("SELECT `".$sqlq."` From `targets` WHERE `id`='$targetid' LIMIT 1");
    if(mysqli_num_rows($tarsql)>0){
        $dbticket->query("UPDATE `targets` SET `".$sqlq."`='".$val."' Where `id`='".$targetid."'");
    }
    }
    $dbticket->close(); } $link->close();
?>