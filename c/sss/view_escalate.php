<?php 
    include '../../config/conn.php';
    include '../../config/function.php';
    include 'session.php';

    $redirect = @$_GET['cd'];

    $title = "Review Request";

    $request=$link->query("SELECT `gy_esc_type`,`gy_esc_reason`,`gy_tracker_id`,`gy_tracker_date`,`gy_tracker_login`,`gy_tracker_breakout`,`gy_tracker_breakin`,`gy_tracker_logout`,`gy_tracker_wh`,`gy_tracker_bh`,`gy_tracker_ot`,`gy_esc_status`,`gy_esc_deny` From `gy_escalate` Where `gy_esc_id`='$redirect'");
    $req=$request->fetch_array();

    $tracker=$link->query("SELECT `gy_emp_code`,`gy_emp_fullname`,`gy_tracker_date`,`gy_tracker_login`,`gy_tracker_breakout`,`gy_tracker_breakin`,`gy_tracker_logout`,`gy_tracker_wh`,`gy_tracker_bh`,`gy_tracker_ot` From `gy_tracker` Where `gy_tracker_id`='".$req['gy_tracker_id']."'");
    $track=$tracker->fetch_array();

    function mybackg($current, $request){

        if ($current == $request) {
            $mybg = "mybg";
        }else{
            $mybg = "mybg_blue";
        }

        return $mybg;
    }
?>

<!DOCTYPE html>
<html lang="en">

<?php include 'head.php'; ?>

<style type="text/css">
    body{
        color: #000;
    }

    @media print{
        .no-print{
            display: none;
        }
    }
</style>

<body>

    <div class="page-wrapper">
        <div class="container">

            <!-- MAIN CONTENT-->
            <div class="main-content" style="padding: 20px;">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="title-1 m-b-25"><?php echo $title; ?></h2>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <strong class="card-title mb-3">Request: <span style="color: blue;"><?= escalate_type($req['gy_esc_type']) ?></span> <span class="pull-right"><a href="download?cd=<?= $redirect; ?>&type=time"><button type="button" class="btn btn-success btn-sm" title="click to download attachment ..."><i class="fa fa-download"></i> Download Escalate Form</button></a></span></strong>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive m-b-40">
                                                <table class="table table-bordered" style="font-family: 'Calibri'; font-size: 14px;">
                                                    <thead>
                                                        <tr class="mybg" style="text-transform: uppercase;">
                                                            <th style="padding: 5px;" class="text-center">requested for</th>
                                                            <th colspan="2" style="padding: 5px;" class="text-center"><?= $track['gy_emp_code']." - ".$track['gy_emp_fullname'] ?></th>
                                                        </tr>
                                                        <tr class="mybg">
                                                            <th style="padding: 5px;" class="text-center">REASON</th>
                                                            <th colspan="2" style="padding: 5px;" class="text-center"><i><?= $req['gy_esc_reason'] ?></i></th>
                                                        </tr>
                                                        <tr class="mybg" style="text-transform: uppercase;">
                                                            <th style="padding: 5px;" class="text-center">date</th>
                                                            <th colspan="2" style="padding: 5px;" class="text-center"><?= date("m/d/Y", strtotime($track['gy_tracker_date'])) ?></th>
                                                        </tr>
                                                        <tr class="mybg" style="text-transform: uppercase;">
                                                            <th style="padding: 5px;" class="text-center">Title</th>
                                                            <th style="padding: 5px;" class="text-center">Current</th>
                                                            <th style="padding: 5px; color: blue;" class="text-center">Request</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="<?= mybackg($track['gy_tracker_login'], $req['gy_tracker_login']) ?>">
                                                            <th style="padding: 5px;" class="text-center">Login</th>
                                                            <td style="padding: 5px;" class="text-center" title="<?= simpdate($track['gy_tracker_login']); ?>"><?= simptime($track['gy_tracker_login'], $track['gy_tracker_date']); ?></td>
                                                            <td style="padding: 5px; color: blue;" class="text-center" title="<?= simpdate($req['gy_tracker_login']); ?>"><?= simptime($req['gy_tracker_login'], $req['gy_tracker_date']); ?></td>
                                                        </tr>
                                                        <tr class="<?= mybackg($track['gy_tracker_logout'], $req['gy_tracker_logout']) ?>">
                                                            <th style="padding: 5px;" class="text-center">Logout</th>
                                                            <td style="padding: 5px;" class="text-center" title="<?= simpdate($track['gy_tracker_logout']); ?>"><?= simptime($track['gy_tracker_logout'], $track['gy_tracker_date']); ?></td>
                                                            <td style="padding: 5px; color: blue;" class="text-center" title="<?= simpdate($req['gy_tracker_logout']); ?>"><?= simptime($req['gy_tracker_logout'], $req['gy_tracker_date']); ?></td>
                                                        </tr>
                                                        <tr class="<?= mybackg($track['gy_tracker_breakout'], $req['gy_tracker_breakout']) ?>">
                                                            <th style="padding: 5px;" class="text-center">Break-Out</th>
                                                            <td style="padding: 5px;" class="text-center" title="<?= simpdate($track['gy_tracker_breakout']); ?>"><?= simptime($track['gy_tracker_breakout'], $track['gy_tracker_date']); ?></td>
                                                            <td style="padding: 5px; color: blue;" class="text-center" title="<?= simpdate($req['gy_tracker_breakout']); ?>"><?= simptime($req['gy_tracker_breakout'], $req['gy_tracker_date']); ?></td>
                                                        </tr>
                                                        <tr class="<?= mybackg($track['gy_tracker_breakin'], $req['gy_tracker_breakin']) ?>">
                                                            <th style="padding: 5px;" class="text-center">Break-In</th>
                                                            <td style="padding: 5px;" class="text-center" title="<?= simpdate($track['gy_tracker_breakin']); ?>"><?= simptime($track['gy_tracker_breakin'], $track['gy_tracker_date']); ?></td>
                                                            <td style="padding: 5px; color: blue;" class="text-center" title="<?= simpdate($req['gy_tracker_breakin']); ?>"><?= simptime($req['gy_tracker_breakin'], $req['gy_tracker_date']); ?></td>
                                                        </tr>
                                                        <?php if($req['gy_esc_status']==2 ){ ?>
                                                        <tr>
                                                            <th style="padding: 5px;" class="text-center">Reason for Rejection</th>
                                                            <td style="padding: 5px;" class="text-center" colspan="2"><?= $req['gy_esc_deny']; ?></td>
                                                        </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php include 'footer.php'; ?>
                </div>
            </div>
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
        </div>
    </div>

    <?php include 'scripts.php'; ?>

</body>

</html>
<!-- end document-->