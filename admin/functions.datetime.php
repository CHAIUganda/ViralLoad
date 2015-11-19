<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* DATE/TIME RELATED FUNCTIONS
*/

/**
* function to take a given date and establish its authenticity
* @param: $datetime
*/
function isDateAuthentic($datetime) {
	//convert to timestamps
	$currentTimestamp=0;
	$currentTimestamp=strtotime($datetime);
	//return the new date
	$finalDate=0;
	$finalDate=date('Y-m-d', $currentTimestamp);
	//check if this date is valid
	$d = DateTime::createFromFormat('Y-m-d', $finalDate);
	if($d && 
		$d->format('Y-m-d') == $finalDate && 
			$finalDate!="0000-00-00" && 
				$finalDate!="1970-01-01" && 
					$finalDate!="NULL" && 
						$finalDate!="" && 
							$finalDate!="0") {
		return $datetime;
	}
}

/**
* function to take a given date and increment it based on the supplied duration
* @param: $date
* @param: $duration - in days, otherwise provide "interval $duration day week etc"
*/
function addToDate($date,$duration) {
	//return getDualInfoWithAlias("date_add(".($date?"'$date'":"now()").",interval ".(is_numeric($duration)?"$duration day":$duration).")","newdate");
	$finalDate=0;
	$finalDate=getDualInfoWithAlias("date_add(".($date?"'$date'":"now()").",interval ".(is_numeric($duration)?"$duration day":$duration).")","newdate");
	if(isDateAuthentic($finalDate)) {
		return $finalDate;
	}
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
	$finalDate=0;
	$finalDate=date('Y-m-d', $futureTimestamp);
	if(isDateAuthentic($finalDate)) {
		return $finalDate;
	}
}

/**
* function to take a given time and increment it based on the supplied duration
* @param: $time
* @param: $duration - in minutes
*/
function addToTime($time,$duration) {
	//convert to timestamps
	$currentTimestamp=0;
	$currentTimestamp=strtotime($time);
	$futureTimestamp=0;
	$futureTimestamp=$currentTimestamp + (60*$duration);
	//return the new date
	return date('H:i:s', $futureTimestamp);
}

/**
* function to take a given time and increment it based on the supplied duration
* @param: $time
* @param: $duration - in minutes
* note: less micro seconds
*/
function addToTimeLessMicro($time,$duration) {
	//convert to timestamps
	$currentTimestamp=0;
	$currentTimestamp=strtotime($time);
	$futureTimestamp=0;
	$futureTimestamp=$currentTimestamp + (60*$duration);
	//return the new date
	return date('H:i', $futureTimestamp);
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
* function to get up to the hourly difference between two dates
* @param: $date1, $time1
* @param: $date2, $time2
*/
function getDetailedDateDifference($date1,$time1,$date2,$time2) {
	//duration
	$duration=0;
	$duration=getDateDifference($date1,$date2);
	if($duration) {
		return ($duration==1?"$duration day":"$duration days");
	} else {
		//get the hourly difference
		$timeStamp1=0;
		$timeStamp1=strtotime("$date1 $time1");
		$timeStamp2=0;
		$timeStamp2=strtotime("$date2 $time2");
		$timeDuration=0;
		$timeDuration=(strftime('%H',$timeStamp1)>strftime('%H',$timeStamp2)?strftime('%H',$timeStamp1)-strftime('%H',$timeStamp2):strftime('%H',$timeStamp2)-strftime('%H',$timeStamp1));
		if($timeDuration<0) {
			$timeDuration*=-1;
		}
		if($timeDuration) {
			return ($timeDuration==1?"$timeDuration hour":"$timeDuration hours");
		}
	}
}

/**
* function to get up to the hourly difference between two dates
* return even negatives
* @param: $date1, $time1 - should be greater than $date2, $time2 for a positive
* @param: $date2, $time2
*/
function getStandardDetailedDateDifference($date1,$time1,$date2,$time2) {
	//duration
	$duration=0;
	$duration=getStandardDateDifference($date1,$date2);
	if($duration==0) {
		//get the hourly difference
		$timeStamp1=0;
		$timeStamp1=strtotime("$date1 $time1");
		$timeStamp2=0;
		$timeStamp2=strtotime("$date2 $time2");
		$timeDuration=0;
		$timeDuration=strftime('%H',$timeStamp1)-strftime('%H',$timeStamp2);
		return $timeDuration;
	} else {
		return $duration;
	}
}

/**
* function to compare dates
* @param: $date1
* @param: $date2
* if $date1>$date2, returns 1
* else returns 0
*/
function isDate1GreaterDate2($date1,$date2) {
	//date1Timestamp
	$date1Timestamp=0;
	$date1Timestamp=strtotime("$date1");
	$date2Timestamp=0;
	$date2Timestamp=strtotime("$date2");
	//duration
	$diff=0;
	$diff=$date1Timestamp-$date2Timestamp;
	return ($diff>1?1:0);
}

/**
* function to take a given date in the form of yyyy-mm-dd and return it as day, dd-month-year
* @param: $date
*/
function getFormattedDate($date) {
	//convert to timestamps
	$currentTimestamp=0;
	$currentTimestamp=strtotime($date);
	//return the new date
	if(isDateAuthentic(date('Y-m-d', $currentTimestamp))) {
		return date('D, d-M-Y', $currentTimestamp);
	}
}

/**
* function to take a given date in the form of yyyy-mm-dd and return it as yyyymmdd
* @param: $date
*/
function getFormattedDateCRB($date) {
	//convert to timestamps
	$currentTimestamp=0;
	$currentTimestamp=strtotime($date);
	//return the new date
	if(isDateAuthentic(date('Y-m-d', $currentTimestamp))) {
		return date('Ymd', $currentTimestamp);
	}
}

/**
* function to take a given date in the form of yyyy-mm-dd and return it as dd-month-year
* @param: $date
*/
function getFormattedDateLessDay($date) {
	//convert to timestamps
	$currentTimestamp=0;
	$currentTimestamp=strtotime($date);
	//return the new date
	if(isDateAuthentic(date('Y-m-d', $currentTimestamp))) {
		return date('d-M-Y', $currentTimestamp);
	}
}

/**
* function to take a given date in the form of yyyy-mm-dd and return it as year-mm-dd
* @param: $date
*/
function getRawFormattedDateLessDay($date) {
	//convert to timestamps
	$currentTimestamp=0;
	$currentTimestamp=strtotime($date);
	//return the new date
	if(isDateAuthentic(date('Y-m-d', $currentTimestamp))) {
		return date('Y-m-d', $currentTimestamp);
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
	return date('H:i:s', $currentTimestamp);
}

/**
* function to take a given date in the form of yyyy-mm-dd H:i:s and return it as time less ':s'
* @param: $date
*/
function getFormattedTimeLessS($date) {
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
	//convert to timestamps
	$currentTimestamp=0;
	$currentTimestamp=strtotime($date);
	//return the new date
	if(isDateAuthentic(date('Y-m-d', $currentTimestamp))) {
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
	if(isDateAuthentic(date('Y-m-d', $currentTimestamp))) {
		return date('d', $currentTimestamp);
	}
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
	if(isDateAuthentic(date('Y-m-d', $currentTimestamp))) {
		return date('D', $currentTimestamp);
	}
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
	if(isDateAuthentic(date('Y-m-d', $currentTimestamp))) {
		return date('m', $currentTimestamp);
	}
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
	if(isDateAuthentic(date('Y-m-d', $currentTimestamp))) {
		return date('M', $currentTimestamp);
	}
}

/**
* function to get current day
*/
function getCurrentDay() {
	global $c,$default_GMTtimezone;
	//return gmdate("D");
	return getFormattedDateDayname(getDualInfoWithAlias("convert_tz(now(),'$default_GMTtimezone:00','".getDetailedTableInfo2("vl_timezones",($c?"lower(country)='".strtolower($c)."'":"country='United Kingdom'"),"timezone")."')","datetime"));
}

/**
* function to get current year
*/
function getCurrentYear() {
	global $c,$default_GMTtimezone;
	//return gmdate("Y");
	return getFormattedDateYear(getDualInfoWithAlias("convert_tz(now(),'$default_GMTtimezone:00','".getDetailedTableInfo2("vl_timezones",($c?"lower(country)='".strtolower($c)."'":"country='United Kingdom'"),"timezone")."')","datetime"));
}

/**
* function to get current month
*/
function getCurrentMonth() {
	global $c,$default_GMTtimezone;
	//return gmdate("m");
	return getFormattedDateMonth(getDualInfoWithAlias("convert_tz(now(),'$default_GMTtimezone:00','".getDetailedTableInfo2("vl_timezones",($c?"lower(country)='".strtolower($c)."'":"country='United Kingdom'"),"timezone")."')","datetime"));
}

/**
* function to get current date
*/
function getCurrentDate() {
	global $c,$default_GMTtimezone;
	//return gmdate("Y-m-d");
	return getRawFormattedDateLessDay(getDualInfoWithAlias("convert_tz(now(),'$default_GMTtimezone:00','".getDetailedTableInfo2("vl_timezones",($c?"lower(country)='".strtolower($c)."'":"country='United Kingdom'"),"timezone")."')","datetime"));
}

/**
* function to get current second
*/
function getCurrentSecond() {
	global $c,$default_GMTtimezone;
	//return gmdate("s");
	return getFormattedSeconds(getDualInfoWithAlias("convert_tz(now(),'$default_GMTtimezone:00','".getDetailedTableInfo2("vl_timezones",($c?"lower(country)='".strtolower($c)."'":"country='United Kingdom'"),"timezone")."')","datetime"));
}

//excel to timestamp
function excelDate2mysqlDate($excelDate) {
	//numbers of days between January 1, 1900 and 1970 (including 19 leap years)
	$minDatesDiff=25569;
	//numbers of second in a day:
	$secInDay=86400;
	//timestamp
	$timestamp=0;
	$timestamp=($excelDate - $minDatesDiff) * $secInDay;

	//process
	if($excelDate <= $minDatesDiff) {
		return 0;
	}
	
	//return the new date
	if(isDateAuthentic(date('Y-m-d', $timestamp))) {
		return date('Ymd',$timestamp);
	}
}

/**
* function to take a given date in the form of mm/dd/yyyy from the excel template and return it as yyyymmdd
* @param: $date
*/
function getFormattedDateCRBExcel($date) {
	$datearray=array();
	$datearray=explode("/",$date);
	//final date
	$finalDate=0;
	$finalDate="$datearray[2]-$datearray[1]-$datearray[0]";
	if(isDateAuthentic($finalDate)) {
		return $finalDate;
	}
}
?>