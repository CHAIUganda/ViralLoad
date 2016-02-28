<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* FORM SPECIFIC
*/

/**
* function to load a form
* @string: $formurl
* @array: $parameters
*/
function loadForm($formurl,$parameters) {
	//init curl
	$curld = curl_init();
	//Prepare for the POST operation
	curl_setopt($curld,CURLOPT_POST,true);
	//Give cURL the variable names & values to POST
	curl_setopt($curld,CURLOPT_POSTFIELDS,$parameters);
	//The URL to which to POST the data
	curl_setopt($curld,CURLOPT_URL,$formurl);
	//Indicate that we want the output returned into a variable
	curl_setopt($curld,CURLOPT_RETURNTRANSFER,true);
	//output
	$return=0;
	$return=curl_exec($curld);
	//cleanup
	curl_close($curld);
	//return
	return $return;
}
?>