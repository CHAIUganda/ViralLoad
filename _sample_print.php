<?php 
$smpl_types = array(1=>'DBS', 2=>'Plasma');
$sample_type = $row_sampleTypeID == 1? 'DBS' : 'Plasma';
$genders = array(
	'Female'=>'Female',
	'Male'=>'Male',
	'Left Blank'=>'Left Blank',
	);
$yes_no = array(1=>"Yes", 2=>"No");

$method="";
$machine_result = "";
$test_date = "";
switch ($machine_type) {
	case 'abbott':
		$method = "Abbott Real time HIV-1 PCR";
		$mr = end(explode("::", $row_abbott_result));
		$mr_arr = explode("|||", $mr);
		$machine_result = isset($mr_arr[0])?$mr_arr[0]:"";
		$test_date = isset($mr_arr[1])?$mr_arr[1]:"";
		break;

	case 'roche':
		$method = "HIV-1 RNA PCR Roche";
		$mr = end(explode("::", $row_roche_result));
		$mr_arr = explode("|||", $mr);
		$machine_result = isset($mr_arr[0])?$mr_arr[0]:"";
		$test_date = isset($mr_arr[1])?$mr_arr[1]:"";
		break;
	
	default:
		$method = "";
		$machine_result = "";
		$test_date = "";
		break;
}

$result = "";
if(!empty($row_override_result)){
	$or = end(explode("::", $row_override_result));
	$or = explode("|||", $or);
	$result = $or[0];
	$test_date = "";
}else{
	$result = $machine_result;
}

$result = getVLNumericResult($result, $machine_type, $factor);

$numerical_result = getNumericalResult($result);

$suppressed=isSuppressed2($numerical_result, $sample_type, $test_date);
switch ($suppressed) {
	case 'YES': // patient suppressed, according to the guidlines at that time
		$smiley="smiley.smile.gif";
		$recommendation=getRecommendation($suppressed,$sampleVLTestDate,$sampleType);
		break;

	case 'NO': // patient suppressed, according to the guidlines at that time
		$smiley="smiley.sad.gif";
		$recommendation=getRecommendation($suppressed,$sampleVLTestDate,$sampleType);					
		break;
	
	default:
		$smiley="smiley.sad.gif";
		$recommendation="There is No Result Given. The Test Failed the Quality Control Criteria. We advise you send a a new sample.";
		break;
}

$location_id = "$row_lrCategory$row_lrEnvelopeNumber/$row_lrNumericID";

$signature = end(explode("/", $signature_path));

$now_s = strtotime(date("Y-m-d"));

$repeated = !empty($row_repeated)?1:2;

$rejected = $row_verify_outcome=="Rejected"?1:2;
 ?>
<page size="A4">
<!-- <div class="print-container"> -->
	<div class="print-header">
		<img src="/images/uganda.emblem.gif">
		<div class="print-header-moh">
			ministry of health uganda<br>
			national aids control program<br>
		</div>

	central public health laboratories<br>
	
	<u>viral load test results</u><br>
	</div>
	<div class="row">
		<div class="col-xs-6" >
			<div class="print-ttl">facility details</div>
			<div class="print-sect">
				<table>
					<tr>
						<td>Name:</td>
						<td class="print-val"><?=$row_facility?></td>
					</tr>
					<tr>
						<td>District:</td>
						<td class="print-val"><?=$row_district?> | Hub: <?=$row_hub_name?></td>
					</tr>
				</table>
			</div>
			
		</div>
		<div class="col-xs-6">
			<div class="print-ttl">sample details</div>
			<div class="print-sect">
				<table>
					<tr>
						<td>Form #: </td>
						<td class="print-val"><?=$row_formNumber?></td>
					</tr>
					<tr>
						<td>Sample Type: </td>
						<td class="print-val-check"> &nbsp; <?=MyHTML::boolean_draw($smpl_types, $row_sampleTypeID)?></td>
					</tr>
				</table>
			</div>
		</div>

	</div>

	<div class="print-ttl">patient information</div>
	<div class="print-sect">
		<div class="row">
			<div class="col-xs-6" >				
				<table>
					<tr>
						<td>ART Number: &nbsp;</td>
						<td class="print-val"><?=$row_artNumber ?></td>
					</tr>
					<tr>
						<td>Other ID:</td>
						<td class="print-val"><?=$row_otherID ?></td>
					</tr>
					<tr>
						<td>Gender:</td>
						<td class="print-val-check"><?=MyHTML::boolean_draw($genders, $row_gender)?></td>
					</tr>
				</table>
				
			</div>
			<div class="col-xs-6">
				
				<table>
					<tr>
						<td>Date of Birth:</td>
						<td class="print-val"><?=getFormattedDateLessDay($row_dateOfBirth)?></td>
					</tr>
					<tr>
						<td>Phone Number:</td>
						<td class="print-val-"><?=$phone?></td>
					</tr>
				</table>
				
			</div>

		</div>
	</div>
	<div class="print-ttl">sample test information</div>
	<div class="print-sect">
		<div class="row">
			<div class="col-xs-6">
				
				<table>
					<tr>
						<td>Sample Collection Date: &nbsp; </td>
						<td class="print-val"><?=getFormattedDateLessDay($row_collectionDate) ?></td>
					</tr>
					<tr>
						<td>Reception Date: &nbsp; </td>
						<td class="print-val"><?=getFormattedDateLessDay($row_receiptDate) ?></td>
					</tr>
					<tr>
						<td>Test Date: &nbsp; </td>
						<td class="print-val"><?=getFormattedDateLessDay($test_date) ?></td>
					</tr>

				</table>
				
			</div>

			<div class="col-xs-6">
				
				<table>
					<tr>
						<td>Repeat Test:  &nbsp; </td>
						<td><?=MyHTML::boolean_draw($yes_no, $repeated)?></td>
					</tr>
					<tr>
						<td>Sample Rejected:  &nbsp; </td>
						<td><?=MyHTML::boolean_draw($yes_no, $rejected)?></td>
					</tr>
				</table>
				
			</div>

		</div>
			If rejected Reason: <?=$row_rejection_reason ?>
	</div>
	<?php if ($row_verify_outcome!="Rejected"){ ?>
	<div class="print-ttl">viral load results</div>
	<div class="print-sect">
		<div class="row">
			<div class="col-xs-9">
				<table colspan="2">
					<tr>
						<td width="40%">Method Used: </td>
						<td ><?=$method ?></td>
					</tr>

					<tr>
						<td>Location ID: </td>
						<td ><?=$location_id ?></td>
					</tr>

					<tr>
						<td>Viral Load Testing #: </td>
						<td ><?=$row_vlSampleID ?></td>
					</tr>

					<tr>
						<td valign="top">Result of Viral Load: </td>
						<td ><?=$result ?></td>
					</tr>
				</table>		
				
			</div>
			<div class="col-xs-3">
				<img src="/images/<?=$smiley ?>" height="150" width="150">
			</div>

		</div>		 				

	</div>


	<div class="print-ttl">recommendations</div>
	<div class="print-sect">
		Suggested Clinical Action based on National Guidelines:<br>
		<div style="margin-left:10px"><?=$recommendation ?></div>
	</div>
	<?php } ?>

	<br>
	<div class="row">
		<?php if ($row_verify_outcome!="Rejected"){ ?>
		<div class="col-xs-2">
			Lab Technologist: 
		</div>
		<div class="col-xs-3">
			<img src="/images/signatures/<?=$signature ?>" height="50" width="100">
			<hr>
		</div>
		<?php } ?>
		<div class="col-xs-2">
			Lab Manager: 
		</div>
		<div class="col-xs-3">
			<img src="/images/signatures/signature.14.gif" height="50" width="100">
			<hr>
		</div>
		<?php if ($row_verify_outcome!="Rejected"){ ?>
		<div class="col-xs-2">
			<div class="qrcode-output" value="<?="VL,$location_id,$suppressed,$now_s" ?>"></div>
		</div>
		<?php } ?>
	</div>
</page>
<!-- </div> -->