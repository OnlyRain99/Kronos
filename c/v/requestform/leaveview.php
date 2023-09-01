<?php
include '../../../config/conn.php';
include '../../../config/function.php';
include '../session.php';

$rid = addslashes($_REQUEST['rid']);

$dssql=$link->query("SELECT * FROM `gy_leave` WHERE `gy_leave_id`=$rid Limit 1");
$dsrow=$dssql->fetch_array();

$dptsql=$link->query("SELECT `gy_department`.`name_department`as`dptname`,`gy_accounts`.`gy_acc_name`as`accnm` From `gy_accounts` LEFT JOIN `gy_department` ON `gy_accounts`.`gy_dept_id`=`gy_department`.`id_department` WHERE `gy_accounts`.`gy_acc_id`='".$dsrow['gy_acc_id']."' ");
$dptrow=$dptsql->fetch_array();

?>
<div class="card" style="max-width: 500px; margin: 0px;">
    <h4 class="card-header"><?php if($dsrow['gy_leave_day']==1){echo "Full Day | ";}else if($dsrow['gy_leave_day']==0.5){echo "Half Day | ";} echo get_leave_type($dsrow['gy_leave_type']); ?></h4>
    <div class="card-body">
    	<div class="row">
    		<div class="col"><button type="button" class="btn btn-<?php if($dsrow['gy_leave_status']==0){echo"warning";}else if($dsrow['gy_leave_status']==1){echo"success";}else if($dsrow['gy_leave_status']==2){echo"danger";}else{echo"secondary";} ?> btn-lg btn-block" disabled><?php if($dsrow['gy_leave_status']==0){echo'<i class="fa-solid fa-chalkboard-user"></i> Request Pending';}else if($dsrow['gy_leave_status']==1){echo'<i class="fa-solid fa-calendar-check"></i> '; if($dsrow['gy_publish']==1){if($dsrow['gy_leave_paid']==1){echo "Paid";}else{echo "Unpaid";}} echo " LOA Approved"; }else if($dsrow['gy_leave_status']==2){echo'<i class="fa-solid fa-handshake-slash"></i> LOA Request Rejected';}else{echo'<i class="fa-solid fa-circle-xmark"></i> LOA Request Cancelled';} ?></button></div>
    	</div>
    	<table class="table table-responsive table-striped">
    		<tbody>
    			<tr>
    				<th scope="row" colspan="2" class="text-nowrap text-center"><?php echo $dptrow['dptname']." - ".$dptrow['accnm']; ?></th>
    			</tr>
                <tr>
                    <th scope="row" class="text-nowrap text-right">LOA Date :</th>
                    <td class="text-left"><?php echo date("F j, Y", strtotime($dsrow['gy_leave_date_from'])); ?></td>
                </tr>
    			<tr>
    				<th scope="row" class="text-nowrap text-right">LOA Reason :</th>
    				<td class="text-left"><?php echo $dsrow['gy_leave_reason']; ?></td>
    			</tr>
    			<?php if($dsrow['gy_leave_status']==1 || $dsrow['gy_leave_status']==2){ ?>
    			<tr>
    				<th scope="row" class="text-nowrap text-right">Approved By :</th>
    				<td class="text-left"><?php echo getuserfullname($dsrow['gy_leave_approver']); ?></td>
    			</tr>
    			<tr>
    				<th scope="row" class="text-nowrap text-right">Approved Date :</th>
    				<td class="text-left"><?php echo date("F j, Y", strtotime($dsrow['gy_leave_date_approved'])); ?></td>
    			</tr>
    			<?php } if($dsrow['gy_leave_status']==2){ ?>
    			<tr>
    				<th scope="row" class="text-nowrap text-right"><span class="text-danger">Rejection Reason :</th>
    				<td class="text-left"><?php echo $dsrow['gy_leave_remarks']; ?></td>
    			</tr>
    			<?php } ?>
    		</tbody>
    	</table>
    	<div class="row">
    		<div class="col" style="padding-right: 0px;">
    			<a type="button" href="../../kronos_file_store/<?php echo $dsrow['gy_leave_attachment']; ?>" target="_blank" class="btn btn-outline-primary btn-block"><i class="fa-solid fa-eye"></i> View File</a>
    		</div>
    		<div class="col" style="padding-left: 0px;">
    			<a type="button" href="leave/dl_attach?fid=<?php echo $dsrow['gy_leave_id']; ?>" target="_new" class="btn btn-outline-primary btn-block"><i class="fa-solid fa-file-arrow-down"></i> Downoad File</a>
    		</div>
    	</div>
    </div>
    <div class="card-footer text-muted">Filed on <?php echo date("F j, Y h:i:s a", strtotime($dsrow['gy_leave_filed'])) ?></div>
</div>
<?php
$link->close();
?>