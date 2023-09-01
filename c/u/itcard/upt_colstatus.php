<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$colid = addslashes($_REQUEST['colid']);

if($colid!=""){
    $tmsql = $link->query("SELECT `team_toollist`.`team_owner` AS `towner`,`team_toollist`.`team_switch` AS `tswitch`,`team_collist`.`col_status` AS `colstatus` FROM `team_collist` LEFT JOIN `team_toollist` ON `team_collist`.`team_id`=`team_toollist`.`team_id` WHERE `team_collist`.`col_id`='$colid' LIMIT 1");
    $tmrow=$tmsql->fetch_array();
    $owner = $tmrow['towner'];
    $switch = $tmrow['tswitch'];
    $colstatus = $tmrow['colstatus'];

    if($owner==$user_code || $switch==1){
        if($colstatus==1){ $colstatus=0; }else if($colstatus==0){ $colstatus=1; }
        $link->query("UPDATE `team_collist` SET `col_status`=$colstatus Where `col_id`='$colid'");
    }
    echo $colstatus;
}
$link->close();
?>