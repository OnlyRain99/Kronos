<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$activepage=1;
 if($user_type == 5 && $user_dept == 2){
$dptlst = addslashes($_REQUEST['dptlst']);
$nmsrch = addslashes($_REQUEST['nmsrch']);
$status = addslashes($_REQUEST['status']);
$pgnm = addslashes($_REQUEST['pgnm']);
$numofpg=13;
$snum = ($pgnm * $numofpg) - $numofpg;
$intsql = "`gy_employee`.`gy_emp_code`as`sibsid`,`gy_employee`.`gy_emp_fullname`as`flname`,`gy_employee`.`gy_emp_email`as`semail`,`gy_employee`.`gy_emp_account`as`accnm`,`gy_employee`.`gy_acc_id`as`accid`,`gy_employee`.`gy_emp_rate`as`rate`,`gy_employee`.`gy_work_from`as`wfoh`,`gy_employee`.`gy_emp_type`as`lvl`,`gy_employee`.`gy_emp_supervisor`as`sup`,`gy_employee`.`gy_lastedit_by`as`lstedtby`";
$extsql = " ORDER BY `gy_employee`.`gy_lastedit_by` asc,`gy_user`.`gy_full_name` asc ";
if($dptlst=="all" && $nmsrch==""){
$sql="SELECT ".$intsql." FROM `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user`.`gy_user_status`=$status ".$extsql;
}else if($dptlst!="all" && $nmsrch==""){
$sql="SELECT ".$intsql." FROM `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user`.`gy_user_status`=$status AND `gy_employee`.`gy_acc_id`=$dptlst ".$extsql;
}else if($dptlst=="all" && $nmsrch!=""){
$sql="SELECT ".$intsql." FROM `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user`.`gy_user_status`=$status AND (`gy_user`.`gy_full_name` LIKE '%$nmsrch%' OR `gy_employee`.`gy_emp_code`='$nmsrch') ".$extsql;
}else{
$sql="SELECT ".$intsql." FROM `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user`.`gy_user_status`=$status AND `gy_employee`.`gy_acc_id`=$dptlst AND (`gy_user`.`gy_full_name` LIKE '%$nmsrch%' OR `gy_employee`.`gy_emp_code`='$nmsrch') ".$extsql;
}

$empsql=$link->query($sql);
$btncnt=$empsql->num_rows;
$empsql=$link->query($sql." LIMIT ".$snum.", ".$numofpg);
    $i=0; $pdslst = array(array());
    while($emprow=$empsql->fetch_array()){
        $pdslst[$i][0]=$emprow['sibsid'];
        $pdslst[$i][1]=$emprow['flname'];
        $pdslst[$i][2]=$emprow['semail'];
        $pdslst[$i][3]=$emprow['accnm'];
        $pdslst[$i][4]=$emprow['accid'];
        $pdslst[$i][5]=$emprow['rate'];
        $pdslst[$i][6]=$emprow['wfoh'];
        $pdslst[$i][7]=$emprow['lvl'];
        $pdslst[$i][8]=$emprow['sup'];
        $pdslst[$i][9]=$emprow['lstedtby'];
        $i++;
    }

$dptsql=$link->query("SELECT `gy_accounts`.`gy_acc_id`as`accid`,`gy_department`.`name_department`as`dptname` From `gy_accounts` LEFT JOIN `gy_department` ON `gy_accounts`.`gy_dept_id`=`gy_department`.`id_department` ");
    $i1=0; $dptarr = array(array());
    while($dptrow=$dptsql->fetch_array()){
        $dptarr[$i1][0]=$dptrow['accid'];
        $dptarr[$i1][1]=$dptrow['dptname'];
        $i1++;
    }

for($i2=0;$i2<$i;$i2++){
?>
<tr class="<?php if($pdslst[$i2][9]==""){echo"bg-danger";} ?> text-nowrap text-center">
    <td style="padding:4px;" scope="row"><?php echo $pdslst[$i2][0]; ?></td>
    <td style="padding:4px;"><?php echo $pdslst[$i2][1]; ?></td>
    <td style="padding:4px;"><?php echo $pdslst[$i2][2]; ?></td>
    <td style="padding:4px;"><?php if($pdslst[$i2][6]==0){echo"Office";}else if($pdslst[$i2][6]==1){echo"Home";} ?></td>
    <td style="padding:4px;"><?php if($pdslst[$i2][5]==0){echo"Daily";}else if($pdslst[$i2][5]==1){echo"Monthly";} ?></td>
    <td style="padding:4px;"><?php echo $pdslst[$i2][7]; ?></td>
    <td style="padding:4px;"><?php echo getuserfullname($pdslst[$i2][8]); ?></td>
    <td style="padding:0px;"><button type="submit" class="btn btn-primary btn-block btn-sm" onclick="pdscards('<?php echo $pdslst[$i2][0]; ?>')"><b>PDS</b></button></td>
</tr>
<?php } ?>
<nav aria-label="..." style="position: absolute;">
    <ul class="pagination flex-wrap" id="pagelink">
    <?php for($i2=1;$i2<=ceil($btncnt/$numofpg);$i2++){ ?>
        <li class="page-item <?php if($pgnm==$i2){echo "active"; $activepage=$pgnm;}?>"><a class="page-link" href="#" onclick="getmenuinpval(<?php  echo $i2; ?>)"><?php echo $i2; ?></a></li>
    <?php } ?>
    </ul>
</nav>
<?php } $link->close(); ?>
<input type="hidden" id="actpage" value="<?php echo $activepage; ?>">