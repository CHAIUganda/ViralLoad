<?
//register a globals variable for security
header("Access-Control-Allow-Origin: *");
$GLOBALS['vlDC']=true;
include "conf.php";

/*
* access this page by visiting, for example 
* http://vl.trailanalytics.com/json/district/amg299281fmlasd5dc02bd238919260fg6ad261d094zafd9/
* 
* step 1: ran a basic authentication on the user
* step 2: direct the user to the appropriate case statement
*/

//authenticate
if($token=="amg299281fmlasd5dc02bd238919260fg6ad261d094zafd9") {
	switch($option) {
		case "regions":
			//get regions
			$query=0;
			$query=mysqlquery("select * from vl_regions order by region limit 50");
			if(mysqlnumrows($query)) {
				$array=array();
				while($q=mysqlfetcharray($query)) {
					$subarray=array();
					$subarray["id"]=$q["id"];
					$subarray["region"]=$q["region"];
					$subarray["created"]=$q["created"];
					$subarray["createdby"]=$q["createdby"];
					$array[]=$subarray;
				}
				//prepare json string
				echo json_encode($array);
			}
		break;
		case "districts":
			//get districts
			$query=0;
			$query=mysqlquery("select * from vl_districts order by district limit 50");
			if(mysqlnumrows($query)) {
				$array=array();
				while($q=mysqlfetcharray($query)) {
					$subarray=array();
					$subarray["id"]=$q["id"];
					$subarray["district"]=$q["district"];
					$subarray["regionID"]=$q["regionID"];
					$subarray["created"]=$q["created"];
					$subarray["createdby"]=$q["createdby"];
					$array[]=$subarray;
				}
				//prepare json string
				echo json_encode($array);
			}
		break;
		case "hubs":
			//get hubs
			$query=0;
			$query=mysqlquery("select * from vl_hubs order by hub limit 50");
			if(mysqlnumrows($query)) {
				$array=array();
				while($q=mysqlfetcharray($query)) {
					$subarray=array();
					$subarray["id"]=$q["id"];
					$subarray["hub"]=$q["hub"];
					$subarray["ipID"]=$q["ipID"];
					$subarray["created"]=$q["created"];
					$subarray["createdby"]=$q["createdby"];
					$array[]=$subarray;
				}
				//prepare json string
				echo json_encode($array);
			}
		break;
		case "ips":
			//get ips
			$query=0;
			$query=mysqlquery("select * from vl_ips order by ip limit 50");
			if(mysqlnumrows($query)) {
				$array=array();
				while($q=mysqlfetcharray($query)) {
					$subarray=array();
					$subarray["id"]=$q["id"];
					$subarray["ip"]=$q["ip"];
					$subarray["created"]=$q["created"];
					$subarray["createdby"]=$q["createdby"];
					$array[]=$subarray;
				}
				//prepare json string
				echo json_encode($array);
			}
		break;
		case "facilities":
			//get hubs
			$query=0;
			$query=mysqlquery("select * from vl_facilities order by facility limit 50");
			if(mysqlnumrows($query)) {
				$array=array();
				while($q=mysqlfetcharray($query)) {
					$subarray=array();
					$subarray["id"]=$q["id"];
					$subarray["facility"]=$q["facility"];
					$subarray["districtID"]=$q["districtID"];
					$subarray["hubID"]=$q["hubID"];
					$subarray["ipID"]=$q["ipID"];
					$subarray["phone"]=$q["phone"];
					$subarray["email"]=$q["email"];
					$subarray["contactPerson"]=$q["contactPerson"];
					$subarray["physicalAddress"]=$q["physicalAddress"];
					$subarray["returnAddress"]=$q["returnAddress"];
					$subarray["active"]=$q["active"];
					$subarray["created"]=$q["created"];
					$subarray["createdby"]=$q["createdby"];
					$array[]=$subarray;
				}
				//prepare json string
				echo json_encode($array);
			}
		break;
	}
}
?>