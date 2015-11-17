<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* DEBUG FUNCTIONS
*/

function alert($string) {
	$string=eregi_replace("'","\'",$string);
	echo "<script>alert('$string');</script>";
}

function go($string) {
	echo "<script>document.location.href='$string';</script>";
}

function getThisURL() {
	//globals
	global $server_url,$home_url,$siteFolder;
	/**
	* the country parameter has been set
	* from the drop-down menu
	*
	* $home_url - $server_url shall leave us with the
	* string that must be removed from $_SERVER['REQUEST_URI']
	*/
	$url=NULL;
	$url=@preg_replace("/$server_url/is","",$home_url);
	/**
	* remove this component from the $_SERVER['REQUEST_URI']
	* leaving us with the exact url: ?p=page&a=action&c=country
	*/
	$return=0;
	$return=preg_replace("/$url/is","",$_SERVER['REQUEST_URI']);
	//final clean through
	$return=preg_replace("/$siteFolder/is","",$return);

	return ($return=="?"?getThisURLwithPostVariables():$return);
}

function getThisFormattedURL($variableName,$variable) {
	return preg_replace("/&$variableName=$variable/is","",getThisURL());
}

/**
* should we return an & or nothing
* if getThisURL() only has a ?, then an & shdn't be returned
*/
function getAmp() {
	if(getThisURL()=="?") {
		$url=0;
		$url="";
		//this may be a form submission hence check for all post variables
		$count=1;
		foreach($_POST as $var => $value) {
			$url.=($count==1?"":"&")."$var=$value";
			$count++;
		}
		return $url."&";
	} else {
		return "&";
	}
}

/**
* get the url after a form has been submitted
* at this stage, the url usually only contains ?
*/
function getThisURLwithPostVariables() {
	$url=0;
	$url="";
	//this may be a form submission hence check for all post variables
	$count=1;
	foreach($_POST as $var => $value) {
		$url.=($count==1?"?":"&")."$var=$value";
		$count++;
	}
	return $url;
}

/**
* log action
*/
function logPageHit($action) {
	global $datetime,$user;
	if($action) {
		mysqlquery("insert into vl_logs_pagehits 
						(countryID,page,action,who,at) 
						values 
						('$cID','$action','".getThisURL()."','$user','$datetime')");
	}
}

/**
* log table changes
*/
function logTableChange($tableName,$fieldName,$fieldID,$fieldValueOld,$fieldValueNew) {
	global $datetime,$user;
	if($fieldValueOld!=$fieldValueNew) {
		mysqlquery("insert into vl_logs_tables 
						(tableName,fieldName,fieldID,fieldValueOld,fieldValueNew,url,created,createdby) 
						values 
						('$tableName','$fieldName','$fieldID','$fieldValueOld','$fieldValueNew','".getThisURL()."','$datetime','$user')");
	}
}

/**
* log credit account status
*/
function logCreditAccountStatus($tableName,$clientNumber,$creditAccount,$creditAccountStatus,$creditApprovalDate) {
	global $datetime,$user,$curTime;
	/*
	* avoid duplicates
	* ensure new entry is different from last known entry
	*/
	if(!isQuery("select * from vl_logs_creditaccountstatus where 
							tableName='$tableName' and 
							clientNumber='$clientNumber' and 
							creditAccount='$creditAccount' and 
							creditAccountStatus='$creditAccountStatus'")) {
		//insert
		mysqlquery("insert into vl_logs_creditaccountstatus 
				(tableName,clientNumber,creditAccount,creditAccountStatus,created,createdby) 
				values 
				('$tableName','$clientNumber','$creditAccount','$creditAccountStatus','".getRawFormattedDateLessDay($creditApprovalDate)." ".$curTime."','$user')");
	} else {
		if(getDetailedTableInfo2("vl_logs_creditaccountstatus","tableName='$tableName' and clientNumber='$clientNumber' and creditAccount='$creditAccount' order by created desc limit 1","creditAccountStatus")!=$creditAccountStatus) {
			//insert
			mysqlquery("insert into vl_logs_creditaccountstatus 
					(tableName,clientNumber,creditAccount,creditAccountStatus,created,createdby) 
					values 
					('$tableName','$clientNumber','$creditAccount','$creditAccountStatus','$datetime','$user')");
		}
	}
}

/**
* get data for removal
* @param: $query
*/
function getRemovedData($query) {
	//removed data
	$removedData="";
	
	//get the table name, usually the 3rd word in the query
	$wordInQuery=array();
	$wordInQuery=explode(" ",$query);
	$tableName=0;
	$tableName=$wordInQuery[2];
	
	//get fields in table
	$tableQuery=0;
	$tableQuery=mysqlquery("desc $tableName");
	if(mysqlnumrows($tableQuery)) {
		$fieldsInTable=array();
		while($row=mysqlfetcharray($tableQuery)) {
			$fieldsInTable[]=$row["Field"];
		}
	}
	
	//switch statement from "delete from" to "select * from"
	$newQuery=0;
	$newQuery=preg_replace("/delete from/is","select * from",$query);
	
	//ran the select query
	$selectQuery=0;
	$selectQuery=mysqlquery($newQuery);
	if(mysqlnumrows($selectQuery) && count($fieldsInTable)) {
		for($i=0;$i<count($fieldsInTable);$i++) {
			$removedData.="$fieldsInTable[$i]::".mysqlresult($selectQuery,0,"$fieldsInTable[$i]").($i<(count($fieldsInTable)-1)?"|":"");
		}
	}
	
	//return
	return $removedData;
}

/**
* log data removal
* @param: $query
*/
function logDataRemoval($query) {
	global $datetime,$user;
	if(!isQuery("select * from vl_logs_removals where query='".preg_replace("/'/s","\'",$query)."' and createdby='$user'")) {
		//log the removal
		mysqlquery("insert into vl_logs_removals 
						(sqlQuery,removedData,created,createdby) 
						values 
						('".preg_replace("/'/s","\'",$query)."','".getRemovedData($query)."','$datetime','$user')");
	}
}

/**
* log new region, return regionID
*/
function logNewRegion($region) {
	global $datetime,$user;
	//avoid duplicates
	if(!getDetailedTableInfo2("vl_regions","lower(region)='".strtolower($region)."' limit 1","id")) {
		//insert into vl_regions
		mysqlquery("insert into vl_regions 
				(region,created,createdby) 
				values 
				('$region','$datetime','$user')");
		//return regionID
		return getDetailedTableInfo2("vl_regions","createdby='$user' order by id desc limit 1","id");
	} else {
		//return regionID
		return getDetailedTableInfo2("vl_regions","lower(region)='".strtolower($region)."' limit 1","id");
	}
}

/**
* log new district, return districtID
*/
function logNewDistrict($district) {
	global $datetime,$user;
	//get/log the regionID
	$regionID=0;
	$regionID=logNewRegion("Region Left Blank");

	//avoid duplicates
	if(!getDetailedTableInfo2("vl_districts","lower(district)='".strtolower($district)."' limit 1","id")) {
		//insert into vl_districts
		mysqlquery("insert into vl_districts 
				(district,regionID,created,createdby) 
				values 
				('$district','$regionID','$datetime','$user')");
		//return districtID
		return getDetailedTableInfo2("vl_districts","createdby='$user' order by id desc limit 1","id");
	} else {
		//return districtID
		return getDetailedTableInfo2("vl_districts","lower(district)='".strtolower($district)."' limit 1","id");
	}
}

/**
* log new facility, return facilityID
*/
function logNewFacility($facility,$district) {
	global $datetime,$user;
	
	//get/log the districtID
	$districtID=0;
	$districtID=logNewDistrict($district);
	
	//avoid duplicates
	if(!getDetailedTableInfo2("vl_facilities","lower(facility)='".strtolower($facility)."' and districtID='$districtID' limit 1","id")) {
		//add new facility
		mysqlquery("insert into vl_facilities 
				(facility,districtID,created,createdby) 
				values 
				('$facility','$districtID','$datetime','$user')");
		//return facilityID
		return getDetailedTableInfo2("vl_facilities","createdby='$user' order by id desc limit 1","id");
	} else {
		//return facilityID
		return getDetailedTableInfo2("vl_facilities","lower(facility)='".strtolower($facility)."' and districtID='$districtID' limit 1","id");
	}
}

/**
* log new hub, return hubID
*/
function logNewHub($hub,$facilityID) {
	global $datetime,$user;
	//avoid duplicates
	if(!getDetailedTableInfo2("vl_hubs","lower(hub)='".strtolower($hub)."' limit 1","id")) {
		//insert into vl_hubs
		mysqlquery("insert into vl_hubs 
				(hub,created,createdby) 
				values 
				('$hub','$datetime','$user')");
		//return hubID
		$hubID=0;
		$hubID=getDetailedTableInfo2("vl_hubs","createdby='$user' order by id desc limit 1","id");
		//ensure this facilityID belongs to this hub
		if($facilityID && !getDetailedTableInfo2("vl_facilities","id='$facilityID' and hubID='$hubID' limit 1","id")) {
			//log table change
			logTableChange("vl_facilities","hubID",$facilityID,getDetailedTableInfo2("vl_facilities","id='$facilityID'","hubID"),$hubID);
			//update vl_hubs
			mysqlquery("update vl_facilities set hubID='$hubID' where id='$facilityID'");
		}
		return $hubID;
	} else {
		//return hubID
		$hubID=0;
		$hubID=getDetailedTableInfo2("vl_hubs","lower(hub)='".strtolower($hub)."' limit 1","id");
		//ensure this facilityID belongs to this hub
		if($facilityID && !getDetailedTableInfo2("vl_facilities","id='$facilityID' and hubID='$hubID' limit 1","id")) {
			//log table change
			logTableChange("vl_facilities","hubID",$facilityID,getDetailedTableInfo2("vl_facilities","id='$facilityID'","hubID"),$hubID);
			//update vl_hubs
			mysqlquery("update vl_facilities set hubID='$hubID' where id='$facilityID'");
		}
		return $hubID;
	}
}

/**
* log new ip, return ipID
*/
function logNewIP($ip,$facilityID,$hubID) {
	global $datetime,$user;
	//avoid duplicates
	if(!getDetailedTableInfo2("vl_ips","lower(ip)='".strtolower($ip)."' limit 1","id")) {
		//insert into vl_ips
		mysqlquery("insert into vl_ips 
				(ip,created,createdby) 
				values 
				('$ip','$datetime','$user')");
		//return ipID
		$ipID=0;
		$ipID=getDetailedTableInfo2("vl_ips","createdby='$user' order by id desc limit 1","id");
		//ensure this facilityID belongs to this ip
		if($facilityID && !getDetailedTableInfo2("vl_facilities","id='$facilityID' and ipID='$ipID' limit 1","id")) {
			//log table change
			logTableChange("vl_facilities","ipID",$facilityID,getDetailedTableInfo2("vl_facilities","id='$facilityID'","ipID"),$ipID);
			//update vl_ips
			mysqlquery("update vl_facilities set ipID='$ipID' where id='$facilityID'");
		}
		//ensure this hubID belongs to this ip
		if($hubID && !getDetailedTableInfo2("vl_hubs","id='$hubID' and ipID='$ipID' limit 1","id")) {
			//log table change
			logTableChange("vl_hubs","ipID",$hubID,getDetailedTableInfo2("vl_hubs","id='$hubID'","ipID"),$ipID);
			//update vl_ips
			mysqlquery("update vl_hubs set ipID='$ipID' where id='$hubID'");
		}
		return $ipID;
	} else {
		//return ipID
		$ipID=0;
		$ipID=getDetailedTableInfo2("vl_ips","lower(ip)='".strtolower($ip)."' limit 1","id");
		//ensure this facilityID belongs to this ip
		if($facilityID && !getDetailedTableInfo2("vl_facilities","id='$facilityID' and ipID='$ipID' limit 1","id")) {
			//log table change
			logTableChange("vl_facilities","ipID",$facilityID,getDetailedTableInfo2("vl_facilities","id='$facilityID'","ipID"),$ipID);
			//update vl_ips
			mysqlquery("update vl_facilities set ipID='$ipID' where id='$facilityID'");
		}
		//ensure this hubID belongs to this ip
		if($hubID && !getDetailedTableInfo2("vl_hubs","id='$hubID' and ipID='$ipID' limit 1","id")) {
			//log table change
			logTableChange("vl_hubs","ipID",$hubID,getDetailedTableInfo2("vl_hubs","id='$hubID'","ipID"),$ipID);
			//update vl_ips
			mysqlquery("update vl_hubs set ipID='$ipID' where id='$hubID'");
		}
		return $ipID;
	}
}

/**
* update patient's ART
*/
function updatePatientData($patientID,$field,$data,$collectionDate) {
	global $datetime,$user;
	if($patientID && $field && $data && getDetailedTableInfo2("vl_patients","id='$patientID' limit 1","id")) {
		//special provisions for date of birth
		if($field=="dateOfBirth") {
			$dateOfBirth=0;
			$dateOfBirth=getFormattedDateYear(subtractFromDate(($collectionDate?$collectionDate:$datetime),($data*12*30.5)));
			//replace data with dateOfBirth
			$data=$dateOfBirth;
		}
		//log table change
		logTableChange("vl_patients","$field",$patientID,getDetailedTableInfo2("vl_patients","id='$patientID'","$field"),$data);
		//update vl_hubs
		mysqlquery("update vl_patients set $field='$data' where id='$patientID'");
	}
}

/**
* log patient phone
*/
function logPatientPhone($patientID,$phone) {
	global $datetime,$user;
	//prepend 0 to the phone number only if the final phone number does not become 00
	if(substr("0".$phone,0,2)!="00") {
		$phone="0".$phone;
	}
	//avoid duplicates
	if($phone && !getDetailedTableInfo2("vl_patients_phone","patientID='$patientID' and phone='$phone' limit 1","id")) {
		mysqlquery("insert into vl_patients_phone 
						(patientID,phone,created,createdby) 
						values 
						('$patientID','$phone','$datetime','$user')");
	}
}

/**
* log new currentRegimen, return regionID
*/
function logNewCurrentRegimen($currentRegimen) {
	global $datetime,$user;
	//avoid duplicates
	if(!getDetailedTableInfo2("vl_appendix_regimen","lower(appendix)='".strtolower($currentRegimen)."' limit 1","id")) {
		//position
		$position=0;
		$position=getDetailedTableInfo3("vl_appendix_regimen","appendix!=''","count(id)","num")+1;
		//insert into vl_appendix_regimen
		mysqlquery("insert into vl_appendix_regimen 
				(appendix,position,created,createdby) 
				values 
				('$currentRegimen','$position','$datetime','$user')");
		//return currentRegimenID
		return getDetailedTableInfo2("vl_appendix_regimen","createdby='$user' order by id desc limit 1","id");
	} else {
		//return currentRegimenID
		return getDetailedTableInfo2("vl_appendix_regimen","lower(appendix)='".strtolower($currentRegimen)."' limit 1","id");
	}
}

/**
* log new treatmentInitiation, return treatmentInitiationID
*/
function logNewTreatmentInitiation($treatmentInitiation) {
	global $datetime,$user;
	//avoid duplicates
	if($treatmentInitiation) {
		if(!getDetailedTableInfo2("vl_appendix_treatmentinitiation","lower(appendix)='".strtolower($treatmentInitiation)."' limit 1","id")) {
			//position
			$position=0;
			$position=getDetailedTableInfo3("vl_appendix_treatmentinitiation","appendix!=''","count(id)","num")+1;
			//insert into vl_appendix_treatmentinitiation
			mysqlquery("insert into vl_appendix_treatmentinitiation 
					(appendix,position,created,createdby) 
					values 
					('$treatmentInitiation','$position','$datetime','$user')");
			//return treatmentInitiationID
			return getDetailedTableInfo2("vl_appendix_treatmentinitiation","createdby='$user' order by id desc limit 1","id");
		} else {
			//return treatmentInitiationID
			return getDetailedTableInfo2("vl_appendix_treatmentinitiation","lower(appendix)='".strtolower($treatmentInitiation)."' limit 1","id");
		}
	}
}

/**
* log new treatmentStatus, return treatmentStatusID
*/
function logNewTreatmentStatus($treatmentStatus) {
	global $datetime,$user;
	//avoid duplicates
	if(!getDetailedTableInfo2("vl_appendix_treatmentstatus","lower(appendix)='".strtolower($treatmentStatus)."' limit 1","id")) {
		//position
		$position=0;
		$position=getDetailedTableInfo3("vl_appendix_treatmentstatus","appendix!=''","count(id)","num")+1;
		//insert into vl_appendix_treatmentstatus
		mysqlquery("insert into vl_appendix_treatmentstatus 
				(appendix,position,created,createdby) 
				values 
				('$treatmentStatus','$position','$datetime','$user')");
		//return treatmentStatusID
		return getDetailedTableInfo2("vl_appendix_treatmentstatus","createdby='$user' order by id desc limit 1","id");
	} else {
		//return treatmentStatusID
		return getDetailedTableInfo2("vl_appendix_treatmentstatus","lower(appendix)='".strtolower($treatmentStatus)."' limit 1","id");
	}
}

/**
* log new reasonForFailure, return reasonForFailureID
*/
function logNewReasonForFailure($reasonForFailure) {
	global $datetime,$user;
	//avoid duplicates
	if($reasonForFailure) {
		if(!getDetailedTableInfo2("vl_appendix_failurereason","lower(appendix)='".strtolower($reasonForFailure)."' limit 1","id")) {
			//position
			$position=0;
			$position=getDetailedTableInfo3("vl_appendix_failurereason","appendix!=''","count(id)","num")+1;
			//insert into vl_appendix_failurereason
			mysqlquery("insert into vl_appendix_failurereason 
					(appendix,position,created,createdby) 
					values 
					('$reasonForFailure','$position','$datetime','$user')");
			//return reasonForFailureID
			return getDetailedTableInfo2("vl_appendix_failurereason","createdby='$user' order by id desc limit 1","id");
		} else {
			//return reasonForFailureID
			return getDetailedTableInfo2("vl_appendix_failurereason","lower(appendix)='".strtolower($reasonForFailure)."' limit 1","id");
		}
	}
}

/**
* log new tbTreatmentPhase, return tbTreatmentPhaseID
*/
function logNewTBTreatmentPhase($tbTreatmentPhase) {
	global $datetime,$user;
	//avoid duplicates
	if($tbTreatmentPhase) {
		if(!getDetailedTableInfo2("vl_appendix_tbtreatmentphase","lower(appendix)='".strtolower($tbTreatmentPhase)."' limit 1","id")) {
			//position
			$position=0;
			$position=getDetailedTableInfo3("vl_appendix_tbtreatmentphase","appendix!=''","count(id)","num")+1;
			//insert into vl_appendix_tbtreatmentphase
			mysqlquery("insert into vl_appendix_tbtreatmentphase 
					(appendix,position,created,createdby) 
					values 
					('$tbTreatmentPhase','$position','$datetime','$user')");
			//return tbTreatmentPhaseID
			return getDetailedTableInfo2("vl_appendix_tbtreatmentphase","createdby='$user' order by id desc limit 1","id");
		} else {
			//return tbTreatmentPhaseID
			return getDetailedTableInfo2("vl_appendix_tbtreatmentphase","lower(appendix)='".strtolower($tbTreatmentPhase)."' limit 1","id");
		}
	}
}

/**
* log new arvAdherence, return arvAdherenceID
*/
function logNewARVAdherence($arvAdherence) {
	global $datetime,$user;
	//avoid duplicates
	if($arvAdherence) {
		if(!getDetailedTableInfo2("vl_appendix_arvadherence","lower(appendix)='".strtolower($arvAdherence)."' limit 1","id")) {
			//position
			$position=0;
			$position=getDetailedTableInfo3("vl_appendix_arvadherence","appendix!=''","count(id)","num")+1;
			//insert into vl_appendix_arvadherence
			mysqlquery("insert into vl_appendix_arvadherence 
					(appendix,position,created,createdby) 
					values 
					('$arvAdherence','$position','$datetime','$user')");
			//return arvAdherenceID
			return getDetailedTableInfo2("vl_appendix_arvadherence","createdby='$user' order by id desc limit 1","id");
		} else {
			//return arvAdherenceID
			return getDetailedTableInfo2("vl_appendix_arvadherence","lower(appendix)='".strtolower($arvAdherence)."' limit 1","id");
		}
	}
}
?>