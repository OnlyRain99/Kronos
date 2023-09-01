<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$bid = addslashes($_REQUEST['bid']);

$lstsql=$link->query("SELECT * From `tool_list` WHERE `tool_id`=$bid LIMIT 1");
$lstrow=$lstsql->fetch_array();
$toolid = $lstrow['tool_id'];
$toolname = $lstrow['tool_name'];
$toolstt = $lstrow['tool_status'];

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
<div class="card <?php if($toolstt!=1){echo"border-danger";} ?>" >
<div class="card-header" style="min-height: 48px;" onmouseover="showelem('toolactdea')" onmouseout="hideelem('toolactdea')">
        <Input type="text" id="tooldname" style="font-weight: bold;" class="form-noline" value="<?php echo $toolname; ?>" oninput="this.style.color='red'; showelem('updtoolname');">
        <button class="btn btn-outline-secondary btn-sm" style="display: none;" id="updtoolname" onclick="updtoolname(<?php echo $toolid; ?>)" title="Update Tool Name"><i class="fa-solid fa-pen-to-square"></i> </button>
        <button class="btn btn-outline-<?php if($toolstt==1){echo"success";}else{echo"danger";} ?> btn-sm" style="display: none;" id="toolactdea" title="Deactivate this Tool" onclick="actdea(<?php echo $toolid; ?>, <?php echo $toolstt; ?>)" ><?php if($toolstt==1){ ?><i class="fa-solid fa-eye"></i><?php }else{ ?><i class="fa-solid fa-eye-slash"></i><?php } ?></button>
</div>
<div style="max-height: 400px; height: 400px; overflow: auto;" id="tooldetailyst">
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
</div>
<div class="input-group">
    <input type="text" class="form-control" id="tooldlabel" placeholder="Enter Tool Detail">
    <select class="form-select" id="tooltype">
        <option value="text">Text Input Type</option>
        <option value="number">Number Input Type</option>
        <option value="email">Email Input Type</option>
        <option value="password">Password Input Type</option>
    </select>
    <button onclick="updtails(<?php echo $toolid; ?>)" class="btn btn-outline-secondary" title="Add New Tool Details"><i class="fa-solid fa-circle-plus"></i></button>
</div>
</div>