<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

if ($user_type == 6 && $user_dept == 2) {

$evtnm = addslashes($_REQUEST['evtnm']);
$opt = addslashes($_REQUEST['opt']);
$whrsql = "";
if($evtnm!="" && $opt==0){
    $link->query("INSERT INTO `gy_holiday_types`(`gy_hol_type_name`)values('$evtnm')");
}
if($evtnm!="" && $opt==1){
    $whrsql=" WHERE `gy_hol_type_name` LIKE '%$evtnm%' ";
}
if($evtnm=="" && $opt==2){
    $id = addslashes($_REQUEST['evtid']);
    $link->query("DELETE FROM `gy_holiday_types` WHERE `gy_hol_type_id`!=1 AND `gy_hol_type_id`!=2 AND `gy_hol_type_id`=$id");
}
$row=0; $holtarr = array(array());
$htsql=$link->query("SELECT `gy_hol_type_id`,`gy_hol_type_name`,`gy_hol_status` FROM `gy_holiday_types` ".$whrsql);
while($htrow=$htsql->fetch_array()){
    $holtarr[$row][0] = $htrow['gy_hol_type_id'];
    $holtarr[$row][1] = $htrow['gy_hol_type_name'];
    $holtarr[$row][2] = $htrow['gy_hol_status'];
    $row++;
}
for($i=0;$i<$row;$i++){
?>
<tr>
<tr>
    <td><a href="#" onclick="readpol(this)" id="evtnma_<?php echo $holtarr[$i][0]; ?>"><?php echo $holtarr[$i][1]; ?></a></td>
    <td><?php if($holtarr[$i][2]==1){ ?><i class="fa-solid fa-check-to-slot text-success" title="Active"></i><?php }else if($holtarr[$i][2]==0){ ?><i class="fa-sharp fa-solid fa-ban text-danger" title="Inactive"></i><?php } ?></td>
    <td><button type="button" class="btn-close" aria-label="Close" title="Delete" <?php if($holtarr[$i][0]==1||$holtarr[$i][0]==2){echo"disabled";}?> onclick="deleteevtnm('<?php echo $holtarr[$i][0]; ?>')" ></button></td>
</tr>
<?php } } $link->close(); ?>