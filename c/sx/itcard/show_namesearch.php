<?php 
$stype = addslashes($_REQUEST['eval']);
if($stype==0){ ?>
    <select class="form-select" id="shw_a2z" onchange="showstartc(this, 1)">
        <option value="">All</option>
            <?php for($i=65;$i<91;$i++){ ?>
        <option value="<?php echo strtolower(chr($i)); ?>"><?php echo strtoupper(chr($i)); ?></option>
            <?php } ?>
    </select>
    <label for="shw_a2z">Show</label>
<?php }else if($stype==1){ ?>
    <input type="text" class="form-control" id="shw_a2z" placeholder="Search Name" onkeyup="showstartc(this, 1)">
    <label for="shw_a2z">Search Name</label>
<?php } ?>