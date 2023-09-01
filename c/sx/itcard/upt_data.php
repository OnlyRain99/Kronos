<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$type = addslashes($_REQUEST['type']);
$dataval = addslashes($_REQUEST['dataval']);
if($type==0){
    $dataid = addslashes($_REQUEST['dataid']);
    $link->query("UPDATE `tool_data` SET `td_value`='$dataval' Where `td_id`='$dataid'");
}else if($type==1){
    $toolid = addslashes($_REQUEST['toolid']);
    $empcod = addslashes($_REQUEST['empcod']);
    $cntql = $link->query("SELECT * From `tool_data` WHERE `td_tooldid`=$toolid AND `td_emp_code`='$empcod'");
    $count=$cntql->num_rows;
    if($count<=0){
    $link->query("INSERT INTO `tool_data`(`td_tooldid`, `td_emp_code`, `td_value`, `td_status`)values('$toolid', '$empcod', '$dataval', 1)");
    }
}

$link->close();
?>