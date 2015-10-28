<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* function to get the phone code for a given country
* @param: $country
*/
function getPhoneCode($country) {
	//get the towns first
	$query=0;
	$query=mysqlquery("select distinct phonecode from vl_countries where lower(country)=lower('$country')");
	if(mysqlnumrows($query)) {
		return mysqlresult($query,0,'phonecode');
	} else {
		return "+";
	}
}

/**
* function to get the countryID for a given country
* @param: $country
*/
function getCountryID($country) {
	global $datetime,$trailSessionUser;
	//get the towns first
	$query=0;
	$query=mysqlquery("select distinct countryID from vl_countries where lower(country)=lower('$country')");
	if(mysqlnumrows($query)) {
		return mysqlresult($query,0,'countryID');
	} else {
		//create a new country and return it's ID
		$mquery=0;
		$mquery=mysqlquery("select max(countryID) maxID from vl_countries");
		$maxID=0;
		$maxID=mysqlresult($mquery,0,'maxID')+1;
		mysqlquery("insert into vl_countries 
				(countryID,country,created,createdby) 
				values 
				('$maxID','$country','$datetime','$trailSessionUser')");
		return $maxID;
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
		return removeSpecialCharacters(mysqlresult($query,0,'country'));
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
		return removeSpecialCharacters(mysqlresult($query,0,'country'));
	}
}

/**
* function to get the townID for a given town
* @param: $town
*/
function getTownID($town) {
	//get the towns first
	$query=0;
	$query=mysqlquery("select distinct townID from vl_countries where lower(town)=lower('$town')");
	if(mysqlnumrows($query)) {
		return mysqlresult($query,0,'townID');
	}
}

/**
* function to get the town for a given townID
* @param: $townID
*/
function getTown($townID) {
	if(is_numeric($townID)) {
		//get the towns first
		$query=0;
		$query=mysqlquery("select distinct town from vl_countries where townID='$townID' and townID!=0");
		if(mysqlnumrows($query)) {
			return removeSpecialCharacters(mysqlresult($query,0,'town'));
		}
	} else {
		return $townID;
	}
}

/**
* function to process a town
* @param: $town
* @param: $country
*/
function processTown($town,$country) {
	global $datetime,$trailSessionUser;

	//a townID is usually what is submitted in the $town variable
	if(!getTown($town)) {
		$query=0;
		$query=mysqlquery("select distinct town from vl_countries where (lower(town)=lower('$town') or townID='$town') and lower(country)=lower('$country')");
		//if no town but country exists
		if(!mysqlresult($query,0,'town') && getCountryID($country)) {
			//process a townID
			$tquery=0;
			$tquery=mysqlquery("select max(townID) maxID from vl_countries");
			$townID=0;
			$townID=mysqlresult($tquery,0,'maxID')+1;
	
			mysqlquery("insert into vl_countries 
						(countryID,country,townID,town,phonecode,created,createdby) 
						values 
						('".getCountryID($country)."','$country','$townID','$town','".getPhoneCode($country)."','$datetime','$trailSessionUser')");

			return $townID;
		}
	} else {
		//otherwise, we already have the townID
		return removeSpecialCharacters($town);
	}
}

/**
* function to get the towns for a given country
* @param: $country
*/
function getTownsInCountry($country,$default) {
	//get the towns first
	$query=0;
	$query=mysqlquery("select distinct townID,town from vl_countries where lower(country)=lower('$country') and published=1 and townID!=0 order by town");
	if(mysqlnumrows($query)) {
		$return=0;
		$return="<select name=\"town\" id=\"town\" class=\"search\" onChange=\"selectother(this.form);\">";
		if($default) {
			$return.="<option value=\"".(is_numeric($default)?$default:getTownID($default))."\" selected>".(is_numeric($default)?getTown($default):$default)."</option>";
		} else {
			$return.="<option value=\"\" selected>- select ".removeSpecialCharacters((getTextAttributesInCountry("city",$country,0)?getTextAttributesInCountry("city",$country,0):"town"))." -</option>";
		}
		$q=array();
		while($q=mysqlfetcharray($query)) {
			$return.="<option value=\"$q[townID]\">".removeSpecialCharacters($q[town])."</option>";
		}
		$return.="<option value=\"Other\">Other</option>";
		$return.="</select>";
	} else {
		$return=0;
		$return="<input name=\"town\" type=\"text\" class=\"search\" id=\"town\" value=\"$default\" size=\"25\" maxlength=\"250\">";
	}
	return $return;
}

/**
* function to get the towns for a given country
* @param: $country
*/
function getCustomisedTownsInCountry($country,$default,$fieldName,$fieldID) {
	//get the towns first
	$query=0;
	$query=mysqlquery("select distinct townID,town from vl_countries where lower(country)=lower('$country') and published=1 order by town");
	if(mysqlnumrows($query)) {
		$return=0;
		$return="<select name=\"$fieldName\" id=\"$fieldName\" class=\"search\" onChange=\"if((this.form).$fieldName.value=='Other') { loadInput('$fieldName','$fieldID','$default'); }\">";
		if($default) {
			$return.="<option value=\"".(is_numeric($default)?$default:getTownID($default))."\" selected>".(is_numeric($default)?getTown($default):$default)."</option>";
		} else {
			$return.="<option value=\"\" selected>- select ".removeSpecialCharacters((getTextAttributesInCountry("city",$country,0)?getTextAttributesInCountry("city",$country,0):"town"))." -</option>";
		}
		$q=array();
		while($q=mysqlfetcharray($query)) {
			$return.="<option value=\"$q[townID]\">".removeSpecialCharacters($q[town])."</option>";
		}
		$return.="<option value=\"Other\">Other</option>";
		$return.="</select>";
	} else {
		$return=0;
		$return="<input name=\"$fieldName\" type=\"text\" class=\"search\" id=\"$fieldName\" value=\"\" size=\"25\" maxlength=\"250\">";
	}
	return $return;
}

/**
* function to get the attributes for a given country
* @param: $country
*/
function getTextAttributesInCountry($attribute,$country,$formatted) {
	$query=0;
	$query=mysqlquery("select distinct $attribute from vl_countries where lower(country)=lower('$country') and published=1 order by town");
	if(mysqlnumrows($query) && mysqlresult($query,0,$attribute)!="") {
		if($formatted) {
			return "<font class=\"vl_red\">*</font> ".removeSpecialCharacters(mysqlresult($query,0,$attribute)).":";
		} else {
			return removeSpecialCharacters(mysqlresult($query,0,$attribute));
		}
	}
}

/**
* function to get the towns for a given country
* @param: $country
*/
function getFieldAttributesInCountry($attribute,$attributevalue,$country) {
	//get the towns first
	$query=0;
	$query=mysqlquery("select distinct $attribute from vl_countries where lower(country)=lower('$country') and published=1 order by town");
	if(mysqlnumrows($query) && mysqlresult($query,0,$attribute)!="") {
		if($attribute=="zip") {
			return "<input name=\"$attribute\" type=\"text\" class=\"search\" id=\"$attribute\" value=\"$attributevalue\" size=\"15\" maxlength=\"15\">";
		} else {
			return "<input name=\"$attribute\" type=\"text\" class=\"search\" id=\"$attribute\" value=\"$attributevalue\" size=\"25\" maxlength=\"250\">";
		}
	}
}

/**
* function to load countries against a check-box allowing
* a user select individual countries as part of his selection
* of markets
*/
function loadCountriesForSelection($companyID) {
	global $trailSessionUser;

	$theUserID=0;
	//ensure $trailSessionUser is not a visitor
	if(substr($trailSessionUser,0,7)!="visitor") {
		$theUserID=getUserID($trailSessionUser);
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
						<td width=\"99%\">".removeSpecialCharacters($q[country])."</td>
					</tr>";
		}
		$return.="</table>";

		return $return;
	} else {
		$return=0;
		//$return="No countries found in database!";
		$return="";

		return $return;
	}
}

/**
* function to get specific countries from a region
* @param: $region e.g. africa, south america etc
*/
function loadCountriesFromRegion($region,$companyID) {
	if($region) {
		global $trailSessionUser;
	
		$theUserID=0;
		//ensure $trailSessionUser is not a visitor
		if(substr($trailSessionUser,0,7)!="visitor") {
			$theUserID=getUserID($trailSessionUser);
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
							<td width=\"99%\">".removeSpecialCharacters($q[country])."</td>
						</tr>";
			}
			$return.="</table>";
	
			return $return;
		} else {
			$return=0;
			//$return="No countries found in database!";
			$return="";
	
			return $return;
		}
	}
}

/**
* function to get the region to which a country belongs
* @param: $country
*/
function getCountryRegion($country) {
	return getDetailedTableInfo2("vl_countries","lower(country)=lower('$country')","region");
}
?>