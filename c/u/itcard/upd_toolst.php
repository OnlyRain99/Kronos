<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$toolname = addslashes($_REQUEST['toolname']);
if($toolname!=""){
    $link->query("INSERT INTO `tool_list`(`tool_name`, `tool_status`)values('$toolname', 1)");
}  ?>

<?php $tolsql=$link->query("SELECT * From `tool_list` WHERE `tool_status`=1 ORDER BY `tool_id` desc");
    while($tolrow=$tolsql->fetch_array()){ ?>
<button onclick="setinpname('<?php echo $tolrow['tool_id']; ?>')" class="btn btn-outline-<?php if($tolrow['tool_status']==1){echo"secondary";}else{echo"danger";} ?>"><?php echo $tolrow['tool_name']; ?></button>
<?php } $link->close(); ?>