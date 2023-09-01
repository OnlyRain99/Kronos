<?php
include '../../config/conn.php';

$details = addslashes($_REQUEST['details']);
$ip = addslashes($_REQUEST['ip']);
if($details!=""&&$ip!=""){
	$link->query("INSERT INTO `gy_whitelist`(`details`,`ip`)Values('$details','$ip')");
}

$wlsql=$link->query("SELECT * From `gy_whitelist` ORDER BY `id` desc");
while ($wlrow=$wlsql->fetch_array()){
?>
	<tr class="mybg">
		<td style="padding: 4px;" class="text-center text-nowrap"><?php echo $wlrow['details']; ?></td>
    	<td style="padding: 4px;" class="text-center text-nowrap"><?php echo $wlrow['ip']; ?></td>
    	<td style="padding: 0px;"><button class="btn btn-sm btn-danger btn-block" id="<?php echo "remo_".$wlrow['id']; ?>" onclick="upd_removeip(this)" title="Do not allow this IP"><i class='fas fa-trash-alt'></i></button></td>
    </tr>
<?php } $link->close();  ?>