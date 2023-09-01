<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

    $name = "";
    if($user_type == 1 && $user_dept == 2){
        $sibsid = addslashes($_REQUEST['sibsid']);
        $statement=$link->query("SELECT `gy_full_name` From `gy_user` Where `gy_user_code`='$sibsid'");
        $res=$statement->fetch_array();
        $name = $res['gy_full_name'];
    }
echo $name;
$link->close();
?>