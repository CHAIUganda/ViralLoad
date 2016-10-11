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
function XloadHub($formname,$updateFieldID,$hubFieldID,$hubTextID,$facilityID) {
	$facilityID=validate($facilityID);
	$objResponse = new vlDCResponse();
	$hubID=0;
	$hubID=getDetailedTableInfo2("vl_facilities","id='$facilityID'","hubID");
	if($hubID) {
		//hub name
		$hubName=0;
		$hubName=getDetailedTableInfo2("vl_hubs","id='$hubID'","hub");

		$objResponse->addAssign("$updateFieldID","innerHTML","");
		$objResponse->addScript("document.$formname.$hubFieldID.value='$hubID'");
		$objResponse->addAssign("$hubTextID","innerHTML",$hubName);
	} else {
		$objResponse->addAssign("$updateFieldID","innerHTML","");
	}
	return $objResponse->getXML();
}

/**
* update employer
*/
function XloadDistrict($formname,$updateFieldID,$districtFieldID,$districtTextID,$facilityID) {
	$facilityID=validate($facilityID);
	$objResponse = new vlDCResponse();
	$districtID=0;
	$districtID=getDetailedTableInfo2("vl_facilities","id='$facilityID'","districtID");
	if($districtID) {
		//district name
		$districtName=0;
		$districtName=getDetailedTableInfo2("vl_districts","id='$districtID'","district");

		$objResponse->addAssign("$updateFieldID","innerHTML","");
		$objResponse->addScript("document.$formname.$districtFieldID.value='$districtID'");
		$objResponse->addAssign("$districtTextID","innerHTML",$districtName);
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
		$artNumberModified=0;
		$artNumberModified=$artNumber;
		//clean art number by removing - . / and spaces
		$artNumberModified=preg_replace("/\-/s","",$artNumberModified);
		$artNumberModified=preg_replace("/\./s","",$artNumberModified);
		$artNumberModified=preg_replace("/\//s","",$artNumberModified);
		$artNumberModified=preg_replace("/\s/s","",$artNumberModified);

		$uniqueID=$facilityID."-A-$artNumberModified";
		//$patientID=getDetailedTableInfo2("vl_patients","uniqueID='$uniqueID' and artNumber='$artNumber' limit 1","id");
		$patientID=getDetailedTableInfo2("vl_patients","uniqueID='$uniqueID' limit 1","id");
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
								<td class=\"vl_tdsub\" width=\"85%\"><strong>Location&nbsp;ID</strong></td>
								<td class=\"vl_tdsub\" width=\"5%\" align=\"center\"><strong>Received&nbsp;at&nbsp;CPHL</strong></td>
								<td class=\"vl_tdsub\" width=\"5%\" align=\"center\"><strong>Date&nbsp;of&nbsp;Birth</strong></td>
							</tr>";
			$q=array();
			$count=0;
			while($q=mysqlfetcharray($query)) {
				$count+=1;
				$queryResults.="<tr onMouseover=\"this.bgColor='#f0e6dd'\" onMouseout=\"this.bgColor='#ebdfd4'\">
									<td class=\"".($count<$num?"vl_tdstandard":"vl_tdnoborder")."\" style=\"padding:8px\">$q[formNumber]</td>
									<td class=\"".($count<$num?"vl_tdstandard":"vl_tdnoborder")."\" style=\"padding:8px\">$q[lrCategory]$q[lrEnvelopeNumber]/$q[lrNumericID]</td>
									<td class=\"".($count<$num?"vl_tdstandard":"vl_tdnoborder")."\" align=\"center\">".getFormattedDateLessDay($q["receiptDate"])."</td>
									<td class=\"".($count<$num?"vl_tdstandard":"vl_tdnoborder")."\" align=\"center\">".getFormattedDateLessDay(getDetailedTableInfo2("vl_patients","id='$patientID' limit 1","dateOfBirth"))."</td>
								</tr>";
			}
			$queryResults.="</table>";
			//return results
			$objResponse->addAssign("artNumberHistoryID","innerHTML","<div style=\"border: 1px solid #c3ab94; background-color: #ebdfd4; padding: 10px\">$queryResults</div>");
		} else {
			$objResponse->addAssign("artNumberHistoryID","innerHTML","");
		}
	} else {
		$objResponse->addAssign("artNumberHistoryID","innerHTML","");
	}
	return $objResponse->getXML();
}

/**
* update application number
*/
function XloadFacilityFromFormName($formnumber,$formName,$fieldID,$facilityIDField) {
	//validate
	$formnumber=validate($formnumber);
	$formName=validate($formName);
	$fieldID=validate($fieldID);
	$facilityIDField=validate($facilityIDField);
	$objResponse = new vlDCResponse();
	//reference number
	$refNumber=0;
	$refNumber=getDetailedTableInfo2("vl_forms_clinicalrequest","formNumber='$formnumber' limit 1","refNumber");
	//facility ID
	$facilityID=0;
	$facilityID=getDetailedTableInfo2("vl_forms_clinicalrequest_dispatch","refNumber='$refNumber' limit 1","facilityID");
	if($facilityID) {
		//load facilities
		$facilities=0;
		$facilities="<select name=\"$fieldID\" id=\"$fieldID\" class=\"search\" onchange=\"getHubDistrict(),checkForHubDistrict(), loadArtHistory(document.$formName.artNumber,document.$formName.facilityID.value)\">";
		$query=0;
		$query=mysqlquery("select * from vl_facilities where facility!='' order by facility");
		$facilities.="<option value=\"$facilityID\" selected=\"selected\">".getDetailedTableInfo2("vl_facilities","id='$facilityID' limit 1","facility")."</option>";
		if(mysqlnumrows($query)) {
			while($q=mysqlfetcharray($query)) {
				$facilities.="<option value=\"$q[id]\">$q[facility]</option>";
			}
		}
		$facilities.="</select>";
		//load responses
		$objResponse->addAssign("$facilityIDField","innerHTML",$facilities);
		$objResponse->addScript("checkForHubDistrict()");
		$objResponse->addScript("loadArtHistory(document.$formName.artNumber,'$facilityID')");
	}
	return $objResponse->getXML();
}
?>