<div class="modal fade" id="reason_<?= $reqrow['gy_sched_esc_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-mm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel"><i class="fa fa-comments"></i> Reason</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="jumbotron">
                    <b><?= getuserfullname($reqrow['gy_req_by']); ?></b>
                    <br>
                    "<?= $reqrow['gy_req_reason']; ?>"
                    <?php if($reqrow['gy_req_status']==2){ ?>
                    <br><br>
                    <b>Denied Because:</b><br>
                    "<?= $reqrow['gy_req_deny']; ?>"
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pic_<?= $reqrow['gy_sched_esc_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-mm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel"><i class="fa fa-photo"></i> Attachment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php  
                    if ($reqrow['gy_req_photodir'] == "") {
                        echo "<p>no attachment</p>";
                    }else{
                ?>
                    <center>
                    <span style="font-style: italic;"><?= $reqrow['gy_req_photodir']; ?></span>
                    <img src="<?= '../../kronos_file_store/'.$reqrow['gy_req_photodir']; ?>" alt="no preview available">
                    </center>

                <?php } ?>
            </div>
            <div class="modal-footer">
                <center><a href="download?cd=<?= $reqrow['gy_sched_esc_id']; ?>&type=sched"><button type="button" class="btn btn-primary" title="click to download attachment ..."><i class="fa fa-download"></i> Download</button></a></center>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="approve_<?php echo $reqrow['gy_sched_esc_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticModalLabel"><i class="fa fa-check"></i> Approve <span style="color: blue;"><?= $reqrow['gy_emp_fullname']; ?></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    Do you want to approve this request?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a href="sched_esc_approve?cd=<?php echo $reqrow['gy_sched_esc_id']; ?>"><button type="button" class="btn btn-success">Approve</button></a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deny_<?php echo $reqrow['gy_sched_esc_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticModalLabel"><i class="fa fa-times"></i> Deny <span style="color: blue;"><?= $reqrow['gy_emp_fullname']; ?></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data" action="sched_esc_deny?cd=<?php echo $reqrow['gy_sched_esc_id']; ?>&trackcode=<?php echo $reqrow['gy_sched_esc_code']; ?>">
            <div class="modal-body">
                <p>
                    <div class="form-group">
                        <label>Comment</label>
                        <textarea name="comment" class="form-control" rows="3" placeholder="type a comment here ..." autofocus required></textarea>
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