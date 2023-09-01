<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
date_default_timezone_set('Asia/Taipei');
$time = date("F j, Y, g:i A");
echo "data: {$time}\n\n";
flush();
?>