<?php
    include '../../config/conn.php';

$name = addslashes($_REQUEST['name']);
$type = addslashes($_REQUEST['type']);
$cal = addslashes($_REQUEST['cal']);
$loc = addslashes($_REQUEST['hloc']);
if($loc>2){$loc=0;}
if($name!=""&&$type!=""&&$cal==0){
    $month = addslashes($_REQUEST['month']);
    $day = addslashes($_REQUEST['day']);
if($month!=""&&$day!=""){
$gydate = date("Y-m-d", strtotime(date("Y")."-".$month."-".$day));
    $link->query("INSERT INTO `gy_holiday_calendar`(`gy_hol_title`,`gy_hol_type_id`,`gy_hol_date`,`gy_a_year`,`gy_hol_loc`)Values('$name','$type','$gydate',0,$loc)");

 $hcsql=$link->query("SELECT * FROM `gy_holiday_calendar` LEFT JOIN `gy_holiday_types` on `gy_holiday_calendar`.`gy_hol_type_id`=`gy_holiday_types`.`gy_hol_type_id` where `gy_holiday_calendar`.`gy_a_year`=0 order by month(`gy_holiday_calendar`.`gy_hol_date`), day(`gy_holiday_calendar`.`gy_hol_date`) asc"); while($hcrow=$hcsql->fetch_array()){ ?>
    <tr>
        <td><?php echo date("F d", strtotime($hcrow['gy_hol_date'])); ?></td>
        <td><?php echo $hcrow['gy_hol_title']; ?></td>
        <td <?php if($hcrow['gy_hol_loc']<2){ ?>style="font-size: 12px;"<?php } ?> ><?php echo $hcrow['gy_hol_type_name']; if($hcrow['gy_hol_loc']==0){echo"(Tagum)";}else if($hcrow['gy_hol_loc']==1){echo"(Davao)";} ?></td>
        <td><button type="button" class="btn-close" aria-label="Close" onclick="remevt(<?php echo "'".$hcrow['gy_hol_id']."', '".date("F d", strtotime($hcrow['gy_hol_date']))."', '".$hcrow['gy_hol_title']."', 0, 0, 1"; ?>)"></button></td>
    </tr>
<?php }}
}else if($name!=""&&$type!=""&&$cal==1){
    $dateoo = addslashes($_REQUEST['dateoo']);
if($dateoo!="" && $dateoo>=date("Y-m-d")){
    $link->query("INSERT INTO `gy_holiday_calendar`(`gy_hol_title`,`gy_hol_type_id`,`gy_hol_date`,`gy_a_year`,`gy_hol_loc`)Values('$name','$type','$dateoo',1,$loc)");

 $hcsql=$link->query("SELECT * FROM `gy_holiday_calendar` LEFT JOIN `gy_holiday_types` on `gy_holiday_calendar`.`gy_hol_type_id`=`gy_holiday_types`.`gy_hol_type_id` where `gy_holiday_calendar`.`gy_a_year`=1 and `gy_holiday_calendar`.`gy_hol_date`>='".date("Y-m-d")."' order by `gy_holiday_calendar`.`gy_hol_date` asc"); while($hcrow=$hcsql->fetch_array()){ ?>
    <tr>
        <td><?php echo date("F d, Y", strtotime($hcrow['gy_hol_date'])); ?></td>
        <td><?php echo $hcrow['gy_hol_title']; ?></td>
        <td <?php if($hcrow['gy_hol_loc']<2){ ?>style="font-size: 12px;"<?php } ?> ><?php echo $hcrow['gy_hol_type_name']; if($hcrow['gy_hol_loc']==0){echo"(Tagum)";}else if($hcrow['gy_hol_loc']==1){echo"(Davao)";} ?></td>
        <td><button type="button" class="btn-close" aria-label="Close" onclick="remevt(<?php echo "'".$hcrow['gy_hol_id']."', '".date("F d, Y", strtotime($hcrow['gy_hol_date']))."', '".$hcrow['gy_hol_title']."', 1, 0, 1"; ?>)"></button></td>
    </tr>
<?php }
}} $link->close(); ?>