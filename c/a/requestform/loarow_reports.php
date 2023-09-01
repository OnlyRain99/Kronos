<?php
include '../../../config/conn.php';
include '../../../config/function.php';
include '../session.php';

$dfro = addslashes($_REQUEST['dfro']);
$dato = addslashes($_REQUEST['dato']);
$pars = addslashes($_REQUEST['pars']);
$pbsh = addslashes($_REQUEST['pbsh']);

$dfro = date("Y-m-d", strtotime($dfro));
$dato = date("Y-m-d", strtotime($dato));

if($pars==0){ $lyvrstt="`gy_leave_status`!=2"; }
else if($pars==1){ $lyvrstt="`gy_leave_status`=1"; }
else if($pars==2){ $lyvrstt="`gy_leave_status`=0"; }

$lyv2arr = array(array());
$lyvsql=$link->query("SELECT * From `gy_leave` WHERE `gy_leave_date_from`>='$dfro' AND `gy_leave_date_to`<='$dato' AND `gy_publish`=$pbsh AND ".$lyvrstt." ORDER BY `gy_leave_date_from` asc");
$i3=0;
while ($lyvrow=$lyvsql->fetch_array()){
    $lyv2arr[$i3][0] = $lyvrow['gy_leave_id'];
    $lyv2arr[$i3][1] = $lyvrow['gy_leave_filed'];
    $lyv2arr[$i3][2] = $lyvrow['gy_leave_type'];
    $lyv2arr[$i3][3] = $lyvrow['gy_leave_reason'];
    $lyv2arr[$i3][4] = $lyvrow['gy_user_id'];
    $lyv2arr[$i3][5] = $lyvrow['gy_leave_status'];
    $lyv2arr[$i3][6] = $lyvrow['gy_leave_date_from'];
    $lyv2arr[$i3][7] = $lyvrow['gy_leave_approver'];
    $lyv2arr[$i3][8] = $lyvrow['gy_leave_date_approved'];
    $lyv2arr[$i3][9] = $lyvrow['gy_publish'];
    $lyv2arr[$i3][10] = $lyvrow['gy_leave_paid'];
    $lyv2arr[$i3][11] = $lyvrow['gy_leave_day'];
    $i3++;
}

$link->close();
for($i=0;$i<$i3;$i++){
?>
<tr class="text-nowrap text-center">
    <td style="padding:4px;"><?php echo date("F d, Y", strtotime($lyv2arr[$i][6])); ?></td>
	<td style="padding:4px;"><?php echo get_emp_code($lyv2arr[$i][4]); ?></td>
	<td style="padding:4px;"><?php echo getuserfullname($lyv2arr[$i][4]); ?></td>
    <td style="padding:4px;"><?php echo get_leave_type($lyv2arr[$i][2]); ?></td>
    <td style="padding:4px;"><?php
		if($lyv2arr[$i][10]==0 && $lyv2arr[$i][11]==1){ echo "Approved LOA (No Pay)"; }
        else if($lyv2arr[$i][10]==1 && $lyv2arr[$i][11]==1){ echo "Approved LOA (With Pay)"; }
        else if($lyv2arr[$i][10]==0 && $lyv2arr[$i][11]==0.5){ echo "Approved Half Day LOA (No Pay)"; }
        else if($lyv2arr[$i][10]==1 && $lyv2arr[$i][11]==0.5){ echo "Approved Half Day LOA (With Pay)"; }
	?></td>
</tr>
<?php } ?>