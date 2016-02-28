<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* OPERATIONS STAFF FUNCTIONS
*/

function getSubordinateOperationsStaff($supervisor) {
	$query=0;
	$query=mysqlquery("select id from vl_users where reportsTo='$supervisor'");
	if(mysqlnumrows($query)) {
		$staff=array();
		while($q=mysqlfetcharray($query)) {
			$staff[]=$q["id"];
		}
		return $staff;
	}
}
?>