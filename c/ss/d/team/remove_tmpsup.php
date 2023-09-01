<?php
include '../../../../config/conn.php';
include '../../../../config/function.php';
include '../session.php';

$tsid = addslashes($_REQUEST['tsid']);
$tscode = addslashes($_REQUEST['tscode']);

$dltts=$link->query("DELETE FROM `gy_temp_sup` Where `temp_sup_id`='$tsid' AND `temp_sup_code`='$tscode'");
if($dltts){ echo "tsremove"; }else{ echo "error"; }

$link->close();
?>