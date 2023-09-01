<?php 
date_default_timezone_set('Asia/Taipei');
    include '../../config/conn.php';
    include '../../config/function.php';
    //include '../../config/connnk.php';
    include 'session.php';

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

    if(isset($_POST['id_gy'])){
        $id_gy = words($_POST['id_gy']); //hidden emp id
        $empsi = words($_POST['empsi']); //SiBS ID
        $flname = words($_POST['flname']); // last name
        $ffname = words($_POST['ffname']); // first name
        $fmname = words($_POST['fmname']); // m name
        $perg = ucfirst(strtolower(words($_POST['perg']))); // Gender
        $perdob = words($_POST['perdob']);// Date of Birth
        $percs = ucfirst(strtolower(words($_POST['percs']))); //Civil Status
        $empdh = words($_POST['empdh']); //Date Hired
        $ctctha = words($_POST['ctctha']); //Home Address
        $ctctea = words($_POST['ctctea']); //Emergency Address
        $ctctma = words($_POST['ctctma']); //Mailing Address
        $ctctsa = words($_POST['ctctsa']); //Secondary Address
        $ctctgid = words($_POST['ctctgid']); //Government ID
        $ctctgidn = words($_POST['ctctgidn']); //Government ID #
        $ctctpe = words($_POST['ctctpe']); //Personal Email
        $ctctcn = words($_POST['ctctcn']); //Contact Number
        $ctctecp = words($_POST['ctctecp']); //Emergency Contact Person
        $ctctecn = words($_POST['ctctecn']); //Emergency Contact Number
        $proacc = words($_POST['proacc']); // Account
        $asloc = words($_POST['asloc']); //Assigned Location
        $promng = words($_POST['promng']); //Manager
        $proajd = words($_POST['proajd']); //Account Join Date
        $accname = get_acc_name($proacc); // account name
        $fullnm = $ffname." ".$flname; // fullname

       $tcnhod = fblnk(words($_POST['tcnhod'])); //NHO Date
       $fstsd = fblnk(words($_POST['fstsd'])); //FST Start Date
       $fsted = fblnk(words($_POST['fsted'])); //FST End Date
       $certdt = fblnk(words($_POST['certdt'])); //Certification Date
       $pstsd = fblnk(words($_POST['pstsd'])); //PST Start Date
       $psted = fblnk(words($_POST['psted'])); //PST End Date
       $fugold = fblnk(words($_POST['fugold'])); //Full Go Live Date
       $grbasd = fblnk(words($_POST['grbasd'])); //Grab bay Start Date
       $grbaed = fblnk(words($_POST['grbaed'])); //Grab bay End Date
       $promd = fblnk(words($_POST['promd'])); //Promotion Date

        $proemp = fblnk(words($_POST['proemp']));
        $probemp = fblnk(words($_POST['probemp']));
        $regemp = fblnk(words($_POST['regemp']));

        $tagdate = fblnk(words($_POST['tagdate']));
        $davdate = fblnk(words($_POST['davdate']));
        $hybdate = fblnk(words($_POST['hybdate']));
        if($tagdate!="0000-00-00" && $davdate=="0000-00-00"){
            if(date("Y-m-d", strtotime($tagdate))<=date("Y-m-d")){ $asloc=0; }
        }else if($tagdate=="0000-00-00" && $davdate!="0000-00-00"){
            if(date("Y-m-d", strtotime($davdate))<=date("Y-m-d")){ $asloc=1; }
        }else if($tagdate!="0000-00-00" && $davdate!="0000-00-00"){
            if(date("Y-m-d", strtotime($tagdate))<=date("Y-m-d")&&(date("Y-m-d", strtotime($tagdate))>date("Y-m-d", strtotime($davdate))||date("Y-m-d", strtotime($davdate))>date("Y-m-d")) ){ $asloc=0; }
            else if(date("Y-m-d", strtotime($davdate))<=date("Y-m-d")&&(date("Y-m-d", strtotime($davdate))>date("Y-m-d", strtotime($tagdate))||date("Y-m-d", strtotime($tagdate))>date("Y-m-d")) ){ $asloc=1; }
		}

		if($perdob==""){ $perdob="0000-00-00"; }
		if($empdh==""){ $empdh="0000-00-00"; }
		if($proajd==""){ $proajd="0000-00-00"; }

        $empsql = $link->query("SELECT * From `gy_employee` where `gy_emp_id`='$id_gy' LIMIT 1");
        $emprow = $empsql->fetch_array();
        $empcount=$empsql->num_rows;
        if($empcount>0){ $empsi = $emprow['gy_emp_code']; }

		//cmpr
    $cng = "";
    $sersql = "";
		if($emprow['gy_emp_lname']!=$flname){
            $sersql.="`gy_emp_lname`='$flname',"; $cng.=cmpllog("Last Name", $emprow['gy_emp_lname'], $flname); }
        if($emprow['gy_emp_fname']!=$ffname){
            $sersql.="`gy_emp_fname`='$ffname',"; $cng.=cmpllog("last Name", $emprow['gy_emp_fname'], $ffname); }
        if($emprow['gy_emp_mname']!=$fmname){
            $sersql.="`gy_emp_mname`='$fmname',"; $cng.=cmpllog("Middle Name", $emprow['gy_emp_mname'], $fmname); }
        if($emprow['gy_gender']!=$perg){
            $sersql.="`gy_gender`='$perg',"; $cng.=cmpllog("Gender", $emprow['gy_gender'], $perg); }
        if($emprow['gy_dob']!=$perdob){
            $sersql.="`gy_dob`='$perdob',"; $cng.=cmpllog("Date of Birth", $emprow['gy_dob'], $perdob); }
        if($emprow['gy_civilstatus']!=$percs){
            $sersql.="`gy_civilstatus`='$percs',"; $cng.=cmpllog("Civil Status", $emprow['gy_civilstatus'], $percs); }
        if($emprow['gy_emp_hiredate']!=$empdh){
            $sersql.="`gy_emp_hiredate`='$empdh',"; $cng.=cmpllog("Date Hired", $emprow['gy_emp_hiredate'], $empdh); }
        if($emprow['gy_home_address']!=$ctctha){
            $sersql.="`gy_home_address`='$ctctha',"; $cng.=cmpllog("Homde Address", $emprow['gy_home_address'], $ctctha); }
        if($emprow['gy_emrg_address']!=$ctctea){ 
            $sersql.="`gy_emrg_address`='$ctctea',"; $cng.=cmpllog("Emergency Address", $emprow['gy_emrg_address'], $ctctea); }
        if($emprow['gy_mail_address']!=$ctctma){ 
            $sersql.="`gy_mail_address`='$ctctma',"; $cng.=cmpllog("Mailing Address", $emprow['gy_mail_address'], $ctctma); }
        if($emprow['gy_second_address']!=$ctctsa){ 
            $sersql.="`gy_second_address`='$ctctsa',"; $cng.=cmpllog("Secondary Address", $emprow['gy_second_address'], $ctctsa); }
        if($emprow['gy_gov_id']!=$ctctgid){
            $sersql.="`gy_gov_id`='$ctctgid',"; $cng.=cmpllog("Government ID", $emprow['gy_gov_id'], $ctctgid); }
        if($emprow['gy_gov_idnum']!=$ctctgidn){
            $sersql.="`gy_gov_idnum`='$ctctgidn',"; $cng.=cmpllog("Government ID#", $emprow['gy_gov_idnum'], $ctctgidn); }
        if($emprow['gy_personal_email']!=$ctctpe){ 
            $sersql.="`gy_personal_email`='$ctctpe',"; $cng.=cmpllog("Personal Email", $emprow['gy_personal_email'], $ctctpe); }
        if($emprow['gy_contact_num']!=$ctctcn){ 
            $sersql.="`gy_contact_num`='$ctctcn',"; $cng.=cmpllog("Contact Number", $emprow['gy_contact_num'], $ctctcn); }
        if($emprow['gy_ecperson']!=$ctctecp){
            $sersql.="`gy_ecperson`='$ctctecp',"; $cng.=cmpllog("Emergency Contact Person", $emprow['gy_ecperson'], $ctctecp); }
        if($emprow['gy_ecnumber']!=$ctctecn){
            $sersql.="`gy_ecnumber`='$ctctecn',"; $cng.=cmpllog("Emergency Contact Number", $emprow['gy_ecnumber'], $ctctecn); }
        if($emprow['gy_acc_id']!=$proacc){
            $sersql.="`gy_acc_id`='$proacc',`gy_emp_account`='$accname',";
            $cng.=cmpllog("Account", $emprow['gy_emp_account'], $accname); }
        if($emprow['gy_assignedloc']!=$asloc){
            $sersql.="`gy_assignedloc`='$asloc',";
            $cng.=cmpllog("Assigned Location", cvtloc($emprow['gy_assignedloc']), cvtloc($asloc)); }
        if($emprow['gy_emp_om']!=$promng){ 
            $sersql.="`gy_emp_om`='$promng',"; 
            $cng.=cmpllog("Manager", get_emp_name($emprow['gy_emp_om']), get_emp_name($promng)); }
        if($emprow['gy_accjoin']!=$proajd){ 
            $sersql.="`gy_accjoin`='$proajd',"; $cng.=cmpllog("Account join Date", $emprow['gy_accjoin'], $proajd); }

        if($emprow['gy_nhodate']!=$tcnhod){
            $sersql.="`gy_nhodate`='$tcnhod',"; $cng.=cmpllog("NHO Date", $emprow['gy_nhodate'], $tcnhod); }
        if($emprow['gy_fststartdate']!=$fstsd){
            $sersql.="`gy_fststartdate`='$fstsd',"; $cng.=cmpllog("FST Start Date", $emprow['gy_fststartdate'], $fstsd); }
        if($emprow['gy_fstenddate']!=$fsted){
            $sersql.="`gy_fstenddate`='$fsted',"; $cng.=cmpllog("FST End Date", $emprow['gy_fstenddate'], $fsted); }
        if($emprow['gy_certification']!=$certdt){
            $sersql.="`gy_certification`='$certdt',"; $cng.=cmpllog("Certification Date", $emprow['gy_certification'], $certdt); }
        if($emprow['gy_pststartdate']!=$pstsd){
            $sersql.="`gy_pststartdate`='$pstsd',"; $cng.=cmpllog("PST Start Date", $emprow['gy_pststartdate'], $pstsd); }
        if($emprow['gy_pstenddate']!=$psted){
            $sersql.="`gy_pstenddate`='$psted',"; $cng.=cmpllog("PST End Date", $emprow['gy_pstenddate'], $psted); }
        if($emprow['gy_fullgolivedate']!=$fugold){
            $sersql.="`gy_fullgolivedate`='$fugold',"; $cng.=cmpllog("Full Go Live Date", $emprow['gy_fullgolivedate'], $fugold); }
        if($emprow['gy_gradbaystartdate']!=$grbasd){
            $sersql.="`gy_gradbaystartdate`='$grbasd',"; $cng.=cmpllog("Grab bay Start Date", $emprow['gy_gradbaystartdate'], $grbasd); }
        if($emprow['gy_gradbayenddate']!=$grbaed){
            $sersql.="`gy_gradbayenddate`='$grbaed',"; $cng.=cmpllog("Grab bay End Date", $emprow['gy_gradbayenddate'], $grbaed); }
        if($emprow['gy_promotiondate']!=$promd){
            $sersql.="`gy_promotiondate`='$promd',"; $cng.=cmpllog("Promotion Date", $emprow['gy_promotiondate'], $promd); }

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

$myfile = "../../hr_logs/pdsupdatelogs.php";

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
"owner":"'.$empsi.'",
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

        $uptsql=$link->query("UPDATE `gy_employee` SET ".$sersql."`gy_emp_fullname`='$fullnm',`gy_emp_lastedit`='$datenow',`gy_lastedit_by`='$user_code' Where `gy_emp_id`='$id_gy'");

        $link->query("UPDATE `gy_user` SET `gy_full_name`='$fullnm' Where `gy_user_code`='$empsi'");
        //$dbticket->query("UPDATE `vidaxl_masterlist` SET `mr_emp_name`='$fullnm' where `mr_emp_code`='$empsi' ");

    if($uptsql){ header("location: stats?note=update"); }else{ header("location: stats?note=error"); }
    }else{ header("location: stats?note=error"); }

//$dbticket->close();
$link->close();
?>