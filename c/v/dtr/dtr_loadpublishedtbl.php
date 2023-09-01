<?php
include '../../../config/conn.php';
include '../../../config/function.php';
include '../session.php';

$year = addslashes($_REQUEST['year']);
$month = addslashes($_REQUEST['month']);
$cutoff = addslashes($_REQUEST['cutoff']);

$i=0; $pblsharr = array(array());
$pshdsql=$link->query("SELECT * From `dtr_publish` Where `dtr_year`='$year' AND `dtr_month`='$month' AND `dtr_cutoff`='$cutoff' ORDER BY length(`gy_emp_code`) asc, `gy_emp_code` asc");
while($dtrrow=$pshdsql->fetch_array()){
	$pblsharr[$i][0]=$dtrrow['dtr_publish_id'];
	$pblsharr[$i][1]=$dtrrow['gy_emp_code'];
	$pblsharr[$i][2]=$dtrrow['dtr_noofhours'];
	$pblsharr[$i][3]=$dtrrow['dtr_lateut'];
	$pblsharr[$i][4]=$dtrrow['dtr_absences'];
	$pblsharr[$i][5]=$dtrrow['dtr_regot'];
	$pblsharr[$i][6]=$dtrrow['dtr_rdreg'];
	$pblsharr[$i][7]=$dtrrow['dtr_rdot'];
	$pblsharr[$i][8]=$dtrrow['dtr_shreg'];
	$pblsharr[$i][9]=$dtrrow['dtr_shot'];
	$pblsharr[$i][10]=$dtrrow['dtr_shrdreg'];
	$pblsharr[$i][11]=$dtrrow['dtr_shrdot'];
	$pblsharr[$i][12]=$dtrrow['dtr_lhreg'];
	$pblsharr[$i][13]=$dtrrow['dtr_lhot'];
	$pblsharr[$i][14]=$dtrrow['dtr_lhrdreg'];
	$pblsharr[$i][15]=$dtrrow['dtr_lhrdot'];
	$pblsharr[$i][16]=$dtrrow['dtr_ndreg'];
	$pblsharr[$i][17]=$dtrrow['dtr_ndregot'];
	$pblsharr[$i][18]=$dtrrow['dtr_ndrdreg'];
	$pblsharr[$i][19]=$dtrrow['dtr_ndrdot'];
	$pblsharr[$i][20]=$dtrrow['dtr_ndsh'];
	$pblsharr[$i][21]=$dtrrow['dtr_ndshot'];
	$pblsharr[$i][22]=$dtrrow['dtr_ndshrd'];
	$pblsharr[$i][23]=$dtrrow['dtr_ndshrdot'];
	$pblsharr[$i][24]=$dtrrow['dtr_ndlh'];
	$pblsharr[$i][25]=$dtrrow['dtr_ndlhot'];
	$pblsharr[$i][26]=$dtrrow['dtr_ndlhrd'];
	$pblsharr[$i][27]=$dtrrow['dtr_ndlhrdot'];
	$pblsharr[$i][28]=$dtrrow['dtr_cmpute'];
	$i++;
}

$link->close();
for($i1=0;$i1<$i;$i1++){ ?>
<tr>
   <td style="padding:0px;" ><button class="btn btn-outline-danger btn-sm btn-block" onclick="removepblshdtr(<?php echo $pblsharr[$i1][0]; ?>)" title="Remove"><i class="fa-solid fa-trash"></i></button></td>
   <td style="padding:0px;"><?php if($pblsharr[$i1][28]==0 || $pblsharr[$i1][28]==1 || $pblsharr[$i1][28]==2){?><a type="button" href="dtr/dtr_dlpublishdtr?fid=<?php echo $pblsharr[$i1][0]; ?>" target="_new" class="btn btn-outline-secondary btn-sm btn-block" title="Download DTR"><i class="fa-solid fa-file-arrow-down"></i></a><?php } ?></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center text-nowrap" id="pblshname_<?php echo $pblsharr[$i1][0]; ?>"><?php echo get_emp_name($pblsharr[$i1][1]); ?><!--Employee Name--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][2]; ?><!--No Of Hours--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][3]; ?><!--Late|UT--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][4]; ?><!--Absences--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][5]; ?><!--Reg|OT--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][6]; ?><!--RD|Reg--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][7]; ?><!--RD|OT--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][8]; ?><!--SH|Reg--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][9]; ?><!--SH|OT--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][10]; ?><!--SH|RD|Reg--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][11]; ?><!--SH|RD|OT--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][12]; ?><!--LH|Reg--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][13]; ?><!--LH|OT--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][14]; ?><!--LH|RD|Reg--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][15]; ?><!--LH|RD|OT--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][16]; ?><!--ND|Reg--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][17]; ?><!--ND|Reg|OT--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][18]; ?><!--ND|RD|Reg--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][19]; ?><!--ND|RD|OT--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][20]; ?><!--ND|SH--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][21]; ?><!--ND|SH|OT--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][22]; ?><!--ND|SH|RD--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][23]; ?><!--ND|SH|RD|OT--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][24]; ?><!--ND|LH--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][25]; ?><!--ND|LH|OT--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][26]; ?><!--ND|LH|RD--></td>
   <td style="padding-top:5px;padding-bottom:5px;" class="text-center"><?php echo $pblsharr[$i1][27]; ?><!--ND|LH|RD|OT--></td>
</tr>
<?php } ?>