<?php
$file = addslashes($_REQUEST['file']);
$myfile = fopen("../../../msg_logs/".$file.".php", "w") or die("Unable to open file!");
fwrite($myfile, "");
fclose($myfile);
?>