<?php
    include '../../../config/conn.php';
    $dprtid = addslashes($_REQUEST['dprtid']);
    $getaccounts=$link->query("SELECT * From `gy_accounts` Where `gy_dept_id`='$dprtid' and `gy_acc_status`=0 Order By `gy_acc_name` ASC");
    while($accrow=$getaccounts->fetch_array()){ ?>
	<option value="<?php echo $accrow['gy_acc_id']; ?>"><?php echo $accrow['gy_acc_name']; ?></option>
<?php } $link->close(); ?>