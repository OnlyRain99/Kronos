<div class="modal fade" id="approve_<?php echo $escrow['gy_esc_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticModalLabel"><i class="fa fa-check"></i> Approve <span style="color: blue;"><?= getuserfullname($escrow['gy_esc_by']); ?></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    Type: <span style="color: blue;"> <?= escalate_type($escrow['gy_esc_type']); ?> </span> <br>
                    Do you want to approve this request?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a href="esc_approve?cd=<?php echo $escrow['gy_esc_id']; ?>"><button type="button" class="btn btn-success">Approve</button></a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deny_<?php echo $escrow['gy_esc_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticModalLabel"><i class="fa fa-times"></i> Deny <span style="color: blue;"><?= getuserfullname($escrow['gy_esc_by']); ?></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data" action="esc_deny?cd=<?php echo $escrow['gy_esc_id']; ?>">
            <div class="modal-body">
                <p>
                    <div class="form-group">
                        <label>Reason</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="type your reason here ..." autofocus required></textarea>
                    </div>
                    Do you want to deny this request?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" name="deny" id="submit" class="btn btn-danger">Deny</button>
            </div>
            </form>
        </div>
    </div>
</div>