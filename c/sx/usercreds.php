<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "Users Tools Records";

 if($user_type == 8 && $user_dept == 3){
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
                        <h2 class="title-1 m-b-25"><?php echo $title; ?> <i class="fa-solid fa-users-gear"></i></h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        <ul class="nav nav-tabs">
                            <li class="nav-item"><a class="nav-link active" aria-current="page" href="#"><strong><i class="fa-solid fa-users-gear"></i> Users Tool Records</strong></a></li>
                            <li class="nav-item"><a class="nav-link active" href="teamcreds"><i class="fa-solid fa-users-rectangle"></i> Team Tool Records</a></li>
                            <li class="nav-item"><a class="nav-link active" href="mycreds"><i class="fa-solid fa-user-secret"></i> My Tool Records</a></li>
                        </ul>
                            <div class="card">
                                <div class="card-header">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <div class="form-floating">
                                            <select class="form-select" id="fil_type" onchange="switchflr(this)">
                                            <option value="0">A to Z</option>
                                            <option value="1">Search</option>
                                            <option value="2">Value</option>
                                            </select>
                                            <label for="sel_recent">Filter Type</label>
                                        </div>
                                        </div>
                                        <div class="form-floating" id="swtchsearch">
                                            <select class="form-select" id="shw_a2z" onchange="showstartc(this, 1)">
                                            <option value="">All</option>
                                            <?php for($i=65;$i<91;$i++){ ?>
                                            <option value="<?php echo strtolower(chr($i)); ?>"><?php echo strtoupper(chr($i)); ?></option>
                                            <?php } ?>
                                            </select>
                                            <label for="shw_a2z">Show</label>
                                        </div>
                                        <div class="form-floating">
                                            <select class="form-select" id="sel_fol" onchange="showstartc(document.getElementById('shw_a2z'), 1)">
                                                <option value="0">First Name</option>
                                                <option value="1">Last Name</option>
                                            </select>
                                            <label for="sel_fol">Sort By</label>
                                        </div>
                                        <div class="form-floating">
                                            <select class="form-select" id="sel_acde" onchange="showstartc(document.getElementById('shw_a2z'), 1)">
                                                <option value="2">All</option>
                                                <option value="0" selected>Active</option>
                                                <option value="1">Inactive</option>
                                            </select>
                                            <label for="sel_acde">Status</label>
                                        </div>
                                        <div class="form-floating">
                                            <select class="form-select" id="sel_tool" onchange="showstartc(document.getElementById('shw_a2z'), 1)"></select>
                                            <label for="sel_tool">Tools</label>
                                        </div>
                                            <button class="btn btn-outline-dark" onclick="mngtools()"><i class="fas fa-tools"></i> Manage</button>
                                            <button class="btn btn-lg" onclick="showstartc(document.getElementById('shw_a2z'), 1)" id="btnrefid"><i class="fa-solid fa-arrow-rotate-right faa-spin animated faa-slow"></i></button>
                                    </div>

                                </div>
                                    <div class="table-responsive">
                                        <table class="table table table-bordered" style="font-family: 'Calibri'; font-size: 14px;"  id="credsbody">
                                        </table>
                                    </div>
                                <div class="card-footer text-muted" id="credfoot"></div>
                                <div id="dyn"></div>
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
        var glbtmr = "";

        function cpytocb(elem, val){
            val = document.getElementById("inpidval_"+val).value;
            var el = document.createElement('textarea');
            el.value = val;
            el.setAttribute('readonly', '');
            el.style = {display: 'none'};
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            elem.innerHTML='<i class="fa-solid fa-clipboard"></i>';
        }

        function eyesh(elem, id){
            var elemid = document.getElementById("lblsp_"+id);
            var inpid = document.getElementById("inpidval_"+id);
            if(elemid!=null){
                if(elemid.innerHTML=="********"){ elemid.innerHTML = inpid.value; elem.innerHTML='<i class="fa-solid fa-eye-slash"></i>'; elem.title="Hide Password"; inpid.type="text"; }
                else{ elemid.innerHTML = "********"; elem.innerHTML='<i class="fa-solid fa-eye"></i>'; elem.title="Show Password"; inpid.type="password"; }
            }
        }

        function kpresssv(elem){
            elem.addEventListener("keypress", function(event) {
                if (event.key === "Enter"){
                    event.preventDefault();
                    const dtld = elem.id.split("_")[1];
                    document.getElementById("uptcllbtn_"+dtld).click();
                }
            });
        }
        function upddata(dataid, cellid, hidshwid){
            var dataval = document.getElementById("inpidval_"+cellid);
            if(document.getElementById("inptyp_"+cellid).value.toLowerCase()=="email"){
                var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                if(!dataval.value.match(mailformat)){
                    dataval.focus();
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
                      icon: 'error',
                      title: 'Invalid Email Address!'
                    })
                    return false;
                }
            }
                hideelem("inpt_"+hidshwid);
                showelem("dspt_"+hidshwid);
                document.getElementById("lblsp_"+cellid).innerText = dataval.value;
                if(dataid!="empty"){
                    sendpost("dyn", "itcard/upt_data.php", "dataid="+dataid+"&type=0&dataval="+dataval.value);
                }else{
                    var toolid = document.getElementById("fndtlid_"+cellid).value;
                    var empcod = document.getElementById("fndecd_"+cellid).value;
                    sendpost("dyn", "itcard/upt_data.php", "type=1&toolid="+toolid+"&empcod="+empcod+"&dataval="+dataval.value);
                }
        }

        function moveitem(dtld, sortid, direct){
            sendpost("tooldetailyst", "itcard/sort_move.php", "dtld="+dtld+"&sortid="+sortid+"&direct="+direct);
        }

        function updtooldtl(elem){
            const dtld = elem.id.split("_")[1];
            var inpid = "inpdtl_"+dtld;
            var selid = "seldtl_"+dtld;
            var inpval = document.getElementById(inpid).value;
            var selval = document.getElementById(selid).value;
            sendpost("tooldetailyst", "itcard/upt_details.php", "dtld="+dtld+"&inpval="+inpval+"&selval="+selval);
            document.getElementById(inpid).classList.remove("is-invalid");
            document.getElementById(selid).classList.remove("is-invalid");
            elem.style.display = "none";
            showstartc(document.getElementById('shw_a2z'), 1);
        }

        function changedtlstatus(elem){
            const dtld = elem.id.split("_")[1];
            if(elem.innerHTML=='<i class="fa-solid fa-eye"></i>'){ elem.innerHTML='<i class="fa-solid fa-eye-slash"></i>'; elem.classList.remove("btn-outline-success"); elem.classList.add("btn-outline-danger"); }
            else if(elem.innerHTML=='<i class="fa-solid fa-eye-slash"></i>'){ elem.innerHTML='<i class="fa-solid fa-eye"></i>'; elem.classList.remove("btn-outline-danger"); elem.classList.add("btn-outline-success"); }
            sendpost("tooldetailyst", "itcard/upt_detailstatus.php", "dtld="+dtld);
            showstartc(document.getElementById('shw_a2z'), 1);
        }

        function uptinpt(elem, btnid){
            elem.classList.add("is-invalid");
            showelem(btnid);
        }

        function updtails(toolid){
            var label = document.getElementById("tooldlabel").value;
            var type = document.getElementById("tooltype").value;
            sendpost("tooldetailyst", "itcard/upt_tdetails.php", "label="+label+"&type="+type+"&toolid="+toolid);
            document.getElementById("tooldlabel").value = "";
            showstartc(document.getElementById('shw_a2z'), 1);
        }

        function actdea(toolid, stt){
            var xhttp2 = new XMLHttpRequest();
            xhttp2.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){
                    document.getElementById("toolstt").value = stt;
                    uptoolst();
                    setinpname(toolid);
                    sendpost("sel_tool", "itcard/sel_toollst.php", "");
                    showstartc(document.getElementById('shw_a2z'), 1);
                }
            };
            xhttp2.open("POST", "itcard/upt_toolstatus.php", true);
            xhttp2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp2.send("toolid="+toolid+"&status="+stt);
        }

        function updtoolname(toolid){
            var toolname = document.getElementById("tooldname").value;
            var xhttp1 = new XMLHttpRequest();
            xhttp1.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){
                    uptoolst();
                    document.getElementById("tooldname").style.color = "black";
                    hideelem('updtoolname');
                    sendpost("sel_tool", "itcard/sel_toollst.php", "");
                    showstartc(document.getElementById('shw_a2z'), 1);
                }
            };
            xhttp1.open("POST", "itcard/upt_toolname.php", true);
            xhttp1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp1.send("toolid="+toolid+"&toolname="+toolname);
        }

        function setinpname(btnid){
            sendpost("tooldsply", "itcard/show_tooldsply.php", "bid="+btnid);
        }

        function showelem(idname){
            if(document.getElementById(idname)!=null){
                document.getElementById(idname).style.display = "inline";
            }
        }
        function hideelem(idname){
            if(document.getElementById(idname)!=null){
                document.getElementById(idname).style.display = "none";
            }
        }

        function uptoolst(){
            var sst = document.getElementById("toolstt").value;
            var seach = document.getElementById("toolnms").value;
            sendpost("lstoftool", "itcard/show_toolst.php", "stt="+sst+"&cha="+seach);
        }

        function mngtools(){
            Swal.fire({
                title: '<i class="fas fa-tools"></i> Manage Tools',
                html: '<div class="row" id="dyntools"></div>',
                showCancelButton: false,
                showConfirmButton: false,
                showCloseButton: true,
                width: '850px'
            }).then((result) => {
                if(!result.isConfirmed){
					document.getElementById("btnrefid").onclick();
                }
            })
            sendpost("dyntools", "itcard/manage_tools.php", "");
        }

        function switchflr(elem){
            if(elem.value==0){
                sendpost("swtchsearch", "itcard/show_namesearch.php", "eval="+elem.value);
            }else if(elem.value==1){
                sendpost("swtchsearch", "itcard/show_namesearch.php", "eval="+elem.value);
            }
            showstartc(document.getElementById("shw_a2z"), 1);
        }

        function showstartc(elem, pgnum){
            var filtype  = document.getElementById("fil_type").value;
            var sortby  = document.getElementById("sel_fol").value;
            var status  = document.getElementById("sel_acde").value;
            var seltool  = document.getElementById("sel_tool").value;
            sendpost("credsbody", "itcard/show_startc.php", "cha="+elem.value+"&sortby="+sortby+"&status="+status+"&pgnum="+pgnum+"&seltool="+seltool+"&filtype="+filtype);
            sendpost("credfoot", "itcard/show_footc.php", "cha="+elem.value+"&status="+status+"&pgnum="+pgnum);
			sendpost("sel_tool", "itcard/sel_toollst.php", "");
        }

        function sendpost(sid, loc, post){
            document.getElementById("btnrefid").innerHTML = '<i class="fa-solid fa-arrow-rotate-right faa-spin animated faa-fast"></i>';
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200 && this.responseText != ""){
                    document.getElementById(sid).innerHTML = this.responseText;
                }
                    document.getElementById("btnrefid").innerHTML = '<i class="fa-solid fa-arrow-rotate-right faa-spin animated faa-slow"></i>';
            };
            xhttp.open("POST", loc, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(post);
        }

        function cleartimer(){
            clearTimeout(glbtmr);
        }
        function settimer(){
            glbtmr = setTimeout(tblrefresh, 60000);
        }

        function tblrefresh(){
            showstartc(document.getElementById('shw_a2z'), 1);
            glbtmr = setTimeout(tblrefresh, 60000);
        }

        function trigonchg(){
            document.getElementById("shw_a2z").onchange();
            sendpost("sel_tool", "itcard/sel_toollst.php", "");
            glbtmr = setTimeout(tblrefresh, 60000);
        }

        trigonchg();
    </script>
</body>
</html>
<?php } $link->close(); ?>