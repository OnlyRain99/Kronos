<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "Denied Escalation Request";

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
                            <a class="nav-link" href="escalaterequest">Pending Request</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="approverequest">Approved Request</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="rejectrequest">Denied Request</a>
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

        function showmrdtls(stid, sctm){
            Swal.fire({
                html: '<div id="tordsply"><i class="fa fa-refresh faa-spin faa-fast animated" aria-hidden="true"></i> Loading . . .</div>',
                width: '1000px',
                showCancelButton: true,
                showDenyButton: false,
                showConfirmButton: false,
                cancelButtonText: '<i class="fa-solid fa-rectangle-xmark"></i> Close'
            })
            sendpost("tordsply", "escalationrequest/escalateform.php", "stid="+stid+"&sctm="+sctm);
        }

        function loadtblescrqst(pgnm){
            sendpost("tblescrqst", "escalationrequest/showrejectesc.php", "pgnm="+pgnm);
        }

        function sendpost(sid, loc, post){
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){
                        document.getElementById(sid).innerHTML = this.responseText;
                }
            };
            xhttp.open("POST", loc, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(post);
        }

        loadtblescrqst(1);
    </script>
</body>
</html>
<!-- end document-->
