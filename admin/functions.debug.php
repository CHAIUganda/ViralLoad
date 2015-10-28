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
?>