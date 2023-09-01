<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white" id="mediumModalLabel"><i class="fa fa-plus"></i> Add New Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data" action="add_emp" onsubmit="return validateForm(this);">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>SiBS ID <span style="font-size: 12px;" id="duplicate_alert"></span></label>
                            <input type="text" class="form-control" name="idcode" id="idcode" maxlength="5" placeholder="Ex. 0000" autofocus required>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" maxlength="255" placeholder="sample@gmail.com" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>FirstName</label>
                            <input type="text" class="form-control" name="fname" maxlength="255" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>LastName</label>
                            <input type="text" class="form-control" name="lname" maxlength="255" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>MiddleName</label>
                            <input type="text" class="form-control" name="mname" maxlength="255" >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Account</label>
                            <select name="account" id="emp_account" class="form-control" required>
                                <option></option>
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
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Level</label>
                            <select name="type" id="emp_type" class="form-control" required>
                                <option></option>
                                <option value="1">L1</option>
                                <option value="2">L2</option>
                                <option value="3">L3</option>
                                <option value="4">L4</option>
                                <option value="5">L5</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Position Type</label>
                            <select name="function_type" id="emp_function_type" class="form-control" required>
                                <option value="0">default</option>
                                <option value="1">Scheduler</option>
                                <option value="2">CompBen</option>
                                <option value="3">IT/ETC</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Supervisor/<span style="color: red;">Admin</span></label>
                            <select name="mysup" class="form-control">
                                <option></option>
                            <?php  
                                //get supervisors
                                $getsup=$link->query("SELECT `gy_user_id`,`gy_full_name`,`gy_user_type` From `gy_user` Where `gy_user_id`!='1' AND `gy_user_type`='0' OR `gy_user_type`='2' OR `gy_user_type`='3' OR `gy_user_type`='4' OR `gy_user_type`='5'");
                                while ($suprow=$getsup->fetch_array()) {

                                    if ($suprow['gy_user_type'] == 0) {
                                        $optioncolor = "red";
                                    }else if ($suprow['gy_user_type'] == 2) {
                                        $optioncolor = "green";
                                    }else if ($suprow['gy_user_type'] == 3) {
                                        $optioncolor = "blue";
                                    }else if ($suprow['gy_user_type'] == 4) {
                                        $optioncolor = "#217777";
                                    }else if ($suprow['gy_user_type'] == 5) {
                                        $optioncolor = "#000";
                                    }else{
                                        $optioncolor = "#495057";
                                    }
                            ?>
                                <option style="color: <?php echo $optioncolor; ?>;" value="<?php echo $suprow['gy_user_id']; ?>"><?php echo $suprow['gy_full_name']; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <p style="text-align: center; font-style: italic; color: red;">- Employee's user account will be sent on his/her registered email -</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" name="add" id="submit" class="btn btn-success">Confirm</button>
            </div>
            </form>
        </div>
    </div>
</div>