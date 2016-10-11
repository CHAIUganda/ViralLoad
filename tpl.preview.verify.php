<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

//get variables
$id=getValidatedVariable("id");
$pg=getValidatedVariable("pg");

$formNumber=0;
$formNumber=getDetailedTableInfo2("vl_samples","id='$id' limit 1","formNumber");

$districtID=0;
$districtID=getDetailedTableInfo2("vl_samples","id='$id' limit 1","districtID");

$hubID=0;
$hubID=getDetailedTableInfo2("vl_samples","id='$id' limit 1","hubID");

$facilityID=0;
$facilityID=getDetailedTableInfo2("vl_samples","id='$id' limit 1","facilityID");

$currentRegimenID=0;
$currentRegimenID=getDetailedTableInfo2("vl_samples","id='$id' limit 1","currentRegimenID");

$pregnant=0;
$pregnant=getDetailedTableInfo2("vl_samples","id='$id' limit 1","pregnant");

$pregnantANCNumber=0;
$pregnantANCNumber=getDetailedTableInfo2("vl_samples","id='$id' limit 1","pregnantANCNumber");

$breastfeeding=0;
$breastfeeding=getDetailedTableInfo2("vl_samples","id='$id' limit 1","breastfeeding");

$activeTBStatus=0;
$activeTBStatus=getDetailedTableInfo2("vl_samples","id='$id' limit 1","activeTBStatus");

$collectionDate=0;
$collectionDate=getDetailedTableInfo2("vl_samples","id='$id' limit 1","collectionDate");

$receiptDate=0;
$receiptDate=getDetailedTableInfo2("vl_samples","id='$id' limit 1","receiptDate");

$treatmentLast6Months=0;
$treatmentLast6Months=getDetailedTableInfo2("vl_samples","id='$id' limit 1","treatmentLast6Months");

$treatmentInitiationDate=0;
$treatmentInitiationDate=getDetailedTableInfo2("vl_samples","id='$id' limit 1","treatmentInitiationDate");
if($treatmentInitiationDate=="0000-00-00") {
	$treatmentInitiationDate="";
}

$sampleTypeID=0;
$sampleTypeID=getDetailedTableInfo2("vl_samples","id='$id' limit 1","sampleTypeID");

$viralLoadTestingID=0;
$viralLoadTestingID=getDetailedTableInfo2("vl_samples","id='$id' limit 1","viralLoadTestingID");

$treatmentInitiationID=0;
$treatmentInitiationID=getDetailedTableInfo2("vl_samples","id='$id' limit 1","treatmentInitiationID");

$treatmentInitiationOther=0;
$treatmentInitiationOther=getDetailedTableInfo2("vl_samples","id='$id' limit 1","treatmentInitiationOther");

$treatmentStatusID=0;
$treatmentStatusID=getDetailedTableInfo2("vl_samples","id='$id' limit 1","treatmentStatusID");

$reasonForFailureID=0;
$reasonForFailureID=getDetailedTableInfo2("vl_samples","id='$id' limit 1","reasonForFailureID");

$tbTreatmentPhaseID=0;
$tbTreatmentPhaseID=getDetailedTableInfo2("vl_samples","id='$id' limit 1","tbTreatmentPhaseID");

$arvAdherenceID=0;
$arvAdherenceID=getDetailedTableInfo2("vl_samples","id='$id' limit 1","arvAdherenceID");

$vlTestingRoutineMonitoring=0;
$vlTestingRoutineMonitoring=getDetailedTableInfo2("vl_samples","id='$id' limit 1","vlTestingRoutineMonitoring");

$routineMonitoringLastVLDate=0;
$routineMonitoringLastVLDate=getDetailedTableInfo2("vl_samples","id='$id' limit 1","routineMonitoringLastVLDate");
if($routineMonitoringLastVLDate=="0000-00-00") {
	$routineMonitoringLastVLDate="";
}

$routineMonitoringValue=0;
$routineMonitoringValue=getDetailedTableInfo2("vl_samples","id='$id' limit 1","routineMonitoringValue");

$routineMonitoringSampleTypeID=0;
$routineMonitoringSampleTypeID=getDetailedTableInfo2("vl_samples","id='$id' limit 1","routineMonitoringSampleTypeID");

$vlTestingRepeatTesting=0;
$vlTestingRepeatTesting=getDetailedTableInfo2("vl_samples","id='$id' limit 1","vlTestingRepeatTesting");

$repeatVLTestLastVLDate=0;
$repeatVLTestLastVLDate=getDetailedTableInfo2("vl_samples","id='$id' limit 1","repeatVLTestLastVLDate");
if($repeatVLTestLastVLDate=="0000-00-00") {
	$repeatVLTestLastVLDate="";
}

$repeatVLTestValue=0;
$repeatVLTestValue=getDetailedTableInfo2("vl_samples","id='$id' limit 1","repeatVLTestValue");

$repeatVLTestSampleTypeID=0;
$repeatVLTestSampleTypeID=getDetailedTableInfo2("vl_samples","id='$id' limit 1","repeatVLTestSampleTypeID");

$vlTestingSuspectedTreatmentFailure=0;
$vlTestingSuspectedTreatmentFailure=getDetailedTableInfo2("vl_samples","id='$id' limit 1","vlTestingSuspectedTreatmentFailure");

$suspectedTreatmentFailureLastVLDate=0;
$suspectedTreatmentFailureLastVLDate=getDetailedTableInfo2("vl_samples","id='$id' limit 1","suspectedTreatmentFailureLastVLDate");
if($suspectedTreatmentFailureLastVLDate=="0000-00-00") {
	$suspectedTreatmentFailureLastVLDate="";
}

$suspectedTreatmentFailureValue=0;
$suspectedTreatmentFailureValue=getDetailedTableInfo2("vl_samples","id='$id' limit 1","suspectedTreatmentFailureValue");

$suspectedTreatmentFailureSampleTypeID=0;
$suspectedTreatmentFailureSampleTypeID=getDetailedTableInfo2("vl_samples","id='$id' limit 1","suspectedTreatmentFailureSampleTypeID");

$patientID=0;
$patientID=getDetailedTableInfo2("vl_samples","id='$id' limit 1","patientID");

$artNumber=0;
$artNumber=getDetailedTableInfo2("vl_patients","id='$patientID' limit 1","artNumber");

$otherID=0;
$otherID=getDetailedTableInfo2("vl_patients","id='$patientID' limit 1","otherID");

$gender=0;
$gender=getDetailedTableInfo2("vl_patients","id='$patientID' limit 1","gender");

$dateOfBirth=0;
$dateOfBirth=getDetailedTableInfo2("vl_patients","id='$patientID' limit 1","dateOfBirth");

$patientPhone=0;
$patientPhone=getDetailedTableInfo2("vl_patients_phone","patientID='$patientID' order by created desc limit 1","phone");

$created=0;
$created=getDetailedTableInfo2("vl_samples","id='$id' limit 1","created");

$createdby=0;
$createdby=getDetailedTableInfo2("vl_samples","id='$id' limit 1","createdby");
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="0" class="vl">
        <tr>
            <td class="tab_active">FORM:&nbsp;<?=getDetailedTableInfo2("vl_samples","id='$id'","formNumber")?></td>
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
            <legend><strong>FORM/FACILITY CREDENTIALS</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
						<table width="100%" border="0" class="vl">
                            <tr>
                              <td width="30%" style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Form&nbsp;#&nbsp;<font class="vl_red">*</font></td>
                              <td width="70%" style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=$formNumber?></td>
                            </tr>
                        <tr>
                          <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Facility&nbsp;Name&nbsp;<font class="vl_red">*</font></td>
                          <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=getDetailedTableInfo2("vl_facilities","id='$facilityID' limit 1","facility")?></td>
                        </tr>
                        <tr>
                          <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Hub</td>
                          <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=getDetailedTableInfo2("vl_hubs","id='$hubID' limit 1","hub")?></td>
                        </tr>
                        <tr>
                          <td style="padding:4px 0px">District</td>
                          <td style="padding:4px 0px"><?=getDetailedTableInfo2("vl_districts","id='$districtID' limit 1","district")?></td>
                        </tr>
                      </table>
                        </div>
                  </fieldset>
                </td>
            </tr>
            <tr>
                <td>
                  <fieldset style="width: 100%">
            <legend><strong>SAMPLE DETAILS</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
						<table width="100%" border="0" class="vl">
                            <tr>
							<td width="30%" style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Collection&nbsp;Date&nbsp;<font class="vl_red">*</font></td>
                              <td width="70%" style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=getFormattedDate($collectionDate)?></td>
                            </tr>
                            <tr>
							<td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Received&nbsp;at&nbsp;CPHL&nbsp;<font class="vl_red">*</font></td>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=getFormattedDate($receiptDate)?></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px">Sample&nbsp;Type&nbsp;<font class="vl_red">*</font></td>
                              <td style="padding:4px 0px"><?=getDetailedTableInfo2("vl_appendix_sampletype","id='$sampleTypeID' limit 1","appendix")?></td>
                        </tr>
                      </table>
                        </div>
                  </fieldset>
                </td>
            </tr>
            <tr>
                <td>
                  <fieldset style="width: 100%">
            <legend><strong>PATIENT INFORMATION</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
                          <table width="100%" border="0" class="vl">
                            <tr>
                              <td width="30%" style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">ART&nbsp;Number&nbsp;<font class="vl_red">*</font></td>
                              <td width="70%" style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=$artNumber?></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Other&nbsp;ID</td>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=$otherID?></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Gender&nbsp;<font class="vl_red">*</font></td>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=$gender?></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Date&nbsp;of&nbsp;Birth</td>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=getFormattedDate($dateOfBirth)?></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px">Patient&nbsp;Phone</td>
                              <td style="padding:4px 0px"><?=$patientPhone?></td>
                            </tr>
                          </table>
                        </div>
                  </fieldset>
                </td>
            </tr>
            <tr>
                <td>
                  <fieldset style="width: 100%">
                    <legend><strong>TREATMENT INFORMATION</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
                          <table width="100%" border="0" class="vl">
                            <tr>
                              <td width="30%" style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">
                              	Has&nbsp;Patient&nbsp;Been&nbsp;on&nbsp;Treatment
                                for&nbsp;the&nbsp;Last&nbsp;6&nbsp;Months&nbsp;<font class="vl_red">*</font>
                                </td>
                              <td width="70%" style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=$treatmentLast6Months?></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Treatment&nbsp;Initiation&nbsp;Date&nbsp;<font class="vl_red">*</font></td>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=getFormattedDate($treatmentInitiationDate)?></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Current&nbsp;Regimen&nbsp;<font class="vl_red">*</font></td>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=getDetailedTableInfo2("vl_appendix_regimen","id='$currentRegimenID' limit 1","appendix")?></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Indication&nbsp;for&nbsp;Treatment&nbsp;Initiation</td>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=getDetailedTableInfo2("vl_appendix_treatmentinitiation","id='$treatmentInitiationID' limit 1","appendix")?></td>
                            </tr>
                            <? if($treatmentInitiationOther) { ?>
                            <tr>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Other</td>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=$treatmentInitiationOther?></td>
                            </tr>
                            <? } ?>
                            <tr>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Patient&nbsp;Treatment&nbsp;Line&nbsp;<font class="vl_red">*</font></td>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=getDetailedTableInfo2("vl_appendix_treatmentstatus","id='$treatmentStatusID' limit 1","appendix")?></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Failure&nbsp;Reason</td>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=getDetailedTableInfo2("vl_appendix_failurereason","id='$reasonForFailureID' limit 1","appendix")?></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Viral&nbsp;Load&nbsp;Testing&nbsp;<font class="vl_red">*</font></td>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=getDetailedTableInfo2("vl_appendix_viralloadtesting","id='$viralLoadTestingID' limit 1","appendix")?></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Pregnant&nbsp;<font class="vl_red">*</td>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=$pregnant?></td>
                            </tr>
                            <? if($pregnantANCNumber) { ?>
                            <tr>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">ANC&nbsp;#</td>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=$pregnantANCNumber?></td>
                            </tr>
                            <? } ?>
                            <tr>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Breastfeeding&nbsp;<font class="vl_red">*</td>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=$breastfeeding?></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Active&nbsp;TB&nbsp;Status</td>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=$activeTBStatus?></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">TB&nbsp;Treatment&nbsp;Phase</td>
                              <td style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=getDetailedTableInfo2("vl_appendix_tbtreatmentphase","id='$tbTreatmentPhaseID' limit 1","appendix")?></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px">ARV&nbsp;Adherence</td>
                              <td style="padding:4px 0px"><?=getDetailedTableInfo2("vl_appendix_arvadherence","id='$arvAdherenceID' limit 1","appendix")?></td>
                            </tr>
                          </table>
                        </div>
                  </fieldset>
                </td>
            </tr>
            <tr>
              <td>
				<fieldset style="width: 100%">
					<legend><strong>INDICATION FOR VIRAL LOAD TESTING</strong></legend>
						<div style="padding:5px 0px 0px 0px">
						<table width="100%" border="0" class="vl">
                        	<? if($vlTestingRoutineMonitoring) { ?>
                            <tr>
                              <td style="padding:5px 0px; border-bottom: 1px dashed #dfe6e6"><strong>Routine Monitoring</strong></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px">Last&nbsp;Viral&nbsp;Load&nbsp;Date: <?=getFormattedDate($routineMonitoringLastVLDate)?></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px">Value: <?=$routineMonitoringValue?></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px">Sample&nbsp;Type: <?=getDetailedTableInfo2("vl_appendix_sampletype","id='$routineMonitoringSampleTypeID' limit 1","appendix")?></td>
                            </tr>
                            <? } ?>
                        	<? if($vlTestingRepeatTesting) { ?>
                            <tr>
                              <td style="padding:5px 0px; border-bottom: 1px dashed #dfe6e6"><strong>Repeat Viral Load Test after detectable viraemia and 6 months adherence counseling</strong></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px">Last&nbsp;Viral&nbsp;Load&nbsp;Date: <?=getFormattedDate($repeatVLTestLastVLDate)?></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px">Value: <?=$repeatVLTestValue?></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px">Sample&nbsp;Type: <?=getDetailedTableInfo2("vl_appendix_sampletype","id='$repeatVLTestSampleTypeID' limit 1","appendix")?></td>
                            </tr>
                            <? } ?>
                        	<? if($vlTestingSuspectedTreatmentFailure) { ?>
                            <tr>
                              <td style="padding:5px 0px; border-bottom: 1px dashed #dfe6e6"><strong>Suspected Treatment Failure</strong></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px">Last&nbsp;Viral&nbsp;Load&nbsp;Date: <?=getFormattedDate($suspectedTreatmentFailureLastVLDate)?></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px">Value: <?=$suspectedTreatmentFailureLastVLDate?></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px">Sample&nbsp;Type: <?=getDetailedTableInfo2("vl_appendix_sampletype","id='$suspectedTreatmentFailureSampleTypeID' limit 1","appendix")?></td>
                            </tr>
                            <? } ?>
						</table>
                        </div>
                </fieldset>
              </td>
            </tr>
            <tr>
                <td>
                  <fieldset style="width: 100%">
            <legend><strong>ENTRANT'S DETAILS</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
						<table width="100%" border="0" class="vl">
                            <tr>
							<td width="30%" style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6">Captured&nbsp;By</td>
                              <td width="70%" style="padding:4px 0px; border-bottom: 1px dashed #dfe6e6"><?=$createdby?></td>
                            </tr>
                            <tr>
                              <td style="padding:4px 0px">On</td>
                              <td style="padding:4px 0px"><?=getFormattedDate($created)." at ".getFormattedTimeLessS($created)?></td>
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
    	<td style="padding: 10px 0px 0px 0px; border-top: 1px solid #999999;"><a href="#rnd" onclick="closeMessage()" class="trailanalyticss">Close!</a><? if(!getDetailedTableInfo2("vl_samples_verify","sampleID='$id'","outcome")) { ?> :: <a href="/verify/approve.reject/<?=$id?>/<?=$pg?>/<?=($encryptedSample?"search/$encryptedSample/":"")?>">Approve/Reject</a><? } ?><? if(!$noedit) { ?> :: <a href="/verify/find.and.edit/<?=$id?>/<?=$pg?>/">Edit&nbsp;Sample</a><? } ?></td>
    </tr>
	</table>
    </td>
  </tr>
</table>
