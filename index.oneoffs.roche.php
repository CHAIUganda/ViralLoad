<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

/**
* INSTRUCTIONS
* ran as lnx http://192.168.0.43/index.oneoffs.php
* task 1: update the vl_samples while maintaining the logs
* task 2: under http://vl.cphluganda.org/admin/?act=atreatmentstatus, merge all samples with "Left Blank" into "Regimen Left Blank"
* task 3: link all facilities with no hub to "No Hub", and all facilities with no district to "No District"
* task 4: move all samples with sample type "Whole Blood" to type "Plasma"
* task 5: move all samples with sample type "Left Blank" to type "DBS"
* task 6: remove facilities for which there are no samples
* task 7: remove regimen which have no samples
* task 8: remove treatment lines which have no samples or regimen
* task 9: match the districtID and hubID within vl_samples to the facility within vl_facilities
* task 10: remove facilities with no samples
* task 11: populate vl_results_merged
*/

//task 11: populate vl_results_merged, begin with abbott
$query=0;
$query=mysqlquery("select * from vl_results_roche");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		//factor
		$factor=0;
		$factor=getDetailedTableInfo2("vl_results_multiplicationfactor","worksheetID='$q[worksheetID]' limit 1","factor");
		if(!$factor) {
			$factor=1;
		}
		//alphanumeric result
		$resultAlphanumeric=0;
		$resultAlphanumeric=getVLResult("roche",$q["worksheetID"],$q["sampleID"],$factor);
		//numeric result
		$resultNumeric=0;
		$resultNumeric=getVLNumericResultOnly($resultAlphanumeric);
		mysqlquery("insert ignore into vl_results_merged 
						(machine,worksheetID,vlSampleID,resultAlphanumeric,
							resultNumeric,created,createdby) 
						values 
						('roche','$q[worksheetID]','$q[sampleID]','$resultAlphanumeric',
							'$resultNumeric','$q[created]','$q[createdby]')");
	}
}
?>