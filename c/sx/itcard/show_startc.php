<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$limsql = "";
$row1=0; $namearr = array(array());
$filtype = addslashes($_REQUEST['filtype']);
$status = addslashes($_REQUEST['status']);
$cha = addslashes($_REQUEST['cha']);
$sortby = addslashes($_REQUEST['sortby']);
$seltool = addslashes($_REQUEST['seltool']);
if($status==0){ $sttsql = " `gy_user`.`gy_user_status`='0' "; }
else if($status==1){ $sttsql = " `gy_user`.`gy_user_status`='1' "; }
else if($status==2){ $sttsql = " `gy_user`.`gy_user_status`>='0' AND `gy_user`.`gy_user_status`<='1' "; }
if($sortby==0){ $sqlcon = "gy_emp_fname"; }else if($sortby==1){ $sqlcon = "gy_emp_lname"; }
if($cha==""){
$pgnum = addslashes($_REQUEST['pgnum']);
$snum = ($pgnum * 10) - 10;

$limsql = " LIMIT ".$snum.", 10";
}
$codearr = array();
$seasql=$link->query("SELECT `gy_emp_code`,`gy_emp_fname`,`gy_emp_lname`,`gy_emp_mname`,`gy_user_status` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` WHERE `gy_employee`.`$sqlcon` LIKE '$cha%' AND ".$sttsql." ORDER BY `$sqlcon` asc".$limsql);
    while ($searow=$seasql->fetch_array()){
        $codearr[$row1] = $searow['gy_emp_code'];
        $namearr[0][$row1] = $searow['gy_emp_code'];
        $namearr[1][$row1] = $searow['gy_emp_fname'];
        $namearr[2][$row1] = $searow['gy_emp_lname'];
        $namearr[3][$row1] = $searow['gy_emp_mname'];
        $namearr[4][$row1] = $searow['gy_user_status'];
        $namearr[5][$row1] = 1;
        if($filtype==2){
            $cprsql=$link->query("SELECT `td_tooldid` FROM `tool_data` WHERE `td_emp_code`='".$namearr[0][$row1]."' AND `td_value`!='' ");
            $namearr[5][$row1] = $cprsql->num_rows;
            if($namearr[5][$row1]>0 && $seltool>0){
                $tmpcnt=0;
                while($cprrow=$cprsql->fetch_array()){
                $cmpsql=$link->query("SELECT * FROM `tool_details` WHERE `toold_listid`='".$seltool."' AND `toold_id`='".$cprrow['td_tooldid']."' ");
                    $tmpcnt+=$cmpsql->num_rows;
                }
                $namearr[5][$row1] = $tmpcnt;
            }
        }
        if($namearr[5][$row1]>0){
        $row1++;
        }
    }

$seltlsql = "";
if($seltool!=""){ $seltlsql=" WHERE `tool_id`='$seltool' "; }
$row2=0; $tmp2=0; $tlstarr = array(array()); $tldarr = array(array(array())); $tooldid = array();
$tlstsql = $link->query("SELECT * FROM `tool_list` ".$seltlsql." ORDER BY `tool_name` asc");
    while($tlstrow=$tlstsql->fetch_array()){
        if($tlstrow['tool_status']==1){
            $tlstarr[$row2][1] = $tlstrow['tool_name'];
            $currtid = $tlstrow['tool_id'];
            $tldsql = $link->query("SELECT `toold_id`,`toold_label`,`toold_type` FROM `tool_details` WHERE `toold_listid`=$currtid AND `toold_status`=1 ORDER BY `toold_sortid` ASC");
                $tmp1=0;
                while($tldrow=$tldsql->fetch_array()){
                    $tldarr[$row2][$tmp1][0] = $tldrow['toold_label'];
                    $tldarr[$row2][$tmp1][1] = $tldrow['toold_id'];
                    $tldarr[$row2][$tmp1][2] = $tldrow['toold_type'];
                    $tooldid[$tmp2] = $tldrow['toold_id'];
                    $tmp1++; $tmp2++;
                }
                $tlstarr[$row2][0] = $tmp1;
        $row2++;
        }
    }

$row3 = 0; $tooldata=array(array());
if(!empty($tooldid) && !empty($codearr)){
$tdtsql = $link->query("SELECT * FROM `tool_data` WHERE `td_emp_code` IN ('".implode("','", $codearr)."') AND `td_tooldid` IN (".implode(",", $tooldid).") ");
    while($tdtrow=$tdtsql->fetch_array()){
        $tooldata[$row3][0] = $tdtrow['td_id'];
        $tooldata[$row3][1] = $tdtrow['td_tooldid'];
        $tooldata[$row3][2] = $tdtrow['td_emp_code'];
        $tooldata[$row3][3] = $tdtrow['td_value'];
        $tooldata[$row3][4] = $tdtrow['td_status'];
        $row3++;
    }
}
$link->close();
?>

        <thead class="text-center text-nowrap bg-secondary text-white">
            <tr>
                <th scope="col" class="bg-secondary" style="padding:0px; position:sticky; left:0px; top:0px;" ></th>
                <?php for($i=0;$i<$row2;$i++){ ?>
                    <th scope="col" style="padding:4px;" colspan="<?php echo ifzeror1($tlstarr[$i][0]); ?>" class="text-capitalize"><?php echo $tlstarr[$i][1]; ?></th>
                <?php } ?>
            </tr>

            <tr>
                <th scope="col" class="bg-secondary" style="padding:4px; position:sticky; left:0px; top:0px;">Name</th>
                <?php for($i=0;$i<$row2;$i++){
                        if($tlstarr[$i][0]>0){
                        for($i1=0;$i1<$tlstarr[$i][0];$i1++){ ?>
                            <th scope="col" style="padding:4px;" class="text-capitalize"><?php echo $tldarr[$i][$i1][0]; ?></th>
                <?php }}else{ ?>
                            <th scope="col"></th>
                <?php } } ?>
            </tr>
        </thead>
        <tbody>
<?php for($i=0;$i<$row1;$i++){ ?>
<tr class="text-nowrap text-center <?php if($namearr[4][$i]==1){echo "text-danger";} ?>" <?php if($namearr[5][$i]<=0){ ?>style="visibility:collapse"<?php } ?> >
    <td class="bg-light" style="padding:3px; position:sticky; left:0px; top:0px;"><?php if($sortby==0){ echo $namearr[1][$i]." ".$namearr[3][$i]." ".$namearr[2][$i]; }else if($sortby==1){ echo $namearr[2][$i]." ".$namearr[3][$i].", ".$namearr[1][$i]; } ?></td>

            <?php for($i1=0;$i1<$row2;$i1++){
                if($tlstarr[$i1][0]>0){
                for($i2=0;$i2<$tlstarr[$i1][0];$i2++){ $selval = findinx($tldarr[$i1][$i2][1], $namearr[0][$i], $tooldata, $row3); ?>
                <td style="padding:3px;" onmouseover='showelem("ico_<?php echo $i."-".$i1."-".$i2; ?>"); showelem("vwpsw_<?php echo $i."-".$i1."-".$i2; ?>"); showelem("cpyval_<?php echo $i."-".$i1."-".$i2; ?>");' onmouseout='hideelem("ico_<?php echo $i."-".$i1."-".$i2; ?>"); hideelem("vwpsw_<?php echo $i."-".$i1."-".$i2; ?>"); hideelem("cpyval_<?php echo $i."-".$i1."-".$i2; ?>");'>
                    <span id="dspt_<?php echo $i."-".$i1."-".$i2; ?>">
                        <span id="lblsp_<?php echo $i1."-".$i2."-".$i; ?>"><?php  if(!empty($selval)){ if($tldarr[$i1][$i2][2]=="password"){echo "********";}else{ echo $selval[1]; } } ?></span>
                        <button onclick='hideelem("dspt_<?php echo $i."-".$i1."-".$i2; ?>"); showelem("inpt_<?php echo $i."-".$i1."-".$i2; ?>");' title="Edit"><i class="fa-solid fa-pen" style="display: none;" id="ico_<?php echo $i."-".$i1."-".$i2; ?>"></i></button>

                    </span>
                    <span style="display: none;" id="inpt_<?php echo $i."-".$i1."-".$i2; ?>">
                    <input type="<?php echo $tldarr[$i1][$i2][2]; ?>" class="text-center" value='<?php if(!empty($selval)){echo $selval[1];} ?>' onfocusin='cleartimer()' onfocusout='settimer()' id="inpidval_<?php echo $i1."-".$i2."-".$i; ?>" onkeypress="kpresssv(this)">
                    <input type="hidden" id="fndtlid_<?php echo $i1."-".$i2."-".$i; ?>" value="<?php echo $tldarr[$i1][$i2][1]; ?>" style="display: none;" >
                    <input type="hidden" id="fndecd_<?php echo $i1."-".$i2."-".$i; ?>" style="display: none;" value="<?php echo $namearr[0][$i]; ?>" >
                    <input type="hidden" id="inptyp_<?php echo $i1."-".$i2."-".$i; ?>" style="display: none;" value="<?php echo $tldarr[$i1][$i2][2]; ?>">
                    <button onclick='upddata("<?php if(!empty($selval)){ echo $selval[0]; }else{echo "empty";} ?>", "<?php echo $i1."-".$i2."-".$i; ?>", "<?php echo $i."-".$i1."-".$i2; ?>");' title="Update" id="uptcllbtn_<?php echo $i1."-".$i2."-".$i; ?>"><i class="fa-solid fa-pen-to-square"></i></button>
                    </span>
                        <?php if($tldarr[$i1][$i2][2]=="password" && !empty($selval)){?>
                            <button id="vwpsw_<?php echo $i."-".$i1."-".$i2; ?>" style="display: none;" title="Show Password" onclick="eyesh(this, '<?php echo $i1."-".$i2."-".$i; ?>')"><i class="fa-solid fa-eye"></i></button>
                        <?php } if(!empty($selval)){ ?><button id="cpyval_<?php echo $i."-".$i1."-".$i2; ?>" style="display: none;" title="copy" onclick="cpytocb(this, '<?php echo $i1."-".$i2."-".$i; ?>')"><i class="fa-solid fa-copy"></i></button><?php } ?>
                </td>
            <?php }}else{ ?>
                <td style="padding:3px;" ></td>
            <?php } } ?>

</tr>
<?php } ?>
        </tbody>

<?php
    function ifzeror1($num){
        if($num<=0){ return 1; }else{ return $num; }
    }

    function findinx($toolval, $codeval, $twodiarr, $rwcnt){
        $selval = array();
        for($i3=0;$i3<$rwcnt;$i3++){
            if($twodiarr[$i3][1]==$toolval && $twodiarr[$i3][2]==$codeval){
                $selval[0] = $twodiarr[$i3][0];
                $selval[1] = $twodiarr[$i3][3];
                $selval[2] = $twodiarr[$i3][4];
                break;
            }
        }
    return $selval;
    }
?>