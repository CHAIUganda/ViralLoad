<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

//CSV File
$csv=0;
$csv="\"Warning Type\",\"Post-Warning Submission Details\",\"Time\",\"User\"\n";

//compile $from and $to
$from=0;
$from="$fromYear-$fromMonth-$fromDay";

$to=0;
$to="$toYear-$toMonth-$toDay";
	
$query=0;
$query=mysqlquery("select * from vl_logs_warnings where date(created)>='$from' and date(created)<='$to'");
if(mysqlnumrows($query)) {
	$q=array();
	while($q=mysqlfetcharray($query)) {
		$warningType=0;
		switch($q["logCategory"]) {
			case "changedPatientsGender":
				$warningType="Change in patient's gender";
			break;
			case "capturedRepeatVLTest":
				$warningType="Capture of a repeat VL test";
			break;
		}
		$csv.="\"$warningType\",\"$q[logDetails]\",\"".getFormattedTimeLessS($q["created"])."\",\"$q[createdby]\"\n";
	}
}

//output
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=".($id?"$qFilename":"download.csv"));
print $csv;
?>
