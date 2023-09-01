<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';
    if($myaccount == 22){
	include '../../../config/connnk.php';

?>
<div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text" id="">Enter Shop Name</span>
  </div>
  <input type="text" id="shopname" class="form-control" maxlength="99">
  <button class="btn btn-outline-secondary" id="subshpnm" onclick="addtolist(this)">ADD</button>
</div>
<div id="updateshop"></div>
<div class="table-responsive">
	<table class="table table table-bordered">
		<thead>
		    <tr>
			<th scope="col" class="minwid-120">Shop</th>
			<th scope="col">Status</th>
			<th scope="col">Remove</th>
			</tr>
		</thead>
		<tbody>
<?php $shoplist=$dbticket->query("SELECT * From `shops` ORDER BY `id` ASC");
            while ($shoprow=$shoplist->fetch_array()){ ?>
			<tr>
				<td style="padding: 0px;"><?php echo $shoprow['shop_name']; ?></td>
				<td style="padding: 0px;"><?php if($shoprow['shop_status']==1){ ?><button class="btn btn-outline-success btn-block btn-sm" onclick="upditems(this, 0)" name="shopbtn" id="<?php echo "shop_".$shoprow['id']; ?>"><i class="fa fa-check-square"></i></button><?php }else{ ?><button class="btn btn-outline-danger btn-block btn-sm" onclick="upditems(this, 0)" name="shopbtn" id="<?php echo "shop_".$shoprow['id']; ?>"><i class="fa fa-square"></i></button><?php } ?></td>
				<td style="padding: 0px;"><button class="btn btn-outline-danger btn-block btn-sm" onclick="upditems(this, 1)" name="shopbtn" id="<?php echo "shopdel_".$shoprow['id']; ?>"><i class="fa fa-trash"></i></button></td>
			</tr>
<?php } ?>
		</tbody>
	</table>
</div>

<?php $dbticket->close(); } $link->close(); ?>