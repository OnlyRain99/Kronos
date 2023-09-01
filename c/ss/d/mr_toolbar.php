<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';
    if($myaccount == 22){
    include '../../../config/connnk.php';

    $arid = array("");
    $i = 0;
    $gyemp=$link->query("SELECT `gy_emp_code` From `gy_employee` Where `gy_emp_supervisor`='$user_id' OR `gy_acc_id`=22 ");
    while ($gyerow=$gyemp->fetch_array()) {
        $arid[$i] = getuserid($gyerow['gy_emp_code']);
        $i++;
    }
?>

    <div class="form-inline">
        <div class="form-group mb-2">
        <select name='emp_code' id='emp_code' class="form-select" aria-label="Agent Name">
        <?php
            //array master list users
            $i = 0; $vxlmarr = array();
            $vxlmlist=$dbticket->query("SELECT * From `vidaxl_masterlist`"); 
            while($vxlmrow=$vxlmlist->fetch_array()){
                $vxlmarr[$i] = $vxlmrow['mr_emp_code'];
                $i++;
            }
        
        $vxlemp=$link->query("SELECT `gy_emp_code`,`gy_emp_fullname`,`gy_acc_id` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user`.`gy_user_status`=0 AND `gy_acc_id`=22 AND (`gy_emp_supervisor`='$user_id' OR `gy_emp_supervisor` IN (".implode(',',$arid).")) ORDER BY `gy_emp_fullname` ASC");
            $disbtn = "";
            while ($vxlrow=$vxlemp->fetch_array()) {
            if(!in_array($vxlrow['gy_emp_code'], $vxlmarr)){ ?>
                <option value="<?php echo $vxlrow['gy_emp_code']; ?>"><?php echo $vxlrow['gy_emp_fullname']; ?></option>
                <?php }
            }
            if(mysqli_num_rows($vxlemp) <= 0){ $disbtn = "disabled"; } ?>
        </select>
        </div>

        <div class="form-group mb-2">
            <input name="zendeskid" id="zendeskid" type="number" class="form-control" placeholder="Zendesk ID" maxlength="19" aria-label="Agent Zendesk ID" oninput="checkattr(this)">
        </div>

        <div class="form-group mb-2">
            <select name="site" id="site" class="form-select" aria-label="Site/Location">
                <option value="0">Davao</option>
                <option value="1">Tagum</option>
            </select>
        </div>

        <div class="form-group mb-2">
            <select name="skill" id="skill" class="form-select" aria-label="Skill">
                <option value="1">Skill 1</option>
                <option value="1.1">Skill 1.1</option>
                <option value="1.2">Skill 1.2</option>
                <option value="2">Skill 2</option>
                <option value="3">Skill 3</option>
            </select>
        </div>

        <div class="form-group mb-2">
            <select name="pbreps" id="pbreps" class="form-select" aria-label="Primary/Bench Reps">
                <option value="0">Primary</option>
                <option value="1">Bench</option>
            </select>
        </div>

        <div class="form-group mb-2">
            <select name="focusg" id="focusg" class="form-select" aria-label="Focus Group">
            <?php $nkfocusg=$dbticket->query("SELECT * From `focus_group` ORDER BY `id` ASC");
                while ($nkfgrow=$nkfocusg->fetch_array()) {?>
                <option value="<?php echo $nkfgrow['id']; ?>"><?php echo $nkfgrow['fg_name']; ?></option>
            <?php } ?>
            </select>
        </div>

        <div class="form-group mx-sm-2 mb-2">
            <button onclick="updatelist()" id="updatemslist" name="updatemslist" class="btn btn-outline-secondary" <?php echo $disbtn; ?>>Upload</button>
        </div>

        <div class="form-group mx-sm-2 mb-2">
            <btn class="btn btn-outline-secondary" onclick="managetarget()">Manage Targets</btn>
        </div>

        <div class="form-group mx-sm-2 mb-2">
            <btn class="btn btn-outline-secondary" onclick="managefg()">Manage Focus Group</btn>
        </div>

        <div class="form-group mx-sm-2 mb-2">
            <btn class="btn btn-outline-secondary" onclick="manageshop()">Manage Shops</btn>
        </div>

        <div class="form-group mx-sm-2 mb-2">
            <btn class="btn btn-outline-secondary" onclick="managereports()">Reports</btn>
        </div>

        <div class="form-group mx-sm-3 mb-2">
            <a class="btn btn-outline-secondary" href="https://kronos.mysibs.info/RT_Ticket_Tracker/" target="_blank">Show RT Ticket Tracker</a>
        </div>
    </div>

<?php $dbticket->close(); } $link->close(); ?>