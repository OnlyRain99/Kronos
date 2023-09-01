<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$dtld = addslashes($_REQUEST['dtld']);
$direct = addslashes($_REQUEST['direct']);

$dtlsql=$link->query("SELECT `toold_sortid`,`toold_listid` From `tool_details` WHERE `toold_id`='$dtld'");
$dtlrow=$dtlsql->fetch_array();
$sortlistid = $dtlrow['toold_sortid'];
$toolistid = $dtlrow['toold_listid'];

$i=0;
$higsql=$link->query("SELECT `toold_id`,`toold_sortid` From `tool_details` WHERE `toold_listid`='$toolistid' order by `toold_sortid` desc");

if($direct==0){ $newsortid = $sortlistid-1; }
else if($direct==1){ $newsortid = $sortlistid+1; }

while($higrow=$higsql->fetch_array()){
if($i==0){
    $highid = $higrow['toold_id'];
    $highsort = $higrow['toold_sortid'];
}
if($higrow['toold_sortid']==$sortlistid){
    $oldsid = $higrow['toold_id'];
}
if($higrow['toold_sortid']==$newsortid){
    $newsid = $higrow['toold_id'];
}
    $i++;
}

if($newsortid>=1 && $newsortid<=$highsort){
    $link->query("UPDATE `tool_details` SET `toold_sortid`='$newsortid' Where `toold_id`='$oldsid'  ");
    $link->query("UPDATE `tool_details` SET `toold_sortid`='$sortlistid' Where `toold_id`='$newsid'  ");
}

$i2 = 0; $dtlarr = array(array());
$dtlsql=$link->query("SELECT * From `tool_details` WHERE `toold_listid`=$toolistid ORDER BY `toold_sortid` asc");
while($dtlrow=$dtlsql->fetch_array()){
    $dtlarr[$i2][0] = $dtlrow['toold_id'];
    $dtlarr[$i2][1] = $dtlrow['toold_sortid'];
    $dtlarr[$i2][2] = $dtlrow['toold_label'];
    $dtlarr[$i2][3] = $dtlrow['toold_type'];
    $dtlarr[$i2][4] = $dtlrow['toold_status'];
    $i2++;
}

$link->close();
?>
<ul class="list-group">
<?php for($i1=0;$i1<$i2;$i1++){ ?>
  <li class="list-group-item" style="padding:0;" >
    <div class="input-group">
      <div class="btn-group-vertical">
        <button class="btn btn-outline-light btn-sm" <?php if($i1!=0){ ?> onclick="moveitem(<?php echo $dtlarr[$i1][0]; ?>, <?php echo $dtlarr[$i1][1]; ?>, 0)" title="Move Up" <?php } ?> ><i class="fa-solid fa-sort-up"></i></button>
        <button class="btn btn-outline-light btn-sm" <?php if($i1<($i2-1)){ ?> onclick="moveitem(<?php echo $dtlarr[$i1][0]; ?>, <?php echo $dtlarr[$i1][1]; ?>, 1)" title="Move Down" <?php } ?> ><i class="fa-solid fa-sort-down"></i></button>
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