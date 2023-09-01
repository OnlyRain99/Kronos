<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "Employee's Record";

    $notify = @$_GET['note'];

    if ($notify == "added") {
        $note = "Employee Added";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "update") {
        $note = "Update Successful";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "delete") {
        $note = "Delete Successful";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "userupdatefail") {
        $note = "User Account Update Fail";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "exist") {
        $note = "SiBS ID already exist";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "s_space") {
        $note = "White spaces is not allowed";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "email_exist") {
        $note = "Email already exist";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "s_zero") {
        $note = "Only 0 is not allowed";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "empty") {
        $note = "Empty search";
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

    $query_one = "SELECT * From `gy_employee` Order By `gy_emp_email` ASC";

    $query_two = "SELECT COUNT(`gy_emp_id`) From `gy_employee` Order By `gy_emp_email` ASC";

    $query_three = "SELECT * From `gy_employee` Order By `gy_emp_email` ASC ";

    $my_num_rows = 15;

    include 'my_pagination.php';

    //get accounts
    $info=$link->query("SELECT * From `gy_employee` Order By `gy_emp_email` ASC");
    $countinfo=$info->num_rows;
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
                            <h2 class="title-1 m-b-25"><?php echo $title; ?> <span style="font-size: 15px; text-transform: lowercase;" class="badge badge-success"><?php echo 0 + $countinfo; ?> results</span></h2>
                            <div style="<?php echo $notes; ?>" id="<?php echo $noteid; ?>" class="sufee-alert alert with-close alert-<?php echo $notec; ?> alert-dismissible fade show">
                                <span class="badge badge-pill badge-<?php echo $notec; ?>">Alert</span>
                                <?php echo $note; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <form method="post" enctype="multipart/form-data" action="redirect_manager" onsubmit="return validateForm(this);">
                    <div class="row">
                        <div class="col-lg-2">
                            <a href="add_employee"><button type="button" class="btn btn-success" title="click to add employee ..."><i class="fa fa-plus"></i> Add Employee</button></a>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <input type="text" name="emp_search" class="form-control" placeholder="search name/email/id number here ..." autofocus required>
                            </div>
                        </div>
						<div class="col-lg-1"><a href="loa_reports" class="btn faa-parent animated-hover float-right" title="LOA Report Generator"><i class="fa-solid fa-file-export faa-horizontal"></i></a></div>
                        <div class="col-lg-1">
                            <btn class="btn  faa-parent animated-hover" onclick="showhistory()" title="Show Update History"><i class="fa-solid fa-clock-rotate-left faa-spin faa-reverse faa-slow"></i></btn>
                        </div>
                    </div>
                    </form>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive m-b-40">
                                        <table class="table table-striped table-hover" style="font-family: 'Calibri'; font-size: 14px;">
                                            <thead class="table-dark">
                                                <tr class="mybg">
                                                    <th class="text-center" style="padding: 10px">Time Records</th>
                                                    <th class="text-center" style="padding: 10px">Schedule</th>
                                                    <th class="text-center" style="padding: 10px">ID</th>
                                                    <th style="padding: 10px">Fullname</th>
                                                    <th style="padding: 10px">Email</th>
                                                    <th style="padding: 10px">Account</th>
                                                    <th class="text-center" style="padding: 10px"><i class="fa fa-lock"></i></th>
                                                    <th class="text-center" style="padding: 10px"><i class="fa fa-edit"></i></th>
                                                    <th class="text-center" style="padding: 7px"><b>PDS</b></th>
                                                    <th class="text-center" style="padding: 10px"><i class="fa fa-trash"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php  
                                                    while ($inforow=$query->fetch_array()) {

                                                        //emp
                                                        if ($inforow['gy_emp_type'] == 1) {
                                                            $emptypecolor = "#000";
                                                            $emptype = "L1";
                                                        }else if ($inforow['gy_emp_type'] == 2) {
                                                            $emptypecolor = "green";
                                                            $emptype = "L2";
                                                        }else if ($inforow['gy_emp_type'] == 3) {
                                                            $emptypecolor = "blue";
                                                            $emptype = "L3";
                                                        }else if ($inforow['gy_emp_type'] == 4) {
                                                            $emptypecolor = "#217777";
                                                            $emptype = "L4";
                                                        }else if ($inforow['gy_emp_type'] == 5) {
                                                            $emptypecolor = "#000";
                                                            $emptype = "L5";
                                                        }else if ($inforow['gy_emp_type'] == 11) {
                                                            $emptypecolor = "#000";
                                                            $emptype = "Scheduler";
                                                        }else if ($inforow['gy_emp_type'] == 12) {
                                                            $emptypecolor = "#000";
                                                            $emptype = "CompBen";
                                                        }else{
                                                            $emptypecolor = "#000";
                                                            $emptype = "Regular";
                                                        }

                                                        //get supervisor
                                                        $supcode=words($inforow['gy_emp_supervisor']);
                                                        $getsuper=$link->query("SELECT `gy_full_name` From `gy_user` where `gy_user_id`='$supcode'");
                                                        $super=$getsuper->fetch_array();
                                                ?>
                                                <tr class="mybg">
                                                    <td class="text-center" style="padding: 0px; color: #fff;"><a href="view_record?cd=<?php echo $inforow['gy_emp_id']; ?>" onclick="window.open(this.href, 'mywin',
        'left=20,top=20,width=1280,height=720,toolbar=1,resizable=0'); return false;">View</a></td>
                                                    <td class="text-center" style="padding: 0px; color: #fff;"><a href="view_schedule?cd=<?php echo $inforow['gy_emp_id']; ?>" onclick="window.open(this.href, 'mywin',
        'left=20,top=20,width=1280,height=720,toolbar=1,resizable=0'); return false;"><button type="button" title="click to view schedule ..." class="btn btn-primary btn-sm btn-block"><i class="fa fa-calendar"></i></button></a></td>
                                                    <td class="text-center" style="padding: 0px; color: <?php echo $emptypecolor; ?>;"><?php echo $inforow['gy_emp_code']; ?></td>
                                                    <td style="padding: 0px; color: <?php echo $emptypecolor; ?>;"><?php echo $inforow['gy_emp_fullname']; ?></td>
                                                    <td style="padding: 0px; color: <?php echo $emptypecolor; ?>;"><?php echo $inforow['gy_emp_email']; ?></td>
                                                    <td style="padding: 0px; color: <?php echo $emptypecolor; ?>;"><?php echo get_acc_name($inforow['gy_acc_id']); ?></td>
                                                    <td class="text-center" style="padding: 0px; color: #fff;"><button type="button" class="btn btn-warning btn-sm btn-block" data-target="#show_<?php echo $inforow['gy_emp_id']; ?>" data-toggle="modal" title="click to show employee account details ..."><i class="fa fa-lock"></i></button></td>
                                                    <td class="text-center" style="padding: 0px; color: #fff;"><a href="edit_employee?cd=<?= $inforow['gy_emp_id']; ?>" class="btn btn-info btn-sm btn-block"><button type="button" class="" title="click to edit employee details ..."><i class="fa fa-edit"></i></button></a></td>
                                                    <td class="text-center" style="padding: 0px; color: <?php echo $emptypecolor; ?>;"><form method="POST" action="pds"><input type="hidden" name="emphidcode" value="<?php echo $inforow['gy_emp_code']; ?>"><button type="submit" class="btn btn-primary btn-block btn-sm" ><b>PDS</b></button></form></td>
                                                    <td class="text-center" style="padding: 0px; color: #fff;"><button type="button" class="btn btn-danger btn-sm btn-block" data-target="#delete_<?php echo $inforow['gy_emp_id']; ?>" data-toggle="modal" title="click to remove employee from the list ..."><i class="fa fa-trash"></i></button></td>
                                                </tr>

                                                <?php  
                                                    $delete_link = "delete_emp?mode=normal&cd=".$inforow['gy_emp_id'];
                                                ?>

                                                <?php include 'modal_emp.php'; ?>

                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="text-center"> 
                                 <ul class="pagination">
                                    <?php echo $paginationCtrls; ?>
                                 </ul>
                            </div>
                         </div>
                    </div>
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
        $("#activate-alert").fadeTo(5000, 500).slideUp(500, function(){
            $("#activate-alert").slideUp(500);
        });

        function validateForm(formObj) {
            formObj.submit.disabled = true; 
            formObj.submit.innerHTML = "saving data ..."; 
            return true;  
        }

        function showhistory(){
            Swal.fire({
                title: '<span class="faa-parent animated-hover"><i class="fa-solid fa-clock-rotate-left faa-spin faa-reverse faa-slow"></i> PDS Update History</span>',
                html: '<div class="table-responsive"><table class="table table-striped table-hover"><thead><tr><th>Date</th><th>Updated By</th><th>PSD Of</th><th>Input Changes</th></tr></thead><tbody id="pdsuh"></tbody></table></div> <nav aria-label="..."><ul class="pagination flex-wrap" id="pagediv"></div></nav>',
                showCancelButton: false,
                showCloseButton: true,
                showConfirmButton: false,
                focusConfirm: true,
                width: 'auto'
            })
            get_xhttp(1);
        }
        function get_xhttp(dfnm){
            var pgcntt=10;
            var actpg = "";
            var xhttp1 = new XMLHttpRequest();
                xhttp1.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200){
                        if(this.responseText!=""){
                            var xarr = JSON.parse(this.responseText);
                            document.getElementById("pdsuh").innerHTML="";
                            document.getElementById("pagediv").innerHTML="";

                            var start=((xarr.length-1)-((pgcntt*dfnm)-pgcntt));
                            var end=start-pgcntt; if(end<0){ end=0; }
                            for(var i=start;i>=end;i--){
                                document.getElementById("pdsuh").innerHTML+='<tr class="text-nowrap"><td>'+cvtdate(xarr[i].datetime)+'</td><td id="updby_'+i+'">'+jscvtnm('updby_'+i, "pdsform/searchname.php", "sibsid="+xarr[i].by)+'</td><td id="psdown_'+i+'">'+jscvtnm('psdown_'+i, "pdsform/searchname.php", "sibsid="+xarr[i].owner)+'</td><td class="text-left">'+xarr[i].changes+'</td></tr>';
                            }

                            for(var i=1;i<=Math.ceil((xarr.length-1)/pgcntt);i++){
                                actpg = ""; if(i==dfnm){ actpg = "active"; }
                                document.getElementById("pagediv").innerHTML+='<li class="page-item '+actpg+'"><a class="page-link" href="#" onclick="get_xhttp('+i+')" >'+i+'</a></li>';
                            }
                        }
                    }
                };
            xhttp1.open("POST", "pdsform/history.php", true);
            xhttp1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp1.send("");
        }
        function jscvtnm(elemid, loc, sibsid){
            var xhttp2 = new XMLHttpRequest();
                xhttp2.onreadystatechange = function(){
                   if(this.readyState == 4 && this.status == 200 && this.responseText != ""){
                        document.getElementById(elemid).innerHTML=this.responseText;
                   }
                };
            xhttp2.open("POST", loc, true);
            xhttp2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp2.send(sibsid);
        }
        function cvtdate(date){
            const d = new Date(date);
            const monthNames = ["January", "February", "March", "April", "May", "June","July", "August", "September", "October", "November", "December"];

            var hours = d.getHours();
            var AmOrPm = hours >= 12 ? 'pm' : 'am';
            hours = (hours % 12) || 12;
            var minutes = d.getMinutes();
            var finalTime = hours + ":" + minutes + " " + AmOrPm;

            return monthNames[d.getMonth()]+" "+d.getDate()+", "+d.getFullYear()+" "+finalTime;
        }
    </script>

</body>

</html>
<!-- end document-->
