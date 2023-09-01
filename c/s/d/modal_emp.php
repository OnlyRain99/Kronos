<div class="modal fade" id="edit_<?php echo $inforow['gy_emp_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel"><i class="fa fa-edit"></i> Edit <span style="color: blue;"><?php echo $inforow['gy_emp_fullname']; ?></span> Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data" action="update_emp?cd=<?php echo $inforow['gy_emp_id']; ?>" onsubmit="return validateForm(this);">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>ID</label>
                            <input type="text" class="form-control" name="idcode" maxlength="4" placeholder="Ex. 0000" value="<?php echo $inforow['gy_emp_code']; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" maxlength="255" placeholder="sample@gmail.com" value="<?php echo $inforow['gy_emp_email']; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Fullname (Lastname, Firstname Middlename)</label>
                            <input type="text" class="form-control" name="fullname" maxlength="255" value="<?php echo $inforow['gy_emp_fullname']; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Account</label>
                            <select name="account" class="form-control" required>
                                <option><?php echo $inforow['gy_emp_account']; ?></option>
                                <option>BayShore Dental Studio</option>
                                <option>Coast Dental - Claims</option>
                                <option>Coast Dental - Coast Connect</option>
                                <option>Coast Dental - Collections</option>
                                <option>Coast Dental - DentistRX</option>
                                <option>Finance Department</option>
                                <option>FST</option>
                                <option>Graphyte</option>
                                <option>Guhilot</option>
                                <option>HR Department</option>
                                <option>IT Department</option>
                                <option>Marketlend</option>
                                <option>Quality Management</option>
                                <option>Sales</option>
                                <option>SME</option>
                                <option>Sun Dental Lab</option>
                                <option>Training Department</option>
                                <option>US Coachways</option>
                                <option>US Visa</option>
                                <option>Utility / Security</option>
                                <option>VidaXL</option>
                                <option>WFM</option>
                                <option>Yomdel - Davao</option>
                                <option>Yomdel - Tagum</option>
                                <option>Others</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" name="add" id="submit" class="btn btn-primary">Confirm</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="delete_<?php echo $inforow['gy_emp_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticModalLabel"><i class="fa fa-trash"></i> Delete <span style="color: blue;"><?php echo $inforow['gy_emp_fullname']; ?></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    Do you want to delete this employee on the list?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a href="delete_emp?cd=<?php echo $inforow['gy_emp_id']; ?>"><button type="button" class="btn btn-danger">Confirm</button></a>
            </div>
        </div>
    </div>
</div>