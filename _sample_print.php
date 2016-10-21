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
switch ($wrksht_row['machineType']) {
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
	$result = end(explode("::", $row_override_result));
}else{
	$result = $machine_result;
}

$numerical_result = getNumericalResult($result);


$suppressed=isSuppressed2($numerical_result, $sample_type, $test_date);
switch ($suppressed) {
	case 'YES': // patient suppressed, according to the guidlines at that time
		$smiley="<img src='/images/smiley.smile.gif' />";
		$recommendation=getRecommendation($suppressed,$sampleVLTestDate,$sampleType);
		break;

	case 'NO': // patient suppressed, according to the guidlines at that time
		$smiley="<img src='/images/smiley.sad.gif' />";
		$recommendation=getRecommendation($suppressed,$sampleVLTestDate,$sampleType);					
		break;
	
	default:
		$smiley="<img src='/images/smiley.sad.gif' />";
		$recommendation="There is No Result Given. The Test Failed the Quality Control Criteria. We advise you send a a new sample.";
		break;
}

$signature = end(explode("/", $wrksht_row['signaturePATH']));
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
	<hr>
	viral load test results<br>
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
						<td class="print-val"><?=$row_district?></td>
					</tr>
				</table>
			</div>
			
		</div>
		<div class="col-xs-6">
			<div class="print-ttl">sample details</div>
			<div class="print-sect">
				<table>
					<tr>
						<td>Form #:</td>
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

	<div class="row">
		<div class="col-xs-6" >
			<div class="print-sect">
				<table>
					<tr>
						<td>ART Number: &nbsp;</td>
						<td class="print-val"><?=$row_artNumber ?></td>
					</tr>
					<tr>
						<td>Other ID:</td>
						<td class="print-val"><?=$row_otherID ?>&nbsp;</td>
					</tr>
					<tr>
						<td>Gender:</td>
						<td class="print-val-check"><?=MyHTML::boolean_draw($genders, $row_gender)?></td>
					</tr>
				</table>
			</div>
			
		</div>
		<div class="col-xs-6">
			<div class="print-sect">
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
			<div class="col-xs-4">Sample Collection Date: <?=getFormattedDateLessDay($row_collectionDate) ?></div>
			<div class="col-xs-4">Reception Date: <?=getFormattedDateLessDay($row_receiptDate) ?></div>
			<div class="col-xs-4">Test Date: <?=getFormattedDateLessDay($test_date) ?></div>
		</div>
		<hr>
		Repeat Test: <?=MyHTML::tabs(9)?><?=MyHTML::boolean_draw($yes_no, 2)?>
		<hr>
		Sample Rejected: &nbsp;<?=MyHTML::boolean_draw($yes_no, 2)?>
		<hr>
		if rejected Reason: 

	</div>
	<div class="print-ttl">viral load results</div>
	<div class="print-sect">
		<div class="row">
			<div class="col-xs-8">
				Method Used:  <?=MyHTML::tabs(12)?> <?=$method ?>
				<hr>
				Location ID:  <?=MyHTML::tabs(15)?>
				<?="$row_lrCategory$row_lrEnvelopeNumber/$row_lrNumericID" ?>
				<hr>
				Viral Load Testing #: &nbsp; <?=$row_vlSampleID ?>
				<hr>
				Result of Viral Load: &nbsp; <?=$result ?>
			</div>
			<div class="col-xs-4">
				<?=$smiley ?>
			</div>

		</div>
				

	</div>

	<div class="print-ttl">recommendations</div>
	Suggested Clinical Action based on National Guidelines:<br>

	<?=$recommendation ?>

	<div class="row">
		<div class="col-xs-2">
			Lab Technologist: 
		</div>
		<div class="col-xs-3">
			<img src="/images/signatures/<?=$signature ?>" height="50" width="100">
			<hr>
		</div>
		<div class="col-xs-2">
			Lab Manager: 
		</div>
		<div class="col-xs-3">
			<img src="/images/signatures/signature.14.gif" height="50" width="100">
			<hr>
		</div>
		<div class="col-xs-2"><img src="/images/qr_code.png" height="75" width="75"></div>
	</div>
</page>
<!-- </div> -->