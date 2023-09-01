<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';
    $title = "VidaXL Master Roster Manager";
    if($myaccount == 22){

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
                        <div class="col-lg-12">
                            <h2 class="title-1 m-b-25"><?php echo $title; ?> <i class="fas fa-address-book"></i></h2>
                        </div>
                    </div>
                            <div class="card">
                                <div class="card-header" id="toolbar"><i class='fa fa-spinner fa-pulse'></i></div>
                                <div id="load_vxlemp"><i class='fa fa-spinner fa-pulse'></i></div>
                                <div id="remove_mr"></div>
                            </div>
                    <?php include 'footer.php'; ?>
                </div>
            </div>
        </div>
    </div>
   <?php include 'scripts.php'; ?>
<script type="text/javascript">

function daterange(){
    var from = _getID("datefrom").value;
    var to = _getID("dateto").value;

    if (from) {
        _getID("dateto").min = from;
    }

    if (to) {
        _getID("datefrom").max = to;
    }
}

function updtargettbl(elem){
    var elemid = elem.id.substring(elem.id.indexOf("_")+1);
    var elemval = "";
    var tblid = "";
    if(elem.id=="skill_"+elemid){
        tblid = "tblskill_"+month2_;
        elemval = "&skill="+elem.value;
    }else if(elem.id=="operator_"+elemid){
        tblid = "tblope_"+elemid;
        elemval = "&operator="+elem.value;
        if(elem.value==">"||elem.value==">="){ document.getElementById("month2_"+elemid).disabled = false;
        }else{ document.getElementById("month2_"+elemid).disabled = true; }
    }else if(elem.id=="month1_"+elemid){
        tblid = "tblmth1_"+elemid;
        elemval = "&month1="+elem.value;
    }else if(elem.id=="month2_"+elemid){
        tblid = "tblmth2_"+elemid;
        elemval = "&month2="+elem.value;
    }else if(elem.id=="target_"+elemid){
        tblid = "tbltarget_"+elemid;
        elemval = "&target="+elem.value;
    }
    document.getElementById(elem.id).disabled = true;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200){
        document.getElementById(tblid).innerHTML = this.responseText;
    document.getElementById(elem.id).disabled = false;
    }};
    xhttp.open("POST", "mr_updtbltar.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("targetid="+elemid+elemval);   
}

function updatesel(elem){
    var empcode = elem.id.substring(elem.id.indexOf("_")+1);
    var locval = elem.value;
    if("loc_"+empcode==elem.id){
        var divid = "selloc_"+empcode;
        var urlval = "&loc="+locval;
    }else if("skillsel_"+empcode==elem.id){
        var divid = "selskill_"+empcode;
        var urlval = "&skill="+locval;
    }else if("pbrepsel_"+empcode==elem.id){
        var divid = "selpbrep_"+empcode;
        var urlval = "&pbrep="+locval;
    }else if("focusgsel_"+empcode==elem.id){
        var divid = "selfocusg_"+empcode;
        var urlval = "&focusg="+locval;
    }else if("zendeskid_"+empcode==elem.id){
        var divid = "zend_"+empcode;
        var urlval = "&zendid="+locval;
    }
    document.getElementById(elem.id).disabled = true;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200){
        document.getElementById(divid).innerHTML = this.responseText;
        document.getElementById(elem.id).disabled = false;
    }};
    xhttp.open("POST", "mr_updateval.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("empcode="+empcode+urlval);
}

function switchemstat(elem){
    var empcode = elem.id.substring(elem.id.indexOf("_")+1);
    var divid = "empstatus_"+empcode;
    document.getElementById(elem.id).disabled = true;
    document.getElementById(elem.id).innerHTML = "<i class='fa fa-spinner fa-pulse'></i>";
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200){
        document.getElementById(divid).innerHTML = this.responseText;
        if(elem.title == "Active"){
            document.getElementById(elem.id).classList.remove('btn-outline-success');
            document.getElementById(elem.id).classList.add('btn-outline-danger');
            document.getElementById(elem.id).setAttribute("title", "Inactive");
            document.getElementById(elem.id).innerHTML = "<i class='fas fa-toggle-off'></i>";
        }else{
            document.getElementById(elem.id).classList.remove('btn-outline-danger');
            document.getElementById(elem.id).classList.add('btn-outline-success');
            document.getElementById(elem.id).setAttribute("title", "Active");
            document.getElementById(elem.id).innerHTML = "<i class='fas fa-toggle-on'></i>";       
        }
        document.getElementById(elem.id).disabled = false;
    }};
    xhttp.open("POST", "mr_updempstatus.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("empcode="+empcode);
}

function upditems(elem, btn){
    var elemname = elem.name;
    var shopbtn = document.getElementsByName(elemname);
    for(var i=0;i<shopbtn.length;i++){
    document.getElementsByName(elemname)[i].disabled = true;
    }
    document.getElementById(elem.id).innerHTML = "<i class='fa fa-spinner fa-pulse'></i>";
    var itemid = elem.id.substring(elem.id.indexOf("_")+1);
    var varval = "";
    if("shopdel_"+itemid==elem.id){
        varval = "shopid="+itemid;
    }else if("fgdel_"+itemid==elem.id){
        varval = "fgid="+itemid;
    }else if("tardel_"+itemid==elem.id){
        varval = "targetid="+itemid;
    }
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200){
        if("shopdel_"+itemid==elem.id){
        document.getElementById("updateshop").innerHTML = this.responseText;
        manageshop();
        load_vxllist();
        }else if("fgdel_"+itemid==elem.id){
        document.getElementById("updatefg").innerHTML = this.responseText;
        managefg();
        load_vxltool();
        load_vxllist();
        }else if("tardel_"+itemid==elem.id){
        document.getElementById("updatetarget").innerHTML = this.responseText;
        managetarget();
        }
    }};
    xhttp.open("POST", "mr_upditems.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(varval+"&btn="+btn);
}

function addtolist(elem){
    document.getElementById(elem.id).disabled = true;
    document.getElementById(elem.id).innerHTML = "<i class='fa fa-spinner fa-pulse'></i>";
    var saveval = "";
    if(elem.id=="subshpnm"){
        saveval = "shopname="+document.getElementById("shopname").value;
    }else if(elem.id=="subfgnm"){
        saveval = "fgname="+document.getElementById("focusgroupinp").value;
    }else if(elem.id=="subtarget"){
        saveval = "skill="+document.getElementById("targetskill").value+"&ope="+document.getElementById("targerope").value+"&frommonth="+document.getElementById("frommonth").value+"&tomonth="+document.getElementById("tomonth").value+"&target="+document.getElementById("targetval").value;
    }
    if(saveval!=""){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200){
        if(elem.id=="subshpnm"){
        document.getElementById("updateshop").innerHTML = this.responseText;
        manageshop();
        load_vxllist();
        }else if(elem.id=="subfgnm"){
        document.getElementById("updatefg").innerHTML = this.responseText;
        managefg();
        load_vxltool();
        load_vxllist();
        }else if(elem.id=="subtarget"){
        document.getElementById("updatetarget").innerHTML = this.responseText;
        managetarget();
        }
    }};
    xhttp.open("POST", "mr_additemlist.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(saveval);
    }
}

function dlemprep(){
    var empcode = document.getElementById("searchempsel").value;
    var fdate = document.getElementById("datefrom").value;
    var tdate = document.getElementById("dateto").value;    
    if(empcode != "" && fdate != "" && tdate != ""){
        var urlx = "empcode="+empcode+"&fdate="+fdate+"&tdate="+tdate;
        window.open("mr_gentktrep?"+urlx, "_blank");
    }
}

function searchemprep(elem){
    var empcode = document.getElementById("searchempsel").value;
    var fdate = document.getElementById("datefrom").value;
    var tdate = document.getElementById("dateto").value;
    if(empcode != "" && fdate != "" && tdate != ""){
    document.getElementById(elem.id).innerHTML = "<i class='fa fa-spinner fa-pulse'></i>";
    document.getElementById(elem.id).disabled = true;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
                document.getElementById("tblreport").innerHTML = this.responseText;
                document.getElementById(elem.id).innerHTML = "Search <i class='fas fa-search'></i>";
                document.getElementById(elem.id).disabled = false;
            }
        };
        xhttp.open("POST", "mr_searchreport.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("empcode="+empcode+"&fdate="+fdate+"&tdate="+tdate);
    }
}
function searchempreplogs(elem){
    var empcode = document.getElementById("searchempsellogs").value;
    var fdate = document.getElementById("datefrom").value;
    var tdate = document.getElementById("dateto").value;
    if(empcode != "" && fdate != "" && tdate != ""){
    document.getElementById(elem.id).innerHTML = "<i class='fa fa-spinner fa-pulse'></i>";
    document.getElementById(elem.id).disabled = true;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
                document.getElementById("tbllogs").innerHTML = this.responseText;
                document.getElementById(elem.id).innerHTML = "Search <i class='fas fa-search'></i>";
                document.getElementById(elem.id).disabled = false;
            }
        };
        xhttp.open("POST", "mr_searchlogs.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("empcode="+empcode+"&fdate="+fdate+"&tdate="+tdate);
    }
}

function managereports(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200){
        Swal.fire({
            title: 'Reports',
            html: '<div id="report_mr"></div>',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Close',
            width: 'auto'
        })
        document.getElementById("report_mr").innerHTML = this.responseText;
    }};
    xhttp.open("GET", "mr_report.php", true);
    xhttp.send();
}
function checkticketlogs(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200){
        document.getElementById("report_mr").innerHTML = this.responseText;
    }};
    xhttp.open("GET", "mr_logs.php", true);
    xhttp.send();
}
function checkreports(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200){
        document.getElementById("report_mr").innerHTML = this.responseText;
    }};
    xhttp.open("GET", "mr_report.php", true);
    xhttp.send();
}

function managetarget(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200){
        Swal.fire({
            title: 'Manage Targets',
            html: '<div id="target_mr"></div>',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Close',
            width: '700px'
        })
        document.getElementById("target_mr").innerHTML = this.responseText;
    }};
    xhttp.open("GET", "mr_target.php", true);
    xhttp.send();
}

function managefg(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200){
        Swal.fire({
            title: 'Manage Focus Group',
            html: '<div id="focusg_mr"></div>',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Close'
        })
        document.getElementById("focusg_mr").innerHTML = this.responseText;
    }};
    xhttp.open("GET", "mr_focusgroup.php", true);
    xhttp.send();
}

function manageshop(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200){
        Swal.fire({
            title: 'Manage Shops',
            html: '<div id="shops_mr"></div>',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Close'
        })
        document.getElementById("shops_mr").innerHTML = this.responseText;
    }};
    xhttp.open("GET", "mr_shops.php", true);
    xhttp.send();
}

function switchshop(elem, empcode, shopid){
    var btname = document.getElementsByName("switchshopbtn");
    document.getElementById(elem.id).disabled = true;
    var btnid = elem.id.substring(elem.id.indexOf("_")+1);
    var divid = "shopemp_"+btnid;
    document.getElementById(elem.id).innerHTML = "<i class='fa fa-spinner fa-pulse'></i>";
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200){
        document.getElementById(divid).innerHTML = this.responseText;
        if(elem.title == "Selected"){
            document.getElementById(elem.id).setAttribute("title", "Not Selected");
            document.getElementById(elem.id).innerHTML = "<i class='fa fa-square'></i>";
        }else{
            document.getElementById(elem.id).setAttribute("title", "Selected");
            document.getElementById(elem.id).innerHTML = "<i class='fa fa-check-square'></i>";
        }
        document.getElementById(elem.id).disabled = false;
    }};
    xhttp.open("POST", "mr_updempshop.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("empcode="+empcode+"&shopid="+shopid);
}

function updatelist(){
    var empcode = document.getElementById("emp_code").value;
    var zendeskid = document.getElementById("zendeskid").value;
    var site = document.getElementById("site").value;
    var skill = document.getElementById("skill").value;
    var pbreps = document.getElementById("pbreps").value;
    var focusg = document.getElementById("focusg").value;
    if(empcode != ""){
    document.getElementById("updatemslist").disabled = true;
    document.getElementById("updatemslist").innerHTML = "<i class='fa fa-spinner fa-pulse'></i>";
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200){
        document.getElementById("remove_mr").innerHTML = this.responseText;
        load_vxllist();
        setTimeout(load_vxltool, 1000);
    }};
    xhttp.open("POST", "mr_newrecord.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("empcode="+empcode+"&zendeskid="+zendeskid+"&site="+site+"&skill="+skill+"&pbreps="+pbreps+"&focusg="+focusg);
    }
}

function delconfirm(elem){
var empcode = elem.id.substring(elem.id.indexOf("_")+1);
Swal.fire({
  title: 'Remove this item?',
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes, remove it!'
}).then((result) => {
  if (result.isConfirmed) {
    document.getElementById(elem.id).innerHTML = "<i class='fa fa-spinner fa-pulse'></i>";
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200){
        document.getElementById("remove_mr").innerHTML = this.responseText;
        load_vxltool();
        document.getElementById("row_"+empcode).remove();
        Swal.fire('Deleted!','The item has been remove.','success')
    }};
    xhttp.open("POST", "mr_remove.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("empcode="+empcode);
  }
})
}

function checkattr(elem){
    var maxlength = 20;
    if(elem.value.length > maxlength){
        document.getElementById(elem.id).value = elem.value.substr(0,maxlength); }
    else if(elem.value < 0){ document.getElementById(elem.id).value = 0; }
}

function setintlimit(elem){
    var tdid = elem.id.substring(elem.id.indexOf("_")+1);
    if(elem.value < 0 && elem.value != ""){ document.getElementById(elem.id).value = ""; }
    else if(elem.value > (elem.max-1)){ document.getElementById(elem.id).value = elem.max; }
    if(elem.id == "frommonth"){
        if(elem.value==""||(elem.value>document.getElementById("tomonth").value&&document.getElementById("tomonth").value!="")){
            document.getElementById("tomonth").disabled = true;
            document.getElementById("tomonth").value = "";
            document.getElementById("targetval").disabled = true;
            document.getElementById("targetval").value = "";
            document.getElementById("subtarget").disabled = true;
        }else if(elem.value != ""){
            if(document.getElementById("targerope").value==">"||document.getElementById("targerope").value==">="){ document.getElementById("tomonth").disabled = false; }
            document.getElementById("targetval").disabled = false;
        }
    }else if(elem.id == "tomonth"){
        if(parseInt(elem.value)>=parseInt(document.getElementById("frommonth").value) || elem.value == ""){
            document.getElementById("targetval").disabled = false;
        }else{
            document.getElementById("subtarget").disabled = true;
            document.getElementById("targetval").disabled = true;
            document.getElementById("targetval").value = "";
        }
    }else if(elem.id == "targetval"){
        if(elem.value != "" && elem.value >= 0){
            document.getElementById("subtarget").disabled = false;
        }else{
            document.getElementById("subtarget").disabled = true;
        }
    }else if(elem.id == "targerope"){
        if((elem.value==">"||elem.value==">=")&&document.getElementById("frommonth").value!=""){
            document.getElementById("tomonth").disabled = false;
        }else{
            document.getElementById("tomonth").disabled = true;            
            document.getElementById("tomonth").value = "";
        }
    }else if(elem.id=="target_"+tdid){
        var elemval = elem.value;
        if(elem.value==""){ elemval = 0; }
        document.getElementById("demail_"+tdid).innerText = elemval * 8;
        document.getElementById("dtarger_"+tdid).innerText = Math.ceil(elemval * 6.375);
        document.getElementById("htarger_"+tdid).innerText = Math.ceil(Math.ceil(elemval * 6.375)/8);
    }
}

function load_vxllist(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200){
        document.getElementById("load_vxlemp").innerHTML = this.responseText;
    }};
    xhttp.open("GET", "masterroster.php", true);
    xhttp.send();
}

function load_vxltool(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200){
        document.getElementById("toolbar").innerHTML = this.responseText;
    }};
    xhttp.open("GET", "mr_toolbar.php", true);
    xhttp.send();
}

load_vxltool();
load_vxllist();
</script>
</body>
</html>
<?php } $link->close(); ?>