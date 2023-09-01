<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';

    $title = "My Leave Calendar";

$inimlcdate = $onlydate;
if(isset($_REQUEST['lcdate'])){
$inimlcdate = addslashes($_REQUEST['lcdate']);
}

$empsql = $link->query("SELECT `gy_emp_leave_credits` From `gy_employee` WHERE `gy_emp_code`='$user_code' LIMIT 1");;
$emprow=$empsql->fetch_array();
$lvcredit=$emprow['gy_emp_leave_credits'];

$link->close();
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
                            <h2 class="float-left title-1 m-b-25"><i class="fa-solid fa-calendar-day"></i> <?php echo $title; ?></h2>
                            <div class="float-right m-t-35 faa-tada animated faa-slow"><b id="lvccngme"><?php echo $lvcredit; ?></b></div>
                            <div class="float-right m-t-35 m-r-5"><b>Leave Credits : </b></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        <div class="card">
                            <div class="card-body"><div id="calendar"></div></div>
                            <div id="clrsql"></div><div id="sbmtloa"></div>
                        </div>
                        </div>
                    </div>
                    <?php include 'footer.php'; ?>
                </div>
            </div>
        </div>
    </div>
    <?php include 'scripts.php'; ?>
    <script src='https://cdn.jsdelivr.net/npm/rrule@2.6.4/dist/es5/rrule.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/rrule@5.5.0/main.global.min.js'></script>
    <script type="text/javascript">
        var calendar="";

        function removeplan(date, lid){
            sendpost("rtrntoplnlst", "leave/removeplan.php", "lid="+lid+"&date="+date);
        }

        function showapprloa(lid){
            sendpost("acrdbody_"+lid, "leave/showtblaprvloa.php", "lid="+lid);
        }

        function confrmcancelloa(date, lid){
            const Toast = Swal.mixin({
                      toast: true,
                })
                Toast.fire({
                      icon: 'warning',
                      title: 'Proceed Removing this LOA Request?',
                      text: 'Action cannot be undo once done',
                      showCancelButton: true,
                      confirmButtonColor: '#d33',
                      cancelButtonColor: '#3085d6',
                      confirmButtonText: '<i class="fa-solid fa-eraser"></i> Remove LOA Request',
                      cancelButtonText: '<i class="fa-solid fa-person-running"></i> Do not Remove LOA'
                }).then((result) => {
                    if(result.isConfirmed){
                        sendpost("sbmtloa", "leave/removeloarequest.php", "lid="+lid);
                    }
                })
        }

        function filealeave(date){
            var lvtyp = document.getElementById("leaveid").value;
            var lvres = document.getElementById("leavereason").value;
            var lvfyl = document.getElementById("leavefile").files;
            var hiddt = document.getElementById("hiddate").value;
            var lvfhd = "";
            if(document.getElementById("fdid").checked==true){ lvfhd="fdid"; }
            else if(document.getElementById("hdid").checked==true){ lvfhd="hdid"; }
            if(hiddt==date && lvtyp!="" && lvres!="" && lvfyl.length>0){
                var formData = new FormData();
                formData.append("lvtyp", lvtyp);
                formData.append("lvres", lvres);
                formData.append("lvfyl", lvfyl[0]);
                formData.append("hiddt", date);
                formData.append("lvfhd", lvfhd);
                //sendpost("sbmtloa","leave/submitloarequest.php", formData);

                var xhttp = new XMLHttpRequest();
                    xhttp.open("POST", "leave/submitloarequest.php", true);
                    xhttp.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200) {
                        //document.getElementById("sbmtloa").innerHTML = this.responseText;
                        loaresponse(this.responseText);
                    }
                    };
                xhttp.send(formData);
            }
        }

        function switchlvplt(date, tabnm){
            sendpost("pltdtyl","leave/plotview.php", "startdate="+date+"&pltvw="+tabnm);
        }

        function refrshnotif(){
            sendpost("lvccngme","leave/refreshnotification.php", "type=0");
            sendpost("sbid-mlcntf","leave/refreshnotification.php", "type=1");
            sendpost("sbid-ircntf","leave/refreshnotification.php", "type=2");
        }

        function loaresponse(status){
            calendar.refetchEvents();
            var title="";
            if(status=="success"){ title="LOA request submitted to your supervisor"; }
            else if(status=="danger"){ status="error"; title="Error on request, try again"; }
            else if(status=="sizelimit"){ status="warning"; title="The attached file exceeded the file size limit"; }
            else if(status=="fileinvalid"){ status="warning"; title="The attached file is invalid"; }
            else if(status=="invalidaction"){ status="error"; title="invalid Action"; }
            else if(status=="loaremove"){ status='success'; title="LOA Request Removed"; }
            else if(status=="loanotremove"){ status="error"; title="Encountered an Error on Removing LOA Request, please try again"; }
            else if(status=="loacancel"){ status='info'; title="Approved LOA Request has been cancel"; }
            else if(status=="loanotcancel"){ status="error"; title="Encountered an Error on Cancelling LOA Request, please try again"; }
            toastalert(status, title);
        }

        function showresponse(status){
            calendar.refetchEvents();

            var title = "";
            if(status=="success"){ title="Calendar plotted"; }
            else if(status=="error"){ title= "Error plotting"; }
            else if(status=="duplicate"){ title= "Duplicatte or conflict dates, please try again on a vacant dates"; status="warning"; }
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

        function plottrigger(){
            var accntid = document.getElementById("empdepartment").value;
            var dtfrom = document.getElementById("hiddate").value;
            var dtto = document.getElementById("dateend").value;
            var lslot = document.getElementById("leaveslot").value;
            var justfy = document.getElementById("leavejstfy").value;

            sendpost("clrsql", "leave/plotdates.php", "accntid="+accntid+"&dtfrom="+dtfrom+"&dtto="+dtto+"&lslot="+lslot+"&justfy="+justfy)
        }

      document.addEventListener('DOMContentLoaded', function(){
        var calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          initialDate: '<?php echo $inimlcdate; ?>',
        dateClick: function(info){
            Swal.fire({
                width: 'auto',
                //title: info.dateStr,
                showCancelButton: false,
                showConfirmButton: false,
                html: '<div id="pltdtyl"></div>'
            })
            info.dayEl.style.backgroundColor = '#ECE9E8';
            sendpost("pltdtyl","leave/plotview.php", "startdate="+info.dateStr+"&pltvw=2");
        },
        eventDidMount: function(info) {
            var tooltip = new bootstrap.Tooltip(info.el, {
            title: info.event.extendedProps.description,
            placement: 'top',
            trigger: 'hover',
            container: 'body'
          });
        },
          events: { url: 'leave/calendarevent' }
      });
      calendar.render();
      });

        function sendpost(sid, loc, post){
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200 && this.responseText != ""){
                    if(sid=="clrsql"){ showresponse(this.responseText); }
                    else if(sid=="sbmtloa"){ loaresponse(this.responseText); }
                    else if(sid=="rtrntoplnlst"){ calendar.refetchEvents(); switchlvplt(this.responseText, 1); }
                    else{ document.getElementById(sid).innerHTML = this.responseText; }
                }
            };
            xhttp.open("POST", loc, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(post);
        }

    </script>
</body>
</html>