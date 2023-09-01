<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$tmtoolid = addslashes($_REQUEST['tmtoolid']);
$toolname = addslashes($_REQUEST['toolname']);
if($toolname!=""){
    $tmsql = $link->query("SELECT `team_owner`,`team_switch` FROM `team_toollist` WHERE `team_id`='$tmtoolid' LIMIT 1");
    $tmrow=$tmsql->fetch_array();
    $owner = $tmrow['team_owner'];
    $switch = $tmrow['team_switch'];
    if($owner==$user_code || $switch==1){
        $link->query("UPDATE `team_toollist` SET `team_name`='$toolname' Where `team_id`='$tmtoolid'");
    }
}
$link->close();
?>