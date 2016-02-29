<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

/**
* INSTRUCTIONS
* ran as lnx http://192.168.0.43/index.oneoffs.php
* task 1: re-work the field vl_patients.uniqueID
*/

//task 1: re-work the field vl_patients.uniqueID
$query=0;
$query=mysqlquery("select * from vl_patients where artNumber!='' and (artNumber like '%-%' or artNumber like '%.%' or artNumber like '%/%' or artNumber like '% %')");
if(mysqlnumrows($query)) {
	while($q=mysqlfetcharray($query)) {
		//get the uniqueID
		$uniqueID=0;
		$uniqueID=$q["uniqueID"];
		//split uniqueID based on -A-
		$uniqueIDArray=array();
		$uniqueIDArray=explode("-A-",$uniqueID);
		
		$uniqueIDstring1="";
		$uniqueIDstring2="";
		$uniqueIDstring1=$uniqueIDArray[0]."-A-";
		if(count($uniqueIDArray)) {
			for($i=1;$i<count($uniqueIDArray);$i++) {
				$uniqueIDstring2.=$uniqueIDArray[$i];
			}
			//clean $uniqueIDstring by removing - . / and spaces
			$uniqueIDstring2=preg_replace("/\-/s","",$uniqueIDstring2);
			$uniqueIDstring2=preg_replace("/\./s","",$uniqueIDstring2);
			$uniqueIDstring2=preg_replace("/\//s","",$uniqueIDstring2);
			$uniqueIDstring2=preg_replace("/\s/s","",$uniqueIDstring2);
			//update vl_patients.uniqueID
			logTableChange("vl_patients","uniqueID",$q["id"],$q["uniqueID"],"$uniqueIDstring1$uniqueIDstring2");
			mysqlquery("update vl_patients set uniqueID='$uniqueIDstring1$uniqueIDstring2' where id='$q[id]'");
			//update vl_samples.patientUniqueID
			logTableChange("vl_samples","patientUniqueID",getDetailedTableInfo2("vl_samples","patientUniqueID='$q[uniqueID]' limit 1","id"),$q["uniqueID"],"$uniqueIDstring1$uniqueIDstring2");
			mysqlquery("update vl_samples set patientUniqueID='$uniqueIDstring1$uniqueIDstring2' where patientUniqueID='$q[uniqueID]'");
		}
	}
}
?>