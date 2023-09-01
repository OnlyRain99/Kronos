<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';
    if($myaccount == 22){
    include '../../../config/connnk.php';
?>
<div class="table-responsive">
    <table class="table table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
<thead>
    <tr class="mybg">
        <th scope="col" style="padding: 10px;" class="text-center"><i class="fa fa-trash"></i></th>
        <th scope="col" style="padding: 10px;" class="text-center"><i class='fas fa-toggle-on'></i></th>
        <th scope="col" style="padding: 10px;" class="text-center">Site</th>
        <th scope="col" style="padding: 10px; position: sticky; left: 0; background-color: #fafafa;" class="text-center">Name</th>
        <th scope="col" style="padding: 10px;" class="text-center ">Zendesk ID</th>
        <th scope="col" style="padding: 10px;" class="text-center ">Skill</th>
        <th scope="col" style="padding: 10px;" class="text-center ">P/B Reps</th>
        <th scope="col" style="padding: 10px;" class="text-center">Focus Group</th>
<?php $shoplist=$dbticket->query("SELECT `shop_name` From `shops` WHERE `shop_status`='1' ORDER BY `id` ASC");
      while ($shoprow=$shoplist->fetch_array()){?>
        <th style="padding: 10px;" class="text-center"><?php echo $shoprow['shop_name']; ?></th>
<?php } ?>
    <tr>
</thead>
<tbody>
<?php
    $arid = array("");
    $i = 0;
    $gyemp=$link->query("SELECT `gy_emp_code` From `gy_employee` Where `gy_emp_supervisor`='$user_id' OR `gy_acc_id`=22");
    while ($gyerow=$gyemp->fetch_array()) {
        $arid[$i] = getuserid($gyerow['gy_emp_code']);
        $i++;
    }

    //array fg
    $i = 0; $fgarr = array(array());
    $fglist=$dbticket->query("SELECT * From `focus_group` ORDER BY `id` ASC");
    while($fgrow=$fglist->fetch_array()){
        $fgarr[$i][0] = $fgrow['id'];
        $fgarr[$i][1] = $fgrow['fg_name'];
        $i++;
    }
    //array shops
    $i = 0; $shoparr = array(array());
    $shoplist=$dbticket->query("SELECT `id`,`shop_name` From `shops` WHERE `shop_status`='1' ORDER BY `id` ASC");
    while($shoprow=$shoplist->fetch_array()){
        $shoparr[$i][0] = $shoprow['id'];
        $shoparr[$i][1] = $shoprow['shop_name'];
        $i++;
    }
    //array shop emp
    $i = 0; $shopemparr = array(array());
    $shopemp=$dbticket->query("SELECT * From `shop_emp` ORDER BY `id` ASC");
    while($shopemprow=$shopemp->fetch_array()){
        $shopemparr[$i][0] = $shopemprow['emp_code'];
        $shopemparr[$i][1] = $shopemprow['shop_id'];
        $shopemparr[$i][2] = $shopemprow['shop_check'];
        $i++;
    }
    //array master list users
    $i = 0; $vxlmarr = array(); $vidarr = array(array());
    $vxlmlist=$dbticket->query("SELECT * From `vidaxl_masterlist`"); 
        while($vxlmrow=$vxlmlist->fetch_array()){
            $vxlmarr[$i] = $vxlmrow['mr_emp_code']; 
            $vidarr[$i][0] = $vxlmrow['last_update'];
            $vidarr[$i][1] = $vxlmrow['mr_skill'];
            $vidarr[$i][2] = $vxlmrow['mr_pbreps'];
            $vidarr[$i][3] = $vxlmrow['today_email'];
            $vidarr[$i][4] = $vxlmrow['today_chat'];
            $vidarr[$i][5] = $vxlmrow['today_phone'];
            $vidarr[$i][6] = $vxlmrow['mr_loc'];
            $vidarr[$i][7] = $vxlmrow['mr_zendeskid'];
            $vidarr[$i][8] = $vxlmrow['mr_focusg'];
            $vidarr[$i][9] = $vxlmrow['mr_status'];
            $i++;
    }

    $dbticket->close();

    $vxlemp=$link->query("SELECT `gy_emp_code`,`gy_emp_fullname`,`gy_acc_id` From `gy_employee` Where `gy_emp_supervisor`='$user_id' OR `gy_emp_supervisor` IN (".implode(",",$arid).") ORDER BY `gy_emp_fullname` ASC");
    $i1 = 0;
    while($vxlrow=$vxlemp->fetch_array()) {
        for($i2=0;$i2<count($vxlmarr);$i2++){
            if($vxlmarr[$i2]==$vxlrow['gy_emp_code']){
                $empcode = $vxlmarr[$i2];
                $last_update = $vidarr[$i2][0];
                $mr_skill = $vidarr[$i2][1];
                $mr_pbreps = $vidarr[$i2][2];
                $today_email = $vidarr[$i2][3];
                $today_chat = $vidarr[$i2][4];
                $today_phone = $vidarr[$i2][5];
                $mr_loc = $vidarr[$i2][6];
                $mr_zendeskid = $vidarr[$i2][7];
                $mr_focusg = $vidarr[$i2][8];
                $mr_status = $vidarr[$i2][9];
 ?>
    <tr id="<?php echo "row_".$empcode; ?>">
        <td scope="row" style="padding: 0px;" class="text-center" ><btn class="btn btn-outline-danger btn-block btn-sm" title="Remove" onclick="delconfirm(this)" id="<?php echo "remove_".$empcode; ?>"><i class="fa fa-trash"></i></btn></td>

        <td style="padding: 0px;" class="text-center" ><?php if($mr_status == 1){ ?>
            <btn class="btn btn-outline-success btn-block btn-sm" title="Active" id="statusemp_<?php echo $empcode; ?>" onclick="switchemstat(this)"><i class='fas fa-toggle-on'></i></btn>
        <?php }else{ ?>
            <btn class="btn btn-outline-danger btn-block btn-sm" title="Inactive" id="statusemp_<?php echo $empcode; ?>" onclick="switchemstat(this)"><i class='fas fa-toggle-off'></i></btn>
        <?php } ?>
        <div id="empstatus_<?php echo $empcode; ?>"></div>
        </td>

        <td style="padding: 0px;" class="text-center">
            <select class="form-select form-select-sm minwid-85" id="loc_<?php echo $empcode; ?>" onchange="updatesel(this)"><?php for($i=0;$i<=1;$i++){ ?>
                <option value="<?php echo $i; ?>" <?php if($mr_loc==$i){echo "selected";} ?>> <?php if($i==0){ echo "Davao"; }else if($i==1){echo "Tagum";}  ?> </option>
            <?php } ?></select>
        <div id="selloc_<?php echo $empcode; ?>"></div>
        </td>

        <td style="padding: 0px; position: sticky; left: 0; background-color: #fafafa;" class="text-center"><input class="form-control form-control-sm minwid-120" type="text" value="<?php echo $vxlrow['gy_emp_fullname']; ?>" readonly></td>
        <td style="padding: 0px;" class="text-center"><input class="form-control form-control-sm minwid-120" type="number" id="zendeskid_<?php echo $empcode; ?>" oninput="checkattr(this)" onfocusout="updatesel(this)" value="<?php echo $mr_zendeskid; ?>"><div id="zend_<?php echo $empcode; ?>"></div></td>

        <td style="padding: 0px;" class="text-center">
            <select class="form-select form-select-sm minwid-90" id="skillsel_<?php echo $empcode; ?>" onchange="updatesel(this)">
                <?php $skillarr = array(1,1.1,1.2,2,3); for($i=0;$i<count($skillarr);$i++){ ?>
                <option value="<?php echo $skillarr[$i]; ?>" <?php if($skillarr[$i]==$mr_skill){echo "selected";} ?>><?php echo "Skill ".$skillarr[$i]; ?></option>
            <?php } ?></select>
        <div id="selskill_<?php echo $empcode; ?>"></div>
        </td>

        <td style="padding: 0px;" class="text-center">
            <select class="form-select form-select-sm minwid-90" id="pbrepsel_<?php echo $empcode; ?>" onchange="updatesel(this)"><?php for($i=0;$i<=1;$i++){ ?>
                <option value="<?php echo $i; ?>" <?php if($i==$mr_pbreps){echo "selected";} ?>><?php if($i==0){echo "Primary";}else if($i==1){echo "Bench";} ?></option>
            <?php } ?></select>
        <div id="selpbrep_<?php echo $empcode; ?>"></div>
        </td>
        <td style="padding: 0px;" class="text-center">
            <select class="form-select form-select-sm minwid-120" id="focusgsel_<?php echo $empcode; ?>" onchange="updatesel(this)">
            <?php for($i=0;$i<count($fgarr);$i++){ ?>
                <option value="<?php $fgarr[$i][0]; ?>" <?php if($fgarr[$i][0]==$mr_focusg){echo "selected";} ?>><?php echo $fgarr[$i][1] ?></option>
            <?php } ?>
            </select>
        <div id="selfocusg_<?php echo $empcode; ?>"></div>
        </td>
        <?php
        for($row=0;$row<count($shoparr);$row++){ $check = 0;
            for($col=0;$col<count($shopemparr);$col++){
                if($shopemparr[$col][0]==$empcode && $shopemparr[$col][1]==$shoparr[$row][0]){ $check = 1; ?>
                    <td scope="row" style="padding: 0px;" class="text-center" >
                        <?php if($shopemparr[$col][2]==1){ ?>
                        <button class="btn btn-outline-secondary btn-block btn-sm" title="Selected" onclick="<?php echo "switchshop(this,'".$shopemparr[$col][0]."',".$shoparr[$row][0].")"; ?>" id="<?php echo "swshop_".$i1; ?>" name="switchshopbtn"><i class="fa fa-check-square"></i></button>
                        <?php }else{ ?>
                        <button class="btn btn-outline-secondary btn-block btn-sm" title="Not Selected" onclick="<?php echo "switchshop(this,'".$shopemparr[$col][0]."',".$shoparr[$row][0].")"; ?>" id="<?php echo "swshop_".$i1; ?>" name="switchshopbtn"><i class="fa fa-square"></i></button>
                        <?php } ?>
                    <div id="<?php echo "shopemp_".$i1; ?>"></div>
                    </td>
                <?php $i1++; }
            } if($check==0){ ?>
                    <td scope="row" style="padding: 0px;" class="text-center" >
                        <button class="btn btn-outline-secondary btn-block btn-sm" title="Not Selected" onclick="<?php echo "switchshop(this,'".$empcode."',".$shoparr[$row][0].")"; ?>" id="<?php echo "swshop_".$i1; ?>" name="switchshopbtn"><i class="fa fa-square"></i></button>
                    <div id="<?php echo "shopemp_".$i1; ?>"></div>
                    </td>
            <?php $i1++; } } ?>
    </tr>
    <?php break; }} $i1++; } ?>
</tbody>
    </table>
</div>
<?php } $link->close(); ?>