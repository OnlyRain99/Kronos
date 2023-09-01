<?php
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $title = "Event Calendar";  

if ($user_type == 6 && $user_dept == 2) {
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
                            <li class="nav-item"><a class="nav-link active" aria-current="page" href="#"><strong>Calendar</strong></a></li>
                            <li class="nav-item"><a class="nav-link active" href="plot_calendar">Plot Holiday</a></li>
                            <li class="nav-item"><a class="nav-link active" href="event_rules">Holiday Policy</a></li>
                        </ul>

                        <div class="card">
                            <div class="card-body"><div id="calendar"></div></div>
                        </div>
                        <div id="calsql"></div>
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
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
        dateClick: function(info){
            Swal.fire({
                title: 'Date: '+info.dateStr,
                icon: 'question',
                width: 'auto',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<i class="fas fa-flag"></i> Plot',
                html: '<div class="form-floating mb-1">'+
                      '<input type="text" class="form-control" id="calevtnm" placeholder=" ">'+
                      '<label for="calevtnm">Enter Event Name</label>'+
                      '</div>'+
                      '<div class="form-floating mb-1">'+
                      '<select class="form-select" id="evttype">'+
                      <?php $htsql=$link->query("SELECT * FROM `gy_holiday_types` WHERE `gy_hol_status`!=0"); while($htrow=$htsql->fetch_array()){ echo '\'<option value="'.$htrow['gy_hol_type_id'].'">'.$htrow['gy_hol_type_name'].'</option>\'+'; } ?>
                      '</select>'+
                      '<label for="evttype">Select Type of Event</label>'+
                      '</div>'+
					  '<div class="form-floating">'+
					  '<select class="form-select" id="evtloc">'+
                      '<option value="2">All</option>'+
                      '<option value="0">Tagum</option>'+
                      '<option value="1">Davao</option>'+
                      '</select>'+
                      '<label for="evtloc">Affected Location</label>'+
					  '</div>'
            }).then((result) => {
                if(result.isConfirmed){
                    var name = document.getElementById("calevtnm").value;
                    var etype = document.getElementById("evttype").value;
					var eloc = document.getElementById("evtloc").value;
                 if(name!=""&&etype!=""){
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200){
                        document.getElementById("calsql").innerHTML = this.responseText;
                        calendar.refetchEvents();
                    }};
                    xhttp.open("POST", "calendat_updoo", true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.send("name="+name+"&type="+etype+"&dateoo="+info.dateStr+"&eloc="+eloc);
                 }else{ Swal.fire('Field was Empty','Failed to record the event!','error') }
                }
            })
            info.dayEl.style.backgroundColor = '#ECE9E8';
        },
        eventDidMount: function(info) {
            var tooltip = new bootstrap.Tooltip(info.el, {
            title: info.event.extendedProps.description,
            placement: 'top',
            trigger: 'hover',
            container: 'body'
          });
        },
          events: { url: 'calendarevent' }
        });
        calendar.render();

      });
    </script>
    <?php } $link->close(); ?>
</body>
</html>