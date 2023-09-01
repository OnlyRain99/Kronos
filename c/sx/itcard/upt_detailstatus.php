<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$dtld = addslashes($_REQUEST['dtld']);
$status = 0;
$dtlsql=$link->query("SELECT `toold_status` From `tool_details` WHERE `toold_id`='$dtld'");
$count=$dtlsql->num_rows;
if($count==1){
    $dtlrow=$dtlsql->fetch_array();
    if($dtlrow['toold_status']==1){ $status = 0; }
    else if($dtlrow['toold_status']==0){ $status = 1; }
}
    $link->query("UPDATE `tool_details` SET `toold_status`='$status' Where `toold_id`='$dtld'");

$link->close();
?>