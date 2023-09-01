<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "Upload DTR";
$link->close();
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>
<body>
    <div class="page-wrapper">
        <?php include 'header-m.php'; ?>
        <?php include 'sidebar.php'; ?>
        <div class="page-container">
            <div class="main-content" style="padding: 20px;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="title-1 m-b-25"><?php echo $title; ?> <i class="fa-solid fa-business-time"></i></h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header"><strong class="card-title mb-3"><center>Upload <span style="color: green;">.csv</span> Files</center></strong></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">

                                    <label for="liloFile" class="form-label">Upload Log In and Out</label>
                                    <input class="form-control" type="file" id="liloFile" name="liloFile" accept=".csv" required>
                                    <small>Column Requirements: <mark>ID</mark>, <mark>Name</mark>, <mark>Date</mark>, <mark>Time In</mark>, <mark>Time Out</mark>, <mark>Status</mark></small>

                                    </div><div class="col-md-6">

                                    <label for="biboFile" class="form-label">Upload Break In and Out</label>
                                    <input class="form-control" type="file" id="biboFile" accept=".csv" required>
                                    <small>Column Requirements: <mark>Date</mark>, <mark>Name</mark>, <mark>Time Start</mark>, <mark>Time End</mark>, <mark>Status</mark></small>

                                    </div>
                                </div>
                                <div class="row pt-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select" id="slt_account" required>
                                                <option value="20">US Visa</option>
                                            </select>
                                            <label class="slt_account">Select Account</label>
                                        </div>

                                        </div><div class="col-md-6">

                                        <button class="btn btn-primary btn-lg btn-block" onclick="readcsv()"><span id="loadfilespan"><i class="fa-solid fa-file-arrow-down"></i></span> Load the CSV Files </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-muted"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="dynfile"></div>
                    <?php include 'footer.php'; ?>
                </div>
            </div>
        </div>
    </div>
<?php include 'scripts.php'; ?>
<script type="text/javascript">
   function sleep(ms) {
      return new Promise(resolve => setTimeout(resolve, ms));
   }
    async function uplallval(rnum){
        document.getElementById("spnupall").innerHTML='<i class="fa-solid fa-spinner faa-spin animated"></i>';
        for(var i=0;i<rnum;i++){
            var elem = document.getElementById("btnupd_"+i);
            if(typeof(elem) != 'undefined' && elem!=null){
                elem.onclick();
                await sleep(500);
            }
        }
        document.getElementById("spnupall").innerHTML='<i class="fa-solid fa-file-arrow-up"></i>';
    }

    function updttrk(elem){
        var eleid = elem.id.split('_')[1];
        var id = document.getElementById("inpid_"+eleid).value;
        var date = document.getElementById("hiddate_"+eleid).value;
        var login = document.getElementById("selli_"+eleid).value;
        var logout = document.getElementById("sello_"+eleid).value;
        var acc = document.getElementById("slt_account").value;
        var bin = document.getElementById("brkin_"+eleid).value;
        var bout = document.getElementById("brkout_"+eleid).value;
        var lognm = document.getElementById("lognm_"+eleid).innerHTML;
        if(id!=""&&date!=""&&login!=""&&logout!=""&&acc!=""){
            var formData = new FormData();
            formData.append("id", id);
            formData.append("date", date);
            formData.append("login", login);
            formData.append("logout", logout);
            formData.append("acc", acc);
            formData.append("bin", bin);
            formData.append("bout", bout);

            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "upload_dtr/updtracker.php", true);
             xhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200) {
                    var updst = this.responseText;
                    if(updst==1){ document.getElementById("tridx_"+eleid).remove();
                        Swal.fire({
                          position: 'top',
                          icon: 'success',
                          title: lognm+"'s DTR Uploaded Successfully",
                          showConfirmButton: false,
                          allowOutsideClick: false,
                          timer: 1500
                        })
                    }else if(updst==2){
                        Swal.fire({
                          position: 'center',
                          icon: 'error',
                          title: lognm+"'s DTR Logs Data Invalid",
                          showConfirmButton: false,
                          timer: 1500
                        })
                    }else if(updst==3){
                        Swal.fire({
                          position: 'center',
                          icon: 'warning',
                          title: lognm+" Records Not Found",
                          showConfirmButton: false,
                          timer: 1500
                        })
                    }
                }
             };
            xhttp.send(formData);
        }
    }

    function chkvld(elem){
        var eleid = elem.id.split('_')[1];
        var slcopt = document.getElementById("dtlstopt");
        var tgname = slcopt.getElementsByTagName("option");
        var fnd = 0;
        for(var i=0;i<tgname.length;i++){ if(elem.value==tgname[i].value){ fnd++; break; }}
        if(fnd==1){
            document.getElementById("btnstt_"+eleid).innerHTML = '<button class="btn btn-success btn-block" title="Ready for Upload" id="btnupd_'+eleid+'" onclick="updttrk(this)"> Upload <i class="fa-solid fa-circle-arrow-up"></i></button>';
        }else{
            document.getElementById("btnstt_"+eleid).innerHTML = '<button class="btn btn-secondary btn-block" title="Row Value not Acceptable"> Invalid <i class="fa-solid fa-triangle-exclamation"></i></button>';
        }
    }

    function readcsv(){
        document.getElementById("loadfilespan").innerHTML = '<i class="fa-solid fa-spinner faa-spin animated"></i>';
        var files = document.getElementById("liloFile").files;
        var bfile = document.getElementById("biboFile").files;
        var acc = document.getElementById("slt_account").value;
        if(files.length>0){
            var formData = new FormData();
            const colord = [0,1,2,3,4,5];
            const brkcol = [0,1,2,3,4];
            formData.append("file", files[0]);
            formData.append("bile", bfile[0]);
            formData.append("acc", acc);
            formData.append("colord", colord);
            formData.append("brkcol", brkcol);

            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "upload_dtr/compilecsvs.php", true);
             xhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("dynfile").innerHTML = this.responseText;
                    document.getElementById("loadfilespan").innerHTML = '<i class="fa-solid fa-file-arrow-down"></i>';
                }
             };
        xhttp.send(formData);
        }
    }

    function updtbl(){
        document.getElementById("4loading").innerHTML='<i class="fa-solid fa-spinner faa-spin animated"></i>';
        var b4u_code = document.getElementById("b4u_code").value;
        var b4u_name = document.getElementById("b4u_name").value;
        var b4u_date = document.getElementById("b4u_date").value;
        var b4u_logi = document.getElementById("b4u_logi").value;
        var b4u_logo = document.getElementById("b4u_logo").value;
        var b4u_bout = document.getElementById("b4u_bout").value;
        var b4u_bin = document.getElementById("b4u_bin").value;
        const colord = [b4u_code,b4u_name,b4u_date,b4u_logi,b4u_logo,b4u_logo+1];

        var files = document.getElementById("liloFile").files;
        var bfile = document.getElementById("biboFile").files;
        var acc = document.getElementById("slt_account").value;
        const brkcol = [0,1,b4u_bout,b4u_bin,4];
        if(files.length>0){
            var formData = new FormData();
            formData.append("file", files[0]);
            formData.append("bile", bfile[0]);
            formData.append("acc", acc);
            formData.append("colord", colord);
            formData.append("brkcol", brkcol);

            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "upload_dtr/compilecsvs.php", true);
             xhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("dynfile").innerHTML = this.responseText;
                }
             };
        xhttp.send(formData);
        }
    }
</script>
</body>
</html>
