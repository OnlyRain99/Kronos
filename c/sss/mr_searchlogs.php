<?php
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';
    if($myaccount == 22 || $user_dept == 9){
    include '../../config/connnk.php';

    $empcode = addslashes($_REQUEST['empcode']);
    $fdate = addslashes($_REQUEST['fdate']);
    $tdate = addslashes($_REQUEST['tdate']);

    $i = 0; $ticketid = array(); $ticketarr = array(array());
    $tktlist=$dbticket->query("SELECT `emp_code`,`channel`,`ticket_date`,`ticket_id` From `ticket` Where `ticket_date`>='".date("Y-m-d H:i:s",strtotime($fdate." 00:00:00"))."' AND `ticket_date`<='".date("Y-m-d H:i:s",strtotime($tdate." 24:00:00"))."' AND `emp_code`='".$empcode."'");
    if(mysqli_num_rows($tktlist) > 0){
        while($tktrow=$tktlist->fetch_array()){
            $ticketid[$i] = $tktrow['emp_code'];
            $ticketarr[$i][0] = $tktrow['channel'];
            $ticketarr[$i][1] = $tktrow['ticket_date'];
            $ticketarr[$i][2] = $tktrow['ticket_id'];
            $i++;
        }
    }

    $dbticket->close();

    $vxlemp=$link->query("SELECT `gy_emp_email`,`gy_emp_fname`,`gy_emp_lname`,`gy_emp_mname` From `gy_employee` WHERE `gy_emp_code`='".$empcode."' LIMIT 1");
    $vxlrow=$vxlemp->fetch_array();
    $email = $vxlrow['gy_emp_email'];
    $fname = $vxlrow['gy_emp_fname'];
    $lname = $vxlrow['gy_emp_lname'];
    $mname = $vxlrow['gy_emp_mname'];

    for($i1=0;$i1<count($ticketid);$i1++){
        tblcntt($empcode, $email, $fname, $lname, $mname, date("m/d/Y", strtotime($ticketarr[$i1][1])), date("h:i:s A", strtotime($ticketarr[$i1][1])), $ticketarr[$i1][0], $ticketarr[$i1][2]);
    }

} $link->close();

function tblcntt($empcode, $emyl, $fname, $lname, $mname, $date, $time, $channel, $tcktid){ ?>
<tr>
    <th scope="row" style="padding: 5px;" class="text-center"><?php echo $empcode; ?></th>
    <th style="padding: 5px;" class="text-center"><?php echo $emyl; ?></th>
    <th style="padding: 5px;" class="text-nowrap text-center"><?php echo $fname; ?></th>
    <th style="padding: 5px;" class="text-nowrap text-center"><?php echo $lname; ?></th>
    <th style="padding: 5px;" class="text-nowrap text-center"><?php echo $mname; ?></th>
    <th style="padding: 5px;" class="text-center"><?php echo $date; ?></th>
    <th style="padding: 5px;" class="text-center"><?php echo $time; ?></th>
    <th style="padding: 5px;" class="text-center"><?php echo $channel; ?></th>
    <th style="padding: 5px;" class="text-center"><?php echo $tcktid; ?></th>
</tr>
<?php } ?>