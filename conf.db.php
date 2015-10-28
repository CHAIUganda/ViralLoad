<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

if($_SERVER['REMOTE_ADDR']=="127.0.0.1") {
	$db = mysqlconnect('localhost', 'root', '');
	mysqlselectdb("eid_uganda_vl");
} elseif($_SERVER['SERVER_NAME']=="vl.trailanalytics.com") {
	$db = mysqlconnect('localhost', 'borrower_vl', 'viralload#123');
	mysqlselectdb("borrower_vl");
} else {
	$db = mysqlconnect('localhost', 'root', 'root#VL');
	mysqlselectdb("eid_uganda_vl");
}

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
* function to remove uneccessary characters from input
* @param: $string
*/
function validate($string) {
	//trim
	$string=trim($string);
	//remove load_file
	$string=preg_replace("/load_file/is","loadfile",$string);
	//remove ';
	$string=preg_replace("/';/s","",$string);
	//remove '
	$string=preg_replace("/'/s","",$string);
	//remove %20
	$string=preg_replace("/%20/s"," ",$string);
	//remove %
	$string=preg_replace("/%/s","",$string);
	//remove script
	$string=preg_replace("/script/is","",$string);
	//remove javascript
	$string=preg_replace("/javascript/is","",$string);
	//remove src
	$string=preg_replace("/src/is","",$string);
	//remove alert(
	$string=preg_replace("/alert\s?\(/is","(",$string);
	//remove javascript mouseevents
	$string=preg_replace("/onclick\s?=/is","=",$string);
	$string=preg_replace("/ondblclick\s?=/is","=",$string);
	$string=preg_replace("/onkeydown\s?=/is","=",$string);
	$string=preg_replace("/onkeypress\s?=/is","=",$string);
	$string=preg_replace("/onkeyup\s?=/is","=",$string);
	$string=preg_replace("/onmousedown\s?=/is","=",$string);
	$string=preg_replace("/onmousemove\s?=/is","=",$string);
	$string=preg_replace("/onmouseout\s?=/is","=",$string);
	$string=preg_replace("/onmouseup\s?=/is","=",$string);
	$string=preg_replace("/onmouseover\s?=/is","=",$string);
	//remove 0x
	$string=preg_replace("/0x/is","ox",$string);
	//remove html special characters
	$string=validateVariable($string);
	//return
	return trim($string);
}

/**
* function to remove uneccessary characters from input
* less HTML validations
* @param: $string
*/
function validateLessHTML($string) {
	//trim
	$string=trim($string);
	//remove load_file
	$string=preg_replace("/load_file/is","loadfile",$string);
	//remove ';
	$string=preg_replace("/';/s","",$string);
	//remove '
	$string=preg_replace("/'/s","",$string);
	//remove %20
	$string=preg_replace("/%20/s"," ",$string);
	//remove %
	$string=preg_replace("/%/s","",$string);
	//remove script
	$string=preg_replace("/script/is","",$string);
	//remove javascript
	$string=preg_replace("/javascript/is","",$string);
	//remove src
	$string=preg_replace("/src/is","",$string);
	//remove alert(
	$string=preg_replace("/alert\s?\(/is","(",$string);
	//remove javascript mouseevents
	$string=preg_replace("/onclick\s?=/is","=",$string);
	$string=preg_replace("/ondblclick\s?=/is","=",$string);
	$string=preg_replace("/onkeydown\s?=/is","=",$string);
	$string=preg_replace("/onkeypress\s?=/is","=",$string);
	$string=preg_replace("/onkeyup\s?=/is","=",$string);
	$string=preg_replace("/onmousedown\s?=/is","=",$string);
	$string=preg_replace("/onmousemove\s?=/is","=",$string);
	$string=preg_replace("/onmouseout\s?=/is","=",$string);
	$string=preg_replace("/onmouseup\s?=/is","=",$string);
	$string=preg_replace("/onmouseover\s?=/is","=",$string);
	//remove 0x
	$string=preg_replace("/0x/is","ox",$string);
	//return
	return trim($string);
}

/**
* function to remove uneccessary characters from output
* @param: $string
*/
function validateVariable($string) {
	//remove &
	$string=preg_replace("/&amp;/is","and",$string);
	$string=preg_replace("/&/s","and",$string);
	//remove <
	$string=preg_replace("/</s","&lt;",$string);
	//remove >
	$string=preg_replace("/>/s","&gt;",$string);
	//return
	return $string;
}

/**
* function to remove uneccessary characters from output
* @param: $string
*/
function validateError($string) {
	//remove &
	$string=preg_replace("/&amp;/is","and",$string);
	$string=preg_replace("/&/s","and",$string);
	//remove <
	$string=preg_replace("/</s","&lt;",$string);
	//remove >
	$string=preg_replace("/>/s","&gt;",$string);
	//re-instate <b> from &lt;b&gt;
	$string=preg_replace("/&lt;b&gt;/is","<b>",$string);
	//re-instate </b> from &lt;/b&gt;
	$string=preg_replace("/&lt;\/b&gt;/is","</b>",$string);
	//re-instate <strong> from &lt;strong&gt;
	$string=preg_replace("/&lt;strong&gt;/is","<strong>",$string);
	//re-instate </strong> from &lt;/strong&gt;
	$string=preg_replace("/&lt;\/strong&gt;/is","</strong>",$string);
	//re-instate <br> from &lt;br&gt;
	$string=preg_replace("/&lt;br&gt;/is","<br>",$string);
	//re-instate <br/> from &lt;br/&gt;
	$string=preg_replace("/&lt;br(\s?)\/&gt;/is","<br\\3/>\\4\\5",$string);
	//re-instate <a> from &lt;a&gt;
	$string=preg_replace("/&lt;a&gt;/is","<a>",$string);
	//re-instate </a> from &lt;/a&gt;
	$string=preg_replace("/&lt;\/a&gt;/is","</a>",$string);
	//return
	return $string;
}

/**
* function to get a string and replace key attributes within it
*/
function replaceCharacters($pattern,$replacement,$string) {
	//$string=str_replace($pattern,$replacement,$string);
	if($pattern && $replacement && $string) {
		$string=preg_replace("/$pattern/is",$replacement,$string);
	}
	return $string;
}

/**
* function to get the validated variable by post or get
* @param: $string
*/
function getValidatedVariable($string) {
	//return
	return validate($_POST["$string"]?$_POST["$string"]:$_GET["$string"]);
}

/**
* function to get the variable
*/
function getVariable($variable) {
	if($_GET["$variable"]) {
		return validate($_GET["$variable"]);
	} elseif($_POST["$variable"]) {
		return validate($_POST["$variable"]);
	} else {
		return validate($$variable);
	}
}

/**
* function to get the variable, disregard the HTML tags therein
*/
function getVariableLessHTML($variable) {
	if($_GET["$variable"]) {
		return validateLessHTML($_GET["$variable"]);
	} elseif($_POST["$variable"]) {
		return validateLessHTML($_POST["$variable"]);
	} else {
		return validateLessHTML($$variable);
	}
}

/**
* function to get the unvalidated variable
*/
function getVariableRAW($variable) {
	if($_GET["$variable"]) {
		return $_GET["$variable"];
	} elseif($_POST["$variable"]) {
		return $_POST["$variable"];
	} else {
		return $$variable;
	}
}
?>
