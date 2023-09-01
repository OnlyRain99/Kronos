<?php
    include '../config/connnk.php';
    
    $maxupd=$dbticket->query("SELECT MAX(last_update) AS ticktedate From `vidaxl_masterlist` LIMIT 1");
    $maxrow=$maxupd->fetch_array();
?>
<input type="hidden" id="newhidmaxrow" value="<?php echo $maxrow['ticktedate']; ?>">
<?php $dbticket->close(); ?>