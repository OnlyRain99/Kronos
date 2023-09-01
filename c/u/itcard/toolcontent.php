<?php 
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../session.php';

$id = addslashes($_REQUEST['id']);
$sval = addslashes($_REQUEST['sval']);
$sel_col = addslashes($_REQUEST['sel_col']);
$ordr = addslashes($_REQUEST['ordr']);
if($id>0&&$id!=""){
    $teamarr=array();
    $tmsql = $link->query("SELECT * FROM `team_toollist` WHERE `team_id`='$id' LIMIT 1");
    $tmrow=$tmsql->fetch_array();
        $teamarr[0] = $tmrow['team_id'];
        $teamarr[1] = $tmrow['team_name'];
        $teamarr[2] = $tmrow['team_owner'];
        $teamarr[3] = $tmrow['team_switch'];
}

if($teamarr[2]==$user_code || $teamarr[3]==1){
$func = addslashes($_REQUEST['func']);
if($func==3){
    $delteam = addslashes($_REQUEST['delteam']);
    $delrow = addslashes($_REQUEST['delrow']);
    $link->query("DELETE FROM `team_data` WHERE `tool_id`='$delteam' AND `row_id`='$delrow'");
}

$row3=0; $rowarr = array(array());
//$itmsql = $link->query("SELECT `row_id` FROM `team_data` WHERE `tool_id`=$teamarr[0] ORDER BY `row_id` asc");
//
//if($itmsql->num_rows>0){
//while($itmrow=$itmsql->fetch_array()){
//    if(!in_array($itmrow['row_id'], $rowarr)){ $rowarr[$row3]=$itmrow['row_id']; $row3++; }
//} asort($rowarr); $row3=max($rowarr);
//}

    $srchsql = "";
    //if($func==2 && $sval!=""){ $srchsql=" AND `data_value`='$sval' "; }
    $row2=0; $datarr = array(array()); $row4=0; $srcarr = array();
    $datsql = $link->query("SELECT * FROM `team_data` WHERE `tool_id`=$teamarr[0] ".$srchsql." ORDER BY `tool_id` asc, `col_id` asc, `row_id` asc");
if($datsql->num_rows>0){
    while($datrow=$datsql->fetch_array()){
        $good=0;
        if($func==2 && strtolower($datrow['data_value'])==strtolower($sval)){
            $srcarr[$row4]=$datrow['row_id']; $row4++; $good=1;
        }else if($func==2 && in_array($datrow['row_id'], $srcarr)){
             $good=1;
        }
        if($func!=2 || $good==1){
        $datarr[$row2][0] = $datrow['data_id'];
        $datarr[$row2][1] = $datrow['col_id'];
        $datarr[$row2][2] = $datrow['row_id'];
        $datarr[$row2][3] = $datrow['data_value'];
        $row2++;

//        if(!in_array($datrow['row_id'], $rowarr)){ $rowarr[$row3]=$datrow['row_id']; $row3++; }
         if(!in_array($datrow['row_id'], array_column($rowarr, 0))){
            if($sel_col=="" || $datrow['col_id']==$sel_col){
             $rowarr[$row3][0]=$datrow['row_id'];
             $rowarr[$row3][1]=$datrow['data_value'];
             $row3++;
            }
         }
        }
    } if(count(array_column($rowarr, 0))>0){ $row3=max(array_column($rowarr,0)); } //asort(array_column($rowarr,1));
}
if($func==1){ $row3++; $rowarr[count(array_column($rowarr,0))][0]=$row3; }
if($sel_col!=""){ $columns = array_column($rowarr, 1); }
else{ $columns = array_column($rowarr, 0); }
if(count(array_column($rowarr, 0))>0){
    if($ordr=="SORT_ASC"){ array_multisort($columns, SORT_ASC, $rowarr); }
    else if($ordr=="SORT_DESC"){ array_multisort($columns, SORT_DESC, $rowarr); }
}

    $row1=0; $colarr = array(array());
    $colsql = $link->query("SELECT * FROM `team_collist` WHERE `col_status`=1 AND `team_id`=$teamarr[0] ORDER BY `col_order` asc");
    while($colrow=$colsql->fetch_array()){
        $colarr[$row1][0] = $colrow['col_id'];
        $colarr[$row1][1] = $colrow['col_val'];
        $colarr[$row1][2] = $colrow['col_type'];
        if($func==1 && $row1==0){
            $link->query("INSERT INTO `team_data`(`tool_id`,`col_id`,`row_id`)VALUES($teamarr[0], ".$colarr[$row1][0].", $row3)");
        }
        $row1++;
    }
?>
    <div class="card-header">
        <div class="input-group">
            <a class="btn btn-outline-secondary btn-sm" title="Return" onclick="location.reload();"><i class="fa-solid fa-person-walking-arrow-loop-left"></i> Go Back</a>
            <input type="hidden" id="teamtoolid" value="<?php echo $teamarr[0]; ?>">
            <input type="text" style="width:25%" placeholder="Tool Name" oninput="this.style.color='red'; showelem('updtlnm');" id="toolnmid" class="form-control-sm form-bline text-center" value="<?php echo $teamarr[1]; ?>" required>
            <button class="btn btn-secondary btn-sm" style="display: none;" id="updtlnm" onclick="updtlname(this, 'toolnmid')" title="Save"><i class="fa-solid fa-pen-to-square"></i> Update</button>
            <button class="btn btn-outline-secondary btn-sm" title="Manage Columns" onclick="managecol(<?php echo $teamarr[0]; ?>)"><i class="fa fa-columns"></i> Manage Columns</button>
            <input type="text" placeholder="Search Word" id="srchwrd" autofocus class="form-control-sm form-bline text-center" onkeypress="srchname(this)" value="<?php echo $sval; ?>" required>
            <button class="btn btn-outline-secondary btn-sm" title="Search" onclick="clcksrch()"><i class="fa-solid fa-magnifying-glass"></i></button>
            <select class="form-select-sm form-bline" id="sel_col" onchange="clcksrch()">
                <option value="">Default</option>
                <?php for($i=0;$i<$row1;$i++){ ?>
                <option value="<?php echo $colarr[$i][0]; ?>" <?php if($sel_col==$colarr[$i][0]){echo"selected";} ?> ><?php echo $colarr[$i][1]; ?></option>
                <?php } ?>
            </select>
            <?php if($ordr=="SORT_ASC"){ ?>
                <button class="btn btn-outline-secondary btn-sm" title="Sort in ascending order (A-Z)" onclick="changesort('SORT_DESC')"><i class="fa-solid fa-arrow-down-a-z"></i></button>
                <input type="hidden" id="srtval" value="SORT_ASC">
            <?php }else if($ordr=="SORT_DESC"){ ?>
                <button class="btn btn-outline-secondary btn-sm" title="Sort in descending order (Z-A)" onclick="changesort('SORT_ASC')"><i class="fa-solid fa-arrow-down-z-a"></i></button>
                <input type="hidden" id="srtval" value="SORT_DESC">
            <?php } ?>
        </div>
    </div>
    <div class="">
        <div class="table-responsive teamtable" style="max-height: 450px; overflow: auto;" id="thetblsize">
            <table class="table table table-bordered" style="font-family: 'Calibri'; font-size: 14px;"  id="credsbody">
                <thead class="text-center text-nowrap bg-secondary text-white">
                <tr>
                    <th style="padding:0px; width: 10px;"><btn class="btn btn-outline-secondary btn-sm btn-block" onclick="opentool(<?php echo $id; ?>, 1)"><i class="fa-solid fa-plus"></i></btn></th>
                <?php for($i=0;$i<$row1;$i++){ ?>
                    <th scope="col" style="padding:3px;" class="text-capitalize"><?php echo $colarr[$i][1]; ?></th>
                <?php } ?>
                </tr>
                </thead>
                <tbody>
            <?php for($i1=0;$i1<count(array_column($rowarr,0));$i1++){ ?>
                <tr class="text-nowrap text-center" id="tblrow_<?php echo$teamarr[0]."-".$rowarr[$i1][0]; ?>">
                    <td style="padding: 0px;" onmouseover='showelem("delbtn_<?php echo$teamarr[0]."-".$rowarr[$i1][0]; ?>")' onmouseout='hideelem("delbtn_<?php echo$teamarr[0]."-".$rowarr[$i1][0]; ?>")'>
                            <button title="Delete Item" style="display: none;" id="delbtn_<?php echo$teamarr[0]."-".$rowarr[$i1][0]; ?>" onclick="deleteitem('<?php echo$teamarr[0]."-".$rowarr[$i1][0]; ?>')"><i class="fa-solid fa-delete-left"></i></button>
                    </td>
                <?php for($i=0;$i<$row1;$i++){
                    //$tmpcol=""; $tmprow="";
                    $tmpval="";
                    for($i2=0;$i2<$row2;$i2++){
                        if(isset($datarr[$i2][0])){
                        if($datarr[$i2][1]==$colarr[$i][0]&&$datarr[$i2][2]==$rowarr[$i1][0]){
                            //$tmprow=$i2;
                            $tmpval=$datarr[$i2][3];
                            unset($datarr[$i2][0]);
                            unset($datarr[$i2][1]);
                            unset($datarr[$i2][2]);
                            unset($datarr[$i2][3]);
                            break;
                        }}
                    }
                    ?>
                    <td style="padding: 0px;" onmouseover='showelem("ico_<?php echo$teamarr[0]."-".$colarr[$i][0]."-".$rowarr[$i1][0];?>"); showelem("vwpsw_<?php echo$teamarr[0]."-".$colarr[$i][0]."-".$rowarr[$i1][0];?>"); showelem("cpyval_<?php echo$teamarr[0]."-".$colarr[$i][0]."-".$rowarr[$i1][0];?>");' onmouseout='hideelem("ico_<?php echo$teamarr[0]."-".$colarr[$i][0]."-".$rowarr[$i1][0];?>"); hideelem("vwpsw_<?php echo$teamarr[0]."-".$colarr[$i][0]."-".$rowarr[$i1][0];?>"); hideelem("cpyval_<?php echo$teamarr[0]."-".$colarr[$i][0]."-".$rowarr[$i1][0];?>");'>
                        <span id="teanspn_<?php echo$teamarr[0]."-".$colarr[$i][0]."-".$rowarr[$i1][0];?>" >
                            <span id="lblsp_<?php echo$teamarr[0]."-".$colarr[$i][0]."-".$rowarr[$i1][0];?>"><?php if($colarr[$i][2]=="password"&&$tmpval!=""){echo"********";}else{echo $tmpval;} ?></span>
                            <button onclick='showelem("teamspn_<?php echo$teamarr[0]."-".$colarr[$i][0]."-".$rowarr[$i1][0];?>"); hideelem("teanspn_<?php echo$teamarr[0]."-".$colarr[$i][0]."-".$rowarr[$i1][0];?>");'><i class="fa-solid fa-pen" title="Edit" style="display: none;" id="ico_<?php echo$teamarr[0]."-".$colarr[$i][0]."-".$rowarr[$i1][0];?>"></i></button>
                        </span>
                        <span id="teamspn_<?php echo$teamarr[0]."-".$colarr[$i][0]."-".$rowarr[$i1][0];?>" style="display: none;">
                            <input type="<?php echo $colarr[$i][2]; ?>" onfocusin='cleartimer()' onfocusout='tmrtrig()' id="taaminp_<?php echo$teamarr[0]."-".$colarr[$i][0]."-".$rowarr[$i1][0];?>" class="text-center" value="<?php echo $tmpval; ?>" onkeypress="kpresssv(this)">
                            <button title="Save" id="uptcllbtn_<?php echo$teamarr[0]."-".$colarr[$i][0]."-".$rowarr[$i1][0];?>" onclick="uptteamdata('<?php echo$teamarr[0]."-".$colarr[$i][0]."-".$rowarr[$i1][0];?>', '<?php echo$colarr[$i][2];?>')"><i class="fa-solid fa-pen-to-square"></i></button>
                        </span>
                            <?php if($tmpval!=""){ if($colarr[$i][2]=="password"){ ?>
                                <button title="Show Password" style="display: none;" id="vwpsw_<?php echo$teamarr[0]."-".$colarr[$i][0]."-".$rowarr[$i1][0];?>" onclick="eyesh(this)"><i class="fa-solid fa-eye"></i></button>
                            <?php } ?>
                                <button id="cpyval_<?php echo$teamarr[0]."-".$colarr[$i][0]."-".$rowarr[$i1][0];?>" style="display: none;" title="copy" onclick="cpytocb(this)"><i class="fa-solid fa-copy"></i></button>
                            <?php } ?>
                    </td>
                <?php } ?>
                </tr>
            <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer text-muted"><?php echo count(array_column($rowarr,0))." row"; if(count($row3)>1){echo"s";} echo" | ".$row1." column"; if($row1>1){echo"s";}  ?></div>
<?php } $link->close(); ?>
