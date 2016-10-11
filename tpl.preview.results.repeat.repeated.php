<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

//get variables
$sampleID=getValidatedVariable("sampleID");
$worksheetID=getValidatedVariable("worksheetID");

$formNumber=0;
$formNumber=getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","formNumber");

$sampleReferenceNumber=0;
$sampleReferenceNumber=getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","vlSampleID");

$worksheetReferenceNumber=0;
$worksheetReferenceNumber=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID'","worksheetReferenceNumber");

$patientID=0;
$patientID=getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","patientID");

$artNumber=0;
$artNumber=getDetailedTableInfo2("vl_patients","id='$patientID' limit 1","artNumber");

$otherID=0;
$otherID=getDetailedTableInfo2("vl_patients","id='$patientID' limit 1","otherID");

$worksheetReferenceNumber=0;
$worksheetReferenceNumber=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID'","worksheetReferenceNumber");

$factor=0;
$factor=getDetailedTableInfo2("vl_results_multiplicationfactor","worksheetID='$worksheetID' limit 1","factor");
if(!$factor) {
	$factor=1;
}

$sampleVLTestDate=0;
//$sampleVLTestDate=getDetailedTableInfo2("vl_results_abbott_runtimes","worksheetID='$worksheetID' limit 1","runCompletionTime");
$machineType=0;
if(getDetailedTableInfo2("vl_results_abbott","sampleID='$sampleReferenceNumber' and worksheetID='$worksheetID' limit 1","id")) {
	$machineType="abbott";
	if(!$sampleVLTestDate) {
		$sampleVLTestDate=getDetailedTableInfo2("vl_results_abbott","sampleID='$sampleReferenceNumber' and worksheetID='$worksheetID' limit 1","created");
	}
} elseif(getDetailedTableInfo2("vl_results_roche","SampleID='$sampleReferenceNumber' and worksheetID='$worksheetID' limit 1","id")) {
	$machineType="roche";
	if(!$sampleVLTestDate) {
		$sampleVLTestDate=getDetailedTableInfo2("vl_results_roche","SampleID='$sampleReferenceNumber' and worksheetID='$worksheetID' limit 1","created");
	}
} else {
	$machineType="rejected";
}

$result=0;
$result=getVLResult($machineType,$worksheetID,$sampleReferenceNumber,$factor);

//repeated details
$scheduledOn=0;
$scheduledOn=getDetailedTableInfo2("vl_logs_samplerepeats","sampleID='$sampleID' and oldWorksheetID='$worksheetID' limit 1","created");

$repeatedOn=0;
$repeatedOn=getDetailedTableInfo2("vl_logs_samplerepeats","sampleID='$sampleID' and oldWorksheetID='$worksheetID' limit 1","repeatedOn");

$withWorksheetID=0;
$withWorksheetID=getDetailedTableInfo2("vl_logs_samplerepeats","sampleID='$sampleID' and oldWorksheetID='$worksheetID' limit 1","withWorksheetID");

$repeatedFactor=0;
$repeatedFactor=getDetailedTableInfo2("vl_results_multiplicationfactor","worksheetID='$withWorksheetID' limit 1","factor");
if(!$repeatedFactor) {
	$repeatedFactor=1;
}

$repeatedResult=0;
$repeatedResult=getVLResult($machineType,$withWorksheetID,$sampleReferenceNumber,$repeatedFactor);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="0" class="vl">
        <tr>
            <td class="tab_active">FORM:&nbsp;<?=getDetailedTableInfo2("vl_samples","id='$sampleID'","formNumber")?></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td style="border:1px solid #CCCCFF; padding:20px">
	<table width="100%" border="0" class="vl">
    <tr>
        <td>
	        <div style="height: 280px; overflow: auto; padding:3px">
            	<table width="92%" border="0" class="vl">
            <tr>
                <td>
                  <fieldset style="width: 100%">
            <legend><strong>PATIENT/SAMPLE RESULTS</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
						<table width="100%" border="0" class="vl">
                        <tr>
                          <td width="30%" style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Patient&nbsp;ART&nbsp;#</td>
                          <td width="70%" style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=$artNumber?></td>
                        </tr>
                        <tr>
                          <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Patient&nbsp;Other&nbsp;ID</td>
                          <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=$otherID?></td>
                        </tr>
                        <tr>
                          <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Original&nbsp;Result</td>
                          <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=$result?></td>
                        </tr>
                        <tr>
                          <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Original&nbsp;Factor</td>
                          <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">x<?=$factor?></td>
                        </tr>
                        <tr>
                          <td style="padding:4px 0px">Original&nbsp;Test&nbsp;Date</td>
                          <td style="padding:4px 0px"><?=getFormattedDate($sampleVLTestDate)."&nbsp;at&nbsp;".getFormattedTimeLessS($sampleVLTestDate)?></td>
                        </tr>
                      </table>
                        </div>
                  </fieldset>
                </td>
            </tr>
            <tr>
                <td>
                  <fieldset style="width: 100%">
            <legend><strong>REPEAT SAMPLE DETAILS</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
						<table width="100%" border="0" class="vl">
                            <tr>
							<td width="30%" style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Scheduled&nbsp;for&nbsp;Repeat&nbsp;on</td>
                              <td width="70%" style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=getFormattedDate($scheduledOn)."&nbsp;at&nbsp;".getFormattedTimeLessS($scheduledOn)?></td>
                            </tr>
                            <tr>
							<td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Repeated&nbsp;on</td>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=getFormattedDate($repeatedOn)."&nbsp;at&nbsp;".getFormattedTimeLessS($repeatedOn)?></td>
                            </tr>
                            <tr>
							<td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Repeat&nbsp;Results</td>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=$repeatedResult?></td>
                            </tr>
                        <tr>
                          <td style="padding:4px 0px">Repeat&nbsp;Factor</td>
                          <td style="padding:4px 0px">x<?=$repeatedFactor?></td>
                        </tr>
                      </table>
                        </div>
                  </fieldset>
                </td>
            </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr>
    	<td><img src="/images/spacer.gif" width="10" height="10" /></td>
    </tr>
    <tr>
    	<td style="padding: 10px 0px 0px 0px; border-top: 1px solid #999999;"><a href="#rnd" onclick="closeMessage()" class="trailanalyticss">Close!</a></td>
    </tr>
	</table>
    </td>
  </tr>
</table>
