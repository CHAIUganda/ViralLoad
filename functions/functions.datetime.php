<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* DATE/TIME RELATED FUNCTIONS
*/

/*
 * This work of Lionel SAURON (http://sauron.lionel.free.fr:80) is licensed under the
 * Creative Commons Attribution 2.0 France License.
 *
 * To view a copy of this license, visit http://creativecommons.org/licenses/by/2.0/fr/
 * or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
 */

/**
 * Parse a time/date generated with strftime().
 *
 * This function is the same as the original one defined by PHP (Linux/Unix only),
 *  but now you can use it on Windows too.
 *  Limitation : Only this format can be parsed %S, %M, %H, %d, %m, %Y
 * 
 * @author Lionel SAURON
 * @version 1.0
 * @public
 * 
 * @param $sDate(string)    The string to parse (e.g. returned from strftime()).
 * @param $sFormat(string)  The format used in date  (e.g. the same as used in strftime()).
 * @return (array)          Returns an array with the <code>$sDate</code> parsed, or <code>false</code> on error.
 */
if(function_exists("strptime") == false)
{
    function strptime($sDate, $sFormat)
    {
        $aResult = array
        (
            'tm_sec'   => 0,
            'tm_min'   => 0,
            'tm_hour'  => 0,
            'tm_mday'  => 1,
            'tm_mon'   => 0,
            'tm_year'  => 0,
            'tm_wday'  => 0,
            'tm_yday'  => 0,
            'unparsed' => $sDate,
        );

        while($sFormat != "")
        {
            // ===== Search a %x element, Check the static string before the %x =====
            $nIdxFound = strpos($sFormat, '%');
            if($nIdxFound === false)
            {

                // There is no more format. Check the last static string.
                $aResult['unparsed'] = ($sFormat == $sDate) ? "" : $sDate;
                break;
            }

            $sFormatBefore = substr($sFormat, 0, $nIdxFound);
            $sDateBefore   = substr($sDate,   0, $nIdxFound);

            if($sFormatBefore != $sDateBefore) break;

            // ===== Read the value of the %x found =====
            $sFormat = substr($sFormat, $nIdxFound);
            $sDate   = substr($sDate,   $nIdxFound);

            $aResult['unparsed'] = $sDate;

            $sFormatCurrent = substr($sFormat, 0, 2);
            $sFormatAfter   = substr($sFormat, 2);

            $nValue = -1;
            $sDateAfter = "";

            switch($sFormatCurrent)
            {
                case '%S': // Seconds after the minute (0-59)

                    sscanf($sDate, "%2d%[^\\n]", $nValue, $sDateAfter);

                    if(($nValue < 0) || ($nValue > 59)) return false;

                    $aResult['tm_sec']  = $nValue;
                    break;

                // ----------
                case '%M': // Minutes after the hour (0-59)
                    sscanf($sDate, "%2d%[^\\n]", $nValue, $sDateAfter);

                    if(($nValue < 0) || ($nValue > 59)) return false;

                    $aResult['tm_min']  = $nValue;
                    break;

                // ----------
                case '%H': // Hour since midnight (0-23)
                    sscanf($sDate, "%2d%[^\\n]", $nValue, $sDateAfter);

                    if(($nValue < 0) || ($nValue > 23)) return false;

                    $aResult['tm_hour']  = $nValue;
                    break;

                // ----------
                case '%d': // Day of the month (1-31)
                    sscanf($sDate, "%2d%[^\\n]", $nValue, $sDateAfter);

                    if(($nValue < 1) || ($nValue > 31)) return false;

                    $aResult['tm_mday']  = $nValue;
                    break;

                // ----------
                case '%m': // Months since January (0-11)
                    sscanf($sDate, "%2d%[^\\n]", $nValue, $sDateAfter);

                    if(($nValue < 1) || ($nValue > 12)) return false;

                    $aResult['tm_mon']  = ($nValue - 1);
                    break;

                // ----------
                case '%Y': // Years since 1900

                    sscanf($sDate, "%4d%[^\\n]", $nValue, $sDateAfter);

                    if($nValue < 1900) return false;

                    $aResult['tm_year']  = ($nValue - 1900);
                    break;

                // ----------
                default:
                    break 2; // Break Switch and while

            } // END of case format

            // ===== Next please =====
            $sFormat = $sFormatAfter;
            $sDate   = $sDateAfter;

            $aResult['unparsed'] = $sDate;

        } // END of while($sFormat != "")

        // ===== Create the other value of the result array =====
        $nParsedDateTimestamp = mktime($aResult['tm_hour'], $aResult['tm_min'], $aResult['tm_sec'],
                                $aResult['tm_mon'] + 1, $aResult['tm_mday'], $aResult['tm_year'] + 1900);

        // Before PHP 5.1 return -1 when error
        if(($nParsedDateTimestamp === false)
        ||($nParsedDateTimestamp === -1)) return false;

        $aResult['tm_wday'] = (int) strftime("%w", $nParsedDateTimestamp); // Days since Sunday (0-6)
        $aResult['tm_yday'] = (strftime("%j", $nParsedDateTimestamp) - 1); // Days since January 1 (0-365)

        return $aResult;
    } // END of function

} // END of if(function_exists("strptime") == false)
	
function DEFINE_date_create_from_format() {
	function date_create_from_format($dformat, $dvalue) {
		$schedule = $dvalue;
		$schedule_format = str_replace(array('Y','m','d', 'H', 'i','a'),array('%Y','%m','%d', '%I', '%M', '%p' ) ,$dformat);
		// %Y, %m and %d correspond to date()'s Y m and d.
		// %I corresponds to H, %M to i and %p to a
		$ugly = strptime($schedule, $schedule_format);
		$ymd = sprintf(
		// This is a format string that takes six total decimal
		// arguments, then left-pads them with zeros to either
		// 4 or 2 characters, as needed
		'%04d-%02d-%02d %02d:%02d:%02d',
		$ugly['tm_year'] + 1900,  // This will be "111", so we need to add 1900.
		$ugly['tm_mon'] + 1,      // This will be the month minus one, so we add one.
		$ugly['tm_mday'], 
		$ugly['tm_hour'], 
		$ugly['tm_min'], 
		$ugly['tm_sec']);
		$new_schedule = new DateTime($ymd);

		return $new_schedule;
	}
}

if( !function_exists("date_create_from_format") )
	DEFINE_date_create_from_format();

/**
* function to take a given date and establish its authenticity
* @param: $datetime
*/
function isDateAuthentic($datetime) {
	//convert to timestamps
	$currentTimestamp=0;
	$currentTimestamp=strtotime($datetime);
	//if $datetime==0000-00-00, then $currentTimestamp is empty however date('Y-m-d', $currentTimestamp) will resolve as 1970-01-01
	if($currentTimestamp) {
		//return the new date
		$finalDate=0;
		$finalDate=date('Y-m-d', $currentTimestamp);
		//check if this date is valid
		//$d = DateTime::createFromFormat('Y-m-d', $finalDate);
		$d = date_create_from_format('Y-m-d', $finalDate);
		if($d && 
			$d->format('Y-m-d') == $finalDate && 
				$datetime!="0000-00-00" && 
					$datetime!="0000-00-00 00:00" && 
						$datetime!="0000-00-00 00:00:00" && 
							$finalDate!="NULL" && 
								$finalDate!="" && 
									$finalDate!="0") {
			return $datetime;
		}
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
	if(isDateAuthentic($date)) {
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
	if($currentTimestamp) {
		return date('H:i:s', $futureTimestamp);
	}
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
	if($currentTimestamp) {
		return date('H:i', $futureTimestamp);
	}
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
	if(isDateAuthentic($date1) && isDateAuthentic($date2)) {
		return abs(getDualInfoWithAlias("datediff('$date1','$date2')","duration"));
	}
}

/**
* function to get the difference between two dates, in days
* return negatives as well
* @param: $date1 - should be greater than $date2 for a positive result
* @param: $date2
*/
function getStandardDateDifference($date1,$date2) {
	if(isDateAuthentic($date1) && isDateAuthentic($date2)) {
		return getDualInfoWithAlias("datediff('$date1','$date2')","duration");
	}
}

/**
* function to get up to the hourly difference between two dates
* @param: $date1, $time1
* @param: $date2, $time2
*/
function getDetailedDateDifference($date1,$time1,$date2,$time2) {
	if(isDateAuthentic($date1) && isDateAuthentic($date2)) {
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
			$timeDuration=abs($timeDuration);
			if($timeDuration && $timeStamp1 && $timeStamp2) {
				return ($timeDuration==1?"$timeDuration hour":"$timeDuration hours");
			}
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
	if(isDateAuthentic($date1) && isDateAuthentic($date2)) {
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
			if($timeStamp1 && $timeStamp2) {
				return $timeDuration;
			}
		} else {
			return $duration;
		}
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
	if(isDateAuthentic($date1) && isDateAuthentic($date2)) {
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
	if(isDateAuthentic($date)) {
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
	if(isDateAuthentic($date)) {
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
	if(isDateAuthentic($date)) {
		return date('d-M-Y', $currentTimestamp);
	}
}

/**
* function to take a given date in the form of yyyy-mm-dd and return it as dd-month-year
* @param: $date
*/
function getFormattedDateLessDaySlash($date) {
	//convert to timestamps
	$currentTimestamp=0;
	$currentTimestamp=strtotime($date);
	//return the new date
	if(isDateAuthentic($date)) {
		return date('d/M/Y', $currentTimestamp);
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
	if(isDateAuthentic($date)) {
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
	if($currentTimestamp) {
		return date('H:i:s', $currentTimestamp);
	}
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
	if($currentTimestamp) {
		return date('H:i', $currentTimestamp);
	}
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
	if($currentTimestamp) {
		return date('H', $currentTimestamp);
	}
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
	if($currentTimestamp) {
		return date('i', $currentTimestamp);
	}
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
	if($currentTimestamp) {
		return date('s', $currentTimestamp);
	}
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
	if(isDateAuthentic($date)) {
		return date('Y', $currentTimestamp);
	}
}

/**
* function to take a given date in the form of yyyy-mm-dd and return it as year
* @param: $date
*/
function getFormattedDateYearShort($date) {
	//convert to timestamps
	$currentTimestamp=0;
	$currentTimestamp=strtotime($date);
	//return the new date
	if(isDateAuthentic($date)) {
		return date('y', $currentTimestamp);
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
	if(isDateAuthentic($date)) {
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
	if(isDateAuthentic($date)) {
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
	if(isDateAuthentic($date)) {
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
	if(isDateAuthentic($date)) {
		return date('M', $currentTimestamp);
	}
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
	if(isDateAuthentic($date)) {
		return date('W', $currentTimestamp);
	}
}

/**
* function to get current day
*/
function getCurrentDay() {
	global $c,$default_GMTtimezone,$default_country;
	//return gmdate("D");
	return getFormattedDateDayname(getDualInfoWithAlias("now()","datetime"));
}

/**
* function to get current year
*/
function getCurrentYear() {
	global $c,$default_GMTtimezone,$default_country;
	//return gmdate("Y");
	return getFormattedDateYear(getDualInfoWithAlias("now()","datetime"));
}

/**
* function to get current month
*/
function getCurrentMonth() {
	global $c,$default_GMTtimezone,$default_country;
	//return gmdate("m");
	return getFormattedDateMonth(getDualInfoWithAlias("now()","datetime"));
}

/**
* function to get current date
*/
function getCurrentDate() {
	global $c,$default_GMTtimezone;
	//return gmdate("Y-m-d");
	return getRawFormattedDateLessDay(getDualInfoWithAlias("now()","datetime"));
}

/**
* function to get current second
*/
function getCurrentSecond() {
	global $c,$default_GMTtimezone;
	//return gmdate("s");
	return getFormattedSeconds(getDualInfoWithAlias("now()","datetime"));
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
?>