<?
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
	global $server_url;
	$url=0;
	$url=$server_url.$_SERVER['REQUEST_URI'];
	return $url;
}

function getThisFormattedURL($variableName,$variable) {
	return preg_replace("/&$variableName=$variable/is","",getThisURL());
}

function getThisURLArray($userID) {
	global $c;

	$thisURL=0;
	$thisURL=getThisURL();
	//add any post variables
	foreach($_POST as $var => $value) {
		$thisURL.="&$var=$value";
	}
	//remove ?
	$thisURL=preg_replace("/\?/is","",$thisURL);
	//split based on &
	$thisURLArray=array();
	$thisURLArray=explode("&",$thisURL);
	$urlArray=array();
	$thisA=0;
	foreach($thisURLArray as $thisA) {
		//split based on = 
		$thisAArray=array();
		$thisAArray=explode("=",$thisA);
		$urlArray["$thisAArray[0]"]=$thisAArray[1];
	}

	$urlArray["fvlDCuser"]=$userID;
	$urlArray["c"]=$c;
	
	return $urlArray;
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
* log page hit
* @param: $page
*/
function logURL($url,$postVariables) {
	global $datetime,$trailSessionUser;
	mysqlquery("insert into vl_logs_pagehits 
					(url,postVariables,created,createdby) 
					values 
					('$url','$postVariables','$datetime','$trailSessionUser')");
}

/**
* log table changes
*/
function logTableChange($tableName,$fieldName,$fieldID,$fieldValueOld,$fieldValueNew) {
	global $datetime,$trailSessionUser;
	if($fieldValueOld!=$fieldValueNew) {
		mysqlquery("insert into vl_logs_tables 
						(tableName,fieldName,fieldID,fieldValueOld,fieldValueNew,url,created,createdby) 
						values 
						('$tableName','$fieldName','$fieldID','$fieldValueOld','$fieldValueNew','".getThisURL()."','$datetime','$trailSessionUser')");
	}
}

/**
* log search
*/
function logProfileSearch($query,$results) {
	global $datetime,$trailSessionUser;
	if($query) {
		mysqlquery("insert into vl_logs_pagehits_profiles 
						(query,results,who,at) 
						values 
						('$query','$results','$trailSessionUser','$datetime')");
	}
}

/**
* get the url after a form has been submitted
* at this stage, the url usually only contains ?
*/
function getPostVariables() {
	$url=0;
	$url="";
	//this may be a form submission hence check for all post variables
	$count=1;
	foreach($_POST as $var => $value) {
		$url.="$var: $value<br>";
	}
	return $url;
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
	global $datetime,$trailSessionUser;
	if(!isQuery("select * from vl_logs_removals where query='".preg_replace("/'/s","\'",$query)."' and createdby='$trailSessionUser'")) {
		//log the removal
		mysqlquery("insert into vl_logs_removals 
						(sqlQuery,removedData,created,createdby) 
						values 
						('".preg_replace("/'/s","\'",$query)."','".getRemovedData($query)."','$datetime','$trailSessionUser')");
	}
}

/**
* log search query
* @param: $searchQuery
* @param: $searchFilter
*/
function logSearchQuery($searchQuery,$searchFilter) {
	global $datetime,$trailSessionUser;
	mysqlquery("insert into vl_logs_searches 
					(searchQuery,searchFilter,created,createdby) 
					values 
					('$searchQuery','$searchFilter','$datetime','$trailSessionUser')");
}

/**
* log search query
* @param: $refNumber
*/
function logDownloadedVLClinicalForms($refNumber) {
	global $datetime,$trailSessionUser;
	if(!getDetailedTableInfo2("vl_logs_downloadedclinicalforms","refNumber='$refNumber' limit 1","id")) {
		mysqlquery("insert into vl_logs_downloadedclinicalforms 
						(refNumber,created,createdby) 
						values 
						('$refNumber','$datetime','$trailSessionUser')");
	}
}
?>