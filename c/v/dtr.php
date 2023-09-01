<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "Daily time Record";
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
                        <h2 class="title-1 m-b-25"><?php echo $title; ?> <i class="fas fa-history"></i></h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="form-floating">
                                                <select class="form-select" id="slt_by" onchange="sel_by()">
                                                    <option value="0">Select All</option>
                                                    <option value="1">Level</option>
                                                    <option value="2">Account</option>
                                                    <option value="3">Department</option>
                                                </select>
                                                <label for="slt_by">Select By</label>
                                            </div>
                                            <div class="form-floating" id="ff_option">
                                                <select class="form-select" id="slt_2nd" onchange="sel_search()">
                                                    <option value="0">Search Emplyee</option>
                                                    <option value="1">Select Employee</option>
                                                </select>
                                                <label for="slt_2nd">Option</label>
                                            </div>
                                            <div class="form-floating" id="ff_srchlst">
                                                <input type="text" id="empname" class="form-control minwid-120" oninput="autosearch();">
                                                <label class="empname">Employee Name or ID</label>
                                            </div>
                                        </div>

                                            <div class="form-floating">
                                                <input type="number" min="0" id="inp_year" class="form-control" style="max-width: 100px" value="<?php echo date("Y"); ?>" oninput="loadpublish()">
                                                <label for="inp_year">Year</label>
                                            </div>
                                            <div class="form-floating">
                                                <select class="form-select minwid-100" id="slt_mth" onchange="loadpublish()">
                                                <?php for($i=1;$i<=12;$i++){ ?>
                                                    <option value="<?php echo $i; ?>" <?php if(($i==date("m")-1 && date("d")<=15)||($i==date("m") && date("d")>15)) { echo "selected"; } ?>><?php echo date("F", mktime(0,0,0,$i,10)); ?></option>
                                                <?php } ?>
                                                </select>
                                                <label for="slt_mth">Month</label>
                                            </div>
                                            <div class="form-floating">
                                                <select class="form-select" id="slt_coff" onchange="loadpublish()">
                                                    <option value="1">1</option>
                                                    <option value="2" <?php if(date("d")<=15){echo "selected"; } ?>>2</option>
                                                </select>
                                                <label for="slt_coff">CutOff</label>
                                            </div>
                                            <div class="form-floating">
                                                <select class="form-select" id="ff_status" onchange="autosearch();">
                                                    <option value="2">Select All</option>
                                                    <option value="0" selected>Active</option>
                                                    <option value="1">Deactivated</option>
                                                </select>
                                                <label for="ff_status">Status</label>
                                            </div>
                                    </div>

                                    <div class="" id="dynamictbl">
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div style="position: absolute; right:0%;" id="dlpblshdtrid"></div>
                                <div class="card-header fw-bold">Published DTR List</div>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                            <thead>
                                                <tr style="padding:4px;" class="text-center text-nowrap bg-primary text-white">
                                                    <th scope="col" title="Remove"><i class="fa-solid fa-trash"></i></th>
                                                    <th scope="col" title="Download DTR"><i class="fa-solid fa-file-arrow-down"></i></th>
                                                    <th scope="col" >Employee Name</th>
                                                    <th scope="col" >No Of Hours</th>
                                                    <th scope="col" >Late|UT</th>
                                                    <th scope="col" >Absences</th>
                                                    <th scope="col" >Reg|OT</th>
                                                    <th scope="col" >RD|Reg</th>
                                                    <th scope="col" >RD|OT</th>
                                                    <th scope="col" >SH|Reg</th>
                                                    <th scope="col" >SH|OT</th>
                                                    <th scope="col" >SH|RD|Reg</th>
                                                    <th scope="col" >SH|RD|OT</th>
                                                    <th scope="col" >LH|Reg</th>
                                                    <th scope="col" >LH|OT</th>
                                                    <th scope="col" >LH|RD|Reg</th>
                                                    <th scope="col" >LH|RD|OT</th>
                                                    <th scope="col" >ND|Reg</th>
                                                    <th scope="col" >ND|Reg|OT</th>
                                                    <th scope="col" >ND|RD|Reg</th>
                                                    <th scope="col" >ND|RD|OT</th>
                                                    <th scope="col" >ND|SH</th>
                                                    <th scope="col" >ND|SH|OT</th>
                                                    <th scope="col" >ND|SH|RD</th>
                                                    <th scope="col" >ND|SH|RD|OT</th>
                                                    <th scope="col" >ND|LH</th>
                                                    <th scope="col" >ND|LH|OT</th>
                                                    <th scope="col" >ND|LH|RD</th>
                                                    <th scope="col" >ND|LH|RD|OT</th>
                                                </tr>
                                            </thead>
                                            <tbody  class="" id="dynpublished"></tbody >
                                        </table>
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
        function removepblshdtr(pblshid){
            var name = document.getElementById("pblshname_"+pblshid).innerText;
           const Toast = Swal.mixin({
                      toast: true,
                })
            Toast.fire({
                title: 'Remove '+name+' Published DTR?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="fa-solid fa-trash"></i> Remove',
                cancelButtonText: '<i class="fa-solid fa-xmark"></i> Close'
            }).then((result) => {
                if(result.isConfirmed){
                    sendpost("publishpdsfsid", "dtr/dtr_removepublisheddtr.php", "pblshid="+pblshid);
                }
            })
        }

        function dwnldpblshdtr(){
            var year = document.getElementById("inp_year").value;
            var month = document.getElementById("slt_mth").value;
            var cutoff=  document.getElementById("slt_coff").value;
            //sendpost("dwnlddtrpbls", "dtr/dtr_downloadpub;ishdtr.php", "year="+year+"&month="+month+"&cutoff="+cutoff, 0);

            var urlx = "year="+year+"&month="+month+"&cutoff="+cutoff;
            var wintmp = window.open("dtr/dtr_downloadpub?"+urlx, "_blank");
        }

        function loadpublish(){
            var year = document.getElementById("inp_year").value;
            var month = document.getElementById("slt_mth").value;
            var cutoff=  document.getElementById("slt_coff").value;
            sendpost("dynpublished", "dtr/dtr_loadpublishedtbl.php", "year="+year+"&month="+month+"&cutoff="+cutoff, 0);
            autosearch();
        }

        function uptcrdresponse(status){
            var title="";
            if(status=="success"){ title="DTR has been Published"; }
            else if(status=="error"){ title="Error Publishing DTR"; }
            else if(status=="dltsuccess"){ status="info"; title="Item has been remove"; }
            else if(status=="dltsuccess"){ status="dlterr"; title="Error removing item"; }
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

        function autosearch(){
            var sval = document.getElementById("empname").value;
            var selby = document.getElementById("slt_by").value;
            var selop = document.getElementById("slt_2nd").value;
            var year = document.getElementById("inp_year").value;
            var month = document.getElementById("slt_mth").value;
            var cutoff=  document.getElementById("slt_coff").value;
            if(sval!=""){
            var status = document.getElementById("ff_status").value;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){
                    document.getElementById("dynamictbl").innerHTML = this.responseText;
                }
            };
            xhttp.open("POST", "dtr_search.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("sval="+sval+"&status="+status+"&selby="+selby+"&selop="+selop+"&year="+year+"&month="+month+"&cutoff="+cutoff);
            }else{ document.getElementById("dynamictbl").innerHTML = ""; }
        }

        function sel_search(){
            var selby = document.getElementById("slt_by").value;
            var selop = document.getElementById("slt_2nd").value;
            var xhttp = new XMLHttpRequest();
            if(selby==0 && selop==0){
            sendpost("ff_srchlst", "dtr_searchtxtbx.php", "", 0);
            }else if(selby==0 && selop==1){
            sendpost("ff_srchlst", "dtr_emplist.php", "selby="+selby, 0);
            }else if(selby==1){
            sendpost("ff_srchlst", "dtr_emplist.php", "selby="+selby+"&lvlsel="+selop, 0);                
            }else if(selby==2){
            sendpost("ff_srchlst", "dtr_emplist.php", "selby="+selby+"&lvlsel="+selop, 0);                
            }else if(selby==3){
            sendpost("ff_srchlst", "dtr_emplist.php", "selby="+selby+"&lvlsel="+selop, 0);                
            }
        }

        function sel_by(){
            var selby = document.getElementById("slt_by").value;
            var sel_source = "";
            if(selby==0){ sel_source = "dtr_selall.php"; }
            else if(selby==1){ sel_source = "dtr_levellst.php"; }
            else if(selby==2){ sel_source = "dtr_accountlst.php"; }
            else if(selby==3){ sel_source = "dtr_departmentlst.php"; }
            sendpost("ff_option", sel_source, "", 1);
        }

        function viewdtr(sid){
            var year = document.getElementById("inp_year").value;
            var month = document.getElementById("slt_mth").value;
            var cutoff = document.getElementById("slt_coff").value;
            var dmrate = document.getElementById("dmrateid_"+sid).value;
            var cmputetyp=  document.getElementById("dtrcmputetyp_"+sid).value;

            Swal.fire({
                title: 'Daily Time Record <i class="fas fa-history faa-wrench animated">',
                html: '<div id="dyndtr"><i class="fas fa-history faa-spin faa-reverse faa-fast animated"></i> Loading ...</div>',
                confirmButtonColor: '#3085d6',
                showCancelButton: true,
                confirmButtonText: '<i class="fa-solid fa-calendar-check"></i> Publish DTR',
                cancelButtonText: '<i class="fa-solid fa-rectangle-xmark"></i> Close',
                showCloseButton: true,
                width: 'auto'
            }).then((result) => {
                if(result.isConfirmed){
                    var dtrhol=document.getElementById("dtrhol").value;

                    var dtrnof=document.getElementById("dtrnof").value;
                    var dtrltut=document.getElementById("dtrltut").value;
                    var dtrabcs=document.getElementById("dtrabcs").value;

                    var dtrregot=document.getElementById("dtrregot").value;
                    var dtrrdreg=document.getElementById("dtrrdreg").value;
                    var dtrrdot=document.getElementById("dtrrdot").value;
                    const dtrholreg = [], dtrholot = [], dtrholrdreg = [], dtrholrdot = [];
                    for(var i=1;i<dtrhol;i++){
                        dtrholreg[i-1]=document.getElementById("dtrholreg_"+i).value;
                        dtrholot[i-1]=document.getElementById("dtrholot_"+i).value;
                        dtrholrdreg[i-1]=document.getElementById("dtrholrdreg_"+i).value;
                        dtrholrdot[i-1]=document.getElementById("dtrholrdot_"+i).value;
                    }
                    var dtrndreg=document.getElementById("dtrndreg").value;
                    var dtrndregot=document.getElementById("dtrndregot").value;
                    var dtrndrdreg=document.getElementById("dtrndrdreg").value;
                    var dtrndrdot=document.getElementById("dtrndrdot").value;
                    const dtrholnd = [], dtrholndot = [], dtrholndrd = [], dtrholndrdot = [];
                    for(i=1;i<dtrhol;i++){
                        dtrholnd[i-1]=document.getElementById("dtrholnd_"+i).value;
                        dtrholndot[i-1]=document.getElementById("dtrholndot_"+i).value;
                        dtrholndrd[i-1]=document.getElementById("dtrholndrd_"+i).value;
                        dtrholndrdot[i-1]=document.getElementById("dtrholndrdot_"+i).value;
                    }
                    sendpost("publishpdsfsid", "dtr/dtr_publish.php", "sibsid="+sid+"&year="+year+"&month="+month+"&cutoff="+cutoff+"&dmrate="+dmrate+"&dtrnof="+dtrnof+"&dtrltut="+dtrltut+"&dtrabcs="+dtrabcs+"&dtrregot="+dtrregot+"&dtrrdreg="+dtrrdreg+"&dtrrdot="+dtrrdot+"&dtrholreg="+dtrholreg+"&dtrholot="+dtrholot+"&dtrholrdreg="+dtrholrdreg+"&dtrholrdot="+dtrholrdot+"&dtrndreg="+dtrndreg+"&dtrndregot="+dtrndregot+"&dtrndrdreg="+dtrndrdreg+"&dtrndrdot="+dtrndrdot+"&dtrholnd="+dtrholnd+"&dtrholndot="+dtrholndot+"&dtrholndrd="+dtrholndrd+"&dtrholndrdot="+dtrholndrdot+"&cmputetyp="+cmputetyp, 0);
                }
            })
            var loc="dtr_view.php";
            if(cmputetyp==1){ loc="dtr/dtr_slide2hours.php"; }
            else if(cmputetyp==2){ loc="dtr/dtr_flixible.php"; }

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
                document.getElementById("dyndtr").innerHTML = this.responseText;
            }};
            xhttp.open("POST", loc, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("sibsid="+sid+"&year="+year+"&month="+month+"&cutoff="+cutoff+"&dmrate="+dmrate);
        }

        function sendpost(sid, loc, post, by){
            if(sid!="dynpublished" && sid!="publishpdsfsid"){ document.getElementById("dynamictbl").innerHTML = ""; }
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){
                    if(sid=="publishpdsfsid"){ uptcrdresponse(this.responseText); loadpublish(); }
                    else{
                        document.getElementById(sid).innerHTML = this.responseText;
                        if(by==1){ sel_search(); }                        
                        if(sid=="dynpublished"){
                            if(this.responseText!=""){ document.getElementById("dlpblshdtrid").innerHTML='<button class="btn btn-outline-secondary" onclick="dwnldpblshdtr()"><i class="fa-solid fa-file-arrow-down"></i> Download Published DTR</button>'; }
                            else{ document.getElementById("dlpblshdtrid").innerHTML=''; }
                        }
                    }
                }
            };
            xhttp.open("POST", loc, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(post);   
        }

        loadpublish();
    </script>
</body>
</html>