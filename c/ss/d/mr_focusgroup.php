<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';
    if($myaccount == 22){
	include '../../../config/connnk.php';

?>
<div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text" id="">Enter Focus Group Name</span>
  </div>
  <input type="text" id="focusgroupinp" class="form-control" maxlength="30">
  <button class="btn btn-outline-secondary" id="subfgnm" onclick="addtolist(this)">ADD</button>
</div>
<div id="updatefg"></div>
<div class="table-responsive">
	<table class="table table table-bordered">
		<thead>
		    <tr>
			<th scope="col" class="minwid-120">Focus Group</th>
			<th scope="col">Remove</th>
			</tr>
		</thead>
		<tbody>
<?php $fglist=$dbticket->query("SELECT * From `focus_group` ORDER BY `id` ASC");
            while ($fgrow=$fglist->fetch_array()){ ?>
			<tr>
				<td style="padding: 0px;"><?php echo $fgrow['fg_name']; ?></td>
				<td style="padding: 0px;"><button class="btn btn-outline-danger btn-block btn-sm" onclick="upditems(this, 1)" name="fgbtn" id="<?php echo "fgdel_".$fgrow['id']; ?>"><i class="fa fa-trash"></i></button></td>
			</tr>
<?php } ?>
		</tbody>
	</table>
</div>

<?php $dbticket->close(); } $link->close(); ?>