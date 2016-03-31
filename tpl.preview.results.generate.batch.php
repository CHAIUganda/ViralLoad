<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

//array initiation
//$pdfFileName=array();
$html=0;
$html="<style type=\"text/css\">
<!--
div.special { margin: auto; width:82%; padding: 1px; }
div.special table { width:100%; font-size:9px; border-collapse:collapse; }
-->
</style>";

//iterations
if(count($sampleResultCheckbox)) {
	$sm=0;
	foreach($sampleResultCheckbox as $sm) {
		//split variable
		$sampleResult=array();
		$sampleResult=explode("|",$sm);
		//key variables
		$sampleID=0;
		$sampleID=validate($sampleResult[0]);
		$worksheetID=0;
		$worksheetID=validate($sampleResult[1]);
		//other relevant variables
		$machineType=validate($machineType);
		$worksheetMachineType=0;
		$worksheetMachineType=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID'","machineType");
		$resultsTable=0;
		$resultsSampleType=0;
		$resultField=0;
		$methodUsed=0;
		if($machineType=="abbott") {
			$resultsTable="vl_results_abbott";
			$resultsSampleType="sampleID";
			$resultField="result";
			$methodUsed="Abbott Real time HIV-1 PCR";
		} elseif($machineType=="roche") {
			$resultsTable="vl_results_roche";
			$resultsSampleType="SampleID";
			$resultField="Result";
			$methodUsed="HIV-1 RNA PCR Roche";
		} elseif($machineType=="all") {
			if($worksheetMachineType=="abbott") {
				$resultsTable="vl_results_abbott";
				$resultsSampleType="sampleID";
				$resultField="result";
				$methodUsed="Abbott Real time HIV-1 PCR";
			} elseif($worksheetMachineType=="roche") {
				$resultsTable="vl_results_roche";
				$resultsSampleType="SampleID";
				$resultField="Result";
				$methodUsed="HIV-1 RNA PCR Roche";
			}
		}
		
		//if no $worksheetMachineType, this is likely a rejected sample
		if($worksheetMachineType) {
			//log the print of this form
			mysqlquery("insert into vl_logs_printedresults 
							(sampleID,worksheetID,created,createdby) 
							values 
							('$sampleID','$worksheetID','$datetime','$trailSessionUser')");
			
			$facilityID=0;
			$facilityID=getDetailedTableInfo2("vl_samples","id='$sampleID'","facilityID");
			$facilityName=0;
			$facilityName=getDetailedTableInfo2("vl_facilities","id='$facilityID'","facility");
			
			$districtID=0;
			$districtID=getDetailedTableInfo2("vl_samples","id='$sampleID'","districtID");
			$districtName=0;
			$districtName=getDetailedTableInfo2("vl_districts","id='$districtID'","district");
			
			$hubID=0;
			$hubID=getDetailedTableInfo2("vl_samples","id='$sampleID'","hubID");
			$hubName=0;
			$hubName=getDetailedTableInfo2("vl_hubs","id='$hubID'","hub");
			
			$formNumber=0;
			$formNumber=getDetailedTableInfo2("vl_samples","id='$sampleID'","formNumber");
			$vlSampleID=0;
			$vlSampleID=getDetailedTableInfo2("vl_samples","id='$sampleID'","vlSampleID");
			$locationID=0;
			$locationID=getDetailedTableInfo2("vl_samples","id='$sampleID'","lrCategory").getDetailedTableInfo2("vl_samples","id='$sampleID'","lrEnvelopeNumber")."/".getDetailedTableInfo2("vl_samples","id='$sampleID'","lrNumericID");
			
			$sampleTypeID=0;
			$sampleTypeID=getDetailedTableInfo2("vl_samples","id='$sampleID'","sampleTypeID");
			$sampleType=0;
			$sampleType=getDetailedTableInfo2("vl_appendix_sampletype","id='$sampleTypeID'","appendix");

			$patientID=0;
			$patientID=getDetailedTableInfo2("vl_samples","id='$sampleID'","patientID");
			$artNumber=0;
			$artNumber=getDetailedTableInfo2("vl_patients","id='$patientID'","artNumber");
			$otherID=0;
			$otherID=getDetailedTableInfo2("vl_patients","id='$patientID'","otherID");
			$dateOfBirth=0;
			$dateOfBirth=getDetailedTableInfo2("vl_patients","id='$patientID'","dateOfBirth");
			$gender=0;
			$gender=getDetailedTableInfo2("vl_patients","id='$patientID'","gender");
			$phone=0;
			$phone=getDetailedTableInfo2("vl_patients_phone","patientID='$patientID' order by created desc limit 1","phone");
			
			$sampleCollectionDate=0;
			$sampleCollectionDate=getDetailedTableInfo2("vl_samples","id='$sampleID'","collectionDate");
			$sampleReceiptDate=0;
			$sampleReceiptDate=getDetailedTableInfo2("vl_samples","id='$sampleID'","receiptDate");
			$sampleVLTestDate=0;
			//$sampleVLTestDate=getDetailedTableInfo2("vl_results_abbott_runtimes","worksheetID='$worksheetID' limit 1","runCompletionTime");
			//if(!$sampleVLTestDate) {
				$sampleVLTestDate=getDetailedTableInfo2($resultsTable,"$resultsSampleType='$vlSampleID' order by created desc limit 1","created");
			//}
			
			//factor
			$factor=0;
			$factor=getDetailedTableInfo2("vl_results_multiplicationfactor","worksheetID='$worksheetID' limit 1","factor");
			if(!$factor) {
				$factor=1;
			}
			$vlResult=0;
			$vlResult=getVLResult(($worksheetMachineType?$worksheetMachineType:$machineType),$worksheetID,$vlSampleID,$factor);
			
			//signatures
			$worksheetOwner=0;
			$worksheetOwner=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID'","createdby");

			$labTechSignature=0;
			if(getDetailedTableInfo2("vl_users","email='$worksheetOwner'","signaturePATH")) {
				$labTechSignature="<img src=\"".getDetailedTableInfo2("vl_users","email='$worksheetOwner'","signaturePATH")."\" width=\"120\" height=\"50\" />";
			}
			
			$labManagerSignature=0;
			if(getDetailedTableInfo2("vl_users","role='Lab Manager' order by created desc limit 1","signaturePATH")) {
				$labManagerSignature="<img src=\"".getDetailedTableInfo2("vl_users","role='Lab Manager' order by created desc limit 1","signaturePATH")."\" width=\"120\" height=\"50\" />";
			}

			//remove "Copies / mL" and "," from $vlResult
			$numericVLResult=0;
			$numericVLResult=preg_replace("/Copies \/ mL/s","",$vlResult);
			$numericVLResult=preg_replace("/,/s","",$numericVLResult);
			$numericVLResult=preg_replace("/\</s","",$numericVLResult);
			$numericVLResult=preg_replace("/\&lt;/s","",$numericVLResult);
			$numericVLResult=preg_replace("/\&gt;/s","",$numericVLResult);
			$numericVLResult=trim($numericVLResult);
			//multiply by 2
			//$numericVLResult*=2;
			$smiley=0;
			$recommendation=0;

			$suppressed=getDetailedTableInfo2("vl_results_merged","vlSampleID='$vlSampleID'","suppressed");//suppression status
			
			switch ($suppressed) {
				case 'YES': // patient suppressed, according to the guidlines at that time
					$smiley="<img src=\"$system_default_path"."images/smiley.smile.gif\" />";
					$recommendation=getRecommendation($suppressed,$sampleVLTestDate,$sampleType);
					break;

				case 'NO': // patient suppressed, according to the guidlines at that time
					$smiley="<img src=\"$system_default_path"."images/smiley.sad.gif\" />";
					$recommendation=getRecommendation($suppressed,$sampleVLTestDate,$sampleType);					
					break;
				
				default:
					$smiley="<img src=\"$home_url/images/smiley.sad.gif\" />";
					$recommendation="There is No Result Given. The Test Failed the Quality Control Criteria. We advise you send a a new sample.";
					break;
			}

			/*if($sampleType=="DBS") {
				if(is_numeric($numericVLResult)) {
					if($numericVLResult<5000) {
						$smiley="<img src=\"$system_default_path"."images/smiley.smile.gif\" />";
						$recommendation="Below 5,000 copies/mL: Patient is suppressing their viral load. <br>Please continue adherence counseling. Do another viral load after 12 months.";
					} elseif($numericVLResult>=5000) {
						$smiley="<img src=\"$system_default_path"."images/smiley.sad.gif\" />";
						$recommendation="Above 5,000 copies/mL: Patient has elevated viral load. <br>Please initiate intensive adherence counseling and conduct a repeat viral load test after six months.";
					}
				} else {
					if($vlResult=="Failed.") { 
						$smiley="<img src=\"$home_url/images/smiley.sad.gif\" />";
						$recommendation="There is No Result Given. The Test Failed the Quality Control Criteria. We advise you send a a new sample.";
					} else {
						$smiley="<img src=\"$system_default_path"."images/smiley.smile.gif\" />";
						$recommendation="Below 5,000 copies/mL: Patient is suppressing their viral load. <br>Please continue adherence counseling. Do another viral load after 12 months.";
					}
				}
			} else {
				if(is_numeric($numericVLResult)) {
					if($numericVLResult<1000) {
						$smiley="<img src=\"$system_default_path"."images/smiley.smile.gif\" />";
						$recommendation="Below 1,000 copies/mL: Patient is suppressing their viral load. <br>Please continue adherence counseling. Do another viral load after 12 months.";
					} elseif($numericVLResult>=1000) {
						$smiley="<img src=\"$system_default_path"."images/smiley.sad.gif\" />";
						$recommendation="Above 1,000 copies/mL: Patient has elevated viral load. <br>Please initiate intensive adherence counseling and conduct a repeat viral load test after six months.";
					}
				} else {
					if($vlResult=="Failed.") {
						$smiley="<img src=\"$home_url/images/smiley.sad.gif\" />";
						$recommendation="There is No Result Given. The Test Failed the Quality Control Criteria. We advise you send a a new sample.";
					} else {
						$smiley="<img src=\"$system_default_path"."images/smiley.smile.gif\" />";
						$recommendation="Below 1,000 copies/mL: Patient is suppressing their viral load. <br>Please continue adherence counseling. Do another viral load after 12 months.";
					}
				}
			}*/
	
			//was this sample overridden?
			if(getDetailedTableInfo2("vl_results_override","sampleID='$vlSampleID'","id")) {
				//remove smiley and recommendation
				$smiley="";
				$recommendation="";
			}
			
			//generate form
			$html.="<page orientation=\"portrait\" format=\"165x230\" style=\"font-size: 9px\">
				<div class=\"special\">
					  <!-- Start Header -->
						<div align=\"center\"><img src=\"$home_url/images/uganda.emblem.gif\"></div>
						<div align=\"center\"><strong>MINISTRY OF HEALTH UGANDA<br>NATIONAL AIDS CONTROL PROGRAM (ACP)</strong></div>
					  <!-- End Header -->
					  <!-- Start Text -->
						<div style=\"padding:3px; border-bottom: 1px solid #333; text-align: center\">CENTRAL PUBLIC HEALTH LABORATORIES</div>
						<div style=\"padding:3px 0px 10px 0px; text-align: center\">Viral Load Test Results</div>
					  <!-- End Text -->
					
					<!-- Start Facility and Sample Details -->
					<table style=\"width: 100%\">
					  <tr>
						<!-- Start Facility Details -->
						<td style=\"width: 45%; padding:0px 3px 0px 0px\">
							<div style=\"padding: 5px; background: #ccc; border-bottom: 1px solid #666\"><strong>FACILITY DETAILS</strong></div>
							<div style=\"padding: 2px 5px; border-left: 1px solid #999; border-right: 1px solid #999; border-bottom: 1px solid #999\">
								<table style=\"width: 90%\">
									<tr>
										<td style=\"width: 20%; padding: 5px 0px\">Name:</td>
										<td style=\"width: 80%; padding: 5px 0px; border-bottom: 1px solid #333\">$facilityName</td>
									</tr>
									<tr>
										<td style=\"padding: 5px 0px\">District:</td>
										<td style=\"padding: 5px 0px; border-bottom: 1px solid #333\">$districtName | Hub: $hubName</td>
									</tr>
								</table>
							</div>
						</td>
						<!-- End Facility Details -->
						<!-- Start Sample Details -->
						<td style=\"width: 55%\">
							<div style=\"padding: 5px; background: #ccc; border-bottom: 1px solid #666\"><strong>SAMPLE DETAILS</strong></div>
							<div style=\"padding: 2px 5px; border-left: 1px solid #999; border-right: 1px solid #999; border-bottom: 1px solid #999\">
								<table style=\"width: 40%\">
									<tr>
										<td style=\"width: 30%; padding: 5px 0px\">Form&nbsp;#:</td>
										<td style=\"width: 70%; padding: 5px 0px; border-bottom: 1px solid #333\">$formNumber</td>
									</tr>
									<tr>
										<td>Sample&nbsp;Type:</td>
										<td style=\"padding: 4px 0px\">
											<table style=\"width: 100%\">
											  <tr>
												<td style=\"width: 2%\"><div style=\"padding: 1px; height: 12px; width:12px; border: 1px solid #333; text-align: center\">".($sampleType=="DBS"?"<img src=\"$home_url"."/images/check.gif\" />":"&nbsp;")."</div></td>
												<td style=\"width: 48%; padding:0px 5px 0px 5px\">DBS</td>
												<td style=\"width: 2%\"><div style=\"padding: 1px; height: 12px; width:12px; border: 1px solid #333; text-align: center\">".($sampleType=="Plasma"?"<img src=\"$home_url"."/images/check.gif\" />":"&nbsp;")."</div></td>
												<td style=\"width: 48%; padding:0px 5px 0px 5px\">Plasma</td>
											  </tr>
											</table>
										</td>
									</tr>
								</table>
							</div>
						</td>
						<!-- End Sample Details -->
					  </tr>
					</table>
					<!-- End Facility and Sample Details -->
					
					<!-- Start Patient Information -->
					<div style=\"padding: 0px 0px 2px 0px\">
						<div style=\"padding: 5px; background: #ccc; border-bottom: 1px solid #666\"><strong>PATIENT INFORMATION</strong></div>
						<div style=\"padding: 2px 5px; border-left: 1px solid #999; border-right: 1px solid #999; border-bottom: 1px solid #999\">
							<table style=\"width: 60%\">
								<tr>
									<!-- Start Column 1 -->
									<td style=\"width: 50%; padding: 5px\">
										<table style=\"width: 50%\">
										  <tr>
											<td style=\"width: 50%\">ART Number:</td>
											<td style=\"width: 50%; padding: 5px 0px; border-bottom: 1px solid #333\">$artNumber</td>
										  </tr>
										  <tr>
											<td>Other ID:</td>
											<td style=\"padding: 5px 0px; border-bottom: 1px solid #333\">$otherID</td>
										  </tr>
										  <tr>
											<td>Gender:</td>
											<td style=\"padding: 4px 0px\">
												<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
												  <tr>
													<td style=\"width: 2%\"><div style=\"padding: 1px; height: 12px; width:12px; border: 1px solid #333; text-align: center\">".($gender=="Male"?"<img src=\"$home_url"."/images/check.gif\" />":"&nbsp;")."</div></td>
													<td style=\"width: 18%\" style=\"padding:0px 5px 0px 5px\">Male</td>
													<td style=\"width: 2%\"><div style=\"padding: 1px; height: 12px; width:12px; border: 1px solid #333; text-align: center\">".($gender=="Female"?"<img src=\"$home_url"."/images/check.gif\" />":"&nbsp;")."</div></td>
													<td style=\"width: 18%\" style=\"padding:0px 0px 0px 5px\">Female</td>
													<td style=\"width: 2%\"><div style=\"padding: 1px; height: 12px; width:12px; border: 1px solid #333; text-align: center\">".($gender=="Left Blank"?"<img src=\"$home_url"."/images/check.gif\" />":"&nbsp;")."</div></td>
													<td style=\"width: 58%; padding:0px 0px 0px 5px\">Left Blank</td>
												  </tr>
												</table>
											</td>
										  </tr>
										</table>
									</td>
									<!-- End Column 1 -->
									<!-- Start Column 2 -->
									<td style=\"width: 50%; padding: 5px\">
										<table style=\"width: 100%\">
										  <tr>
											<td style=\"width: 60%\">Date of Birth:</td>
											<td style=\"width: 40%; padding: 5px 0px; border-bottom: 1px solid #333\">".(getFormattedDateLessDay($dateOfBirth))."</td>
										  </tr>
										  <tr>
											<td>Phone Number:</td>
											<td style=\"padding: 5px 0px; border-bottom: 1px solid #333\">$phone</td>
										  </tr>
										</table>
									</td>
									<!-- End Column 2 -->
								</tr>
							</table>
						</div>
					</div>
					<!-- End Patient Information -->
					
					<!-- Start Sample Test Information -->
					<div style=\"padding: 0px 0px 2px 0px\">
						<div style=\"padding: 5px; background: #ccc; border-bottom: 1px solid #666\"><strong>SAMPLE TEST INFORMATION</strong></div>
						<div style=\"padding: 2px 5px; border-left: 1px solid #999; border-right: 1px solid #999; border-bottom: 1px solid #999\">
							<!-- Start Sample Collection Date -->
							<div style=\"width: 95%; padding:5px 0px; border-bottom: 1px solid #CCC\">
								<table style=\"width: 95%\">
								  <tr>
									<td style=\"width: 23%\">Sample&nbsp;Collection&nbsp;Date:</td>
									<td style=\"width: 15%; padding:0px 5px\">".getFormattedDateLessDay($sampleCollectionDate)."</td>
									<td style=\"width: 15%\">Reception&nbsp;Date:</td>
									<td style=\"width: 10%\" style=\"padding:0px 5px\">".getFormattedDateLessDay($sampleReceiptDate)."</td>
									<td style=\"width: 20%\">Viral&nbsp;Load&nbsp;Test&nbsp;Date:</td>
									<td style=\"width: 17%; padding:0px 5px\">".getFormattedDateLessDay($sampleVLTestDate)."</td>
								  </tr>
								</table>
							</div>
							<!-- End Sample Collection Date -->
			
							<!-- Start Repeat Test -->
							<div style=\"width: 95%; padding:5px 0px; border-bottom: 1px solid #CCC\">
								<table style=\"width: 60%\">
								  <tr>
									<td style=\"width: 35%\">Repeat Test:</td>
									<td style=\"width: 2%\"><div style=\"padding: 1px; height: 12px; width:12px; border: 1px solid #333; text-align: center\">".(getDetailedTableInfo2("vl_logs_samplerepeats","sampleID='$sampleID' and withWorksheetID!=''","id")?"<img src=\"$home_url"."/images/check.gif\" />":"&nbsp;")."</div></td>
									<td style=\"width: 18%; padding:0px 5px 0px 5px\">Yes</td>
									<td style=\"width: 2%\"><div style=\"padding: 1px; height: 12px; width:12px; border: 1px solid #333; text-align: center\">".(getDetailedTableInfo2("vl_logs_samplerepeats","sampleID='$sampleID' and withWorksheetID!=''","id")?"&nbsp;":"<img src=\"$home_url"."/images/check.gif\" />")."</div></td>
									<td style=\"width: 43%; padding:0px 0px 0px 5px\">No</td>
								  </tr>
								</table>
							</div>
							<!-- End Repeat Test -->
			
							<!-- Start Sample Rejected -->
							<div style=\"width: 95%; padding:5px 0px; border-bottom: 1px solid #CCC\">
								<table style=\"width: 60%\">
								  <tr>
									<td style=\"width: 35%\">Sample Rejected:</td>
									<td style=\"width: 2%\"><div style=\"padding: 1px; height: 12px; width:12px; border: 1px solid #333; text-align: center\">&nbsp;</div></td>
									<td style=\"width: 18%; padding:0px 5px 0px 5px\">Yes</td>
									<td style=\"width: 2%\"><div style=\"padding: 1px; height: 12px; width:12px; border: 1px solid #333; text-align: center\"><img src=\"$home_url"."/images/check.gif\" /></div></td>
									<td style=\"width: 43%; padding:0px 0px 0px 5px\">No</td>
								  </tr>
								</table>
							</div>
							<!-- End Sample Rejected -->
			
							<!-- Start Rejection Reason -->
							<div style=\"width: 95%; padding:5px 0px\">
								<table style=\"width: 60%\">
								  <tr>
									<td style=\"width: 35%\">If Rejected, Reason:</td>
									<td style=\"width: 45%; padding:0px 0px 0px 5px\">&nbsp;</td>
								  </tr>
								</table>
							</div>
							<!-- End Rejection Reason -->
						</div>
					</div>
					<!-- End Sample Test Information -->
					
					<!-- Start Viral Load Results -->
					<div style=\"padding: 0px 0px 2px 0px\">
						<div style=\"padding: 5px; background: #ccc; border-bottom: 1px solid #666\"><strong>VIRAL LOAD RESULTS</strong></div>
						<div style=\"padding: 2px 5px; border-left: 1px solid #999; border-right: 1px solid #999; border-bottom: 1px solid #999\">
							<table style=\"width: 90%\">
							  <tr>
								<td style=\"width: 81%\">
									<div style=\"padding:5px 0px 0px 0px\">
										<!-- Start Machine Type -->
										<div style=\"padding:5px 0px; border-bottom: 1px solid #CCC\">
											<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
											  <tr>
												<td style=\"width: 30%\">Method Used:</td>
												<td style=\"width: 70%; padding:0px 5px\">$methodUsed</td>
											  </tr>
											</table>
										</div>
										<!-- End Machine Type -->
						
										<!-- Start Location ID -->
										<div style=\"padding:5px 0px; border-bottom: 1px solid #CCC\">
											<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
											  <tr>
												<td style=\"width: 30%\">Location ID:</td>
												<td style=\"width: 70%; padding:0px 5px\">$locationID</td>
											  </tr>
											</table>
										</div>
										<!-- End Location ID -->
						
										<!-- Start Viral Load Testing # -->
										<div style=\"padding:5px 0px; border-bottom: 1px solid #CCC\">
											<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
											  <tr>
												<td style=\"width: 30%\">Viral Load Testing #:</td>
												<td style=\"width: 70%; padding:0px 5px\">$vlSampleID</td>
											  </tr>
											</table>
										</div>
										<!-- End Viral Load Testing # -->
						
										<!-- Start Result of Viral Load -->
										<div style=\"padding:5px 0px\">
											<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
											  <tr>
												<td style=\"width: 30%\">Result of Viral Load:</td>
												<td style=\"width: 70%; padding:0px 5px\">$vlResult</td>
											  </tr>
											</table>
										</div>
										<!-- End Result of Viral Load -->
									</div>
								</td>
								".($smiley?"
								<td align=\"center\" style=\"width: 20% padding:10px\">
									<!-- Start Smiley Face -->
									$smiley
									<!-- End Smiley Face -->
								</td>
								":"")."
							  </tr>
							</table>
						</div>
					</div>
					<!-- End Viral Load Results -->
					".($recommendation?"
					<!-- Start Recommendation -->
					<div style=\"padding: 5px; background: #ccc; border-bottom: 1px solid #666\"><strong>RECOMMENDATIONS</strong></div>
					<div style=\"padding: 2px 5px; border-left: 1px solid #999; border-right: 1px solid #999; border-bottom: 1px solid #999\">
						<!-- Start Clinical Recommendation -->
						<div style=\"width: 90%; padding:10px\">Suggested Clinical Action based on National Guidelines:</div>
						<div style=\"width: 90%; padding:5px 30px\">$recommendation</div>
						<!-- End Clinical Recommendation -->
					</div>
					<!-- End Recommendation -->
					":"")."
					<!-- Start Sign Offs -->
					<div style=\"padding:20px 0px 0px 0px\">
						<table style=\"width: 100%\">
						  <tr>
							<td style=\"width: 20%\">Lab Technologist:</td>
							<td style=\"width: 30%; border-bottom: 1px solid #333\">".($labTechSignature?$labTechSignature:"&nbsp;")."</td>
							<td style=\"width: 20%\">Lab Manager:</td>
							<td style=\"width: 30%; border-bottom: 1px solid #333\">".($labManagerSignature?$labManagerSignature:"&nbsp;")."</td>
						  </tr>
						</table>
					</div>
					<!-- End Sign Offs -->
			
				</div>
			</page>";
		} else {
			//log the print of this form
			mysqlquery("insert into vl_logs_printedrejectedresults 
							(sampleID,created,createdby) 
							values 
							('$sampleID','$datetime','$trailSessionUser')");
			
			$facilityID=0;
			$facilityID=getDetailedTableInfo2("vl_samples","id='$sampleID'","facilityID");
			$facilityName=0;
			$facilityName=getDetailedTableInfo2("vl_facilities","id='$facilityID'","facility");
			
			$districtID=0;
			$districtID=getDetailedTableInfo2("vl_samples","id='$sampleID'","districtID");
			$districtName=0;
			$districtName=getDetailedTableInfo2("vl_districts","id='$districtID'","district");
			
			$hubID=0;
			$hubID=getDetailedTableInfo2("vl_samples","id='$sampleID'","hubID");
			$hubName=0;
			$hubName=getDetailedTableInfo2("vl_hubs","id='$hubID'","hub");
			
			$formNumber=0;
			$formNumber=getDetailedTableInfo2("vl_samples","id='$sampleID'","formNumber");
			$vlSampleID=0;
			$vlSampleID=getDetailedTableInfo2("vl_samples","id='$sampleID'","vlSampleID");
			
			$sampleTypeID=0;
			$sampleTypeID=getDetailedTableInfo2("vl_samples","id='$sampleID'","sampleTypeID");
			$sampleType=0;
			$sampleType=getDetailedTableInfo2("vl_appendix_sampletype","id='$sampleTypeID'","appendix");
			
			$patientID=0;
			$patientID=getDetailedTableInfo2("vl_samples","id='$sampleID'","patientID");
			$artNumber=0;
			$artNumber=getDetailedTableInfo2("vl_patients","id='$patientID'","artNumber");
			$otherID=0;
			$otherID=getDetailedTableInfo2("vl_patients","id='$patientID'","otherID");
			$dateOfBirth=0;
			$dateOfBirth=getDetailedTableInfo2("vl_patients","id='$patientID'","dateOfBirth");
			$gender=0;
			$gender=getDetailedTableInfo2("vl_patients","id='$patientID'","gender");
			$phone=0;
			$phone=getDetailedTableInfo2("vl_patients_phone","patientID='$patientID' order by created desc limit 1","phone");
			
			$sampleCollectionDate=0;
			$sampleCollectionDate=getDetailedTableInfo2("vl_samples","id='$sampleID'","collectionDate");
			$sampleReceiptDate=0;
			$sampleReceiptDate=getDetailedTableInfo2("vl_samples","id='$sampleID'","receiptDate");
			
			$rejectionReasonID=0;
			$rejectionReasonID=getDetailedTableInfo2("vl_samples_verify","sampleID='$sampleID'","outcomeReasonsID");
			$rejectionReason=0;
			$rejectionReason=getDetailedTableInfo2("vl_appendix_samplerejectionreason","id='$rejectionReasonID' and sampleTypeID='$sampleTypeID'","appendix");
			$sampleVLRejectionDate=0;
			$sampleVLRejectionDate=getDetailedTableInfo2("vl_samples_verify","sampleID='$sampleID'","created");
			
			//signatures
			$labManagerSignature=0;
			if(getDetailedTableInfo2("vl_users","role='Lab Manager' order by created desc limit 1","signatureURL")) {
				$labManagerSignature="<img src=\"".getDetailedTableInfo2("vl_users","role='Lab Manager' order by created desc limit 1","signatureURL")."\" width=\"120\" height=\"50\" />";
			}
			
			//generate form
			$html.="<page orientation=\"portrait\" format=\"150x220\" style=\"font-size: 9px\">
				<div class=\"special\">
					  <!-- Start Header -->
						<div align=\"center\"><img src=\"$home_url/images/uganda.emblem.gif\"></div>
						<div align=\"center\"><strong>MINISTRY OF HEALTH UGANDA<br>NATIONAL AIDS CONTROL PROGRAM (ACP)</strong></div>
					  <!-- End Header -->
					  <!-- Start Text -->
						<div style=\"padding:10px; border-bottom: 1px solid #333; text-align: center\">CENTRAL PUBLIC HEALTH LABORATORIES</div>
						<div style=\"padding:10px 0px 30px 0px; text-align: center\">Viral Load Test Results</div>
					  <!-- End Text -->
					
					<!-- Start Facility and Sample Details -->
					<table style=\"width: 100%\">
					  <tr>
						<!-- Start Facility Details -->
						<td style=\"width: 45%; padding:0px 3px 0px 0px\">
							<div style=\"padding: 5px; background: #ccc; border-bottom: 1px solid #666\"><strong>FACILITY DETAILS</strong></div>
							<div style=\"padding: 2px 5px; border-left: 1px solid #999; border-right: 1px solid #999; border-bottom: 1px solid #999\">
								<table style=\"width: 90%\">
									<tr>
										<td style=\"width: 20%; padding: 5px 0px\">Name:</td>
										<td style=\"width: 80%; padding: 5px 0px; border-bottom: 1px solid #333\">$facilityName</td>
									</tr>
									<tr>
										<td style=\"padding: 5px 0px\">District:</td>
										<td style=\"padding: 5px 0px; border-bottom: 1px solid #333\">$districtName | Hub: $hubName</td>
									</tr>
								</table>
							</div>
						</td>
						<!-- End Facility Details -->
						<!-- Start Sample Details -->
						<td style=\"width: 55%\">
							<div style=\"padding: 5px; background: #ccc; border-bottom: 1px solid #666\"><strong>SAMPLE DETAILS</strong></div>
							<div style=\"padding: 2px 5px; border-left: 1px solid #999; border-right: 1px solid #999; border-bottom: 1px solid #999\">
								<table style=\"width: 40%\">
									<tr>
										<td style=\"width: 30%; padding: 5px 0px\">Form&nbsp;#:</td>
										<td style=\"width: 70%; padding: 5px 0px; border-bottom: 1px solid #333\">$formNumber</td>
									</tr>
									<tr>
										<td>Sample&nbsp;Type:</td>
										<td style=\"padding: 4px 0px\">
											<table style=\"width: 100%\">
											  <tr>
												<td style=\"width: 2%\"><div style=\"padding: 1px; height: 12px; width:12px; border: 1px solid #333; text-align: center\">".($sampleType=="DBS"?"<img src=\"$home_url"."/images/check.gif\" />":"&nbsp;")."</div></td>
												<td style=\"width: 48%; padding:0px 5px 0px 5px\">DBS</td>
												<td style=\"width: 2%\"><div style=\"padding: 1px; height: 12px; width:12px; border: 1px solid #333; text-align: center\">".($sampleType=="Plasma"?"<img src=\"$home_url"."/images/check.gif\" />":"&nbsp;")."</div></td>
												<td style=\"width: 48%; padding:0px 5px 0px 5px\">Plasma</td>
											  </tr>
											</table>
										</td>
									</tr>
								</table>
							</div>
						</td>
						<!-- End Sample Details -->
					  </tr>
					</table>
					<!-- End Facility and Sample Details -->
					
					<!-- Start Patient Information -->
					<div style=\"padding: 0px 0px 2px 0px\">
						<div style=\"padding: 5px; background: #ccc; border-bottom: 1px solid #666\"><strong>PATIENT INFORMATION</strong></div>
						<div style=\"padding: 2px 5px; border-left: 1px solid #999; border-right: 1px solid #999; border-bottom: 1px solid #999\">
							<table style=\"width: 60%\">
								<tr>
									<!-- Start Column 1 -->
									<td style=\"width: 50%; padding: 5px\">
										<table style=\"width: 50%\">
										  <tr>
											<td style=\"width: 50%\">ART Number:</td>
											<td style=\"width: 50%; padding: 5px 0px; border-bottom: 1px solid #333\">$artNumber</td>
										  </tr>
										  <tr>
											<td>Other ID:</td>
											<td style=\"padding: 5px 0px; border-bottom: 1px solid #333\">$otherID</td>
										  </tr>
										  <tr>
											<td>Gender:</td>
											<td style=\"padding: 4px 0px\">
												<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
												  <tr>
													<td style=\"width: 2%\"><div style=\"padding: 1px; height: 12px; width:12px; border: 1px solid #333; text-align: center\">".($gender=="Male"?"<img src=\"$home_url"."/images/check.gif\" />":"&nbsp;")."</div></td>
													<td style=\"width: 18%\" style=\"padding:0px 5px 0px 5px\">Male</td>
													<td style=\"width: 2%\"><div style=\"padding: 1px; height: 12px; width:12px; border: 1px solid #333; text-align: center\">".($gender=="Female"?"<img src=\"$home_url"."/images/check.gif\" />":"&nbsp;")."</div></td>
													<td style=\"width: 18%\" style=\"padding:0px 0px 0px 5px\">Female</td>
													<td style=\"width: 2%\"><div style=\"padding: 1px; height: 12px; width:12px; border: 1px solid #333; text-align: center\">".($gender=="Left Blank"?"<img src=\"$home_url"."/images/check.gif\" />":"&nbsp;")."</div></td>
													<td style=\"width: 58%; padding:0px 0px 0px 5px\">Left Blank</td>
												  </tr>
												</table>
											</td>
										  </tr>
										</table>
									</td>
									<!-- End Column 1 -->
									<!-- Start Column 2 -->
									<td style=\"width: 50%; padding: 5px\">
										<table style=\"width: 100%\">
										  <tr>
											<td style=\"width: 60%\">Date of Birth:</td>
											<td style=\"width: 40%; padding: 5px 0px; border-bottom: 1px solid #333\">".(getFormattedDateLessDay($dateOfBirth))."</td>
										  </tr>
										  <tr>
											<td>Phone Number:</td>
											<td style=\"padding: 5px 0px; border-bottom: 1px solid #333\">$phone</td>
										  </tr>
										</table>
									</td>
									<!-- End Column 2 -->
								</tr>
							</table>
						</div>
					</div>
					<!-- End Patient Information -->
					
					<!-- Start Sample Test Information -->
					<div style=\"padding: 0px 0px 2px 0px\">
						<div style=\"padding: 5px; background: #ccc; border-bottom: 1px solid #666\"><strong>SAMPLE TEST INFORMATION</strong></div>
						<div style=\"padding: 2px 5px; border-left: 1px solid #999; border-right: 1px solid #999; border-bottom: 1px solid #999\">
							<!-- Start Sample Collection Date -->
							<div style=\"width: 95%; padding:5px 0px; border-bottom: 1px solid #CCC\">
								<table style=\"width: 95%\">
								  <tr>
									<td style=\"width: 23%\">Sample&nbsp;Collection&nbsp;Date:</td>
									<td style=\"width: 15%; padding:0px 5px\">".getFormattedDateLessDay($sampleCollectionDate)."</td>
									<td style=\"width: 15%\">Reception&nbsp;Date:</td>
									<td style=\"width: 47%\" style=\"padding:0px 5px\">".getFormattedDateLessDay($sampleReceiptDate)."</td>
								  </tr>
								</table>
							</div>
							<!-- End Sample Collection Date -->
			
							<!-- Start Repeat Test -->
							<div style=\"width: 95%; padding:5px 0px; border-bottom: 1px solid #CCC\">
								<table style=\"width: 60%\">
								  <tr>
									<td style=\"width: 35%\">Repeat Test:</td>
									<td style=\"width: 2%\"><div style=\"padding: 1px; height: 12px; width:12px; border: 1px solid #333; text-align: center\">".(getDetailedTableInfo2("vl_logs_samplerepeats","sampleID='$sampleID' and withWorksheetID!=''","id")?"<img src=\"$home_url"."/images/check.gif\" />":"&nbsp;")."</div></td>
									<td style=\"width: 18%; padding:0px 5px 0px 5px\">Yes</td>
									<td style=\"width: 2%\"><div style=\"padding: 1px; height: 12px; width:12px; border: 1px solid #333; text-align: center\">".(getDetailedTableInfo2("vl_logs_samplerepeats","sampleID='$sampleID' and withWorksheetID!=''","id")?"&nbsp;":"<img src=\"$home_url"."/images/check.gif\" />")."</div></td>
									<td style=\"width: 43%; padding:0px 0px 0px 5px\">No</td>
								  </tr>
								</table>
							</div>
							<!-- End Repeat Test -->
			
							<!-- Start Sample Rejected -->
							<div style=\"width: 95%; padding:5px 0px; border-bottom: 1px solid #CCC\">
								<table style=\"width: 60%\">
								  <tr>
									<td style=\"width: 35%\">Sample Rejected:</td>
									<td style=\"width: 2%\"><div style=\"padding: 1px; height: 12px; width:12px; border: 1px solid #333; text-align: center\"><img src=\"$home_url"."/images/check.gif\" /></div></td>
									<td style=\"width: 18%; padding:0px 5px 0px 5px\">Yes</td>
									<td style=\"width: 2%\"><div style=\"padding: 1px; height: 12px; width:12px; border: 1px solid #333; text-align: center\">&nbsp;</div></td>
									<td style=\"width: 43%; padding:0px 0px 0px 5px\">No</td>
								  </tr>
								</table>
							</div>
							<!-- End Sample Rejected -->
			
							<!-- Start Rejection Reason -->
							<div style=\"width: 95%; padding:5px 0px\">
								<table style=\"width: 80%\">
								  <tr>
									<td style=\"width: 35%\">If Rejected, Reason:</td>
									<td style=\"width: 45%; padding:0px 0px 0px 5px\"><font color=\"#FF0000\">$rejectionReason</font></td>
								  </tr>
								</table>
							</div>
							<!-- End Rejection Reason -->
						</div>
					</div>
					<!-- End Sample Test Information -->
			
					<!-- Start Sign Offs -->
					<div style=\"padding:20px 0px 0px 0px\">
						<table style=\"width: 100%\">
						  <tr>
							<td style=\"width: 20%\">Lab Manager:</td>
							<td style=\"width: 80%; border-bottom: 1px solid #333\">".($labManagerSignature?$labManagerSignature:"&nbsp;")."</td>
						  </tr>
						</table>
					</div>
					<!-- End Sign Offs -->
			
				</div>
			</page>";
		}
		
		//load PDF object
		/*
		$pdf = new DOMPDF();
		$pdf->load_html($html);
		$pdf->set_paper("letter", "portrait");
		$pdf->render();
		$pdf->outputToFile($filename, array("Attachment" => false));
		//$pdf->stream($formNumber, array("Attachment" => false));

		$pdfFileName[]=$filename;
		*/
	}
}

//filename
$filename=0;
$filename="VL.Results.BatchForm.pdf";

try {
	$html2pdf = new HTML2PDF('P', 'A4', 'fr', true, 'UTF-8', 0);
	$html2pdf->writeHTML($html, isset($_GET['vuehtml']));
	$html2pdf->Output($filename);
}
catch(HTML2PDF_exception $e) {
	echo $e;
	exit;
}

//render to PDF
/*
$pdfmerge = 0;
$pdfmerge = new PDFMerger;
for($i=0;$i<count($pdfFileName);$i++) {
	$pdfmerge->addPDF("$system_default_path/downloads.forms/$pdfFileName[$i]","all","P");
}
$pdfmerge->merge('browser', '');
*/
?>