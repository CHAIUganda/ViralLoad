<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}
?>
<? if($saved) { ?>
<table width="100%" border="0" class="vl">
  <tr>
    <td class="vl_success">Item Saved!</td>
  </tr>
  <tr>
    <td><img src="images/spacer.gif" width="3" height="3" /></td>
  </tr>
</table>
<? } ?>
<?
		switch($option) {
            case add:
				//check for missing variables
				$error=0;
				$error="";
				
				//process
				if(!$error) {
					//file uploaded
					//has file been uploaded
					if(is_uploaded_file($_FILES['userfile']['tmp_name'])) {
						//filename
						$fileOriginalName=0;
						$fileOriginalName=$_FILES['userfile']['name'];
						$fileOriginalName=addslashes($fileOriginalName);
						//temp name
						$tmpName=0;
						$tmpName=$_FILES['userfile']['tmp_name'];
						//size
						$fileSize=0;
						$fileSize=$_FILES['userfile']['size'];
						//type
						$fileType=0;
						$fileType=$_FILES['userfile']['type'];
						//extension
						$extension=0;
						$extension=ext($fileOriginalName);
						//check to ensure this is xls
						if($extension!="xls") {
							$error.="<br>Incorrect file extension '.$extension'. Try saving the document as an 'xls' file i.e. excel 97 - 2003";
						}
						/*
						if($fileSize>$default_maxUploadSize) {
							$error.="<br>File size is greater than the accepted ".number_format((float)$default_maxUploadSize/1000)." KB. Please reduce the size of the file then attempt to upload it again.";
						}
						*/
						if(!$error) {
							//added/modified
							$added=0;
							$modified=0;
							//prcess the file
							$excelData->read($_FILES['userfile']['tmp_name']);
							
							//process data within the first sheet
							for($i=2;$i<=$excelData->sheets[0]['numRows'];$i++) {
								//Form Number
								$formNumber=0;
								$formNumber=trim($excelData->sheets[0]['cells'][$i][1]);
								
								//Sample ID
								$vlSampleID=0;
								$vlSampleID=trim($excelData->sheets[0]['cells'][$i][3]);
								
								//Facility
								$facilityID=0;
								//$facilityID=logNewFacility(trim($excelData->sheets[0]['cells'][$i][4]),trim($excelData->sheets[0]['cells'][$i][5]));
								$facilityID=logUpdateFacility(trim($excelData->sheets[0]['cells'][$i][3]),trim($excelData->sheets[0]['cells'][$i][4]));
								
								//District
								$districtID=0;
								$districtID=logNewDistrict(trim($excelData->sheets[0]['cells'][$i][5]));

								//Hub
								$hubID=0;
								$hubID=logNewHub(trim($excelData->sheets[0]['cells'][$i][6]),$facilityID);

								//Date of Collection; deduct by 1 date for reasons I'm yet to figure out
								$collectionDate=0;
								$collectionDate=subtractFromDate(getFormattedDateCRBExcel(trim($excelData->sheets[0]['cells'][$i][7])),1);
								
								//Sample Type
								$sampleTypeID=0;
								$sampleTypeID=getDetailedTableInfo2("vl_appendix_sampletype","lower(appendix)='".strtolower(trim($excelData->sheets[0]['cells'][$i][8]))."' limit 1","id");
								
								/*
								//patientID
								$patientID=0;
								$patientID=getDetailedTableInfo2("vl_samples","vlSampleID='".trim($excelData->sheets[0]['cells'][$i][3])."' limit 1","patientID");
								
								//Patient ART
								updatePatientData($patientID,"artNumber",trim($excelData->sheets[0]['cells'][$i][9]),$collectionDate);

								//Patient OtherID
								updatePatientData($patientID,"otherID",trim($excelData->sheets[0]['cells'][$i][10]),$collectionDate);

								//Gender
								updatePatientData($patientID,"gender",trim($excelData->sheets[0]['cells'][$i][11]),$collectionDate);

								//Age (Years)
								//updatePatientData($patientID,"dateOfBirth",trim($excelData->sheets[0]['cells'][$i][12]),$collectionDate);

								//Phone Number
								logPatientPhone($patientID,trim($excelData->sheets[0]['cells'][$i][13]));
								
								//Has Patient Been on treatment for at least 6 months
								$treatmentLast6Months=0;
								$treatmentLast6Months=trim($excelData->sheets[0]['cells'][$i][14]);
								*/

								//Date of Treatment Initiation
								$treatmentInitiationDate=0;
								$treatmentInitiationDate=getRawFormattedDateLessDay(trim($excelData->sheets[0]['cells'][$i][15]));

								//Current Regimen
								$currentRegimenID=0;
								$currentRegimenID=logNewCurrentRegimen(trim($excelData->sheets[0]['cells'][$i][16]));

								//Indication for Treatment Initiation
								//$treatmentInitiationID=0;
								//$treatmentInitiationID=logNewTreatmentInitiation(trim($excelData->sheets[0]['cells'][$i][17]));

								//Which Treatment Line is Patient on
								$treatmentStatusID=0;
								$treatmentStatusID=logNewTreatmentStatus(trim($excelData->sheets[0]['cells'][$i][18]));

								/*
								//Reason for Failure
								$reasonForFailureID=0;
								$reasonForFailureID=logNewReasonForFailure(trim($excelData->sheets[0]['cells'][$i][19]));

								//Is Patient Pregnant
								$pregnant=0;
								$pregnant=trim($excelData->sheets[0]['cells'][$i][20]);

								//ANC Number
								$pregnantANCNumber=0;
								$pregnantANCNumber=trim($excelData->sheets[0]['cells'][$i][21]);

								//Is Patient Breastfeeding
								$breastfeeding=0;
								$breastfeeding=trim($excelData->sheets[0]['cells'][$i][22]);

								//Patient has Active TB
								$activeTBStatus=0;
								$activeTBStatus=trim($excelData->sheets[0]['cells'][$i][23]);

								//If Yes are they on
								$tbTreatmentPhaseID=0;
								$tbTreatmentPhaseID=logNewTBTreatmentPhase(trim($excelData->sheets[0]['cells'][$i][24]));

								//ARV Adherence
								$arvAdherenceID=0;
								$arvAdherenceID=logNewARVAdherence(trim($excelData->sheets[0]['cells'][$i][25]));

								//Routine Monitoring
								$vlTestingRoutineMonitoring=0;
								$vlTestingRoutineMonitoring=(trim($excelData->sheets[0]['cells'][$i][26])=="Yes"?"1":"0");

								//Last Viral Load Date
								$routineMonitoringLastVLDate=0;
								$routineMonitoringLastVLDate=(trim($excelData->sheets[0]['cells'][$i][27])?getRawFormattedDateLessDay(trim($excelData->sheets[0]['cells'][$i][27])):"");

								//Value
								$routineMonitoringValue=0;
								$routineMonitoringValue=trim($excelData->sheets[0]['cells'][$i][28]);

								//Sample Type
								$routineMonitoringSampleTypeID=0;
								$routineMonitoringSampleTypeID=getDetailedTableInfo2("vl_appendix_sampletype","lower(appendix)='".strtolower(trim($excelData->sheets[0]['cells'][$i][29]))."' limit 1","id");

								//Repeat Viral Load Test after Suspected Treatment Failure adherence counseling
								$vlTestingRepeatTesting=0;
								$vlTestingRepeatTesting=(trim($excelData->sheets[0]['cells'][$i][30])=="Yes"?"1":"0");

								//Last Viral Load Date
								$repeatVLTestLastVLDate=0;
								$repeatVLTestLastVLDate=(trim($excelData->sheets[0]['cells'][$i][31])?getRawFormattedDateLessDay(trim($excelData->sheets[0]['cells'][$i][31])):"");

								//Value
								$repeatVLTestValue=0;
								$repeatVLTestValue=trim($excelData->sheets[0]['cells'][$i][32]);

								//Sample Type
								$repeatVLTestSampleTypeID=0;
								$repeatVLTestSampleTypeID=getDetailedTableInfo2("vl_appendix_sampletype","lower(appendix)='".strtolower(trim($excelData->sheets[0]['cells'][$i][33]))."' limit 1","id");

								//Suspected Treatment Failure
								$vlTestingSuspectedTreatmentFailure=0;
								$vlTestingSuspectedTreatmentFailure=(trim($excelData->sheets[0]['cells'][$i][34])=="Yes"?"1":"0");

								//Last Viral Load Date
								$suspectedTreatmentFailureLastVLDate=0;
								$suspectedTreatmentFailureLastVLDate=(trim($excelData->sheets[0]['cells'][$i][35])?getRawFormattedDateLessDay(trim($excelData->sheets[0]['cells'][$i][35])):"");

								//Value
								$suspectedTreatmentFailureValue=0;
								$suspectedTreatmentFailureValue=trim($excelData->sheets[0]['cells'][$i][36]);

								//Sample Type
								$suspectedTreatmentFailureSampleTypeID=0;
								$suspectedTreatmentFailureSampleTypeID=getDetailedTableInfo2("vl_appendix_sampletype","lower(appendix)='".strtolower(trim($excelData->sheets[0]['cells'][$i][37]))."' limit 1","id");
								*/
								
								//manage duplicates
								if($formNumber && $vlSampleID) {
									//get the sample ID
									$sampleID=0;
									$sampleID=getDetailedTableInfo2("vl_samples","vlSampleID='$vlSampleID' limit 1","id");
									
									//log table change
									logTableChange("vl_samples","districtID",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","districtID"),$districtID);
									logTableChange("vl_samples","hubID",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","hubID"),$hubID);
									logTableChange("vl_samples","facilityID",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","facilityID"),$facilityID);
									logTableChange("vl_samples","sampleTypeID",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","sampleTypeID"),$sampleTypeID);
									logTableChange("vl_samples","collectionDate",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","collectionDate"),$collectionDate);
									logTableChange("vl_samples","treatmentInitiationDate",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","treatmentInitiationDate"),$treatmentInitiationDate);
									logTableChange("vl_samples","currentRegimenID",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","currentRegimenID"),$currentRegimenID);
									logTableChange("vl_samples","treatmentStatusID",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","treatmentStatusID"),$treatmentStatusID);

									/*
									logTableChange("vl_samples","pregnant",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","pregnant"),$pregnant);
									logTableChange("vl_samples","pregnantANCNumber",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","pregnantANCNumber"),$pregnantANCNumber);
									logTableChange("vl_samples","breastfeeding",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","breastfeeding"),$breastfeeding);
									logTableChange("vl_samples","activeTBStatus",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","activeTBStatus"),$activeTBStatus);
									logTableChange("vl_samples","receiptDate",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","receiptDate"),$receiptDate);
									logTableChange("vl_samples","treatmentLast6Months",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","treatmentLast6Months"),$treatmentLast6Months);
									logTableChange("vl_samples","viralLoadTestingID",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","viralLoadTestingID"),$viralLoadTestingID);
									logTableChange("vl_samples","treatmentInitiationID",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","treatmentInitiationID"),$treatmentInitiationID);
									logTableChange("vl_samples","treatmentInitiationOther",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","treatmentInitiationOther"),$treatmentInitiationOther);
									logTableChange("vl_samples","reasonForFailureID",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","reasonForFailureID"),$reasonForFailureID);
									logTableChange("vl_samples","tbTreatmentPhaseID",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","tbTreatmentPhaseID"),$tbTreatmentPhaseID);
									logTableChange("vl_samples","arvAdherenceID",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","arvAdherenceID"),$arvAdherenceID);
									logTableChange("vl_samples","vlTestingRoutineMonitoring",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","vlTestingRoutineMonitoring"),$vlTestingRoutineMonitoring);
									logTableChange("vl_samples","routineMonitoringLastVLDate",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","routineMonitoringLastVLDate"),$routineMonitoringLastVLDate);
									logTableChange("vl_samples","routineMonitoringValue",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","routineMonitoringValue"),$routineMonitoringValue);
									logTableChange("vl_samples","routineMonitoringSampleTypeID",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","routineMonitoringSampleTypeID"),$routineMonitoringSampleTypeID);
									logTableChange("vl_samples","vlTestingRepeatTesting",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","vlTestingRepeatTesting"),$vlTestingRepeatTesting);
									logTableChange("vl_samples","repeatVLTestLastVLDate",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","repeatVLTestLastVLDate"),$repeatVLTestLastVLDate);
									logTableChange("vl_samples","repeatVLTestValue",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","repeatVLTestValue"),$repeatVLTestValue);
									logTableChange("vl_samples","repeatVLTestSampleTypeID",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","repeatVLTestSampleTypeID"),$repeatVLTestSampleTypeID);
									logTableChange("vl_samples","vlTestingSuspectedTreatmentFailure",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","vlTestingSuspectedTreatmentFailure"),$vlTestingSuspectedTreatmentFailure);
									logTableChange("vl_samples","suspectedTreatmentFailureLastVLDate",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","suspectedTreatmentFailureLastVLDate"),$suspectedTreatmentFailureLastVLDate);
									logTableChange("vl_samples","suspectedTreatmentFailureValue",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","suspectedTreatmentFailureValue"),$suspectedTreatmentFailureValue);
									logTableChange("vl_samples","suspectedTreatmentFailureSampleTypeID",$sampleID,getDetailedTableInfo2("vl_samples","id='$sampleID'","suspectedTreatmentFailureSampleTypeID"),$suspectedTreatmentFailureSampleTypeID);
									*/
									
									//update
									/*
									mysqlquery("update vl_samples set 
													districtID='$districtID',
													hubID='$hubID',
													facilityID='$facilityID',
													currentRegimenID='$currentRegimenID',
													pregnant='$pregnant',
													pregnantANCNumber='$pregnantANCNumber',
													breastfeeding='$breastfeeding',
													activeTBStatus='$activeTBStatus',
													collectionDate='$collectionDate',
													receiptDate='$receiptDate',
													treatmentLast6Months='$treatmentLast6Months',
													treatmentInitiationDate='$treatmentInitiationDate',
													sampleTypeID='$sampleTypeID',
													viralLoadTestingID='$viralLoadTestingID',
													treatmentInitiationID='$treatmentInitiationID',
													treatmentInitiationOther='$treatmentInitiationOther',
													treatmentStatusID='$treatmentStatusID',
													reasonForFailureID='$reasonForFailureID',
													tbTreatmentPhaseID='$tbTreatmentPhaseID',
													arvAdherenceID='$arvAdherenceID',
													vlTestingRoutineMonitoring='$vlTestingRoutineMonitoring',
													routineMonitoringLastVLDate='$routineMonitoringLastVLDate',
													routineMonitoringValue='$routineMonitoringValue',
													routineMonitoringSampleTypeID='$routineMonitoringSampleTypeID',
													vlTestingRepeatTesting='$vlTestingRepeatTesting',
													repeatVLTestLastVLDate='$repeatVLTestLastVLDate',
													repeatVLTestValue='$repeatVLTestValue',
													repeatVLTestSampleTypeID='$repeatVLTestSampleTypeID',
													vlTestingSuspectedTreatmentFailure='$vlTestingSuspectedTreatmentFailure',
													suspectedTreatmentFailureLastVLDate='$suspectedTreatmentFailureLastVLDate',
													suspectedTreatmentFailureValue='$suspectedTreatmentFailureValue',
													suspectedTreatmentFailureSampleTypeID='$suspectedTreatmentFailureSampleTypeID' 
													where 
													id='$sampleID'");
									*/
									mysqlquery("update vl_samples set 
													districtID='$districtID',
													hubID='$hubID',
													facilityID='$facilityID',
													currentRegimenID='$currentRegimenID',
													collectionDate='$collectionDate',
													treatmentInitiationDate='$treatmentInitiationDate',
													sampleTypeID='$sampleTypeID',
													treatmentStatusID='$treatmentStatusID' 
													where 
													id='$sampleID'");
									if(mysqlerror())
										die("1: ".mysqlerror());
								}
							}
							
							//update facilities and IPs
							for($i=2;$i<=$excelData->sheets[1]['numRows'];$i++) {
								//Facility
								$facilityID=0;
								$facilityID=logNewFacility(trim($excelData->sheets[1]['cells'][$i][1]),trim($excelData->sheets[1]['cells'][$i][2]));
								
								//District
								$districtID=0;
								$districtID=logNewDistrict(trim($excelData->sheets[1]['cells'][$i][2]));

								//Hub
								$hubID=0;
								$hubID=logNewHub(trim($excelData->sheets[1]['cells'][$i][3]),$facilityID);

								//IP
								$ipID=0;
								$ipID=logNewIP(trim($excelData->sheets[1]['cells'][$i][4]),$facilityID,$hubID);
							}
							
							//update regimen
							for($i=2;$i<=$excelData->sheets[2]['numRows'];$i++) {
								//Current Regimen
								$currentRegimenID=0;
								$currentRegimenID=logNewCurrentRegimen(trim($excelData->sheets[2]['cells'][$i][1]));

								//Which Treatment Line is Patient on
								$treatmentStatusID=0;
								$treatmentStatusID=logNewTreatmentStatus(trim($excelData->sheets[2]['cells'][$i][2]));

								//ensure this treatment line is matched to the regimen
								if($currentRegimenID && $treatmentStatusID && !getDetailedTableInfo2("vl_appendix_regimen","id='$currentRegimenID' and treatmentStatusID='$treatmentStatusID' limit 1","id")) {
									//log table change
									logTableChange("vl_appendix_regimen","treatmentStatusID",$currentRegimenID,getDetailedTableInfo2("vl_appendix_regimen","id='$currentRegimenID'","treatmentStatusID"),$treatmentStatusID);
									//update vl_appendix_regimen
									mysqlquery("update vl_appendix_regimen set treatmentStatusID='$treatmentStatusID' where id='$currentRegimenID'");
								}
							}
							//flag
							$success=1;
						}
					} else {
						$error.="<br>File could not be uploaded. Consider contacting your Systems Administrator.";
					}
				}
            break;
            default:
                if($modify) {
                    $task="modify";
                }
            break;
		}
		
		//set task
		if(!$task) {
			$task="add";
		}
?>
<script language="JavaScript" type="text/javascript">
		<!--
        function checkForm(rulesForm) {
            return (true);
        }
        //-->
        </script>
<form action="?act=uploaddata&nav=datamanagement" method="post" enctype="multipart/form-data" name="rulesForm" id="rulesForm" onsubmit="return checkForm(this)">
  <table width="100%" border="0" class="vl">
    <? if($success) { ?>
    <tr>
      <td class="vl_success">Processing Complete!</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <? } else if($error) { ?>
    <tr>
      <td class="vl_error">Unable to process your submission due to the following error(s):
        <?=$error?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <? } ?>
    <tr>
      <td style="padding:10px 0px 10px 0px">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
                  <tr>
                    <td width="80%"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
                        <tr>
                          <td colspan="2" style="padding:10px 0px; border-bottom:1px solid #cccccc">Upload Template in an excel &quot;.xls&quot; format.</td>
                        </tr>
                      <tr>
                        <td colspan="2" style="padding:10px 0px"><input name="userfile" type="file" class="search" size="28" /></td>
                      </tr>
                        <tr>
                          <td style="padding:10px 0px; border-top:1px solid #cccccc"><input type="submit" name="button" id="button" value="   Save   " />
                            <input name="act" type="hidden" id="act" value="uploaddata" />
                            <input name="option" type="hidden" id="option" value="<?=$task?>" /></td>
                        </tr>
                        <tr>
                          <td style="padding:20px 0px 10px 0px; border-bottom: 1px dashed #CCC"><strong>Statistics</strong></td>
                        </tr>
                        <tr>
                          <td class="vls_grey" style="padding:10px 0px 5px 0px">Number of unique facilities within samples DB: <?=number_format((float)getDetailedTableInfo3("vl_samples","id>0","count(distinct facilityID)","num"))?></td>
                        </tr>
                        <tr>
                          <td class="vls_grey" style="padding:5px 0px">Number of unique districts within samples DB: <?=number_format((float)getDetailedTableInfo3("vl_samples","id>0","count(distinct districtID)","num"))?></td>
                        </tr>
                        <tr>
                          <td class="vls_grey" style="padding:5px 0px">Number of unique hubs within samples DB: <?=number_format((float)getDetailedTableInfo3("vl_samples","id>0","count(distinct hubID)","num"))?></td>
                        </tr>
                    </table></td>
                    <td width="20%" style="padding:20px 0px 0px 10px" valign="top">
					<!-- start alert -->
                        <table border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #f3f3f3" width="100%">
                          <tr>
                            <td style="padding:10px"><table width="100%" border="0" class="vl">
                              <tr>
                                <td><strong>TEMPLATES</strong></td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                              </tr>
                              <tr>
                                <td bgcolor="#d5d5d5" style="padding:10px">Download Template</td>
                              </tr>
                              <tr>
                                <td style="padding:10px"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                  <tr>
                                    <td><a href="templates/template.uploaddata.xls"><img src="templates/download.excel.gif" border="0" /></a></td>
                                    <td width="100%" style="padding-left:10px"><a href="templates/template.uploaddata.xls">Download&nbsp;Template</a></td>
                                  </tr>
                                </table></td>
                              </tr>
                            </table></td>
                          </tr>
                        </table>
					<!-- end alert -->
                    </td>
                  </tr>
                </table>
      </td>
    </tr>
  </table>
</form>
