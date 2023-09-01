<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$label = addslashes($_REQUEST['label']);
$type = addslashes($_REQUEST['type']);
$toolid = addslashes($_REQUEST['toolid']);
$sortid = 1;
$detsql=$link->query("SELECT `toold_sortid` From `tool_details` WHERE `toold_listid`=$toolid ORDER BY `toold_sortid` desc");
$count=$detsql->num_rows;
if($count>0){
$detrow=$detsql->fetch_array();
$sortid = $detrow['toold_sortid']+1;
}
if($label!=""){
    $link->query("INSERT INTO `tool_details`(`toold_sortid`, `toold_listid`, `toold_label`, `toold_type`, `toold_status`)values('$sortid', '$toolid', '$label', '$type', 1)");
}

$i = 0; $dtlarr = array(array());
$dtlsql=$link->query("SELECT * From `tool_details` WHERE `toold_listid`=$toolid ORDER BY `toold_sortid` asc");
while($dtlrow=$dtlsql->fetch_array()){
    $dtlarr[$i][0] = $dtlrow['toold_id'];
    $dtlarr[$i][1] = $dtlrow['toold_sortid'];
    $dtlarr[$i][2] = $dtlrow['toold_label'];
    $dtlarr[$i][3] = $dtlrow['toold_type'];
    $dtlarr[$i][4] = $dtlrow['toold_status'];
    $i++;
}

$link->close();
?>

<ul class="list-group">
<?php for($i1=0;$i1<$i;$i1++){ ?>
  <li class="list-group-item" style="padding:0;" >
    <div class="input-group">
      <div class="btn-group-vertical">
        <button class="btn btn-outline-light btn-sm" <?php if($i1!=0){ ?> onclick="moveitem(<?php echo $dtlarr[$i1][0]; ?>, <?php echo $dtlarr[$i1][1]; ?>, 0)" title="Move Up" <?php } ?> ><i class="fa-solid fa-sort-up"></i></button>
        <button class="btn btn-outline-light btn-sm" <?php if($i1<($i-1)){ ?> onclick="moveitem(<?php echo $dtlarr[$i1][0]; ?>, <?php echo $dtlarr[$i1][1]; ?>, 1)" title="Move Down" <?php } ?> ><i class="fa-solid fa-sort-down"></i></button>
      </div>

        <input type="text" class="form-control" placeholder="Tool Label" oninput="uptinpt(this, '<?php echo "btndtlupd_".$dtlarr[$i1][0]; ?>')" id="<?php echo"inpdtl_".$dtlarr[$i1][0]; ?>" value="<?php echo $dtlarr[$i1][2]; ?>">
        <select class="form-select" oninput="uptinpt(this, '<?php echo "btndtlupd_".$dtlarr[$i1][0]; ?>')" id="<?php echo"seldtl_".$dtlarr[$i1][0]; ?>" >
            <option value="text" <?php if($dtlarr[$i1][3]=="text"){echo "selected";} ?>>Text Input Type</option>
            <option value="number" <?php if($dtlarr[$i1][3]=="number"){echo "selected";} ?>>Number Input Type</option>
            <option value="email" <?php if($dtlarr[$i1][3]=="email"){echo "selected";} ?>>Email Input Type</option>
            <option value="password" <?php if($dtlarr[$i1][3]=="password"){echo "selected";} ?>>Password Input Type</option>
        </select>

        <button class="btn btn-outline-secondary btn-sm" id="<?php echo "btndtlupd_".$dtlarr[$i1][0]; ?>" title="Update this Item" onclick="updtooldtl(this)" style="display: none;"><i class="fa-solid fa-pen-to-square"></i></button>

        <button class="btn btn-outline-<?php if($dtlarr[$i1][4]==1){echo"success";}else{echo"danger";} ?> btn-sm" id="<?php echo "btndtlhid_".$dtlarr[$i1][0]; ?>" title="Disable this Item" onclick="changedtlstatus(this)"><?php if($dtlarr[$i1][4]==1){ ?><i class="fa-solid fa-eye"></i><?php }else{ ?><i class="fa-solid fa-eye-slash"></i><?php } ?></button>

    </div>
  </li>
<?php } ?>
</ul>