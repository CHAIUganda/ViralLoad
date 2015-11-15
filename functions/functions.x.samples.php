<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* SAMPLES SPECIFIC FUNCTIONS: LOAD SAMPLE DATA
*/

/**
* update application number
*/
function XloadHub($formname,$updateFieldID,$hubFieldID,$facilityID) {
	$facilityID=validate($facilityID);
	$objResponse = new vlDCResponse();
	$hubID=0;
	$hubID=getDetailedTableInfo2("vl_facilities","id='$facilityID'","hubID");
	if($hubID) {
		$objResponse->addAssign("$updateFieldID","innerHTML","");
		$objResponse->addScript("document.$formname.$hubFieldID.value='$hubID'");
	} else {
		$objResponse->addAssign("$updateFieldID","innerHTML","");
	}
	return $objResponse->getXML();
}

/**
* update employer
*/
function XloadDistrict($formname,$updateFieldID,$districtFieldID,$facilityID) {
	$facilityID=validate($facilityID);
	$objResponse = new vlDCResponse();
	$districtID=0;
	$districtID=getDetailedTableInfo2("vl_facilities","id='$facilityID'","districtID");
	if($districtID) {
		$objResponse->addAssign("$updateFieldID","innerHTML","");
		$objResponse->addScript("document.$formname.$districtFieldID.value='$districtID'");
	} else {
		$objResponse->addAssign("$updateFieldID","innerHTML","");
	}
	return $objResponse->getXML();
}

/**
* update employer
*/
function XloadArtHistory($artNumber,$facilityID) {
	$artNumber=validate($artNumber);
	$facilityID=validate($facilityID);
	//concatenations, patient ID
	$uniqueID=0;
	$patientID=0;
	if($artNumber) {
		$uniqueID=$facilityID."-A-$artNumber";
		$patientID=getDetailedTableInfo2("vl_patients","uniqueID='$uniqueID' and artNumber='$artNumber' limit 1","id");
	}

	$objResponse = new vlDCResponse();
	if($artNumber && $facilityID && $patientID) {
		//get historical samples from this ART/Facility
		$query=0;
		$query=mysqlquery("select * from vl_samples where patientUniqueID='$uniqueID' order by receiptDate desc limit 3");
		$queryResults="";
		$num=0;
		$num=mysqlnumrows($query);
		if($num) {
			$queryResults="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
							<tr>
								<td colspan=\"3\" class=\"vls_grey\" style=\"padding:0px 0px 8px 0px\">ART # <strong>$artNumber</strong> VL Testing History (last 3)</td>
							</tr>
							<tr>
								<td class=\"vl_tdsub\" width=\"5%\"><strong>Form&nbsp;#</strong></td>
								<td class=\"vl_tdsub\" width=\"90%\"><strong>Location&nbsp;ID</strong></td>
								<td class=\"vl_tdsub\" width=\"5%\" align=\"center\"><strong>Received&nbsp;at&nbsp;CPHL</strong></td>
							</tr>";
			$q=array();
			$count=0;
			while($q=mysqlfetcharray($query)) {
				$count+=1;
				$queryResults.="<tr onMouseover=\"this.bgColor='#f0e6dd'\" onMouseout=\"this.bgColor='#ebdfd4'\">
									<td class=\"".($count<$num?"vl_tdstandard":"vl_tdnoborder")."\" style=\"padding:8px\">$q[formNumber]</td>
									<td class=\"".($count<$num?"vl_tdstandard":"vl_tdnoborder")."\" style=\"padding:8px\">$q[lrCategory]$q[lrEnvelopeNumber]/$q[lrNumericID]</td>
									<td class=\"".($count<$num?"vl_tdstandard":"vl_tdnoborder")."\" align=\"center\">".getFormattedDateLessDay($q["receiptDate"])."</td>
								</tr>";
			}
			$queryResults.="</table>";
		}
		
		$objResponse->addAssign("artNumberHistoryID","innerHTML","<div style=\"border: 1px solid #c3ab94; background-color: #ebdfd4; padding: 10px\">$queryResults</div>");
	} else {
		$objResponse->addAssign("artNumberHistoryID","innerHTML","");
	}
	return $objResponse->getXML();
}
?>