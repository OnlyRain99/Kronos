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