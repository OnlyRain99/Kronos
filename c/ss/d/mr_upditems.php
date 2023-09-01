<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';
    if($myaccount == 22){
    include '../../../config/connnk.php';

if(isset($_REQUEST['shopid'])){
$shopid = addslashes($_REQUEST['shopid']);
$btn = addslashes($_REQUEST['btn']);
$st = 0;
if($shopid != "" && $shopid > 0){
    $shpnmq=$dbticket->query("SELECT * From `shops` WHERE `id`='$shopid' LIMIT 1");
        if(mysqli_num_rows($shpnmq)>0){
    if($btn==0){
    $shopidrow=$shpnmq->fetch_array();
        if($shopidrow['shop_status'] == 1){ $st = 0; }
        else if($shopidrow['shop_status'] == 0){ $st = 1; }
    $dbticket->query("UPDATE `shops` SET `shop_status`='$st' Where `id`='$shopid'");
    }else if($btn==1){
    $dbticket->query("DELETE FROM `shops` Where `id`='$shopid'");
    $dbticket->query("DELETE FROM `shop_emp` Where `shop_id`='$shopid'");
    }
    }
}
}else if(isset($_REQUEST['fgid'])){
$fgid = addslashes($_REQUEST['fgid']);
if($fgid != "" && $fgid > 0){
    $dbticket->query("DELETE FROM `focus_group` Where `id`='$fgid'");
}
}else if(isset($_REQUEST['targetid'])){
$targetid = addslashes($_REQUEST['targetid']);
if($targetid != "" && $targetid > 0){
    $dbticket->query("DELETE FROM `targets` Where `id`='$targetid'");
}
}

$dbticket->close(); } $link->close(); ?>