<?php
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "Event Calendar";
if($user_type == 6 && $user_dept == 2){
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
                        <div class="col-lg-12"><h2 class="title-1 m-b-25"><i class="fas fa-calendar-alt"></i> <?php echo $title; ?></h2></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        <ul class="nav nav-tabs">
                            <li class="nav-item"><a class="nav-link active" href="eventcalendar">Calendar</a></li>
                            <li class="nav-item"><a class="nav-link active" aria-current="page" href="#"><strong>Plot Holiday</strong></a></li>
                            <li class="nav-item"><a class="nav-link active" href="event_rules">Holiday Policy</a></li>
                        </ul>

                        <div class="card text-dark bg-light">
                            <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="txtpernm" placeholder="Event Name">
                                        <select class="form-select" id="selpertyp" >
                                        <?php $htsql=$link->query("SELECT * FROM `gy_holiday_types` WHERE `gy_hol_status`!=0"); while($htrow=$htsql->fetch_array()){ ?>
                                        <option value="<?php echo $htrow['gy_hol_type_id']; ?>"><?php echo $htrow['gy_hol_type_name']; ?></option>
                                        <?php } ?>
                                        </select>
										<select class="form-select" id="seltagdav">
										<option value="2">All</option>
										<option value="0">Tagum</option>
										<option value="1">Davao</option>
										</select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-3" id="updsel">
                                        <select class="form-select" id="selperm" onchange="addandloadey('updsel','calendar_updsel','col=0')">
                                            <option value="0" selected>Every Year</option>
                                            <option value="1">Once Only</option>
                                        </select>
                                        <select class="form-select" id="selpermm">
                                        <?php for($i=1;$i<=12;$i++){ ?>
                                            <option value="<?php echo $i; ?>" <?php if($i==date("m")){ echo "selected"; } ?>><?php echo date("F", mktime(0,0,0,$i,10)); ?></option>
                                        <?php } ?>
                                        </select>
                                        <select class="form-select" id="selpermd">
                                        <?php for($i=1;$i<=31;$i++){ ?><option value="<?php echo $i;?>" <?php if($i==date("d")){ echo "selected"; } ?>><?php echo $i;?></option><?php } ?>
                                        </select>
                                        <btn class="btn btn-primary" onclick="plotevt()"><i class="fas fa-flag"></i> PLOT</btn>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <strong class="card-title mb-3">
                                            <span class="pull-left"><i class='fas fa-calendar-plus'></i></span>
                                            <span class="pull-right">Every Year</span></strong>
                                        </div>
                                        <table class="table table-striped">
                                            <thead><tr>
                                                <th scope="col">Date</th>
                                                <th scope="col">Event Name</th>
                                                <th scope="col">Event Type</th>
                                                <th scope="col"></th>
                                            </tr></thead>
                                            <tbody id="dyney">
                                        <?php $hcsql=$link->query("SELECT * FROM `gy_holiday_calendar` LEFT JOIN `gy_holiday_types` on `gy_holiday_calendar`.`gy_hol_type_id`=`gy_holiday_types`.`gy_hol_type_id` where `gy_holiday_calendar`.`gy_a_year`=0 AND `gy_holiday_calendar`.`gy_hol_lastday`='0000-00-00' order by month(`gy_holiday_calendar`.`gy_hol_date`), day(`gy_holiday_calendar`.`gy_hol_date`) asc"); while($hcrow=$hcsql->fetch_array()){ ?>
                                            <tr>
                                                <td><?php echo date("F d", strtotime($hcrow['gy_hol_date'])); ?></td>
                                                <td><?php echo $hcrow['gy_hol_title']; ?></td>
                                                <td <?php if($hcrow['gy_hol_loc']<2){ ?>style="font-size: 12px;"<?php } ?> ><?php echo $hcrow['gy_hol_type_name']; if($hcrow['gy_hol_loc']==0){echo"(Tagum)";}else if($hcrow['gy_hol_loc']==1){echo"(Davao)";} ?></td>
                                                <td><button type="button" class="btn-close" aria-label="Close" onclick="remevt(<?php echo "'".$hcrow['gy_hol_id']."', '".date("F d", strtotime($hcrow['gy_hol_date']))."', '".addslashes($hcrow['gy_hol_title'])."', 0, 0, 1"; ?>)"></button></td>
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
                                            <span class="pull-left" id="pullleft"><i class='far fa-calendar-plus'></i> Upcoming <span style="cursor: pointer" class="btn-outline-dark btn-sm" onclick="switchoo(0)"><i id='reswitch' class='fas fa-retweet faa-wrench faa-slow animated'></i></span></span>
                                            <span class="pull-right">Occur Once</span></strong>
                                        </div>
                                        <table class="table table-striped">
                                            <thead><tr>
                                                <th scope="col">Date</th>
                                                <th scope="col">Event Name</th>
                                                <th scope="col">Event Type</th>
                                                <th scope="col"></th>
                                            </tr></thead>
                                            <tbody id="dynoo" style="font-size: 14px;">
                                        <?php  $hcsql=$link->query("SELECT * FROM `gy_holiday_calendar` LEFT JOIN `gy_holiday_types` on `gy_holiday_calendar`.`gy_hol_type_id`=`gy_holiday_types`.`gy_hol_type_id` where `gy_holiday_calendar`.`gy_a_year`=1 and `gy_holiday_calendar`.`gy_hol_date`>='".date("Y-m-d")."' order by `gy_holiday_calendar`.`gy_hol_date` asc"); while($hcrow=$hcsql->fetch_array()){ ?>
                                            <tr>
                                                <td><?php echo date("F d, Y", strtotime($hcrow['gy_hol_date'])); ?></td>
                                                <td><?php echo $hcrow['gy_hol_title']; ?></td>
                                                <td <?php if($hcrow['gy_hol_loc']<2){ ?>style="font-size: 12px;"<?php } ?> ><?php echo $hcrow['gy_hol_type_name']; if($hcrow['gy_hol_loc']==0){echo"(Tagum)";}else if($hcrow['gy_hol_loc']==1){echo"(Davao)";} ?></td>
                                                <td><button type="button" class="btn-close" aria-label="Close" onclick="remevt(<?php echo "'".$hcrow['gy_hol_id']."', '".date("F d, Y", strtotime($hcrow['gy_hol_date']))."', '".addslashes($hcrow['gy_hol_title'])."', 1, 0, 1"; ?>)"></button></td>
                                            </tr>
                                        <?php } ?>
                                            </tbody>
                                        </table>
                                        <nav aria-label="..."> 
                                            <ul class="pagination flex-wrap" id="pagelink">
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <?php include 'footer.php'; } $link->close(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php include 'scripts.php'; ?>
<script type="text/javascript">
    function daterange(){
        let today = new Date();
        var year = today.getFullYear();
        var month = today.getMonth() + 1;
        var day = today.getDate();
        if (day < 10) day = '0' + day;
        if (month < 10) month = '0' + month;
        today = year+"-"+month+"-"+day
        var oodate = _getID("oodate").min;
        if (oodate) { _getID("oodate").min = today; }
    }

    function remevt(remid, date, name, col, expr, swt){
			const Toast = Swal.mixin({
                toast: true,
                  didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
            Toast.fire({
                icon: 'warning',
                title: 'End the event of '+name+' that was scheduled on '+date+'?',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="fa-sharp fa-solid fa-xmark"></i> Unplot',
                cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancel'
            }).then((result) => {
                if(result.isConfirmed){
					var dyncol = "";
					var dynlnk = "";
					if(col==0){ dyncol = "dyney"; dynlnk = "remid="+remid+"&col=0"; }
					else if(col==1){ dyncol = "dynoo"; dynlnk = "remid="+remid+"&col=1&expr="+expr+"&swt="+swt; }
					addandloadey(dyncol, "calendar_remplot", dynlnk);
                }
            })
    }

    function plotevt(){
        var name = document.getElementById("txtpernm").value;
        var holtyp = document.getElementById("selpertyp").value;
		var holloc = document.getElementById("seltagdav").value;
        if(document.getElementById("selperm").value==0 && name!="" && holtyp!=""){
            var month = document.getElementById("selpermm").value;
            var day = document.getElementById("selpermd").value;
            addandloadey("dyney","calendar_addplot","name="+name+"&type="+holtyp+"&cal=0&month="+month+"&day="+day+"&hloc="+holloc);
        }else if(document.getElementById("selperm").value==1 && name!="" && holtyp!=""){
            var dateoo = document.getElementById("oodate").value;
            addandloadey("dynoo","calendar_addplot","name="+name+"&type="+holtyp+"&cal=1&dateoo="+dateoo+"&hloc="+holloc);
        }
    }

    function switchoo(intlp){
        if(intlp==0){
            document.getElementById("pullleft").innerHTML = "<i class='fas fa-sort-numeric-down-alt'></i> Expired <span style='cursor: pointer' class='btn-outline-dark btn-sm' onclick='switchoo(1)'><i id='reswitch' class='fas fa-retweet faa-spin faa-fast animated'></i></span>";
            addandloadey("dynoo", "swicthupcmg_exprd", "swt=0&pg=0");
            addandloadey("pagelink", "exprdevt_paging", "pg=1");
        }else if(intlp==1){
            document.getElementById("pullleft").innerHTML = "<i class='fas fa-sort-numeric-up-alt'></i> Upcoming <span style='cursor: pointer' class='btn-outline-dark' onclick='switchoo(0)'><i id='reswitch' class='fas fa-retweet faa-spin faa-fast animated'></i></span>";
            addandloadey("dynoo", "swicthupcmg_exprd", "swt=1");
            document.getElementById("pagelink").innerHTML = "";
        }
    }

    function switchpage(pagenum){
        addandloadey("dynoo", "swicthupcmg_exprd", "swt=0&pg="+pagenum);
        addandloadey("pagelink", "exprdevt_paging", "pg="+pagenum);
    }

    function addandloadey(dyndiv, phpname, sndtext){
        var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                document.getElementById(dyndiv).innerHTML = this.responseText;
                document.getElementById("txtpernm").value = "";
                document.getElementById("reswitch").classList.remove('faa-spin');
                document.getElementById("reswitch").classList.remove('faa-fast');
                document.getElementById("reswitch").classList.add('faa-wrench');
                document.getElementById("reswitch").classList.add('faa-slow');
            }};
            xhttp.open("POST", phpname, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(sndtext); 
    }
</script>
</body>
</html>