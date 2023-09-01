<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "My Tool Records";

 if($user_type == 8 && $user_dept == 3){
    if(isset($_POST['teamid'])){
    $teamid = words($_POST['teamid']);
    $teamarr=array();
    $tmsql = $link->query("SELECT * FROM `team_toollist` WHERE `team_id`='$teamid' LIMIT 1");
    $tmrow=$tmsql->fetch_array();
        $teamarr[0] = $tmrow['team_id'];
        $teamarr[1] = $tmrow['team_name'];
        $teamarr[2] = $tmrow['team_owner'];
        $teamarr[3] = $tmrow['team_switch'];

        if($teamarr[2]==$user_code){
            $link->query("UPDATE `team_toollist` SET `team_switch`=0 Where `team_id`=$teamarr[0]");
        }
    }
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
                        <h2 class="title-1 m-b-25"><?php echo $title; ?> <i class="fa-solid fa-users-rectangle"></i></h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        <ul class="nav nav-tabs">
                            <li class="nav-item"><a class="nav-link active" href="usercreds"><i class="fa-solid fa-users-gear"></i> Users Tool Records</a></li>
                            <li class="nav-item"><a class="nav-link active" href="teamcreds"><i class="fa-solid fa-users-rectangle"></i> Team Tool Records</a></li>
                            <li class="nav-item"><a class="nav-link active" aria-current="page" href="mycreds"><strong><i class="fa-solid fa-user-secret"></i> My Tool Records</a></strong></li>
                        </ul>
                            <div class="card" id="carddyn">
                                <div class="card-header">
                                    <div class="input-group">
                                        <input type="text" style="width:40%" placeholder="Tool Name Here..." id="inptxtid" class="form-control-sm form-bline text-center" onkeypress="dtckpress(this)">
                                        <button class="btn btn-secondary btn-sm" onclick="search()" title="Search Tool Name"><i class="fa-solid fa-magnifying-glass-arrow-right"></i> Search</button>
                                        <button class="btn btn-secondary btn-sm" onclick="cnfrmnew()" title="New Tool Group"><i class="fa-solid fa-file-circle-plus"></i> Create</button>
                                    </div>
                                </div>
                                <span id="tblcontentlst"></span>
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
        var teamtmr = "";
        function deleteitem(btnid){
            document.getElementById("tblrow_"+btnid).classList.add("table-warning");
            const dtld = btnid.split("-");
            var id = document.getElementById("teamtoolid").value;
            var elem = document.getElementById("srchwrd");
            var sel_col = document.getElementById("sel_col").value;
            var srtval = document.getElementById("srtval").value;

            const Toast = Swal.mixin({
                toast: true,
            })
            Toast.fire({
                icon: 'warning',
                title: 'Proceed deleting this item?',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="fa-solid fa-delete-left"></i> Delete',
                cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancel'
            }).then((result) => {
                if(result.isConfirmed){
                    sendpost("carddyn","itcard/toolcontent.php","id="+id+"&func=3&sval="+elem.value+"&sel_col="+sel_col+"&ordr="+srtval+"&delteam="+dtld[0]+"&delrow="+dtld[1]);
                }
                document.getElementById("tblrow_"+btnid).classList.remove("table-warning");
            })
        }

        function switchteam(i, name){
            Swal.fire({
                title: 'Move <b>'+name+'</b> to <i class="fa-solid fa-users-rectangle"></i> <b>Team Tool Records</b>?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<i class="fa fa-angle-double-left"></i> Move',
                cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancel'
            }).then((result) => {
                if(result.isConfirmed){ document.getElementById("fromswtch_"+i).submit(); }
            })
        }

        function cpytocb(elem){
            const dtld = elem.id.split("_")[1];
            val = document.getElementById("taaminp_"+dtld).value;
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

        function eyesh(elem){
            const dtld = elem.id.split("_")[1];
            var elemid = document.getElementById("lblsp_"+dtld);
            var inpid = document.getElementById("taaminp_"+dtld);
            if(elemid.innerHTML=="********"){ elem.title="Hide Password"; elem.innerHTML='<i class="fa-solid fa-eye-slash"></i>';
                elemid.innerHTML=inpid.value; inpid.type="text";
            }else{ elem.title="Show Password"; elem.innerHTML='<i class="fa-solid fa-eye"></i>';
                elemid.innerHTML="********"; inpid.type="password";
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
        function uptteamdata(id,typ){
            const dtld = id.split("-");
            var val = document.getElementById("taaminp_"+id);
            if(document.getElementById("taaminp_"+id).type=="email"){
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                if(!val.value.match(mailformat)){
                    val.focus();
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
            if(dtld!=0&&val.value!=""){
                sendpost("teanspn_"+id, "itcard/upt_teamdata.php", "val="+val.value+"&tool="+dtld[0]+"&col="+dtld[1]+"&row="+dtld[2]);
                showelem("teanspn_"+id);
                hideelem("teamspn_"+id);
                if(document.getElementById("taaminp_"+id).type=="password"){ document.getElementById("lblsp_"+id).innerHTML = "********"; }
                else{ document.getElementById("lblsp_"+id).innerHTML = val.value; }
            }
        }

        function moveitem(teamid, sortid, mov){
            sendpost("formov", "itcard/move_teamcol.php", "id="+teamid+"&sortid="+sortid+"&mov="+mov);
        }

        function changedtlstatus(elem, id){
            const dtld = elem.id.split("_")[1];
            var xhttp1 = new XMLHttpRequest();
            xhttp1.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    if(this.responseText==0){
                        elem.innerHTML='<i class="fa-solid fa-eye-slash"></i>';
                        elem.classList.remove("btn-outline-success");
                        elem.classList.add("btn-outline-danger");
                        elem.title="Click to Show";
                    }else if(this.responseText==1){
                        elem.innerHTML='<i class="fa-solid fa-eye"></i>';
                        elem.classList.remove("btn-outline-danger");
                        elem.classList.add("btn-outline-success");
                        elem.title="Click to Hide";
                    }
                    opentool(id,0);
                }
            };
            xhttp1.open("POST", "itcard/upt_colstatus.php", true);
            xhttp1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp1.send("colid="+dtld);
        }

        function updtooldtl(elem, id){
            const i = elem.id.split("_")[1];
            var name = document.getElementById("inpdtl_"+i).value;
            var type = document.getElementById("seldtl_"+i).value;
            if(name!=""&&type!=""){
            var xhttp1 = new XMLHttpRequest();
            xhttp1.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    document.getElementById("btndtlupd_"+i).style.display="none";
                    document.getElementById("inpdtl_"+i).style.color="";
                    document.getElementById("seldtl_"+i).style.color="";
                    opentool(id,0);
                }
            };
            xhttp1.open("POST", "itcard/upt_colname.php", true);
            xhttp1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp1.send("colid="+i+"&colname="+name+"&coltype="+type);
            }
        }

        function uptinpt(elem){
            const i = elem.id.split("_")[1];
            elem.style.color = "red";
            document.getElementById("btndtlupd_"+i).style.display="inline";
        }

        function addcol(id){
            var connm = document.getElementById("entcn").value;
            var contyp = document.getElementById("tooltype").value;
            if(connm!=""&&contyp!=""){
                sendpost("dynteamtools", "itcard/manage_teamcol.php", "id="+id+"&name="+connm+"&contyp="+contyp);
                opentool(id,0);
            }
        }

        function managecol(id){
            Swal.fire({
                title: '<i class="fa fa-columns"></i> Manage Columns',
                html: '<div class="row" id="dynteamtools" style="overflow: hidden; padding:0px;"></div>',
                showCancelButton: false,
                showConfirmButton: false,
                showCloseButton: true,
                scrollbarPadding: false
            }).then((result) => {
                if(!result.isConfirmed){ opentool(id,0); }
            })
            sendpost("dynteamtools", "itcard/manage_teamcol.php", "id="+id+"&name=");
        }

        function srchname(elem){
            var id = document.getElementById("teamtoolid").value;
            var sel_col = document.getElementById("sel_col").value;
            var srtval = document.getElementById("srtval").value;
            elem.addEventListener("keypress", function(event){
                if (event.key === "Enter"){
                    event.preventDefault();
                    var func = 2;
                    if(elem.value==""){ func = 0; }
                    sendpost("carddyn","itcard/toolcontent.php","id="+id+"&func="+func+"&sval="+elem.value+"&sel_col="+sel_col+"&ordr="+srtval);
                }
            });
        }function clcksrch(){
            var id = document.getElementById("teamtoolid").value;
            var elem = document.getElementById("srchwrd");
            var sel_col = document.getElementById("sel_col").value;
            var srtval = document.getElementById("srtval").value;
            var func = 2;
            if(elem.value==""){ func = 0; }
            sendpost("carddyn","itcard/toolcontent.php","id="+id+"&func="+func+"&sval="+elem.value+"&sel_col="+sel_col+"&ordr="+srtval);
        }function changesort(chgval){
            var chgval = document.getElementById("srtval").value = chgval;
            clcksrch();
        }

        function dtckpress(elem){
            elem.addEventListener("keypress", function(event) {
                if (event.key === "Enter"){
                    event.preventDefault();
                    search();
                }
            });
        }

        function updtlname(btnid, tlnmid){
            var nm = document.getElementById(tlnmid);
            var id = document.getElementById("teamtoolid").value;
            var xhttp2 = new XMLHttpRequest();
            xhttp2.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){
                    nm.style.color = "";
                    hideelem(btnid.id);
                }
            };
            xhttp2.open("POST", "itcard/upd_teamtoolname.php", true);
            xhttp2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp2.send("toolname="+nm.value+"&tmtoolid="+id);
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

        function cleartimer(){ clearTimeout(teamtmr); }
        function tmrtrig(){ teamtmr = setTimeout(timerstart, 60000); }
        function timerstart(){
            var id = document.getElementById("teamtoolid").value;
            sendpost("carddyn","itcard/toolcontent.php","id="+id+"&func=0&sval=&sel_col=&ordr=SORT_ASC");
            teamtmr = setTimeout(timerstart, 60000);
        }

        function opentool(id, func){
            if(id!=""){
                sendpost("carddyn","itcard/toolcontent.php","id="+id+"&func="+func+"&sval=&sel_col=&ordr=SORT_ASC");
            }
        }

        function showcard(id){
            if(id%4!=0){
            document.getElementById("crdsz_"+id).classList.remove("col-md-3");
            document.getElementById("crdsz_"+id).classList.add("col-md-4");
            }
            document.getElementById("crdhdr_"+id).classList.remove("text-truncate");
            document.getElementById("crdhdr_"+id).style.color = "#0d6efd";
            document.getElementById("crdhdri_"+id).style.color = "#0d6efd";
            document.getElementById("crdhdr_"+id).style.fontWeight = "bold";
            document.getElementById("crdhdr_"+id).style.textDecoration = "underline";
            document.getElementById("crdftr_"+id).classList.remove("text-truncate");

            showelem("swtm_"+id);
        }

        function lesscard(id){
            if(id%4!=0){
            document.getElementById("crdsz_"+id).classList.remove("col-md-4");
            document.getElementById("crdsz_"+id).classList.add("col-md-3");
            }
            document.getElementById("crdhdr_"+id).classList.add("text-truncate");
            document.getElementById("crdhdr_"+id).style.color = "";
            document.getElementById("crdhdri_"+id).style.color = "";
            document.getElementById("crdhdr_"+id).style.fontWeight = "";
            document.getElementById("crdhdr_"+id).style.textDecoration = "";
            document.getElementById("crdftr_"+id).classList.add("text-truncate");

            hideelem("swtm_"+id);
        }

        function search(){
            var txt = document.getElementById("inptxtid");            
            sendpost("tblcontentlst","itcard/loadnmlist.php","search=1&tooltyp=0&toolnm="+txt.value);
        }

        function cnfrmnew(){
            var txt = document.getElementById("inptxtid");
            if(txt.value!=""){
                Swal.fire({
                    title: 'Proceed Creating '+txt.value+' Tool for the Team?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '<i class="fa-solid fa-folder-plus"></i> Create New Tool',
                    cancelButtonText: '<i class="fa-solid fa-folder-closed"></i> Close'
                }).then((result) => {
                    if(result.isConfirmed){
                        sendpost("tblcontentlst","itcard/loadnmlist.php","search=0&tooltyp=0&toolnm="+txt.value);
                        txt.value="";
                    }
                })
            }
        }

        function loadstart(){
            sendpost("tblcontentlst","itcard/loadnmlist.php","search=1&tooltyp=0&toolnm=");
        }

        function sendpost(sid, loc, post){
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200 && this.responseText != ""){
                    document.getElementById(sid).innerHTML = this.responseText;

                    var div = document.getElementById("thetblsize");
                    if(div){ div.scrollTop = div.scrollHeight; }
                }
            };
            xhttp.open("POST", loc, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(post);
        }

        loadstart();
    </script>
</body>
</html>
<?php } $link->close(); ?>