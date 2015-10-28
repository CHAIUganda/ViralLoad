<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

//array initiation
$pdfFileName=array();

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
		if($sampleType=="DBS") {
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
		}

		//was this sample overridden?
		if(getDetailedTableInfo2("vl_results_override","sampleID='$vlSampleID'","id")) {
			//remove smiley and recommendation
			$smiley="";
			$recommendation="";
		}
		
		//generate form
		$html=0;
		$html="<style type=\"text/css\">
		<!--
		.vl {
			font-family:Arial,Helvetica,sans-serif;
			font-size:12px;
			color:#3c3c3c;
		}
		.vl_red {
			font-family:Arial,Helvetica,sans-serif;
			font-size:12px;
			color:#F00;
		}
		.vl11 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 11px;
			color: #333333;
		}
		.vl11_grey {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 11px;
			color: #CCC;
		}
		.vl10 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 10px;
			color: #333333;
		}
		.vl10_grey {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 10px;
			color: #CCC;
		}
		-->
		</style>
		
		<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"vl\">
		  <!-- Start Header -->
		  <tr>
			<td style=\"padding:5px\" align=\"center\">
				<div align=\"center\"><img src=\"$system_default_path"."images/uganda.emblem.gif\"></div>
				<div align=\"center\"><strong>MINISTRY OF HEALTH UGANDA<br>NATIONAL AIDS CONTROL PROGRAM (ACP)</strong></div>
			</td>
		  </tr>
		  <!-- End Header -->
		  <!-- Start Text -->
		  <tr>
			<td style=\"padding:5px\">
				<div>&nbsp;</div>
				<div style=\"padding:5px; border-bottom: 1px solid #333\" align=\"center\">CENTRAL PUBLIC HEALTH LABORATORIES</div>
				<div style=\"padding:5px 0px 20px 0px\" align=\"center\">Viral Load Test Results</div>
			</td>
		  </tr>
		  <!-- End Text -->
		</table>
		
		<!-- Start Facility and Sample Details -->
		<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"vl\">
		  <tr>
			<!-- Start Facility Details -->
			<td width=\"45%\" style=\"padding:5px\" valign=\"top\">
				<fieldset style=\"width: 100%\">
				<legend><strong>FACILITY DETAILS</strong></legend>
					<div style=\"padding:5px 0px 0px 0px\">
						<table width=\"100%\" border=\"0\" class=\"vl\">
							<tr>
								<td width=\"20%\">Name:</td>
								<td width=\"80%\" style=\"padding: 5px 0px; border-bottom: 1px solid #333\">$facilityName</td>
							</tr>
							<tr>
								<td>District:</td>
								<td>
									<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
									  <tr>
										<td width=\"45%\" style=\"padding: 5px 0px; border-bottom: 1px solid #333\">$districtName</td>
										<td width=\"10%\">Hub:</td>
										<td width=\"45%\" style=\"padding: 5px 0px; border-bottom: 1px solid #333\">$hubName</td>
									  </tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
				</fieldset>
			</td>
			<!-- End Facility Details -->
			<!-- Start Sample Details -->
			<td width=\"55%\" style=\"padding:5px\" valign=\"top\">
				<fieldset style=\"width: 100%\">
				<legend><strong>SAMPLE DETAILS</strong></legend>
					<div style=\"padding:5px 0px 0px 0px\">
						<table width=\"100%\" border=\"0\" class=\"vl\">
							<tr>
								<td width=\"30%\" style=\"padding: 5px 0px\">Form&nbsp;#:</td>
								<td width=\"70%\" style=\"padding: 5px 0px; border-bottom: 1px solid #333\">$formNumber</td>
							</tr>
							<tr>
								<td>Sample&nbsp;Type:</td>
								<td style=\"padding: 4px 0px\">
									<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
									  <tr>
										<td width=\"2%\"><div style=\"height: 18px; width:18px; border: 1px solid #333\" align=\"center\">".($sampleType=="DBS"?"<img src=\"$system_default_path"."images/check.gif\" />":"&nbsp;")."</div></td>
										<td width=\"13%\" style=\"padding:0px 5px 0px 5px\">DBS</td>
										<td width=\"2%\"><div style=\"height: 18px; width:18px; border: 1px solid #333\" align=\"center\">".($sampleType=="Plasma"?"<img src=\"$system_default_path"."images/check.gif\" />":"&nbsp;")."</div></td>
										<td width=\"13%\" style=\"padding:0px 5px 0px 5px\">Plasma</td>
										<td width=\"2%\"><div style=\"height: 18px; width:18px; border: 1px solid #333\" align=\"center\">".($sampleType=="Whole blood"?"<img src=\"$system_default_path"."images/check.gif\" />":"&nbsp;")."</div></td>
										<td width=\"68%\" style=\"padding:0px 0px 0px 5px\">Whole&nbsp;Blood</td>
									  </tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
				</fieldset>
			</td>
			<!-- End Sample Details -->
		  </tr>
		</table>
		<!-- End Facility and Sample Details -->
		
		<!-- Start Patient Information -->
		<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"vl\">
		  <tr>
			<td style=\"padding:5px\" valign=\"top\">
				<fieldset style=\"width: 100%\">
				<legend><strong>PATIENT INFORMATION</strong></legend>
					<div style=\"padding:5px 0px 0px 0px\">
						<table width=\"100%\" border=\"0\" class=\"vl\">
							<tr>
								<!-- Start Column 1 -->
								<td width=\"50%\" valign=\"top\">
									<table width=\"100%\" border=\"0\" class=\"vl\">
									  <tr>
										<td width=\"30%\">ART Number:</td>
										<td width=\"70%\" style=\"padding: 5px 0px; border-bottom: 1px solid #333\">$artNumber</td>
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
												<td width=\"2%\"><div style=\"height: 18px; width:18px; border: 1px solid #333\">".($gender=="Male"?"<img src=\"$system_default_path"."images/check.gif\" />":"&nbsp;")."</div></td>
												<td width=\"18%\" style=\"padding:0px 5px 0px 5px\">Male</td>
												<td width=\"2%\"><div style=\"height: 18px; width:18px; border: 1px solid #333\">".($gender=="Female"?"<img src=\"$system_default_path"."images/check.gif\" />":"&nbsp;")."</div></td>
												<td width=\"18%\" style=\"padding:0px 0px 0px 5px\">Female</td>
												<td width=\"2%\"><div style=\"height: 18px; width:18px; border: 1px solid #333\">".($gender=="Left Blank"?"<img src=\"$system_default_path"."images/check.gif\" />":"&nbsp;")."</div></td>
												<td width=\"58%\" style=\"padding:0px 0px 0px 5px\">Left Blank</td>
											  </tr>
											</table>
										</td>
									  </tr>
									</table>
								</td>
								<!-- End Column 1 -->
								<!-- Start Column 2 -->
								<td width=\"50%\" valign=\"top\">
									<table width=\"100%\" border=\"0\" class=\"vl\">
									  <tr>
										<td width=\"55%\">Date of Birth (DOB):</td>
										<td width=\"45%\" style=\"padding: 5px 0px; border-bottom: 1px solid #333\">".(getFormattedDate($dateOfBirth))."</td>
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
				</fieldset>
			</td>
		  </tr>
		</table>
		<!-- End Patient Information -->
		
		<!-- Start Sample Test Information -->
		<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"vl\">
		  <tr>
			<td style=\"padding:5px\" valign=\"top\">
				<fieldset style=\"width: 100%\">
				<legend><strong>SAMPLE TEST INFORMATION </strong></legend>
					<div style=\"padding:5px 0px 0px 0px\">
						<!-- Start Sample Collection Date -->
						<div style=\"padding:5px 0px; border-bottom: 1px solid #CCC\">
							<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
							  <tr>
								<td width=\"25%\">Sample&nbsp;Collection&nbsp;Date:</td>
								<td width=\"13%\" style=\"padding:0px 5px\">".getFormattedDateLessDay($sampleCollectionDate)."</td>
								<td width=\"13%\">Reception&nbsp;Date:</td>
								<td width=\"12%\" style=\"padding:0px 5px\">".getFormattedDateLessDay($sampleReceiptDate)."</td>
								<td width=\"25%\">Viral&nbsp;Load&nbsp;Test&nbsp;Date:</td>
								<td width=\"12%\" style=\"padding:0px 5px\">".getFormattedDateLessDay($sampleVLTestDate)."</td>
							  </tr>
							</table>
						</div>
						<!-- End Sample Collection Date -->
		
						<!-- Start Repeat Test -->
						<div style=\"padding:5px 0px; border-bottom: 1px solid #CCC\">
							<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
							  <tr>
								<td width=\"25%\">Repeat Test:</td>
								<td width=\"2%\"><div style=\"height: 18px; width:18px; border: 1px solid #333\">".(getDetailedTableInfo2("vl_logs_samplerepeats","sampleID='$sampleID' and withWorksheetID='$worksheetID'","id")?"<img src=\"$home_url"."/images/check.gif\" />":"&nbsp;")."</div></td>
								<td width=\"18%\" style=\"padding:0px 5px 0px 5px\">Yes</td>
								<td width=\"2%\"><div style=\"height: 18px; width:18px; border: 1px solid #333\">".(getDetailedTableInfo2("vl_logs_samplerepeats","sampleID='$sampleID' and withWorksheetID='$worksheetID'","id")?"&nbsp;":"<img src=\"$home_url"."/images/check.gif\" />")."</div></td>
								<td width=\"53%\" style=\"padding:0px 0px 0px 5px\">No</td>
							  </tr>
							</table>
						</div>
						<!-- End Repeat Test -->
		
						<!-- Start Sample Rejected -->
						<div style=\"padding:5px 0px; border-bottom: 1px solid #CCC\">
							<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
							  <tr>
								<td width=\"25%\">Sample Rejected:</td>
								<td width=\"2%\"><div style=\"height: 18px; width:18px; border: 1px solid #333\">&nbsp;</div></td>
								<td width=\"18%\" style=\"padding:0px 5px 0px 5px\">Yes</td>
								<td width=\"2%\"><div style=\"height: 18px; width:18px; border: 1px solid #333\"><img src=\"$system_default_path"."images/check.gif\" /></div></td>
								<td width=\"53%\" style=\"padding:0px 0px 0px 5px\">No</td>
							  </tr>
							</table>
						</div>
						<!-- End Sample Rejected -->
		
						<!-- Start Rejection Reason -->
						<div style=\"padding:5px 0px\">
							<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
							  <tr>
								<td width=\"25%\">If Rejected, Reason:</td>
								<td width=\"75%\" style=\"padding:0px 0px 0px 5px\">&nbsp;</td>
							  </tr>
							</table>
						</div>
						<!-- End Rejection Reason -->
					</div>
				</fieldset>
			</td>
		  </tr>
		</table>
		<!-- End Sample Test Information -->
		
		<!-- Start Viral Load Results -->
		<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"vl\">
		  <tr>
			<td style=\"padding:5px\" valign=\"top\">
				<fieldset style=\"width: 100%\">
				<legend><strong>VIRAL LOAD RESULTS</strong></legend>
					<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
					  <tr>
						<td width=\"80%\">
							<div style=\"padding:5px 0px 0px 0px\">
								<!-- Start Machine Type -->
								<div style=\"padding:5px 0px; border-bottom: 1px solid #CCC\">
									<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
									  <tr>
										<td width=\"30%\">Method Used:</td>
										<td width=\"70%\" style=\"padding:0px 5px\">$methodUsed</td>
									  </tr>
									</table>
								</div>
								<!-- End Machine Type -->
		
								<!-- Start Location ID -->
								<div style=\"padding:5px 0px; border-bottom: 1px solid #CCC\">
									<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
									  <tr>
										<td width=\"30%\">Location ID:</td>
										<td width=\"70%\" style=\"padding:0px 5px\">$locationID</td>
									  </tr>
									</table>
								</div>
								<!-- End Location ID -->
				
								<!-- Start Viral Load Testing # -->
								<div style=\"padding:5px 0px; border-bottom: 1px solid #CCC\">
									<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
									  <tr>
										<td width=\"30%\">Viral Load Testing #:</td>
										<td width=\"70%\" style=\"padding:0px 5px\">$vlSampleID</td>
									  </tr>
									</table>
								</div>
								<!-- End Viral Load Testing # -->
				
								<!-- Start Result of Viral Load -->
								<div style=\"padding:5px 0px\">
									<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
									  <tr>
										<td width=\"30%\">Result of Viral Load:</td>
										<td width=\"70%\" style=\"padding:0px 5px\">$vlResult</td>
									  </tr>
									</table>
								</div>
								<!-- End Result of Viral Load -->
							</div>
						</td>
						".($smiley?"
						<td width=\"20%\" align=\"center\" style=\"padding:10px\">
							<!-- Start Smiley Face -->
							$smiley
							<!-- End Smiley Face -->
						</td>
						":"")."
					  </tr>
					</table>
				</fieldset>
			</td>
		  </tr>
		</table>
		<!-- End Viral Load Results -->
		
		".($recommendation?"
		<!-- Start Recommendation -->
		<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"vl\">
		  <tr>
			<td style=\"padding:5px\" valign=\"top\">
				<fieldset style=\"width: 100%\">
				<legend><strong>RECOMMENDATIONS</strong></legend>
					<!-- Start Clinical Recommendation -->
					<div style=\"padding:10px\">Suggested Clinical Action based on National Guidelines:</div>
					<div style=\"padding:5px 30px\">$recommendation</div>
					<!-- End Clinical Recommendation -->
				</fieldset>
			</td>
		  </tr>
		</table>
		<!-- End Recommendation -->
		":"")."
		
		<!-- Start Sign Offs -->
		<div style=\"padding:20px 0px 0px 0px\">
			<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"vl\">
			  <tr>
				<td width=\"20%\">Lab Technologist:</td>
				<td width=\"30%\" style=\"border-bottom: 1px solid #333\">".($labTechSignature?$labTechSignature:"&nbsp;")."</td>
				<td width=\"20%\">Lab Manager:</td>
				<td width=\"30%\" style=\"border-bottom: 1px solid #333\">".($labManagerSignature?$labManagerSignature:"&nbsp;")."</td>
			  </tr>
			</table>
		</div>
		<!-- End Sign Offs -->";
		
		//filename
		$filename=0;
		$filename="VL.Results.Form.$sampleID.$worksheetID.pdf";
		
		//load PDF object
		$pdf = new DOMPDF();
		$pdf->load_html($html);
		$pdf->set_paper("letter", "portrait");
		$pdf->render();
		$pdf->outputToFile($filename, array("Attachment" => false));
		//$pdf->stream($formNumber, array("Attachment" => false));

		$pdfFileName[]=$filename;
	}
}

//render to PDF
$pdfmerge = 0;
$pdfmerge = new PDFMerger;
for($i=0;$i<count($pdfFileName);$i++) {
	$pdfmerge->addPDF("$system_default_path/downloads.forms/$pdfFileName[$i]","all","P");
}
$pdfmerge->merge('browser', '');
?>