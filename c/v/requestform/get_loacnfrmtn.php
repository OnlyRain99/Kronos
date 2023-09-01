<?php
include '../../../config/conn.php';
include '../../../config/function.php';
include '../session.php';

$rid = addslashes($_REQUEST['rid']);

$dssql=$link->query("SELECT * FROM `gy_leave` WHERE `gy_publish`=0 AND `gy_leave_id`=$rid Limit 1");
if($dssql->num_rows>0){
$dsrow=$dssql->fetch_array();

    $lyvusrsql = $link->query("SELECT `gy_user_id` FROM `gy_leave` WHERE `gy_leave_id`='$rid' LIMIT 1");
    $lyvusrrow=$lyvusrsql->fetch_array();

    $rytsql=$link->query("SELECT `gy_employee`.`gy_emp_rate`AS`empryt`,`gy_employee`.`gy_emp_leave_credits`AS`emplvc` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_user`.`gy_user_id`='".$lyvusrrow['gy_user_id']."' LIMIT 1");
    $lyvusrrow=$rytsql->fetch_array();

if($dsrow['gy_leave_status']==0){$color="warning";}else if($dsrow['gy_leave_status']==1){$color="success  text-white";}else if($dsrow['gy_leave_status']==2){$color="danger";}else{$color="secondary";}
?>
<div class="card border-<?php echo $color ?>" style="max-width: 500px; margin: 0px;">
    <h4 class="card-header bg-<?php echo $color ?>"><?php if($dsrow['gy_leave_day']==1){echo"Full Day | ";}else if($dsrow['gy_leave_day']==0.5){echo"Half Day | ";} echo get_leave_type($dsrow['gy_leave_type']); ?></h4>
    <div class="card-body">
    	<label class="btn btn-block bg-secondary text-white"><span class="float-left"><?php echo getuserfullname($dsrow['gy_user_id'])."  "; ?></span><span class="float-right"><?php echo " | ".get_rate_type($lyvusrrow['empryt']); ?></span></label>
    	<div class="row">
    		<?php if($dsrow['gy_leave_status']==1){ ?><div class="col">
    			<button class="btn btn-outline-primary btn-block" onclick="publishloacfrm('<?php echo $dsrow['gy_leave_id']; ?>')"><i class="fas fa-cloud-upload-alt"></i> Publish</button>
    		<?php } ?></div>
    		<div class="col">
    			<button class="btn btn-outline-warning btn-block" onclick="chat_panel('<?php echo $dsrow['gy_leave_id']; ?>', 'loa', '<?php echo $user_code;?>')"><i class="far fa-comment"></i> Revalidate</button>
    		</div>
    	</div>
    </div>
    <div class="card-footer text-muted"><?php echo date("F j, Y", strtotime($dsrow['gy_leave_date_from'])) ?></div>
</div>
<?php
}else{ echo "switch"; } $link->close();
?>