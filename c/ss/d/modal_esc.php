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
                    <img src="<?= '../../../kronos_file_store/'.$reqrow['gy_req_photodir']; ?>" alt="no preview available">
                    </center>

                <?php } ?>
            </div>
        </div>
    </div>
</div>