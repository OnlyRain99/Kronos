<?php
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include 'session.php';
    if($myaccount == 22){
	include '../../../config/connnk.php';
?>
<div class="input-group">
  <div class="input-group-prepend">
        <select class="form-control minwid-80" id="targetskill">
                <?php $skillarr = array(1,1.1,1.2,2,3); for($i=0;$i<count($skillarr);$i++){ ?>
                <option value="<?php echo $skillarr[$i]; ?>"><?php echo "Skill ".$skillarr[$i]; ?></option>
            <?php } ?>
        </select>
    <span class="input-group-text">Tenure</span>
    <select class="form-select form-select-sm" id="targerope" onchange="setintlimit(this)">
        <option value="=">=</option>
        <option value=">">></option>
        <option value="<"><</option>
        <option value=">=">≥</option>
        <option value="<=">≤</option>
    </select>
  </div>
    <input type="number" class="form-control" min="0" max="100" id="frommonth" onkeyup="setintlimit(this)" placeholder="Month 1">
    <span class="input-group-text">≤</span>
    <input type="number" class="form-control" min="0" max="100" id="tomonth" onkeyup="setintlimit(this)"  placeholder="Month 2" disabled>
    <span class="input-group-text">Target : </span>
    <input type="number" class="form-control" min="0" max="999999" id="targetval" onkeyup="setintlimit(this)"  placeholder="Hourly" disabled>
  <button class="btn btn-outline-secondary" id="subtarget" onclick="addtolist(this)" disabled>APPLY</button>
</div>
<div id="updatetarget"></div>
<div class="table-responsive">
    <table class="table table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
        <thead>
            <tr>
            <th scope="col" style="padding: 5px;" class="text-center">Skill</th>
            <th scope="col" style="padding: 5px;" class="text-center">       </th>
            <th scope="col" style="padding: 5px;" class="text-center">Month</th>
            <th scope="col" style="padding: 5px;" class="text-center">≤</th>
            <th scope="col" style="padding: 5px;" class="text-center">Month</th>
            <th scope="col" style="padding: 5px;" class="text-center">Daily Email</th>
            <th scope="col" style="padding: 5px;" class="text-center">Hourly Email</th>
            <th scope="col" style="padding: 5px;" class="text-center">Daily Target</th>
            <th scope="col" style="padding: 5px;" class="text-center">Hourly Target</th>
            <th scope="col" style="padding: 5px;" class="text-center"><i class="fa fa-trash"></i></th>
            </tr>
        </thead>
        <tbody>
        <?php $trgtlist=$dbticket->query("SELECT * From `targets` ORDER BY `Skill` ASC, `month_first` ASC, `month_last` ASC");
            while ($trgtrow=$trgtlist->fetch_array()){ ?>
            <tr>
            <td style="padding: 0px;">
                <select class="form-control form-control-sm minwid-80" id="<?php echo "skill_".$trgtrow['id']; ?>" onchange="updtargettbl(this)">
                <?php $skillarr = array(1,1.1,1.2,2,3); for($i=0;$i<count($skillarr);$i++){ ?>
                    <option value="<?php echo $skillarr[$i]; ?>" <?php if($trgtrow['skill']==$skillarr[$i]){ echo "selected"; } ?>><?php echo "Skill ".$skillarr[$i]; ?></option>
                <?php } ?>
                </select>
                <div id="<?php echo "tblskill_".$trgtrow['id']; ?>"></div>
            </td>
            <td style="padding: 0px;"><select class="form-control form-control-sm" id="<?php echo "operator_".$trgtrow['id']; ?>" onchange="updtargettbl(this)">
                    <option value="=" <?php if($trgtrow['operator']=="="){echo "selected";} ?>>=</option>
                    <option value=">" <?php if($trgtrow['operator']==">"){echo "selected";} ?>>></option>
                    <option value="<" <?php if($trgtrow['operator']=="<"){echo "selected";} ?>><</option>
                    <option value=">=" <?php if($trgtrow['operator']==">="){echo "selected";} ?>>≥</option>
                    <option value="<=" <?php if($trgtrow['operator']=="<="){echo "selected";} ?>>≤</option>
                </select>
                <div id="<?php echo "tblope_".$trgtrow['id']; ?>"></div>
            </td>
            <td style="padding: 0px;"><input type="number" class="form-control form-control-sm" min="0" max="100" id="<?php echo "month1_".$trgtrow['id']; ?>" value="<?php echo $trgtrow['month_first']; ?>" oninput="setintlimit(this)" onfocusout="updtargettbl(this)"><div id="<?php echo "tblmth1_".$trgtrow['id']; ?>"></div></td>
            <td style="padding: 5px;" class="text-center">≤</td>
            <td style="padding: 0px;"><input type="number" class="form-control form-control-sm" min="0" max="100" id="<?php echo "month2_".$trgtrow['id']; ?>" oninput="setintlimit(this)" onfocusout="updtargettbl(this)" value="<?php if($trgtrow['month_last']!=0){echo $trgtrow['month_last']; } ?>" <?php if($trgtrow['operator']!=">" && $trgtrow['operator']!=">="){echo "disabled";} ?>><div id="<?php echo "tblmth2_".$trgtrow['id']; ?>"></div></td>
            <td style="padding: 5px;" class="text-center" id="<?php echo "demail_".$trgtrow['id']; ?>"><?php echo $trgtrow['hourly_target']*8; ?></td>
            <td style="padding: 0px;"><input type="number" class="form-control form-control-sm" min="0" max="999999" id="<?php echo "target_".$trgtrow['id']; ?>" value="<?php echo $trgtrow['hourly_target']; ?>" oninput="setintlimit(this)" onfocusout="updtargettbl(this)"><div id="<?php echo "tbltarget_".$trgtrow['id']; ?>"></div></td>
            <td style="padding: 5px;" class="text-center" id="<?php echo "dtarger_".$trgtrow['id']; ?>"><?php echo round($trgtrow['hourly_target']*6.375); ?></td>
            <td style="padding: 5px;" class="text-center" id="<?php echo "htarger_".$trgtrow['id']; ?>"><?php echo ceil(round($trgtrow['hourly_target']*6.375)/8);?></td>
            <td style="padding: 0px;"><button class="btn btn-outline-danger btn-block btn-sm" onclick="upditems(this, 1)" name="targetbtn" id="<?php echo "tardel_".$trgtrow['id']; ?>"><i class="fa fa-trash"></i></button></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<?php $dbticket->close(); } $link->close(); ?>