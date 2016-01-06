<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

/**
* INSTRUCTIONS
* ran as lnx http://192.168.0.43/index.oneoffs.php
* task 1: get all sample IDs from abbott which appear more than once in vl_results_merged 
* task 2: get all sample IDs from roche which appear more than once in vl_results_merged 
* task 3: a procedure to remove result override for data collected after 29/dec/15
	this was done because the system was applying the override to samples that had failed once not twice
	the correct procedure is to apply the override to samples that have failed twice
* task 4: a procedure to override key abbott results based on this query
	select sampleID,count(id) num from vl_results_abbott where result='3119 A no clot exit detected error was encountered by the Liquid Handler.' and sampleID not in (select sampleID from vl_results_override) group by sampleID having num>1 order by num desc
* task 5: fix a result typo
	update vl_results_override set result='There is No Result Given. The Test Failed the Quality Control Criteria. We advise you send a new sample.' where result='There is No Result Given. The Test Failed the Quality Control Criteria. We advise you send a a new sample.'

//key variables
$rocheFailedResult=0;
$rocheFailedResult="Invalid Test Result. Insufficient sample remained to repeat the assay.";
$abbottFailedResult=0;
$abbottFailedResult="Failed.";

//task 1: results from abbott
$machine="abbott";
$query=0;
$query=mysqlquery("select vlSampleID,count(id) num,max(created) dateCreated from vl_results_merged 
							where 
								machine='abbott' and 
									resultAlphanumeric='$abbottFailedResult' and 
										vlSampleID!='HIV_NEG' and 
											vlSampleID!='HIV_HIPOS' and 
												vlSampleID!='HIV_LOPOS' and 
													vlSampleID!='HIV_CALA' and 
														vlSampleID!='HIV_CALB' 
															group by vlSampleID having num>1 order by num desc");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		//log result override
		logResultOverride($q["vlSampleID"],getDetailedTableInfo2("vl_results_merged","lower(machine)='".strtolower($machine)."' and vlSampleID='$q[vlSampleID]' and created='$q[dateCreated]' limit 1","worksheetID"),$default_resultFailureNewSampleMessage);
	}
}

//task 2: results from roche
$machine="roche";
$query=0;
$query=mysqlquery("select vlSampleID,count(id) num,max(created) dateCreated from vl_results_merged 
							where 
								machine='roche' and 
									resultAlphanumeric='$rocheFailedResult' 
										group by vlSampleID having num>1 order by num desc");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		//log result override
		logResultOverride($q["vlSampleID"],getDetailedTableInfo2("vl_results_merged","lower(machine)='".strtolower($machine)."' and vlSampleID='$q[vlSampleID]' and created='$q[dateCreated]' limit 1","worksheetID"),$default_resultFailureNewSampleMessage);
	}
}

//task 3: a procedure to remove result override for data collected after 29/dec/15
$query=0;
$query=mysqlquery("select vlSampleID,created,count(id) num from vl_results_merged 
							where 
								vlSampleID in (select sampleID from vl_results_override) and 
									date(created)>='2015-12-27' 
										group by vlSampleID having num=1");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		//get the id
		$id=0;
		$id=getDetailedTableInfo2("vl_results_override","sampleID='$q[vlSampleID]' and date(created)>='2015-12-27' order by created desc limit 1","id");
		//log and remove record
		logDataRemoval("delete from vl_results_override where id='$id'");
		mysqlquery("delete from vl_results_override where id='$id'");
	}
}
*/

//task 4: a procedure to override key abbott results
$query=0;
$query=mysqlquery("select sampleID, count(id) num, max(created) dateCreated from vl_results_abbott 
						where 
							result='3119 A no clot exit detected error was encountered by the Liquid Handler.' and 
								sampleID not in (select sampleID from vl_results_override) 
									group by sampleID having num>1 order by num desc");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		//log result override
		logResultOverride($q["sampleID"],getDetailedTableInfo2("vl_results_abbott","sampleID='$q[sampleID]' and created='$q[dateCreated]' limit 1","worksheetID"),$default_resultFailureNewSampleMessage);
	}
}

//task 5: fix a result typo
mysqlquery("update vl_results_override set result='There is No Result Given. The Test Failed the Quality Control Criteria. We advise you send a new sample.' where result='There is No Result Given. The Test Failed the Quality Control Criteria. We advise you send a a new sample.'");
?>