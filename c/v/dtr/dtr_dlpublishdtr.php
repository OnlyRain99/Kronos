<?php
include '../../../config/conn.php';
include '../../../config/function.php';
include '../session.php';

$fid = @$_GET['fid'];
$filename=""; $excelData="";
$pbssql=$link->query("SELECT `dtr_publish`.`gy_emp_code`as`dtrcode`,`dtr_publish`.`dtr_year`as`year`,`dtr_publish`.`dtr_month`as`month`,`dtr_publish`.`dtr_cutoff`as`cutoff`,`dtr_publish`.`dtr_mdrate`as`dtrmdrate`,`dtr_publish`.`dtr_cmpute`as`dtrcmpute`,`gy_employee`.`gy_emp_fullname`as`dtrname`,`dtr_noofhours`,`dtr_lateut`,`dtr_absences`,`dtr_regot`,`dtr_rdreg`,`dtr_rdreg`,`dtr_rdot`,`dtr_shreg`,`dtr_shot`,`dtr_shrdreg`,`dtr_shrdot`,`dtr_lhreg`,`dtr_lhot`,`dtr_lhrdreg`,`dtr_lhrdot`,`dtr_ndreg`,`dtr_ndregot`,`dtr_ndrdreg`,`dtr_ndrdot`,`dtr_ndsh`,`dtr_ndshot`,`dtr_ndshrd`,`dtr_ndshrdot`,`dtr_ndlh`,`dtr_ndlhot`,`dtr_ndlhrd`,`dtr_ndlhrdot` From `dtr_publish` LEFT JOIN `gy_employee` ON `dtr_publish`.`gy_emp_code`=`gy_employee`.`gy_emp_code` Where `dtr_publish`.`dtr_publish_id`=$fid limit 1");
$pbsrow=$pbssql->fetch_array();
if($pbssql->num_rows>0){
$htrw = 1; $htarr = array(); $hlarr = array(); $pblshrow=array();
$holtsql=$link->query("SELECT `gy_hol_type_id`,`gy_hol_abbrv` FROM `gy_holiday_types` WHERE `gy_hol_status`=1 ORDER BY `gy_hol_type_id` desc");
while ($holtrow=$holtsql->fetch_array()){
    $hlarr[$htrw] = $holtrow['gy_hol_type_id'];
    $htarr[$htrw] = strtoupper($holtrow['gy_hol_abbrv']);
    $htrw++;
}

    $sibsid=$pbsrow['dtrcode'];
    $year=$pbsrow['year'];
    $month=$pbsrow['month'];
    $cutoff=$pbsrow['cutoff'];
    $emprate=$pbsrow['dtrmdrate'];
    $dtrcmpute=$pbsrow['dtrcmpute'];

    $dtrname=$pbsrow['dtrname'];

    $pblshrow[0]='SIB-'.$sibsid;
    $pblshrow[1]=$dtrname;
    $pblshrow[2]='';
    $pblshrow[3]='';
    $pblshrow[4]='';
    $pblshrow[5]='';
    $pblshrow[6]=$pbsrow['dtr_noofhours'];
    $pblshrow[7]=$pbsrow['dtr_lateut'];
    $pblshrow[8]=$pbsrow['dtr_absences'];
    $pblshrow[9]=$pbsrow['dtr_regot'];
    $pblshrow[10]=$pbsrow['dtr_rdreg'];
    $pblshrow[11]=$pbsrow['dtr_rdot'];
    $pblshrow[12]=$pbsrow['dtr_shreg'];
    $pblshrow[13]=$pbsrow['dtr_shot'];
    $pblshrow[14]=$pbsrow['dtr_shrdreg'];
    $pblshrow[15]=$pbsrow['dtr_shrdot'];
    $pblshrow[16]=$pbsrow['dtr_lhreg'];
    $pblshrow[17]=$pbsrow['dtr_lhot'];
    $pblshrow[18]=$pbsrow['dtr_lhrdreg'];
    $pblshrow[19]=$pbsrow['dtr_lhrdot'];
    $pblshrow[20]=$pbsrow['dtr_ndreg'];
    $pblshrow[21]=$pbsrow['dtr_ndregot'];
    $pblshrow[22]=$pbsrow['dtr_ndrdreg'];
    $pblshrow[23]=$pbsrow['dtr_ndrdot'];
    $pblshrow[24]=$pbsrow['dtr_ndsh'];
    $pblshrow[25]=$pbsrow['dtr_ndshot'];
    $pblshrow[26]=$pbsrow['dtr_ndshrd'];
    $pblshrow[27]=$pbsrow['dtr_ndshrdot'];
    $pblshrow[28]=$pbsrow['dtr_ndlh'];
    $pblshrow[29]=$pbsrow['dtr_ndlhot'];
    $pblshrow[30]=$pbsrow['dtr_ndlhrd'];
    $pblshrow[31]=$pbsrow['dtr_ndlhrdot'];

    $filename="DTR_".$sibsid.$year.$month.$cutoff.$emprate.$dtrcmpute.".xls";
    $i2=0;
    $fields = array();
    $fields[$i2]='SIB-'.$sibsid; $i2++;
    $fields[$i2]=$dtrname; $i2++;
    $fields[$i2]='Date'; $i2++;
    $fields[$i2]='Shift'; $i2++;
    $fields[$i2]='Log In'; $i2++;
    $fields[$i2]='Log Out'; $i2++;
    $fields[$i2]='No Of Hours'; $i2++;
    $fields[$i2]='Late|UT'; $i2++;
    $fields[$i2]='Absences'; $i2++;
    $fields[$i2]='Reg|OT'; $i2++;
    $fields[$i2]='RD|Reg'; $i2++;
    $fields[$i2]='RD|OT';
    for($i1=1;$i1<$htrw;$i1++){
        $i2++; $fields[$i2]=$htarr[$i1].'|Reg';
        $i2++; $fields[$i2]=$htarr[$i1].'|OT';
        $i2++; $fields[$i2]=$htarr[$i1].'|RD|Reg';
        $i2++; $fields[$i2]=$htarr[$i1].'|RD|OT';
    } $i2++;
    $fields[$i2]='ND|Reg'; $i2++;
    $fields[$i2]='ND|Reg|OT'; $i2++;
    $fields[$i2]='ND|RD|Reg'; $i2++;
    $fields[$i2]='ND|RD|OT';
    for($i1=1;$i1<$htrw;$i1++){
        $i2++; $fields[$i2]='ND|'.$htarr[$i1];
        $i2++; $fields[$i2]='ND|'.$htarr[$i1].'|OT';
        $i2++; $fields[$i2]='ND|'.$htarr[$i1].'|RD';
        $i2++; $fields[$i2]='ND|'.$htarr[$i1].'|RD|OT';
    }

    $excelData = implode("\t", array_values($fields))."\n";
$lastrow = array();
$sqlseq = "";
if($cutoff==1){ $datefrom=date('Y-m-d', strtotime($year.'-'.$month.'-1')); $dateto=date('Y-m-d', strtotime($year.'-'.$month.'-15')); $sqlseq="AND `gy_tracker_date` >='".date("Y-m-d", strtotime($datefrom.' -1 day'))."' AND `gy_tracker_date` <= '".date("Y-m-d", strtotime($dateto.' + 1 day'))."'"; }
else if($cutoff==2){ $datefrom=date('Y-m-d', strtotime($year.'-'.$month.'-16')); $dateto=date('Y-m-t', strtotime($year.'-'.$month.'-16')); $sqlseq="AND `gy_tracker_date` >='".date("Y-m-d", strtotime($datefrom.' -1 day'))."' AND `gy_tracker_date` <= '".date("Y-m-d", strtotime($dateto.' + 1 day'))."'"; }

$i = 0;
$cntdate = date('Y-m-d', strtotime($datefrom));
$lstdt = checklastdateifholdy($cntdate, $sibsid);
$tmsht=$link->query("SELECT * From `gy_tracker` Where `gy_emp_code`='".$sibsid."' AND `gy_tracker_request`!='reject' AND `gy_tracker_status`=1 ".$sqlseq." Order By `gy_tracker_date` ASC");
    while ($tsrow=$tmsht->fetch_array()){
        $i++;
        $undertime = array(0,0,0);
        $truedate = array(0,0,"","");
        $asloc = $tsrow['gy_tracker_loc'];
        $lilo = array($tsrow['gy_tracker_login'],$tsrow['gy_tracker_breakout'],$tsrow['gy_tracker_breakin'],$tsrow['gy_tracker_logout'], "");
        $truedate = matchsched($tsrow['gy_emp_code'], $tsrow['gy_tracker_date'], $lilo[1], $lilo[2], $lilo[3], $dtrcmpute);
        $lilo = ifot($tsrow['gy_tracker_id'], $tsrow['gy_tracker_code'], $lilo, $truedate[0], $truedate[2], $truedate[3]);

        while($cntdate<date("Y-m-d", strtotime($truedate[0]))){
                $cnttruedate = matchsched($tsrow['gy_emp_code'], $cntdate." 00:00:00", "0000-00-00 00:00:00", "0000-00-00 00:00:00", "0000-00-00 00:00:00", $dtrcmpute);
                $thismode = getmode($tsrow['gy_emp_code'], $cntdate, 100);
                if($cnttruedate[0] == $truedate[0]){ $cnttruedate[1] = 0;  $cnttruedate[2] = ""; $cnttruedate[3] = ""; }
                $absent=""; if(($cnttruedate[1]>0 && $thismode==1) && $emprate==1 && $dtrcmpute!=2){ $absent=1; }
            $dayholsta = array(array());
            $loastatus = getloastatus($tsrow['gy_emp_code'], date("Y-m-d", strtotime($cntdate)));
            $lastrow=tblcntt($thismode, $tsrow['gy_emp_code'], date("m/d/Y", strtotime($cntdate)), chktime($cnttruedate[2]).' - '.chktime($cnttruedate[3]), "0000-00-00 00:00:00", "0000-00-00 00:00:00", $cnttruedate[1], 0, $undertime, $absent, $htrw, $dayholsta, $lastrow, "", $emprate, $dtrcmpute, $lstdt);  unset($dayholsta);
            if(($cnttruedate[1]>0 && $thismode==1) && $loastatus!=1){ $lstdt=0; }else if(($cnttruedate[1]>0 && $thismode==1) && $loastatus==1){ $lstdt=1; }
            $excelData .= implode("\t", array_values($lastrow)) . "\n";
            $cntdate = date('Y-m-d', strtotime($cntdate.' +1 day')); }
            if($cntdate>date("Y-m-d", strtotime($truedate[0]))){ $cntdate = date("Y-m-d", strtotime($truedate[0])); }

            $thismode = getmode($tsrow['gy_emp_code'], $cntdate, 1);
            if ($tsrow['gy_tracker_status'] == 1) {
                if ($thismode == 1 || $thismode == 2) {
                    $undertime = getundert($tsrow['gy_emp_code'], $truedate[0], $tsrow['gy_tracker_login'], $tsrow['gy_tracker_logout'],$tsrow['gy_tracker_breakout'],$tsrow['gy_tracker_breakin'], $dtrcmpute);
                }
            }
            $absent=""; if(($truedate[1]>0 && $thismode==1) && ($lilo[0]=="0000-00-00 00:00:00" || $lilo[3]=="0000-00-00 00:00:00") && $emprate==1 && $dtrcmpute!=2){ $absent=1; }

            if(date("Y-m-d", strtotime($truedate[0]))>=date('Y-m-d', strtotime($datefrom)) && date("Y-m-d", strtotime($truedate[0]))<=date('Y-m-d', strtotime($dateto))){
            $dayholsta = array(array());
            $dayholsta = getholstatus($thismode, date("Y-m-d", strtotime($truedate[0])), $truedate[2], $truedate[3], $lilo[0], $lilo[3], $lilo[1], $lilo[2], $hlarr, $asloc, $dtrcmpute, $lstdt);
            $lastrow=tblcntt($thismode, $tsrow['gy_emp_code'], date("m/d/Y", strtotime($truedate[0])), chktime($truedate[2]).' - '.chktime($truedate[3]), $tsrow['gy_tracker_login'], $tsrow['gy_tracker_logout'], $truedate[1], $tsrow['gy_tracker_bh'], $undertime, $absent, $htrw,$dayholsta, $lastrow, $lilo[4],$emprate, $dtrcmpute, $lstdt); unset($dayholsta);
            if(($truedate[1]>0 && $thismode==1) && ($lilo[0]=="0000-00-00 00:00:00" || $lilo[3]=="0000-00-00 00:00:00")){ $lstdt=0; }else if($thismode!=0){ $lstdt=1; }
            $excelData .= implode("\t", array_values($lastrow)) . "\n";
            }
            $cntdate = date('Y-m-d', strtotime($cntdate.' +1 day'));
            unset($lilo);
    }
    while($cntdate<=date("Y-m-d", strtotime($dateto))){
          $undertime = array(0,0,0);
          $truedate = matchsched($sibsid, $cntdate." 00:00:00", "0000-00-00 00:00:00", "0000-00-00 00:00:00", "0000-00-00 00:00:00", $dtrcmpute);
        $thismode = getmode($sibsid, $cntdate, 100);
        $absent=""; if($truedate[1]>0 && $thismode==1 && $emprate==1 && $dtrcmpute!=2){ $absent=1; }
    if(strtotime($truedate[0]) == strtotime($cntdate.' -1 day')){ $truedate[1] = 0; $truedate[2] = ""; $truedate[3] = "";  }
    $dayholsta = array(array());
    $lastrow=tblcntt($thismode, $sibsid, date("m/d/Y", strtotime($cntdate)), chktime($truedate[2]).' - '.chktime($truedate[3]), "0000-00-00 00:00:00", "0000-00-00 00:00:00", $truedate[1], 0, $undertime, $absent, $htrw, $dayholsta, $lastrow, "",$emprate, $dtrcmpute, $lstdt); unset($dayholsta);
    $excelData .= implode("\t", array_values($lastrow)) . "\n";
    $cntdate = date('Y-m-d', strtotime($cntdate.' +1 day'));
    }
    $excelData .= implode("\t", array_values($pblshrow)) . "\n";
}

$link->close();

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");

echo $excelData;

function tblcntt($mode, $sbsid, $date, $shift, $lin, $lout, $wh, $bh, $utl, $absnt, $htrw, $holarr, $lrow, $aprvnote, $empryt, $cmptyp, $lstdt){
    if($cmptyp!=2){ $loa = getloa($sbsid, date("Y-m-d", strtotime($date))); }
    $abrv = getdaystatus(date("Y-m-d", strtotime($date)));
    if($cmptyp!=2){ if($wh>=5){ $swh = $wh - 1; }else if($wh>4 && $wh<5){ $swh=4; }else{ $swh = $wh; } }else if($cmptyp==2){ $swh = $wh - $utl[2]; }
    if($utl[2]>=1){ $utl[2] = $utl[2] - 1; }else{ $utl[2]=0; }
    if($mode==0 || $mode==2){ $swh=""; $utl[0]=0; $utl[1]=0; $utl[2]=0; }
    $in=chktime($lin);
    $out=chktime($lout);
    if($in=="--:--"){ $in=""; $swh=""; $utl[0]=0; $utl[1]=0; $utl[2]=0; unset($holarr); }
    if($out=="--:--"){ $out=""; $swh=""; $utl[0]=0; $utl[1]=0; $utl[2]=0; unset($holarr); }
    if($shift=="--:-- - --:--"){ $shift=""; $swh=""; $utl[0]=0; $utl[1]=0; $utl[2]=0; unset($holarr); }
    if($cmptyp!=2){
    if($abrv[0]!="" && $abrv[1]==0){ $utl[0]=0; $utl[1]=0; $utl[2]=0; }
    if($abrv[0]!=""){ if($abrv[2]==0){ $absnt=""; if(($mode!=0 && $mode!=2) && ($lstdt==1 && strpos(strtolower($abrv[0]), "lh")!==false)){ if($wh>=5){ $swh = $wh - 1; }else if($wh>4 && $wh<5){ $swh=4; }else{ $swh = $wh; } } }else if($abrv[2]==1 && $absnt!=""){ $swh=""; } }
    if($loa[0]==1 && $mode==1){
        if($loa[2]==1){
            $absnt="";
            if($wh>=5){ $swh = $wh - 1; }else if($wh>4 && $wh<5){ $swh=4; }else{ $swh = $wh; }
            if($loa[4]==0.5 && ($in=="" || $out=="")){ //wo lilo
                if($empryt==1){ $absnt=$loa[4]; }
                else if($empryt==0){ $utl[0]=4; $utl[1]=0; $utl[2]=0; }
            }else if($loa[4]==0.5 && ($in!="" && $out!="")){ //w lilo
                $tmputl=$utl[0]+$utl[1]+$utl[2];
                if($tmputl>4){ $utl[0]=$tmputl-5; }
                else{ $utl[0]=$tmputl-4; }
                $utl[1]=0; $utl[2]=0;
            }
        }
 
        $aprvnote=$loa[1]; }
    if($absnt==1){ unset($holarr); }
    if($swh>8){ $swh=8; }
    }

$lineData = array();
$i3=0;
$lineData[$i3]=$abrv[0]; 
    if($utl[2]>0){ if($aprvnote!=""){$aprvnote.=" | ";} $aprvnote.="Overbreak for ".ifzero(round($utl[2],9)); }
    if(($mode==0 || $mode==2)&&$abrv[0]!=""){$lineData[$i3].="/";}
    if($mode==0){ $lineData[$i3].="RD"; }else if($mode==2){ $lineData[$i3].="RDOT"; }
    $i3++;
$lineData[$i3]=$aprvnote; $i3++;
$lineData[$i3]=$date; $i3++;
if($shift!=""){$lineData[$i3]=$shift;}else if($mode==0 || $mode==2){$lineData[$i3]="Rest Day";} $i3++;
$lineData[$i3]=$in; $i3++;
$lineData[$i3]=$out; $i3++;
$tmprw = ifzero(round($swh,2)); $lineData[$i3]=$tmprw; $i3++;
$tmprw = 0; if(strtotime($lout)<strtotime($lin)+32400 && $lin!="0000-00-00 00:00:00"&&$lout!="0000-00-00 00:00:00"){ $tmprw=getdstrdf((strtotime($lin)+32400)-strtotime($lout)); } $tmprw = ifzero(round($tmprw+$utl[2],9)); if($swh>=5){ if($tmprw>4&&$tmprw<=5){$tmprw=4;}else if($tmprw>5){$tmprw-=1;} } $lineData[$i3]=$tmprw; $i3++;
$tmprw = $absnt; $lineData[$i3]=$absnt;

$lrw=3; for($i4=1;$i4<4;$i4++){
    if(isset($holarr[0][$i4])){ $i3++; $lineData[$i3]=getdstrdf($holarr[0][$i4]); } $lrw++;
}
for($i4=1;$i4<$htrw;$i4++){ for($i5=0;$i5<4;$i5++){
    if(isset($holarr[$i4][$i5])){ $i3++; $lineData[$i3]=getdstrdf($holarr[$i4][$i5]); } $lrw++;
}}
for($i4=4;$i4<8;$i4++){
    if(isset($holarr[0][$i4])){ $i3++; $lineData[$i3]=getdstrdf($holarr[0][$i4]); } $lrw++;
}
for($i4=1;$i4<$htrw;$i4++){ for($i5=4;$i5<8;$i5++){
    if(isset($holarr[$i4][$i5])){ $i3++; $lineData[$i3]=getdstrdf($holarr[$i4][$i5]); } $lrw++;
}}
//return $lrow;
array_walk($lineData, 'filterData');
return $lineData;
}

function filterData(&$str){
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
}

    function getdtrwh($sdate, $adate){
        $tosec = (strtotime($adate) - strtotime($sdate))/60;
        $hour = floor($tosec / 60);
        $min = floor($tosec % 60);

        $total = $hour*60;
        $total += $min;
        return $total/60;
    }

    function getdstrdf($strdtm){
        $tosec = (double)$strdtm/60;
        $hour = floor($tosec / 60);
        $min = floor($tosec % 60);

        $total = $hour*60;
        $total += $min;
        $total = round($total/60,9);
        if($total==0){$total="";}
        return $total;
    }

    function getdtrmindif($sdate, $adate, $mod){
        $tosec = strtotime($adate) - strtotime($sdate);
        $hour = floor($tosec / 3600);
        if($mod == "in"){ $min = floor(($tosec - 3600 * $hour)/60); }
        else if($mod == "out"){ $min = ceil(($tosec - 3600 * $hour)/60); }
        $total = $hour*60;
        $total += $min;
        return $total/60;
    }

function ifzero($numval){
    if($numval<=0){ $numval=""; }
    return $numval;
}

function getundert($dbemp, $correctdate, $dblogin, $dblogout, $dbbrkout, $dbbrkin, $cmptyp){
    include '../../../config/conn.php';
    $theemp = getempid($dbemp);
    $ut = array(0,0,0);
    $empsch=$link->query("SELECT `gy_sched_day`, `gy_sched_login`, `gy_sched_logout` FROM `gy_schedule` WHERE `gy_sched_day`='".$correctdate."' AND `gy_emp_id`='".$theemp."' LIMIT 1");
    if(mysqli_num_rows($empsch) > 0){
        while ($scrow=$empsch->fetch_array()) {
            $schedin = date("Y-m-d H:i:s", strtotime($scrow['gy_sched_day']." ".convert24to0($scrow['gy_sched_login'])));
            if(date("H:i:s", strtotime($scrow['gy_sched_login'])) > date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout'])))){
            $schedout = date("Y-m-d H:i:s", strtotime($scrow['gy_sched_day']." ".convert24to0($scrow['gy_sched_logout']).' +1 day'));
            }else{ $schedout = date("Y-m-d H:i:s", strtotime($scrow['gy_sched_day']." ".convert24to0($scrow['gy_sched_logout']))); }
            $dblogin = date("Y-m-d H:i:s", strtotime($dblogin));
            if($dblogout != "0000-00-00 00:00:00"){ $dblogout = date("Y-m-d H:i:s", strtotime($dblogout)); }
          if($cmptyp==1){
            $schdin2hrlt = date("Y-m-d H:i:s", strtotime($schedin."+2 hours"));
            $schdout2hrut = date("Y-m-d H:i:s", strtotime($schedout."-2 hours"));
            if($dblogin > $schdin2hrlt){ $ut[0] = getdtrmindif($schdin2hrlt, $dblogin, "in"); }
            if($dblogout < $schdout2hrut && $dblogout != "0000-00-00 00:00:00"){ $ut[1] = getdtrmindif($dblogout, $schdout2hrut, "out"); }

            if($dblogin < $schdin2hrlt){ $ut[2] = getdtrmindif($dblogin, $schdin2hrlt, "in"); }
            if($dblogout > $schdout2hrut && $dblogout != "0000-00-00 00:00:00"){ $ut[2] += getdtrmindif($schdout2hrut, $dblogout, "out"); }
          }else{
            if($dblogin > $schedin){ $ut[0] = getdtrmindif($schedin, $dblogin, "in"); }
            if($dblogout < $schedout && $dblogout != "0000-00-00 00:00:00"){ $ut[1] = getdtrmindif($dblogout, $schedout, "out"); }

            if($dblogin < $schedin){ $ut[2] = getdtrmindif($dblogin, $schedin, "in"); }
            if($dblogout > $schedout && $dblogout != "0000-00-00 00:00:00"){ $ut[2] += getdtrmindif($schedout, $dblogout, "out"); }
          }
        }
    }
    $ut[2]=getdtrwh($dbbrkout, $dbbrkin);
    $link->close();
    return $ut;
}

function getdaystatus($holdate){
    include '../../../config/conn.php';
    $holid = array("","","");
    $hddate = date("Y-m-d",strtotime($holdate));
    $curyear = date("Y",strtotime($holdate));
    $curmonth = date("m",strtotime($holdate));
    $curday = date("d",strtotime($holdate));
    $dssql=$link->query("SELECT * FROM `gy_holiday_calendar` LEFT JOIN `gy_holiday_types` ON `gy_holiday_calendar`.`gy_hol_type_id`=`gy_holiday_types`.`gy_hol_type_id` WHERE (`gy_holiday_calendar`.`gy_a_year`=1 AND `gy_holiday_calendar`.`gy_hol_date`='$hddate')OR(`gy_holiday_calendar`.`gy_a_year`=0 AND Year(`gy_holiday_calendar`.`gy_hol_date`)<='$curyear' AND (Year(`gy_holiday_calendar`.`gy_hol_lastday`)='0000' OR (Year(`gy_holiday_calendar`.`gy_hol_lastday`)!='0000' AND Year(`gy_holiday_calendar`.`gy_hol_lastday`)>='$curyear' ) ) )AND(MONTH(`gy_holiday_calendar`.`gy_hol_date`)='$curmonth'AND DAY(`gy_holiday_calendar`.`gy_hol_date`)='$curday') ");
        while($dsrow=$dssql->fetch_assoc()){
            if($holid[0]!=""){ $holid[0].="/"; }
            $holid[0].=strtoupper($dsrow['gy_hol_abbrv']);
            $holid[1]=$dsrow['lateut'];
            $holid[2]=$dsrow['absnt'];
        }
    $link->close();
    return $holid;
}

    function getloastatus($sibid, $thisdate){
    include '../../../config/conn.php';
    $loastatus = 0;
    $ctlsql=$link->query("SELECT * FROM `gy_leave` LEFT JOIN `gy_user` ON `gy_leave`.`gy_user_id`=`gy_user`.`gy_user_id` WHERE `gy_leave`.`gy_leave_date_from`='$thisdate' AND `gy_user`.`gy_user_code`='$sibid' AND `gy_leave`.`gy_leave_status`=1 AND `gy_leave`.`gy_leave_paid`>0 AND `gy_leave`.`gy_publish`=1 ");
        if($ctlsql->num_rows>0){
            $loastatus=1;
        }
    $link->close();
    return $loastatus;
    }

function getloa($sibid, $thisdate){
    include '../../../config/conn.php';
    $tmlvcnt = array(0, "", 0, 0, 0);
    $ctlsql=$link->query("SELECT * FROM `gy_leave` LEFT JOIN `gy_user` ON `gy_leave`.`gy_user_id`=`gy_user`.`gy_user_id` WHERE `gy_leave`.`gy_leave_date_from`='$thisdate' AND `gy_user`.`gy_user_code`='$sibid' AND `gy_leave`.`gy_leave_status`=1 AND `gy_leave`.`gy_publish`=1 ");
        if($ctlsql->num_rows>0){
        $ctlrow=$ctlsql->fetch_array();
            $tmlvcnt[0]=1;
            if($ctlrow['gy_leave_paid']==0 && $ctlrow['gy_leave_day']==1){ $tmlvcnt[1]="Approved LOA (No Pay)"; }
            else if($ctlrow['gy_leave_paid']==1 && $ctlrow['gy_leave_day']==1){ $tmlvcnt[1]="Approved LOA (With Pay)"; }
            else if($ctlrow['gy_leave_paid']==0 && $ctlrow['gy_leave_day']==0.5){ $tmlvcnt[1]="Approved Half Day LOA (No Pay)"; }
            else if($ctlrow['gy_leave_paid']==1 && $ctlrow['gy_leave_day']==0.5){ $tmlvcnt[1]="Approved Half Day LOA (With Pay)"; }
            $tmlvcnt[2]=$ctlrow['gy_leave_paid'];
            $tmlvcnt[3]=$ctlrow['gy_emp_rate'];
            $tmlvcnt[4]=$ctlrow['gy_leave_day'];
        }
    $link->close();
    return $tmlvcnt;
}

function matchsched($dbemp, $dblogin, $dbbreo, $dbbrei, $dblogo, $cmptyp){
    $today = date("Y-m-d", strtotime($dblogin));
    $arrsched = array($today,0,"","");
  if($cmptyp==2){
    if($dblogin!="0000-00-00 00:00:00" && $dblogo!="0000-00-00 00:00:00"){
    $arrsched[1]=getdtrwh(date("Y-m-d H:i:s", strtotime($dblogin)), date("Y-m-d H:i:s", strtotime($dblogo)));
    $arrsched[2]=date("H:i:s", strtotime($dblogin));
    $arrsched[3]=date("H:i:s", strtotime($dblogo));
    }      
  }else{
    include '../../../config/conn.php';
    $yesterday = date("Y-m-d", strtotime($dblogin.' -1 day'));
    $tomorrow = date("Y-m-d", strtotime($dblogin.' +1 day'));
    $theemp = getempid($dbemp);
    $sqlemp="`gy_sched_day`>='".$yesterday."' AND `gy_sched_day`<='".$tomorrow."'";
    if($dblogo != "0000-00-00 00:00:00"){ $endday = $dblogo; }
    else if($dbbrei != "0000-00-00 00:00:00"){ $endday = $dbbrei; }
    else if($dbbreo != "0000-00-00 00:00:00"){ $endday = $dbbreo; }
    else { $endday = $dblogin; $sqlemp="`gy_sched_day`='".$today."'"; }
    $empsch=$link->query("SELECT `gy_sched_day`, `gy_sched_login`, `gy_sched_logout` FROM `gy_schedule` WHERE ".$sqlemp." AND `gy_emp_id`='".$theemp."' AND `gy_sched_mode`!=0 ORDER BY `gy_sched_day` ASC");
    if(mysqli_num_rows($empsch) > 0){
        while ($scrow=$empsch->fetch_array()) {
            if(date("H:i:s", strtotime(convert24to0($scrow['gy_sched_login']))) > date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout'])))) {
                $schedlout = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout']))).' +1 day');
            }else{
                $schedlout = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_logout']))));
            }
                $schedin = strtotime($scrow['gy_sched_day']." ".date("H:i:s", strtotime(convert24to0($scrow['gy_sched_login']))));
            if(strtotime($dblogin) <= $schedlout && strtotime($endday) >= $schedin || $sqlemp=="`gy_sched_day`='".$today."'"){ $arrsched[0] = $scrow['gy_sched_day'];
            $arrsched[1] = getdtrwh(date("Y-m-d", strtotime($arrsched[0]))." ".convert24to0($scrow['gy_sched_login']), date("Y-m-d H:i:s", $schedlout));
            $arrsched[2] = $scrow['gy_sched_login'];
            $arrsched[3] = $scrow['gy_sched_logout'];
            break; }
        }
    }
    $link->close();
  }
    return $arrsched;
}

function ifot($trkid, $trkcd, $lilo, $scdate, $scin, $scout){
$sftin = strtotime(date("Y-m-d", strtotime($scdate))." ".convert24to0($scin));
$sftout = strtotime(date("Y-m-d", strtotime($scdate))." ".convert24to0($scout));
if($sftin>$sftout){ $sftout = strtotime(date("Y-m-d", strtotime($scdate))." ".$scout."+1 day"); }

if($sftin>strtotime($lilo[0])){ $lilo[0]=date("Y-m-d H:i:s", $sftin); }
if($sftout<strtotime($lilo[3])){ $lilo[3]=date("Y-m-d H:i:s", $sftout); }
$requestdate="";
    include '../../../config/conn.php';
    $escsql=$link->query("SELECT `gy_tracker_login`, `gy_tracker_breakout`, `gy_tracker_breakin`, `gy_tracker_logout`, `gy_esc_date` FROM `gy_escalate` WHERE `gy_esc_type`=6 AND `gy_esc_status`=1 AND `gy_tracker_id`='$trkid' ORDER BY `gy_esc_id` desc Limit 1");
        while($escrow=$escsql->fetch_array()){
            if(strtotime($escrow['gy_tracker_login'])<$sftin && strtotime($escrow['gy_tracker_logout'])>$sftout && getdtrwh($escrow['gy_tracker_login'], $sftin)>=0.5 && getdtrwh($sftout, $escrow['gy_tracker_logout'])>=0.5){
                $lilo[0]=$escrow['gy_tracker_login'];
                $lilo[3]=$escrow['gy_tracker_logout'];
                $lilo[4]="Approved OT ".date("h:iA", strtotime($lilo[0]))." and ".date("h:ia", strtotime($lilo[3])); }
            else if(strtotime($escrow['gy_tracker_login'])<$sftin && getdtrwh($escrow['gy_tracker_login'], $sftin)>=0.5){ $lilo[0]=$escrow['gy_tracker_login']; $lilo[4]="Approved Early OT from ".date("h:iA", strtotime($lilo[0])); }
            else if(strtotime($escrow['gy_tracker_logout'])>$sftout && getdtrwh($sftout, $escrow['gy_tracker_logout'])>=0.5){ $lilo[3]=$escrow['gy_tracker_logout']; $lilo[4]="Approved OT until ".date("h:iA", strtotime($lilo[3])); }
            $requestdate=$escrow['gy_esc_date'];
        }

    $schsql=$link->query("SELECT `gy_tracker_login`,`gy_tracker_logout`,`gy_sched_login`,`gy_sched_logout`,`gy_sched_day`,`gy_req_date` FROM `gy_schedule_escalate` WHERE `gy_req_status`=1 AND `gy_sched_mode`=2 AND `gy_tracker_login`!='0000-00-00 00:00:00' AND `gy_tracker_logout`!='0000-00-00 00:00:00' AND `gy_sched_esc_code`=$trkcd ORDER BY `gy_sched_esc_id` desc Limit 1");
        while($schrow=$schsql->fetch_array()){
          if(strtotime($schrow['gy_req_date'])>strtotime($requestdate)){
            $tmpin = date("Y-m-d H:i:s", strtotime(date("Y-m-d", strtotime($schrow['gy_sched_day']))." ".date("H:i:s",strtotime($schrow['gy_sched_login']))));
            $tmpout= date("Y-m-d H:i:s", strtotime(date("Y-m-d", strtotime($schrow['gy_sched_day']))." ".date("H:i:s",strtotime($schrow['gy_sched_logout']))));
             if(strtotime($tmpin)>strtotime($tmpout)){ $tmpout=date("Y-m-d H:i:s", strtotime($tmpout."+1 day")); }
             if(strtotime($schrow['gy_tracker_login'])<strtotime($tmpin) && strtotime($schrow['gy_tracker_logout'])>strtotime($tmpout) && getdtrwh($schrow['gy_tracker_login'], $tmpin)>=0.5 && getdtrwh($tmpout, $schrow['gy_tracker_logout'])>=0.5){
                 $lilo[0]=$schrow['gy_tracker_login'];
                 $lilo[3]=$schrow['gy_tracker_logout'];
                 $lilo[4]="Approved OT ".date("h:iA", strtotime($lilo[0]))." and ".date("h:iA", strtotime($lilo[3])); }
             else if(strtotime($schrow['gy_tracker_login'])<strtotime($tmpin) && getdtrwh($schrow['gy_tracker_login'], $tmpin)>=0.5){ $lilo[0]=$schrow['gy_tracker_login']; $lilo[4]="Approved Early OT from ".date("h:iA", strtotime($lilo[0])); }
             else if(strtotime($schrow['gy_tracker_logout'])>strtotime($tmpout) && getdtrwh($tmpout, $schrow['gy_tracker_logout'])>=0.5){ $lilo[3]=$schrow['gy_tracker_logout']; $lilo[4]="Approved OT until ".date("h:iA", strtotime($lilo[3])); }
          }
        }
    $link->close();
    return $lilo;
}

function checklastdateifholdy($cdate, $empcode){
    $validdt = 0;
    include '../../../config/conn.php';
    $emplsch=$link->query("SELECT `gy_sched_day`,`gy_sched_login`,`gy_sched_logout` FROM `gy_schedule` WHERE `gy_sched_day`<'".$cdate."' AND `gy_emp_id`='".getempid($empcode)."' AND `gy_sched_mode`=1 ORDER BY `gy_sched_day` DESC LIMIT 1");
        while ($elsrow=$emplsch->fetch_array()){
            $scdin = $elsrow['gy_sched_day']." ".convert24to0($elsrow['gy_sched_login']);
            $scdout = $elsrow['gy_sched_day']." ".convert24to0($elsrow['gy_sched_logout']);
            if($scdin>=$scdout){ $scdout = date("Y-m-d H:i:s", strtotime($scdout." +1 day")); }
            $sqlseq = " AND `gy_tracker_login`!='0000-00-00 00:00:00' AND `gy_tracker_logout`!='0000-00-00 00:00:00' AND `gy_tracker_login`<'$scdout' AND `gy_tracker_logout`>'$scdin' ";
            $trksql=$link->query("SELECT * From `gy_tracker` Where `gy_emp_code`='".$empcode."' AND `gy_tracker_request`!='reject' AND `gy_tracker_status`=1 ".$sqlseq." Order By `gy_tracker_date` ASC");
            if(mysqli_num_rows($trksql) > 0){ $validdt=1; }

            $thisdate=$elsrow['gy_sched_day'];
            $ctlsql=$link->query("SELECT * FROM `gy_leave` LEFT JOIN `gy_user` ON `gy_leave`.`gy_user_id`=`gy_user`.`gy_user_id` WHERE `gy_leave`.`gy_leave_date_from`='$thisdate' AND `gy_user`.`gy_user_code`='$empcode' AND `gy_leave`.`gy_leave_status`=1 AND `gy_leave`.`gy_publish`=1 ");
                if(mysqli_num_rows($ctlsql) > 0){ $validdt=1; }
        }
    $link->close(); 
    return $validdt;
}

function getmode($empcode, $date, $mod){
    include '../../../config/conn.php';
    $empsch=$link->query("SELECT `gy_sched_mode` FROM `gy_schedule` WHERE `gy_sched_day`='".$date."' AND `gy_emp_id`='".getempid($empcode)."' LIMIT 1");
        while ($modrow=$empsch->fetch_array()){ $mod = $modrow['gy_sched_mode']; }
    $link->close();
    return $mod;
}

function getholstatus($mode, $holdate, $shiftin, $shiftout, $lin, $lout, $bout, $bin, $hlarr, $asloc, $cmptyp, $lstdt){
    include '../../../config/conn.php';
    $holarr = array(array());
    for($i=0;$i<=count($hlarr);$i++){ for($i1=0;$i1<8;$i1++){ $holarr[$i][$i1]=""; }}
if($cmptyp!=2){
$dayarr = array();
$dayarr[0] = date("Y-m-d", strtotime($holdate." -1 day"));
$dayarr[1] = $holdate;
$sftin = strtotime($holdate." ".$shiftin);
$sftout = strtotime($holdate." ".$shiftout);
if(strtotime($shiftin)>strtotime($shiftout)){ $sftout = strtotime($holdate." ".$shiftout." +1 day"); }
$slin=strtotime($lin);
$slout=strtotime($lout);
if($cmptyp==1){
    if(($slin>$sftin && $slin<=($sftin+7200)) || ($slin<$sftin && $slin>=($sftin-7200))){ $sftin=$slin; }
    else if($slin>($sftin+7200)){ $sftin=$sftin+7200; }
    else if($slin<($sftin-7200)){ $sftin=$sftin-7200; }
    if($slout<($sftin + 25200)){ $sftout = $slout; }
    else{ $sftout = $sftin + 32400; }
}else{
    if($slin>$sftin){ $sftin=$slin; } if($slout<$sftout){ $sftout=$slout; }
}
if($bout=="0000-00-00 00:00:00" && $bin!="0000-00-00 00:00:00"){ $bin=$bout; }
else if($bin=="0000-00-00 00:00:00" && $bout!="0000-00-00 00:00:00"){ $bout=$bin; }
else if($bin=="0000-00-00 00:00:00" && $bout=="0000-00-00 00:00:00"){
    if(getdstrdf($sftout-$sftin)>=5){
        $bout = date("Y-m-d H:i:s", $sftin+14400);
        $bin = date("Y-m-d H:i:s", $sftin+18000);
    }else{
        $bout = date("Y-m-d H:i:s", $sftin+(($sftout-$sftin)/2));
        $bin = $bout;
    }
}else if(strtotime($bin)>=$sftout){
    $tbrk = strtotime($bin)-strtotime($bout);
    if($cmptyp==0 || $cmptyp==1){
    if(($sftout-$sftin)>=18000 && $tbrk<3600){ $tbrk=3600; }
    else if(($sftout-$sftin)<=14400&& $tbrk<59){ $tbrk=0; }
    }
    if(($sftout-$sftin)>$tbrk){
        $bout = date("Y-m-d H:i:s", $sftin+((($sftout-$sftin)/2)-($tbrk/2)));
        $bin = date("Y-m-d H:i:s", $sftin+((($sftout-$sftin)/2)+($tbrk/2)));
    }else if(($sftout-$sftin)==$tbrk){
        $bout = date("Y-m-d H:i:s", $sftin);
        $bin = date("Y-m-d H:i:s", $sftout);
    }else{
        $bout = date("Y-m-d H:i:s", $sftin+((($sftout-$sftin)/2)));
        //$bin = date("Y-m-d H:i:s", $bout." +1 second");
        $bin = $bout;
    }
   if((strtotime($bin)-strtotime($bout))<3600){
    if(getdstrdf($sftout-$sftin)>=5){ $addsec = 3600-(strtotime($bin)-strtotime($bout)); }
    else { $addsec = 0; }
    if((strtotime($bin)+$addsec)<strtotime($lout) && (strtotime($bin)+$addsec)<$sftout){ $bin=date("Y-m-d H:i:s", strtotime($bin)+$addsec); }
    else if((strtotime($bout)-$addsec)>strtotime($lin) && (strtotime($bout)-$addsec)>$sftin){ $bout=date("Y-m-d H:i:s", strtotime($bout)-$addsec); }
   }
}else if((strtotime($bin)-strtotime($bout))<3600){
    if(getdstrdf($sftout-$sftin)>=5){ $addsec = 3600-(strtotime($bin)-strtotime($bout)); }
    else { $addsec = 0; }
    if((strtotime($bin)+$addsec)<strtotime($lout) && (strtotime($bin)+$addsec)<$sftout){ $bin=date("Y-m-d H:i:s", strtotime($bin)+$addsec); }
    else if((strtotime($bout)-$addsec)>strtotime($lin) && (strtotime($bout)-$addsec)>$sftin){ $bout=date("Y-m-d H:i:s", strtotime($bout)-$addsec); }
}
$sbout=strtotime($bout);
$sbin=strtotime($bin);
//echo "b=".$holdate." ".$bout."=b";

if(date("Y-m-d", strtotime($lout))>date("Y-m-d", strtotime($holdate))){ $dayarr[2] = date("Y-m-d", strtotime($holdate." +1 day")); }

$d0dix = array();
$d1dix = array();
$d2dix = array();
for($i=0;$i<count($dayarr);$i++){
    $hddate = date("Y-m-d",strtotime($dayarr[$i]));
    $curyear = date("Y",strtotime($dayarr[$i]));
    $curmonth = date("m",strtotime($dayarr[$i]));
    $curday = date("d",strtotime($dayarr[$i]));
$sqls="SELECT * FROM `gy_holiday_calendar` LEFT JOIN `gy_holiday_types` ON `gy_holiday_calendar`.`gy_hol_type_id`=`gy_holiday_types`.`gy_hol_type_id` WHERE ((`gy_holiday_calendar`.`gy_a_year`=1 AND `gy_holiday_calendar`.`gy_hol_date`='$hddate')OR(`gy_holiday_calendar`.`gy_a_year`=0 AND Year(`gy_holiday_calendar`.`gy_hol_date`)<='$curyear' AND (Year(`gy_holiday_calendar`.`gy_hol_lastday`)='0000' OR (Year(`gy_holiday_calendar`.`gy_hol_lastday`)!='0000' AND Year(`gy_holiday_calendar`.`gy_hol_lastday`)>='$curyear' ) ) )AND(MONTH(`gy_holiday_calendar`.`gy_hol_date`)='$curmonth'AND DAY(`gy_holiday_calendar`.`gy_hol_date`)='$curday')) AND (`gy_holiday_calendar`.`gy_hol_loc`=$asloc OR `gy_holiday_calendar`.`gy_hol_loc`=2) ";
$dssql=$link->query($sqls);
        $holcnt = $dssql->num_rows;
        if($i==0){
            $i2=0;
            if($holcnt>0){
                while($dsrow1=$dssql->fetch_assoc()){ if(($dsrow1['gy_hol_type_id']==1&&$lstdt==1)||($dsrow1['gy_hol_type_id']!=1)){
                    $ystart = strtotime($dayarr[$i]." ".$dsrow1['gy_day_start']);
                    $yend = strtotime($dayarr[$i]." ".$dsrow1['gy_day_end']);
                    $ynstart = strtotime($dayarr[$i]." ".$dsrow1['gy_night_start']);
                    $ynend = strtotime($dayarr[$i]." ".$dsrow1['gy_night_end']);
                    if($ynstart>$ynend){ $ynend=strtotime($dayarr[$i]." ".$dsrow1['gy_night_end']." +1 day"); }
                    $d0dix[$i2] = array_search($dsrow1['gy_hol_type_id'],$hlarr);
                    $i2++;
                }}
            }if($i2==0){
             $ystart=strtotime($dayarr[0]." 06:00:00");
             $yend=strtotime($dayarr[0]." 22:00:00");
             $ynstart=strtotime($dayarr[0]." 22:00:00");
             $ynend=strtotime($dayarr[1]." 06:00:00");
             $d0dix[0]=0;
            }
        }else if($i==1){
            $ymidn = strtotime($holdate." 00:00:00");
            $midn = strtotime($holdate." 00:00:00 +1 day");
            $i2=0;
            if($holcnt>0){
                while($dsrow1=$dssql->fetch_assoc()){ if(($dsrow1['gy_hol_type_id']==1&&$lstdt==1)||($dsrow1['gy_hol_type_id']!=1)){
                    $start = strtotime($dayarr[$i]." ".$dsrow1['gy_day_start']);
                    $end = strtotime($dayarr[$i]." ".$dsrow1['gy_day_end']);
                    $nstart = strtotime($dayarr[$i]." ".$dsrow1['gy_night_start']);
                    $nend = strtotime($dayarr[$i]." ".$dsrow1['gy_night_end']);
                    if($nstart>$nend){ $nend=strtotime($dayarr[$i]." ".$dsrow1['gy_night_end']." +1 day"); }
                    $d1dix[$i2] = array_search($dsrow1['gy_hol_type_id'],$hlarr);
                    $i2++;
                }}
            }if($i2==0){
             $start=strtotime($holdate." 06:00:00");
             $end=strtotime($holdate." 22:00:00");
             $nstart=strtotime($holdate." 22:00:00");
             $nend=strtotime($holdate." 06:00:00 +1 day");
             $d1dix[0]=0;
            }
        }else if($i==2){
            $i2=0;
            if($holcnt>0){
                while($dsrow1=$dssql->fetch_assoc()){ if(($dsrow1['gy_hol_type_id']==1&&$lstdt==1)||($dsrow1['gy_hol_type_id']!=1)){
                    $d2dix[$i2] = array_search($dsrow1['gy_hol_type_id'],$hlarr);
                    $i2++;
                }}
            }if($i2==0){ $d2dix[0]=0;  }
        }
}
    $link->close();
if($mode==1 && $cmptyp==0){ $reg=0; $ot=1; $nd=4; $ndot=5; }else if($mode==2 && $cmptyp==0){ $reg=2; $ot=3; $nd=6; $ndot=7; }else if($mode==1 && $cmptyp==1){ $reg=0; $ot=0; $nd=4; $ndot=4; }
if($mode==1 || ($mode==2 && $cmptyp==0)){ for($day=0;$day<count($dayarr);$day++){
    if($day==0){
      for($i3=0;$i3<count($d0dix);$i3++){
        if($slin<=$ymidn && $slin>=$ynstart && $sbout>=$ymidn){ $holarr[$d0dix[$i3]][$ndot]+=($ymidn-$slin); }//early NDOT
        else if($sbout<=$ymidn && $slin>=$ynstart){ $holarr[$d0dix[$i3]][$ndot]+=($sbout-$slin); }//early NDOT
        else if($sbout>$ymidn && $slin<$ynstart){ $holarr[$d0dix[$i3]][$ndot]+=($ymidn-$ynstart); }//early NDOT
        else if($slin<$ynstart && $sbout>=$ynstart && $sbout<=$ymidn){ $holarr[$d0dix[$i3]][$ndot]+=($sbout-$ynstart); }//early NDOT

        if($sbin<=$ymidn && $sbin>=$ynstart && $slout>$ymidn){ $holarr[$d0dix[$i3]][$ndot]+=($ymidn-$sbin); }//early NDOT
        else if($sbin<=$ynstart){ $holarr[$d0dix[$i3]][$ndot]+=($ymidn-$ynstart); }//early NDOT

        if($slin<$ynstart && $sbout>=$ynstart){ $holarr[$d0dix[$i3]][$ot]+=($ynstart-$slin); }//early OT
        else if($slin<$ynstart && $sbout<=$ynstart){ $holarr[$d0dix[$i3]][$ot]+=($sbout-$slin); }//early OT
        else if($sbin<$ynstart){ $holarr[$d0dix[$i3]][$ot]+=($ynstart-$sbin); }//early OT
      }
    }else if($day==1){
      for($i3=0;$i3<count($d1dix);$i3++){
       // morning ND
       if($sftout>$ymidn && $sftin<$ynend){
        if($sftin>=$ymidn && $sbout<=$ynend){ $holarr[$d1dix[$i3]][$nd]+=($sbout-$sftin); } //ND BO
        else if($sftin<$ymidn && $sbout<=$ynend && $sbout>$ymidn){ $holarr[$d1dix[$i3]][$nd]+=($sbout-$ymidn); }
        else if($sftin>=$ymidn && $sftin<$ynend && $sbout>=$ynend){ $holarr[$d1dix[$i3]][$nd]+=($ynend-$sftin); }
        else if($sftin>$ymidn && $sbout>$ynend){ $holarr[$d1dix[$i3]][$nd]+=($ynend-$ymidn); }

        if($sbin>=$ymidn && $sftout<=$ynend){ $holarr[$d1dix[$i3]][$nd]+=($sftout-$sbin); } //ND BI
        else if($sbin>=$ymidn && $sbin<$ynend && $sftout>$ynend){ $holarr[$d1dix[$i3]][$nd]+=($ynend-$sbin); }
        else if($sbin<=$ymidn && $sftout>$ymidn && $sftout<=$ynend){ $holarr[$d1dix[$i3]][$nd]+=($sftout-$ymidn); }
        else if($sbin<$ymidn && $sftout>$ynend){ $holarr[$d1dix[$i3]][$nd]+=($ynend-$ymidn); }
       }
       // night ND
       if($sftin<$midn && $sftout>$nstart){
        if($sftin>=$nstart && $sbout<=$midn){ $holarr[$d1dix[$i3]][$nd]+=($sbout-$sftin); }//ND BO
        else if($sftin>=$nstart && $sftin<$midn && $sbout>$midn){ $holarr[$d1dix[$i3]][$nd]+=($midn-$sftin); }
        else if($sftin<$nstart && $sbout>$nstart && $sbout<=$midn){ $holarr[$d1dix[$i3]][$nd]+=($sbout-$nstart); }
        else if($sftin<$nstart && $sbout>$midn){ $holarr[$d1dix[$i3]][$nd]+=($midn-$nstart); }

        if($sbin>=$nstart && $sftout<=$midn){ $holarr[$d1dix[$i3]][$nd]+=($sftout-$sbin); }//ND BI
        else if($sbin>=$nstart && $sbin<$midn && $sftout>$midn){ $holarr[$d1dix[$i3]][$nd]=((double)$holarr[$d1dix[$i3]][$nd])+((double)$midn-$sbin); }
        else if($sbin<$nstart && $sftout>$nstart && $sftout<=$midn){ $holarr[$d1dix[$i3]][$nd]=((double)$holarr[$d1dix[$i3]][$nd])+((double)$sftout-$nstart); }
        else if($sbin<$nstart && $sftout>$midn){ $holarr[$d1dix[$i3]][$nd]=((double)$holarr[$d1dix[$i3]][$nd])+((double)$midn-$nstart); }
       }

       // morning NDOT
       if($slin<$ynend && $slout>$ymidn){
        if($slin<$sftin){//NDOT early
         if($slin>=$ymidn && $sftin<=$ynend){ $holarr[$d1dix[$i3]][$ndot]+=($sftin-$slin); }
         else if($slin<$ymidn && $sftin<=$ynend && $sftin>$ymidn){ $holarr[$d1dix[$i3]][$ndot]+=($sftin-$ymidn); }
         else if($slin>=$ymidn && $slin<$ynend && $sftin>$ynend){ $holarr[$d1dix[$i3]][$ndot]+=($ynend-$slin); }
         else if($slin<$ymidn && $sftin>$ynend){ $holarr[$d1dix[$i3]][$ndot]+=($ynend-$ymidn); }
        }
        if($slout>$sftout){ //NDOT OT
         if($sftout>=$ymidn && $slout<=$ynend){ $holarr[$d1dix[$i3]][$ndot]+=($slout-$sftout); }
         else if($sftout>=$ymidn && $sftout<=$ynend && $slout>$ynend){ $holarr[$d1dix[$i3]][$ndot]+=($ynend-$sftout); }
        }
       }
       // evening NDOT
       if($slin<$midn && $slout>$nstart){
        if($slin<$sftin){//NDOT early
         if($slin>=$nstart && $sftin<=$midn){ $holarr[$d1dix[$i3]][$ndot]+=($sftin-$slin); }
         else if($slin<$nstart && $sftin>$nstart && $sftin<$midn){ $holarr[$d1dix[$i3]][$ndot]+=($sftin-$nstart); }
        }
        if($slout>$sftout){//NDOT OT
         if($sftout>=$nstart && $slout<=$midn){ $holarr[$d1dix[$i3]][$ndot]+=($slout-$sftout); }
         else if($sftout>=$nstart && $sftout<$midn && $slout>$midn){ $holarr[$d1dix[$i3]][$ndot]+=($midn-$sftout); }
         else if($sftout<$nstart && $slout>$midn){ $holarr[$d1dix[$i3]][$ndot]=(double)$holarr[$d1dix[$i3]][$ndot]+(double)($midn-$nstart); }
         else if($sftout<$nstart && $slout>$nstart && $slout<=$midn){ $holarr[$d1dix[$i3]][$ndot]=((double)$holarr[$d1dix[$i3]][$ndot])+((double)$slout-$nstart); }
        }
       }
       // regular
       if($sftin<$nstart && $sftout>$ynend){
        if($sftin>=$ynend && $sbout<=$nstart){ $holarr[$d1dix[$i3]][$reg]=((double)$holarr[$d1dix[$i3]][$reg])+((double)$sbout-$sftin); }//reg bo
        else if($sftin<$ynend && $sbout>$ynend && $sbout<=$nstart){ $holarr[$d1dix[$i3]][$reg]+=($sbout-$ynend); }
        else if($sftin>=$ynend && $sftin<$nstart && $sbout>$nstart){ $holarr[$d1dix[$i3]][$reg]+=($nstart-$sftin); }
        else if($sftin<$ynend && $sbout>$nstart){ $holarr[$d1dix[$i3]][$reg]+=($nstart-$ynend); }

        if($sbin>=$ynend && $sftout<=$nstart){ $holarr[$d1dix[$i3]][$reg]+=($sftout-$sbin); }//reg bi
        else if($sbin>=$ynend && $sbin<$nstart && $sftout>$nstart){ $holarr[$d1dix[$i3]][$reg]+=($nstart-$sbin); }
        else if($sbin<$ynend && $sftout>$ynend && $sftout<=$nstart){ $holarr[$d1dix[$i3]][$reg]+=($sftout-$ynend); }
        else if($sbin<$ynend && $sftout>$nstart){ $holarr[$d1dix[$i3]][$reg]+=($nstart-$ynend); }
       }
       // regular ot
       if($slin<$nstart && $slout>$ynend){
        if($slin<$sftin){//early
         if($slin>=$ynend && $sftin<=$nstart){ $holarr[$d1dix[$i3]][$ot]+=($sftin-$slin); }
         else if($slin<$ynend && $sftin>$ynend && $sftin<=$nstart){ $holarr[$d1dix[$i3]][$ot]+=($sftin-$ynend); }
         else if($slin<$ynend && $sftin>$nstart){ $holarr[$d1dix[$i3]][$ot]+=($sftin-$ynend); }
         else if($slin>=$ynend && $slin<$nstart && $sftin>$nstart){ $holarr[$d1dix[$i3]][$ot]+=($nstart-$slin); }
        }
        if($slout>$sftout){//ot
         if($sftout>=$ynend && $slout<=$nstart){ $holarr[$d1dix[$i3]][$ot]=((double)$holarr[$d1dix[$i3]][$ot])+((double)$slout-$sftout); }
         else if($sftout>=$ynend && $sftout<$nstart && $slout>$nstart){ $holarr[$d1dix[$i3]][$ot]=((double)$holarr[$d1dix[$i3]][$ot])+((double)$nstart-$sftout); }
         else if($sftout<$ynend && $slout>$nstart){ $holarr[$d1dix[$i3]][$ot]+=($nstart-$ynend); }
        }
       }
      }
    }else if($day==2){
      for($i3=0;$i3<count($d2dix);$i3++){
       //nd nextday
       if($sftin<=$midn && $sbout>$midn && $sbout<=$nend){ $holarr[$d2dix[$i3]][$nd]+=($sbout-$midn); }//ND nextday bo
       else if($sftin>=$midn && $sbout<=$nend){ $holarr[$d2dix[$i3]][$nd]+=($sbout-$sftin); }
       else if($sftin>=$midn && $sbout>$nend){ $holarr[$d2dix[$i3]][$nd]+=($nend-$sftin); }
       else if($sftin>=$nend && $sbout>$nend){ $holarr[$d2dix[$i3]][$reg]+=($sbout-$sftin); }
       else if($sftin<=$midn && $sbout>$midn && $sbout>$nend){ $holarr[$d2dix[$i3]][$nd]+=($nend-$midn); }

       if($sbin>=$midn && $sftout<=$nend){ $holarr[$d2dix[$i3]][$nd]+=($sftout-$sbin); } //ND nextday bi
       else if($sbin>=$midn && $sbin<=$nend && $sftout>$nend){ $holarr[$d2dix[$i3]][$nd]+=($nend-$sbin); }
       else if($sftin<=$nend && $sbout>$nend){ $holarr[$d2dix[$i3]][$reg]+=($sbout-$nend); }
       else if($sbin<=$midn && $sftout>$midn && $sftout<=$nend){ $holarr[$d2dix[$i3]][$nd]=((double)$holarr[$d2dix[$i3]][$nd])+((double)$sftout-$midn); }
       else if($sbin<=$midn && $sftout>$nend){ $holarr[$d2dix[$i3]][$nd]+=($nend-$midn); }

       if($sbin<=$nend && $sftout>$nend){ $holarr[$d2dix[$i3]][$reg]+=($sftout-$nend); } //nextday reg
       else if($sbin>=$nend && $sftout>$nend){ $holarr[$d2dix[$i3]][$reg]+=($sftout-$sbin); }
       //ot/ndot
       if($slout>$sftout && $slout>$midn){
        if($slout<=$nend && $sftout>=$midn){ $holarr[$d2dix[$i3]][$ndot]=((double)$holarr[$d2dix[$i3]][$ndot])+((double)$slout-$sftout); }//nextday NDOT
        else if($slout<=$nend && $sftout<$midn){ $holarr[$d2dix[$i3]][$ndot]=(double)$holarr[$d2dix[$i3]][$ndot]+(double)($slout-$midn); }
        else if($slout>$nend && $sftout<$midn){ $holarr[$d2dix[$i3]][$ndot]+=($nend-$midn); }
        else if($slout>$nend && $sftout>=$midn && $sftout<=$nend){ $holarr[$d2dix[$i3]][$ndot]=((double)$holarr[$d2dix[$i3]][$ndot])+((double)$nend-$sftout); }

        if($slout>$nend && $sftout<=$nend){ $holarr[$d2dix[$i3]][$ot]=((double)$holarr[$d2dix[$i3]][$ot])+((double)$slout-$nend); }//nextday ot
        else if($slout>$nend && $sftout>$nend){ $holarr[$d2dix[$i3]][$ot]+=($slout-$sftout); }
       }
      }
    }
}}
}
    return $holarr;    
}
?>