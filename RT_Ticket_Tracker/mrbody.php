<?php
    include '../config/conn.php';
    include '../config/connnk.php';
    
    $maxupd=$dbticket->query("SELECT MAX(last_update) AS ticktedate From `vidaxl_masterlist` LIMIT 1");
    $maxrow=$maxupd->fetch_array();
?>
<input type="hidden" id="hidmaxrow" value="<?php echo $maxrow['ticktedate']; ?>">
        <thead>
            <tr>
                <th scope="col" style="padding: 10px;" class="text-center">#</th>
                <th scope="col" style="padding: 10px;" class="text-center">LOC</th>
                <th scope="col" style="padding: 10px;" class="text-center">Email Address</th>
                <th scope="col" style="padding: 10px; position: sticky; left: 0; background-color: #fafafa;" class="text-center">Name</th>
                <th scope="col" style="padding: 10px;" class="text-center minwid-90">Zendesk ID</th>
                <th scope="col" style="padding: 10px;" class="text-center">Supervisor</th>
                <th scope="col" style="padding: 10px;" class="text-center">Skill</th>
                <th scope="col" style="padding: 10px;" class="text-center">Shift</th>
                <th scope="col" style="padding: 10px;" class="text-center">RD1</th>
                <th scope="col" style="padding: 10px;" class="text-center">RD2</th>
                <th scope="col" style="padding: 10px;" class="text-center">Primary/Bench Reps</th>
                <th scope="col" style="padding: 10px;" class="text-center">Focus Group</th>
                <th scope="col" style="padding: 10px;" class="text-center">Attendance</th>
                <th scope="col" style="padding: 10px;" class="text-center">Offline Task</th>
                <th scope="col" style="padding: 10px;" class="text-center">Months</th>
                <th scope="col" style="padding: 10px;" class="text-center">Daily Target</th>
                <th scope="col" style="padding: 10px;" class="text-center">Hourly Target</th>
                <th scope="col" style="padding: 10px;" class="text-center">Email</th>
                <th scope="col" style="padding: 10px;" class="text-center">Phone</th>
                <th scope="col" style="padding: 10px;" class="text-center">Chat</th>
                <th scope="col" style="padding: 10px;" class="text-center">Total</th>
        <?php $shoplist=$dbticket->query("SELECT `shop_name` From `shops` WHERE `shop_status`='1' ORDER BY `id` ASC");
            while ($shoprow=$shoplist->fetch_array()){?>
                <th scope="col" style="padding: 10px;" class="text-center"><?php echo $shoprow['shop_name']; ?></th>
        <?php } ?>
            </tr>
        </thead>
        <tbody>
        <?php
        $loc = addslashes($_REQUEST['loc']);
        $sup = addslashes($_REQUEST['sup']);
        $skl = addslashes($_REQUEST['skl']);
        $pbr = addslashes($_REQUEST['pbr']);
        $ffg = addslashes($_REQUEST['ffg']);
        $shp = addslashes($_REQUEST['shp']);
        $sqlvxlm = "";
        if($loc!=""){ $sqlvxlm.=" AND `mr_loc`=".$loc; }
        if($skl!=""){ $sqlvxlm.=" AND `mr_skill`=".$skl; }
        if($pbr!=""){ $sqlvxlm.=" AND `mr_pbreps`=".$pbr; }
        if($ffg!=""){ $sqlvxlm.=" AND `mr_focusg`=".$ffg; }
        //sort1 master list users
        $i = 0; $vxlmarr = array(); $vidarr = array(array());
        $vxlmlist=$dbticket->query("SELECT * From `vidaxl_masterlist` WHERE `mr_status`='1'".$sqlvxlm); 
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
                $i++;
        }
        //sort2 shop emp
        $i = 0; $shopemparr = array(array());
        $shopemp=$dbticket->query("SELECT * From `shop_emp` ORDER BY `id` ASC");
        while($shopemprow=$shopemp->fetch_array()){
            $shopemparr[$i][0] = $shopemprow['emp_code'];
            $shopemparr[$i][1] = $shopemprow['shop_id'];
            $shopemparr[$i][2] = $shopemprow['shop_check'];
            $i++;
        }
        //sort3 shops
        $i = 0; $shoparr = array(array());
        $shoplist=$dbticket->query("SELECT `id`,`shop_name` From `shops` WHERE `shop_status`='1' ORDER BY `id` ASC");
        while($shoprow=$shoplist->fetch_array()){
            $shoparr[$i][0] = $shoprow['id'];
            $shoparr[$i][1] = $shoprow['shop_name'];
            $i++;
        }
        //sort4 fg
        $i = 0; $fgarr = array(array());
        $fglist=$dbticket->query("SELECT * From `focus_group` ORDER BY `id` ASC");
        while($fgrow=$fglist->fetch_array()){
            $fgarr[$i][0] = $fgrow['id'];
            $fgarr[$i][1] = $fgrow['fg_name'];
            $i++;
        }
        //sort5 target
        $i = 0; $targetarr = array(array());
        $trgtmr=$dbticket->query("SELECT * From `targets` ORDER BY `id` ASC");
        while($trgrow=$trgtmr->fetch_array()){
            $targetarr[$i][0] = $trgrow['skill'];
            $targetarr[$i][1] = $trgrow['operator'];
            $targetarr[$i][2] = $trgrow['month_first'];
            $targetarr[$i][3] = $trgrow['month_last'];
            $targetarr[$i][4] = $trgrow['hourly_target'];
            $i++;
        }
        //sort6 ticket today
        if(date("H")>=8){ $tdatenow = date("Y-m-d 08:00:00"); }else{ $tdatenow = date("Y-m-d 08:00:00", strtotime("-1 day")); }
        $i=0; $tkttdyarr = array();
        $tkttdylst=$dbticket->query("SELECT `emp_code`,`channel` From `ticket` WHERE `ticket_date`>='".$tdatenow."' and `ticket_date`<='".date("Y-m-d H:i:s")."' AND `emp_code` IN ('".implode("','",$vxlmarr)."')"); 
        while($tkttdyrow=$tkttdylst->fetch_array()){
            $tkttdyarr[$i] = $tkttdyrow['emp_code'];
            $i++;
        }
        $tkttdycnt = array_count_values($tkttdyarr);
        //sort7 offline task
        $i=0; $offidarr = array(); $offtarr = array(array());
        $offtsql=$dbticket->query("SELECT * From `offline_task` Where `end_time`='0000-00-00 00:00:00' AND `emp_code` IN ('".implode("','",$vxlmarr)."') ORDER BY `id` ASC");
        while($offtrow=$offtsql->fetch_array()){
            $offidarr[$i] = $offtrow['emp_code'];
            $offtarr[$i][0] = $offtrow['start_time'];
            $offtarr[$i][1] = $offtrow['task'];
            $i++;
        }

    $dbticket->close();

        //sort8 TL
        $i = 0; $tlinx = array(); $tlarr = array(array());
        $tllist=$link->query("SELECT `gy_emp_fullname`,`gy_emp_code` From `gy_employee` Where `gy_acc_id`=22 and `gy_emp_type`=2 ORDER BY `gy_emp_fullname` ASC");
        while($tlrow=$tllist->fetch_array()){
            $tlinx[$i] = tl_id($tlrow['gy_emp_code']);
            $tlarr[$i][0] = 0;
            $tlarr[$i][1] = 0;
            $tlarr[$i][2] = $tlrow['gy_emp_fullname'];
            $i++; 
        }

        //init value
        $i = 1;
        $schpri = 0;
        $schben = 0;
        $prespri = 0;
        $presben = 0;
        $targetpri = 0;
        $targetben = 0;
        $runpri = 0;
        $runben = 0;
        $sqlvxlsup="";
        if($sup!=""){ $sqlvxlsup=" AND `gy_emp_supervisor`=".$sup; }
        $emplist=$link->query("SELECT `gy_emp_id`,`gy_emp_code`,`gy_emp_fullname`,`gy_emp_email`,`gy_emp_supervisor`,`gy_emp_hiredate` From `gy_employee` Where `gy_emp_code` IN ('".implode("','",$vxlmarr)."') ".$sqlvxlsup." ORDER BY `gy_emp_supervisor` ASC");
        while($emprow=$emplist->fetch_array()){
            
                    $last_update = "";
                    $mr_skill = 0;
                    $mr_pbreps = 0;
                    $today_email = 0;
                    $today_chat = 0;
                    $today_phone = 0;
                    $mr_loc = 0;
                    $mr_zendeskid = "";
                    $mr_focusg = 0;
            for($i1=0;$i1<count($vxlmarr);$i1++){
                if($vxlmarr[$i1]==$emprow['gy_emp_code']){
                    $last_update = $vidarr[$i1][0];
                    $mr_skill = $vidarr[$i1][1];
                    $mr_pbreps = $vidarr[$i1][2];
                    $today_email = $vidarr[$i1][3];
                    $today_chat = $vidarr[$i1][4];
                    $today_phone = $vidarr[$i1][5];
                    $mr_loc = $vidarr[$i1][6];
                    $mr_zendeskid = $vidarr[$i1][7];
                    $mr_focusg = $vidarr[$i1][8];
                    break;
                }
            }
            $offln = "";
            $arscdt = array("","", 0, 0, ""); $arscdt = getschedtoday($emprow['gy_emp_id'], $last_update);
            $arrrd = array("", ""); $arrrd = getrddate($emprow['gy_emp_id'], date('Y-m-d',strtotime('last sunday')), date('Y-m-d',strtotime('saturday')));
            $tenurem = tenuremonth($emprow['gy_emp_hiredate']);
            $dhtarget = array(0,0);
            $dhtarget = dhtarget($tenurem, $mr_skill, $targetarr);
            if($mr_pbreps==0){
                //schidule primary
                $schpri += ($arscdt[2] + $arscdt[3]);
                $prespri += $arscdt[2];
                if(strtotime($arscdt[4])>=strtotime($tdatenow) && strtotime($arscdt[4])<=strtotime(date("Y-m-d H:i:s"))){
                    $targetpri += $dhtarget[0];                    
                }
                if(isset($tkttdycnt[$emprow['gy_emp_code']])){
                    $runpri += $tkttdycnt[$emprow['gy_emp_code']];
                }
            }else if($mr_pbreps==1){
                //schedule bench
                $schben += ($arscdt[2] + $arscdt[3]);
                $presben += $arscdt[2];
                if(strtotime($arscdt[4])>=strtotime($tdatenow) && strtotime($arscdt[4])<=strtotime(date("Y-m-d H:i:s"))){
                    $targetben += $dhtarget[0];
                }
                if(isset($tkttdycnt[$emprow['gy_emp_code']])){
                    $runben += $tkttdycnt[$emprow['gy_emp_code']];
                }
            }

            for($i1=0;$i1<count($tlinx);$i1++){
                if($tlinx[$i1]==$emprow['gy_emp_supervisor']){
                    if(isset($tkttdycnt[$emprow['gy_emp_code']])){
                        $tlarr[$i1][1] += $tkttdycnt[$emprow['gy_emp_code']];
                    }
                    if(strtotime($arscdt[4])>=strtotime($tdatenow) && strtotime($arscdt[4])<=strtotime(date("Y-m-d H:i:s"))){
                        $tlarr[$i1][0] += $dhtarget[0];                    
                    }
                    break;
                }
            }

            for($i1=0;$i1<count($offidarr);$i1++){
                if($offidarr[$i1]==$emprow['gy_emp_code']){
                    $offln = $offtarr[$i1][1]." (".getmindif($offtarr[$i1][0], date("Y-m-d H:i:s"),"in").")";
                    break;
                }
            }

$check = 0;
    for($col=0;$col<count($shopemparr);$col++){
        if($shopemparr[$col][0]==$emprow['gy_emp_code'] && $shopemparr[$col][1]==$shp && $shopemparr[$col][2]==1){
            $check = 1; break; }}
if(($loc==$mr_loc || $loc=="") && ($sup=="" || $sup==$emprow['gy_emp_supervisor']) && ($skl=="" || $skl==$mr_skill) && ($pbr=="" || $pbr==$mr_pbreps) && ($ffg=="" || $ffg==$mr_focusg) && ($shp=="" || $check == 1)){
            ?>
            <tr class="<?php if($offln!=""){ echo "bg-warning"; } ?>">
                <th scope="row" style="padding: 5px;" class="text-center"><?php echo $i; ?></th>
                <td style="padding: 5px;" class="text-center"><?php echo mr_loc($mr_loc); ?></td>
                <td style="padding: 5px;"><?php echo $emprow['gy_emp_email']; ?></td>
                <td style="padding: 5px; position: sticky; left: 0; background-color: #fafafa;" class="text-nowrap text-center"><?php echo $emprow['gy_emp_fullname']; ?></td>
                <td style="padding: 5px;" class="text-center"><?php echo $mr_zendeskid; ?></td>
                <td style="padding: 5px;" class="text-nowrap text-center"><?php echo supervisor_name($emprow['gy_emp_supervisor']); ?></td>
                <td style="padding: 5px;" class="text-nowrap text-center"><?php echo "Skill ".$mr_skill; ?></td>
                <td style="padding: 5px;" class="text-nowrap text-center"><?php echo $arscdt[0]; ?></td>
                <td style="padding: 5px;" class="text-center"><?php echo $arrrd[0]; ?></td>
                <td style="padding: 5px;" class="text-center"><?php echo $arrrd[1]; ?></td>
                <td style="padding: 5px" class="text-center"><?php echo mr_pbrep($mr_pbreps); ?></td>
                <td style="padding: 5px;" class="text-nowrap text-center"><?php echo mr_fg($mr_focusg, $fgarr); ?></td>
                <td style="padding: 5px;" class="text-center"><?php echo $arscdt[1]; ?></td>
                <td style="padding: 5px;" class="text-center text-nowrap"><?php echo $offln; ?></td>
                <td style="padding: 5px;" class="text-center"><?php echo $tenurem; ?></td>
                <td style="padding: 5px;" class="text-center"><?php echo $dhtarget[0]; ?></td>
                <td style="padding: 5px;" class="text-center"><?php echo $dhtarget[1]; ?></td>
                <td style="padding: 5px;" class="text-center"><?php echo $today_email; ?></td>
                <td style="padding: 5px;" class="text-center"><?php echo $today_phone; ?></td>
                <td style="padding: 5px;" class="text-center"><?php echo $today_chat; ?></td>
                <td style="padding: 5px;" class="text-center"><?php echo $today_email+$today_phone+$today_chat; ?></td>
<?php
for($row=0;$row<count($shoparr);$row++){
    $check = 0;
    for($col=0;$col<count($shopemparr);$col++){
        if($shopemparr[$col][0]==$emprow['gy_emp_code'] && $shopemparr[$col][1]==$shoparr[$row][0]){ $check = 1; ?>
            <td style="padding: 0px;">
                <?php if($shopemparr[$col][2]==1){ ?>
                    <span class="text-nowrap"><center style="padding-top: 5px;"><i class="fa fa-check-square"></i></center></span>
                <?php }else{ ?>
                    <span class="text-nowrap"><center style="padding-top: 5px;"><i class="fa fa-square"></i></center></span>
                <?php } ?>
            </td>
<?php break;  }
    } if($check==0){ ?>
            <td style="padding: 0px;">
                    <span class="text-nowrap"><center style="padding-top: 5px;"><i class="fa fa-square"></i></center></span>
            </td>
<?php    }
} ?>

            </tr>
        <?php } $i++; } $link->close(); ?>
        </tboddy>
<input type="hidden" id="inphidschpri" value="<?php echo $schpri; ?>">
<input type="hidden" id="inphidschben" value="<?php echo $schben; ?>">
<input type="hidden" id="inphidprespri" value="<?php echo $prespri; ?>">
<input type="hidden" id="inphidpresben" value="<?php echo $presben; ?>">

<input type="hidden" id="inphidtargetpri" value="<?php echo $targetpri; ?>">
<input type="hidden" id="inphidtargetben" value="<?php echo $targetben; ?>">
<input type="hidden" id="inphidrunpri" value="<?php echo $runpri; ?>">
<input type="hidden" id="inphidrunben" value="<?php echo $runben; ?>">

<?php for($i1=0;$i1<count($tlinx);$i1++){ ?>
<input type="hidden" name="inphidtlname" value="<?php echo $tlarr[$i1][2]; ?>">
<input type="hidden" name="inphidtltarget" value="<?php echo $tlarr[$i1][0]; ?>">
<input type="hidden" name="inphidtlrunn" value="<?php echo $tlarr[$i1][1]; ?>">
<?php } 

function mr_loc($val){
    if($val==0){ return "DV"; }
    else if($val==1){ return "TG"; }
}

function mr_pbrep($val){
    if($val==0){ return "Primary"; }
    else if($val==1){ return "Bench"; }
}

function mr_fg($val, $fgarr){
    $fgname = "";
    for($i=0;$i<count($fgarr);$i++){
        if($fgarr[$i][0]==$val){ $fgname = $fgarr[$i][1]; break; }
    }
    return $fgname;
}

function supervisor_name($supervisor){
    include '../config/conn.php';
    $supervisor=$link->query("SELECT `gy_full_name` From `gy_user` Where `gy_user_id`='$supervisor'");
        $svrow=$supervisor->fetch_array();
    $link->close();
    return $svrow['gy_full_name'];
}

function tl_id($empcode){
    include '../config/conn.php';
    $supervisor=$link->query("SELECT `gy_user_id` From `gy_user` Where `gy_user_code`='$empcode'");
        $svrow=$supervisor->fetch_array();
    $link->close();
    return $svrow['gy_user_id'];
}

function convert24to0($time){
    if($time == "24:00:00"){ $time = "00:00:00"; }
    return $time;
}

function getschedtoday($empid, $curtime){
    date_default_timezone_set('Asia/Taipei');
    if($curtime==""){ $curtime="0000-00-00 00:00:00"; }
    include '../config/conn.php';
    $today = array("","", 0, 0, "");
    $yesterday = date("Y-m-d", strtotime(date("Y-m-d").' -1 day'));
    $tomorrow = date("Y-m-d", strtotime(date("Y-m-d").' +1 day'));
    $empsch=$link->query("SELECT `gy_sched_mode`,`gy_sched_day`, `gy_sched_login`, `gy_sched_logout` FROM `gy_schedule` WHERE `gy_sched_day`>='".$yesterday."' AND `gy_sched_day`<='".$tomorrow."' AND `gy_emp_id`='".$empid."' ORDER BY `gy_sched_day` ASC");
    if(mysqli_num_rows($empsch) > 0){
        while ($scrow=$empsch->fetch_array()) {
            if(date("H:i:s", strtotime(convert24to0($scrow['gy_sched_login']))) > date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout'])))) {
                $schedlout = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout']))).' +1 day');
            }else{
                $schedlout = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout']))));
            }
            $scin = $scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_login'])));
            $scout = date("Y-m-d H:i:s", $schedlout);
            if(strtotime(date("Y-m-d H:i:s")) >= strtotime($scin)){ $today[4] = $scin; }
            if(strtotime(date("Y-m-d H:i:s")) < $schedlout){
                if($scrow['gy_sched_mode']==1){
                $today[0] = date("h:i:00 A", strtotime(convert24to0($scrow['gy_sched_login'])))." - ".date("h:i:00 A", strtotime(convert24to0($scrow['gy_sched_logout'])));
                }else{ $today[0] = "RD"; }
            if(strtotime($curtime)>=strtotime($scin)&&strtotime($curtime)<=strtotime($scout) ){ $today[1] = "Present"; $today[2] = 1; }
            else if(strtotime($curtime)<strtotime($scin) && strtotime(date("Y-m-d H:i:s"))>=strtotime($scin)){ $today[1] = "Absent"; $today[3] = 1; }
            else { $today[1] = ""; }
            break; }
        }
    }
$link->close();
return $today;
}

function getrddate($empid, $sun,$sat){
include '../config/conn.php';
$rd = array("", "");
$empsch=$link->query("SELECT `gy_sched_mode`,`gy_sched_day` FROM `gy_schedule` WHERE `gy_sched_day`>='".$sun."' AND `gy_sched_day`<='".$sat."' AND `gy_emp_id`='".$empid."' ORDER BY `gy_sched_day` ASC");
    if(mysqli_num_rows($empsch) > 0){ $i = 0;
        while ($scrow=$empsch->fetch_array()) {
            if($scrow['gy_sched_mode'] == 0){
                if(date('N', strtotime($scrow['gy_sched_day'])) == 1){ $rd[$i]='M'; }
                else if(date('N', strtotime($scrow['gy_sched_day'])) == 2){ $rd[$i]='T'; }
                else if(date('N', strtotime($scrow['gy_sched_day'])) == 3){ $rd[$i]='W'; }
                else if(date('N', strtotime($scrow['gy_sched_day'])) == 4){ $rd[$i]='Th'; }
                else if(date('N', strtotime($scrow['gy_sched_day'])) == 5){ $rd[$i]='F'; }
                else if(date('N', strtotime($scrow['gy_sched_day'])) == 6){ $rd[$i]='SA'; }
                else if(date('N', strtotime($scrow['gy_sched_day'])) == 7){ $rd[$i]='Su'; }
            $i++;
            }
        if($i>1){ break; }
        }
    }
$link->close();
return $rd;
}

function tenuremonth($hdate){
    if($hdate!="0000-00-00" || $hdate!=""){
        $hdate = date("Y-m-d", strtotime($hdate));
        $datediff = strtotime(date("Y-m-d")) - strtotime($hdate);
        $total = round($datediff / (60 * 60 * 24));
        return round($total/30);
    }else { return ""; }
}

function dhtarget($month, $skill, $targetarr){
    $mult = 7.5*0.85;
    $target = array(0,0);
    $emailt = 0;
    for($i=0;$i<count($targetarr);$i++){
        if($targetarr[$i][0]==$skill){
            if($targetarr[$i][1]=="="){
                if($targetarr[$i][2]==$month){
                    $emailt = $targetarr[$i][4];
                    break;
                }
            }else if($targetarr[$i][1]==">"){
                if(($month>$targetarr[$i][2]&&$targetarr[$i][3]==0)||($month>$targetarr[$i][2]&&$month<=$targetarr[$i][3])){
                    $emailt = $targetarr[$i][4];
                    break;
                }
            }else if($targetarr[$i][1]==">="){
                if(($month>=$targetarr[$i][2]&&$targetarr[$i][3]==0)||($month>=$targetarr[$i][2]&&$month<=$targetarr[$i][3])){
                    $emailt = $targetarr[$i][4];
                    break;
                }
            }else if($targetarr[$i][1]=="<"){
                if($month<$targetarr[$i][2]){
                    $emailt = $targetarr[$i][4];
                    break;
                }
            }else if($targetarr[$i][1]=="<="){
                if($month<=$targetarr[$i][2]){
                    $emailt = $targetarr[$i][4];
                    break;
                }
            }
        }
    }
    //$edailyt = $emailt * 8;
    $target[0] = round($emailt * $mult);
    $target[1] = ceil($target[0]/8);
    return $target;
}

function getmindif($sdate, $adate, $mod){
    $tosec = strtotime($adate) - strtotime($sdate);
    $hour = floor($tosec / 3600);
    if($mod == "in"){ $min = floor(($tosec - 3600 * $hour)/60); }
    else if($mod == "out"){ $min = ceil(($tosec - 3600 * $hour)/60); }
    $sec = $tosec % 60;
    if($min<10){ $min="0".$min; }
    return $hour.":".$min;
}
 ?>