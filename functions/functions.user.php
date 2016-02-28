<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* USER SPECIFIC FUNCTIONS
*/

/**
* function to get a user's ID from their phone
* @param: $phone
*/
function getUserID($email) {
	$query=0;
	$query=mysqlquery("select id from vl_users where lower(email)='".strtolower($email)."'");
	if(mysqlnumrows($query)) {
		return mysqlresult($query,0,'id');
	}
}

/**
* function to get a user's email from their id
* @param: $id
*/
function getUserEmail($userID) {
	$query=0;
	$query=mysqlquery("select email from vl_users where id='$userID'");
	if(mysqlnumrows($query)) {
		return mysqlresult($query,0,'email');
	}
}

/**
* function to get a user's details from their phone
* @param: $phone
* @param: $info - must correspond to the database field
*/
function getUserInfo($email,$info) {
	$query=0;
	$query=mysqlquery("select $info from vl_users where lower(email)='".strtolower($email)."'");
	if(mysqlnumrows($query)) {
		return mysqlresult($query,0,$info);
	}
}

/**
* function to get a user's details from their id
* @param: $id
* @param: $info - must correspond to the database field
*/
function getUserInfoByID($id,$info) {
	$query=0;
	$query=mysqlquery("select $info from vl_users where id='$id'");
	if(mysqlnumrows($query)) {
		return mysqlresult($query,0,$info);
	}
}

/**
* function to reset a user's password
* @param: $id
*/
function resetUserPassword($id) {
	//create a new password
	$password=0;
	$password=generatePassword();
	mysqlquery("update vl_users set password=password('$password') where id=$id");
	return $password;
}

/**
* function to check the client number
* @param: $clientnumber
* @param: $formname
* @param: $id
* @param: $customertype
* @param: $version
*/
function getUserInfoByClientNumber($clientNumber,$column) {
	global $datetime,$trailSessionUser;
	
	//flag
	$datafound=0;
    
    //prepare all relevant variables
    $data=0;
    
	//start by searching clientNumber in vl_profile_customers
	$query=0;
	$query=mysqlquery("select $column from vl_profiles_customers where clientNumber='$clientNumber' order by created desc");
	if(mysqlnumrows($query)) {
		//flag
		$datafound=1;
        //get data
        $q=array();
        while($q=mysqlfetcharray($query)) {
            $data=($data?$data:$q["$column"]);
        }
	}
    
	//then search ClientNumber in vl_datacap111
	if($column!="ClientAdviceNoticeFlag") {
		$query=0;
		$query=mysqlquery("select $column from vl_datacap111 where ClientNumber='$clientNumber' order by created desc");
		if(mysqlnumrows($query)) {
			//flag
			$datafound=1;
			//get data
			$q=array();
			while($q=mysqlfetcharray($query)) {
				$data=($data?$data:$q["$column"]);
			}
		}
	}
    
	//then search ClientNumber in vl_datacba111
	$query=0;
	$query=mysqlquery("select $column from vl_datacba111 where ClientNumber='$clientNumber' order by created desc");
	if(mysqlnumrows($query)) {
		//flag
		$datafound=1;
        //get data
        $q=array();
        while($q=mysqlfetcharray($query)) {
            $data=($data?$data:$q["$column"]);
        }
	}
    
	//then search ClientNumber in vl_databc111
	if($column!="ClientAdviceNoticeFlag" && $column!="ClientConsentFlag") {
		$query=0;
		$query=mysqlquery("select $column from vl_databc111 where ClientNumber='$clientNumber' order by created desc");
		if(mysqlnumrows($query)) {
			//flag
			$datafound=1;
			//get data
			$q=array();
			while($q=mysqlfetcharray($query)) {
				$data=($data?$data:$q["$column"]);
			}
		}
	}
    
	if($column!="ClientAdviceNoticeFlag") {
		//then search ClientNumber in vl_datacap109
		$query=0;
		$query=mysqlquery("select $column from vl_datacap109 where ClientNumber='$clientNumber' order by created desc");
		if(mysqlnumrows($query)) {
			//flag
			$datafound=1;
			//get data
			$q=array();
			while($q=mysqlfetcharray($query)) {
				$data=($data?$data:$q["$column"]);
			}
		}
	}
    
	//then search ClientNumber in vl_datacba109
	$query=0;
	$query=mysqlquery("select $column from vl_datacba109 where ClientNumber='$clientNumber' order by created desc");
	if(mysqlnumrows($query)) {
		//flag
		$datafound=1;
        //get data
        $q=array();
        while($q=mysqlfetcharray($query)) {
            $data=($data?$data:$q["$column"]);
        }
	}
    
	//then search ClientNumber in vl_databc109
	if($column!="ClientAdviceNoticeFlag" && $column!="ClientConsentFlag") {
		$query=0;
		$query=mysqlquery("select $column from vl_databc109 where ClientNumber='$clientNumber' order by created desc");
		if(mysqlnumrows($query)) {
			//flag
			$datafound=1;
			//get data
			$q=array();
			while($q=mysqlfetcharray($query)) {
				$data=($data?$data:$q["$column"]);
			}
		}
	}
    
	//then search ClientNumber in vl_datacap106
	/*
	$query=0;
	$query=mysqlquery("select $column from vl_datacap106 where ClientNumber='$clientNumber' order by created desc");
	if(mysqlnumrows($query)) {
		//flag
		$datafound=1;
        //get data
        $q=array();
        while($q=mysqlfetcharray($query)) {
            $data=($data?$data:$q["$column"]);
        }
	}
    
	//then search ClientNumber in vl_datacba106
	$query=0;
	$query=mysqlquery("select $column from vl_datacba106 where ClientNumber='$clientNumber' order by created desc");
	if(mysqlnumrows($query)) {
		//flag
		$datafound=1;
        //get data
        $q=array();
        while($q=mysqlfetcharray($query)) {
            $data=($data?$data:$q["$column"]);
        }
	}

	//then search ClientNumber in vl_databc106
	$query=0;
	$query=mysqlquery("select $column from vl_databc106 where ClientNumber='$clientNumber' order by created desc");
	if(mysqlnumrows($query)) {
		//flag
		$datafound=1;
        //get data
        $q=array();
        while($q=mysqlfetcharray($query)) {
            $data=($data?$data:$q["$column"]);
        }
	}
	*/
	return $data;
}

/**
* function to check if this client was accepted or rejected based on output files
* @param: $clientnumber
* @param: $version
*/
function getUserCRBStatus($clientNumber,$dataTable,$headerID,$file,$version) {
	//work out the headerID for this output file
	$outputHeaderID=0;
	$outputHeaderID=getDetailedTableInfo2("vl_header","inputoutput='output' and SubmissionEndDate='".getDetailedTableInfo2("vl_header","id='$headerID'","SubmissionEndDate")."' and File='$file' and Version='$version' order by id desc limit 1","id");
	//is there an output file matching this $dataTable
	if($outputHeaderID) {
		if(isQuery("SELECT $dataTable.* 
						FROM $dataTable,vl_rules,vl_datacodes,vl_header 
						WHERE 
						vl_rules.ruleName = vl_datacodes.code AND 
						vl_datacodes.dataID = $dataTable.id AND 
						vl_datacodes.headerID = vl_header.id AND 
						vl_header.id = '$outputHeaderID' AND 
						vl_rules.file = '$file' AND 
						vl_rules.rejectRule = 'Yes' AND 
						vl_rules.version = '$version' AND 
						$dataTable.ClientNumber = '$clientNumber'")) {
			//rejected
			return "<font class=\"vl_red\">Rejected</a>";
		} else {
			//accepted
			return "<font class=\"vl_green\">Accepted</a>";
		}
	} else {
		//unknown
		return "<font class=\"vl_grey\">Unknown</a>";
	}
}

/**
* function to change a user's setting
* @param: $userID
* @param: $setting
* @param: $value
*/
function alterUserSetting($userID,$setting,$value) {
	//globals
	global $datetime,$trailSessionUser;
	
	//does user have a setting account?
	$query=0;
	$query=mysqlquery("select * from vl_users_settings where userID='$userID'");
	if(mysqlnumrows($query)) {
		//log table change
		logTableChange("vl_users_settings","$setting",getDetailedTableInfo2("vl_users_settings","userID='$userID'","id"),getDetailedTableInfo2("vl_users_settings","userID='$userID'","$setting"),$value);
		//update
		mysqlquery("update vl_users_settings set $setting='$value' where userID='$userID'");
	} else {
		//insert
		mysqlquery("insert into vl_users_settings 
						(userID,$setting,created,createdby) 
						values 
						('$userID','$value','$datetime','$trailSessionUser')");
	}
}
?>