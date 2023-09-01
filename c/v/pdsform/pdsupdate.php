<?php 
date_default_timezone_set('Asia/Taipei');
    include '../../../config/conn.php';
    include '../../../config/function.php';
    include '../../../config/connnk.php';
    include '../session.php';

function cmpllog($dbn, $old, $new){
    $arw = "";
    $rnd = rand(0,3);
    if($rnd==0){ $arw="<i class='fas fa-angle-double-right faa-horizontal faa-reverse faa-fast animated'></i>"; }
    else if($rnd==1){ $arw="<i class='fas fa-angle-double-right faa-passing faa-slow animated'></i>"; }
    else if($rnd==2){ $arw="<i class='fas fa-angle-double-right faa-passing faa-fast animated'></i>"; }
    else if($rnd==3){ $arw="<i class='fas fa-angle-double-right faa-horizontal animated'></i>"; }
    return $dbn." : ".preg_replace('/\s+/', '', $old).$arw.preg_replace('/\s+/', '', $new)."<br>";
}

function cvtloc($loc){
    if($loc==0){$loc="Tagum";}
    else if($loc==1){$loc="Davao";}
    return $loc;
}

function fblnk($postdate){
    if($postdate==""){ $postdate="0000-00-00"; }
    return $postdate;
}

    if($user_type == 5 && $user_dept == 2 && $myaccount==36){
        $sibsid = addslashes($_REQUEST['sibsid']);

        $flname = addslashes($_REQUEST['pclnm']);
        $ffname = addslashes($_REQUEST['pcfnm']);
        $fmname = addslashes($_REQUEST['pcmnm']);

        $perg = ucfirst(strtolower(addslashes($_REQUEST['pcgdr'])));
        $perdob = addslashes($_REQUEST['pcdob']);
        $percs = ucfirst(strtolower(addslashes($_REQUEST['pccst'])));

        $empdh = addslashes($_REQUEST['ecdh']);
        $ctctha = addslashes($_REQUEST['ecal']);

        $ctctea = addslashes($_REQUEST['cchma']);
        $ctctma = addslashes($_REQUEST['ccema']);
        $ctctsa = addslashes($_REQUEST['ccmad']);
        $ctctgid = addslashes($_REQUEST['ccsad']);
        $ctctgidn = addslashes($_REQUEST['ccgnm']);
        $ctctpe = addslashes($_REQUEST['ccgid']);
        $ctctcn = addslashes($_REQUEST['ccpem']);
        $ctctecp = addslashes($_REQUEST['cccnu']);
        $ctctecn = addslashes($_REQUEST['ccecp']);
        $proacc = addslashes($_REQUEST['ccecn']);

        $asloc = addslashes($_REQUEST['pcajd']);
        $promng = addslashes($_REQUEST['pcacc']);
        $proajd = addslashes($_REQUEST['pcman']);

        $tcnhod = fblnk(words($_REQUEST['tcnhod']));
        $fstsd = fblnk(words($_REQUEST['fstsd']));
        $fsted = fblnk(words($_REQUEST['fsted']));
        $certdt = fblnk(words($_REQUEST['certdt']));
        $pstsd = fblnk(words($_REQUEST['pstsd']));
        $psted = fblnk(words($_REQUEST['psted']));
        $fugold = fblnk(words($_REQUEST['fugold']));
        $grbasd = fblnk(words($_REQUEST['grbasd']));
        $grbaed = fblnk(words($_REQUEST['grbaed']));
        $promd = fblnk(words($_REQUEST['promd']));

        $proemp = fblnk(words($_REQUEST['proemp']));
        $probemp = fblnk(words($_REQUEST['probemp']));
        $regemp = fblnk(words($_REQUEST['regemp']));

        $tagdate = fblnk(words($_REQUEST['tagdate']));
        $davdate = fblnk(words($_REQUEST['davdate']));
        $hybdate = fblnk(words($_REQUEST['hybdate']));
        if($tagdate!="0000-00-00" && $davdate=="0000-00-00"){
            if(date("Y-m-d", strtotime($tagdate))<=date("Y-m-d")){ $ctctha=0; }
        }else if($tagdate=="0000-00-00" && $davdate!="0000-00-00"){
            if(date("Y-m-d", strtotime($davdate))<=date("Y-m-d")){ $ctctha=1; }
        }else if($tagdate!="0000-00-00" && $davdate!="0000-00-00"){
            if(date("Y-m-d", strtotime($tagdate))<=date("Y-m-d")&&(date("Y-m-d", strtotime($tagdate))>date("Y-m-d", strtotime($davdate))||date("Y-m-d", strtotime($davdate))>date("Y-m-d")) ){ $ctctha=0; }
            else if(date("Y-m-d", strtotime($davdate))<=date("Y-m-d")&&(date("Y-m-d", strtotime($davdate))>date("Y-m-d", strtotime($tagdate))||date("Y-m-d", strtotime($tagdate))>date("Y-m-d")) ){ $ctctha=1; }
        }

        $accname = get_acc_name($promng);
        $fullnm = $ffname." ".$flname;
        if($perdob==""){$perdob="0000-00-00";}
        if($empdh==""){$empdh="0000-00-00";}
        if($asloc==""){$asloc="0000-00-00";}

    $cng = "";
    $sersql = "";
    $cmpsql = $link->query("SELECT * From `gy_employee` where `gy_emp_code`='$sibsid'");
    $cmprow=$cmpsql->fetch_array();
    if($cmprow['gy_emp_lname']!=$flname){ 
        $sersql.="`gy_emp_lname`='$flname',"; $cng.=cmpllog("Last Name", $cmprow['gy_emp_lname'], $flname); }
    if($cmprow['gy_emp_fname']!=$ffname){ 
        $sersql.="`gy_emp_fname`='$ffname',"; $cng.=cmpllog("First Name", $cmprow['gy_emp_fname'], $ffname); }
    if($cmprow['gy_emp_mname']!=$fmname){ 
        $sersql.="`gy_emp_mname`='$fmname',"; $cng.=cmpllog("Middle Name", $cmprow['gy_emp_mname'], $fmname); }
    if($cmprow['gy_gender']!=$perg){ 
        $sersql.="`gy_gender`='$perg',"; $cng.=cmpllog("Gender", $cmprow['gy_gender'], $perg); }
    if($cmprow['gy_dob']!=$perdob){ 
        $sersql.="`gy_dob`='$perdob',"; $cng.=cmpllog("Date of Birth", $cmprow['gy_dob'], $perdob); }
    if($cmprow['gy_civilstatus']!=$percs){ 
        $sersql.="`gy_civilstatus`='$percs',"; $cng.=cmpllog("Civil Status", $cmprow['gy_civilstatus'], $percs); }
    if($cmprow['gy_emp_hiredate']!=$empdh){ 
        $sersql.="`gy_emp_hiredate`='$empdh',"; $cng.=cmpllog("Date Hired", $cmprow['gy_emp_hiredate'], $empdh); }
    if($cmprow['gy_home_address']!=$ctctea){ 
        $sersql.="`gy_home_address`='$ctctea',"; $cng.=cmpllog("Homde Address", $cmprow['gy_home_address'], $ctctea); }
    if($cmprow['gy_emrg_address']!=$ctctma){ 
        $sersql.="`gy_emrg_address`='$ctctma',"; $cng.=cmpllog("Emergency Address", $cmprow['gy_emrg_address'], $ctctma); }
    if($cmprow['gy_mail_address']!=$ctctsa){ 
        $sersql.="`gy_mail_address`='$ctctsa',"; $cng.=cmpllog("Mailing Address", $cmprow['gy_mail_address'], $ctctsa); }
    if($cmprow['gy_second_address']!=$ctctgid){ 
        $sersql.="`gy_second_address`='$ctctgid',"; $cng.=cmpllog("Secondary Address", $cmprow['gy_second_address'], $ctctgid); }
    if($cmprow['gy_gov_id']!=$ctctgidn){ 
        $sersql.="`gy_gov_id`='$ctctgidn',"; $cng.=cmpllog("Government ID", $cmprow['gy_gov_id'], $ctctgidn); }
    if($cmprow['gy_gov_idnum']!=$ctctpe){ 
        $sersql.="`gy_gov_idnum`='$ctctpe',"; $cng.=cmpllog("Government ID#", $cmprow['gy_gov_idnum'], $ctctpe); }
    if($cmprow['gy_personal_email']!=$ctctcn){ 
        $sersql.="`gy_personal_email`='$ctctcn',"; $cng.=cmpllog("Personal Email", $cmprow['gy_personal_email'], $ctctcn); }
    if($cmprow['gy_contact_num']!=$ctctecp){ 
        $sersql.="`gy_contact_num`='$ctctecp',"; $cng.=cmpllog("Contact Number", $cmprow['gy_contact_num'], $ctctecp); }
    if($cmprow['gy_ecperson']!=$ctctecn){ 
        $sersql.="`gy_ecperson`='$ctctecn',"; $cng.=cmpllog("Emergency Contact Person", $cmprow['gy_ecperson'], $ctctecn); }
    if($cmprow['gy_ecnumber']!=$proacc){ 
        $sersql.="`gy_ecnumber`='$proacc',"; $cng.=cmpllog("Emergency Contact Number", $cmprow['gy_ecnumber'], $proacc); }
    if($cmprow['gy_acc_id']!=$promng){ 
        $sersql.="`gy_acc_id`='$promng',`gy_emp_account`='$accname',";
        $cng.=cmpllog("Account", $cmprow['gy_emp_account'], $accname); }
    if($cmprow['gy_assignedloc']!=$ctctha){ 
        $sersql.="`gy_assignedloc`='$ctctha',"; 
        $cng.=cmpllog("Assigned Location", cvtloc($cmprow['gy_assignedloc']), cvtloc($ctctha)); }
    if($cmprow['gy_emp_om']!=$proajd){ 
        $sersql.="`gy_emp_om`='$proajd',"; $cng.=cmpllog("Manager", get_emp_name($cmprow['gy_emp_om']), get_emp_name($proajd)); }
    if($cmprow['gy_accjoin']!=$asloc){ 
        $sersql.="`gy_accjoin`='$asloc',"; $cng.=cmpllog("Account join Date", $cmprow['gy_accjoin'], $asloc); }

    if($cmprow['gy_nhodate']!=$tcnhod){
        $sersql.="`gy_nhodate`='$tcnhod',"; $cng.=cmpllog("NHO Date", $cmprow['gy_nhodate'], $tcnhod); }
    if($cmprow['gy_fststartdate']!=$fstsd){
        $sersql.="`gy_fststartdate`='$fstsd',"; $cng.=cmpllog("FST Start Date", $cmprow['gy_fststartdate'], $fstsd); }
    if($cmprow['gy_fstenddate']!=$fsted){
        $sersql.="`gy_fstenddate`='$fsted',"; $cng.=cmpllog("FST End Date", $cmprow['gy_fstenddate'], $fsted); }
    if($cmprow['gy_certification']!=$certdt){
        $sersql.="`gy_certification`='$certdt',"; $cng.=cmpllog("Certification Date", $cmprow['gy_certification'], $certdt); }
    if($cmprow['gy_pststartdate']!=$pstsd){
        $sersql.="`gy_pststartdate`='$pstsd',"; $cng.=cmpllog("PST Start Date", $cmprow['gy_pststartdate'], $pstsd); }
    if($cmprow['gy_pstenddate']!=$psted){
        $sersql.="`gy_pstenddate`='$psted',"; $cng.=cmpllog("PST End Date", $cmprow['gy_pstenddate'], $psted); }
    if($cmprow['gy_fullgolivedate']!=$fugold){
        $sersql.="`gy_fullgolivedate`='$fugold',"; $cng.=cmpllog("Full Go Live Date", $cmprow['gy_fullgolivedate'], $fugold); }
    if($cmprow['gy_gradbaystartdate']!=$grbasd){
        $sersql.="`gy_gradbaystartdate`='$grbasd',"; $cng.=cmpllog("Grab bay Start Date", $cmprow['gy_gradbaystartdate'], $grbasd); }
    if($cmprow['gy_gradbayenddate']!=$grbaed){
        $sersql.="`gy_gradbayenddate`='$grbaed',"; $cng.=cmpllog("Grab bay End Date", $cmprow['gy_gradbayenddate'], $grbaed); }
    if($cmprow['gy_promotiondate']!=$promd){
        $sersql.="`gy_promotiondate`='$promd',"; $cng.=cmpllog("Promotion Date", $cmprow['gy_promotiondate'], $promd); }

    if($cmprow['gy_projempdate']!=$proemp){
        $sersql.="`gy_projempdate`='$proemp',"; $cng.=cmpllog("Project Employment Date", $cmprow['gy_projempdate'], $proemp); }
    if($cmprow['gy_probempdate']!=$probemp){
        $sersql.="`gy_probempdate`='$probemp',"; $cng.=cmpllog("Probationary Employment Date", $cmprow['gy_probempdate'], $probemp); }
    if($cmprow['gy_regempdate']!=$regemp){
        $sersql.="`gy_regempdate`='$regemp',"; $cng.=cmpllog("Regular Employment Date", $cmprow['gy_regempdate'], $regemp); }

    if($cmprow['gy_tagumdate']!=$tagdate){
        $sersql.="`gy_tagumdate`='$tagdate',"; $cng.=cmpllog("Tagum Date", $cmprow['gy_tagumdate'], $tagdate); }
    if($cmprow['gy_davaodate']!=$davdate){
        $sersql.="`gy_davaodate`='$davdate',"; $cng.=cmpllog("Davao Date", $cmprow['gy_davaodate'], $davdate); }
    if($cmprow['gy_hybriddate']!=$hybdate){
        $sersql.="`gy_hybriddate`='$hybdate',"; $cng.=cmpllog("Hybrid Date", $cmprow['gy_hybriddate'], $hybdate); }

$myfile = "../../../hr_logs/pdsupdatelogs.php";

if(!file_exists($myfile)){
    $handle = fopen($myfile, "w") or die("Unable to open file!");
    fwrite($handle, '');
    fclose($handle);
}
    clearstatcache();
$newmsg = '
{
"by":"'.$user_code.'",
"datetime":"'.$datenow.'",
"owner":"'.$sibsid.'",
"changes":"'.$cng.'"
}
';

    $fp = fopen($myfile,'r+');
    $filesize = filesize($myfile);
    $content = fread($fp, $filesize);
    if(strlen($content)>0){
        fseek($fp, -3, SEEK_END);
        fwrite($fp, ','.ltrim($newmsg).']');
    }else{
        fwrite($fp, '['.$newmsg.']');
    }
    fclose($fp);

        $uptsql=$link->query("UPDATE `gy_employee` SET ".$sersql."`gy_emp_fullname`='$fullnm',`gy_emp_lastedit`='$datenow',`gy_lastedit_by`='$user_code' Where `gy_emp_code`='$sibsid'");

        $link->query("UPDATE `gy_user` SET `gy_full_name`='$fullnm' Where `gy_user_code`='$sibsid'");
        $dbticket->query("UPDATE `vidaxl_masterlist` SET `mr_emp_name`='$fullnm' where `mr_emp_code`='$sibsid' ");
    
    if($uptsql){ echo "success"; }else{ echo "error"; }

    }
$dbticket->close();
$link->close();
?>