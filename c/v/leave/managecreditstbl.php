<?php
    include '../../../config/conn.php';
	include '../../../config/function.php';
    include '../session.php';

if($user_type == 5 && $user_dept == 2){
$activepage=1;
$dptslct = addslashes($_REQUEST['dptslct']);
$sibsemp = addslashes($_REQUEST['sibsemp']);
$pgnm = addslashes($_REQUEST['pgn']);
$numofpg=11;
$snum = ($pgnm * $numofpg) - $numofpg;
$pglimit = "";
$emp2bsrch="";
$dpt2bsrch="";
if($dptslct==""){ $pglimit=" Limit ".$snum.", ".$numofpg; }
if($dptslct!="" && $dptslct!="all"){ $dpt2bsrch=" AND `gy_employee`.`gy_acc_id`=$dptslct "; }
if($sibsemp!=""){ $emp2bsrch=" AND (`gy_employee`.`gy_emp_fullname` LIKE '%$sibsemp%' OR `gy_employee`.`gy_emp_code`='$sibsemp') "; }
$sql1="SELECT `gy_employee`.`gy_emp_code`as`empcod`,`gy_employee`.`gy_emp_fullname`as`fllnam`,`gy_employee`.`gy_emp_leave_credits`as`levcri` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user`.`gy_user_status`=0 ".$emp2bsrch.$dpt2bsrch;

$cntsql=$link->query($sql1);
$btncnt=$cntsql->num_rows;

$crdsql=$link->query($sql1.$pglimit);
$i=0; $crdarr = array(array());
while($crdrow=$crdsql->fetch_array()){
$crdarr[$i][0]=$crdrow['empcod'];
$crdarr[$i][1]=$crdrow['fllnam'];
$crdarr[$i][2]=$crdrow['levcri'];
$i++;
}
}
$link->close();

for($i1=0;$i1<$i;$i1++){ ?>
<tr class="text-nowrap text-center">
	<td style="padding-bottom:3px; padding-top:3px;" scope="row">
		<div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="cchck" id="cchck_<?php echo $i1; ?>" value="<?php echo $crdarr[$i1][0]; ?>">
            <label class="custom-control-label" for="cchck_<?php echo $i1; ?>"></label>
        </div>
	</td>
	<td style="padding-bottom:2px;"><?php echo $crdarr[$i1][0]; ?></td>
	<td style="padding-bottom:2px;"><?php echo $crdarr[$i1][1]; ?></td>
	<td style="padding:0px;"><input class="form-control form-control-sm" id="lyvcrdts_<?php echo $i1; ?>" oninput="lvcrdtsedt(<?php echo $i1;?>)" type="number" min="0" value="<?php echo $crdarr[$i1][2]; ?>"></td>
	<td style="padding:0px;"><button id="updtlvcrdts_<?php echo $i1; ?>" class="btn btn-outline-light btn-sm btn-block" onclick="updtlvcrdts(<?php echo $i1; ?>, <?php echo $pgnm; ?>)" disabled><i class="fa-solid fa-check"></i> Update</button></td>
</tr>
<?php } if($dptslct==""){ ?>
<nav aria-label="..." style="position: absolute;">
    <ul class="pagination flex-wrap" id="pagelink">
    	<?php for($i2=1;$i2<=ceil($btncnt/$numofpg);$i2++){ ?>
    		<li class="page-item <?php if($pgnm==$i2){echo "active"; $activepage=$pgnm; }?>"><a class="page-link" onclick="managecreditbl(<?php  echo $i2; ?>)"><?php echo $i2; ?></a></li>
    	<?php } ?>
    </ul>
</nav>
<?php } ?>
<input type="hidden" id="actpage" value="<?php echo $activepage; ?>">