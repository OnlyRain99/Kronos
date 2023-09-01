<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "Pending Escalation Request";

    $notify = @$_GET['note'];

    if ($notify == "invalid") {
        $note = "Invalid ...";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "approve") {
        $note = "Request Approved";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "deny") {
        $note = "Request Denied";
        $notec = "success";
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
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>
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
                            <h2 class="title-1 m-b-25"><?php echo $title; ?> <i class="fas fa-arrow-circle-up"></i></h2>
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
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="escalaterequest">Pending Request</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="approverequest">Approved Request</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="rejectrequest">Denied Request</a>
                        </li>
                    </ul>
                        <!--<div class="card-header"></div>-->
                        <div class="card-body">
                            <!--<div class="row">
                                <div class="col-md-12">-->
                                    <div class="table-responsive m-b-40">
                                        <table class="table table-striped-columns table-bordered table-hover" style="font-family: 'Calibri'; font-size: 14px;">
                                            <thead class="table-dark">
                                                <tr style="padding:4px;" class="text-center text-nowrap">
                                                    <th scope="col" >Type of Request</th>
                                                    <th scope="col" >Manager's Name</th>
                                                    <th scope="col" >Date Requested</th>
                                                    <th scope="col" >Date Filed</th>
                                                    <th scope="col" >PROCESS</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tblescrqst"></tbody>
                                        </table>
                                    </div>
                              <!--  </div>
                            </div>-->
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
        $("#activate-alert").fadeTo(5000, 500).slideUp(500, function(){
            $("#activate-alert").slideUp(500);
        });

        function lv18pndngbdg(){
            sendpost("lv18pndngbdg", "escalationrequest/pendingbadgeupdate.php", "");
        }

        function escalert(status){
            var title="";
            if(status=="success"){ title="Request Escalation has been Approved"; }
            else if(status=="error"){ title="Error Processing the Request"; }
            else if(status=="denysuccess"){ status="info"; title="Request has been Denied"; }
            else if(status=="reqnotmet"){ status="warning"; title="Requirement not Meet"; }
            else if(status=="donotchng"){ status="error"; title="Do Not Change Any Value"; }
            else if(status=="duplicated"){ status="error"; title="The requested date was already approved and do not allowed to have anymore changes."; }
            else if(status=="reqcancl"){ status="info"; title="Request has been cancelled by the employee"; }
            toastalert(status, title);
        }

        function toastalert(status, title){
            const Toast = Swal.mixin({
                      toast: true,
                      position: 'top-end',
                      showConfirmButton: false,
                      timer: 3000,
                      timerProgressBar: true,
                      didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                      }
                })
                Toast.fire({
                      icon: status,
                      title: title
                })
        }

        function showmrdtls(stid, sctm){
            Swal.fire({
                html: '<div id="tordsply"><i class="fa fa-refresh faa-spin faa-fast animated" aria-hidden="true"></i> Loading . . .</div>',
                width: '1000px',
                showCancelButton: true,
                showDenyButton: true,
                showConfirmButton: true,
                confirmButtonColor: '#3085d6',
                denyButtonColor: '#d33',
                confirmButtonText: '<i class="fa-regular fa-thumbs-up"></i> Approve Request',
                denyButtonText: '<i class="fa-regular fa-thumbs-down"></i> Reject Request',
                cancelButtonText: '<i class="fa-solid fa-rectangle-xmark"></i> Close'
            }).then((result) => {
                if(result.isConfirmed){
                    sendpost("cnfrmesclt", "escalationrequest/processescalation.php", "stid="+stid+"&sctm="+sctm+"&proc=1");
                }else if(result.isDenied){ isdeniedq(stid, sctm); }
            })
            sendpost("tordsply", "escalationrequest/escalateform.php", "stid="+stid+"&sctm="+sctm);
        }

        function isdeniedq(stid, sctm){
            html='<div class="form-floating mb-2"><textarea id="loarejremarks" class="form-control" rows="3" placeholder="type the reason here ..." autofocus required></textarea><label for="loarejremarks">Reason for Rejection</label></div>';
            const Toast = Swal.mixin({
                toast: true,
            })
            Toast.fire({
                      icon: 'warning',
                      title: 'Comment',
                      html: html,
                      showCancelButton: true,
                      confirmButtonColor: '#d33',
                      cancelButtonColor: '#3085d6',
                      confirmButtonText: '<i class="fa-solid fa-handshake-slash"></i> Reject',
                      cancelButtonText: '<i class="fa-solid fa-person-running"></i> Cancel'
                }).then((result) => {
                    var rmrks = document.getElementById("loarejremarks").value;
                    if(result.isConfirmed && rmrks!=""){
                        sendpost("cnfrmesclt", "escalationrequest/processescalation.php", "stid="+stid+"&sctm="+sctm+"&proc=2&rmrks="+rmrks);
                    }else{ showmrdtls(stid, sctm); }
                })
        }

        function loadtblescrqst(){
            sendpost("tblescrqst", "escalationrequest/requesttbl.php", "");
            lv18pndngbdg();
        }

        function sendpost(sid, loc, post){
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){
                    if(sid=="cnfrmesclt"){ escalert(this.responseText); loadtblescrqst(); }
                    else{
                        document.getElementById(sid).innerHTML = this.responseText;
                    }
                }
            };
            xhttp.open("POST", loc, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(post);
        }

        setInterval(loadtblescrqst, 20000);
        loadtblescrqst();
    </script>
</body>
</html>
<!-- end document-->
