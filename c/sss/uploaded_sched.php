<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "Upload Schedules";

    $notify = @$_GET['note'];

    if ($notify == "upload_success") {
        $note = "Schedule Upload Successful";
        $notec = "success";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "file_not_allowed") {
        $note = "Only CSV file is allowed to upload";
        $notec = "warning";
        $notes = "";
        $noteid = "activate-alert";
    }else if ($notify == "missingdata") {
        $note = "Data Uploaded was Incomplete or Invalid!";
        $notec = "warning";
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
                            <h2 class="title-1 m-b-25"><?php echo $title; ?> <i class="fa-solid fa-calendar-plus"></i></h2>
                            <div style="<?php echo $notes; ?>" id="<?php echo $noteid; ?>" class="sufee-alert alert with-close alert-<?php echo $notec; ?> alert-dismissible fade show">
                                <span class="badge badge-pill badge-<?php echo $notec; ?>">Alert</span>
                                <?php echo $note; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>

					<ul class="nav nav-tabs ">
                        <li class="nav-item">
                            <a class="nav-link" href="upload_sched">Upload Here</a>
                        </li>
                        <li class="nav-item">
							<a class="nav-link active" aria-current="page" href="uploaded_sched"><strong>Edit Here</strong></a>
                        </li>
                    </ul>
					
					<div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="input-group">
									<div class="form-floating">
										<select class="form-select" id="srchtyp" onchange="srchnms()">
										<option value="search">None</option>
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
										<label for="srchtyp">Filter By</label>
									</div>
									<datalist id="lyvdsply"></datalist>
									<div class="form-floating" id="chgbyfltr">
                                        <input type="text" id="srchidnm" class="form-control " list="lyvdsply" oninput="srchnms()" onkeyup="loadsched(this)">
                                        <label for="srchidnm">Employee Name/SiBS ID</label>
                                    </div>
									<button class="btn btn-lg" id="loadonpress" onclick="updtshwtbl()" title="Refresh"><i class="fas fa-sync faa-wrench animated faa-slow"></i></button>
								</div>
								<div class="table-responsive">
									<table class="table table-hover" id="schdtbl" style="font-family: 'Calibri'; font-size: 14px;">
										<thead class="table-dark">
											<tr style="padding:4px;" class="text-center text-nowrap">
												<th scope="col" >Name</th>
												<th scope="col" >Date</th>
												<th scope="col" >Type</th>
												<th scope="col" >Login</th>
												<th scope="col" >Logout</th>
												<th scope="col" >Update</th>
												<th scope="col" >Delete</th>
											</tr>
										</thead>
										<tbody id="tbldynscd" ></tbody>
									</table>
								</div>
								<input type="hidden" value="" id="hidtblempid">
								<!--<div id="btnpg"></div>-->
							</div>
							<div class="card" id="dvewscd"></div>
							</div>
						</div>
					</div>
				<?php $link->close(); include 'footer.php'; ?>	
				</div>
			</div>
		</div>
	</div>
	<?php include 'scripts.php'; ?>
	<script type="text/javascript">
		function addprocresp(msg){
			const amsg = msg.split(",");
			var title = "";
			var status = "";
			if(amsg[0]=="success"){
				if(amsg[1]>0){ title=amsg[1]+" new schedule has been added"; status="success"; }
				if(amsg[2]>0){ if(title!=""){ title+=", "; } title+=amsg[2]+" schedule has been updated"; status="success"; }
				if(amsg[3]>0){ if(title!=""){ status="error"; title+=", "; status="info"; } title+=amsg[3]+" failed to process"; }
			}else{
				status="error"; title="Invalid Action";
				if(msg.includes("dfrom")){ document.getElementById("from").classList.add("is-invalid"); }
				if(msg.includes("dto")){ document.getElementById("to").classList.add("is-invalid"); }
				if(msg.includes("dscdtyp")){ document.getElementById("seladdscdtyp").classList.add("is-invalid"); }
				if(msg.includes("dscdin")){ document.getElementById("seladdscdin").classList.add("is-invalid"); }
				if(msg.includes("dscdout")){ document.getElementById("seladdscdout").classList.add("is-invalid"); }				
			}
			updtshwtbl();
			toastalert(status, title);
			document.getElementById("btnaplyscd").disabled=false;
		}

		function cnfmscd(cdnm){
			document.getElementById("btnaplyscd").disabled=true;
			document.getElementById("btnaplyscd").innerHTML='<i class="fa-solid fa-calendar-plus faa-spin faa-fast animated"></i>';
			var dfrom = document.getElementById("from").value;
			var dto = document.getElementById("to").value;
			var dscdtyp = document.getElementById("seladdscdtyp").value;
			var dscdin = document.getElementById("seladdscdin").value;
			var dscdout = document.getElementById("seladdscdout").value;
			if(dfrom!=""&&dto!=""&&cdnm!=""&&((dscdtyp!=""&&dscdin!=""&&dscdout!="")||dscdtyp==0)) {
				sendpost("dvaddnwscd", "upload_dtr/add_sched.php", "cdnm="+cdnm+"&dfrom="+dfrom+"&dto="+dto+"&dscdtyp="+dscdtyp+"&dscdin="+dscdin+"&dscdout="+dscdout);
			}else{
				document.getElementById("btnaplyscd").innerHTML='<strong>APPLY</strong> <i class="fa-solid fa-calendar-plus faa-tada faa-fast"></i>';
				document.getElementById("btnaplyscd").disabled=false;
			}
		}

		function chckifempty(elem){
			if(elem.value==""){
				elem.classList.add("is-invalid");
			}else{
				elem.classList.remove("is-invalid");
			}
		}

        function daterange(){
            var from = _getID("from").value;
            var to = _getID("to").value;

            if (from) {
                _getID("to").min = from;
            }

            if (to) {
                _getID("from").max = to;
            }
        }

		function remscdnw(delid){
			var scdid = document.getElementById("hidscdid_"+delid).value;
			Swal.fire({
				title: 'Remove '+document.getElementById("dsplysched_"+delid).innerText,
				icon: 'question',
				showConfirmButton: true,
				showCancelButton: true,
				confirmButtonText: 'Confirm',
				cancelButtonText: 'Cancel',
                confirmButtonColor: '#DE2A30',
                cancelButtonColor: '#939191'
			}).then((result) => {
				if(result.isConfirmed){
					document.getElementById("loadonpress").innerHTML = "<i class='fas fa-sync faa-spin animated faa-fast'></i>";
					var xhttp = new XMLHttpRequest();
					xhttp.onreadystatechange = function(){
						if (this.readyState == 4 && this.status == 200 && this.responseText != ""){
							prcdltdsply(this.responseText);
							document.getElementById("tblscdid_"+delid).remove();
							document.getElementById("loadonpress").innerHTML = "<i class=\"fas fa-sync faa-wrench animated faa-slow\"></i>";
						}
					};
					xhttp.open("POST", "upload_dtr/delete_sched.php", true);
					xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					xhttp.send("scdid="+scdid);
				}
			})
		}

		function processdsply(status, rwid, nswr, nsin, nsout){
			if(status=="success"){ title="Schedule has been updated!";
				document.getElementById("tblscdid_"+rwid).classList.remove("bg-warning");
				document.getElementById("updschd_"+rwid).innerHTML='<i class="fa-solid fa-shuffle fa-flip-vertical faa-horizontal faa-reverse faa-slow"></i>';
				if(document.getElementById("hidselscdtyp_"+rwid).value==0 || document.getElementById("hidselscdtyp_"+rwid).value==2 ){
					document.getElementById("tblscdid_"+rwid).classList.add("bg-light");
					document.getElementById("tblscdid_"+rwid).classList.add("table-striped");
				}else{
					document.getElementById("tblscdid_"+rwid).classList.add("table-bordered");
				}
				if(nswr!=""){
					document.getElementById("selscdtyp_"+rwid).classList.remove("bg-warning");
					document.getElementById("hidselscdtyp_"+rwid).value=nswr;
				}
				if(nsin!=""){
					document.getElementById("selscdin_"+rwid).classList.remove("bg-warning");
					document.getElementById("hidselscdin_"+rwid).value=nsin;
				}
				if(nsout!=""){
					document.getElementById("selscdout_"+rwid).classList.remove("bg-warning");
					document.getElementById("hidselscdout_"+rwid).value=nsout;
				}
			}
            else if(status=="error"){ title="Cannot process your request, try again"; }
            else if(status=="nochange"){ status="info"; title="No changes in the schedule";  }
			toastalert(status, title);
		}
		function prcdltdsply(status){
			if(status=="success"){ title="Schedule has been removed!"; }
            else if(status=="error"){ title="Cannot process your request, try again"; }
            else if(status=="nochange"){ status="info"; title="No changes in the schedule";  }
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

		function updtscdnw(rwid){
			document.getElementById("loadonpress").innerHTML = "<i class='fas fa-sync faa-spin animated faa-fast'></i>";
			var hidid = document.getElementById("hidscdid_"+rwid).value;
			var hidwr = document.getElementById("hidselscdtyp_"+rwid).value;
			var hidin = document.getElementById("hidselscdin_"+rwid).value;
			var hidout = document.getElementById("hidselscdout_"+rwid).value;

			var swr = document.getElementById("selscdtyp_"+rwid).value;
			var sin = document.getElementById("selscdin_"+rwid).value;			
			var sout = document.getElementById("selscdout_"+rwid).value;
			
			var nswr = "";
			var nsin = "";
			var nsout = "";
			if(swr!=hidwr){ nswr = swr; }
			if(sin!=hidin){ nsin = sin; }
			if(sout!=hidout){ nsout = sout; }
			if(nswr!="" || nsin!="" || nsout!=""){
				//alert(document.getElementById("selscdout_"+rwid).value+" = "+hidout);
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function(){
					if (this.readyState == 4 && this.status == 200 && this.responseText != ""){
						processdsply(this.responseText, rwid, nswr, nsin, nsout);
						document.getElementById("loadonpress").innerHTML = "<i class=\"fas fa-sync faa-wrench animated faa-slow\"></i>";
					}
				};
				xhttp.open("POST", "upload_dtr/update_sched.php", true);
				xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhttp.send("hidid="+hidid+"&nswr="+nswr+"&nsin="+nsin+"&nsout="+nsout+"&swr="+swr);
			}
		}

		function changecolor(elem, rd){
			document.getElementById("tblscdid_"+rd).classList.remove("table-striped");
			document.getElementById("tblscdid_"+rd).classList.remove("table-bordered");
			document.getElementById("tblscdid_"+rd).classList.remove("bg-light");
			document.getElementById("tblscdid_"+rd).classList.add("bg-warning");
			elem.classList.add("bg-warning");
			document.getElementById("updschd_"+rd).innerHTML='<i class="fa-solid fa-shuffle fa-flip-vertical faa-horizontal faa-reverse animated"></i>';
		}

		function loadsched(elem){
			var hidid = document.getElementById("hidtblempid").value;
			var slcopt = document.getElementById("lyvdsply");
			var tgname = slcopt.getElementsByTagName("option");
			var fnd = 0;
			var idnm = elem.value;
			for(var i=0;i<tgname.length;i++){ if(tgname[i].value.includes(elem.value) || tgname[i].innerHTML.toLowerCase().includes(elem.value.toLowerCase()) || tgname.length==1){ fnd++; idnm=tgname[i].value; break; }}
			if(fnd==1 && idnm!=hidid){
				document.getElementById("loadonpress").innerHTML = "<i class='fas fa-sync faa-spin animated faa-fast'></i>";
				sendpost("tbldynscd", "upload_dtr/dsply_sched.php", "idnm="+idnm);
				document.getElementById("hidtblempid").value=idnm;
				sendpost("dvewscd", "upload_dtr/dsplyadd_sched.php", "idnm="+idnm);
			}
		}

		function srchnms(){
			var typ = document.getElementById("srchtyp").value;
			var idnm = document.getElementById("srchidnm").value;
			if(idnm!="" || typ>0){
				document.getElementById("loadonpress").innerHTML = "<i class='fas fa-sync faa-spin animated faa-fast'></i>";
				sendpost("lyvdsply", "upload_dtr/emp_datalist.php", "typ="+typ+"&idnm="+idnm);
			}
		}
	
		function updtshwtbl(){
			var slcopt = document.getElementById("lyvdsply");
			var tgname = slcopt.getElementsByTagName("option");
			var fnd = 0;
			var idnm = document.getElementById("srchidnm").value;
			for(var i=0;i<tgname.length;i++){ if(tgname[i].value.includes(idnm) || tgname[i].innerHTML.toLowerCase().includes(idnm.toLowerCase()) || tgname.length==1){ fnd++; idnm=tgname[i].value; break; }}
			if(fnd==1){
				document.getElementById("loadonpress").innerHTML = "<i class='fas fa-sync faa-spin animated faa-fast'></i>";
				sendpost("tbldynscd", "upload_dtr/dsply_sched.php", "idnm="+idnm);
				document.getElementById("hidtblempid").value=idnm;
				sendpost("dvewscd", "upload_dtr/dsplyadd_sched.php", "idnm="+idnm);
			}
		}

        function sendpost(sid, loc, post){
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){
					if(sid=="dvaddnwscd"){ addprocresp(this.responseText); }
					else{
						document.getElementById(sid).innerHTML = this.responseText;
						document.getElementById("loadonpress").innerHTML = "<i class=\"fas fa-sync faa-wrench animated faa-slow\"></i>";
					}
				}
            };
            xhttp.open("POST", loc, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(post);
        }
	</script>
</body>
</html>