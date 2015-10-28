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
		<div align=\"center\"><img src=\"$home_url/images/uganda.emblem.gif\"></div>
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
								<td width=\"2%\"><div style=\"height: 18px; width:18px; border: 1px solid #333\" align=\"center\">".($sampleType=="DBS"?"<img src=\"$home_url"."/images/check.gif\" />":"&nbsp;")."</div></td>
								<td width=\"13%\" style=\"padding:0px 5px 0px 5px\">DBS</td>
								<td width=\"2%\"><div style=\"height: 18px; width:18px; border: 1px solid #333\" align=\"center\">".($sampleType=="Plasma"?"<img src=\"$home_url"."/images/check.gif\" />":"&nbsp;")."</div></td>
								<td width=\"13%\" style=\"padding:0px 5px 0px 5px\">Plasma</td>
								<td width=\"2%\"><div style=\"height: 18px; width:18px; border: 1px solid #333\" align=\"center\">".($sampleType=="Whole blood"?"<img src=\"$home_url"."/images/check.gif\" />":"&nbsp;")."</div></td>
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
										<td width=\"2%\"><div style=\"height: 18px; width:18px; border: 1px solid #333\">".($gender=="Male"?"<img src=\"$home_url"."/images/check.gif\" />":"&nbsp;")."</div></td>
										<td width=\"18%\" style=\"padding:0px 5px 0px 5px\">Male</td>
										<td width=\"2%\"><div style=\"height: 18px; width:18px; border: 1px solid #333\">".($gender=="Female"?"<img src=\"$home_url"."/images/check.gif\" />":"&nbsp;")."</div></td>
										<td width=\"18%\" style=\"padding:0px 0px 0px 5px\">Female</td>
										<td width=\"2%\"><div style=\"height: 18px; width:18px; border: 1px solid #333\">".($gender=="Left Blank"?"<img src=\"$home_url"."/images/check.gif\" />":"&nbsp;")."</div></td>
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
						<td width=\"25%\">Viral&nbsp;Load&nbsp;Rejection&nbsp;Date:</td>
						<td width=\"12%\" style=\"padding:0px 5px\">".getFormattedDateLessDay($sampleVLRejectionDate)."</td>
					  </tr>
					</table>
				</div>
				<!-- End Sample Collection Date -->

				<!-- Start Repeat Test -->
				<div style=\"padding:5px 0px; border-bottom: 1px solid #CCC\">
					<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
					  <tr>
						<td width=\"25%\">Repeat Test:</td>
						<td width=\"2%\"><div style=\"height: 18px; width:18px; border: 1px solid #333\">&nbsp;</div></td>
						<td width=\"18%\" style=\"padding:0px 5px 0px 5px\">Yes</td>
						<td width=\"2%\"><div style=\"height: 18px; width:18px; border: 1px solid #333\"><img src=\"$home_url"."/images/check.gif\" /></div></td>
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
						<td width=\"2%\"><div style=\"height: 18px; width:18px; border: 1px solid #333\"><img src=\"$home_url"."/images/check.gif\" /></div></td>
						<td width=\"18%\" style=\"padding:0px 5px 0px 5px\">Yes</td>
						<td width=\"2%\"><div style=\"height: 18px; width:18px; border: 1px solid #333\">&nbsp;</div></td>
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
						<td width=\"75%\" style=\"padding:0px 0px 0px 5px\"><font color=\"#FF0000\">$rejectionReason</font></td>
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

<!-- Start Sign Offs -->
<div style=\"padding:20px 0px 0px 0px\">
	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"vl\">
	  <tr>
		<td width=\"20%\">Lab Manager:</td>
		<td width=\"80%\" style=\"border-bottom: 1px solid #333\">".($labManagerSignature?$labManagerSignature:"&nbsp;")."</td>
	  </tr>
	</table>
</div>
<!-- End Sign Offs -->";

//load PDF object
$pdf = new DOMPDF();
$pdf->load_html($html);
$pdf->set_paper("letter", "portrait");
$pdf->render();
//$pdf->outputToFile($filename, array("Attachment" => false));
$pdf->stream($formNumber, array("Attachment" => false));
?>