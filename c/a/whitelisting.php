<?php
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "Whitelisting";  

?>

<!DOCTYPE html>
<html lang="en">
<?php  include 'head.php'; ?>
	<body>
		<div class="page-wrapper">
        	<?php include 'header-m.php'; ?>
        	<?php include 'sidebar.php'; ?>
        	<div class="page-container">
        		<div class="main-content" style="padding: 20px;">
        			<div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="title-1 m-b-25"><?php echo $title; ?> <i class="fa fa-compass"></i></h2>
                        </div>
                    </div>

                    <div class="row">
                    	<div class="col-md-6">
                    		<div class="card">
                    			<div class="card-header">
                                    <strong class="card-title mb-3">
										<span class="pull-left"><i class='fas fa-building'></i></span>
                                    	<span class="pull-right">IP Whitelist</span></strong>
                                </div>
								<div class="card-body">
                                	<div class="input-group">
                                	<input class="form-control" id="inpdetails" onkeyup="if_enable()" placeholder="Enter Details" maxlength="99">
                                	<input class="form-control" id="inpip" onkeyup="if_enable()" placeholder="Enter IP Address" maxlength="19">
                                	<button class="btn btn-success" onclick="upd_whitelist()" id="btnip" title="Add to the Allowed List" disabled><i class="fas fa-plus"></i></button>
                                	</div>
								</div>
                                	<table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                		<thead>
                                			<tr class="mybg" style="text-transform: uppercase;">
                                				<th style="padding: 5px;" class="text-center">Details</th>
                                				<th style="padding: 5px;" class="text-center">IP</th>
                                				<th style="padding: 0px;"><button class="btn btn-sm btn-block" title="Remove"><i class='fas fa-trash'></i></button></th>
                                			</tr>
                                		</thead>
                                		<tbody id="wfo_dynamic">
                                			<?php
                                				$wlsql=$link->query("SELECT * From `gy_whitelist` ORDER BY `id` desc");
    											while ($wlrow=$wlsql->fetch_array()){
                                			?>
                                			<tr class="mybg">
                                				<td style="padding: 4px;" class="text-center text-nowrap"><?php echo $wlrow['details']; ?></td>
    											<td style="padding: 4px;" class="text-center text-nowrap"><?php echo $wlrow['ip']; ?></td>
    											<td style="padding: 0px;"><button class="btn btn-sm btn-danger btn-block" id="<?php echo "remo_".$wlrow['id']; ?>" onclick="upd_removeip(this)" title="Do not allow this IP"><i class='fas fa-trash-alt'></i></button></td>
                                			</tr>
                                			<?php } ?>
                                		</tbody>
                                	</table>
                            </div>
                    	</div>

                    	<div class="col-md-6">
                    		<div class="card">
                    			<div class="card-header">
                                    <strong class="card-title mb-3">
										<span class="pull-left"><i class='fas fa-home'></i></span>
                                    	<span class="pull-right">Access Anywhere</span></strong>
                                </div>
                                	<table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                		<thead>
                                			<tr class="mybg" style="text-transform: uppercase;">
                                				<th style="padding: 5px;" class="text-center">Name</th>
                                				<th style="padding: 5px;" class="text-center">Account</th>
                                			</tr>
                                		</thead>
                                		<tbody>
                                			<?php
    											$getemp=$link->query("SELECT `gy_emp_fullname`,`gy_emp_account` From `gy_employee` WHERE `gy_work_from`=1 ORDER BY `gy_emp_account` ASC");
    											while ($emprow=$getemp->fetch_array()){ ?>
    										<tr class="mybg">
    											<td style="padding: 1px;" class="text-center text-nowrap"><?php echo $emprow['gy_emp_fullname']; ?></td>
    											<td style="padding: 1px;" class="text-center text-nowrap"><?php echo $emprow['gy_emp_account']; ?></td>
    										</tr>
    										<?php } $link->close(); ?>
                                		</tbody>
                                	</table>
                    		</div>
                    	</div>
                    </div>
                    <?php include 'footer.php'; ?>
        			</div>
        		</div>
        	</div>
		</div>
		<?php include 'scripts.php'; ?>
		<script type="text/javascript">
			function upd_whitelist(){
				var details = document.getElementById("inpdetails").value;
				var ip = document.getElementById("inpip").value;
				if(details!="" && ip!=""){
					var xhttp = new XMLHttpRequest();
                	xhttp.onreadystatechange = function(){
                	if (this.readyState == 4 && this.status == 200){
                    	document.getElementById("wfo_dynamic").innerHTML = this.responseText;
                    	document.getElementById("inpdetails").value="";
                    	document.getElementById("inpip").value="";
                        document.getElementById("btnip").disabled = true;
                	}};
                	xhttp.open("POST", "newip.php", true);
                	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                	xhttp.send("details="+details+"&ip="+ip);
				}
			}

			function upd_removeip(elem){
				var btnid = elem.id.substring(elem.id.indexOf("_")+1);
				Swal.fire({
            		title: 'Remove this IP?',
            		Text: 'Confirmation',
            		icon: 'warning',
            		showCancelButton: true,
            		confirmButtonColor: '#3085d6',
            		cancelButtonColor: '#d33',
            		confirmButtonText: 'Yes, Proceed',
            		width: 'auto'
        			}).then((result) => {
        				if (result.isConfirmed && btnid!=""){
							var xhttp = new XMLHttpRequest();
                			xhttp.onreadystatechange = function(){
                			if (this.readyState == 4 && this.status == 200){
                    			document.getElementById("wfo_dynamic").innerHTML = this.responseText;
                			}};
                			xhttp.open("POST", "removeip.php", true);
                			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                			xhttp.send("btnid="+btnid);	
        				}
        			})
			}

			function if_enable(){
				var details = document.getElementById("inpdetails").value;
				var ip = document.getElementById("inpip").value;
				if(details!="" && ip!=""){
					document.getElementById("btnip").disabled = false;
				}else{
					document.getElementById("btnip").disabled = true;
				}
			}
		</script>
	</body>
</html>