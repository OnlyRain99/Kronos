<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';
?>
	<div class="col-md-6">
		<div class="card">
				<div class="input-group">
					<input type="text" class="form-control input-group-lg minwid-120" id="toolnameid" placeholder="Enter Tool Name">
					<button class="btn btn-outline-secondary btn-lg" title="Add New Tool" onclick="sendpost('lstoftool', 'itcard/upd_toolst.php', 'toolname='+document.getElementById('toolnameid').value); document.getElementById('toolnameid').value=''; uptoolst();"><i class="fa-solid fa-circle-plus"></i></button>
				</div>
				<div style="max-height: 400px; height: 400px; overflow: auto;" id="lstoftool">
<?php $tolsql=$link->query("SELECT * From `tool_list` WHERE `tool_status`=1 ORDER BY `tool_id` desc");
    while($tolrow=$tolsql->fetch_array()){ ?>
<button onclick="setinpname('<?php echo $tolrow['tool_id']; ?>')" class="btn btn-outline-<?php if($tolrow['tool_status']==1){echo"secondary";}else{echo"danger";} ?> "><?php echo $tolrow['tool_name']; ?></button>
<?php } $link->close(); ?>
				</div>
			  <div class="input-group">
                    <select class="form-select" id="toolstt" onchange="uptoolst()">
                        <option value="2">All</option>
                        <option value="0" selected>Active</option>
                        <option value="1">Deactivated</option>
                    </select>
    				<input type="text" class="form-control" id="toolnms" placeholder="Search Tool Name" onkeyup="uptoolst()">
             </div>
		</div>
	</div>
	<div class="col-md-6" id="tooldsply"></div>