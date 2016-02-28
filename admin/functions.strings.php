<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* STRING CENTERED FUNCTIONS
*/

function generatePassword() {
	global $default_passwordLength;
	//random string
	$chars=array("a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J","k",
	"K","l","L","m","M","n","N","o","O","p","P","q","Q","r","R","s","S","t","T","u","U","v",
	"V","w","W","x","X","y","Y","z","Z","1","2","3","4","5","6","7","8","9");

	$password="";
	for($i=0;$i<$default_passwordLength;$i++) {
	   $password.=$chars[rand(0, count($chars)-1)];
	}
	return $password;
}

function generateRandomString() {
	global $default_stringLength;
	//random string
	$chars=array("a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J","k",
	"K","l","L","m","M","n","N","o","O","p","P","q","Q","r","R","s","S","t","T","u","U","v",
	"V","w","W","x","X","y","Y","z","Z","1","2","3","4","5","6","7","8","9");

	$string="";
	for($i=0;$i<$default_stringLength;$i++) {
	   $string.=$chars[rand(0, count($chars)-1)];
	}
	return $string;
}

function getSummarizedString($string,$limit) {
	if(strlen($string)>$limit) {
		return preg_replace("/<p>/is","",substr($string,0,$limit))." ...";
	} else {
		return $string;
	}
}

function getSummarizedString2($string,$limit) {
	if(strlen($string)>$limit) {
		return preg_replace("/<p>/is","",substr($string,0,$limit));
	} else {
		return $string;
	}
}

function getDetailedSummarizedString($string,$start,$limit) {
	if(strlen($string)>$limit) {
		return preg_replace("/<p>/is","",substr($string,$start,$limit));
	} else {
		return $string;
	}
}

/**
* function to determine whether an 'a' or 'an' should be used before a string
* get the first character of $string
* if a,e,i,o,u, return an else a
*/
function getAorAn($string) {
	switch(strtolower(substr($string,0,1))) {
		case a:
		case e:
		case i:
		case o:
		case u:
			return "an";
		break;
		default:
			return "a";
		break;
	}
}

/**
* function to determine whether an 'st' or 'rd' or 'th' should be used after a number
* get the last character of a number
* if 1 return number'st'
* if 2 return number'nd'
* if 3 return number'rd'
* otherwise return number'th'
*/
function getNumberPosition($number) {
	switch(substr($number,(strlen($number)-1),1)) {
		case 1:
			return $number."<sup>st</sup>";
		break;
		case 2:
			return $number."<sup>nd</sup>";
		break;
		case 3:
			return $number."<sup>rd</sup>";
		break;
		default:
			return $number."<sup>th</sup>";
		break;
	}
}

/**
* function to encrypt a string
* base64_encrypt the string
* remove last 3 characters of string
* store in swapped order
*/
function vlSimpleEncrypt($string) {
	return base64_encode(hash("sha256",$string));
}

/**
* function to decrypt a string
* remove first 3 characters of string
* swap order
* base64_decrypt the string
*/
function vlSimpleDecrypt($string) {
	return base64_decode($string);
}

/**
* function to encrypt a string
* base64_encrypt the string
* remove last 3 characters of string
* store in swapped order
*/
function vlEncrypt($string) {
	if($string) {
		//base 64 encode
		$xstring=0;
		$xstring=base64_encode($string);
	
		//get last 3 characters
		$last3=0;
		$last3=substr($xstring,(strlen($xstring)-3),3);
		$first3=0;
		$first3=substr($xstring,0,(strlen($xstring)-3));
		
		//return encrypted string
		return base64_encode($last3.$first3);
	}
}

/**
* function to decrypt a string
* remove first 3 characters of string
* swap order
* base64_decrypt the string
*/
function vlDecrypt($string) {
	if($string) {
		//decrypt
		$xstring=0;
		$xstring=base64_decode($string);
		//get first 3 characters
		$first3=0;
		$first3=substr($xstring,0,3);
		$last3=0;
		$last3=substr($xstring,3,(strlen($xstring)-3));
		
		//return decrypted string
		return base64_decode($last3.$first3);
	}
}

/**
* function to randomly encrypt a string
* generate random string
* append to vl encrypted string
* base64_encrypt the string
*/
function vlRencrypt($string) {
	//return encrypted string
	return generateRandomString().vlEncrypt($string);
}

/**
* function to remove spaces from a string and replace them with a given character
* 
*/
function removeSpaces($string,$replacement) {
	//$pattern=0;
	//$pattern="@[%20\s]+@";
	//$replace=0;
	//$replace=preg_replace($pattern," ",$string);

	$replace=0;
	$replace=preg_replace("/ /is",$replacement,$string);
	$replace=preg_replace("/%20/is",$replacement,$replace);
	return $replace;
}

/**
* function to process page links i.e.
* start page < previous 1 2 3 4 next > last page
*/
function displayOtherPagesLinks($url,$lowest,$highest,$currentvalue,$radius) {
	echo "<a href=\"$url"."pg=$lowest\">first page</a> ";
	if($currentvalue>$lowest) {
		echo "<a href=\"$url"."pg=".($currentvalue-1)."\">< previous</a> ";
	}
	for($i=$lowest;$i<=$highest;$i++) {
		if($i>=($currentvalue-$radius) && $i<=($currentvalue+$radius)) {
			if($i==$currentvalue) {
				echo "<strong>$i</strong> ";
			} else {
				echo "<a href=\"$url"."pg=$i\">$i</a> ";
			}
		}
	}
	if($currentvalue<$highest) {
		echo "<a href=\"$url"."pg=".($currentvalue+1)."\">next ></a> ";
	}	
	echo "<a href=\"$url"."pg=$highest\">last page</a>";
}

/**
* function to remove all special characters from a string
*/
function removeSpecialCharacters($string) {
	$pattern=0;
	$pattern="@[^a-zA-Z0-9`_\-;:/*!\+\@\\&£$\?<>#%\s\[\]\"=.'(),\n\r\^]+@";
	$replace=0;
	$replace=preg_replace($pattern,"",$string);
	return $replace;
}

/**
* function to remove all special characters from a string
*/
function removeAllSpecialCharacters($string) {
	$pattern=0;
	$pattern="@[^a-zA-Z0-9`_\-;:/*!\+\@\\&£$\?<>#%\s\[\]\"=.'(),\n\r\^]+@";
	$replace=0;
	$replace=preg_replace($pattern,"",$string);
	$replace=htmlentities($replace);
	return $replace;
}
?>