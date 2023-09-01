<?php
    include '../config/conn.php';
    include '../config/connnk.php';
    $title = "Real Time Ticket Tracker";
?>
<!DOCTYPE html>
<html lang="en">
<?php  include 'head.php'; ?>
<body>
    <div class="page-wrapper" style="padding: 20px;">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="title-1 m-b-25"><?php echo $title; ?></h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="col-md-6 float-left border text-nowrap">Production Date : </div>
                                        <div class="col-md-6 float-left border text-nowrap"><?php echo date("F d, Y"); ?></div>
                                        <div class="col-md-6 float-left border text-nowrap">Production Day : </div>
                                        <div class="col-md-6 float-left border text-nowrap"><?php echo date("l"); ?></div>
                                    </div><div class="col-md-8"></div>

                                        <div class="col-md-12 float-left">&nbsp;</div>

                                    <div class="col-md-4">
                                        <div class="col-md-12 float-left border text-nowrap"><strong>REAL TIME HOURLY</strong></div>

                                        <div class="col-md-9 float-left border text-nowrap">Scheduled Primary Reps (Hr) : </div>
                                        <div id="rt_primary" class="col-md-3 float-left border text-center text-nowrap">0</div>

                                        <div class="col-md-9 float-left border text-nowrap">Scheduled Bench Reps (Hr) : </div>
                                        <div id="rt_bench" class="col-md-3 float-left border text-center text-nowrap">0</div>

                                        <div class="col-md-9 float-left border text-nowrap">Total Scheduled Reps (Hr) : </div>
                                        <div id="rt_totalpb" class="col-md-3 float-left border text-center text-nowrap">0</div>

                                        <div class="col-md-12 float-left border">&nbsp;</div>

                                        <div class="col-md-9 float-left border text-nowrap">Present Primary Reps (Hr) : </div>
                                        <div id="rt_pprimary" class="col-md-3 float-left border text-center text-nowrap">0</div>

                                        <div class="col-md-9 float-left border text-nowrap">Present Bench Reps (Hr) : </div>
                                        <div id="rt_pbench" class="col-md-3 float-left border text-center text-nowrap">0</div>

                                        <div class="col-md-9 float-left border text-nowrap">Total Present Reps (Hr) : </div>
                                        <div id="rt_ptotalpb" class="col-md-3 float-left border text-center text-nowrap">0</div>

                                        <div class="col-md-12 float-left border">&nbsp;</div>

                                        <div class="col-md-9 float-left border text-nowrap">True Absenteeism % : </div>
                                        <div id="rt_trueabse" class="col-md-3 float-left border text-center text-nowrap">0</div>

                                        <div class="col-md-9 float-left border text-nowrap">Absenteeism After Coverage % : </div>
                                        <div id="rt_abseafco" class="col-md-3 float-left border text-center text-nowrap">0</div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="col-md-12 float-left border text-nowrap"><strong>DAILY TARGET VS ACTUAL</strong></div>

                                        <div class="col-md-10 float-left border text-nowrap">Target Productivity For Primary Reps (Scheduled) : </div>
                                        <div id="rt_tarpri" class="col-md-2 float-left border text-center text-nowrap">0</div>

                                        <div class="col-md-10 float-left border text-nowrap">Target Productivity For Bench Reps (Scheduled) : </div>
                                        <div id="rt_tarben" class="col-md-2 float-left border text-center text-nowrap">0</div>

                                        <div class="col-md-10 float-left border text-nowrap">Total Target Productivity For All Agents (Scheduled) : </div>
                                        <div id="rt_tarpriben" class="col-md-2 float-left border text-center text-nowrap">0</div>

                                        <div class="col-md-12 float-left border">&nbsp;</div>

                                        <div class="col-md-10 float-left border text-nowrap">Actual Productivity For Primary Reps : </div>
                                        <div id="rt_temapri" class="col-md-2 float-left border text-center text-nowrap">0</div>

                                        <div class="col-md-10 float-left border text-nowrap">Actual Productivity For Bench Reps : </div>
                                        <div id="rt_temaben" class="col-md-2 float-left border text-center text-nowrap">0</div>

                                        <div class="col-md-10 float-left border text-nowrap">Total Actual Productivity For All Agents : </div>
                                        <div id="rt_temapriben" class="col-md-2 float-left border text-center text-nowrap">0</div>

                                        <div class="col-md-12 float-left border">&nbsp;</div>

                                        <div class="col-md-10 float-left border text-nowrap">True Attainment Rate % (Daily) : </div>
                                        <div id="rt_prodrun" class="col-md-2 float-left border text-center text-nowrap">0</div>

                                        <div class="col-md-10 float-left border text-nowrap">Attainment After Coverage % (Daily) :</div>
                                        <div id="rt_prodruncov" class="col-md-2 float-left border text-center text-nowrap">0</div>
                                    </div>
                                    <div class="col-md-2"></div>

                                    <div class="col-md-12">&nbsp;</div>

                                    <div class="col-md-12"><div class="col-md-12 float-left border text-nowrap"><center><strong>TEAM VIEW</strong></center></div></div>

                                </div>
                                <div class="row" id="tldynamic"></div>
                            </div>
                            <div class="card-body">
                                <div class="input-group-prepend">
                                    <div class="form-floating">
                                    <select class="form-select minwid-120" id="fltr_loc" onchange="rfrshicn(this)">
                                        <option value="">All</option>
                                        <option value="0">Davao</option>
                                        <option value="1">Tagum</option>
                                    </select>
                                    <label class="fltr_loc" id="lblloc">Location</label>
                                    </div>
                                    <div class="form-floating">
                                    <select class="form-select minwid-120" id="fltr_sup" onchange="rfrshicn(this)">
                                        <option value="">All</option>
                                        <?php $supsql=$link->query("SELECT `gy_user_id`,`gy_full_name` From `gy_employee` LEFT JOIN `gy_user` ON `gy_employee`.`gy_emp_code`=`gy_user`.`gy_user_code` Where `gy_employee`.`gy_acc_id`=22 and `gy_employee`.`gy_emp_type`>1 ORDER BY `gy_full_name` asc");
                                        while($suprow=$supsql->fetch_array()){ ?>
                                        <option value="<?php echo $suprow['gy_user_id']; ?>"><?php echo $suprow['gy_full_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <label class="fltr_sup" id="lblsup">Supervisor</label>
                                    </div>
                                    <div class="form-floating">
                                    <select class="form-select minwid-120" id="fltr_skl" onchange="rfrshicn(this)">
                                        <option value="">All</option>
                                        <option value="1">Skill 1</option>
                                        <option value="1.1">Skill 1.1</option>
                                        <option value="1.2">Skill 1.2</option>
                                        <option value="2">Skill 2</option>
                                        <option value="3">Skill 3</option>
                                    </select>
                                    <label class="fltr_skl" id="lblskl">Skill</label>
                                    </div>
                                    <div class="form-floating">
                                        <select class="form-select minwid-120" id="fltr_pbr" onchange="rfrshicn(this)">
                                            <option value="">All</option>
                                            <option value="0">Primary</option>
                                            <option value="1">Bench</option>
                                        </select>
                                        <label class="fltr_pbr" id="lblpbr">Primary/Bench</label>
                                    </div>
                                    <div class="form-floating">
                                        <select class="form-select minwid-120" id="fltr_fg" onchange="rfrshicn(this)">
                                            <option value="">All</option>
                                            <?php $fgsql=$dbticket->query("SELECT * From `focus_group` ");
                                            while($fgrow=$fgsql->fetch_array()){ ?>
                                            <option value="<?php echo $fgrow['id']; ?>"><?php echo $fgrow['fg_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <label class="fltr_fg" id="lblfg">Focus Group</label>
                                    </div>
                                    <div class="form-floating">
                                        <select class="form-select minwid-120" id="fltr_shp" onchange="rfrshicn(this)">
                                            <option value="">All</option>
                                            <?php $shpql=$dbticket->query("SELECT * From `shops` ");
                                             while($shprow=$shpql->fetch_array()){ ?>
                                                <option value="<?php echo $shprow['id']; ?>"><?php echo $shprow['shop_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <label class="fltr_shp" id="lblshp">Shop</label>
                                    </div>
                                </div>
                                <?php $dbticket->close(); ?>
                                <div class="table-responsive">
                                    <table class="table table table-bordered" style="font-family: 'Calibri'; font-size: 14px;" id="masterrosterb">
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="comparedt"><i class='fa fa-spinner fa-pulse'></i></div>
    </div>
   <?php include 'scripts.php'; ?>
<script type="text/javascript">
function rfrshicn(txtid){
    var lbltxt = "";
    var lblcls = "";
    if(txtid.id=="fltr_loc"){ lblcls="lblloc"; lbltxt = "Location"; }
    else if(txtid.id=="fltr_sup"){ lblcls="lblsup"; lbltxt = "Supervisor"; }
    else if(txtid.id=="fltr_skl"){ lblcls="lblskl"; lbltxt = "Skill"; }
    else if(txtid.id=="fltr_pbr"){ lblcls="lblpbr"; lbltxt = "Primary/Bench"; }
    else if(txtid.id=="fltr_fg"){ lblcls="lblfg"; lbltxt = "Focus Group"; }
    else if(txtid.id=="fltr_shp"){ lblcls="lblshp"; lbltxt = "Shop"; }
    document.getElementById(lblcls).innerHTML = lbltxt+" <i class='fas fa-refresh faa-spin animated faa-slow'></i>";
}

function loadmasterroster(){
    var loc = document.getElementById("fltr_loc").value;
    var sup = document.getElementById("fltr_sup").value;
    var skl = document.getElementById("fltr_skl").value;
    var pbr = document.getElementById("fltr_pbr").value;
    var ffg = document.getElementById("fltr_fg").value;
    var shp = document.getElementById("fltr_shp").value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200){
        document.getElementById("masterrosterb").innerHTML = this.responseText;
        document.getElementById("lblloc").innerHTML = "Location";
        document.getElementById("lblsup").innerHTML = "Supervisor";
        document.getElementById("lblskl").innerHTML = "Skill";
        document.getElementById("lblpbr").innerHTML = "Primary/Bench";
        document.getElementById("lblfg").innerHTML = "Focus Group";
        document.getElementById("lblshp").innerHTML = "Shop";
        comparetime();
    }};
    xhttp.open("POST", "mrbody.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("loc="+loc+"&sup="+sup+"&skl="+skl+"&pbr="+pbr+"&ffg="+ffg+"&shp="+shp);
}

function createtldiv(name, targer, actual){
    var divrow = document.getElementById("tldynamic");
    const div1 = document.createElement("div");
    div1.setAttribute("class", "col-md-3 float-left");
    const div2 = document.createElement("div");
    div2.setAttribute("class","col-md-12 float-left border text-nowrap");
    const strong1 = document.createElement("strong");
    strong1.innerText = name;
    const div3 = document.createElement("div");
    div3.setAttribute("class", "col-md-8 float-left border text-nowrap");
    div3.innerText = "TL Target (Daily) : ";
    const div4 = document.createElement("div");    
    div4.setAttribute("class","col-md-4 float-left border text-center text-nowrap");
    div4.innerText = targer;
    const div5 = document.createElement("div");
    div5.setAttribute("class", "col-md-8 float-left border text-nowrap");
    div5.innerText = "TL Actual (Daily) : ";
    const div6 = document.createElement("div");    
    div6.setAttribute("class","col-md-4 float-left border text-center text-nowrap");
    div6.innerText = actual;
    const div7 = document.createElement("div");
    div7.setAttribute("class", "col-md-8 float-left border text-nowrap");
    div7.innerText = "Team Performance : ";
    const div8 = document.createElement("div");    
    div8.setAttribute("class","col-md-4 float-left border text-center text-nowrap");
    div8.innerText = Math.round((actual/targer)*100) + " %";
    const div9 = document.createElement("div");
    div9.setAttribute("class", "col-md-12");
    div9.innerText = '\n';

    divrow.appendChild(div1);
    div1.appendChild(div2);
    div2.appendChild(strong1)
    div1.appendChild(div3);
    div1.appendChild(div4);
    div1.appendChild(div5);
    div1.appendChild(div6);
    div1.appendChild(div7);
    div1.appendChild(div8);
    div1.appendChild(div9);
}

function comparetime(){
    var total = 0;
    var schpri = parseInt(document.getElementById("inphidschpri").value);
    var schben = parseInt(document.getElementById("inphidschben").value);
    var prespri = parseInt(document.getElementById("inphidprespri").value);
    var presben = parseInt(document.getElementById("inphidpresben").value);

    var tlname = document.getElementsByName("inphidtlname");
    var tltarg = document.getElementsByName("inphidtltarget");
    var tlrunn = document.getElementsByName("inphidtlrunn");

    var targetpri = parseInt(document.getElementById("inphidtargetpri").value);
    var targetben = parseInt(document.getElementById("inphidtargetben").value);
    var runpri = parseInt(document.getElementById("inphidrunpri").value);
    var runben = parseInt(document.getElementById("inphidrunben").value);

    document.getElementById("rt_primary").innerText = schpri;
    document.getElementById("rt_bench").innerText = schben;
    document.getElementById("rt_totalpb").innerText = (schpri + schben);
    document.getElementById("rt_pprimary").innerText = prespri;
    document.getElementById("rt_pbench").innerText = presben;
    document.getElementById("rt_ptotalpb").innerText = (prespri + presben);
    if(prespri==0 || schpri==0){ total = 0; }else{ total = 100-((prespri/schpri)*100); }
    document.getElementById("rt_trueabse").innerText = Math.ceil(total)+" %";
    if(prespri+presben==0 || schpri==0){ total = 0;}else{ total = 100-(((prespri+presben)/(schpri+schben))*100); }
    document.getElementById("rt_abseafco").innerText = Math.ceil(total)+" %";

    document.getElementById("rt_tarpri").innerText = targetpri;
    document.getElementById("rt_tarben").innerText = targetben;
    document.getElementById("rt_tarpriben").innerText = targetpri+targetben;
    document.getElementById("rt_temapri").innerText = runpri;
    document.getElementById("rt_temaben").innerText = runben;
    document.getElementById("rt_temapriben").innerText = runpri+runben;
    if(runpri==0 || targetpri==0){ total = 0; }else{ total = (runpri/targetpri)*100; }
    document.getElementById("rt_prodrun").innerText = Math.ceil(total)+" %";
    if((targetpri+targetben)==0 || (runpri+runben)==0){ total = 0; }else{ total = ((runpri+runben)/(targetpri+targetben))*100; }
    document.getElementById("rt_prodruncov").innerText = Math.ceil(total)+" %";

    document.getElementById("tldynamic").innerHTML="";
    for(var i=0;i<tlname.length;i++){
        createtldiv(tlname[i].value, parseInt(tltarg[i].value), parseInt(tlrunn[i].value));
    }

    var datetime = document.getElementById("hidmaxrow").value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200){
        document.getElementById("comparedt").innerHTML = this.responseText;
        var newdt = document.getElementById("newhidmaxrow").value;
        if(Date.parse(newdt) > Date.parse(datetime)){ setTimeout(loadmasterroster, 2000); }
        else{ setTimeout(comparetime, 5000); }
    }};
    xhttp.open("GET", "checkdatetime.php", true);
    xhttp.send();
}

loadmasterroster();
</script>
</body>
</html>