<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* TABLES RELATED FUNCTIONS
*/

/**
* function to query the dual system table
* @param: $info
*/
function getDualInfo($info) {
	global $trailSessionUser,$p,$a;
	$query=0;
	$query=mysqlquery("select $info from dual");
	if(!mysqlerror()) {
		if(mysqlnumrows($query)) {
			return mysqlresult($query,0,$info);
		}
	} else {
		//log this error
		$errorName=0;
		$errorName="Database Error!";
		$errorDescription=0;
		$errorDescription="<strong>Database error:</strong> ".mysqlerror();
		$errorDescription.="<br><strong>URL:</strong> ".getThisURL();
		$errorDescription.="<br><strong>Page:</strong> $p";
		$errorDescription.="<br><strong>Action:</strong> $a";
		logError($errorName,$errorDescription,"",getUserID($trailSessionUser));
	}
}

/**
* function to query the dual system table
* @param: $info
* @param: $alias
*/
function getDualInfoWithAlias($info,$alias) {
	global $trailSessionUser,$p,$a;
	$query=0;
	$query=mysqlquery("select $info $alias from dual");
	if(!mysqlerror()) {
		if(mysqlnumrows($query)) {
			return mysqlresult($query,0,$alias);
		}
	} else {
		//log this error
		$errorName=0;
		$errorName="Database Error!";
		$errorDescription=0;
		$errorDescription="<strong>Database error:</strong> ".mysqlerror();
		$errorDescription.="<br><strong>URL:</strong> ".getThisURL();
		$errorDescription.="<br><strong>Page:</strong> $p";
		$errorDescription.="<br><strong>Action:</strong> $a";
		logError($errorName,$errorDescription,"",getUserID($trailSessionUser));
	}
}

/**
* function to get detailed table information
* @param: $table
* @param: $option
* @param: $info
* @param: $alias
*/
function getDetailedTableInfoWithAlias($table,$option,$info,$alias) {
	$query=0;
	$query=mysqlquery("select ".($info && $alias?"$info $alias":"*")." from $table where $option");
	if(mysqlnumrows($query)) {
		return ($info?mysqlresult($query,0,$alias):1);
	}
}

/**
* function to get the table field
* @param: $table
* @param: $id
* @param: $info
*/
function getTableInfo($table,$id,$info) {
	global $trailSessionUser,$p,$a;
	$query=0;
	$query=mysqlquery("select $info from $table where id='$id'");
	if(!mysqlerror()) {
		if(mysqlnumrows($query)) {
			return mysqlresult($query,0,$info);
		}
	} else {
		//log this error
		$errorName=0;
		$errorName="Database Error!";
		$errorDescription=0;
		$errorDescription="<strong>Database error:</strong> ".mysqlerror();
		$errorDescription.="<br><strong>URL:</strong> ".getThisURL();
		$errorDescription.="<br><strong>Page:</strong> $p";
		$errorDescription.="<br><strong>Action:</strong> $a";
		logError($errorName,$errorDescription,"",getUserID($trailSessionUser));
	}
}

/**
* function to get specific table information
* @param: $table
* @param: $field
* @param: $fieldvalue
* @param: $info
*/
function getDetailedTableInfo($table,$field,$fieldvalue,$info) {
	global $trailSessionUser,$p,$a;
	$query=0;
	$query=mysqlquery("select ".($info?$info:"*")." from $table where $field='$fieldvalue'");
	if(!mysqlerror()) {
		if(mysqlnumrows($query)) {
			return ($info?mysqlresult($query,0,$info):1);
		}
	} else {
		//log this error
		$errorName=0;
		$errorName="Database Error!";
		$errorDescription=0;
		$errorDescription="<strong>Database error:</strong> ".mysqlerror();
		$errorDescription.="<br><strong>URL:</strong> ".getThisURL();
		$errorDescription.="<br><strong>Page:</strong> $p";
		$errorDescription.="<br><strong>Action:</strong> $a";
		logError($errorName,$errorDescription,"",getUserID($trailSessionUser));
	}
}

/**
* function to get detailed table information
* @param: $table
* @param: $option
* @param: $info
*/
function getDetailedTableInfo2($table,$option,$info) {
	global $trailSessionUser,$p,$a;
	$query=0;
	$query=mysqlquery("select ".($info?$info:"*")." from $table where $option");
	if(!mysqlerror()) {
		if(mysqlnumrows($query)) {
			return ($info?mysqlresult($query,0,$info):1);
		}
	} else {
		//log this error
		$errorName=0;
		$errorName="Database Error!";
		$errorDescription=0;
		$errorDescription="<strong>Database error:</strong> ".mysqlerror();
		$errorDescription.="<br><strong>URL:</strong> ".getThisURL();
		$errorDescription.="<br><strong>Page:</strong> $p";
		$errorDescription.="<br><strong>Action:</strong> $a";
		logError($errorName,$errorDescription,"",getUserID($trailSessionUser));
	}
}

/**
* function to get detailed table information
* @param: $table
* @param: $option
* @param: $info
* @param: $alias
*/
function getDetailedTableInfo3($table,$option,$info,$alias) {
	global $trailSessionUser,$p,$a;
	$query=0;
	$query=mysqlquery("select ".($info && $alias?"$info $alias":"*")." from $table where $option");
	if(!mysqlerror()) {
		if(mysqlnumrows($query)) {
			return ($info?mysqlresult($query,0,$alias):1);
		}
	} else {
		//log this error
		$errorName=0;
		$errorName="Database Error!";
		$errorDescription=0;
		$errorDescription="<strong>Database error:</strong> ".mysqlerror();
		$errorDescription.="<br><strong>URL:</strong> ".getThisURL();
		$errorDescription.="<br><strong>Page:</strong> $p";
		$errorDescription.="<br><strong>Action:</strong> $a";
		logError($errorName,$errorDescription,"",getUserID($trailSessionUser));
	}
}

/**
* function to get detailed table information
* @param: $table
* @param: $option
* @param: $info
*/
function getDetailedTableInfo4($table,$option,$info) {
	global $trailSessionUser,$p,$a;
	//echo "select ".($info?$info:"*")." from $table where $option<br>";
	$query=0;
	$query=mysqlquery("select ".($info?$info:"*")." from $table $option");
	if(!mysqlerror()) {
		if(mysqlnumrows($query)) {
			return ($info?mysqlresult($query,0,$info):1);
		}
	} else {
		//log this error
		$errorName=0;
		$errorName="Database Error!";
		$errorDescription=0;
		$errorDescription="<strong>Database error:</strong> ".mysqlerror();
		$errorDescription.="<br><strong>URL:</strong> ".getThisURL();
		$errorDescription.="<br><strong>Page:</strong> $p";
		$errorDescription.="<br><strong>Action:</strong> $a";
		logError($errorName,$errorDescription,"",getUserID($trailSessionUser));
	}
}

/**
* function to establish whether a query returns a value
* @param: $q
*/
function isQuery($q) {
	global $trailSessionUser,$p,$a;
	$query=0;
	$query=mysqlquery("$q");
	if(!mysqlerror()) {
		$num=0;
		$num=mysqlnumrows($query);
		return $num;
	} else {
		//log this error
		$errorName=0;
		$errorName="Database Error!";
		$errorDescription=0;
		$errorDescription="<strong>Database error:</strong> ".mysqlerror();
		$errorDescription.="<br><strong>URL:</strong> ".getThisURL();
		$errorDescription.="<br><strong>Page:</strong> $p";
		$errorDescription.="<br><strong>Action:</strong> $a";
		logError($errorName,$errorDescription,"",getUserID($trailSessionUser));
	}
}

/**
* function to query a table using the standard $query=mysqlquery("select statement")
* @param: $q
*/
function queryTableID($q) {
	global $trailSessionUser,$p,$a;
	$query=0;
	$query=mysqlquery("$q");
	if(!mysqlerror()) {
		if(mysqlnumrows($query)) {
			$q=array();
			$return=array();
			while($q=mysqlfetcharray($query)) {
				$return[]=$q["id"];
			}
			return $return;
		}
	} else {
		//log this error
		$errorName=0;
		$errorName="Database Error!";
		$errorDescription=0;
		$errorDescription="<strong>Database error:</strong> ".mysqlerror();
		$errorDescription.="<br><strong>URL:</strong> ".getThisURL();
		$errorDescription.="<br><strong>Page:</strong> $p";
		$errorDescription.="<br><strong>Action:</strong> $a";
		logError($errorName,$errorDescription,"",getUserID($trailSessionUser));
	}
}

/**
* function to query a table using the standard $query=mysqlquery("select statement")
* @param: $q
* @param: $field - which field should we return
*/
function queryTableInfo($q,$field) {
	global $trailSessionUser,$p,$a;
	$query=0;
	$query=mysqlquery("$q");
	if(!mysqlerror()) {
		if(mysqlnumrows($query)) {
			$q=array();
			$return=array();
			while($q=mysqlfetcharray($query)) {
				$return[]=$q["$field"];
			}
			return $return;
		}
	} else {
		//log this error
		$errorName=0;
		$errorName="Database Error!";
		$errorDescription=0;
		$errorDescription="<strong>Database error:</strong> ".mysqlerror();
		$errorDescription.="<br><strong>URL:</strong> ".getThisURL();
		$errorDescription.="<br><strong>Page:</strong> $p";
		$errorDescription.="<br><strong>Action:</strong> $a";
		logError($errorName,$errorDescription,"",getUserID($trailSessionUser));
	}
}

/**
* function to match data
* @param: $table
* @param: $option
* @param: $info
*/
function matchData($table,$comparefields,$returnfield,$searchterm,$options) {
	if($searchterm) {
		$ha=array();
		$ha=explode(",",$comparefields);
		//iterations
		foreach($ha as $h) {
			//trim
			$h=trim($h);
			//query
			$query=0;
			$query=mysqlquery("select $returnfield,match($h) against('$searchterm' in boolean mode) as score 
								from $table 
								where 
								$options and 
								(match($h) against('$searchterm' in boolean mode) or $h like '%$searchterm%') 
								order by score desc 
								limit 1");
			if(!mysqlerror()) {
				if(mysqlnumrows($query)) {
					return mysqlresult($query,0,$returnfield);
				}
			}
		}
	}
}


function insertData($data,$table){
	$columns_str=$values_str="";
	foreach ($data as $k => $v) {
		$columns_str.="`$k`,";
		$val=str_replace("'", "''", $v);
		$val=trim($val);
		$values_str.="'$val',";	
	}
	$columns_str=rtrim($columns_str,",");
	$values_str=rtrim($values_str,",");

	$res=mysqlquery("INSERT INTO `$table` ($columns_str) VALUES ($values_str)");
	$ret=$res?1:0;
	return $ret;
}

function updateData($data,$table,$cond=""){
	if($cond==1 || count($data)<=0) return 0;
	$sql_str="";
	$cols_str="";
	foreach ($data as $k => $v) {
		$val=str_replace("'", "''", $v);
		$val=trim($val);
		$sql_str.="`$k`='$val',";
		$cols_str.="`$k`,";
	}
	$cols_str=rtrim($cols_str,",");
	$sql_str=rtrim($sql_str,",");

	logUpdate($table,$cols_str,$cond);

	$res=mysqlquery("UPDATE `$table` SET $sql_str WHERE $cond");
	$ret=$res?1:0;
	return $ret;
}

function logUpdate($table="",$cols="",$cond=""){
	global $trailSessionUser;
	$res=mysqlquery("SELECT id,$cols FROM $table WHERE $cond");
	while($row=mysqlfetchassoc($res)){
		$log_data=array();
		$log_data['table_name']=$table;
		$log_data['table_id']=$row['id'];
		$log_data['old_information']=json_encode($row);
		$log_data['created']=date("Y-m-d H:i:s");
		$log_data['createdby']=$trailSessionUser;
		insertData($log_data,"vl_logs_edits");
	}
}

function getData($cols="*",$table="",$others=""){
	return mysqlquery("SELECT $cols FROM $table $others");
}

function compareUpdateInfo($old_data=array(),$new_data=array()){
	$ret=array();
	foreach ($new_data as $key => $value) {
		# code...
		if($value!=$old_data[$key]) $ret[$key]=$value;
	}
	return $ret;
}
?>