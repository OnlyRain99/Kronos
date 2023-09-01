<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

$title = "Personal Data Sheet";
 if($user_type == 5 && $user_dept == 2){
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>
<body>
    <div class="page-wrapper">
    <?php include 'header-m.php'; ?>
    <?php include 'sidebar.php'; ?>
        <div class="page-container">
            <div class="main-content p-t-20" >
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                        <h2 class="title-1 m-b-25"><b>[PDS]</b> <?php echo $title; ?></h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="input-group">
                                    <div class="form-floating" id="ffdprtmntdsply">
                                        <select class="form-select" id="empdepartment" onchange="getmenuinpval(1)">
                                        <option value="all">ALL</option>
                                        <?php $dptsql=$link->query("SELECT * From `gy_department` ORDER BY `id_department` ASC");
                                            while($dptrow=$dptsql->fetch_array()){ ?>
                                            <optgroup label="<?php echo $dptrow['name_department']; ?>">
                                            <?php $depsql=$link->query("SELECT * From `gy_accounts` Where `gy_dept_id`='".$dptrow['id_department']."' AND `gy_acc_status`=0 ORDER BY `gy_acc_name` ASC");
                                                while($deprow=$depsql->fetch_array()){ ?>
                                                <option value="<?php echo $deprow['gy_acc_id']; ?>"><?php echo $deprow['gy_acc_name']; ?></option>
                                            <?php } ?>
                                            </optgroup>
                                            <?php } ?>
                                        </select>
                                        <label for="empdepartment">Department List</label>
                                    </div>
                                    <div class="form-floating">
                                        <input type="text" id="empname" class="form-control minwid-120" oninput="getmenuinpval(1)">
                                        <label for="empname">Employee Name/SiBS ID</label>
                                    </div>
                                    <div class="form-floating">
                                        <select class="form-select" id="slc_status" onchange="getmenuinpval(1)">
                                            <option value="0">Active</option>
                                            <option value="1">Deactivated</option>
                                        </select>
                                        <label for="slc_status">Search</label>
                                    </div>
                                    <button class="btn btn-lg" id="loadonpress" onclick="refreshpsdtbl()" title="Refresh"><i class="fas fa-sync faa-wrench animated faa-slow"></i></button>
                                    <button class="btn btn-lg faa-parent animated-hover" onclick="showhistory()" title="Show Update History"><i class="fa-solid fa-clock-rotate-left faa-spin faa-reverse faa-slow"></i></button>
                                </div>

                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" style="font-family: 'Calibri'; font-size: 14px;">
                                            <thead class="table-dark">
                                                <tr style="padding:4px;" class="text-center text-nowrap">
                                                    <th scope="col" >SiBS ID</th>
                                                    <th scope="col" >Name</th>
                                                    <th scope="col" >Company Email</th>
                                                    <th scope="col" >Work From</th>
                                                    <th scope="col" >Rate</th>
                                                    <th scope="col" >Level</th>
                                                    <th scope="col" >Controller Name</th>
                                                    <th scope="col" >PDS</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbldynpds"></tbody>
                                            <div id="pdsalert"></div>
                                        </table>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <?php //include 'footer.php'; ?>
                </div>
            </div>
        </div>
    </div>
    <?php include 'scripts.php'; ?>
    <script type="text/javascript">
        function showhistory(){
            Swal.fire({
                title: '<span class="faa-parent animated-hover"><i class="fa-solid fa-clock-rotate-left faa-spin faa-reverse faa-slow"></i> PDS Update History</span>',
                html: '<div class="table-responsive"><table class="table table-striped table-hover"><thead><tr><th>Date</th><th>Updated By</th><th>PDS Of</th><th>Input Changes</th></tr></thead><tbody id="pdsuh"></tbody></table></div> <nav aria-label="..."><ul class="pagination flex-wrap" id="pagediv"></div></nav>',
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

        function thealertstt(status){
            var title = "";
            if(status=="success"){ title="Personal Data Sheet successfully updated"; }
            else if(status=="error"){ title= "Error updating PDS"; }
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
            refreshpsdtbl();
        }

        function refreshpsdtbl(){
            var crrpg = document.getElementById("actpage").value;
            getmenuinpval(crrpg);
        }

        function slctaccnt(elemsel){
            sendpost("proacc", "pdsform/search_account.php", "dprtid="+elemsel.value);
        }

        function pdscards(sibsid){

            Swal.fire({
                html: '<div class="row" id="pdsinfo"><i class="fa fa-refresh faa-spin faa-fast animated" aria-hidden="true"></i> Loading . . .</div>',
                width: 'auto',
                showCancelButton: true,
                showCloseButton: true,
                <?php if($myaccount!=36){?>
                showConfirmButton: false,
                <?php } ?>
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<i class="fa-solid fa-file-pen"></i> Update',
                cancelButtonText: '<i class="fa-solid fa-rectangle-xmark"></i> Close'
            }).then((result) => {
                if(result.isConfirmed){
                    var pclnm=document.getElementById("flname").value;
                    var pcfnm=document.getElementById("ffname").value;
                    var pcmnm=document.getElementById("fmname").value;

                    var pcgdr=document.getElementById("perg").value;
                    var pcdob=document.getElementById("perdob").value;
                    var pccst=document.getElementById("percs").value;

                    var ecdh=document.getElementById("empdh").value;
                    var ecal=document.getElementById("asloc").value;

                    var cchma=document.getElementById("ctctha").value;
                    var ccema=document.getElementById("ctctea").value;
                    var ccmad=document.getElementById("ctctma").value;
                    var ccsad=document.getElementById("ctctsa").value;
                    var ccgnm=document.getElementById("ctctgid").value;
                    var ccgid=document.getElementById("ctctgidn").value;
                    var ccpem=document.getElementById("ctctpe").value;
                    var cccnu=document.getElementById("ctctcn").value;
                    var ccecp=document.getElementById("ctctecp").value;
                    var ccecn=document.getElementById("ctctecn").value;

                    var pcajd=document.getElementById("proajd").value;
                    var pcacc=document.getElementById("proacc").value;
                    var pcman=document.getElementById("promng").value;

                    var tcnhod=document.getElementById("tcnhod").value;
                    var fstsd=document.getElementById("fstsd").value;
                    var fsted=document.getElementById("fsted").value;
                    var certdt=document.getElementById("certdt").value;
                    var pstsd=document.getElementById("pstsd").value;
                    var psted=document.getElementById("psted").value;
                    var fugold=document.getElementById("fugold").value;
                    var grbasd=document.getElementById("grbasd").value;
                    var grbaed=document.getElementById("grbaed").value;
                    var promd=document.getElementById("promd").value;

                    var proemp=document.getElementById("proemp").value;
                    var probemp=document.getElementById("probemp").value;
                    var regemp=document.getElementById("regemp").value;

                    var tagdate=document.getElementById("tagdate").value;
                    var davdate=document.getElementById("davdate").value;
                    var hybdate=document.getElementById("hybdate").value;

                    sendpost("pdsalert", "pdsform/pdsupdate.php", "pclnm="+pclnm+"&pcfnm="+pcfnm+"&pcmnm="+pcmnm+"&pcgdr="+pcgdr+"&pcdob="+pcdob+"&pccst="+pccst+"&ecdh="+ecdh+"&ecal="+ecal+"&cchma="+cchma+"&ccema="+ccema+"&ccmad="+ccmad+"&ccsad="+ccsad+"&ccgnm="+ccgnm+"&ccgid="+ccgid+"&ccpem="+ccpem+"&cccnu="+cccnu+"&ccecp="+ccecp+"&ccecn="+ccecn+"&pcajd="+pcajd+"&pcacc="+pcacc+"&pcman="+pcman+"&sibsid="+sibsid+"&tcnhod="+tcnhod+"&fstsd="+fstsd+"&fsted="+fsted+"&certdt="+certdt+"&pstsd="+pstsd+"&psted="+psted+"&fugold="+fugold+"&grbasd="+grbasd+"&grbaed="+grbaed+"&promd="+promd+"&proemp="+proemp+"&probemp="+probemp+"&regemp="+regemp+"&tagdate="+tagdate+"&davdate="+davdate+"&hybdate="+hybdate);
                }
            })
            sendpost("pdsinfo", "pdsform/pdsinfo.php", "sibsid="+sibsid);
        }

        function getmenuinpval(pgnm){
            var dptlst = document.getElementById("empdepartment").value;
            var nmsrch = document.getElementById("empname").value;
            var status = document.getElementById("slc_status").value;
            document.getElementById("loadonpress").innerHTML = "<i class='fas fa-sync faa-spin animated faa-fast'></i>";
            sendpost("tbldynpds", "pdsform/loadtblpdslst.php", "dptlst="+dptlst+"&nmsrch="+nmsrch+"&status="+status+"&pgnm="+pgnm);
        }

        function sendpost(sid, loc, post){
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200 && this.responseText != ""){
                    if(sid=="pdsalert"){
                        thealertstt(this.responseText);
                    }else{
                        document.getElementById(sid).innerHTML = this.responseText;
                        document.getElementById("loadonpress").innerHTML = "<i class=\"fas fa-sync faa-wrench animated faa-slow\"></i>";
                    }
                }else{ document.getElementById(sid).innerHTML = ""; }
            };
            xhttp.open("POST", loc, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(post);
        }

        getmenuinpval(1);
    </script>
</body>
</html>
<?php } $link->close(); ?>