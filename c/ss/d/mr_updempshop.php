<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';
    if($myaccount == 22){
    include '../../../config/connnk.php';

$empcode = addslashes($_REQUEST['empcode']);
$shopid = addslashes($_REQUEST['shopid']);

    $shpnmq=$dbticket->query("SELECT id From `shops` WHERE `id`=$shopid LIMIT 1");
        if(mysqli_num_rows($shpnmq)>0){

$shpemq=$dbticket->query("SELECT * From `shop_emp` WHERE `emp_code`='$empcode' AND `shop_id`=$shopid LIMIT 1");
if(mysqli_num_rows($shpemq)>0){
    $shopemprow=$shpemq->fetch_array();
    if($shopemprow['shop_check']==0){
        $dbticket->query("UPDATE `shop_emp` SET `shop_check`=1 Where `id`=".$shopemprow['id']);
    }else{
        $dbticket->query("UPDATE `shop_emp` SET `shop_check`=0 Where `id`=".$shopemprow['id']);
    }
}else{
        $dbticket->query("INSERT INTO `shop_emp`(`emp_code`,`shop_check`,`shop_id`)Values('$empcode',1,'$shopid')");
}
}

 $dbticket->close(); } $link->close();  ?>