<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

    if($user_type == 5 && $user_dept == 2){
    $sibsid = addslashes($_REQUEST['sibsid']);
    $psdsql=$link->query("SELECT * From `gy_employee` Where `gy_emp_code`='$sibsid' LIMIT 1");
    $psdrow=$psdsql->fetch_array();
    $psdarr[0]=$psdrow['gy_emp_code'];
    $psdarr[1]=$psdrow['gy_emp_lname'];
    $psdarr[2]=$psdrow['gy_emp_fname'];
    $psdarr[3]=$psdrow['gy_emp_mname'];
    $psdarr[4] = $psdrow['gy_dob'];
    $psdarr[5] = $psdrow['gy_civilstatus'];
    $psdarr[6] = $psdrow['gy_home_address'];
    $psdarr[7] = $psdrow['gy_gov_id'];
    $psdarr[8] = $psdrow['gy_gov_idnum'];
    $psdarr[9] = $psdrow['gy_personal_email'];
    $psdarr[10] = $psdrow['gy_contact_num'];
    $psdarr[11] = $psdrow['gy_ecperson'];
    $psdarr[12] = $psdrow['gy_ecnumber'];
    $psdarr[13] = $psdrow['gy_gender'];
    $psdarr[14] = $psdrow['gy_emp_hiredate'];
    $psdarr[15] = $psdrow['gy_acc_id'];
    $psdarr[17] = $psdrow['gy_emp_id'];
    $psdarr[18] = $psdrow['gy_emrg_address'];
    $psdarr[19] = $psdrow['gy_mail_address'];
    $psdarr[20] = $psdrow['gy_second_address'];
    $psdarr[21] = $psdrow['gy_assignedloc'];
    $psdarr[22] = $psdrow['gy_emp_om'];
    $psdarr[23] = $psdrow['gy_accjoin'];
    $psdarr[24] = $psdrow['gy_emp_lastedit'];
    $psdarr[25] = $psdrow['gy_lastedit_by'];

    $psdarr[26] = $psdrow['gy_nhodate'];
    $psdarr[27] = $psdrow['gy_fststartdate'];
    $psdarr[28] = $psdrow['gy_fstenddate'];
    $psdarr[29] = $psdrow['gy_pststartdate'];
    $psdarr[30] = $psdrow['gy_pstenddate'];
    $psdarr[31] = $psdrow['gy_certification'];
    $psdarr[32] = $psdrow['gy_gradbaystartdate'];
    $psdarr[33] = $psdrow['gy_gradbayenddate'];
    $psdarr[34] = $psdrow['gy_fullgolivedate'];
    $psdarr[35] = $psdrow['gy_promotiondate'];

    $psdarr[36] = $psdrow['gy_projempdate'];
    $psdarr[37] = $psdrow['gy_probempdate'];
    $psdarr[38] = $psdrow['gy_regempdate'];

    $psdarr[39] = $psdrow['gy_tagumdate'];
    $psdarr[40] = $psdrow['gy_davaodate'];
    $psdarr[41] = $psdrow['gy_hybriddate'];

        $depsql = $link->query("SELECT `gy_dept_id` From `gy_accounts` where `gy_acc_id`=".$psdarr[15]);
        $deprow=$depsql->fetch_array();
        $psdarr[16] = $deprow['gy_dept_id'];

    $i = 0; $accar = array(); $accarr = array();
    $accsql = $link->query("SELECT * From `gy_accounts` ORDER BY `gy_acc_name`");
    while ($accrow=$accsql->fetch_array()){
        $accar[$i] = $accrow['gy_acc_id'];
        $accarr[$i] = $accrow['gy_acc_name'];
        $i++;
    }

    $i = 0; $dptar = array(); $dptarr = array();
    $dptsql = $link->query("SELECT * From `gy_department` ORDER BY `name_department`");
    while ($dptrow=$dptsql->fetch_array()){
        $dptar[$i] = $dptrow['id_department'];
        $dptarr[$i] = $dptrow['name_department'];
        $i++;
    }

    $i = 0; $omar = array(); $omarr = array();
    $omsql = $link->query("SELECT `gy_emp_code`,`gy_emp_fullname` From `gy_employee` where `gy_emp_type`=3");
    while ($omrow=$omsql->fetch_array()){
        $omar[$i] = $omrow['gy_emp_code'];
        $omarr[$i] = $omrow['gy_emp_fullname'];
        $i++;
    }

    $usnmsql=$link->query("SELECT `gy_full_name` From `gy_user` Where `gy_user_code`='$psdarr[25]'");
    $usrrow=$usnmsql->fetch_array();
    $lsteby = $usrrow['gy_full_name'];
?>
<div class="row">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-header"><b>Profile Card</b></div>
            <div class="card-body">
                <div class="form-floating mb-1">
                    <input type="text" class="form-control" id="flname" name="flname" placeholder=" " value="<?php echo $psdarr[1]; ?>" required>
                    <label for="flname">Last Name</label>
                </div>
                <div class="form-floating mb-1">
                    <input type="text" class="form-control" id="ffname" name="ffname" placeholder=" " value="<?php echo $psdarr[2]; ?>" required>
                    <label for="ffname">First Name</label>
                </div>
                <div class="form-floating mb-1">
                    <input type="text" class="form-control" id="fmname" name="fmname" placeholder=" " value="<?php echo $psdarr[3]; ?>">
                    <label for="fmname">Middle Name</label>
                </div>
            </div>
        </div>

        <div class="card">
        <div class="card-header"><b>Personal Card</b></div>
            <div class="card-body">
                <div class="form-floating mb-1">
                    <select class="form-select" id="perg" name="perg">
                        <option value="Male" <?php if($psdarr[13]=="Male"){echo"selected";} ?>>Male</option>
                        <option value="Female" <?php if($psdarr[13]=="Female"){echo"selected";} ?>>Female</option>
                    </select>
                    <label for="perg">Gender</label>
                </div>
                <div class="form-floating mb-1">
                    <input type="date" class="form-control" id="perdob" name="perdob" value="<?php echo $psdarr[4];?>">
                    <label for="perdob">Date of Birth</label>
                </div>
                <div class="form-floating mb-1">
                    <select class="form-select" id="percs" name="percs">
                        <option value="Single" <?php if($psdarr[5]=="Single"){echo"selected";} ?>>Single</option>
                        <option value="Married" <?php if($psdarr[5]=="Married"){echo"selected";} ?>>Married</option>
                        <option value="Widowed" <?php if($psdarr[5]=="Widowed"){echo"selected";} ?>>Widowed</option>
                        <option value="Separated" <?php if($psdarr[5]=="Separated"){echo"selected";} ?>>Separated</option>
                    </select>
                    <label for="percs">Civil Status</label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="card">
        <div class="card-header"><b>Employee Card</b></div>
            <div class="card-body">
             <div class="row">
                <div class="col-md-2"><div class="form-floating mb-1">
                    <input type="text" class="form-control" id="empsi" name="empsi" placeholder=" " value="<?php echo $psdarr[0]; ?>" readonly>
                    <label for="empsi">SiBS ID</label>
                </div></div>
                <div class="col-md-3"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="empdh" name="empdh" placeholder=" " value="<?php echo $psdarr[14]; ?>">
                    <label for="empdh">Date Hired</label>
                </div></div>
                <div class="col-md-4"><div class="form-floating mb-1">
                    <select class="form-select" id="empsta" name="empsta" disabled>
                        <option value="" ></option>
                        <option value="0" <?php if($psdarr[36]!="0000-00-00" && date("Y-m-d", strtotime($psdarr[36]))<=date("Y-m-d")){echo"selected";} ?> >Project Employment</option>
                        <option value="1" <?php if($psdarr[37]!="0000-00-00" && date("Y-m-d", strtotime($psdarr[37]))<=date("Y-m-d")){echo"selected";} ?> >Probationary Employment</option>
                        <option value="2" <?php if($psdarr[38]!="0000-00-00" && date("Y-m-d", strtotime($psdarr[38]))<=date("Y-m-d")){echo"selected";} ?> >Regular Employment</option>
                    </select>
                    <label for="empsta">Employment Status</label>
                </div></div>
                <div class="col-md-3"><div class="form-floating mb-1">
                    <select class="form-select" id="asloc" name="asloc" disabled>
                        <option value="0" <?php if($psdarr[21]==0){echo"selected";} ?>>Tagum</option>
                        <option value="1" <?php if($psdarr[21]==1){echo"selected";} ?>>Davao</option>
                    </select>
                    <label for="asloc">Assigned Location</label>
                </div></div>
                <div class="col-md-4"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="proemp" name="proemp" placeholder=" " value="<?php echo $psdarr[36]; ?>">
                    <label for="proemp">Project Employment</label>
                </div></div>
                <div class="col-md-4"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="probemp" name="probemp" placeholder=" " value="<?php echo $psdarr[37]; ?>">
                    <label for="probemp">Probationary Employment</label>
                </div></div>
                <div class="col-md-4"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="regemp" name="regemp" placeholder=" " value="<?php echo $psdarr[38]; ?>">
                    <label for="regemp">Regular Employment</label>
                </div></div>

                <div class="col-md-4"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="tagdate" name="tagdate" placeholder=" " value="<?php echo $psdarr[39]; ?>">
                    <label for="tagdate">Tagum</label>
                </div></div>
                <div class="col-md-4"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="davdate" name="davdate" placeholder=" " value="<?php echo $psdarr[40]; ?>">
                    <label for="davdate">Davao</label>
                </div></div>
                <div class="col-md-4"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="hybdate" name="hybdate" placeholder=" " value="<?php echo $psdarr[41]; ?>">
                    <label for="hybdate">Hybrid</label>
                </div></div>
             </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><b>Training Card</b></div>
            <div class="card-body">
                <div class="row">
                <div class="col-md-3"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="tcnhod" name="tcnhod" placeholder=" " value="<?php echo $psdarr[26]; ?>">
                    <label for="tcnhod">NHO Date</label>
                </div></div>
                <div class="col-md-3"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="certdt" name="certdt" placeholder=" " value="<?php echo $psdarr[31]; ?>">
                    <label for="certdt">Certification Date</label>
                </div></div>
                <div class="col-md-3"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="fugold" name="fugold" placeholder=" " value="<?php echo $psdarr[34]; ?>">
                    <label for="fugold">Full Go Live Date</label>
                </div></div>
                <div class="col-md-3"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="promd" name="promd" placeholder=" " value="<?php echo $psdarr[35]; ?>">
                    <label for="promd">Promotion Date</label>
                </div></div>
                <div class="col-md-3"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="fstsd" name="fstsd" placeholder=" " value="<?php echo $psdarr[27]; ?>">
                    <label for="fstsd">FST Start Date</label>
                </div>
                <div class="form-floating mb-1">
                    <input type="date" class="form-control" id="fsted" name="fsted" placeholder=" " value="<?php echo $psdarr[28]; ?>">
                    <label for="fsted">FST End Date</label>
                </div></div>
                <div class="col-md-3"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="pstsd" name="pstsd" placeholder=" " value="<?php echo $psdarr[29]; ?>">
                    <label for="pstsd">PST Start Date</label>
                </div>
                <div class="form-floating mb-1">
                    <input type="date" class="form-control" id="psted" name="psted" placeholder=" " value="<?php echo $psdarr[30]; ?>">
                    <label for="psted">PST End Date</label>
                </div></div>
                <div class="col-md-3"><div class="form-floating mb-1">
                    <input type="date" class="form-control" id="grbasd" name="grbasd" placeholder=" " value="<?php echo $psdarr[32]; ?>">
                    <label for="grbasd">Grad bay Start Date</label>
                </div>
                <div class="form-floating mb-1">
                    <input type="date" class="form-control" id="grbaed" name="grbaed" placeholder=" " value="<?php echo $psdarr[33]; ?>">
                    <label for="grbaed">Grad bay End Date</label>
                </div></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
        <div class="card-header"><b>Contact Card</b></div>
        <div class="card-body">
            <div class="form-floating mb-1">
                <input type="text" class="form-control" id="ctctha" name="ctctha" placeholder=" " value="<?php echo $psdarr[6];?>">
                <label for="ctctha">Home Address</label>
            </div>
            <div class="form-floating mb-1">
                <input type="text" class="form-control" id="ctctea" name="ctctea" placeholder=" " value="<?php echo$psdarr[18];?>">
                <label for="ctctha">Emergency Address</label>
            </div>
            <div class="form-floating mb-1">
                <input type="text" class="form-control" id="ctctma" name="ctctma" placeholder=" " value="<?php echo$psdarr[19];?>">
                <label for="ctctha">Mailing Address</label>
            </div>
            <div class="form-floating mb-1">
                <input type="text" class="form-control" id="ctctsa" name="ctctsa" placeholder=" " value="<?php echo$psdarr[20];?>">
                <label for="ctctha">Secondary Address</label>
            </div>
            <div class="row">
                <div class="col-md-6"><div class="form-floating mb-1">
                    <input type="text" class="form-control" id="ctctgid" name="ctctgid" placeholder=" " value="<?php echo $psdarr[7];?>">
                    <label for="ctctgid">Government ID</label>
                </div></div>
                <div class="col-md-6"><div class="form-floating mb-1">
                    <input type="text" class="form-control" id="ctctgidn" name="ctctgidn" placeholder=" " value="<?php echo $psdarr[8];?>">
                    <label for="ctctgidn">Government ID #</label>
                </div></div>
            </div>
            <div class="row">
                <div class="col-md-6"><div class="form-floating mb-1">
                    <input type="text" class="form-control" id="ctctpe" name="ctctpe" placeholder=" " value="<?php echo $psdarr[9]; ?>">
                    <label for="ctctpe">Personal Email</label>
                </div></div>
                <div class="col-md-6"><div class="form-floating mb-1">
                    <input type="text" class="form-control" id="ctctcn" name="ctctcn" placeholder=" " value="<?php echo $psdarr[10]; ?>">
                    <label for="ctctcn">Contact Number</label>
                </div></div>
            </div>
            <div class="row">
                <div class="col-md-6"><div class="form-floating mb-1">
                    <input type="text" class="form-control" id="ctctecp" name="ctctecp" placeholder=" " value="<?php echo $psdarr[11]; ?>">
                    <label for="ctctecp">Emergency Contact Person</label>
                </div></div>
                <div class="col-md-6"><div class="form-floating mb-1">
                    <input type="text" class="form-control" id="ctctecn" name="ctctecn" placeholder=" " value="<?php echo $psdarr[12]; ?>">
                    <label for="ctctecn">Emergency Contact Number</label>
                </div></div>
            </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
        <div class="card-header"><b>Project Card</b></div>
        <div class="card-body">
            <div class="form-floating mb-3">
                <input type="date" class="form-control" id="proajd" name="proajd" placeholder="Account Join Date" value="<?php echo $psdarr[23]; ?>">
                <label for="proajd">Account Join Date</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="prodep" onchange="slctaccnt(this)">
                    <option></option>
                <?php for($i=0;$i<count($dptar);$i++){ ?>
                    <option value="<?php echo $dptar[$i]; ?>" <?php if($dptar[$i]==$psdarr[16]){echo"selected";} ?>><?php echo $dptarr[$i]; ?></option>
                <?php } ?>
                </select>
                <label for="prodep">Department</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="proacc" name="proacc">
                <?php for($i=0;$i<count($accar);$i++){ ?>
                    <option value="<?php echo $accar[$i]; ?>" <?php if($accar[$i]==$psdarr[15]){echo"selected";} ?>><?php echo $accarr[$i]; ?></option>
                <?php } ?>
                </select>
                <label for="proacc">Account</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="promng" name="promng">
                    <option value=""></option>
                    <?php for($i=0;$i<count($omar);$i++){ ?>
                    <option value="<?php echo $omar[$i]; ?>" <?php if($omar[$i]==$psdarr[22]){echo"selected";} ?>><?php echo $omarr[$i]; ?></option>
                    <?php } ?>
                </select>
                <label for="promng">Manager</label>
            </div>
        </div>
        </div>

        <div class="card">
          <ul class="list-group list-group-flush text-left">
            <li class="list-group-item">Last Update: <span class="fst-italic"><?php if($psdarr[24]!="0000-00-00 00:00:00"){echo date("F j, Y h:i a", strtotime($psdarr[24])); } ?></span></li>
            <li class="list-group-item">Edited By: <span class="fst-italic"><?php echo $lsteby; ?></span></li>
          </ul>
        </div>
    </div>

    <input type="hidden" id="id_gy" value="<?php echo $psdarr[17]; ?>">
</div>
<?php    } $link->close(); ?>