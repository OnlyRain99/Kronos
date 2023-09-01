<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "Requested Forms";

 if($user_type == 1 && $user_dept == 2){
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
                        <h2 class="title-1 m-b-25"><?php echo $title; ?> <i class="fa-solid fa-file-export"></i></h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">

                                    <div class="input-group">
                                        <div class="form-floating">
                                            <select class="form-select" id="slt_rtype" onchange="sel_recent()">
                                                <option value="0">All</option>
                                                <option value="loa" selected>Leave Request</option>
                                                <option value="7">Escalate Missed Log (ML)</option>
                                                <option value="8">Escalate Schedule Adjustment (SA)</option>
                                                <option value="6">Escalate Overtime (OT)</option>
                                                <option value="2">Escalate Rest Day OT (RDOT)</option>
                                                <option value="5">Escalate Early Out (EO)</option>
                                            </select>
                                        <label for="slt_rtype">Request Type</label>
                                        </div>

                                        <div class="input-group-prepend">
                                            <div class="form-floating">
                                                <select class="form-select" id="sel_recent" onchange="sel_recent()">
                                                    <option value="0">Recent Forms</option>
                                                    <option value="1">Old Forms</option>
                                                    <option value="2">Published Forms</option>
                                                </select>
                                                <label for="sel_recent">Select Forms</label>
                                            </div>
                                        </div>

                                        <div class="form-floating">
                                            <select class="form-select" id="slt_apnd" onchange="sel_recent()">
                                                <option value="0">All</option>
                                                <option value="1" selected>Approved Request</option>
                                                <option value="2">Pending Request</option>
                                            </select>
                                        <label for="slt_apnd">Request Status</label>
                                        </div>

                                        <button class="btn btn-lg" id="loadonpress" onclick="sel_recent()"><i class="fas fa-sync faa-wrench animated faa-slow"></i></button>
                                    </div>

                                    <div class="card-body" id="dynamictbl"></div>
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
    var namarr = [];
    var intmr = "";

        function toastalert(status){
            var title="";
            if(status=="success"){ title="LOA is now visible in the DTR"; }
            else if(status=="error"){ title="Error publishing this request"; }
            else if(status=="nocredits"){ status="warning"; title="No Credits Available"; }
            else if(status=="notsched"){ status="error"; title="Invalid LOA. Not scheduled to work on this day"; }
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

        function togradbtn(elem){
            var radbtn = document.getElementsByName(elem.name);
            for(var i=0;i<radbtn.length;i++){
                if(radbtn[i].checked==true){ document.getElementById(elem.name+"-"+radbtn[i].id).classList.add("active"); }
                else { document.getElementById(elem.name+"-"+radbtn[i].id).classList.remove("active"); }
            }
        }

        function publishloa(rid){
            Swal.fire({
                title: 'Publish LOA Confirmation',
                showCancelButton: false,
                showConfirmButton: false,
                html: '<div id="pblshloaid"><i class="fa fa-refresh faa-spin faa-fast animated" aria-hidden="true"></i> Loading . . .</div>',
                width: 'auto'
            })
            sendpost("pblshloaid","requestform/get_loacnfrmtn.php", "rid="+rid);
        }
        function publishloacfrm(rfid){
            document.getElementById("loadonpress").innerHTML = "<i class=\"fas fa-sync faa-spin animated faa-fast\"></i>";
            sendpost("pblshloaidtmp", "requestform/publishreq.php", "rfid="+rfid+"&typid=loa");
            sel_recent();
        }

        function publish(rfid, typid, ucode){
            Swal.fire({
                title: 'Publish Confirmation',
                html: '<div id="pblshid"></div>',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-cloud-upload-alt"></i> Publish the Request Form',
                cancelButtonText: '<i class="far fa-comment"></i> Revalidate the Request Form',
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#DD9F07',
                width: 'auto'
            }).then((result) => {
                if(result.isConfirmed){
            document.getElementById("loadonpress").innerHTML = "<i class=\"fas fa-sync faa-spin animated faa-fast\"></i>";
            sendpost("pblshid", "requestform/publishreq.php", "rfid="+rfid+"&typid="+typid);
            sel_recent();
                }else{ chat_panel(rfid, typid, ucode); }
            })
            sendpost("pblshid", "requestform/get_reqdetails.php", "rfid="+rfid+"&typid="+typid);
        }

        function chat_panel(rfid, typid, ucode){
            get_namecon(rfid, typid);
            Swal.fire({
                title: '<i class="fas fa-comments faa-wrench animated-hover"></i> Request Status Confirmation',
                html: '<div id="pnlbdy" class="panel-body"><ul class="chat" id="ulchat">'+
                '</ul></div>'+
                '<div class="panel-footer">'+
                '<div class="input-group">'+
                '<input id="msgl3l4" type="text" class="form-control input-sm" autocomplete="off" placeholder=" Type message here..." />'+
                '<span class="input-group-btn"><button class="btn btn-warning" onclick="updchtmsg('+rfid+', \''+typid+'\', \''+ucode+'\')" id="btnsndico"><i class="far fa-comment-alt"></i> Send</button></span>'+
                '</div>'+ 
                '</div><div id="trashid"></div>',
                showConfirmButton: false,
                showCancelButton: false,
                showCloseButton: true,
                focusConfirm: true,
                width: '600px'
            }).then((result) => {
                if(!result.isConfirmed){
                    source.close();
					sel_recent();
                }
            })

            if(typeof(EventSource) !== "undefined"){
                var source = new EventSource("requestform/get_livedate.php");
                source.onmessage = function(event){ get_xhttp(rfid, typid, ucode); };
            }
        }

        function get_namecon(rfid, typid){
            namarr = [];
            var xhttp3 = new XMLHttpRequest();
                xhttp3.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200){ namarr = JSON.parse(this.responseText); }
                };
            xhttp3.open("POST", "requestform/get_namecon.php", true);
            xhttp3.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp3.send("file="+rfid+"&typid="+typid);
        }

        function get_xhttp(rfid, typid, ucode){
            var xhttp1 = new XMLHttpRequest();
                xhttp1.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200){
                        if(this.responseText!=""){
                            var chtmsg = "";
                            var xarr = JSON.parse(this.responseText);
                            for(var i=xarr.length-1;i>=0;i--){
                                if(xarr[i].msgby==ucode){
                                    chtmsg='<li class="left clearfix"><div class="chat-body clearfix"><div class="header"><strong class="primary-font pull-right"> '+xarr[i].msgby+' </strong> <small class="pull-left text-muted"> <i class="fa-regular fa-clock"></i> '+timesince(xarr[i].datetime)+' </small></div><br><p class="pull-right text-left">'+xarr[i].message+'</p></div></li>'+chtmsg;
                                }else{
                                    chtmsg='<li class="right clearfix"><div class="chat-body clearfix"><div class="header"><strong class="primary-font pull-left"> '+xarr[i].msgby+' </strong> <small class="pull-right text-muted"> <i class="fa-regular fa-clock"></i> '+timesince(xarr[i].datetime)+' </small></div><br><p class="pull-left text-left">'+xarr[i].message+'</p></div></li>'+chtmsg;
                                }
                            }
                                changeto_name(ucode, chtmsg);
                        }
                    }
                };
            xhttp1.open("POST", "requestform/read_file.php", true);
            xhttp1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp1.send("file="+rfid+"&typid="+typid);
        }

        function changeto_name(ucode, chtmsg){
			var cycid = 1;
            for(var i=0;i<namarr.length;i++){
                if(namarr[i].ucode==ucode){
                    chtmsg = replaceAll(chtmsg, '<strong class="primary-font pull-right"> '+namarr[i].ucode+' </strong>', '<strong class="primary-font pull-right"> '+namarr[i].fullname+' </strong>');
                }else{
                    chtmsg = replaceAll(chtmsg, '<strong class="primary-font pull-left"> '+namarr[i].ucode+' </strong>', '<strong class="primary-font pull-left"> '+namarr[i].fullname+' </strong>');
                }
            }
			if(chtmsg.length>document.getElementById("ulchat").innerHTML.length){ cycid=0; }
            document.getElementById("ulchat").innerHTML = chtmsg;
            if(cycid==0){
                var obdv = document.getElementById("pnlbdy");
                    obdv.scrollTop = obdv.scrollHeight;
            document.getElementById("btnsndico").innerHTML = "<i class=\"far fa-comment-alt\"></i> Send";
            }
        }

        function replaceAll(str, find, replace) {
            var escapedFind=find.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
            return str.replace(new RegExp(escapedFind, 'g'), replace);
        }

        function switch_page(pg, opt){
            var rstt = document.getElementById("slt_apnd").value;
            var rtyp = document.getElementById("slt_rtype").value;
            sendpost("dynamictbl", "requestform/formtable.php", "pg="+pg+"&opt="+opt+"&rstt="+rstt+"&rtyp="+rtyp);
        }

        function more_info(rfid, opt){
            Swal.fire({
                title: 'Request Escalation Form Details',
                icon: 'info',
                html: '<div id="reqformd"><i class="fa fa-refresh faa-spin faa-fast animated" aria-hidden="true"></i></div>',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Download Request Form',
                showCloseButton: true,
                focusConfirm: true,
                width: 'auto'
            }).then((result) => {
                if(result.isConfirmed){
                    more_info(rfid, opt);
                    window.open("requestform/download_form?escid="+rfid+"&type="+opt);
                }
            })
            sendpost("reqformd", "requestform/req_formd.php", "rfid="+rfid+"&opt="+opt);
        }

        function more_infoloa(rid){
            Swal.fire({
                width: 'auto',
                title: 'Request LOA Form Details',
                showCancelButton: false,
                showConfirmButton: false,
                html: '<div id="pltdtyl"><i class="fa fa-refresh faa-spin faa-fast animated" aria-hidden="true"></i> Loading . . .</div>'
            })
            sendpost("pltdtyl","requestform/leaveview.php", "rid="+rid);
        }

        function timesince(date){
            const d = new Date(date);
            var vdt="";
            var seconds = Math.floor((new Date() - d) / 1000);
            var interval = seconds / 31536000;
            if(interval>1){ if(Math.floor(interval)==1){vdt=" year ago";}else{vdt=" years ago";} return Math.floor(interval)+vdt; }
            interval = seconds / 2592000;
            if(interval>1){ if(Math.floor(interval)==1){vdt=" month ago";}else{vdt=" months ago";} return Math.floor(interval)+vdt; }
            interval = seconds / 86400;
            if(interval>1){ if(Math.floor(interval)==1){vdt=" day ago";}else{vdt=" days ago";} return Math.floor(interval)+vdt; }
            interval = seconds / 3600;
            if(interval>1){ if(Math.floor(interval)==1){vdt=" hour ago";}else{vdt=" hours ago";} return Math.floor(interval)+vdt; }
            interval = seconds / 60;
            if(interval>1){ if(Math.floor(interval)==1){vdt=" minute ago";}else{vdt=" minutes ago";} return Math.floor(interval)+vdt; }
            return Math.floor(seconds) + " seconds ago";
        }

        function updchtmsg(rfid, typid, ucode){
            var msg = document.getElementById("msgl3l4").value;
            document.getElementById("btnsndico").innerHTML = "<i class=\"fa fa-spinner faa-spin faa-fast animated\"></i> Processing ...";
            document.getElementById("msgl3l4").value="";
            var xhttp2 = new XMLHttpRequest();
            xhttp2.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){ get_xhttp(rfid, typid, ucode); }
            };
            xhttp2.open("POST", "requestform/updatemsgfile.php", true);
            xhttp2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp2.send("file="+rfid+"&typid="+typid+"&ucode="+ucode+"&msg="+msg);            
        }

        function switch_page(pg, opt){
            var rstt = document.getElementById("slt_apnd").value;
            var rtyp = document.getElementById("slt_rtype").value;
            document.getElementById("dynamictbl").innerHTML = "";
            sendpost("dynamictbl", "requestform/formtable.php", "pg="+pg+"&opt="+opt+"&rstt="+rstt+"&rtyp="+rtyp);
        }

        function sel_recent(){
            var sel_source = "";
            var sid = "";
            var post = "";
            var elem = document.getElementById("sel_recent").value;
            var rstt = document.getElementById("slt_apnd").value;
            var rtyp = document.getElementById("slt_rtype").value;
            document.getElementById("loadonpress").innerHTML = "<i class=\"fas fa-sync faa-spin animated faa-fast\"></i>";
            if(elem==0){
                sel_source="requestform/formtable.php"; sid="dynamictbl";
                post = "opt="+elem+"&rstt="+rstt+"&rtyp="+rtyp;
                clearInterval(intmr);
                intmr = setInterval(function() { sel_recent(); }, 3 * 1000);
            }else if(elem==1){
                sel_source="requestform/formtable.php"; sid="dynamictbl";
                post = "opt="+elem+"&pg=1&rstt="+rstt+"&rtyp="+rtyp;
                clearInterval(intmr);
            }else if(elem==2){
                sel_source="requestform/formtable.php"; sid="dynamictbl";
                post = "opt="+elem+"&pg=1&rstt="+rstt+"&rtyp="+rtyp;
                clearInterval(intmr);
            }
            sendpost(sid, sel_source, post);
        }

        function sendpost(sid, loc, post){
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){
                    if(sid=="pblshloaidtmp"){ toastalert(this.responseText); }
                    else if(sid=="pblshloaid"){ 
                        if(this.responseText=="switch"){ sel_recent(); swal.close() }else{ document.getElementById(sid).innerHTML = this.responseText; }
                        }
                    else{ document.getElementById(sid).innerHTML = this.responseText; }
                    document.getElementById("loadonpress").innerHTML = "<i class=\"fas fa-sync faa-wrench animated faa-slow\"></i>";
                }
            };
            xhttp.open("POST", loc, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(post);   
        }

        document.getElementById("sel_recent").onchange();
        //setInterval(function() { sel_recent(); }, 3 * 1000);

    </script>
</body>
</html>
<?php } ?>