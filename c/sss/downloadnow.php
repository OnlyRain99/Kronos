<?php
	//$form = addslashes($_REQUEST['form']);
	$form = @$_GET['form'];
	$formname = "";
	if($form == 1){ $formname = "Leave_of_Absence.pdf"; }
	else if($form == 2){ $formname = "COA.xlsx"; }
	else if($form == 3){ $formname = "OBT.pdf"; }
	else if($form == 4 || $form == 5){ $formname = "Early_Out.pdf"; }
	else if($form == 6){ $formname = "Sched_Adjustment.xlsx"; }
	else if($form == 7){ $formname = "OT_Form.xlsx"; }
	
	$file = '../../kronos_file_formats/'.$formname;
	if(!file_exists($file)){
	    die('file not found');
	} else {
	    header("Cache-Control: public");
	    header("Content-Description: File Transfer");
	    header("Content-Disposition: attachment; filename=$formname");
	    header("Content-Type: application/zip");
	    header("Content-Transfer-Encoding: binary");

	    readfile($file);
	}
?>