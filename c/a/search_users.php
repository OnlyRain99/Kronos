<option></option>
    <?php
        include '../../config/conn.php';
        $cntrlval = addslashes($_REQUEST['cntrlval']);
        $getsup=$link->query("SELECT `gy_user_id`,`gy_full_name`,`gy_user_type` From `gy_user` Where `gy_user_status`=0 AND `gy_user_id`!='1' AND `gy_user_type`=".$cntrlval);
            while ($suprow=$getsup->fetch_array()) {
                if ($suprow['gy_user_type'] == 0) {
                    $optioncolor = "red";
                }else if ($suprow['gy_user_type'] == 2) {
                    $optioncolor = "green";
                }else if ($suprow['gy_user_type'] == 3) {
                    $optioncolor = "blue";
                }else if ($suprow['gy_user_type'] == 4) {
                    $optioncolor = "#217777";
                }else if ($suprow['gy_user_type'] == 5) {
                    $optioncolor = "#000";
                }else{
                    $optioncolor = "#495057";
                }
            ?>
<option style="color: <?php echo $optioncolor; ?>;" value="<?php echo $suprow['gy_user_id']; ?>"><?php echo $suprow['gy_full_name']; ?></option>
    <?php   } ?>