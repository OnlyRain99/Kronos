<div class="modal fade" id="reason_<?php echo $leave['gy_leave_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="smallmodalLabel" style="text-transform: uppercase;">reason</h5>
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
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="approve_<?php echo $leave['gy_leave_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="smallmodalLabel" style="text-transform: uppercase;">approve?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <p>are you sure you want to approve this request?</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="leave_approve?cd=<?= $leave['gy_leave_id']; ?>"><button type="button" class="btn btn-success" title="click to submit approval ..."><i class="fa fa-check"></i> Approve</button></a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="move_<?php echo $leave['gy_leave_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white" id="smallmodalLabel" style="text-transform: uppercase;">move dates</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data" action="leave_move?cd=<?= $leave['gy_leave_id']; ?>" onsubmit="validateForm(this);">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>From</label>
                            <input type="date" name="trans_date_from" id="datefrom" onchange="daterange()" class="form-control" value="<?= $leave['gy_leave_date_from']; ?>" autofocus required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>To</label>
                            <input type="date" name="trans_date_to" id="dateto" onchange="daterange()" class="form-control" value="<?= $leave['gy_leave_date_to']; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea name="trans_reason" rows="3" class="form-control" placeholder="type here ..." required></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <p style="color: red; font-style: italic;">Note: do not plot between rest days ... it will be <b>counted as leave</b></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="move" id="submit" class="btn btn-info" title="click to submit approval ...">Submit & Approve</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="reject_<?php echo $leave['gy_leave_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="smallmodalLabel" style="text-transform: uppercase;">reject leave?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data" action="leave_reject?cd=<?= $leave['gy_leave_id']; ?>" onsubmit="validateForm(this);">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea name="reject_reason" rows="3" class="form-control" placeholder="type here ..." autofocus required></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="move" id="submit" class="btn btn-danger" title="click to reject leave ..."><i class="fa fa-times"></i> Reject</button>
            </div>
            </form>
        </div>
    </div>
</div>