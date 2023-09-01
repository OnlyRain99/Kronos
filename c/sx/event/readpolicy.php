<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

if ($user_type == 6 && $user_dept == 2) {
$polid = addslashes($_REQUEST['polid']);
    $htsql=$link->query("SELECT * FROM `gy_holiday_types` WHERE `gy_hol_type_id`=$polid");
    if($htsql->num_rows==1){
        $row=0; $holparr = array();
        while($htrow=$htsql->fetch_array()){
            $holparr[0] = $htrow['gy_hol_type_id'];
            $holparr[1] = $htrow['gy_hol_type_name'];
            $holparr[2] = $htrow['gy_hol_abbrv'];
            $holparr[3] = $htrow['gy_daybonus'];
            $holparr[4] = $htrow['gy_nightbonus'];
            $holparr[5] = $htrow['gy_day_start'];
            $holparr[6] = $htrow['gy_day_end'];
            $holparr[7] = $htrow['gy_night_start'];
            $holparr[8] = $htrow['gy_night_end'];
            $holparr[9] = $htrow['gy_hol_status'];
            $row++;
        }
    } ?>
<form method="post" enctype="multipart/form-data" action="event_rules" >
    <div class="card-header" style="padding: 6px;"><center>
        <input type="text" class=" form-bline text-center" name="eventname" id="eventname" onkeydown="chg2warn(this)" value="<?php echo $holparr[1]; ?>" style="font-weight: bold;" disabled required>
        <input type="hidden" style="display: none;" value="<?php echo $holparr[0]; ?>" name="eventid">
    </center></div>
    <div class="card-body" style="height: 420px; overflow: auto;">
        <div class="row">
            <div class="col">
                <div class="form-floating  mb-3">
                    <input type="text" maxlength="10" class="form-control text-center text-uppercase fw-bold" placeholder="Make It Short..." id="abbre" name="abbre" onkeydown="chg2warn(this)" value="<?php echo $holparr[2]; ?>" disabled required>
                    <label for="abbre">Abbreviation</label>
                </div>

                <div class="form-floating  mb-3">
                    <input type="time" class="form-control" id="regulartimein" name="regulartimein" onchange="chg2warn(this); chgtmval(this, 'ndifftimeout');" value="<?php echo $holparr[5]; ?>" disabled required>
                    <label for="regulartimein">Day Time Start</label>
                </div>

                <div class="form-floating  mb-3">
                    <input type="time" class="form-control" id="regulartimeout" name="regulartimeout" onchange="chg2warn(this); chgtmval(this, 'ndifftimein');" value="<?php echo $holparr[6]; ?>" disabled required>
                    <label for="regulartimeout">Day Time End</label>
                </div>
            </div>

            <div class="col">
                <div class="form-floating  mb-3">
                    <select class="form-select" onchange="chg2warn(this)" id="polstatus" name="polstatus" disabled ><!--
                        <option value="0" <?php// if($holparr[9]==0){echo"selected";} ?>>Inactive</option>
                        <option value="1" <?php// if($holparr[9]==1){echo"selected";} ?>>Active</option>-->
                    </select>
                    <label for="polstatus"><!--Status--></label>
                </div>

                <div class="form-floating  mb-3">
                    <input type="time" class="form-control" onchange="chg2warn(this); chgtmval(this, 'regulartimeout');" id="ndifftimein" name="ndifftimein" value="<?php echo $holparr[7]; ?>" disabled required>
                    <label for="ndifftimein">Night Diff Start</label>
                </div>

                <div class="form-floating  mb-3">
                    <input type="time" class="form-control" onchange="chg2warn(this); chgtmval(this, 'regulartimein');" id="ndifftimeout" name="ndifftimeout" value="<?php echo $holparr[8]; ?>" disabled required>
                    <label for="ndifftimeout">Night Diff End</label>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer" style="padding: 0px;">
        <!--<button class="btn btn-outline-secondary btn-block" name="submitpol"><i class="fa-sharp fa-solid fa-floppy-disk-circle-arrow-right"></i> Update</button>-->
    </div>
</form>
<?php } $link->close(); ?>