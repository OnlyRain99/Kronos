
<div class="modal fade" id="edit_<?= $schedrow['gy_sched_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-mm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel"><i class="fa fa-edit"></i> <?= date("m/d/Y, D", strtotime($schedrow['gy_sched_day'])); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data" action="<?= $edit_link; ?>" onsubmit="return validateForm(this);">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" name="myday" class="form-control" value="<?= $schedrow['gy_sched_day']; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>WORK/OFF</label>
                            <select name="mymode" id="mymode_<?= $schedrow['gy_sched_id'] ?>" class="form-control" onchange="work_off_<?= $schedrow['gy_sched_id'] ?>()" required>
                                <option value="<?= $schedrow['gy_sched_mode'] ?>"><?= get_mode($schedrow['gy_sched_mode']) ?></option>
                                <option value="1">WORK</option>
                                <option value="0">OFF</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label style="color: green;">Login <i class="fa fa-clock"></i></label>
                            <select name="mylogin" id="mylogin_<?= $schedrow['gy_sched_id'] ?>" class="form-control" <?= $optstat; ?> required>
                                <option value="<?= checktime($schedrow['gy_sched_login']) ?>"><?= $loginopt; ?></option>
                                <?php  
                                    for ($i=1; $i <= 24; $i++) {

                                        if ($i > 12) {

                                            if ($i == 24) {
                                                $ampm = "AM";
                                            }else{
                                                $ampm = "PM";
                                            }

                                            $mainval = $i.":00:00";
                                            $displayval = ($i - 12).":00 ".$ampm;
                                        }else{

                                            if ($i == 12) {
                                                $ampm = "PM";
                                            }else{
                                                $ampm = "AM";
                                            }

                                            $mainval = $i.":00:00";
                                            $displayval = $i.":00 ".$ampm;
                                        }
                                ?>
                                <option value="<?= $mainval; ?>"><?= $displayval; ?></option>
                                <?php } ?>
                            </select>
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Break-Out <i class="fa fa-clock"></i></label>
                            <select name="mybreakout" id="mybreakout_<?= $schedrow['gy_sched_id'] ?>" class="form-control" <?= $optstat; ?> >
                                <option value="<?= checktime($schedrow['gy_sched_breakout']) ?>"><?= $breakoutopt; ?></option>
                                <?php  
                                    for ($i=1; $i <= 24; $i++) {

                                        if ($i > 12) {

                                            if ($i == 24) {
                                                $ampm = "AM";
                                            }else{
                                                $ampm = "PM";
                                            }

                                            $mainval = $i.":00:00";
                                            $displayval = ($i - 12).":00 ".$ampm;
                                        }else{

                                            if ($i == 12) {
                                                $ampm = "PM";
                                            }else{
                                                $ampm = "AM";
                                            }

                                            $mainval = $i.":00:00";
                                            $displayval = $i.":00 ".$ampm;
                                        }
                                ?>
                                <option value="<?= $mainval; ?>"><?= $displayval; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Break-In <i class="fa fa-clock"></i></label>
                            <select name="mybreakin" id="mybreakin_<?= $schedrow['gy_sched_id'] ?>" class="form-control" <?= $optstat; ?> >
                                <option value="<?= checktime($schedrow['gy_sched_breakin']) ?>"><?= $breakinopt; ?></option>
                                <?php  
                                    for ($i=1; $i <= 24; $i++) {

                                        if ($i > 12) {

                                            if ($i == 24) {
                                                $ampm = "AM";
                                            }else{
                                                $ampm = "PM";
                                            }

                                            $mainval = $i.":00:00";
                                            $displayval = ($i - 12).":00 ".$ampm;
                                        }else{

                                            if ($i == 12) {
                                                $ampm = "PM";
                                            }else{
                                                $ampm = "AM";
                                            }

                                            $mainval = $i.":00:00";
                                            $displayval = $i.":00 ".$ampm;
                                        }
                                ?>
                                <option value="<?= $mainval; ?>"><?= $displayval; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label style="color: red;">Logout <i class="fa fa-clock"></i></label>
                            <select name="mylogout" id="mylogout_<?= $schedrow['gy_sched_id'] ?>" class="form-control" <?= $optstat; ?> required>
                                <option value="<?= checktime($schedrow['gy_sched_logout']) ?>"><?= $logoutopt; ?></option>
                                <?php  
                                    for ($i=1; $i <= 24; $i++) {

                                        if ($i > 12) {

                                            if ($i == 24) {
                                                $ampm = "AM";
                                            }else{
                                                $ampm = "PM";
                                            }

                                            $mainval = $i.":00:00";
                                            $displayval = ($i - 12).":00 ".$ampm;
                                        }else{

                                            if ($i == 12) {
                                                $ampm = "PM";
                                            }else{
                                                $ampm = "AM";
                                            }

                                            $mainval = $i.":00:00";
                                            $displayval = $i.":00 ".$ampm;
                                        }
                                ?>
                                <option value="<?= $mainval; ?>"><?= $displayval; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" name="submit" id="submit" class="btn btn-primary">Confirm</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    function work_off_<?= $schedrow['gy_sched_id'] ?>(){

        var mode = _getID("mymode_<?= $schedrow['gy_sched_id'] ?>").value;

        if (mode == 0) {
            $("#mylogin_<?= $schedrow['gy_sched_id'] ?>").prop("disabled", true);
            $("#mybreakout_<?= $schedrow['gy_sched_id'] ?>").prop("disabled", true);
            $("#mybreakin_<?= $schedrow['gy_sched_id'] ?>").prop("disabled", true);
            $("#mylogout_<?= $schedrow['gy_sched_id'] ?>").prop("disabled", true);
        }else{
            $("#mylogin_<?= $schedrow['gy_sched_id'] ?>").prop("disabled", false);
            $("#mybreakout_<?= $schedrow['gy_sched_id'] ?>").prop("disabled", false);
            $("#mybreakin_<?= $schedrow['gy_sched_id'] ?>").prop("disabled", false);
            $("#mylogout_<?= $schedrow['gy_sched_id'] ?>").prop("disabled", false);
        }
    }
</script>

<div class="modal fade" id="delete_<?= $schedrow['gy_sched_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticModalLabel"><i class="fa fa-trash"></i> Delete <span style="color: blue;"><?= date("m/d/Y, D", strtotime($schedrow['gy_sched_day'])); ?></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    Do you want to delete this schedule?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a href="<?= $delete_link; ?>"><button type="button" class="btn btn-danger">Confirm</button></a>
            </div>
        </div>
    </div>
</div>