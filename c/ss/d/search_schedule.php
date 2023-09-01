<?php  
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    //current month
    $datefrom = date("Y-m-d", strtotime(@$_GET['datef']));
    $dateto = date("Y-m-d", strtotime(@$_GET['datet']));

    if ($datefrom == $dateto) {
        $title = "My Schedule: ".date("m/d/Y", strtotime($datefrom));
    }else{
        $title = "My Schedule: ".date("m/d/Y", strtotime($datefrom))." - ".date("m/d/Y", strtotime($dateto));
    }

    $myid=getempid($user_code);

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
                            <h2 class="title-1 m-b-25"><?php echo $title; ?> <i class="far fa-clock"></i></h2>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <strong class="card-title mb-3"><center><?php echo sibsid($user_code)." - ".$user_info; ?></center></strong>
                                </div>
                                <div class="card-body">
                                    <form method="post" enctype="multipart/form-data" action="redirect_manager" onsubmit="validateForm(this)">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label style="color: blue;">*from</label>
                                                <input type="date" name="s_datefrom" id="datefrom" onchange="daterange()" value="<?= $datefrom; ?>" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label style="color: blue;">*to</label>
                                                <input type="date" name="s_dateto" id="dateto" onchange="daterange()" value="<?= $dateto; ?>" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label style="color: blue;">*submit</label>
                                                <button type="submit" name="submit" id="submit" class="btn btn-primary" title="click to search ..."><i class="fa fa-search"></i> Search Schedule</button>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                                    <thead style="background: #fff;">
                                                        <tr class="mybg">
                                                            <th style="padding: 3px; color: #000;" class="text-center">Date</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center">Day</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center">Login</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center">Logout</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center">Scheduled to</th>
                                                            <th style="padding: 3px; color: #000;" class="text-center">Event</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
if($datefrom == "" && $dateto != ""){ $sqlseq = "AND `gy_sched_day` ='".date('Y-m-d', strtotime($dateto))."'"; }
else if($datefrom != "" && $dateto == ""){ $sqlseq = "AND `gy_sched_day`='".date('Y-m-d', strtotime($datefrom))."'"; }
else if($datefrom != "" && $dateto != ""){ $sqlseq = "AND `gy_sched_day`>='".date('Y-m-d', strtotime($datefrom))."' AND `gy_sched_day`<='".date('Y-m-d', strtotime($dateto))."'"; }
else { $sqlseq = "AND `gy_sched_day`='".date("Y-m-d")."'"; }

$cntdate = date('Y-m-d', strtotime($datefrom));
$tmsht=$link->query("SELECT * From `gy_schedule` Where `gy_emp_id`=$myid ".$sqlseq." Order By `gy_sched_day` ASC");
while($tsrow=$tmsht->fetch_array()){
    while($cntdate<date("Y-m-d", strtotime($tsrow['gy_sched_day']))){
        tblcntt($cntdate, "", "", "");
        $cntdate = date('Y-m-d', strtotime($cntdate.' +1 day'));
    }
    tblcntt($cntdate, $tsrow['gy_sched_login'], $tsrow['gy_sched_logout'], $tsrow['gy_sched_mode']);
    $cntdate = date('Y-m-d', strtotime($cntdate.' +1 day'));
}
    while($cntdate<=date("Y-m-d", strtotime($dateto))){
        tblcntt($cntdate, "", "", "");
        $cntdate = date('Y-m-d', strtotime($cntdate.' +1 day'));
    }

function tblcntt($date, $scin, $scout, $mode){
    $scto="";
    $schin="";
    $schout="";
    if($mode=="0"){ $scto="Rest"; $schin="OFF"; $schout="OFF"; }
    else if($mode==1){ $scto="Work"; }
    else if($mode==2){ $scto="RDOT"; }
    if($scin!="" && $mode!="0"){ $schin=date("h:i a",strtotime(convert24to0($scin))); }
    if($scout!="" && $mode!="0"){ $schout=date("h:i a",strtotime(convert24to0($scout))); }
    $evnt = getdaystatus($date);
?>
<tr class="mybg <?php if($date==date("Y-m-d")){echo"table-secondary";}else if($date<date("Y-m-d")){echo"table-light";} if($mode=="0"){echo" text-primary";}else if($mode=="2"){echo" text-primary";}else if($mode==""){echo" text-danger";}else if($evnt!=""){echo" text-success";} ?>">
<td style="padding: 5px;" class="text-center text-nowrap"><?php echo date("F j, Y",strtotime($date)); ?></td>
<td style="padding: 5px;" class="text-center text-nowrap"><?php echo date("D",strtotime($date)); ?></td>
<td style="padding: 5px;" class="text-center text-nowrap"><?php echo $schin ?></td>
<td style="padding: 5px;" class="text-center text-nowrap"><?php echo $schout; ?></td>
<td style="padding: 5px;" class="text-center text-nowrap"><?php echo $scto; ?></td>
<td style="padding: 5px;" class="text-center text-nowrap"><?php echo $evnt; ?></td>
</tr>
<?php }
function getdaystatus($holdate){
    include '../../../config/conn.php';
    $holid = "";
    $hddate = date("Y-m-d",strtotime($holdate));
    $curyear = date("Y",strtotime($holdate));
    $curmonth = date("m",strtotime($holdate));
    $curday = date("d",strtotime($holdate));
    $dssql=$link->query("SELECT * FROM `gy_holiday_calendar` LEFT JOIN `gy_holiday_types` ON `gy_holiday_calendar`.`gy_hol_type_id`=`gy_holiday_types`.`gy_hol_type_id` WHERE (`gy_holiday_calendar`.`gy_a_year`=1 AND `gy_holiday_calendar`.`gy_hol_date`='$hddate')OR(`gy_holiday_calendar`.`gy_a_year`=0 AND Year(`gy_holiday_calendar`.`gy_hol_date`)<='$curyear' AND (Year(`gy_holiday_calendar`.`gy_hol_lastday`)='0000' OR (Year(`gy_holiday_calendar`.`gy_hol_lastday`)!='0000' AND Year(`gy_holiday_calendar`.`gy_hol_lastday`)>='$curyear' ) ) )AND(MONTH(`gy_holiday_calendar`.`gy_hol_date`)='$curmonth'AND DAY(`gy_holiday_calendar`.`gy_hol_date`)='$curday') LIMIT 1");
        while($dsrow=$dssql->fetch_assoc()){
            if($holid!=""){ $holid.="/"; }
            $holid.=strtoupper($dsrow['gy_hol_abbrv']);
            if($dsrow['gy_hol_loc']==0){ $holid.=" (Tagum Only)"; }
            else if($dsrow['gy_hol_loc']==1){ $holid.=" (Davao Only)"; }
        }
    $link->close();
    return $holid;
}
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php include 'footer.php'; $link->close(); ?>
                </div>
            </div>
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
        </div>

    </div>

    <?php include 'scripts.php'; ?>

    <script type="text/javascript">  
        function validateForm(formObj) {
            formObj.submit.disabled = true;
            formObj.submit.innerHTML = "searching ...";
            return true;  
        }  
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
        $("#activate-alert").fadeTo(5000, 500).slideUp(500, function(){
            $("#activate-alert").slideUp(500);
        });
    </script>

</body>

</html>
<!-- end document-->
