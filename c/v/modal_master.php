<div class="modal fade" id="edit_<?php echo $trackrow['gy_tracker_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel"><i class="fa fa-edit"></i> Edit <span style="color: blue;"><?php echo $trackrow['gy_emp_fullname']; ?></span> Time Keep</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data" action="<?php echo $timekeep; ?>" onsubmit="return validateForm(this);">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label style="font-weight: bold; color: #000;">Login <span style="color: red;">*required</span></label>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="date" class="form-control" name="logindate" value="<?php echo puredate(date('Y-m-d', strtotime($trackrow['gy_tracker_login']))); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Time</label>
                                    <input type="time" class="form-control" name="logintime" value="<?php echo puretime(date('H:i:s', strtotime($trackrow['gy_tracker_login']))); ?>"  step="any" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label style="font-weight: bold; color: #000;">Break-out</label>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="date" class="form-control" name="breakoutdate" value="<?php echo puredate(date('Y-m-d', strtotime($trackrow['gy_tracker_breakout']))); ?>" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Time</label>
                                    <input type="time" class="form-control" name="breakouttime" value="<?php echo puretime(date('H:i:s', strtotime($trackrow['gy_tracker_breakout']))); ?>" step="any" >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label style="font-weight: bold; color: #000;">Break-in</label>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="date" class="form-control" name="breakindate" value="<?php echo puredate(date('Y-m-d', strtotime($trackrow['gy_tracker_breakin']))); ?>" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Time</label>
                                    <input type="time" class="form-control" name="breakintime" value="<?php echo puretime(date('H:i:s', strtotime($trackrow['gy_tracker_breakin']))); ?>" step="any" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label style="font-weight: bold; color: #000;">Logout <span style="color: red;">*required</span></label>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="date" class="form-control" name="logoutdate" value="<?php echo puredate(date('Y-m-d', strtotime($trackrow['gy_tracker_logout']))); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Time</label>
                                    <input type="time" class="form-control" name="logouttime" value="<?php echo puretime(date('H:i:s', strtotime($trackrow['gy_tracker_logout']))); ?>" step="any" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" name="update" id="submit" class="btn btn-primary">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="app_<?php echo $trackrow['gy_tracker_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="smallmodalLabel"><i class="fa fa-thumbs-up"></i> Approve</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data" action="<?php echo $statuschange; ?>">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p>
                            <span style="font-weight: bold;" title="<?= date('m/d/Y', strtotime($trackrow['gy_tracker_login'])) ?>">Login:</span> <?= date("g:i A", strtotime($trackrow['gy_tracker_login'])); ?>&nbsp;
                            <span style="font-weight: bold;" title="<?= date('m/d/Y', strtotime($trackrow['gy_tracker_logout'])) ?>">Logout:</span> <?= date("g:i A", strtotime($trackrow['gy_tracker_logout'])); ?>&nbsp;
                        </p>
                    </div>
                    <div class="col-md-12">
                        <p>
                            <span style="font-weight: bold;" title="<?= date('m/d/Y', strtotime($trackrow['gy_tracker_breakout'])) ?>">BO:</span> <?= date("g:i A", strtotime($trackrow['gy_tracker_breakout'])); ?>&nbsp;
                            <span style="font-weight: bold;" title="<?= date('m/d/Y', strtotime($trackrow['gy_tracker_breakin'])) ?>">BI:</span> <?= date("g:i A", strtotime($trackrow['gy_tracker_breakin'])); ?>&nbsp;
                        </p>
                    </div>
                    
                    <div class="col-md-12">
                        <br>
                        <p class="text-center">Approve Overtime?</p>
                        <br>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check">
                            <label style="color: blue;"></label>
                            <div class="radio-check-inline">
                                <label for="otno" class="form-check-label">
                                    <input type="radio" id="otno_<?= $trackrow['gy_tracker_id']; ?>" name="otopt" value="no" class="form-check-input" onchange="ot_<?= $trackrow['gy_tracker_id'];?>()">No
                                </label>
                            </div>
                            <div class="radio-check-inline">
                                <label for="otyes" class="form-check-label">
                                    <input type="radio" id="otyes_<?= $trackrow['gy_tracker_id']; ?>" name="otopt" value="yes" class="form-check-input" onchange="ot_<?= $trackrow['gy_tracker_id'];?>()" required>Yes
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label style="color: blue;">Overtime</label>
                            <input type="number" name="overtime" class="form-control" id="overtime_<?= $trackrow['gy_tracker_id']; ?>" step="0.01" min="0" max="<?= $trackrow['gy_tracker_ot']; ?>" value="<?= $trackrow['gy_tracker_ot']; ?>" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" name="approve" class="btn btn-primary">Confirm</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="rej_<?php echo $trackrow['gy_tracker_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel"><i class="fa fa-thumbs-down"></i> Reject</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data" action="<?php echo $statuschange; ?>">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>
                                <?php 
                                    echo 
                                    date("m/d/Y", strtotime($trackrow['gy_tracker_date']))." - 
                                    <i>".$trackrow['gy_emp_fullname']."</i>";
                                ?>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <label>select reason</label>
                            <select class="form-control" name="my_reason" required>
                                <option><?= $trackrow['gy_tracker_reason']; ?></option>
                                <?php  
                                    //get reasons
                                    $reason=$link->query("SELECT `gy_reason_name` From `gy_reason` Order By `gy_reason_id` ASC");
                                    while ($reasonrow=$reason->fetch_array()) {
                                ?>
                                <option><?= $reasonrow['gy_reason_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>remarks</label>
                            <textarea name="remarks" id="remarks" class="form-control" rows="5" required><?php echo $trackrow['gy_tracker_remarks']; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" name="update" class="btn btn-primary">Confirm</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="info_<?php echo $trackrow['gy_tracker_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="smallmodalLabel"><i class="fa fa-eye"></i> Remarks/Update Log</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>
                                <strong style="text-transform: uppercase;">
                                <?= $omname; ?> <small style="font-weight: normal;"><?= $myom; ?></small><br>
                                <?= $trackrow['gy_tracker_reason']; ?>
                                </strong>
                                <br>
                                <?= $trackrow['gy_tracker_remarks']; ?>
                            </label>
                            <hr>
                            <center><p>-Updates-</p></center>
                            <label style="font-size: 12px;">
                                <?php 
                                    $history = explode(",", $trackrow['gy_tracker_history']);

                                    foreach ($history as $logs) {
                                        echo $logs."<br>";
                                    }
                                ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function ot_<?= $trackrow['gy_tracker_id'];?>(){

        var rej2 = document.getElementById("otyes_<?= $trackrow['gy_tracker_id']; ?>").checked;

        if (rej2 == true) {
            document.getElementById("overtime_<?= $trackrow['gy_tracker_id'];?>").disabled = false;
            document.getElementById("overtime_<?= $trackrow['gy_tracker_id'];?>").focus();
            document.getElementById("overtime_<?= $trackrow['gy_tracker_id'];?>").value = "<?= $trackrow['gy_tracker_ot']; ?>";
        }else{
            document.getElementById("overtime_<?= $trackrow['gy_tracker_id'];?>").disabled = true;
            document.getElementById("overtime_<?= $trackrow['gy_tracker_id'];?>").value = 0;
        }
    }
</script>