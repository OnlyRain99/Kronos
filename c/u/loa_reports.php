<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "LOA Report";

 if($user_type == 1 && $user_dept == 2){
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>
<body>
    <div class="page-wrapper">
    <?php include 'header-m.php'; ?>
    <?php include 'sidebar.php'; ?>
        <div class="page-container">
            <div class="main-content p-t-20" >
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                        <h2 class="title-1 m-b-25"><?php echo $title; ?> <i class="fa-solid fa-file-export"></i></h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">

                                    <div class="input-group">
                                        <div class="form-floating">
											<input type="date" name="from" id="datefrom" onchange="daterange()" class="form-control" required>
											<label class="datefrom">Date From</label>
                                        </div>
										<div class="form-floating">
											<input type="date" name="to" id="dateto" onchange="daterange()" class="form-control" required>
											<label class="dateto">Date To</label>
										</div>

                                        <div class="form-floating">
                                            <select class="form-select" id="slt_apnd" onchange="chngtbl()">
                                                <option value="0">All</option>
                                                <option value="1" selected>Approved Request</option>
                                                <option value="2">Pending Request</option>
                                            </select>
                                        <label for="slt_apnd">Request Status</label>
                                        </div>

                                        <div class="form-floating">
                                            <select class="form-select" id="slt_pbsh" onchange="chngtbl()">
                                                <option value="1" selected>Published</option>
                                                <option value="0">Pending</option>
                                            </select>
                                        <label for="slt_apnd">Status</label>
                                        </div>

                                        <button class="btn btn-lg" id="rfrsh" onclick="chngtbl()"><i class="fas fa-sync faa-wrench animated faa-slow"></i></button>
										<button class="btn btn-lg float-right" title="Download" id="dwnld" onclick="dlemprep()"><i class="fas fa-download faa-float animated faa-slow"></i></button>
                                    </div>

										<div class="table-responsive">
                                        <table class="table table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                            <thead>
                                                <tr>
                                                    <th scope="col" style="padding: 2px;" class="text-center text-nowrap">Requested Date</th>
                                                    <th scope="col" style="padding: 2px;" class="text-center text-nowrap">SIBS ID</th>
                                                    <th scope="col" style="padding: 2px;" class="text-center text-nowrap">Name</th>
                                                    <th scope="col" style="padding: 2px;" class="text-center text-nowrap">Requested Type</th>
                                                    <th scope="col" style="padding: 2px;" class="text-center text-nowrap">Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tblloa"></tbody>
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
		function daterange(){
			var from = _getID("datefrom").value;
			var to = _getID("dateto").value;
			if (from) { _getID("dateto").min = from; }
			if (to) { _getID("datefrom").max = to; }
			chngtbl();
		}

    function dlemprep(){
		document.getElementById("rfrsh").innerHTML = '<i class="fas fa-sync faa-spin animated faa-fast"></i>';
        document.getElementById("dwnld").innerHTML = '<i class="fas fa-download faa-bounce faa-reverse faa-fast animated"></i>';
        document.getElementById("rfrsh").disabled = true;
        document.getElementById("dwnld").disabled = true;
			var dfro = document.getElementById("datefrom").value;
			var dato = document.getElementById("dateto").value;
			var pars = document.getElementById("slt_apnd").value;
			var pbsh = document.getElementById("slt_pbsh").value;
        if(dfro!="" && dato!="" && pars!="" && pbsh!=""){
            var urlx = "dfro="+dfro+"&dato="+dato+"&pars="+pars+"&pbsh="+pbsh;
            var wintmp = window.open("requestform/loareports_csv?"+urlx, "_blank");
            wintmp.onload = function(){
                wintmp.onunload = function () {
					document.getElementById("rfrsh").innerHTML = '<i class="fas fa-sync faa-wrench animated faa-slow"></i>';
                    document.getElementById("dwnld").innerHTML = '<i class="fas fa-download faa-float animated faa-slow"></i>';
                    document.getElementById("rfrsh").disabled = false;
                    document.getElementById("dwnld").disabled = false;
                };
            }
        }
    }

		function chngtbl(){
			document.getElementById("rfrsh").innerHTML = '<i class="fas fa-sync faa-spin animated faa-fast"></i>';
			document.getElementById("dwnld").innerHTML = '<i class="fas fa-download faa-bounce faa-reverse faa-fast animated"></i>';
			document.getElementById("rfrsh").disabled = true;
			document.getElementById("dwnld").disabled = true;
			var dfro = document.getElementById("datefrom").value;
			var dato = document.getElementById("dateto").value;
			var pars = document.getElementById("slt_apnd").value;
			var pbsh = document.getElementById("slt_pbsh").value;
			if(pbsh!="" && pars!="" && dfro!="" && dato!=""){
				var xhttp = new XMLHttpRequest();
					xhttp.onreadystatechange = function(){
					if (this.readyState == 4 && this.status == 200){
						document.getElementById("tblloa").innerHTML = this.responseText;
						document.getElementById("rfrsh").innerHTML = '<i class="fas fa-sync faa-wrench animated faa-slow"></i>';
						document.getElementById("rfrsh").disabled = false;
						document.getElementById("dwnld").innerHTML = '<i class="fas fa-download faa-float animated faa-slow"></i>';
						document.getElementById("dwnld").disabled = false;
					}
					};
				xhttp.open("POST", "requestform/loarow_reports.php", true);
				xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhttp.send("pbsh="+pbsh+"&pars="+pars+"&dfro="+dfro+"&dato="+dato);
			}else{ document.getElementById("tblloa").innerHTML="";  }
		}
    </script>
</body>
</html>
<?php } ?>