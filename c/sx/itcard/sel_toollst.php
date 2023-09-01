<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$i = 0; $tlstarr = array(array());
$tlstsql = $link->query("SELECT * FROM `tool_list` WHERE `tool_status`=1 ORDER BY `tool_name` asc");
    while($tlstrow=$tlstsql->fetch_array()){
        $tlstarr[$i][0] = $tlstrow['tool_id'];
        $tlstarr[$i][1] = $tlstrow['tool_name'];
        $i++;
    }

$link->close(); ?>
<option value="">All</option>
<?php for($i1=0;$i1<$i;$i1++){ ?>
<option value="<?php echo $tlstarr[$i1][0]; ?>"><?php echo $tlstarr[$i1][1]; ?></option>
<?php } ?>