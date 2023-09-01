<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "MyTeam Status";

    $notify = @$_GET['note'];

    if ($notify == "invalid") {
        $note = "Invalid";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "error") {
        $note = "Something Error!";
        $notec = "danger";
        $notes = "";
        $noteid = "activate-alert";
    }else{
        $note = "";
        $notec = "";
        $notes = "display: none;";
        $noteid = "";
    }

    $condition = getall($user_id);

    //for pending
    $sql="SELECT `gy_tracker`.`gy_tracker_id` FROM `gy_employee` LEFT JOIN `gy_tracker` On `gy_employee`.`gy_emp_code`=`gy_tracker`.`gy_emp_code` Where `gy_employee`.`gy_emp_supervisor` IN ('".$condition."') AND `gy_tracker_request`='' AND date(`gy_tracker_date`)='$onlydate'";
    $query=$link->query($sql);
    $pending = $query->num_rows;

    //for approve
    $sql="SELECT `gy_tracker`.`gy_tracker_id` FROM `gy_employee` LEFT JOIN `gy_tracker` On `gy_employee`.`gy_emp_code`=`gy_tracker`.`gy_emp_code` Where `gy_employee`.`gy_emp_supervisor` IN ('".$condition."') AND `gy_tracker_request`='approve' AND date(`gy_tracker_date`)='$onlydate'";
    $aquery=$link->query($sql);
    $approve = $aquery->num_rows;

    //for rejected
    $sql="SELECT `gy_tracker`.`gy_tracker_id` FROM `gy_employee` LEFT JOIN `gy_tracker` On `gy_employee`.`gy_emp_code`=`gy_tracker`.`gy_emp_code` Where `gy_employee`.`gy_emp_supervisor` IN ('".$condition."') AND `gy_tracker_request`='reject' AND date(`gy_tracker_date`)='$onlydate'";
    $vquery=$link->query($sql);
    $reject = $vquery->num_rows;

    //for overtime / approve ot
    $sql="SELECT `gy_tracker`.`gy_tracker_id` FROM `gy_employee` LEFT JOIN `gy_tracker` On `gy_employee`.`gy_emp_code`=`gy_tracker`.`gy_emp_code` Where `gy_employee`.`gy_emp_supervisor` IN ('".$condition."') AND `gy_tracker_request`='overtime' AND date(`gy_tracker_date`)='$onlydate'";
    $squery=$link->query($sql);
    $overtime = $squery->num_rows;

    //for escalate
    $sql="SELECT `gy_tracker`.`gy_tracker_id` FROM `gy_employee` LEFT JOIN `gy_tracker` On `gy_employee`.`gy_emp_code`=`gy_tracker`.`gy_emp_code` Where `gy_employee`.`gy_emp_supervisor` IN ('".$condition."') AND `gy_tracker_request`='escalate' AND date(`gy_tracker_date`)='$onlydate'";
    $nquery=$link->query($sql);
    $escalate = $nquery->num_rows;

    if ($pending == 0 && $approve == 0 && $reject == 0 && $overtime == 0 && $escalate == 0) {
        $chart_status = "<span style='color: red;'>chart empty</span>";
    }else{
        $chart_status = "Pie Chart";
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

        <!-- PAGE CONTAINER-->
        <div class="page-container">

            <!-- MAIN CONTENT-->
            <div class="main-content" style="padding: 20px;">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="title-1 m-b-25"><?php echo $title; ?></h2>
                            <div style="<?php echo $notes; ?>" id="<?php echo $noteid; ?>" class="sufee-alert alert with-close alert-<?php echo $notec; ?> alert-dismissible fade show">
                                <span class="badge badge-pill badge-<?php echo $notec; ?>">Alert</span>
                                <?php echo $note; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div> 
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <form method="post" enctype="multipart/form-data" action="redirect_manager" onsubmit="validateForm(this);">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>From</label>
                                                <input type="date" name="s_datef" id="datefrom" onchange="daterange()" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>To</label>
                                                <input type="date" name="s_datet" id="dateto" onchange="daterange()" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Leaders</label>
                                                <select name="leader" class="form-control" required>
                                                    <option value="all">All</option>
                                                    <?php  
                                                        $leaders=$link->query("SELECT `gy_user_id`,`gy_full_name` From `gy_user` LEFT JOIN `gy_employee` On `gy_user`.`gy_user_code`=`gy_employee`.`gy_emp_code` Where `gy_user`.`gy_user_status`=0 AND `gy_emp_supervisor`='$user_id' Order By `gy_full_name` ASC");
                                                        while ($lead=$leaders->fetch_array()) {
                                                    ?>
                                                    <option value="<?= $lead['gy_user_id']; ?>"><?= $lead['gy_full_name']; ?></option>
                                                <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label style="color: blue;">*search</label>
                                                <button type="submit" name="submit" id="submit" class="btn btn-primary" title="click to search ..."><i class="fa fa-search"></i> Search</button>
                                            </div>
                                        </div>
                                    </div>
                                    </form>

                                    <div class="vue-lists">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <br>
                                                <h2 class="mb-4">Status Count Today - All</h2>
                                                <ul>
                                                    <li><span style="color: gray;"><?= $pending; ?></span> - Pending</li>
                                                    <li><span style="color: #00a65a;"><?= $approve; ?></span> - Approve</li>
                                                    <li><span style="color: #9acd32;"><?= $overtime; ?></span> - Approved OT</li>
                                                    <li><span style="color: #f56954;"><?= $reject; ?></span> - Rejected</li>
                                                    <li><span style="color: #00c0ef;"><?= $escalate; ?></span> - Escalating</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <canvas style="margin-left: -15px;" id="pieChart"></canvas>
                                                <h2 class="mb-4 text-center"><?= $chart_status; ?></h2>
                                            </div>
                                        </div>
                                    </div>
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

    <script>
      $(function () {
        var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
        var pieChart       = new Chart(pieChartCanvas)
        var PieData        = [
          {
            value    : <?= $pending; ?>,
            color    : 'gray',
            highlight: 'gray',
            label    : 'Pending'
          },
          {
            value    : <?= $approve; ?>,
            color    : '#00a65a',
            highlight: '#00a65a',
            label    : 'Approved'
          },
          {
            value    : <?= $overtime; ?>,
            color    : '#9acd32',
            highlight: '#9acd32',
            label    : 'Approved OT'
          },
          {
            value    : <?= $escalate; ?>,
            color    : '#00c0ef',
            highlight: '#00c0ef',
            label    : 'Escalating'
          },
          {
            value    : <?= $reject; ?>,
            color    : '#f56954',
            highlight: '#f56954',
            label    : 'Rejected'
          }
        ]
        var pieOptions     = {
          //Boolean - Whether we should show a stroke on each segment
          segmentShowStroke    : true,
          //String - The colour of each segment stroke
          segmentStrokeColor   : '#fff',
          //Number - The width of each segment stroke
          segmentStrokeWidth   : 2,
          //Number - The percentage of the chart that we cut out of the middle
          percentageInnerCutout: 0, // This is 0 for Pie charts
          //Number - Amount of animation steps
          animationSteps       : 100,
          //String - Animation easing effect
          animationEasing      : 'easeOutBounce',
          //Boolean - Whether we animate the rotation of the Doughnut
          animateRotate        : true,
          //Boolean - Whether we animate scaling the Doughnut from the centre
          animateScale         : false,
          //Boolean - whether to make the chart responsive to window resizing
          responsive           : true,
          // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
          maintainAspectRatio  : true,
          //String - A legend template
          legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        pieChart.Doughnut(PieData, pieOptions)

      })
    </script>

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
        function validateForm(formObj) {
            formObj.submit.disabled = true;
            formObj.submit.innerHTML = "searching data ...";
            return true;  
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
