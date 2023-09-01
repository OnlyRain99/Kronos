<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

    function typeofsched($typid){
        if($typid==1){ $torname = "Escalate My Schedule Adjustment (SA)"; }
        else if($typid==2){ $torname = "Escalate My Overtime (RDOT)"; }
        else { $torname = "Escalate My Schedule Adjustment (RD)"; }
        return $torname;
    }

$pgnm = addslashes($_REQUEST['pgnm']);
$activepage=1;
$numofpg=10;
$snum = ($pgnm * $numofpg) - $numofpg;

	$sql1="SELECT * From `gy_schedule_escalate` LEFT JOIN `gy_employee` ON `gy_schedule_escalate`.`gy_emp_code`=`gy_employee`.`gy_emp_code` Where `gy_schedule_escalate`.`gy_req_status`='2' AND `gy_schedule_escalate`.`gy_publish`=0 AND `gy_employee`.`gy_emp_supervisor`=$user_id Order By `gy_schedule_escalate`.`gy_req_date` DESC, `gy_schedule_escalate`.`gy_sched_mode` ASC";
    $sql2="SELECT * From `gy_escalate` LEFT JOIN `gy_employee` ON `gy_escalate`.`gy_usercode`=`gy_employee`.`gy_emp_code` Where `gy_escalate`.`gy_esc_status`='2' AND `gy_escalate`.`gy_publish`=0 AND `gy_employee`.`gy_emp_supervisor`=$user_id Order By `gy_esc_date` DESC, `gy_escalate`.`gy_esc_type` ASC";

$sqlqry1=$link->query($sql1);
$sqlqry2=$link->query($sql2);
$cnt1 = $sqlqry1->num_rows;
$cnt2 = $sqlqry2->num_rows;
if($cnt1 > $cnt2){ $btncnt=$cnt1; }else{ $btncnt=$cnt2; }

$rqtschd=$link->query($sql1." LIMIT ".$snum.", ".$numofpg);
$rqtlogs=$link->query($sql2." LIMIT ".$snum.", ".$numofpg);

    $i=0; $schdlst = array(array());
    while($schdrow=$rqtschd->fetch_array()){
    	$schdlst[$i][0] = $schdrow['gy_sched_esc_id'];
    	$schdlst[$i][1] = $schdrow['gy_emp_code'];
	   	$schdlst[$i][2] = $schdrow['gy_req_date'];
    	$schdlst[$i][3] = $schdrow['gy_sched_mode'];
    	$schdlst[$i][4] = $schdrow['gy_sched_day'];
    	$i++;
    }

    $i1=0; $esclst = array(array());
    while($logsrow=$rqtlogs->fetch_array()){
    	$esclst[$i1][0] = $logsrow['gy_esc_id'];
    	$esclst[$i1][1] = $logsrow['gy_esc_type'];
    	$esclst[$i1][2] = $logsrow['gy_usercode'];
    	$esclst[$i1][3] = $logsrow['gy_esc_date'];
    	$esclst[$i1][4] = $logsrow['old_tracker_date'];
    	$i1++;
    }

 $link->close();

for($i2=0;$i2<$i;$i2++){
?>
<tr class="mybg text-nowrap text-center">
    <td scope="row" style="padding:4px;"><?php echo typeofsched($schdlst[$i2][3]); ?></td>
    <td style="padding:4px;"><?php echo get_emp_name($schdlst[$i2][1]); ?></td>
    <td style="padding:4px;"><?php echo date("F d, Y", strtotime($schdlst[$i2][4])); ?></td>
    <td style="padding:4px;"><?php echo date("F d, Y", strtotime($schdlst[$i2][2])); ?></td>
    <td style="padding:0px;"><button class="btn btn-outline-secondary btn-sm btn-block" onclick="showmrdtls(<?php echo $schdlst[$i2][0]; ?>, 0)"><i class="fa-solid fa-box-open"></i> OPEN </button></td>
</tr>
<?php } for($i3=0;$i3<$i1;$i3++){ ?>
<tr class="mybg text-nowrap text-center">
    <td scope="row" style="padding:4px;"><?php if($esclst[$i3][1]==6){ echo "Escalate My Overtime (OT)"; }else{ echo escalate_type($esclst[$i3][1]); } ?></td>
    <td style="padding:4px;"><?php echo get_emp_name($esclst[$i3][2]); ?></td>
    <td style="padding:4px;"><?php echo date("F d, Y", strtotime($esclst[$i3][4])); ?></td>
    <td style="padding:4px;"><?php echo date("F d, Y", strtotime($esclst[$i3][3])); ?></td>
    <td style="padding:0px;"><button class="btn btn-outline-secondary btn-sm btn-block" onclick="showmrdtls(<?php echo $esclst[$i3][0]; ?>, 1)"><i class="fa-solid fa-box-open"></i> OPEN </button></td>
</tr>
<?php } ?>

<nav aria-label="..." style="position: absolute;">
    <ul class="pagination flex-wrap" id="pagelink">
    <?php for($i4=1;$i4<=ceil($btncnt/$numofpg);$i4++){ ?>
        <li class="page-item <?php if($pgnm==$i4){echo "active"; $activepage=$pgnm;}?>"><a class="page-link" href="#" onclick="loadtblescrqst(<?php  echo $i4; ?>)"><?php echo $i4; ?></a></li>
    <?php } ?>
    </ul>
</nav>