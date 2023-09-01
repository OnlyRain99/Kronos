<?php
$col = addslashes($_REQUEST['col']);
if($col==1){
?>
  	<select class="form-select" id="selperm" onchange="addandloadey('updsel','calendar_updsel','col=0')">
        <option value="0" selected>Every Year</option>
        <option value="1">Once Only</option>
    </select>
    
    <select class="form-select" id="selpermm">
        <?php for($i=1;$i<=12;$i++){ ?>
        <option value="<?php echo $i; ?>" <?php if($i==date("m")){ echo "selected"; } ?>><?php echo date("F", mktime(0,0,0,$i,10)); ?></option>
        <?php } ?>
    </select>

    <select class="form-select" id="selpermd">
        <?php for($i=1;$i<=31;$i++){ ?><option value="<?php echo $i;?>" <?php if($i==date("d")){ echo "selected"; } ?>><?php echo $i;?></option><?php } ?>
    </select>

    <btn class="btn btn-primary" onclick="plotevt()"><i class="fas fa-flag"></i> PLOT</btn>

<?php }else if($col==0){ ?>
  	<select class="form-select" id="selperm" onchange="addandloadey('updsel','calendar_updsel','col=1')">
        <option value="0" >Every Year</option>
        <option value="1" selected>Once Only</option>
    </select>

	<input type="date" id="oodate" min="<?php echo date("Y-m-d");?>" onchange="daterange()" class="form-control" required>

    <btn class="btn btn-primary" onclick="plotevt()"><i class="fas fa-flag"></i> PLOT</btn>
<?php } ?>