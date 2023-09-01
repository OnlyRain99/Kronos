<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';
    if($myaccount == 22){
	include '../../../config/connnk.php';

    //array master list users
    $i = 0; $vxlmarr = array();
    $vxlmlist=$dbticket->query("SELECT `mr_emp_code` From `vidaxl_masterlist`"); 
        while($vxlmrow=$vxlmlist->fetch_array()){
            $vxlmarr[$i] = $vxlmrow['mr_emp_code'];
            $i++;
        }

$dbticket->close();
?>
<ul class="nav nav-tabs">
  <li class="nav-item">
    <button class="nav-link active" onclick="checkreports()">Scheduled Reports</button>
  </li>
  <li class="nav-item">
    <button class="nav-link" onclick="checkticketlogs()">Ticket Logs</button>
  </li>
</ul>
<div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text" >User: </span>
  </div>
    <select class="form-control" id="searchempsel">
        <option></option>
        <option value="all">All</option>
        <?php
            $vxlemp=$link->query("SELECT `gy_emp_code`,`gy_emp_fullname` From `gy_employee` Where `gy_emp_code` IN ('".implode("','",$vxlmarr)."') ORDER BY `gy_emp_fullname` ASC");
                while($vxlrow=$vxlemp->fetch_array()){ ?>
        <option value="<?php echo $vxlrow['gy_emp_code']; ?>"><?php echo $vxlrow['gy_emp_fullname']; ?></option>
        <?php } ?>
    </select>
    <span class="input-group-text" > From : </span>
    <input type="date" name="from" id="datefrom" onchange="daterange()" class="form-control" required>
    <span class="input-group-text" > To : </span>
    <input type="date" name="to" id="dateto" onchange="daterange()" class="form-control" required>
  <button class="btn btn-outline-secondary" id="btnsearchrpt" onclick="searchemprep(this)" >Search <i class="fas fa-search"></i></button>
</div>

<div class="table-responsive">
    <table class="table table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
        <thead>
            <tr>
                <th scope="col" style="padding: 10px;" class="text-center">ID</th>
                <th scope="col" style="padding: 10px;" class="text-center">Email</th>
                <th scope="col" style="padding: 10px;" class="text-center">First Name</th>
                <th scope="col" style="padding: 10px;" class="text-center">Last Name</th>
                <th scope="col" style="padding: 10px;" class="text-center">Middle Name</th>
                <th scope="col" style="padding: 10px;" class="text-center">Depertment</th>
                <th scope="col" style="padding: 10px;" class="text-center">Date</th>
                <th scope="col" style="padding: 10px;" class="text-center">Login</th>
                <th scope="col" style="padding: 10px;" class="text-center">Logout</th>
                <th scope="col" style="padding: 10px;" class="text-center">Chat</th>
                <th scope="col" style="padding: 10px;" class="text-center">Email</th>
                <th scope="col" style="padding: 10px;" class="text-center">Phone</th>
            </tr>
        </thead>
        <tbody id="tblreport"></tbody>
    </table>
</div>
<?php } $link->close(); ?>