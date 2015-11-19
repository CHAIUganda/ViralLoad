<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

/*
$string="4442 Internal control cycle number is too high. Valid range is [18.35, 22.35].";
echo "String: $string<br>";
echo "SubString: ".substr($string,0,47);
*/

$date="2015/10/30";
echo "Date: $date<br>
		Final Date: ".getFormattedDate($date);
?>