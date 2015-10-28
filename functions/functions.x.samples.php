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
?>