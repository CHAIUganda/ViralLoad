<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* SYSTEM/SCHEDULED RANS: vlDC
*/

/**
* system ran last
* register vlDC functions, and process the requests
*/
$vlDC->registerFunction("XloadHub");
$vlDC->registerFunction("XloadDistrict");
$vlDC->registerFunction("XloadArtHistory");
$vlDC->registerFunction("XloadFacilityFromFormName");
$vlDC->processRequests();
?>