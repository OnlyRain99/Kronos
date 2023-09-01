<div class="modal fade" id="reason_<?php echo $leave['gy_leave_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="smallmodalLabel" style="text-transform: uppercase;"><i class="fa fa-eye"></i> reason/remarks/attachment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body bg-warning">
                                <p>
                                    <center><b>REASON</b></center>
                                    <?= $leave['gy_leave_reason']; ?>
                                </p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body bg-info">
                                <p>
                                    <center><b>REMARKS</b></center>
                                    <?= $leave['gy_leave_remarks']; ?>
                                </p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body bg-default">
                                <p>
                                    <center><b>ATTACHMENT</b></center>
                                    <a href="dl_leave_attch?cd=<?= $leave['gy_leave_id']; ?>" title="click to downlad file ..." target="_new"><?= $leave['gy_leave_attachment']; ?></a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cancel_<?php echo $leave['gy_leave_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="smallmodalLabel" style="text-transform: uppercase;">cancel ?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <a href="leave_cancel?cd=<?= $leave['gy_leave_id']; ?>"><button type="button" class="btn btn-danger" title="click to submit cancellation ...">Submit</button></a>
            </div>
        </div>
    </div>
</div>