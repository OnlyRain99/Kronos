<?php
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "My Daily Logs";

$dadate = "";
if(isset($_REQUEST['dadate'])){
    $dadate = addslashes($_REQUEST['dadate']);
}
$datef = "";
$datet = "";
if(date("d")>15){$datef=date("Y-m-16"); $datet=date("Y-m-t");}
else{$datef=date("Y-m-01"); $datet=date("Y-m-15");}
if($dadate != ""){
    $datef = date("Y-m-d", strtotime($dadate));
    $datet = date("Y-m-d", strtotime($dadate));
}
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
							<h2 class="title-1 m-b-25"> <?php echo $title; ?> <i class="fas fa-folder"></i></h2>
						</div>
					</div>

					<form method="post" enctype="multipart/form-data" >
						<div class="row">
							 <div class="col-lg-3">
							 	<div class="form-group">
							 		<label>From</label>
							 		<input type="date" name="datefrom" id="datefrom" onchange="daterange()" class="form-control" value="<?php echo $datef; ?>">
							 	</div>
							 </div>
							 <div class="col-lg-3">
							 	<div class="form-group">
							 		<label>To</label>
							 		<input type="date" name="dateto" id="dateto" onchange="daterange()" class="form-control" value="<?php echo $datet; ?>">
							 	</div>
							 </div>
							 <div class="col-lg-2">
							 	<label><br></label>
							 	<btn onclick="procsearch()" class="btn btn-success btn-block"><i class="fa fa-search"></i> Search</btn>
							 </div>
						</div>
					</form>
					<div class="row">
						<div class="col-lg-12">
							<div class="card">
								<div class="table-responsive m-b-40">
									<table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
										<thead>
											<tr class="mybg">
												<th style="padding: 10px;" class="text-center">Date</th>
                                                <th style="padding: 10px;" class="text-center">IN</th>
                                                <th style="padding: 10px;" class="text-center">BO</th>
                                                <th style="padding: 10px;" class="text-center">BI</th>
                                                <th style="padding: 10px;" class="text-center">OUT</th>
                                                <th style="padding: 10px;" class="text-center">SH</th>
                                                <th style="padding: 10px;" class="text-center">BH</th>
                                                <th style="padding: 10px;" class="text-center">OT</th>
                                                <th style="padding: 10px;" class="text-center text-danger">UT/L</th>
                                                <th style="padding: 10px;" class="text-center">ATH</th>
                                                <th style="padding: 10px;" class="text-center">Status</th>
                                                <th style="padding: 10px;" class="text-center">Remarks</th>
											</tr>
										</thead>
										<tbody id="mytmsht">
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<?php include 'footer.php'; $link->close(); ?>
				</div>
			</div>
		</div>
	</div>
	<?php include 'scripts.php'; ?>
	<script type="text/javascript">  

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

        function searchdate(datefrom, dateto){
		var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
                document.getElementById("mytmsht").innerHTML = this.responseText;
            }
        };
        xhttp.open("POST", "search_daily_logs.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("&datefrom="+datefrom+"&dateto="+dateto);
        }
		
		function procsearch(){
			var datefrom = document.getElementById("datefrom").value;
			var dateto = document.getElementById("dateto").value;
			searchdate(datefrom, dateto);
		}
		
        function autosetmdl(){
        	const d = new Date();
        	var month = d.getMonth() + 1;
        	var datefrom, dateto;
        	if(d.getDate() <= 15){
        		datefrom = month+"/1/"+d.getFullYear();
        		dateto = month+"/15/"+d.getFullYear();
        	}else{
        		var ldom = new Date(d.getFullYear(), month, 0).getDate();
        		datefrom = month+"/16/"+d.getFullYear();
        		dateto = month+"/"+ldom+"/"+d.getFullYear();
        	}

            if(document.getElementById("datefrom").value!=""){
            	procsearch();
            }else{
				searchdate(datefrom, dateto);
            }
        }

        autosetmdl();
    </script>
</body>
</html>