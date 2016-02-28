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

//get the data
$query=0;
$query=mysqlquery("select distinct month(a.created) theMonth,year(a.created) theYear,a.facilityID facilityID,
								CASE 
									WHEN (round(datediff(now(),b.dateOfBirth)/365)<5 or b.dateOfBirth='0000-00-00') then 1
									WHEN round(datediff(now(),b.dateOfBirth)/365)>=5 and round(datediff(now(),b.dateOfBirth)/365)<=9 then 2
									WHEN round(datediff(now(),b.dateOfBirth)/365)>=10 and round(datediff(now(),b.dateOfBirth)/365)<=18 then 3
									WHEN round(datediff(now(),b.dateOfBirth)/365)>=19 and round(datediff(now(),b.dateOfBirth)/365)<=25 then 4
									WHEN round(datediff(now(),b.dateOfBirth)/365)>=26 then 5
								END theAgeCategory 
									from vl_samples a,vl_patients b where a.patientID=b.id order by a.created");
if(mysqlnumrows($query)) {
	//sample type IDs
	$dbsSampleTypeID=0;
	$dbsSampleTypeID=getDetailedTableInfo2("vl_appendix_sampletype","appendix='DBS' limit 1","id");
	$plasmaSampleTypeID=0;
	$plasmaSampleTypeID=getDetailedTableInfo2("vl_appendix_sampletype","appendix='Plasma' limit 1","id");
	
	$array=array();
	while($q=mysqlfetcharray($query)) {
		//process last portion of output based on age category
		switch($q["theAgeCategory"]) {
			case 1: //age is < 5
				//samples_received
				echo "$q[theMonth], $q[theYear], $q[facilityID], $q[theAgeCategory], ".getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and (round(datediff(now(),b.dateOfBirth)/365)<5 or b.dateOfBirth='0000-00-00')","count(a.id)","num")."<br>";
			break;
			case 2: //age >= 5 && age <= 9
				//samples_received
				echo "$q[theMonth], $q[theYear], $q[facilityID], $q[theAgeCategory], ".getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=5 and round(datediff(now(),b.dateOfBirth)/365)<=9 and b.dateOfBirth!='0000-00-00'","count(a.id)","num")."<br>";
			break;
			case 3: //age >= 10 && age <= 18
				//samples_received
				echo "$q[theMonth], $q[theYear], $q[facilityID], $q[theAgeCategory], ".getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=10 and round(datediff(now(),b.dateOfBirth)/365)<=18 and b.dateOfBirth!='0000-00-00'","count(a.id)","num")."<br>";
			break;
			case 4: //age >= 19 && age <= 25
				//samples_received
				echo "$q[theMonth], $q[theYear], $q[facilityID], $q[theAgeCategory], ".getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=19 and round(datediff(now(),b.dateOfBirth)/365)<=25 and b.dateOfBirth!='0000-00-00'","count(a.id)","num")."<br>";
			break;
			case 5: //age >= 26
			default: //age >= 26
				//samples_received
				echo "$q[theMonth], $q[theYear], $q[facilityID], $q[theAgeCategory], ".getDetailedTableInfo3("vl_samples a,vl_patients b","a.patientID=b.id and month(a.created)='$q[theMonth]' and year(a.created)='$q[theYear]' and a.facilityID='$q[facilityID]' and round(datediff(now(),b.dateOfBirth)/365)>=26 and b.dateOfBirth!='0000-00-00'","count(a.id)","num")."<br>";
			break;
		}
	}
}
?>