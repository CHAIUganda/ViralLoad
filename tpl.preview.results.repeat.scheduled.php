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

$machineType=0;
if(getDetailedTableInfo2("vl_results_abbott","sampleID='$sampleReferenceNumber' and worksheetID='$worksheetID' limit 1","id")) {
	$machineType="abbott";
} elseif(getDetailedTableInfo2("vl_results_roche","SampleID='$sampleReferenceNumber' and worksheetID='$worksheetID' limit 1","id")) {
	$machineType="roche";
} else {
	$machineType="rejected";
}

$result=0;
$result=getVLResult($machineType,$worksheetID,$sampleReferenceNumber,$factor);

//dispatch details
$scheduledDate=0;
$scheduledDate=getDetailedTableInfo2("vl_logs_samplerepeats","sampleID='$sampleID' and oldWorksheetID='$worksheetID' limit 1","created");

$by=0;
$by=getDetailedTableInfo2("vl_logs_samplerepeats","sampleID='$sampleID' and oldWorksheetID='$worksheetID' limit 1","createdby");
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
                          <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Result</td>
                          <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=$result?></td>
                        </tr>
                        <tr>
                          <td style="padding:4px 0px">Factor</td>
                          <td style="padding:4px 0px">x<?=$factor?></td>
                        </tr>
                      </table>
                        </div>
                  </fieldset>
                </td>
            </tr>
            <tr>
                <td>
                  <fieldset style="width: 100%">
            <legend><strong>REPEAT SCHEDULE DETAILS</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
						<table width="100%" border="0" class="vl">
                            <tr>
							<td width="30%" style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Scheduled&nbsp;on</td>
                              <td width="70%" style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=getFormattedDate($scheduledDate)?></td>
                            </tr>
                            <tr>
							<td style="padding:4px 0px">By</td>
                              <td style="padding:4px 0px"><?=$by?></td>
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
