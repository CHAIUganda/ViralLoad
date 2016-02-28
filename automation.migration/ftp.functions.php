<?
//initiate db connection
$db = 0;
$db = mysqlconnect('localhost', 'root', '');
mysqlselectdb("dr_changamka");

/**
* function to override mysql_connect
*/
function mysqlconnect($server,$username,$password) {
	return mysqli_connect($server,$username,$password);
}

/**
* function to override mysql_select_db
*/
function mysqlselectdb($databasename) {
	global $db;
	
	mysqli_select_db($db,$databasename);
}

/**
* function to override mysql_query
*/
function mysqlquery($query) {
	global $db;
	
	return mysqli_query($db,$query);
}

/**
* function to override mysql_num_rows
*/
function mysqlnumrows($resultindex) {
	return mysqli_num_rows($resultindex);
}

/**
* function to override mysql_result
*/
function mysqlresult($res,$row=0,$col=0){ 
	$numrows = mysqli_num_rows($res); 
	if ($numrows && $row <= ($numrows-1) && $row >=0){
		mysqli_data_seek($res,$row);
		$resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
		if (isset($resrow[$col])){
			return $resrow[$col];
		}
	}
	return false;
}

/**
* function to override mysql_fetch_array
*/
function mysqlfetcharray($resultindex) {
	return mysqli_fetch_array($resultindex);
}

/**
* function to override mysql_error
*/
function mysqlerror() {
	global $db;
	
	return mysqli_error($db);
}

/**
* FUNCTION DEFINITIONS
* function to query the dual system table
* @param: $info
*/
function getDualInfo($info) {
	$query=0;
	$query=mysqlquery("select $info from dual");
	if(!mysqlerror()) {
		if(mysqlnumrows($query)) {
			return mysqlresult($query,0,$info);
		}
	}
}

/**
* function to query the dual system table
* @param: $info
* @param: $alias
*/
function getDualInfoWithAlias($info,$alias) {
	$query=0;
	$query=mysqlquery("select $info $alias from dual");
	if(!mysqlerror()) {
		if(mysqlnumrows($query)) {
			return mysqlresult($query,0,$alias);
		}
	}
}

/**
* function to get the table field
* @param: $table
* @param: $id
* @param: $info
*/
function getTableInfo($info,$table,$options) {
	$query=0;
	$query=mysqlquery("select $info from $table where $options");
	if(!mysqlerror()) {
		if(mysqlnumrows($query)) {
			return mysqlresult($query,0,$info);
		}
	}
}

/**
* function to get detailed table information
* @param: $table
* @param: $option
* @param: $info
* @param: $alias
*/
function getTableInfoWithAlias($info,$alias,$table,$options) {
	$query=0;
	$query=mysqlquery("select ".($info && $alias?"$info $alias":"*")." from $table where $options");
	if(!mysqlerror()) {
		if(mysqlnumrows($query)) {
			return ($info?mysqlresult($query,0,$alias):1);
		}
	}
}

/**
* function to take a given date and increment it based on the supplied duration
* @param: $date
* @param: $duration - in days, otherwise provide "interval $duration day week etc"
*/
function addToDate($date,$duration) {
	return getDualInfoWithAlias("date_add(".($date?"'$date'":"now()").",interval ".(is_numeric($duration)?"$duration day":$duration).")","newdate");
}

/**
* function to take a given date and reduce it based on the supplied duration
* @param: $date
* @param: $duration - in days
*/
function subtractFromDate($date,$duration) {
	//convert to timestamps
	$currentTimestamp=0;
	$currentTimestamp=strtotime($date);
	$futureTimestamp=0;
	$futureTimestamp=$currentTimestamp - (60*60*24*$duration);
	//return the new date
	return date('Y-m-d', $futureTimestamp);
}

/**
* function to get the difference between two dates, in days
* @param: $date1
* @param: $date2
*/
function getDateDifference($date1,$date2) {
	//duration
	$duration=0;
	$duration=getDualInfoWithAlias("datediff('$date1','$date2')","duration");
	if($duration<0) {
		return (getDualInfoWithAlias("datediff('$date1','$date2')","duration")*-1);
	} else {
		return getDualInfoWithAlias("datediff('$date1','$date2')","duration");
	}
}

/**
* function to get the difference between two dates, in days
* return negatives as well
* @param: $date1 - should be greater than $date2 for a positive result
* @param: $date2
*/
function getStandardDateDifference($date1,$date2) {
	return getDualInfoWithAlias("datediff('$date1','$date2')","duration");
}

/**
* function to take a given date in the form of yyyy-mm-dd and return it as day, dd-month-year
* @param: $date
*/
function getFormattedDate($date) {
	if($date=="0000-00-00 00:00:00" || $date=="NULL" || $date=="0" || $date=="") {
		//return "No date";
		return "";
	} else {
		//convert to timestamps
		$currentTimestamp=0;
		$currentTimestamp=strtotime($date);
		//return the new date
		return date('D, d-M-Y', $currentTimestamp);
	}
}

/**
* function to take a given date in the form of yyyy-mm-dd and return it as day, dd-month-year
* @param: $date
*/
function getFormattedDateMYSQL($date) {
	if($date=="0000-00-00 00:00:00" || $date=="NULL" || $date=="0" || $date=="") {
		//return "No date";
		return "";
	} else {
		//convert to timestamps
		$currentTimestamp=0;
		$currentTimestamp=strtotime($date);
		//return the new date
		return date('Y-m-d', $currentTimestamp);
	}
}

/**
* function to take a given date in the form of yyyy-mm-dd and return it as day, dd-month-year
* @param: $date
*/
function getFormattedDateSimple($date) {
	if($date=="0000-00-00 00:00:00" || $date=="NULL" || $date=="0" || $date=="") {
		//return "No date";
		return "";
	} else {
		//convert to timestamps
		$currentTimestamp=0;
		$currentTimestamp=strtotime($date);
		//return the new date
		return date('Ymd', $currentTimestamp);
	}
}

/**
* function to take a given date in the form of yyyy-mm-dd H:i:s and return it as time
* @param: $date
*/
function getFormattedTime($date) {
	//convert to timestamps
	$currentTimestamp=0;
	$currentTimestamp=strtotime($date);
	//return the new date
	return date('H:i', $currentTimestamp);
}

/**
* function to take a given date in the form of yyyy-mm-dd H:i:s and return it's Hour
* @param: $date
*/
function getFormattedHour($date) {
	//convert to timestamps
	$currentTimestamp=0;
	$currentTimestamp=strtotime($date);
	//return the new date
	return date('H', $currentTimestamp);
}

/**
* function to take a given date in the form of yyyy-mm-dd H:i:s and return it's Minutes
* @param: $date
*/
function getFormattedMinutes($date) {
	//convert to timestamps
	$currentTimestamp=0;
	$currentTimestamp=strtotime($date);
	//return the new date
	return date('i', $currentTimestamp);
}

/**
* function to take a given date in the form of yyyy-mm-dd H:i:s and return it's Seconds
* @param: $date
*/
function getFormattedSeconds($date) {
	//convert to timestamps
	$currentTimestamp=0;
	$currentTimestamp=strtotime($date);
	//return the new date
	return date('s', $currentTimestamp);
}

/**
* function to take a given date in the form of yyyy-mm-dd and return it as year
* @param: $date
*/
function getFormattedDateYear($date) {
	if($date) {
		//convert to timestamps
		$currentTimestamp=0;
		$currentTimestamp=strtotime($date);
		//return the new date
		return date('Y', $currentTimestamp);
	}
}

/**
* function to take a given date in the form of yyyy-mm-dd and return it as week day
* @param: $date
*/
function getFormattedDateDay($date) {
	//convert to timestamps
	$currentTimestamp=0;
	$currentTimestamp=strtotime($date);
	//return the new date
	return date('d', $currentTimestamp);
}

/**
* function to take a given date in the form of yyyy-mm-dd and return it as day
* @param: $date
*/
function getFormattedDateDayname($date) {
	//convert to timestamps
	$currentTimestamp=0;
	$currentTimestamp=strtotime($date);
	//return the new date
	return date('D', $currentTimestamp);
}

/**
* function to take a given date in the form of yyyy-mm-dd and return it as month
* @param: $date
*/
function getFormattedDateMonth($date) {
	//convert to timestamps
	$currentTimestamp=0;
	$currentTimestamp=strtotime($date);
	//return the new date
	return date('m', $currentTimestamp);
}

/**
* function to take a given date in the form of yyyy-mm-dd and return it as monthname
* @param: $date
*/
function getFormattedDateMonthname($date) {
	//convert to timestamps
	$currentTimestamp=0;
	$currentTimestamp=strtotime($date);
	//return the new date
	return date('M', $currentTimestamp);
}

/**
* function to take a given date in the form of yyyy-mm-dd and return it as week
* @param: $date
*/
function getFormattedDateWeekofyear($date) {
	//convert to timestamps
	$currentTimestamp=0;
	$currentTimestamp=strtotime($date);
	//return the new date
	return date('W', $currentTimestamp);
}

/**
* function to get current day
*/
function getCurrentDay() {
	//return gmdate("D");
	return getFormattedDateDayname(getDualInfoWithAlias("now()","datetime"));
}

/**
* function to get current year
*/
function getCurrentYear() {
	//return gmdate("Y");
	return getFormattedDateYear(getDualInfoWithAlias("now()","datetime"));
}

/**
* function to get current month
*/
function getCurrentMonth() {
	//return gmdate("m");
	return getFormattedDateMonth(getDualInfoWithAlias("now()","datetime"));
}

/**
* function to get current date
*/
function getCurrentDate() {
	//return gmdate("Y-m-d");
	return getRawFormattedDateLessDay(getDualInfoWithAlias("now()","datetime"));
}

/**
* function to get current second
*/
function getCurrentSecond() {
	//return gmdate("s");
	return getFormattedSeconds(getDualInfoWithAlias("now()","datetime"));
}

/**
* function to get file extension
*/
function getFileExtension($documentName) {
	//retrieve the last 3 characters from the file's name
	$extension=0;
	$position=0;
	$position=strlen($documentName)-3;
	$extension = substr($documentName, $position, 3);
	//address the jpeg issue
	if(strtolower($extension)=="peg") {
		return "jpeg";
	} else {
		return strtolower($extension);
	}
}

/**
* function to send mail
* @param: $recepient
*/
function sendReportByMail($to) {
	/*
	* reports are sent once every week i.e
	* every monday, at 7:00am
	* $firstWeekDate = today() - 7 days
	* $lastWeekDate = today() - 1 day
	*/
	$firstWeekDate=0;
	$firstWeekDate=subtractFromDate(getDualInfoWithAlias("now()","datetime"),7);
	$lastWeekDate=0;
	$lastWeekDate=subtractFromDate(getDualInfoWithAlias("now()","datetime"),1);

	//key mail variables
	$from=0;
	$from="info@antsms.com";

	$subject=0;
	$subject="Test HTML Mail";

	$message="";
	$message="<style type=\"text/css\">
				<!--
				body {
					margin-top: 0px;
					margin-bottom: 0px;
					background-color: #FFFFFF;
				}
				.changamka {
					font-family: arial;
					color: #3A3A3A;
					font-size: 12px;
				}
				.changamkas {
					font-family: arial;
					color: #999999;
					font-size: 11px;
				}
				-->\n
			</style>
			
			<div style=\"padding:10px\">
				<div style=\"padding:20px; border: 1px solid #ccccff\">
					<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"changamka\">
						<tr>
							<td style=\"padding:10px; border-bottom: 1px solid #999999\"><strong>Synchronization Report for the Week: ".getFormattedDate($firstWeekDate)." - ".getFormattedDate($lastWeekDate)."</strong></td>
						</tr>
						<tr>
							<td style=\"padding:10px\">Synchronization Report for the Week</td>
						</tr>
					</table>
				</div>
			</div>";

	$headers="MIME-Version: 1.0\r\n";
	$headers.="Content-type: text/html; charset=iso-8859-1 \r\n";
	$headers.="From: $from\r\n";

	//dispatch mail
	if(mail($to,$subject,$message,$headers)) {
		return 1;
	}
}
?>