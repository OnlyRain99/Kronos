<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

    $i = 0; $dptidarr = array(); $dptnmarr = array();
    $dptsql=$link->query("SELECT * From `gy_department` ORDER BY `id_department` ASC");
    while($dptrow=$dptsql->fetch_array()){
        $dptidarr[$i] = $dptrow['id_department'];
        $dptnmarr[$i] = $dptrow['name_department'];
        $i++;
    }
$link->close();
?>
<select class="form-select " id="slt_2nd" onchange="sel_search()">
    <?php for($i=0;$i<count($dptidarr);$i++){ ?>
    <option value="<?php echo $dptidarr[$i]; ?>" ><?php echo $dptnmarr[$i]; ?></option>
    <?php } ?>
</select>
<label for="slt_2nd">Select Department</label>