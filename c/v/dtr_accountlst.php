<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $i = 0; $accidarr = array(); $accnmarr = array();
    $accsql=$link->query("SELECT * From `gy_accounts` WHERE `gy_acc_status`=0 ORDER BY `gy_acc_name` ASC");
    while($accrow=$accsql->fetch_array()){
        $accidarr[$i] = $accrow['gy_acc_id'];
        $accnmarr[$i] = $accrow['gy_acc_name'];
        $i++;
    }
$link->close();
?>
<select class="form-select " id="slt_2nd" onchange="sel_search()">
    <?php for($i=0;$i<count($accidarr);$i++){ ?>
    <option value="<?php echo $accidarr[$i]; ?>" ><?php echo $accnmarr[$i]; ?></option>
    <?php } ?>
</select>
<label for="slt_2nd">Select Account</label>