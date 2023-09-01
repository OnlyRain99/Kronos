<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$dtld = addslashes($_REQUEST['dtld']);
$inpval = addslashes($_REQUEST['inpval']);
$selval = addslashes($_REQUEST['selval']);

if($inpval!=""){
    $link->query("UPDATE `tool_details` SET `toold_label`='$inpval',`toold_type`='$selval' Where `toold_id`='$dtld'");
}

$link->close();
?>