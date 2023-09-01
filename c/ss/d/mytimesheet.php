<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    $title = "My Timesheet";
?>

<!DOCTYPE html>
<html lang="en">
<?php  
    include 'head.php';
?>
<body class="">
	<div class="page-wrapper">
		<?php include 'header-m.php'; ?>
		<?php include 'sidebar.php'; ?>
		<div class="page-container">
			<div class="main-content" style="padding: 20px;">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<h2 class="title-1 m-b-25"> <?php echo $title; ?></h2>
						</div>
					</div>

					<!--<form method="post" enctype="multipart/form-data" action="export_timesheet" target="_blank" onsubmit="return validateForm(this);">-->
					<form method="post" enctype="multipart/form-data" action="xprt_tscsv" target="_blank" onsubmit="return validateForm(this);">
						<div class="row">
							 <div class="col-lg-3">
							 	<div class="form-group">
							 		<label>Date From</label>
							 		<input type="date" name="datefrom" id="datefrom" onchange="daterange()" class="form-control">
							 	</div>
							 </div>
							 <div class="col-lg-3">
							 	<div class="form-group">
							 		<label>Date To</label>
							 		<input type="date" name="dateto" id="dateto" onchange="daterange()" class="form-control">
							 	</div>
							 </div>
							 <div class="col-lg-4">
							 	<div class="form-group">
							 		<label>Department</label>
							 		<select class="form-control" name="tmsheetid" id="seldepartment">
							 			<option value="0">Select Department</option>
							 			<?php $getaccounts=$link->query("SELECT * From `gy_accounts` Order By `gy_acc_name` ASC");
                                              while ($accrow=$getaccounts->fetch_array()) { ?>
                                        <option value="<?= $accrow['gy_acc_id']; ?>"><?= $accrow['gy_acc_name']; ?></option>
                                        <?php } ?>
							 		</select>
							 	</div>
							 </div>
							 <div class="col-lg-1">
							 	<label><br></label>
							 	<btn onclick="procsearch()" class="btn btn-success btn-block"><i class="fas fa-search"></i></btn>
							 </div>
							 <div class="col-lg-1">
							 	<label><br></label>
							 	<button type="submit" onclick="procsearch()" name="timesheetsbmt" class="btn btn-success btn-block"><i class="fas fa-download"></i></button>
							 </div>
						</div>
					</form>
					<div class="row">
						<div class="col-lg-12">
							<div class="card">
								<div class="table-responsive m-b-40">
									<table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
										<thead>
											<tr>
												<th class="col-2 text-center">Date</th>
                                                <th class="col-4 text-center">Name</th>
                                                <th class="col-2 text-center">Login</th>
                                                <th class="col-2 text-center">Logout</th>
                                                <th class="col-2 text-center">Hours Worked</th>
											</tr>
										</thead>
										<tbody id="mytmsht"></tbody>
									</table>
								</div>
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
        function validateForm(formObj) {
            formObj.submit.disabled = true; 
            return true;
        }

        function daterange(){
            var from = _getID("datefrom").value;
            var to = _getID("dateto").value;

            if (from) {
                _getID("dateto").min = from;
            }

            if (to) {
                _getID("datefrom").max = to;
            }
        }

        function procsearch(){
        var tmsheetid = document.getElementById("seldepartment").options[document.getElementById("seldepartment").selectedIndex].text;
        var datefrom = document.getElementById("datefrom").value;
        var dateto = document.getElementById("dateto").value;
		var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
                document.getElementById("mytmsht").innerHTML = this.responseText;
            }
        };
        xhttp.open("POST", "search_timesheet.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("tmsheetid="+tmsheetid+"&datefrom="+datefrom+"&dateto="+dateto);
        }
    </script>
</body>
</html>