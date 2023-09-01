<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$val = addslashes($_REQUEST['val']);
$tool = addslashes($_REQUEST['tool']);
$col = addslashes($_REQUEST['col']);
$row = addslashes($_REQUEST['row']);

if($tool>0&&$tool!=""){
    $teamarr=array();
    $tmsql = $link->query("SELECT * FROM `team_toollist` WHERE `team_id`='$tool' LIMIT 1");
    $tmrow=$tmsql->fetch_array();
        $teamarr[0] = $tmrow['team_id'];
        $teamarr[1] = $tmrow['team_name'];
        $teamarr[2] = $tmrow['team_owner'];
        $teamarr[3] = $tmrow['team_switch'];

    if($teamarr[2]==$user_code || $teamarr[3]==1){
    $datsql = $link->query("SELECT * FROM `team_data` WHERE `tool_id`=$teamarr[0] AND `col_id`=$col AND `row_id`=$row LIMIT 1");
        if($datsql->num_rows>0){
            $link->query("UPDATE `team_data` SET `data_value`='$val' Where `tool_id`=$teamarr[0] AND `col_id`=$col AND `row_id`=$row ");
        }else{
            $link->query("INSERT INTO `team_data`(`tool_id`,`col_id`,`row_id`, `data_value`)VALUES($teamarr[0], $col, $row, '$val')");
        }
    }
}

$link->close();
?>