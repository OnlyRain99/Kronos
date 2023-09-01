<?php
    include '../../config/conn.php';

$remid = addslashes($_REQUEST['remid']);
$cal = addslashes($_REQUEST['col']);

if($remid!=""&&$cal==0){
    $date2day = date("Y-m-d");
    $link->query("UPDATE `gy_holiday_calendar` SET `gy_hol_lastday`='$date2day' Where `gy_hol_id`='$remid'");

 $hcsql=$link->query("SELECT * FROM `gy_holiday_calendar` LEFT JOIN `gy_holiday_types` on `gy_holiday_calendar`.`gy_hol_type_id`=`gy_holiday_types`.`gy_hol_type_id` where `gy_holiday_calendar`.`gy_a_year`=0 AND `gy_holiday_calendar`.`gy_hol_lastday`='0000-00-00' order by month(`gy_holiday_calendar`.`gy_hol_date`), day(`gy_holiday_calendar`.`gy_hol_date`) asc"); while($hcrow=$hcsql->fetch_array()){ ?>
    <tr>
        <td><?php echo date("F d", strtotime($hcrow['gy_hol_date'])); ?></td>
        <td><?php echo $hcrow['gy_hol_title']; ?></td>
        <td><?php echo $hcrow['gy_hol_type_name']; ?></td>
        <td><button type="button" class="btn-close" aria-label="Close" onclick="remevt(<?php echo "'".$hcrow['gy_hol_id']."', '".date("F d", strtotime($hcrow['gy_hol_date']))."', '".$hcrow['gy_hol_title']."', 0, 0, 1"; ?>)"></button></td>
    </tr>
<?php }
}else if($remid!=""&&$cal==1){
    $link->query("DELETE FROM `gy_holiday_calendar` Where `gy_hol_id`='$remid'");

$expr = addslashes($_REQUEST['expr']);
$swt = addslashes($_REQUEST['swt']);
if($expr>0){ $sqlpg = ", ".$expr; }ELSE{ $sqlpg=""; }
if($swt==0){ $sqlst = "and `gy_holiday_calendar`.`gy_hol_date`<'".date("Y-m-d")."' order by `gy_holiday_calendar`.`gy_hol_date` desc LIMIT 10".$sqlpg; }
else if($swt==1){ $sqlst = "and `gy_holiday_calendar`.`gy_hol_date`>='".date("Y-m-d")."' order by `gy_holiday_calendar`.`gy_hol_date` asc"; }
    
 $hcsql=$link->query("SELECT * FROM `gy_holiday_calendar` LEFT JOIN `gy_holiday_types` on `gy_holiday_calendar`.`gy_hol_type_id`=`gy_holiday_types`.`gy_hol_type_id` where `gy_holiday_calendar`.`gy_a_year`=1 ".$sqlst); while($hcrow=$hcsql->fetch_array()){ ?>
    <tr>
        <td><?php echo date("F d, Y", strtotime($hcrow['gy_hol_date'])); ?></td>
        <td><?php echo $hcrow['gy_hol_title']; ?></td>
        <td><?php echo $hcrow['gy_hol_type_name']; ?></td>
        <td><button type="button" class="btn-close" aria-label="Close" onclick="remevt(<?php echo "'".$hcrow['gy_hol_id']."', '".date("F d, Y", strtotime($hcrow['gy_hol_date']))."', '".$hcrow['gy_hol_title']."', 1, ".$expr.", ".$swt; ?>)"></button></td>
    </tr>
<?php }
} $link->close(); ?>