<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

//key variables
$sampleID=validate($sampleID);

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
$html=0;
$html="<style type=\"text/css\">
<!--
div.special { margin: auto; width:82%; padding: 1px; }
div.special table { width:100%; font-size:9px; border-collapse:collapse; }
-->
</style>
<page orientation=\"portrait\" format=\"150x220\" style=\"font-size: 9px\">
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


//load PDF object
try {
	$html2pdf = new HTML2PDF('P', 'A4', 'fr', true, 'UTF-8', 0);
	$html2pdf->writeHTML($html, isset($_GET['vuehtml']));
	$html2pdf->Output($filename);
}
catch(HTML2PDF_exception $e) {
	echo $e;
	exit;
}

//load PDF object
/*
$pdf = new DOMPDF();
$pdf->load_html($html);
$pdf->set_paper("letter", "portrait");
$pdf->render();
//$pdf->outputToFile($filename, array("Attachment" => false));
$pdf->stream($formNumber, array("Attachment" => false));
*/
?>