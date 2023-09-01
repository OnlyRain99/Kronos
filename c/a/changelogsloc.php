<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $trkid = addslashes($_REQUEST['trkid']);
    $nwloc = addslashes($_REQUEST['nwloc']);

    $locsql=$link->query("UPDATE `gy_tracker` SET `gy_tracker_loc`=$nwloc Where `gy_tracker_id`=$trkid");
    if($locsql){ if($nwloc==0){echo"T";}else if($nwloc==1){echo"D";} }

$link->close();
?>