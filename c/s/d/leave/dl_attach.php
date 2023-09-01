<?php
    include '../../../../config/conn.php';
    include '../../../../config/function.php';
    include '../session.php';

    $fid = @$_GET['fid'];

    $attachment=$link->query("SELECT `gy_leave_attachment` From `gy_leave` Where `gy_leave_id`='$fid'");
    $attach=$attachment->fetch_array();
    $file = '../../../../kronos_file_store/'.$attach['gy_leave_attachment'];
    if(!file_exists($file)){ // file does not exist
        die('file not found');
    } else {
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=".$attach['gy_leave_attachment']."");
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: binary");
        // read the file from disk
        readfile($file);
    }

$link->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Downloading ...</title>
</head>
<body>

</body>
</html>