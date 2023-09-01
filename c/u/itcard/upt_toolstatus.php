<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$toolid = addslashes($_REQUEST['toolid']);

$detsql = $link->query("SELECT `tool_status` From `tool_list` WHERE `tool_id`=$toolid");
$detrow = $detsql->fetch_array();
$sortid = $detrow['tool_status'];
if($sortid==0){ $newsort = 1; }
else if($sortid==1){ $newsort = 0; }

$link->query("UPDATE `tool_list` SET `tool_status`='$newsort' Where `tool_id`=$toolid ");

$link->close();
?>