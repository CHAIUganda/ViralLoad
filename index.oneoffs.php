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
*/

//task 1: update vl_samples
$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='32'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='33'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='35'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='36'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='38'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='39'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='40'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='41'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='42'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"13");
		mysqlquery("update vl_samples set currentRegimenID='13' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='43'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='44'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='45'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"13");
		mysqlquery("update vl_samples set currentRegimenID='13' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='46'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='47'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='48'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='49'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='50'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='51'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='52'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='53'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='54'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='55'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"1");
		mysqlquery("update vl_samples set currentRegimenID='1' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='56'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='57'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"13");
		mysqlquery("update vl_samples set currentRegimenID='13' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='58'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='59'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='60'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='61'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='62'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='63'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='64'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"13");
		mysqlquery("update vl_samples set currentRegimenID='13' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='65'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"4");
		mysqlquery("update vl_samples set currentRegimenID='4' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='66'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"13");
		mysqlquery("update vl_samples set currentRegimenID='13' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='67'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='68'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"4");
		mysqlquery("update vl_samples set currentRegimenID='4' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='69'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"71");
		mysqlquery("update vl_samples set currentRegimenID='71' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where currentRegimenID='70'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","currentRegimenID",$q["id"],$q["currentRegimenID"],"11");
		mysqlquery("update vl_samples set currentRegimenID='11' where id='$q[id]'");
	}
}

//task 2: under http://vl.cphluganda.org/admin/?act=atreatmentstatus, merge all samples with "Left Blank" into "Regimen Left Blank"
$query=0;
$query=mysqlquery("select * from vl_samples where treatmentStatusID='4'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","treatmentStatusID",$q["id"],$q["currentRegimenID"],"5");
		mysqlquery("update vl_samples set treatmentStatusID='5' where id='$q[id]'");
	}
}

//task 3: link all facilities with no hub to "No Hub", and all facilities with no district to "No District"
$noHubID=0;
$noHubID=getDetailedTableInfo2("vl_hubs","hub like '%no hub%' limit 1","id");

$query=0;
$query=mysqlquery("select * from vl_facilities where hubID='' or hubID='0'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_facilities","hubID",$q["id"],$q["hubID"],$noHubID);
		mysqlquery("update vl_facilities set hubID='$noHubID' where id='$q[id]'");
	}
}

$noDistrictID=0;
$noDistrictID=getDetailedTableInfo2("vl_districts","district like '%no district%' limit 1","id");

$query=0;
$query=mysqlquery("select * from vl_facilities where districtID='' or districtID='0'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_facilities","districtID",$q["id"],$q["districtID"],$noDistrictID);
		mysqlquery("update vl_facilities set districtID='$noDistrictID' where id='$q[id]'");
	}
}

//task 4: move all samples with sampleTypeID,routineMonitoringSampleTypeID,repeatVLTestSampleTypeID or suspectedTreatmentFailureSampleTypeID "Whole Blood" to type "Plasma"
$query=0;
$query=mysqlquery("select * from vl_samples where sampleTypeID='3'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","sampleTypeID",$q["id"],$q["sampleTypeID"],"2");
		mysqlquery("update vl_samples set sampleTypeID='2' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where routineMonitoringSampleTypeID='3'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","routineMonitoringSampleTypeID",$q["id"],$q["routineMonitoringSampleTypeID"],"2");
		mysqlquery("update vl_samples set routineMonitoringSampleTypeID='2' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where repeatVLTestSampleTypeID='3'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","repeatVLTestSampleTypeID",$q["id"],$q["repeatVLTestSampleTypeID"],"2");
		mysqlquery("update vl_samples set repeatVLTestSampleTypeID='2' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where suspectedTreatmentFailureSampleTypeID='3'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","suspectedTreatmentFailureSampleTypeID",$q["id"],$q["suspectedTreatmentFailureSampleTypeID"],"2");
		mysqlquery("update vl_samples set suspectedTreatmentFailureSampleTypeID='2' where id='$q[id]'");
	}
}

//task 5: move all samples with sampleTypeID,routineMonitoringSampleTypeID,repeatVLTestSampleTypeID or suspectedTreatmentFailureSampleTypeID "Left Blank" to type "DBS"
$query=0;
$query=mysqlquery("select * from vl_samples where sampleTypeID='4'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","sampleTypeID",$q["id"],$q["sampleTypeID"],"1");
		mysqlquery("update vl_samples set sampleTypeID='1' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where routineMonitoringSampleTypeID='4'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","routineMonitoringSampleTypeID",$q["id"],$q["routineMonitoringSampleTypeID"],"1");
		mysqlquery("update vl_samples set routineMonitoringSampleTypeID='1' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where repeatVLTestSampleTypeID='4'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","repeatVLTestSampleTypeID",$q["id"],$q["repeatVLTestSampleTypeID"],"1");
		mysqlquery("update vl_samples set repeatVLTestSampleTypeID='1' where id='$q[id]'");
	}
}

$query=0;
$query=mysqlquery("select * from vl_samples where suspectedTreatmentFailureSampleTypeID='4'");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		logTableChange("vl_samples","suspectedTreatmentFailureSampleTypeID",$q["id"],$q["suspectedTreatmentFailureSampleTypeID"],"1");
		mysqlquery("update vl_samples set suspectedTreatmentFailureSampleTypeID='1' where id='$q[id]'");
	}
}
?>