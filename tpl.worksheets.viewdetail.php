<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

$worksheetID=validate($worksheetID);
$type=0;
$type=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' order by created limit 1","machineType");
$includeCalibrators=0;
$includeCalibrators=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","includeCalibrators");
$machineType=0;
$machineType=$type;
//worksheet type returned as an ID e.g 1, 2, 3
$worksheetType=0;
$worksheetType=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","worksheetType");
/*
* 8/Sept/14: 
* Hellen (hnansumba@gmail.com, CPHL) requested the Worksheet Name be removed given it is
* a duplicate of the Worksheet Reference Number
*/
//$worksheetName=0;
//$worksheetName=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","worksheetName");
$worksheetReferenceNumber=0;
$worksheetReferenceNumber=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","worksheetReferenceNumber");

$controls=0;
$calibrators=0;
if($type=="roche") {
	$controls=$default_numberControlsRoche;
	$columns=6;
	$calibrators=$default_numberCalibratorsRoche;
} elseif($type=="abbott") {
	$controls=$default_numberControlsAbbott;
	$columns=8;
	$calibrators=$default_numberCalibratorsAbbott;
}

//prepare the contents
$contents=array();

for($i=1;$i<=$controls;$i++) {
	if($i==1) {
    	$pc="<div align=\"center\" class=\"vl\">1</div>
				<div align=\"center\">Negative Control<br><strong>NC</strong></div>";
	} elseif($i==2) {
		$pc="<div align=\"center\" class=\"vl\">2</div>
				<div align=\"center\">Positive Control<br><strong>PC</strong></div>";
	} elseif($i==3) {
		$pc="<div align=\"center\" class=\"vl\">3</div>
				<div align=\"center\"><strong>HPC</strong></div>";
	}
	$contents[]=$pc;
}

//calibrators
if($includeCalibrators) {
	$totalContents=0;
	$totalContents=count($contents)+1;
	for($i=$totalContents;$i<=(($totalContents+$calibrators)-1);$i++) {
		$pc="<div align=\"center\" style=\"padding: 20px 0px 2px 0px\" class=\"vl\">$i</div>
					<div align=\"center\" style=\"padding: 2px 0px 20px 0px\"><strong>Calibrator</strong></div>";
		$contents[]=$pc;
	}
}

//how many samples are to be printed?
$query=0;
$query=mysqlquery("select distinct vl_samples_worksheet.sampleID from vl_samples_worksheet,vl_samples where 
						vl_samples_worksheet.sampleID=vl_samples.id and 
							vl_samples_worksheet.worksheetID='$worksheetID' 
								order by if(vl_samples.lrCategory='',1,0),vl_samples.lrCategory, if(vl_samples.lrEnvelopeNumber='',1,0),vl_samples.lrEnvelopeNumber, if(vl_samples.lrNumericID='',1,0),vl_samples.lrNumericID, vl_samples.formNumber");
if(mysqlnumrows($query)) {
	$i=count($contents);
	while($q=mysqlfetcharray($query)) {
		//controls
		$i+=1;
		//key variables
		$sampleID=0;
		$sampleID=$q["sampleID"];
		$patientID=0;
		$patientID=getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","patientID");
		$sampleNumber=0;
		$sampleNumber=getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","vlSampleID");
		$sampleType=0;
		$sampleType=getDetailedTableInfo2("vl_appendix_sampletype","id='".getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","sampleTypeID")."' limit 1","appendix");
		$locationID=0;
		$locationID=getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","lrCategory").getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","lrEnvelopeNumber").(getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","lrNumericID")?"/".getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","lrNumericID"):"");
		$formNumber=0;
		$formNumber=getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","formNumber");
		$patientART=0;
		$patientART=getDetailedTableInfo2("vl_patients","id='$patientID' limit 1","artNumber");
		$otherID=0;
		$otherID=getDetailedTableInfo2("vl_patients","id='$patientID' limit 1","otherID");
		//factor
		$factor=0;
		$factor=getDetailedTableInfo2("vl_results_multiplicationfactor","worksheetID='$worksheetID' limit 1","factor");
		if(!$factor) {
			$factor=1;
		}
		$result=0;
		$rawResult=0;
		if($type=="roche") {
			$rawResult=getDetailedTableInfo2("vl_results_roche","worksheetID='$worksheetID' and SampleID='$sampleNumber' order by created desc limit 1","Result");
		} elseif($type=="abbott") {
			$rawResult=getDetailedTableInfo2("vl_results_abbott","worksheetID='$worksheetID' and sampleID='$sampleNumber' order by created desc limit 1","result");
		}
		$result=getVLResult($type,$worksheetID,$sampleNumber,$factor);
		
		$contents[]="<div align=\"center\" class=\"vls\">$i</div>
						<div align=\"center\" class=\"vls\" style=\"padding:3px 0px 0px 0px\">Patient ART #: $patientART</div> 
						<div align=\"center\" class=\"vls\" style=\"padding:3px 0px 0px 0px\">Other ID: $otherID</div> 
						<div align=\"center\" class=\"vls\" style=\"padding:1px 0px 0px 0px\">Sample #: $sampleNumber</div> 
						".($locationID?"<div align=\"center\" class=\"vls\" style=\"padding:1px 0px 0px 0px\">Location ID: $locationID</div> ":"")."
						<div align=\"center\" class=\"vls\" style=\"padding:1px 0px 0px 0px\">Form #: $formNumber</div> 
						<div align=\"center\" style=\"padding:5px 0px\"><img src=\"/worksheets/image/".vlEncrypt($sampleNumber)."/\" /></div>
						".($rawResult?"<div align=\"center\" class=\"vls\" style=\"padding:5px 0px 0px 0px; border-top: 1px dashed #cccccc\">Result: $result</div>":"");

		//log printed status
		if(!getDetailedTableInfo2("vl_logs_worksheetsamplesviewed","sampleID='$sampleID' and worksheetID='$worksheetID' limit 1","id")) {
			mysqlquery("insert into vl_logs_worksheetsamplesviewed 
							(sampleID,worksheetID,created,createdby) 
							values 
							('$sampleID','$worksheetID','$datetime','$trailSessionUser')");
		}
	}
}
?>
<div class="vl" style="padding:0px 0px 10px 0px; border-bottom: 1px dashed #cccccc">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="vls">
      <!--
      <tr>
        <td style="padding:0px 0px 3px 0px"><strong>Machine Name:</strong> <?=$worksheetName?></td>
      </tr>
      -->
      <tr>
        <td style="padding:0px 0px 3px 0px"><strong>Reference Number:</strong> <?=$worksheetReferenceNumber?></td>
      </tr>
      <tr>
        <td style="padding:0px 0px 3px 0px"><strong>Machine Type:</strong> <?=($machineType=="abbott"?"Abbott":"Roche")?></td>
      </tr>
      <tr>
        <td style="padding:0px 0px 3px 0px"><strong>Worksheet Type:</strong> <?=getDetailedTableInfo2("vl_appendix_sampletype","id='$worksheetType' limit 1","appendix")?></td>
      </tr>
      <? if($machineType=="roche") { ?>
      <tr>
        <td style="padding:5px 0px 5px 0px; border-top: 1px dashed #cccccc"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
          <tr style="background-color:#e8e8e8">
            <td width="50%" align="center" style="padding: 5px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc"><strong>HI2CAP</strong></td>
            <td width="50%" align="center" style="padding: 5px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc; border-right: 1px solid #ccc"><strong>Bulk Lysis Buffer</strong></td>
          </tr>
          <tr>
            <td align="center" style="padding: 5px; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc"><?=($worksheetID?"<div>".getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","samplePrep")."</div><div class=\"vls_grey\"><strong>Exp&nbsp;Date:</strong>&nbsp;".getFormattedDateLessDay(getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","samplePrepExpiryDate"))."</div>":"&nbsp;")?></td>
            <td align="center" style="padding: 5px; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc; border-right: 1px solid #ccc"><?=($worksheetID?"<div>".getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","bulkLysisBuffer")."</div><div class=\"vls_grey\"><strong>Exp&nbsp;Date:</strong>&nbsp;".getFormattedDateLessDay(getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","bulkLysisBufferExpiryDate"))."</div>":"&nbsp;")?></td>
          </tr>
        </table></td>
      </tr>
      <? } elseif($machineType=="abbott") { ?>
      <tr>
        <td style="padding:5px 0px 5px 0px; border-top: 1px dashed #cccccc"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
          <tr style="background-color:#e8e8e8">
            <td width="20%" align="center" style="padding: 5px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc"><strong>Sample Prep</strong></td>
            <td width="20%" align="center" style="padding: 5px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc"><strong>Bulk Lysis Buffer</strong></td>
            <td width="20%" align="center" style="padding: 5px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc"><strong>Control</strong></td>
            <td width="20%" align="center" style="padding: 5px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc"><strong>Calibrator</strong></td>
            <td width="20%" align="center" style="padding: 5px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc; border-right: 1px solid #ccc"><strong>Amplification Kit</strong></td>
          </tr>
          <tr>
            <td align="center" style="padding: 5px; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc"><?=($worksheetID?"<div>".getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","samplePrep")."</div><div class=\"vls_grey\"><strong>Exp&nbsp;Date:</strong>&nbsp;".getFormattedDateLessDay(getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","samplePrepExpiryDate"))."</div>":"&nbsp;")?></td>
            <td align="center" style="padding: 5px; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc"><?=($worksheetID?"<div>".getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","bulkLysisBuffer")."</div><div class=\"vls_grey\"><strong>Exp&nbsp;Date:</strong>&nbsp;".getFormattedDateLessDay(getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","bulkLysisBufferExpiryDate"))."</div>":"&nbsp;")?></td>
            <td align="center" style="padding: 5px; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc"><?=($worksheetID?"<div>".getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","control")."</div><div class=\"vls_grey\"><strong>Exp&nbsp;Date:</strong>&nbsp;".getFormattedDateLessDay(getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","controlExpiryDate"))."</div>":"&nbsp;")?></td>
            <td align="center" style="padding: 5px; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc"><?=($worksheetID?"<div>".getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","calibrator")."</div><div class=\"vls_grey\"><strong>Exp&nbsp;Date:</strong>&nbsp;".getFormattedDateLessDay(getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","calibratorExpiryDate"))."</div>":"&nbsp;")?></td>
            <td align="center" style="padding: 5px; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc; border-right: 1px solid #ccc"><?=($worksheetID?"<div>".getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","amplificationKit")."</div><div class=\"vls_grey\"><strong>Exp&nbsp;Date:</strong>&nbsp;".getFormattedDateLessDay(getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","amplificationKitExpiryDate"))."</div>":"&nbsp;")?></td>
          </tr>
        </table></td>
      </tr>
      <? } ?>
    </table>
</div>
<div class="vl" style="padding:10px 0px 0px 0px">
    <table width="100%" border="0" class="vl">
				<?
				//abbott has 8 columns, so start by getting total number of rows
				$numberRows=0;
				$numberRows=ceil(count($contents)/$columns);
				$dataCounter=0;
				
				for($i=1;$i<=$numberRows;$i++) {
					//start row
					echo "<tr style=\"background:#dddddd\">";
					//populate the cells
					for($j=1;$j<=$columns;$j++) {
						if($dataCounter<count($contents)) {
							echo "<td bgcolor=\"#dddddd\" style=\"padding:5px\" valign=\"bottom\">$contents[$dataCounter]</td>";
						} else {
							echo "<td bgcolor=\"#dddddd\" style=\"padding:5px\" valign=\"bottom\">&nbsp;</td>";
						}
						$dataCounter+=1;
					}
					//end row
					echo "</tr>";
				}
				?>
    </table>
</div>
