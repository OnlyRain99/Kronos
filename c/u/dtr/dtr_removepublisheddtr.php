<?php
include '../../../config/conn.php';
include '../../../config/function.php';
include '../session.php';

$pblshid = addslashes($_REQUEST['pblshid']);

$deletepblsdtr=$link->query("DELETE FROM `dtr_publish` Where `dtr_publish_id`=$pblshid");
if($deletepblsdtr){echo"dltsuccess";}else{echo"dlterr";}

$link->close(); ?>