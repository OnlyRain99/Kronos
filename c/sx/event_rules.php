<?php
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "Event Calendar";  
    $notes = "display: none;";

if ($user_type == 6 && $user_dept == 2) {
$trigtoast=0;
if(isset($_POST['submitpol'])){
    $evtid = $_POST['eventid'];
    $evtname = $_POST['eventname'];
    $abbre = $_POST['abbre'];
    $regtin = $_POST['regulartimein'];
    $regtout = $_POST['regulartimeout'];
    $polstatus = $_POST['polstatus'];
    $ndiftin = $_POST['ndifftimein'];
    $ndiftout = $_POST['ndifftimeout'];

    $link->query("UPDATE `gy_holiday_types` SET `gy_hol_type_name`='$evtname',`gy_hol_abbrv`='$abbre',`gy_day_start`='$regtin',`gy_day_end`='$regtout',`gy_night_start`='$ndiftin',`gy_night_end`='$ndiftout',`gy_hol_status`='$polstatus' Where `gy_hol_type_id`='$evtid'");
    $trigtoast=1;
}

$row=0; $holtarr = array(array());
$htsql=$link->query("SELECT `gy_hol_type_id`,`gy_hol_type_name`,`gy_hol_status` FROM `gy_holiday_types` ");
while($htrow=$htsql->fetch_array()){
    $holtarr[$row][0] = $htrow['gy_hol_type_id'];
    $holtarr[$row][1] = $htrow['gy_hol_type_name'];
    $holtarr[$row][2] = $htrow['gy_hol_status']; 
    $row++;
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
                        <div class="col-lg-12"><h2 class="title-1 m-b-25"><i class="fas fa-calendar-alt"></i> <?php echo $title; ?></h2></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        <ul class="nav nav-tabs">
                            <li class="nav-item"><a class="nav-link active" href="eventcalendar">Calendar</a></li>
                            <li class="nav-item"><a class="nav-link active" href="plot_calendar">Plot Holiday</a></li>
                            <li class="nav-item"><a class="nav-link active" aria-current="page" href="#"><strong>Holiday Policy</strong></a></li>
                        </ul>
                        </div>
                    </div>
                    <div class="row"><div class="col-md-12"><div class="card"><div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <!--<div class="card-header" style="padding: 0px;"><div class="input-group">
                                    <input type="text" class="form-control form-bline text-center" id="eventnameid" placeholder="Enter Event Name">
                                    <button class="btn btn-outline-secondary btn-sm" title="Add to the Event List" onclick="addtolist()">Add <i class="fa-solid fa-calendar-plus"></i><i class="fa-solid fa-turn-down"></i></button>
                                </div></div>-->
                                <div style="height: 458px; overflow: auto;">
                                <table class="table table-striped">
                                    <thead style="position: sticky; top: 0px; background-color: #fff; z-index: 1;"><tr>
                                        <th scope="col">Event Name</th>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                    </tr></thead>
                                    <tbody id="tblebtlst">
                                        <?php for($i=0;$i<$row;$i++){ ?><tr>
                                            <td><a href="#" onclick="readpol(this)" id="evtnma_<?php echo $holtarr[$i][0]; ?>"><?php echo $holtarr[$i][1]; ?></a></td>
                                            <td><?php if($holtarr[$i][2]==1){ ?><i class="fa-solid fa-check-to-slot text-success" title="Active"></i><?php }else if($holtarr[$i][2]==0){ ?><i class="fa-sharp fa-solid fa-ban text-danger" title="Inactive"></i><?php } ?></td>
                                            <td><button type="button" class="btn-close" aria-label="Close" title="Delete" <?php if($holtarr[$i][0]==1||$holtarr[$i][0]==2){echo"disabled";}?> onclick="deleteevtnm('<?php echo $holtarr[$i][0]; ?>')" ></button></td>
                                        </tr><?php } ?>
                                    </tbody>
                                </table>
                                </div>
                                <div class="card-footer" style="padding: 0px;"><div class="input-group">
                                    <!--<input type="text" id="searchevtnm" class="form-control form-bline text-center" placeholder="Enter Name to Search" onkeydown="searchevtnm()">
                                    <button class="btn btn-outline-secondary btn-sm" title="Search" onclick="searchevtnm()"><i class="fa-solid fa-magnifying-glass"></i></button>-->
                                </div></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card" id="policycard">
                                <div class="card-header" style="padding: 6px;"><center>
                                    <input type="text" class=" form-bline text-center" style="font-weight: bold;" disabled>
                                </center></div>
                                <div class="card-body" style="height: 420px; overflow: auto;">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-floating  mb-3">
                                            <input type="text" maxlength="10" class="form-control text-center text-uppercase fw-bold" placeholder="Make It Short..." id="abbre" disabled required>
                                            <label for="abbre">Abbreviation</label>
                                        </div>

                                        <div class="form-floating  mb-3">
                                            <input type="time" class="form-control" id="regulartimein" value="" disabled required>
                                            <label for="regulartimein">Day Time Start</label>
                                        </div>

                                        <div class="form-floating  mb-3">
                                            <input type="time" class="form-control" id="regulartimeout" value="" disabled required>
                                            <label for="regulartimeout">Day Time End</label>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="form-floating  mb-3">
                                            <select class="form-select" disabled><!--
                                                <option value="0">Inactive</option>
                                                <option value="1">Active</option>-->
                                            </select>
                                            <label for="polstatus"><!--Status--></label>
                                        </div>

                                        <div class="form-floating  mb-3">
                                            <input type="time" class="form-control" id="ndifftimein" value="" disabled required>
                                            <label for="ndifftimein">Night Diff Start</label>
                                        </div>

                                        <div class="form-floating  mb-3">
                                            <input type="time" class="form-control" id="ndifftimeout" value="" disabled required>
                                            <label for="regulartimeout">Night Diff End</label>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="card-footer" style="padding: 0px;">
                                    <!--<button class="btn btn-outline-secondary btn-block" disabled><i class="fa-sharp fa-solid fa-floppy-disk-circle-arrow-right"></i> Update</button>-->
                                </div>
                            </div>
                        </div>
                    </div>
                    </div></div></div></div>
                    <?php include 'footer.php'; ?>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" style="display: none;" value="<?php echo $trigtoast; ?>" id="hidtrigid">
    <input type="hidden" style="display: none;" value="<?php echo $evtname; ?>" id="hidtrignm">
    <?php include 'scripts.php'; ?>
    <script type="text/javascript">
        function chgtmval(elem, id){
            document.getElementById(id).value=elem.value;
        }
        function chg2warn(elem){
            elem.classList.add("text-warning");
        }

        function readpol(elem){
            const dtld = elem.id.split("_")[1];
            sendpost("policycard", "event/readpolicy.php", "polid="+dtld);
        }

        function deleteevtnm(id){
            var evtnm = document.getElementById("evtnma_"+id);
            const Toast = Swal.mixin({
                toast: true,
                  didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
            Toast.fire({
                icon: 'warning',
                title: 'Cannot be undo once done deleting <strong>'+evtnm.innerHTML+'</strong>, proceed anyway?',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="fa-sharp fa-solid fa-xmark"></i> Delete',
                cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancel'
            }).then((result) => {
                if(result.isConfirmed){
                    sendpost("tblebtlst", "event/load_eventlist.php", "evtnm=&opt=2&evtid="+id);
                }
            })
        }

        function searchevtnm(){
            var evtnm = document.getElementById("searchevtnm");
            sendpost("tblebtlst", "event/load_eventlist.php", "evtnm="+evtnm.value+"&opt=1");
        }

        function addtolist(){
            var evtnm = document.getElementById("eventnameid");
            sendpost("tblebtlst", "event/load_eventlist.php", "evtnm="+evtnm.value+"&opt=0");
            evtnm.value="";
        }

        function sendpost(sid, loc, post){
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200 && this.responseText != ""){
                    document.getElementById(sid).innerHTML = this.responseText;
                }
            };
            xhttp.open("POST", loc, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(post);
        }

        function loadstart(){
            var trig = document.getElementById("hidtrigid");
            var name = document.getElementById("hidtrignm");
            if(trig.value==1){
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
                  icon: 'success',
                  title: name.value+' updated successfully'
                })
            }
        }

        loadstart();
    </script>
</body>
</html>
<?php } $link->close(); ?>