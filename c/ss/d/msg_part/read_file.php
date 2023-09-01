<?php
$file = addslashes($_REQUEST['file']);
$typid = addslashes($_REQUEST['typid']);
$contents = '';
$myfile = "../../../../msg_logs/".$file."_".$typid.".php";
$contents = "";
if(file_exists($myfile)){
$handle = fopen($myfile, "rb") or die("Unable to open file!");
		$filesize = filesize($myfile);
		if($filesize > 0){ $contents = fread($handle, $filesize); }
}else{
	$handle = fopen($myfile, "w") or die("Unable to open file!");
	fwrite($handle, '');
}
	fclose($handle);
echo $contents;
?>