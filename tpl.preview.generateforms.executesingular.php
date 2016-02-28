<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

//key variables
$formNumber=validate($formNumber);

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
		<td width=\"15%\" style=\"padding:5px; border-top: 1px solid #333; border-bottom: 1px solid #333; border-left: 1px solid #333\" align=\"center\"><img src=\"$home_url/images/uganda.emblem.gif\"></td>
		<td width=\"70%\" style=\"padding:5px; border: 1px solid #333\" align=\"center\"><strong>MINISTRY OF HEALTH UGANDA<br>NATIONAL AIDS CONTROL PROGRAM (ACP)</strong></td>
		<td width=\"15%\" style=\"padding:5px; border-top: 1px solid #333; border-right: 1px solid #333; border-bottom: 1px solid #333\" align=\"center\" valign=\"middle\">
			<div><strong>FORM&nbsp;#</strong></div>
			<div style=\"padding:5px 0px 0px 0px\" class=\"vl_red\"><strong>$formNumber</strong></div>
		</td>
	  </tr>
	  <!-- End Header -->
	  <!-- Start Line 1 Text -->
	  <tr>
		<td style=\"padding:5px\">&nbsp;</td>
		<td style=\"padding:5px; border-bottom: 1px solid #333\" align=\"center\">Lab Request Form for HIV Viral Load Analysis</td>
		<td style=\"padding:5px\">&nbsp;</td>
	  </tr>
	  <!-- End Line 1 Text -->
	  <!-- Start Line 2 Text -->
	  <tr>
		<td style=\"padding:5px\">&nbsp;</td>
		<td style=\"padding:5px\" align=\"center\"><em>Sample Identification Information: To be completed by Health Facility Laboratory Staff</em></td>
		<td style=\"padding:5px\">&nbsp;</td>
	  </tr>
	  <!-- End Line 2 Text -->
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
							<td width=\"80%\" style=\"border-bottom: 1px solid #333\">&nbsp;</td>
						</tr>
						<tr>
							<td>District:</td>
							<td>
								<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
								  <tr>
									<td width=\"60%\" style=\"border-bottom: 1px solid #333\">&nbsp;</td>
									<td width=\"10%\">Hub:</td>
									<td width=\"30%\" style=\"border-bottom: 1px solid #333\">&nbsp;</td>
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
							<td width=\"30%\">Date&nbsp;of&nbsp;Collection:</td>
							<td width=\"70%\" class=\"vl11_grey\" style=\"border-bottom: 1px solid #333\">DD/MM/YYYY</td>
						</tr>
						<tr>
							<td>Sample&nbsp;Type:</td>
							<td>
								<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
								  <tr>
									<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
									<td width=\"13%\" style=\"padding:0px 5px 0px 5px\">DBS</td>
									<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
									<td width=\"13%\" style=\"padding:0px 5px 0px 5px\">Plasma</td>
									<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
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
			<legend><strong>PATIENT DETAILS</strong> (To be completed by Clinician)</legend>
				<div style=\"padding:5px 0px 0px 0px\">
					<table width=\"100%\" border=\"0\" class=\"vl\">
						<tr>
							<!-- Start Column 1 -->
							<td width=\"50%\" valign=\"top\">
								<table width=\"100%\" border=\"0\" class=\"vl\">
								  <tr>
									<td width=\"30%\">ART Number:</td>
									<td width=\"70%\" style=\"border-bottom: 1px solid #333\">&nbsp;</td>
								  </tr>
								  <tr>
									<td>Other ID:</td>
									<td style=\"border-bottom: 1px solid #333\">&nbsp;</td>
								  </tr>
								  <tr>
									<td>Gender:</td>
									<td>
										<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
										  <tr>
											<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
											<td width=\"18%\" style=\"padding:0px 5px 0px 5px\">Male</td>
											<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
											<td width=\"78%\" style=\"padding:0px 0px 0px 5px\">Female</td>
										  </tr>
										</table>
									</td>
								  </tr>
								  <tr>
									<td>Phone Number:</td>
									<td style=\"border-bottom: 1px solid #333\" class=\"vl11_grey\">+256</td>
								  </tr>
								</table>
							</td>
							<!-- End Column 1 -->
							<!-- Start Column 2 -->
							<td width=\"50%\" valign=\"top\">
								<table width=\"100%\" border=\"0\" class=\"vl\">
								  <tr>
									<td width=\"55%\">Date of Birth (DOB):</td>
									<td width=\"45%\" style=\"border-bottom: 1px solid #333\" class=\"vl11_grey\">DD/MM/YYYY</td>
								  </tr>
								  <tr>
									<td>If DOB Unknown, Age in Years:</td>
									<td style=\"border-bottom: 1px solid #333\">&nbsp;</td>
								  </tr>
								  <tr>
									<td>If &lt; 1 year, Age in Months:</td>
									<td style=\"border-bottom: 1px solid #333\">&nbsp;</td>
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
	
	<!-- Start Treatment Information -->
	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"vl\">
	  <tr>
		<td style=\"padding:5px\" valign=\"top\">
			<fieldset style=\"width: 100%\">
			<legend><strong>TREATMENT INFORMATION</strong></legend>
				<div style=\"padding:5px 0px 0px 0px\">
					<!-- Start Has Patient Been on treatment for at least 6 months? -->
					<div style=\"padding:0px 0px 5px 0px; border-bottom: 1px solid #CCC\">
						<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
						  <tr>
							<td width=\"45%\">Has Patient Been on treatment for at least 6 months?</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"8%\" style=\"padding:0px 5px\">Yes</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"43%\" style=\"padding:0px 5px\">No</td>
						  </tr>
						</table>
					</div>
					<!-- End Has Patient Been on treatment for at least 6 months? -->
	
					<!-- Start Date of Treatment Initiation/Current Regimen -->
					<div style=\"padding:0px 0px 5px 0px; border-bottom: 1px solid #CCC\">
						<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
						  <tr>
							<td width=\"30%\">Date of Treatment Initiation:</td>
							<td width=\"30%\" style=\"border-bottom: 1px solid #333\" class=\"vl11_grey\">DD/MM/YYYY</td>
							<td width=\"20%\">Current Regimen:</td>
							<td width=\"20%\" style=\"border-bottom: 1px solid #333\" class=\"vl11_grey\">(use code below)</td>
						  </tr>
						</table>
				</div>
					<!-- End Date of Treatment Initiation/Current Regimen -->
	
					<!-- Start Indication for Treatment Initiation -->
					<div style=\"padding:0px 0px 5px 0px; border-bottom: 1px solid #CCC\">
						<div style=\"padding:5px 0px 0px 0px\">
						<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
						  <tr>
							<td width=\"32%\">Indication for Treatment Initiation:</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"18%\" style=\"padding:0px 5px\">PMTCT/Option B+</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"13%\" style=\"padding:0px 5px\">Child Under 15</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"13%\" style=\"padding:0px 5px\">CD4&lt;500</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"15%\" style=\"padding:0px 5px\">TB&nbsp;Infection</td>
						  </tr>
						</table>
						</div>
						<div style=\"padding:5px 0px 0px 0px\">
						<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
						  <tr>
							<td width=\"32%\">&nbsp;</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"8%\" style=\"padding:0px 5px\">Other</td>
							<td width=\"30%\">If Other, Provide Details:</td>
							<td width=\"28%\" style=\"border-bottom: 1px solid #333\">&nbsp;</td>
						  </tr>
						</table>
						</div>
					</div>
					<!-- End Indication for Treatment Initiation -->
	
					<!-- Start Which Treatment Line is Patient on -->
					<div style=\"padding:0px 0px 5px 0px; border-bottom: 1px solid #CCC\">
						<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
						  <tr>
							<td width=\"30%\">Which Treatment Line is Patient on?</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"8%\" style=\"padding:0px 5px\">First</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"8%\" style=\"padding:0px 5px\">Second</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"48%\" style=\"padding:0px 5px\">Third</td>
						  </tr>
						</table>
					</div>
					<!-- End Which Treatment Line is Patient on -->
	
					<!-- Start Reason for Failure? -->
					<div style=\"padding:0px 0px 5px 0px; border-bottom: 1px solid #CCC\">
						<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
						  <tr>
							<td width=\"30%\">Reason for Failure?</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"8%\" style=\"padding:0px 5px\">N/A</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"8%\" style=\"padding:0px 5px\">Virological</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"8%\" style=\"padding:0px 5px\">Immunological</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"38%\" style=\"padding:0px 5px\">Clinical</td>
						  </tr>
						</table>
					</div>
					<!-- End Reason for Failure? -->
	
					<!-- Start Is Patient Pregnant? -->
					<div style=\"padding:0px 0px 5px 0px; border-bottom: 1px solid #CCC\">
						<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
						  <tr>
							<td width=\"30%\">Is Patient Pregnant?</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"8%\" style=\"padding:0px 5px\">Yes</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"8%\" style=\"padding:0px 5px\">No</td>
							<td width=\"30%\" style=\"padding:0px 5px\">If Patient is Pregnant, ANC #:</td>
							<td width=\"20%\" style=\"border-bottom: 1px solid #333\">&nbsp;</td>
						  </tr>
						</table>
					</div>
					<!-- End Is Patient Pregnant? -->
	
					<!-- Start Is Patient Breastfeeding? -->
					<div style=\"padding:0px 0px 5px 0px; border-bottom: 1px solid #CCC\">
						<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
						  <tr>
							<td width=\"30%\">Is Patient Breastfeeding?</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"8%\" style=\"padding:0px 5px\">Yes</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"58%\" style=\"padding:0px 5px\">No</td>
						  </tr>
						</table>
					</div>
					<!-- End Is Patient Breastfeeding? -->
	
					<!-- Start Patient has Active TB? -->
					<div style=\"padding:0px 0px 5px 0px; border-bottom: 1px solid #CCC\">
						<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
						  <tr>
							<td width=\"30%\">Patient has Active TB?</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"8%\" style=\"padding:0px 5px\">Yes</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"1%\" style=\"padding:0px 5px\">No</td>
							<td width=\"16%\" style=\"padding:0px 5px\">If&nbsp;Yes,&nbsp;are&nbsp;they&nbsp;on</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"12%\" style=\"padding:0px 5px\">Initiation, or</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"24%\" style=\"padding:0px 5px\">Continuation Phase?</td>
						  </tr>
						</table>
					</div>
					<!-- End Patient has Active TB? -->
	
					<!-- Start ARV Adherence -->
					<div style=\"padding:0px 0px 5px 0px\">
						<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
						  <tr>
							<td width=\"30%\">ARV Adherence:</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"18%\" style=\"padding:0px 5px\">Good &gt; 95%</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"18%\" style=\"padding:0px 5px\">Fair 85 - 94%</td>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"28%\" style=\"padding:0px 5px\">Poor &lt; 85%</td>
						  </tr>
						</table>
					</div>
					<!-- End ARV Adherence -->
				</div>
			</fieldset>
		</td>
	  </tr>
	</table>
	<!-- End Treatment Information -->
	
	<!-- Start Indication for Viral Load Testing (please tick one) -->
	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"vl\">
	  <tr>
		<td style=\"padding:5px\" valign=\"top\">
			<fieldset style=\"width: 100%\">
			<legend><strong>Indication for Viral Load Testing</strong> (please tick one): (To be completed by Clinician)</legend>
				<div style=\"padding:5px 0px 0px 0px\">
					<!-- Start Routine Monitoring -->
					<div style=\"padding:0px 0px 5px 0px\">
						<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
						  <tr>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"30%\" style=\"padding: 0px 0px 0px 5px\">Routine Monitoring</td>
							<td width=\"20%\">Last Viral Load Date:</td>
							<td width=\"2%\" class=\"vl11_grey\" style=\"border-bottom: 1px solid #333\">DD/MM/YYYY</td>
							<td width=\"8%\" style=\"padding: 0px 0px 0px 5px\">Value:</td>
							<td width=\"10%\" class=\"vl11_grey\" style=\"border-bottom: 1px solid #333\">(copies/ml)</td>
							<td width=\"13%\">Sample Type:</td>
							<td width=\"15%\" class=\"vl11_grey\" style=\"border-bottom: 1px solid #333\">&nbsp;</td>
						  </tr>
						</table>
					</div>
					<!-- End Routine Monitoring -->
					
					<!-- Start Repeat Viral Load Test after Suspected Treatment Failure adherence counseling -->
					<div style=\"padding:0px 0px 5px 0px\">
						<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
						  <tr>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"30%\" style=\"padding: 0px 0px 0px 5px\">Repeat Viral Load Test after Suspected Treatment Failure adherence counseling</td>
							<td width=\"20%\">Last Viral Load Date:</td>
							<td width=\"2%\" class=\"vl11_grey\" style=\"border-bottom: 1px solid #333\">DD/MM/YYYY</td>
							<td width=\"8%\" style=\"padding: 0px 0px 0px 5px\">Value:</td>
							<td width=\"10%\" class=\"vl11_grey\" style=\"border-bottom: 1px solid #333\">(copies/ml)</td>
							<td width=\"13%\">Sample Type:</td>
							<td width=\"15%\" class=\"vl11_grey\" style=\"border-bottom: 1px solid #333\">&nbsp;</td>
						  </tr>
						</table>
					</div>
					<!-- End Repeat Viral Load Test after Suspected Treatment Failure adherence counseling -->
					
					<!-- Start Suspected Treatment Failure -->
					<div style=\"padding:0px 0px 5px 0px\">
						<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
						  <tr>
							<td width=\"2%\"><img src=\"$home_url/images/checkbox.gif\"></td>
							<td width=\"30%\" style=\"padding: 0px 0px 0px 5px\">Suspected Treatment Failure</td>
							<td width=\"20%\">Last Viral Load Date:</td>
							<td width=\"2%\" class=\"vl11_grey\" style=\"border-bottom: 1px solid #333\">DD/MM/YYYY</td>
							<td width=\"8%\" style=\"padding: 0px 0px 0px 5px\">Value:</td>
							<td width=\"10%\" class=\"vl11_grey\" style=\"border-bottom: 1px solid #333\">(copies/ml)</td>
							<td width=\"13%\">Sample Type:</td>
							<td width=\"15%\" class=\"vl11_grey\" style=\"border-bottom: 1px solid #333\">&nbsp;</td>
						  </tr>
						</table>
					</div>
					<!-- End Suspected Treatment Failure -->
				</div>
			</fieldset>
		</td>
	  </tr>
	  <!-- Start ART Codes -->
	  <tr>
		<td style=\"padding:5px\" valign=\"top\"><strong>ART Codes</strong></td>
	  </tr>
	  <!-- End ART Codes -->
	  <tr>
		<td style=\"padding:5px\" valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"vl\">
		  <tr>
			<td width=\"30%\" style=\"padding: 5px; border: 1px solid #333\" valign=\"top\">
				<!-- Start Adult 1st-Line Regimens: -->
				<div><strong>Adult 1st-Line Regimens:</strong></div>
				<div>
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				  <tr>
					<td width=\"50%\" valign=\"top\">
					<div class=\"vl10\"><strong>1c</strong> = AZT-3TC-NVP</div>
					<div class=\"vl10\"><strong>1d</strong> = AZT-3TC-EFV</div>
					<div class=\"vl10\"><strong>1e</strong> = TDF-3TC-NVP</div>
					<div class=\"vl10\"><strong>1f</strong> = TDF-3TC-EFV</div>
					<div class=\"vl10\"><strong>1g</strong> = TDF-FTC-NVP</div>
					<div class=\"vl10\"><strong>1h</strong> = TDF-FTC-EFV</div>
					</td>
					<td width=\"50%\" valign=\"top\">
					<div class=\"vl10\"><strong>1i</strong> = ABC-3TC-EFV</div>
					<div class=\"vl10\"><strong>1j</strong> = ABC-3TC-NVP</div>
					</td>
				  </tr>
				</table>
				</div>
				<!-- Start Adult 1st-Line Regimens: -->
			</td>
			<td width=\"1%\">&nbsp;</td>
			<td width=\"19%\" style=\"padding: 5px; border: 1px solid #333\" valign=\"top\">
				<!-- Start Child 1st-Line Regimens: -->
				<div><strong>Child 1st-Line Regimens:</strong></div>
				<div>
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				  <tr>
					<td valign=\"top\">
					<div class=\"vl10\"><strong>4a</strong> = d4T-3TC-NVP</div>
					<div class=\"vl10\"><strong>4b</strong> = d4T-3TC-EFV</div>
					<div class=\"vl10\"><strong>4c</strong> = AZT-3TC-NVP</div>
					<div class=\"vl10\"><strong>4d</strong> = AZT-3TC-EFV</div>
					<div class=\"vl10\"><strong>4e</strong> = ABC-3TC-NVP</div>
					<div class=\"vl10\"><strong>4f</strong> = ABC-3TC-EFV</div>
					</td>
				  </tr>
				</table>
				</div>
				<!-- Start Child 1st-Line Regimens: -->
			</td>
			<td width=\"1%\">&nbsp;</td>
			<td width=\"30%\" style=\"padding: 5px; border: 1px solid #333\" valign=\"top\">
				<!-- Start Adult 2nd-Line Regimens: -->
				<div><strong>Adult 1st-Line Regimens:</strong></div>
				<div>
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				  <tr>
					<td width=\"50%\" valign=\"top\">
					<div class=\"vl10\"><strong>2b</strong> = TDF-3TC-LPV/r</div>
					<div class=\"vl10\"><strong>2c</strong> = TDF-FTC-LPV/r</div>
					<div class=\"vl10\"><strong>2e</strong> = AZT-3TC-LPV/r</div>
					<div class=\"vl10\"><strong>2f</strong> = TDF-FTC-ATV/r</div>
					<div class=\"vl10\"><strong>2g</strong> = TDF-3TC-ATV/r</div>
					<div class=\"vl10\"><strong>2h</strong> = AZT-3TC-ATV/r</div>
					</td>
					<td width=\"50%\" valign=\"top\">
					<div class=\"vl10\"><strong>2i</strong> = ABC-3TC-LPV/r</div>
					<div class=\"vl10\"><strong>2j</strong> = ABC-3TC-ATV/r</div>
					</td>
				  </tr>
				</table>
				</div>
				<!-- Start Adult 2nd-Line Regimens: -->
			</td>
			<td width=\"1%\">&nbsp;</td>
			<td width=\"18%\" style=\"padding: 5px; border: 1px solid #333\" valign=\"top\">
				<!-- Start Child 2nd-Line Regimens: -->
				<div><strong>Child 2nd-Line Regimens:</strong></div>
				<div>
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				  <tr>
					<td valign=\"top\">
					<div class=\"vl10\"><strong>5d</strong> = TDF-3TC-LPV/r</div>
					<div class=\"vl10\"><strong>5e</strong> = TDF-FTC-LPV/r</div>
					<div class=\"vl10\"><strong>5g</strong> = AZT-ABC-LPV/r</div>
					<div class=\"vl10\"><strong>5i</strong> = AZT-3TC-ATV/r</div>
					<div class=\"vl10\"><strong>5j</strong> = ABC-3TC-LPV/r</div>
					<div class=\"vl10\"><strong>5k</strong> = ABC-3TC-ATV/r</div>
					</td>
				  </tr>
				</table>
				</div>
				<!-- Start Child 2nd-Line Regimens: -->
			</td>
		  </tr>
		</table></td>
	  </tr>
	</table>
	<!-- End Treatment Information -->
	
	<!-- Start Sign Offs -->
	<div style=\"padding:10px 0px 0px 0px\">
		<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"vl\">
		  <tr>
			<td width=\"20%\">Requesting Clinician:</td>
			<td width=\"20%\" style=\"border-bottom: 1px solid #333\">&nbsp;</td>
			<td width=\"5%\">Signature:</td>
			<td width=\"20%\" style=\"border-bottom: 1px solid #333\">&nbsp;</td>
			<td width=\"20%\">Request Date:</td>
			<td width=\"15%\" class=\"vl11_grey\" style=\"border-bottom: 1px solid #333\">DD/MM/YYYY</td>
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