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
                        <span style="font-weight: bold;">
                            <?= $trackrow['gy_emp_fullname']; ?>
                            <br>
                            <?= date("m/d/Y", strtotime($trackrow['gy_tracker_date'])) ?>
                        </span>
                    </div>

                    <div class="col-md-12">
                        <hr>
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
                        <hr>
                        <div class="form-group">
                            <label>WORK HOURS <small style="color: blue;">approve 8hrs</small></label>
                            <input type="number" class="form-control" name="wh" value="<?= $trackrow['gy_tracker_wh']; ?>" readonly required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" name="approve" id="submit" class="btn btn-success">Confirm</button>
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
                <button type="submit" name="update" class="btn btn-danger">Confirm</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="info_<?php echo $trackrow['gy_tracker_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="smallmodalLabel"><i class="fa fa-eye"></i> Remarks</h5>
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