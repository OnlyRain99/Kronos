<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    $title = "Page Not Found";
?>

<!DOCTYPE html>
<html lang="en">

<?php include 'head.php'; ?>

<body>

    <div class="page-wrapper">
        <div class="container">

            <!-- MAIN CONTENT-->
            <div class="main-content" style="padding: 20px;">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-lg-12 text-center" style="margin-top: 100px;">
                            <h1 class="m-b-10"><i class="fa fa-warning"></i></h1>
                            <h2 class="title-1 m-b-25"><?php echo $title; ?></h2>
                            <a href="./"><i class="fa fa-angle-double-left"></i> Back to My Kronos</a>
                        </div>
                    </div>

                    <?php include 'footer.php'; ?>

                </div>
            </div>
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
        </div>
    </div>

    <?php include 'scripts.php'; ?>

</body>

</html>
<!-- end document-->