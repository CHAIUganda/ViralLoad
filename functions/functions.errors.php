<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* ERROR LOGGING FUNCTIONS
*/

/*
* function to log system identified errors
* @param: $ame - name assigned to the error
* @param: $description - description assigned to the error
* @param: $user - email of the person working when the error occured
*/
function logError($name,$description,$url,$user) {
	global $datetime;
	mysqlquery("insert into vl_errors 
		(name,description,url,user,created) 
		values 
		('$name','$description','$url','$user','$datetime')");
}

/*
* function to check form submissions and ensure the parameters
* provided are what are required
* @param: $fields - field names to be checked agains, 
* parsed in the following manner fieldname1:fieldvalue1 fieldname2:fieldvalue2
*/
function checkFormFields($fields) {
	$error=0;
	$error="";
	//split $fields based on spaces
	$fieldArray=array();
	$fieldArray=preg_split("/\s/",trim($fields));
	if(count($fieldArray)) {
		for($i=0;$i<count($fieldArray);$i++) {
			//split each entry by the ':'
			$checkArray=array();
			$checkArray=preg_split("/\:\:/",trim($fieldArray[$i]));
			if(count($checkArray)>1) {
				/**
				* the 1st parameter should exist $fieldname
				* but does the 2nd one exist? $fieldvalue?
				* that's why we check to ensure array is > 1 not just any count
				* append $checkArray[0] to $error coz that's the fieldname we are
				* actually interested in showing the client
				*/
				if(!$checkArray[1]) {
					$error.="- ".preg_replace("/_/is"," ",$checkArray[0])."<br>";
				}
			}
		}
	}
	if($error) {
		return "<br>Unable to process your submission because the following fields were missing: <br>$error<br>";
	}
}
?>