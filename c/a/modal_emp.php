<div class="modal fade" id="delete_<?php echo $inforow['gy_emp_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="staticModalLabel"><i class="fa fa-trash"></i> Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    <span style="color: blue; font-weight: bold;"><?php echo $inforow['gy_emp_fullname']; ?></span><br>
                    Do you want to delete this employee on the list?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a href="<?= $delete_link; ?>"><button type="button" class="btn btn-danger">Confirm</button></a>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="show_<?php echo $inforow['gy_emp_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="mediumModalLabel"><i class="fa fa-lock"></i> <?php echo $inforow['gy_emp_fullname']; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <?php  
                            //get emp acc
                            $unq_code=words($inforow['gy_emp_code']);
                            $getempuser=$link->query("SELECT `gy_username`,`gy_password` From `gy_user` Where `gy_user_code`='$unq_code'");
                            $empuser=$getempuser->fetch_array();
                            $empusercount=$getempuser->num_rows;

                            if ($empusercount > 0) {
                                $empusername = $empuser['gy_username'];
                                $emppassword = decryptIt($empuser['gy_password']);
                                //$emppassword = "none";
                            }else{
                                $empusername = "none";
                                $emppassword = "none";
                            }
                        ?>
                        <p class="text-center" style="font-family: 'Courier';">
                            Username <br> <span style="color: blue;"><?php echo $empusername; ?></span> <br>
                            Password <br> <span style="color: blue;"><?php echo $emppassword; ?></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    //make

</script>