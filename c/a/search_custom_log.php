<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $filter = @$_GET['filter'];
    $my_date_from = @$_GET['datef'];
    $my_date_to = @$_GET['datet'];

    if ($filter != "") {

        if ($my_date_from == $my_date_to) {
            $date_format = date("M d, Y", strtotime($my_date_from));
        }else{
            $date_format = date("M d - ", strtotime($my_date_from))." ".date("M d, Y", strtotime($my_date_to));
        }

        //title
        $my_title = "Filter - ".$filter." -> Day of ".$date_format;

        //query
        $query_one=$link->query("SELECT * From `gy_notification` Where `gy_notif_type`='$filter' AND date(`gy_notif_date`) BETWEEN '$my_date_from' AND '$my_date_to' Order By `gy_notif_date` DESC");

    }else{

        //title
        if ($my_date_from == $my_date_to) {
            $date_format = date("M d, Y", strtotime($my_date_from));
        }else{
            $date_format = date("M d - ", strtotime($my_date_from))." ".date("M d, Y", strtotime($my_date_to));
        }

        $my_title = "Day of ".$date_format;

        //query
        $query_one=$link->query("SELECT * From `gy_notification` Where date(`gy_notif_date`) BETWEEN '$my_date_from' AND '$my_date_to' Order By `gy_notif_date` DESC");

    }

    $notifcount=$query_one->num_rows;

    $title = "System Log Search: ".$my_title;
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

        <!-- PAGE CONTAINER-->
        <div class="page-container">

            <!-- MAIN CONTENT-->
            <div class="main-content" style="padding: 20px;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="title-1 m-b-25"><?php echo @number_format(0 + $notifcount)." ".$title; ?></h2>
                        </div>
                        <div class="col-lg-12">
                            <form method="post" enctype="multipart/form-data" action="redirect_manager">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="search_log" placeholder="Search log ..." autofocus required>
                                </div>
                            </form>
                        </div>
                        
                        <div class="col-lg-3">
                            <form method="post" enctype="multipart/form-data" action="redirect_manager">
                            <div class="form-group">
                                <input type="date" class="form-control" name="date_f" id="datefrom" onchange="daterange()" value="<?= $my_date_from; ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <input type="date" class="form-control" name="date_t" id="dateto" onchange="daterange()" value="<?= $my_date_to; ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <select name="filter" class="form-control">
                                    <option value="<?= $filter; ?>"><?= $filter; ?></option>
                                    <option value="dtr">DTR Logs</option>
                                    <option value="inout">Login/Logout</option>
                                    <option value="update">Updates</option>
                                    <option value="insert">New Inputs</option>
                                    <option value="delete">Deleted</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <button type="submit" name="search_custom_log" class="btn btn-primary" title="click to search by dates and filter ..."><i class="fa fa-search"></i> Search</button>
                            </form>
                        </div>

                        <div class="col-lg-12">
                            <p class="title-5" style="color: red; text-transform: lowercase;"><?php echo @number_format(0 + $notifcount); ?> result(s)</p>
                        </div>

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body" style="background: #000; color: #fff;">
                                    <div class="mx-auto d-block">
                                        <p style="font-family: 'Courier';">
                                            <?php
                                                //get logs
                                                while ($logs=$query_one->fetch_array()) {
                                            ?>

                                            <span style="color: green;"><?php echo date("m-d-Y", strtotime($logs['gy_notif_date'])); ?></span> <span style="color: red;"><?php echo date("g:i A", strtotime($logs['gy_notif_date'])); ?></span> ---> <?php echo $logs['gy_notif_text']; ?><br>

                                            <?php } ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
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
    </script>

    <script type="text/javascript">
        $("#activate-alert").fadeTo(5000, 500).slideUp(500, function(){
            $("#activate-alert").slideUp(500);
        });
    </script>

</body>

</html>
<!-- end document-->
