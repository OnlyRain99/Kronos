<?php  
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $get_announce=$link->query("SELECT * From `gy_announce` Where date(`gy_ann_end`) >= '$onlydate' Order By `gy_ann_date` DESC");
    if(mysqli_num_rows($get_announce) > 0){
    while ($ann=$get_announce->fetch_array()) {

        $alert = $ann['gy_ann_type'];

        if ($alert == "success") {
            $options = "Simple";
        }else if ($alert == "warning") {
            $options = "Non-critical";
        }else if ($alert == "danger") {
            $options = "Critical";
        }else if ($alert == "info") {
            $options = "Updates";
        }else{
            $options = "unknown";
        }

        if ($ann['gy_ann_caption'] == "") {
            $captions = "<i>No caption</i>";
        }else{
            $captions = $ann['gy_ann_caption'];
        }

        if (check_confirm($ann['gy_ann_id'], $user_code) == "disabled") {
            $seen = "<small title='".get_seen_date($ann['gy_ann_id'], $user_code)."'><i class='fa fa-check'></i> Confirmed</small>";
        }else{
            $seen = "";
        }

?>
    <div class="alert alert-<?= $alert; ?>" role="alert">
        <span class="badge badge-pill badge-<?= $alert; ?>"><?= date("M d g:i A", strtotime($ann['gy_ann_date'])); ?>
        </span>
        <span class="badge badge-pill badge-<?= $alert; ?>"><?= getuserfullname($ann['gy_ann_by']); ?>
        </span>
        <?= wordlimit($ann['gy_ann_caption'], 60); ?> <a href="content?cd=<?= $ann['gy_ann_id'] ?>" target="_blank" title="click to read whole announcement ..." style="color: blue;" class="alert-link">read more</a>

        <span class="pull-right"><?= $seen; ?></span>
    </div>

<?php }} $link->close(); ?>