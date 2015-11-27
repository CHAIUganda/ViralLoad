<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

/*
$string="4442 Internal control cycle number is too high. Valid range is [18.35, 22.35].";
echo "String: $string<br>";
echo "SubString: ".substr($string,0,47);
*/

//$formNumber="1430205";
//$formNumber="583472";
//$formNumber="583504";

/*
$refNumber=0;
$refNumber=getDetailedTableInfo2("vl_forms_clinicalrequest","formNumber='$formNumber' limit 1","refNumber");

$oldFacilityID=0;
$oldFacilityID=getDetailedTableInfo2("vl_forms_clinicalrequest_dispatch","refNumber='$refNumber' limit 1","facilityID");

echo "Form Number $formNumber dispatched to Facility ".getDetailedTableInfo2("vl_facilities","id='$oldFacilityID' limit 1","facility");
*/
$query=0;
$query=mysqlquery("select * from vl_districts order by district limit 5");
if(mysqlnumrows($query)) {
	$array=array();
	while($q=mysqlfetcharray($query)) {
		$subarray=array();
		$subarray["district"]=$q["district"];
		$subarray["regionID"]=$q["regionID"];
		$subarray["created"]=$q["created"];
		$subarray["createdby"]=$q["createdby"];
		$array[]=$subarray;
	}
}

echo json_encode($array);
/*
$arr = array();
$arr = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
$arr[] = array('alpha' => 12, 'beta' => 24);
*/
?>