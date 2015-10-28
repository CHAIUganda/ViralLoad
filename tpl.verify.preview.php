<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

//get variables
$id=getValidatedVariable("id");

$formNumber=0;
$formNumber=getDetailedTableInfo2("vl_samples","id='$id' limit 1","formNumber");

$batchNumber=0;
$batchNumber=getDetailedTableInfo2("vl_samples","id='$id' limit 1","batchNumber");

$facilityID=0;
$facilityID=getDetailedTableInfo2("vl_samples","id='$id' limit 1","facilityID");

$hubID=0;
$hubID=getDetailedTableInfo2("vl_samples","id='$id' limit 1","hubID");

$districtID=0;
$districtID=getDetailedTableInfo2("vl_samples","id='$id' limit 1","districtID");

$collectionDate=0;
$collectionDate=getDetailedTableInfo2("vl_samples","id='$id' limit 1","collectionDate");

$sampleType=0;
$sampleType=getDetailedTableInfo2("vl_samples","id='$id' limit 1","sampleTypeID");

$viralLoadTesting=0;
$viralLoadTesting=getDetailedTableInfo2("vl_samples","id='$id' limit 1","viralLoadTestingID");

$currentRegimen=0;
$currentRegimen=getDetailedTableInfo2("vl_samples","id='$id' limit 1","currentRegimen");

$treatmentInitiationDate=0;
$treatmentInitiationDate=getDetailedTableInfo2("vl_samples","id='$id' limit 1","treatmentInitiationDate");

$treatmentInitiation=0;
$treatmentInitiation=getDetailedTableInfo2("vl_samples","id='$id' limit 1","treatmentInitiationID");

$treatmentStatus=0;
$treatmentStatus=getDetailedTableInfo2("vl_samples","id='$id' limit 1","treatmentStatusID");

$failureReason=0;
$failureReason=getDetailedTableInfo2("vl_samples","id='$id' limit 1","reasonForFailureID");

$pregnant=0;
$pregnant=getDetailedTableInfo2("vl_samples","id='$id' limit 1","pregnant");

$breastfeeding=0;
$breastfeeding=getDetailedTableInfo2("vl_samples","id='$id' limit 1","breastfeeding");

$activeTB=0;
$activeTB=getDetailedTableInfo2("vl_samples","id='$id' limit 1","activeTBStatus");

$tbTreatmentPhase=0;
$tbTreatmentPhase=getDetailedTableInfo2("vl_samples","id='$id' limit 1","tbTreatmentPhaseID");

$arvAdherence=0;
$arvAdherence=getDetailedTableInfo2("vl_samples","id='$id' limit 1","arvAdherenceID");

$patientID=0;
$patientID=getDetailedTableInfo2("vl_samples","id='$id' limit 1","patientID");

$artNumber=0;
$artNumber=getDetailedTableInfo2("vl_patients","id='$patientID' limit 1","artNumber");

$otherID="";

$patientNames="";

$gender=0;
$gender=getDetailedTableInfo2("vl_patients","id='$patientID' limit 1","gender");

$dateOfBirth=0;
$dateOfBirth=getDetailedTableInfo2("vl_patients","id='$patientID' limit 1","dateOfBirth");

$patientPhone=0;
$patientPhone=getDetailedTableInfo2("vl_patients_phone","patientID='$patientID' order by created desc limit 1","phone");
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
            	<table width="100%" border="0" class="vl">
                <tr>
                    <td>
                      <fieldset style="width: 92%">
                <legend><strong>FORM CREDENTIALS</strong></legend>
                            <div style="padding:5px 0px 0px 0px">
                            <table width="100%" border="0" class="vl">
                                <tr>
                                  <td width="30%" style="padding:5px 0px; border-bottom: 1px solid #efefef"><strong>Form&nbsp;#&nbsp;<font class="vl_red">*</font></strong></td>
                                  <td width="70%" style="padding:5px 0px; border-bottom: 1px solid #efefef"><?=$formNumber?></td>
                                </tr>
                            <tr>
                              <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><strong>Batch&nbsp;#&nbsp;<font class="vl_red">*</font></strong></td>
                              <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><?=$batchNumber?></td>
                            </tr>
                            <tr>
                              <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><strong>Facility&nbsp;Name&nbsp;<font class="vl_red">*</font></strong></td>
                              <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><?=getDetailedTableInfo2("vl_facilities","id='$facilityID' limit 1","facility")?></td>
                            </tr>
                            <tr>
                              <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><strong>Hub</strong></td>
                              <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><?=getDetailedTableInfo2("vl_hubs","id='$hubID' limit 1","hub")?></td>
                            </tr>
                            <tr>
                              <td style="padding:5px 0px"><strong>District</strong></td>
                              <td style="padding:5px 0px"><?=getDetailedTableInfo2("vl_districts","id='$districtID' limit 1","district")?></td>
                            </tr>
                          </table>
                            </div>
                      </fieldset>
                    </td>
                </tr>
                <tr>
                    <td>
                      <fieldset style="width: 92%">
                <legend><strong>PATIENTS CREDENTIALS</strong></legend>
                            <div style="padding:5px 0px 0px 0px">
                              <table width="100%" border="0" class="vl">
                                <tr>
                                  <td width="30%" style="padding:5px 0px; border-bottom: 1px solid #efefef"><strong>ART&nbsp;Number&nbsp;<font class="vl_red">*</font></strong></td>
                                  <td width="70%" style="padding:5px 0px; border-bottom: 1px solid #efefef"><?=$artNumber?></td>
                                </tr>
                                <tr>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><strong>Other&nbsp;ID</strong></td>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><?=$otherID?></td>
                                </tr>
                                <tr>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><strong>Patient&nbsp;Names</strong></td>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><?=$patientNames?></td>
                                </tr>
                                <tr>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><strong>Gender&nbsp;<font class="vl_red">*</font></strong></td>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef">
                                        <?
                                        if($gender=="M") {
                                            echo "Male";
                                        } elseif($gender=="F") {
                                            echo "Female";
                                        }
                                        ?>
                                  </td>
                                </tr>
                                <tr>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><strong>Date&nbsp;of&nbsp;Birth</strong></td>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><?=($dateOfBirth?getFormattedDate($dateOfBirth):"&nbsp;")?></td>
                                </tr>
                                <tr>
                                  <td style="padding:5px 0px"><strong>Patient&nbsp;Phone</strong></td>
                                  <td style="padding:5px 0px"><?=$patientPhone?></td>
                                </tr>
                              </table>
                            </div>
                      </fieldset>
                    </td>
                </tr>
                <tr>
                    <td>
                      <fieldset style="width: 92%">
                        <legend><strong>PATIENT'S SAMPLE</strong></legend>
                            <div style="padding:5px 0px 0px 0px">
                              <table width="100%" border="0" class="vl">
                                <tr>
                                  <td width="30%" style="padding:5px 0px; border-bottom: 1px solid #efefef"><strong>Collection&nbsp;Date&nbsp;<font class="vl_red">*</font></strong></td>
                                  <td width="70%" style="padding:5px 0px; border-bottom: 1px solid #efefef"><?=getFormattedDate($collectionDate)?></td>
                                </tr>
                                <tr>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><strong>Sample&nbsp;Type&nbsp;<font class="vl_red">*</font></strong></td>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><?=getDetailedTableInfo2("vl_appendix_sampletype","id='$sampleType' limit 1","appendix")?></td>
                                </tr>
                                <tr>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><strong>Viral&nbsp;Load&nbsp;Testing&nbsp;<font class="vl_red">*</font></strong></td>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><?=getDetailedTableInfo2("vl_appendix_viralloadtesting","id='$viralLoadTesting' limit 1","appendix")?></td>
                                </tr>
                                <tr>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><strong>Current&nbsp;Regimen</strong></td>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><?=$currentRegimen?></td>
                                </tr>
                                <tr>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><strong>Treatment&nbsp;Initiation&nbsp;Date&nbsp;<font class="vl_red">*</font></strong></td>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><?=getFormattedDate($treatmentInitiationDate)?></td>
                                </tr>
                                <tr>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><strong>Treatment&nbsp;Initiation&nbsp;<font class="vl_red">*</font></strong></td>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><?=getDetailedTableInfo2("vl_appendix_treatmentinitiation","id='$treatmentInitiation' limit 1","appendix")?></td>
                                </tr>
                                <tr>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><strong>Treatment&nbsp;Status&nbsp;<font class="vl_red">*</font></strong></td>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><?=getDetailedTableInfo2("vl_appendix_treatmentstatus","id='$treatmentStatus' limit 1","appendix")?></td>
                                </tr>
                                <tr>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><strong>Failure&nbsp;Reason</strong></td>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><?=getDetailedTableInfo2("vl_appendix_failurereason","id='$failureReason' limit 1","appendix")?></td>
                                </tr>
                                <tr>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><strong>Pregnant</strong></td>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><?=$pregnant?></td>
                                </tr>
                                <tr>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><strong>Breastfeeding</strong></td>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><?=$breastfeeding?></td>
                                </tr>
                                <tr>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><strong>Active&nbsp;TB&nbsp;Status</strong></td>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><?=$activeTB?></td>
                                </tr>
                                <tr>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><strong>TB&nbsp;Treatment&nbsp;Phase</strong></td>
                                  <td style="padding:5px 0px; border-bottom: 1px solid #efefef"><?=getDetailedTableInfo2("vl_appendix_tbtreatmentphase","id='$tbTreatmentPhase' limit 1","appendix")?></td>
                                </tr>
                                <tr>
                                  <td style="padding:5px 0px"><strong>ARV&nbsp;Adherence</strong></td>
                                  <td style="padding:5px 0px"><?=getDetailedTableInfo2("vl_appendix_arvadherence","id='$arvAdherence' limit 1","appendix")?></td>
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
    	<td style="padding: 10px 0px 0px 0px; border-top: 1px solid #999999;"><a href="#rnd" onclick="closeMessage()" class="trailanalyticss">Close!</a> :: <a href="/verify/approve.reject/<?=$id?>/">Approve/Reject</a> :: <a href="/verify/find.and.edit/<?=$id?>/">Edit&nbsp;Sample</a></td>
    </tr>
	</table>
    </td>
  </tr>
</table>
