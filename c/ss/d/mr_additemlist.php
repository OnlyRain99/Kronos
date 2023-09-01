<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';
    if($myaccount == 22){
    include '../../../config/connnk.php';

if(isset($_REQUEST['shopname'])){
    $itemval = addslashes($_REQUEST['shopname']);
    if($itemval != ""){
    $itemq=$dbticket->query("SELECT `id` From `shops` WHERE `shop_name`='$itemval' LIMIT 1");
        if(mysqli_num_rows($itemq)<=0){
            $dbticket->query("INSERT INTO `shops`(`shop_name`,`shop_status`)Values('$itemval',1)");
        }
    }
}else if(isset($_REQUEST['fgname'])){
    $itemval = addslashes($_REQUEST['fgname']); 
    if($itemval != ""){
    $itemq=$dbticket->query("SELECT `id` From `focus_group` WHERE `fg_name`='$itemval' LIMIT 1");
        if(mysqli_num_rows($itemq)<=0){
            $dbticket->query("INSERT INTO `focus_group`(`fg_name`)Values('$itemval')");
        }
    }
}else if(isset($_REQUEST['target'])){
    $skill = addslashes($_REQUEST['skill']);
    $ope = addslashes($_REQUEST['ope']);
    $fmonth = addslashes($_REQUEST['frommonth']);
    $tmonth = addslashes($_REQUEST['tomonth']);
    $target = addslashes($_REQUEST['target']);
    if($skill!=""&&$ope!=""&$fmonth!=""&&$fmonth>=0&&$target!=""&&$target>=0){
        $dbticket->query("INSERT INTO `targets`(`skill`,`operator`,`month_first`,`month_last`,`hourly_target`)Values('$skill','$ope','$fmonth','$tmonth','$target')");
    }
}

$dbticket->close(); } $link->close(); ?>