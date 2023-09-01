<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$stt = addslashes($_REQUEST['stt']);
$cha = addslashes($_REQUEST['cha']);

if($stt==0){ $sttsql = " `tool_status`=1 "; }
else if($stt==1){ $sttsql = " `tool_status`=0 "; }
else if($stt==2){ $sttsql = " `tool_status`>=0 AND `tool_status`<=1 "; }

$tolsql=$link->query("SELECT * From `tool_list` WHERE `tool_name` LIKE '%$cha%' AND ".$sttsql." ORDER BY `tool_id` desc");
    $count=$tolsql->num_rows;
    if($count>0){
    while($tolrow=$tolsql->fetch_array()){ ?>
<button onclick="setinpname(<?php echo $tolrow['tool_id']; ?>)" class="btn btn-outline-<?php if($tolrow['tool_status']==1){echo"secondary";}else{echo"danger";} ?> "><?php echo $tolrow['tool_name']; ?></button>
<?php }}else{ echo "<label>No Result</label>"; } $link->close(); ?>