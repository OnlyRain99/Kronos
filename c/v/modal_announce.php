<div class="modal fade" id="edit_<?= $ann['gy_ann_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel"><i class="fa fa-edit"></i> Edit Announcement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data" action="update_announce?cd=<?= $ann['gy_ann_id']; ?>" onsubmit="return validateForm(this);">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control" name="type" autofocus required>
                                <option value="<?= $ann['gy_ann_type']; ?>"><?= $options; ?></option>
                                <option value="success">Simple</option>
                                <option value="warning">Non-critical</option>
                                <option value="danger">Critical</option>
                                <option value="info">Updates</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Post until (date)</label>
                            <input type="date" name="end_date" min="<?= $onlydate; ?>" value="<?= date("Y-m-d", strtotime($ann['gy_ann_end'])); ?>" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Caption</label>
                            <textarea name="caption" class="form-control" placeholder="type your caption here ..."><?= $captions; ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Attachment</label>
                          <input type="file" name="file" class="form-control" accept="image/jpeg,image/gif,image/png,image/x-eps" onchange="readURL_<?= $ann_id; ?>(this);">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group text-center">
                            <img src="<?= $ann['gy_ann_attachment']; ?>" style="width: 150px; height: 200px;" id="my-image_<?= $ann_id; ?>" onerror="this.onerror=null; this.src='../../images/icon/image.png'">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" name="add" id="submit" class="btn btn-info">Update Post</button>
            </div>
            </form>
        </div>
    </div>
</div>



<script type="text/javascript">
    function readURL_<?= $ann_id; ?>(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#my-image_<?= $ann_id; ?>')
                    .attr('src', e.target.result)
                    .width(150)
                    .height(200);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<div class="modal fade" id="delete_<?= $ann['gy_ann_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="smallModalLabel"><i class="fa fa-trash"></i> Delete Announcement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    Do you want to delete this announcement? <br>
                    <i style="color: red;"><?= wordlimit($ann['gy_ann_caption'], 30); ?></i>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a href="delete_announce?cd=<?= $ann['gy_ann_id']; ?>"><button type="button" class="btn btn-danger">Delete</button></a>
            </div>
            </form>
        </div>
    </div>
</div>