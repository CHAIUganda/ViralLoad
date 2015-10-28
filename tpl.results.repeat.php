<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

//validation
$sampleID=validate($sampleID);
$worksheetID=validate($worksheetID);

if(count($sampleResultCheckbox)) {
	//add
	$added=0;
	foreach($sampleResultCheckbox as $sample) {
		//split variable
		$sampleResult=array();
		$sampleResult=explode("|",$sample);
		//key variables
		$sampleID=0;
		$sampleID=validate($sampleResult[0]);
		$sampleReferenceNumber=0;
		$sampleReferenceNumber=getDetailedTableInfo2("vl_samples","id='$sampleID'","vlSampleID");
		$worksheetID=0;
		$worksheetID=validate($sampleResult[1]);
		//derive machine type
		$machineType=0;
		if(getDetailedTableInfo2("vl_results_abbott","sampleID='$sampleReferenceNumber' and worksheetID='$worksheetID' limit 1","id")) {
			$machineType="abbott";
		} elseif(getDetailedTableInfo2("vl_results_roche","SampleID='$sampleReferenceNumber' and worksheetID='$worksheetID' limit 1","id")) {
			$machineType="roche";
		} else {
			$machineType="rejected";
		}
		//log repeat
		$id=0;
		$id=getDetailedTableInfo2("vl_logs_samplerepeats","sampleID='$sampleID' and oldWorksheetID='$worksheetID' limit 1","id");
		if(!$id) {
			mysqlquery("insert into vl_logs_samplerepeats  
							(sampleID,oldWorksheetID,created,createdby) 
							values 
							('$sampleID','$worksheetID','$datetime','$trailSessionUser')");
			//flags
			$added+=1;
		}
	}
} elseif($sampleID && $worksheetID) {
	$sampleReferenceNumber=0;
	$sampleReferenceNumber=getDetailedTableInfo2("vl_samples","id='$sampleID'","vlSampleID");
	//derive machine type
	$machineType=0;
	if(getDetailedTableInfo2("vl_results_abbott","sampleID='$sampleReferenceNumber' and worksheetID='$worksheetID' limit 1","id")) {
		$machineType="abbott";
	} elseif(getDetailedTableInfo2("vl_results_roche","SampleID='$sampleReferenceNumber' and worksheetID='$worksheetID' limit 1","id")) {
		$machineType="roche";
	} else {
		$machineType="rejected";
	}
	//log repeat
	$id=0;
	$id=getDetailedTableInfo2("vl_logs_samplerepeats","sampleID='$sampleID' and oldWorksheetID='$worksheetID' limit 1","id");
	if(!$id) {
		mysqlquery("insert into vl_logs_samplerepeats  
						(sampleID,oldWorksheetID,created,createdby) 
						values 
						('$sampleID','$worksheetID','$datetime','$trailSessionUser')");
		//flags
		$added+=1;
	}
}

//redirect with flag
go("/results/repeated/".($added?$added:"0")."/".($machineType?$machineType:"abbott")."/");
?>