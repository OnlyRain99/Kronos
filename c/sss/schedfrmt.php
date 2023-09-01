<?php
$fileName = "schedule_format.csv"; 
$fields = array('SiBS ID (number only)', 'Name', 'Login', 'Logout', 'RD', 'Specific Days'); 
header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment; filename=\"$fileName\"");
$fp = fopen('php://output', 'w');
fputcsv($fp, $fields);
$lineData = array(100, "John Doe Cena", "8:00:00 AM", "5:00:00 PM", "Sun-Mon-Tue-Wed-Thu-Fri-Sat", "Sun-Mon-Tue-Wed-Thu-Fri-Sat");
fputcsv($fp, $lineData);

exit;
?>