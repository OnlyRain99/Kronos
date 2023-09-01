<?php
include '../../../config/conn.php';
include '../../../config/function.php';
include '../session.php';

$year = @$_GET['year'];
$month = @$_GET['month'];
$cutoff = @$_GET['cutoff'];
if($cutoff==1){ $cfname="First"; }else if($cutoff==2){ $cfname="Second"; }

$fileName = "DTR_".$year."_".date("F", strtotime($month))."_".$cfname."_CutOff_"."-".date('Ymdhis').".csv"; 
$fields = array('EmployeeNumber','EmployeeName','NoOfHours','UnderTime','Absenses','RegularOT','RestDay','RestDayOT','SpecialHoliday','SpecialHolidayOT','SpecialHolidayRestDay','SpecialHolidayRestDayOT','LegalHoliday','LegalHolidayOT','LegalHolidayRestday','LegalHolidayRestdayOT','NightDiffRegular','NightDiffRegularOT','NightDiffRestDay','NightDiffRestDayOT','NightDiffSpecialHoliday','NightDiffSpecialHolidayOT','NightDiffSpecialHolidayRestDay','NightDiffSpecialHolidayRestDayOT','NightDiffLegalHoliday','NightDiffLegalHolidayOT','NightDiffLegalHolidayRestDay','NightDiffLegalHolidayRestDayOT');
header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment; filename=\"$fileName\"");
$fp = fopen('php://output', 'w');
fputcsv($fp, $fields);

function filterData(&$str){
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
}

if($year>0 && $month>0 && $month<13 && ($cutoff==1 || $cutoff==2)){
	$dlpshdsql=$link->query("SELECT * From `dtr_publish` Where `dtr_year`='$year' AND `dtr_month`='$month' AND `dtr_cutoff`='$cutoff'");
	while($dldtrrow=$dlpshdsql->fetch_array()){
		$lineData = array('sib-'.$dldtrrow['gy_emp_code'], get_emp_name($dldtrrow['gy_emp_code']), $dldtrrow['dtr_noofhours'], $dldtrrow['dtr_lateut'], $dldtrrow['dtr_absences'], $dldtrrow['dtr_regot'], $dldtrrow['dtr_rdreg'], $dldtrrow['dtr_rdot'], $dldtrrow['dtr_shreg'], $dldtrrow['dtr_shot'], $dldtrrow['dtr_shrdreg'], $dldtrrow['dtr_shrdot'], $dldtrrow['dtr_lhreg'], $dldtrrow['dtr_lhot'], $dldtrrow['dtr_lhrdreg'], $dldtrrow['dtr_lhrdot'], $dldtrrow['dtr_ndreg'], $dldtrrow['dtr_ndregot'], $dldtrrow['dtr_ndrdreg'], $dldtrrow['dtr_ndrdot'], $dldtrrow['dtr_ndsh'], $dldtrrow['dtr_ndshot'], $dldtrrow['dtr_ndshrd'], $dldtrrow['dtr_ndshrdot'], $dldtrrow['dtr_ndlh'], $dldtrrow['dtr_ndlhot'], $dldtrrow['dtr_ndlhrd'], $dldtrrow['dtr_ndlhrdot']);
		array_walk($lineData, 'filterData');
		fputcsv($fp, $lineData);
	}
}

$link->close(); exit; ?>