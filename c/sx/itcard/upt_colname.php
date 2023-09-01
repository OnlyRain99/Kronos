<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$colid = addslashes($_REQUEST['colid']);
$colname = addslashes($_REQUEST['colname']);
$coltype = addslashes($_REQUEST['coltype']);
if($colname!=""&&$coltype!=""){
    $tmsql = $link->query("SELECT `team_toollist`.`team_owner` AS `towner`,`team_toollist`.`team_switch` AS `tswitch`, `team_collist`.`col_id` AS `colid` FROM `team_collist` LEFT JOIN `team_toollist` ON `team_collist`.`team_id`=`team_toollist`.`team_id` WHERE `team_collist`.`col_id`='$colid' LIMIT 1");
    $tmrow=$tmsql->fetch_array();
    $owner = $tmrow['towner'];
    $switch = $tmrow['tswitch'];
    $colid = $tmrow['colid'];

    if($owner==$user_code || $switch==1){
        $link->query("UPDATE `team_collist` SET `col_val`='$colname',`col_type`='$coltype' Where `col_id`='$colid'");
    }
}
$link->close();
?>