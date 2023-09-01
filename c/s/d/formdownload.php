<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';
	
	$title = "Request Forms";
	
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>
<body>
	<div class="page-wrapper">
	<?php include 'header-m.php'; ?>
    <?php include 'sidebar.php'; ?>
	<div class="page-container">
		<div class="main-content" style="padding: 20px;">
			<div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25"> <?php echo $title; ?> <i class="fas fa-folder-open"></i></h2>
                </div>
            </div>
			<div class="card-deck">
			<div class="card draggable" id="rotatable">
				<img class="card-img-top" src="../../../images/pdf2.jpg" alt="Card image cap">
				<div class="card-body">
				<h5 class="card-title">LOA</h5>
				<p class="card-text">Leave of Absence Form</p>
				<a href="downloadnow?form=1" class="btn btn-primary btn-block"><i class="fa fa-download"></i> Download</a>
				</div>
			</div>
			<div class="card draggable" id="rotatable1">
				<img class="card-img-top" src="../../../images/excel2.jpg" alt="Card image cap">
				<div class="card-body">
				<h5 class="card-title">COA</h5>
				<p class="card-text">Certificate of Attendance Form</p>
				<a href="downloadnow?form=2" class="btn btn-primary btn-block"><i class="fa fa-download"></i> Download</a>
				</div>
			</div>
			<div class="card draggable" id="rotatable2">
				<img class="card-img-top" src="../../../images/pdf2.jpg" alt="Card image cap">
				<div class="card-body">
				<h5 class="card-title">OBT</h5>
				<p class="card-text">Official Business Trip Form</p>
				<a href="downloadnow?form=3" class="btn btn-primary btn-block"><i class="fa fa-download"></i> Download</a>
				</div>
			</div>
			</div><br><div class="card-deck">
			<div class="card draggable" id="rotatable3">
				<img class="card-img-top" src="../../../images/pdf2.jpg" alt="Card image cap">
				<div class="card-body">
				<h5 class="card-title">EO</h5>
				<p class="card-text">Early Out Form</p>
				<a href="downloadnow?form=4" class="btn btn-primary btn-block"><i class="fa fa-download"></i> Download</a>
				</div>
			</div>
			<div class="card draggable" id="rotatable4">
				<img class="card-img-top" src="../../../images/pdf2.jpg" alt="Card image cap">
				<div class="card-body">
				<h5 class="card-title">VTO</h5>
				<p class="card-text">Voluntary Time Out Form</p>
				<a href="downloadnow?form=5" class="btn btn-primary btn-block"><i class="fa fa-download"></i> Download</a>
				</div>
			</div>
			<div class="card draggable" id="rotatable5">
				<img class="card-img-top" src="../../../images/excel2.jpg" alt="Card image cap">
				<div class="card-body">
				<h5 class="card-title">SA</h5>
				<p class="card-text">Schedule Adjustment Form</p>
				<a href="downloadnow?form=6" class="btn btn-primary btn-block"><i class="fa fa-download"></i> Download</a>
				</div>
			</div>
			</div><br><div class="card-deck">
			<div class="card draggable" id="rotatable6">
				<img class="card-img-top" src="../../../images/excel2.jpg" alt="Card image cap">
				<div class="card-body">
				<h5 class="card-title">OT/RDOT</h5>
				<p class="card-text">Overtime Authorization Form</p>
				<a href="downloadnow?form=7" class="btn btn-primary btn-block"><i class="fa fa-download"></i> Download</a>
				</div>
			</div>
			<div class="card draggable" id="rotatable7">
			</div>
			<div class="card draggable" id="rotatable8">
			</div>
			</div>
			<?php include 'footer.php'; ?>
		</div>
	</div>
	</div>
	<?php include 'scripts.php'; ?>
    <script type="text/javascript">
$( function() {
	$('.draggable').Dragging({
		speed: 500,
		vertical:true,
		horizontal:true,
		rotate:true
	});
  $('#rotatable').propeller({
		inertia: 0.98,
		speed: 0,
		minimalSpeed: 0.001,
		step: 0,
		stepTransitionTime: 0,
		stepTransitionEasing:'linear',

  });
  $('#rotatable1').propeller({inertia: 0.98,});
  $('#rotatable2').propeller({inertia: 0.98,});
  $('#rotatable3').propeller({inertia: 0.98,});
  $('#rotatable4').propeller({inertia: 0.98,});
  $('#rotatable5').propeller({inertia: 0.98,});
  $('#rotatable6').propeller({inertia: 0.98,});
  $('#rotatable7').propeller({inertia: 0.98,});
  $('#rotatable8').propeller({inertia: 0.98,});
} );
	</script>
</body>
</html>
