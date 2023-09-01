<div class="modal fade" id="show_<?= $avail['gy_leave_avail_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="smallmodalLabel"><i class="fa fa-thumbs-down"></i> Justification/Remarks</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="jumbotron">
                            <?= $avail['gy_leave_avail_justify']; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_<?= $avail['gy_leave_avail_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white" id="smallmodalLabel"><i class="fa fa-edit"></i> Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data" action="leave_plot_update?cd=<?= $avail['gy_leave_avail_id']; ?>" onsubmit="return validateForm(this);">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Account</label>
                            <select name="account" class="form-control" autofocus required>
                                <option value="<?= $avail['gy_acc_id']; ?>"><?= get_acc_name($avail['gy_acc_id']); ?></option>
                                <?php  
                                    //get accounts
                                    $getaccounts=$link->query("SELECT * From `gy_accounts` Order By `gy_acc_name` ASC");
                                    while ($accrow=$getaccounts->fetch_array()) {
                                ?>
                                <option value="<?= $accrow['gy_acc_id']; ?>"><?= $accrow['gy_acc_name']; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Slots</label>
                            <input type="number" class="form-control" name="plot_slot" value="<?= $avail['gy_leave_avail_plotted']; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Justification</label>
                            <textarea name="plot_justify" class="form-control" rows="3" placeholder="type your reason here ..." required><?= $avail['gy_leave_avail_justify']; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" id="submit" class="btn btn-info" title="click to update ...">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="delete_<?= $avail['gy_leave_avail_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="smallmodalLabel"><i class="fa fa-trash"></i> Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a href="leave_plot_delete?cd=<?= $avail['gy_leave_avail_id']; ?>"><button type="button" class="btn btn-danger" title="click to delete ...">Delete</button></a>
            </div>
        </div>
    </div>
</div>