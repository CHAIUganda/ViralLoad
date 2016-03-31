<?php
include ".env.php";
$db=mysqli_connect($db_host,$db_username,$db_password);
mysqli_select_db($db,$db_name);

date_default_timezone_set($timezone);

//actual run is here
updateSuppressionStatus();

function validCases(){
	$ret="";
	$cases=array(
		"Failed",
		"Failed.",
		"Invalid",
		"Invalid test result. There is insufficient sample to repeat the assay.",
		"There is No Result Given. The Test Failed the Quality Control Criteria. We advise you send a a new sample.",
		"There is No Result Given. The Test Failed the Quality Control Criteria. We advise you send a new sample.");

	foreach ($cases as $v) {
		$ret.=" resultAlphanumeric NOT LIKE '$v' AND";
	}
	$ret=" (".substr($ret, 0,-3).") ";
	return $ret;	
}

function suppressedCases($generation="1"){
	//generation 1 ended on 31/03/2016, generation 2 begun on 01/04/2016
	$ret="";
	switch ($generation) {
		case 1:
			$ret=" ((s.sampleTypeID=1 AND resultNumeric<=5000) OR (s.sampleTypeID=2 AND resultNumeric<=1000))";
			break;
		case 2:
			$ret=" resultNumeric<=1000 ";
			break;
		
		default:
			$ret="";
			break;
	}
	
	return $ret;
}

function unSuppressedCases($generation="1"){
	//generation 1 ended on 31/03/2016, generation 2 begun on 01/04/2016
	$ret="";
	switch ($generation) {
		case 1:
			$ret=" ((s.sampleTypeID=1 AND resultNumeric>5000) OR (s.sampleTypeID=2 AND resultNumeric>1000))";
			break;
		case 2:
			$ret=" resultNumeric>1000 ";
			break;
		
		default:
			$ret="";
			break;
	}
	
	return $ret;
}


function updateSuppressionStatus(){
	global $db;
	$date_cond="UNIX_TIMESTAMP(r.created)<=1459457999";//$date_cond is quivalent to 2016-03-01 23:59:59
	$spprssn_cond=validCases()." AND ".suppressedCases(1);
	$unspprssn_cond=validCases()." AND ".unSuppressedCases(1);
	$sql_spprssd="UPDATE vl_results_merged AS r,vl_samples AS s  SET suppressed='YES' 
		  		  WHERE r.vlSampleID=s.vlSampleID AND $date_cond AND $spprssn_cond;";
	$sql_unspprssd="UPDATE vl_results_merged AS r,vl_samples AS s  SET suppressed='NO' 
		  		    WHERE r.vlSampleID=s.vlSampleID AND $date_cond AND $unspprssn_cond;";
	//echo $sql_spprssd."\n"; 
    mysqli_query($db,$sql_spprssd) or die(mysqli_error($db))."\n";
    mysqli_query($db,$sql_unspprssd) or die(mysqli_error($db))."\n";
}





?>