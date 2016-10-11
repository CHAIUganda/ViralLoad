<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* COUNTRY SPECIFIC FUNCTIONS
*/

/**
* function to get the phone code for a given country
* @param: $country
*/
function getPhoneCode($country) {
	//get the towns first
	$query=0;
	$query=mysqlquery("select distinct phonecode from vl_countries where lower(country)='".strtolower($country)."'");
	if(mysqlnumrows($query)) {
		return mysqlresult($query,0,'phonecode');
	} else {
		return "+";
	}
}

/**
* function to get the country for a given countryID
* @param: $countryID
*/
function getCountry($countryID) {
	//get the towns first
	$query=0;
	$query=mysqlquery("select distinct country from vl_countries where countryID='$countryID'");
	if(mysqlnumrows($query)) {
		return mysqlresult($query,0,'country');
	}
}

/**
* function to get the country for a given townID
* @param: $townID
*/
function getCountryFromTownID($townID) {
	//get the towns first
	$query=0;
	$query=mysqlquery("select distinct country from vl_countries where townID='$townID'");
	if(mysqlnumrows($query)) {
		return mysqlresult($query,0,'country');
	}
}

/**
* function to get the townID for a given town
* @param: $town
*/
function getTownID($town) {
	//get the towns first
	$query=0;
	$query=mysqlquery("select distinct townID from vl_countries where lower(town)='".strtolower($town)."'");
	if(mysqlnumrows($query)) {
		return mysqlresult($query,0,'townID');
	}
}

/**
* function to get the town for a given townID
* @param: $townID
*/
function getTown($townID) {
	//get the towns first
	$query=0;
	$query=mysqlquery("select distinct town from vl_countries where townID='$townID' and townID!=0");
	if(mysqlnumrows($query)) {
		return mysqlresult($query,0,'town');
	}
}

/**
* function to process a town
* @param: $town
* @param: $country
*/
function processTown($town,$country) {
	global $datetime,$user;

	//a townID is usually what is submitted in the $town variable
	if(!getTown($town)) {
		$query=0;
		$query=mysqlquery("select distinct town from vl_countries where (lower(town)=lower('$town') or townID='$town') and lower(country)=lower('$country')");
		//if no town but country exists
		if(!mysqlnumrows($query) && getCountryID($country)) {
			//process a townID
			$tquery=0;
			$tquery=mysqlquery("select max(townID) maxID from vl_countries");
			$townID=0;
			$townID=mysqlresult($tquery,0,'maxID')+1;
	
			mysqlquery("insert into vl_countries 
						(countryID,country,townID,town,phonecode,created,createdby) 
						values 
						('".getCountryID($country)."','$country','$townID','$town','".getPhoneCode($country)."','$datetime','$user')");

			return $townID;
		}
	}
}

/**
* function to get the towns for a given country
* @param: $country
*/
function getTownsInCountry($country,$default) {
	//get the towns first
	$query=0;
	$query=mysqlquery("select distinct townID,town from vl_countries where lower(country)=lower('$country') and published=1 order by town");
	if(mysqlnumrows($query)) {
		$return=0;
		$return="<select name=\"town\" id=\"town\" class=\"search\" onChange=\"selectother(this.form);\">";
		if($default) {
			$return.="<option ".getTownID($default)." selected>$default</option>";
		} else {
			$return.="<option selected>- select town -</option>";
		}
		$q=array();
		while($q=mysqlfetcharray($query)) {
			$return.="<option value=\"$q[townID]\">$q[town]</option>";
		}
		$return.="<option value=\"Other\">Other</option>";
		$return.="</select>";
	} else {
		$return=0;
		$return="<input name=\"town\" type=\"text\" class=\"search\" id=\"town\" value=\"\" size=\"20\" maxlength=\"250\">";
	}
	return $return;
}

/**
* function to load countries against a check-box allowing
* a user select individual countries as part of his selection
* of markets
*/
function loadCountriesForSelection($companyID) {
	global $user;

	$theUserID=0;
	//ensure $user is not a visitor
	if(substr($user,0,7)!="visitor") {
		$theUserID=getUserID($user);
	}

	//get the towns first
	$query=0;
	$query=mysqlquery("select distinct countryID,country from vl_countries order by country");
	if(mysqlnumrows($query)) {
		$return=0;
		$return="
			<table width=\"100%\" border=\"0\">
			  <tr>
				<td colspan=\"2\">Select the markets covered:</td>
			  </tr>";
		$q=array();
		while($q=mysqlfetcharray($query)) {
			$return.="
					<tr>
						<td width=\"1%\"><input type=\"checkbox\" name=\"marketscoveredUnique[]\" value=\"$q[countryID]\" ".(checkMarketAgainstProvider($theUserID,$q["countryID"],$companyID)?"checked":"")."></td>
						<td width=\"99%\">$q[country]</td>
					</tr>";
		}
		$return.="</table>";

		return $return;
	} else {
		$return=0;
		$return="No countries found in database!";

		return $return;
	}
}

/**
* function to get specific countries from a region
* @param: $region e.g. africa, south america etc
*/
function loadCountriesFromRegion($region,$companyID) {
	if($region) {
		global $user;
	
		$theUserID=0;
		//ensure $user is not a visitor
		if(substr($user,0,7)!="visitor") {
			$theUserID=getUserID($user);
		}
	
		//get the towns first
		$query=0;
		$query=mysqlquery("select distinct countryID,country from vl_countries where region='$region' order by country");
		if(mysqlnumrows($query)) {
			$return=0;
			$return="
				<table width=\"100%\" border=\"0\">
				  <tr>
					<td colspan=\"2\">Select the markets covered:</td>
				  </tr>";
			$q=array();
			while($q=mysqlfetcharray($query)) {
				$return.="
						<tr>
							<td width=\"1%\"><input type=\"checkbox\" name=\"marketscoveredUnique[]\" value=\"$q[countryID]\" ".(checkMarketAgainstProvider($theUserID,$q["countryID"],$companyID)?"checked":"")."></td>
							<td width=\"99%\">$q[country]</td>
						</tr>";
			}
			$return.="</table>";
	
			return $return;
		} else {
			$return=0;
			$return="No countries found in database!";
	
			return $return;
		}
	}
}
?>