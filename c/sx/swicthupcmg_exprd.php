<?php
include '../../config/conn.php';

$swt = addslashes($_REQUEST['swt']);

if($swt==0){
$pg = (addslashes($_REQUEST['pg'])*10)-10;
if($pg>0){ $sqlpg = $pg.", 10"; }ELSE{ $sqlpg="10"; }
 $hcsql=$link->query("SELECT * FROM `gy_holiday_calendar` LEFT JOIN `gy_holiday_types` on `gy_holiday_calendar`.`gy_hol_type_id`=`gy_holiday_types`.`gy_hol_type_id` where `gy_holiday_calendar`.`gy_a_year`=1 and `gy_holiday_calendar`.`gy_hol_date`<'".date("Y-m-d")."' order by `gy_holiday_calendar`.`gy_hol_date` desc limit ".$sqlpg); while($hcrow=$hcsql->fetch_array()){ ?>
    <tr>
        <td><?php echo date("F d, Y", strtotime($hcrow['gy_hol_date'])); ?></td>
        <td><?php echo $hcrow['gy_hol_title']; ?></td>
        <td <?php if($hcrow['gy_hol_loc']<2){ ?>style="font-size: 12px;"<?php } ?> ><?php echo $hcrow['gy_hol_type_name']; if($hcrow['gy_hol_loc']==0){echo"(Tagum)";}else if($hcrow['gy_hol_loc']==1){echo"(Davao)";} ?></td>
        <td><button type="button" class="btn-close" aria-label="Close" disabled></button></td>
    </tr>
<?php } }else if($swt==1){
 $hcsql=$link->query("SELECT * FROM `gy_holiday_calendar` LEFT JOIN `gy_holiday_types` on `gy_holiday_calendar`.`gy_hol_type_id`=`gy_holiday_types`.`gy_hol_type_id` where `gy_holiday_calendar`.`gy_a_year`=1 and `gy_holiday_calendar`.`gy_hol_date`>='".date("Y-m-d")."' order by `gy_holiday_calendar`.`gy_hol_date` asc"); while($hcrow=$hcsql->fetch_array()){ ?>
    <tr>
        <td><?php echo date("F d, Y", strtotime($hcrow['gy_hol_date'])); ?></td>
        <td><?php echo $hcrow['gy_hol_title']; ?></td>
        <td <?php if($hcrow['gy_hol_loc']<2){ ?>style="font-size: 12px;"<?php } ?> ><?php echo $hcrow['gy_hol_type_name']; if($hcrow['gy_hol_loc']==0){echo"(Tagum)";}else if($hcrow['gy_hol_loc']==1){echo"(Davao)";} ?></td>
        <td><button type="button" class="btn-close" aria-label="Close" onclick="remevt(<?php echo "'".$hcrow['gy_hol_id']."', '".date("F d, Y", strtotime($hcrow['gy_hol_date']))."', '".$hcrow['gy_hol_title']."', 1, 0, 1"; ?>)"></button></td>
    </tr>
<?php } } $link->close(); ?>