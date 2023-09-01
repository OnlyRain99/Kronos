<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$toolid = addslashes($_REQUEST['toolid']);
$toolname = addslashes($_REQUEST['toolname']);

if($toolid!=""){
    $link->query("UPDATE `tool_list` SET `tool_name`='$toolname' Where `tool_id`='$toolid'");
}

$link->close();
?>