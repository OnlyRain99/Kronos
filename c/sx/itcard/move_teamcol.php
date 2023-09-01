<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$id = addslashes($_REQUEST['id']);

if($id>0&&$id!=""){
    $teamarr=array();
    $tmsql = $link->query("SELECT * FROM `team_toollist` WHERE `team_id`='$id' LIMIT 1");
    $tmrow=$tmsql->fetch_array();
        $teamarr[0] = $tmrow['team_id'];
        $teamarr[1] = $tmrow['team_name'];
        $teamarr[2] = $tmrow['team_owner'];
        $teamarr[3] = $tmrow['team_switch'];
}
if($teamarr[2]==$user_code || $teamarr[3]==1){

if(isset($_REQUEST['sortid'])&&isset($_REQUEST['mov'])){
    $colord =  addslashes($_REQUEST['sortid']);
    $mov =  addslashes($_REQUEST['mov']);
    $newcolord = $colord; $i1=0;
    $higsql=$link->query("SELECT `col_id`,`col_order` From `team_collist` WHERE `team_id`=$teamarr[0] order by `col_order` desc");    
    if($mov==0){ $newcolord--; }else if($mov==1){ $newcolord++; }
    while($higrow=$higsql->fetch_array()){
        if($i1==0){
            $highsort = $higrow['col_order'];
        }
        if($higrow['col_order']==$colord){
            $oldsid = $higrow['col_id'];
        }
        if($higrow['col_order']==$newcolord){
            $newsid = $higrow['col_id'];
        }
    $i1++;
    }
    if($newcolord>=1 && $newcolord<=$highsort){
        $link->query("UPDATE `team_collist` SET `col_order`='$newcolord' Where `col_id`='$oldsid' ");
        $link->query("UPDATE `team_collist` SET `col_order`='$colord' Where `col_id`='$newsid' ");
    }
}

    $row = 0; $colarr=array(array());
    $colsql = $link->query("SELECT * FROM `team_collist` WHERE `team_id`=$teamarr[0] ORDER BY `col_order` asc");
    while($colrow=$colsql->fetch_array()){
        $colarr[$row][0] = $colrow['col_id'];
        $colarr[$row][1] = $colrow['col_val'];
        $colarr[$row][2] = $colrow['col_type'];
        $colarr[$row][3] = $colrow['col_status'];
        $colarr[$row][4] = $colrow['col_order'];
        $row++;
    }
?>
<ul class="list-group">
<?php for($i=0;$i<$row;$i++){ ?>
  <li class="list-group-item" style="padding:0;" >
    <div class="input-group">
      <div class="btn-group-vertical">
        <button class="btn btn-outline-light btn-sm" <?php if($i>0){ ?> onclick="moveitem(<?php echo $teamarr[0]; ?>, <?php echo $colarr[$i][4]; ?>, 0)" title="Move Up" <?php } ?> ><i class="fa-solid fa-sort-up"></i></button>
        <button class="btn btn-outline-light btn-sm" <?php if($i<($row-1)){ ?> onclick="moveitem(<?php echo $teamarr[0]; ?>, <?php echo $colarr[$i][4]; ?>, 1)" title="Move Down" <?php } ?> ><i class="fa-solid fa-sort-down"></i></button>
      </div>
        <input class="form-control" placeholder="Enter Column Name" value="<?php echo $colarr[$i][1]; ?>" oninput="uptinpt(this)" id="inpdtl_<?php echo $colarr[$i][0]; ?>">
        <select class="form-select" id="seldtl_<?php echo $colarr[$i][0]; ?>" onchange="uptinpt(this)">
            <option value="text" <?php if($colarr[$i][2]=="text"){echo "selected";}?>>Text Input Type</option>
            <option value="number" <?php if($colarr[$i][2]=="number"){echo "selected";}?>>Number Input Type</option>
            <option value="email" <?php if($colarr[$i][2]=="email"){echo "selected";}?>>Email Input Type</option>
            <option value="password" <?php if($colarr[$i][2]=="password"){echo "selected";}?>>Password Input Type</option>
        </select>
        <button class="btn btn-outline-secondary btn-sm" id="<?php echo "btndtlupd_".$colarr[$i][0]; ?>" title="Update this Item" onclick="updtooldtl(this)" style="display: none;"><i class="fa-solid fa-pen-to-square"></i></button>
        <button class="btn btn-outline-<?php if($colarr[$i][3]==1){echo"success";}else{echo"danger";}?> btn-sm" id="btndtlhid_<?php echo $colarr[$i][0]; ?>" onclick="changedtlstatus(this)" title="<?php if($colarr[$i][3]==1){echo"Click to Hide";}else{echo"Click to Show";} ?>"><?php if($colarr[$i][3]==1){?><i class="fa-solid fa-eye"></i><?php }else{?><i class="fa-solid fa-eye-slash"></i><?php } ?></button>
    </div>
  </li>
<?php } ?>
</ul>
<?php } $link->close(); ?>
