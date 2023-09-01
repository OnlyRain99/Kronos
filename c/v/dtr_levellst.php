<select class="form-select " id="slt_2nd" onchange="sel_search()">
    <?php for($i=1;$i<=18;$i++){ ?>
    <option value="<?php echo $i; ?>" ><?php echo "Level ".$i; ?></option>
    <?php } ?>
</select>
<label for="slt_2nd">Select Level</label>