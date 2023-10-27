<?php
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';
	
	$title = "TestModule"
	
?>









<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>
<script
  type="text/javascript"
  src="../node_modules/tw-elements/dist/js/tw-elements.umd.min.js"></script>
  <style type="text/css">

  </style>
<body>
	<div class="page-wrapper">
	<?php include 'header-m.php'; ?>
    <?php include 'sidebar.php'; ?>
	<div class="page-container">
		<div class="main-content" style="padding: 20px;">
			<div class="col-lg-12">
                    <h2 class="title-1 m-b-25">TestModule<i class="fa fa-check"></i></h2>         
<html>
<head>
<div class="container">

  <!-- Button to Open the Modal -->
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
    Open modal
  </button>

  <!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Modal Heading</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
        <center><div class="form-group col-lg-6">
                        <label class="font-weight-bold text-small" for="lastname">Employee SiBS ID<span class="text-primary ml-1">*</span></label>
                        <input class="form-control" id="lastname" type="text" placeholder="SiBS ID" required="" />
                    </div></center>
            <div class="row">
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold text-small" for="lastname">Last name<span class="text-primary ml-1">*</span></label>
                        <input class="form-control" id="lastname" type="text" placeholder="Enter your last name" required="" />
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold text-small" for="lastname">Last name<span class="text-primary ml-1">*</span></label>
                        <input class="form-control" id="lastname" type="text" placeholder="Enter your last name" required="" />
                    </div>
                    <div class="form-group col-lg-6">
                    <label class="font-weight-bold text-small" for="lastname">Quality Assurance Form<span class="text-primary ml-1">*</span></label>
                    <select class="form-select" aria-label="Default select example">
                        <option selected>Open this select menu</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                    </div>
            </div>
                    
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
  
</div>
</style>
			</div>
			<?php include 'footer.php'; ?>
		</div>
</div>
       
	<?php include 'scripts.php'; ?>

</body>
</html>