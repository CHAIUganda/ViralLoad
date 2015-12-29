<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* SAMPLE SPECIFIC FUNCTIONS
*/

/**
* function to generate a unique sample number
*/
function generateSampleNumber() {
	global $datetime,$user;
	//variables
	$number=0;
	$number=getDetailedTableInfo3("vl_samples","year(created)='".getCurrentYear()."' and month(created)='".getCurrentMonth()."'","count(id)","num");
	$number+=1;
	
	$reference=0;
	switch(strlen($number)) {
		case 1:
			$reference.="000$number";
		break;
		case 2:
			$reference.="00$number";
		break;
		case 3:
			$reference.="0$number";
		break;
		default:
			$reference.=$number;
		break;
	}
	return $reference."/".getFormattedDateMonth($datetime).getFormattedDateYearShort($datetime);
}

/**
* function to generate a unique print number for abbott
*/
function generateAbbottPrintID() {
	global $default_numberPrintsAbbott;
	//get last printIDAbbott value from db
	$lastPrintID=0;
	$lastPrintID=getDetailedTableInfo2("vl_samples_verify","outcome='Accepted' order by created desc limit 1","printIDAbbott");
	//variables
	$number=0;
	$number=getDetailedTableInfo3("vl_samples_verify","printIDAbbott='$lastPrintID'","count(id)","num");
	if(!$number) {
		$number=1;
	} elseif($number && $number<$default_numberPrintsAbbott) {
		$number=$lastPrintID;
	} elseif($number && $number>=$default_numberPrintsAbbott) {
		$number=$lastPrintID+1;
	}
	return $number;
}

/**
* function to generate a unique print number for roche
*/
function generateRochePrintID() {
	global $default_numberPrintsRoche;
	//get last printIDAbbott value from db
	$lastPrintID=0;
	$lastPrintID=getDetailedTableInfo2("vl_samples_verify","outcome='Accepted' order by created desc limit 1","printIDRoche");
	//variables
	$number=0;
	$number=getDetailedTableInfo3("vl_samples_verify","printIDRoche='$lastPrintID'","count(id)","num");
	if(!$number) {
		$number=1;
	} elseif($number && $number<$default_numberPrintsRoche) {
		$number=$lastPrintID;
	} elseif($number && $number>=$default_numberPrintsRoche) {
		$number=$lastPrintID+1;
	}
	return $number;
}

/**
* function to generate a unique print number for abbott
*/
function generateWorksheetReferenceNumber() {
	global $user,$datetime;
	//get the user's names
	$names=0;
	$names=getDetailedTableInfo2("vl_users","email='$user' limit 1","names");
	$namesArray=array();
	$namesArray=explode(" ",$names);
	$initials=0;
	if(count($namesArray)>1) {
		//get the first letter of each name
		$initials=substr(trim($namesArray[0]),0,1);
		$initials.=substr(trim($namesArray[1]),0,1);
		$initials=strtoupper($initials);
	} else {
		//get the first 2 letters of the email
		$initials=substr(trim($user),0,2);
		$initials=strtoupper($initials);
	}
	
	//variables
	$number=0;
	$number=getDetailedTableInfo3("vl_samples_worksheetcredentials","year(created)='".getCurrentYear()."' and month(created)='".getCurrentMonth()."'","count(id)","num");
	$number+=1;
	
	$reference=0;
	$reference=getFormattedDateYearShort($datetime).getFormattedDateMonth($datetime);
	switch(strlen($number)) {
		case 1:
			$reference.=$initials."00$number";
		break;
		case 2:
			$reference.=$initials."0$number";
		break;
		default:
			$reference.=$initials.$number;
		break;
	}
	return $reference;
}

/**
* function to retrieve the result
*/
function getVLResult($machineType,$worksheetID,$sampleID,$factor) {
	global $default_resultFailureNewSampleMessage;
	
	$result=0;
	//first check if any result overrides are in place
	if($worksheetID && getDetailedTableInfo2("vl_results_override","sampleID='$sampleID' and worksheetID='$worksheetID'","id")) {
		$result=getDetailedTableInfo2("vl_results_override","sampleID='$sampleID' and worksheetID='$worksheetID'","result");
		return $result;
	} elseif(!$worksheetID && getDetailedTableInfo2("vl_results_override","sampleID='$sampleID'","id")) {
		$result=getDetailedTableInfo2("vl_results_override","sampleID='$sampleID'","result");
		return $result;
	} else {
		$rocheFailedResult=0;
		$rocheFailedResult="Invalid Test Result. Insufficient sample remained to repeat the assay.";
		$abbottFailedResult=0;
		$abbottFailedResult="Failed.";
		
		if($machineType=="roche") {
			if(isResultFailed($machineType,($worksheetID?$worksheetID:""),$sampleID)) {
				/*
				* 15/Sept/14: 
				* Hellen (hnansumba@gmail.com, CPHL) requested that once a sample has failed more than once,
				* there is insufficient sample left for a re-run hence;
				* Change result to read "invalid test result. Insufficient sample remained to repeat the assay"
				*/
				if(getDetailedTableInfo3("vl_results_merged","lower(machine)='".strtolower($machineType)."' and vlSampleID='$sampleID'","count(id)","num")>1 && 
					getDetailedTableInfo2("vl_results_merged","lower(machine)='".strtolower($machineType)."' and vlSampleID='$sampleID' order by created limit 1","resultAlphanumeric")==$rocheFailedResult) {
					/*
					* 21/Jan/15: 
					* (sewyisaac@yahoo.co.uk) 
					* Regarding the report where we do not have results, this is the massage to I propose.
					* There is No Result Given. The Test Failed the Quality Control Criteria. We advise you send a a new sample. 
					* 
					* 23/Dec/2015
					* Request from Joseph Kibirige (joseph.kibirige@yahoo.com, CPHL) and Prossy Mbabazi (pronam2000@yahoo.com, CPHL)
					* when a sample has failed twice, automatically override the 2nd result with
					* "There is No Result Given. The Test Failed the Quality Control Criteria. We advise you send a a new sample."
					*/
					logResultOverride($sampleID,$worksheetID,$default_resultFailureNewSampleMessage);
					//return
					return $default_resultFailureNewSampleMessage;
				} else {
					//return
					return $rocheFailedResult;
				}
			} else {
				$result=getDetailedTableInfo2("vl_results_roche",($worksheetID?"worksheetID='$worksheetID' and ":"")."SampleID='$sampleID' order by created desc limit 1","Result");
				$result=getVLNumericResult($result,$machineType,$factor);
				return $result;
			}
		} elseif($machineType=="abbott") {
			if(isResultFailed($machineType,($worksheetID?$worksheetID:""),$sampleID)) {
				/*
				* 15/Sept/14: 
				* Hellen (hnansumba@gmail.com, CPHL) requested that once a sample has failed more than once,
				* there is insufficient sample left for a re-run hence;
				* Change result to read "invalid test result. Insufficient sample remained to repeat the assay"
				* 
				* 22/Sept/14
				* Adjustment made to recognize both samples as failures
				*/
				if(getDetailedTableInfo3("vl_results_merged","lower(machine)='".strtolower($machineType)."' and vlSampleID='$sampleID'","count(id)","num")>1 && 
					getDetailedTableInfo2("vl_results_merged","lower(machine)='".strtolower($machineType)."' and vlSampleID='$sampleID' order by created limit 1","resultAlphanumeric")==$abbottFailedResult) {
					/*
					* 21/Jan/15: 
					* (sewyisaac@yahoo.co.uk) 
					* Regarding the report where we do not have results, this is the massage to I propose.
					* There is No Result Given. The Test Failed the Quality Control Criteria. We advise you send a a new sample. 
					* 
					* 23/Dec/2015
					* Request from Joseph Kibirige (joseph.kibirige@yahoo.com, CPHL) and Prossy Mbabazi (pronam2000@yahoo.com, CPHL)
					* when a sample has failed twice, automatically override the 2nd result with
					* "There is No Result Given. The Test Failed the Quality Control Criteria. We advise you send a a new sample."
					*/
					logResultOverride($sampleID,$worksheetID,$default_resultFailureNewSampleMessage);
					//return
					return $default_resultFailureNewSampleMessage;
				} else {
					//return
					return $abbottFailedResult;
				}
			} else {
				$result=getDetailedTableInfo2("vl_results_abbott",($worksheetID?"worksheetID='$worksheetID' and ":"")."sampleID='$sampleID' order by created desc limit 1","result");
				$result=getVLNumericResult($result,$machineType,$factor);
				return $result;
			}
		}
	}
}

/**
* function to retrieve the result
*/
function getVLNumericResult($result,$machineType,$factor) {
	//check machine types
	if($machineType=="roche" || $machineType=="abbott") {
		//check conditions
		if($result=="Not detected" || $result=="Target Not Detected" || $result=="Failed" || $result=="Invalid") {
			return $result;
		} elseif(substr(trim($result),0,1)=="<") {
			//clean the result remove "Copies / mL" and "," from $result
			$result=preg_replace("/Copies \/ mL/s","",$result);
			$result=preg_replace("/,/s","",$result);
			$result=preg_replace("/\</s","",$result);
			$result=trim($result);
			/*
			* do not multiply by factor, based on a 17/Sept/14 discussion 
			* with Christine at the CPHL Viral Load Lab
			* $result*=$factor;
			*/
		
			//return
			return "&lt; ".number_format((float)$result,2)." Copies / mL";
		} elseif(substr(trim($result),0,1)==">") {
			//clean the result remove "Copies / mL" and "," from $result
			$result=preg_replace("/Copies \/ mL/s","",$result);
			$result=preg_replace("/,/s","",$result);
			$result=preg_replace("/\>/s","",$result);
			$result=trim($result);
			//factor
			$result*=$factor;
		
			//return
			return "&gt; ".number_format((float)$result,2)." Copies / mL";
		} else {
			//clean the result remove "Copies / mL" and "," from $result
			$result=preg_replace("/Copies \/ mL/s","",$result);
			$result=preg_replace("/,/s","",$result);
			$result=preg_replace("/\</s","",$result);
			$result=preg_replace("/\>/s","",$result);
			$result=trim($result);
			//factor
			$result*=$factor;
		
			//return
			return number_format((float)$result)." Copies / mL";
		}
	}
}

/**
* function to retrieve only the numeric the result
*/
function getVLNumericResultOnly($result) {
	//clean the result remove "Copies / mL" and "," from $result
	$result=preg_replace("/Copies \/ mL/s","",$result);
	$result=preg_replace("/,/s","",$result);
	$result=preg_replace("/\</s","",$result);
	$result=preg_replace("/\>/s","",$result);
	$result=preg_replace("/\&gt\;/s","",$result);
	$result=preg_replace("/\&lt\;/s","",$result);
	$result=trim($result);

	//return
	if(is_numeric($result)) {
		return $result;
	}
}

/**
* function to log whether this sample should be repeated
*/
function logRepeat($machineType,$sampleID,$worksheetID,$result,$flags) {
	global $datetime,$user;
	
	//result and flags should all be lower caps for easier comparison
	$result=trim(strtolower($result));
	$flags=trim(strtolower($flags));

	//avoid duplicates
	$id=0;
	$id=getDetailedTableInfo2("vl_logs_samplerepeats","sampleID='$sampleID' limit 1","id");
	if(!$id) {
		//first time, and sample qualifies for an automatic repeat?
		if(($machineType=="roche" && 
			($result=="failed" || $result=="invalid")) || 
				($machineType=="abbott" && 
					($result=="-1.00" || 
						$result=="3153 there is insufficient volume in the vessel to perform an aspirate or dispense operation." || 
							$result=="3109 a no liquid detected error was encountered by the liquid handler." || 
								$flags=="4442 internal control cycle number is too high. valid range is [18.35, 22.35]."))) {
			mysqlquery("insert into vl_logs_samplerepeats 
							(sampleID,oldWorksheetID,created,createdby) 
							values 
							('$sampleID','$worksheetID','$datetime','$user')");
		}
	} else {
		//get last worksheetID this sample was entered with
		$oldWorksheetID=0;
		$oldWorksheetID=getDetailedTableInfo2("vl_logs_samplerepeats","sampleID='$sampleID' and oldWorksheetID!='' and withWorksheetID='' and created<'$datetime' order by created desc limit 1","oldWorksheetID");
		$withWorksheetID=0;
		$withWorksheetID=getDetailedTableInfo2("vl_logs_samplerepeats","sampleID='$sampleID' and oldWorksheetID!='' and withWorksheetID!='' and created<'$datetime' order by created desc limit 1","withWorksheetID");
		if($oldWorksheetID!=$worksheetID) {
			//log changes
			$modifyID=0;
			$modifyID=getDetailedTableInfo2("vl_logs_samplerepeats","sampleID='$sampleID' and oldWorksheetID='$oldWorksheetID' order by created limit 1","id");
			logTableChange("vl_logs_samplerepeats","repeatedOn",$modifyID,getDetailedTableInfo2("vl_logs_samplerepeats","id='$modifyID'","repeatedOn"),$datetime);
			logTableChange("vl_logs_samplerepeats","withWorksheetID",$modifyID,getDetailedTableInfo2("vl_logs_samplerepeats","id='$modifyID'","withWorksheetID"),$worksheetID);
			//modify the db
			mysqlquery("update vl_logs_samplerepeats set repeatedOn='$datetime',withWorksheetID='$worksheetID' where sampleID='$sampleID' and oldWorksheetID='$oldWorksheetID'");
		} elseif($withWorksheetID==$worksheetID) {
			/*
			* this is a new repeat, which has failed and needs to be re-run 
			* e.g row 1, 193 (under withWorksheetID column) was a repeat which also failed and 
			* ended up being repeated
			+----+----------+----------------+---------------------+-----------------+
			| id | sampleID | oldWorksheetID | repeatedOn          | withWorksheetID |
			+----+----------+----------------+---------------------+-----------------+
			| 37 |     4794 |            192 | 2014-11-17 10:06:58 |             193 |
			| 40 |     4794 |            193 | 2014-11-18 10:07:59 |             203 |
			+----+----------+----------------+---------------------+-----------------+
			*/
			if(($machineType=="roche" && 
				($result=="failed" || $result=="invalid")) || 
					($machineType=="abbott" && 
						($result=="-1.00" || 
							$result=="3153 there is insufficient volume in the vessel to perform an aspirate or dispense operation." || 
								$result=="3109 a no liquid detected error was encountered by the liquid handler." || 
									$flags=="4442 internal control cycle number is too high. valid range is [18.35, 22.35]."))) {
					mysqlquery("insert into vl_logs_samplerepeats 
									(sampleID,oldWorksheetID,created,createdby) 
									values 
									('$sampleID','$worksheetID','$datetime','$user')");
			}
		}
	}
}


/**
* function to fix repeated samples in the VL database
*/
function fixDuplicateSampleIDs() {
	global $datetime,$user;
	//get any duplicate sample IDs
	$query=0;
	$query=mysqlquery("select vlSampleID,count(id) num from vl_samples group by vlSampleID having num>1 order by num desc");
	if(mysqlnumrows($query)) {
		while($q=mysqlfetcharray($query)) {
			//query where vlSampleID=$q[vlSampleID]
			$queryS=0;
			$queryS=mysqlquery("select * from vl_samples where vlSampleID='$q[vlSampleID]' order by created");
			$count=0;
			while($qS=mysqlfetcharray($queryS)) {
				$count+=1;
				//only change the 2nd vlSampleID onwards
				if($count>1) {
					$vlSampleArray=0;
					$vlSampleArray=explode("/",trim($q["vlSampleID"]));
					
					$sampleLength1=0;
					$sampleLength1=strlen($vlSampleArray[0]);
					$sampleLength2=0;
					$sampleLength2=strlen(($vlSampleArray[0]/1)+($count-1));
					$sampleLengthFinal=0;
					$sampleLengthFinal=abs($sampleLength1-$sampleLength2);
					
					$vlSample1=0;
					$vlSample1=($vlSampleArray[0]/1)+($count-1);
					switch($sampleLengthFinal) {
						case "5":
							$vlSample1="00000".$vlSample1;
						break;
						case "4":
							$vlSample1="0000".$vlSample1;
						break;
						case "3":
							$vlSample1="000".$vlSample1;
						break;
						case "2":
							$vlSample1="00".$vlSample1;
						break;
						case "1":
							$vlSample1="0".$vlSample1;
						break;
					}
					
					$vlSampleID=0;
					$vlSampleID=$vlSample1."/".$vlSampleArray[1];
					//update vl_samples
					mysqlquery("update vl_samples set vlSampleID='$vlSampleID' where id='$qS[id]'");
				}
			}
		}
	}
}

/**
* function to verify whether a result from a machine is failed
* @param: $machineType
* @param: $worksheetID
* @param: $sampleID
*/
function isResultFailed($machineType,$worksheetID,$sampleID) {
	switch(strtolower($machineType)) {
		case "abot":
		case "abbot":
		case "abott":
		case "abbott":
			if(getDetailedTableInfo3("vl_results_abbott",($worksheetID?"worksheetID='$worksheetID' and ":"")."sampleID='$sampleID'","count(id)","num")>=1 && 
					(trim(getDetailedTableInfo2("vl_results_abbott",($worksheetID?"worksheetID='$worksheetID' and ":"")."sampleID='$sampleID' order by created desc limit 1","result"))=="-1.00" || 
						trim(getDetailedTableInfo2("vl_results_abbott",($worksheetID?"worksheetID='$worksheetID' and ":"")."sampleID='$sampleID' order by created desc limit 1","result"))=="3153 There is insufficient volume in the vessel to perform an aspirate or dispense operation." || 
							trim(getDetailedTableInfo2("vl_results_abbott",($worksheetID?"worksheetID='$worksheetID' and ":"")."sampleID='$sampleID' order by created desc limit 1","result"))=="3109 A no liquid detected error was encountered by the Liquid Handler." || 
								trim(getDetailedTableInfo2("vl_results_abbott",($worksheetID?"worksheetID='$worksheetID' and ":"")."sampleID='$sampleID' order by created desc limit 1","result"))=="A no liquid detected error was encountered by the Liquid Handler." || 
									trim(getDetailedTableInfo2("vl_results_abbott",($worksheetID?"worksheetID='$worksheetID' and ":"")."sampleID='$sampleID' order by created desc limit 1","result"))=="Unable to process result, instrument response is invalid." || 
										trim(getDetailedTableInfo2("vl_results_abbott",($worksheetID?"worksheetID='$worksheetID' and ":"")."sampleID='$sampleID' order by created desc limit 1","result"))=="3118 A clot limit passed error was encountered by the Liquid Handler." || 
											trim(getDetailedTableInfo2("vl_results_abbott",($worksheetID?"worksheetID='$worksheetID' and ":"")."sampleID='$sampleID' order by created desc limit 1","result"))=="3130 A less liquid than expected error was encountered by the Liquid Handler." || 
												trim(getDetailedTableInfo2("vl_results_abbott",($worksheetID?"worksheetID='$worksheetID' and ":"")."sampleID='$sampleID' order by created desc limit 1","result"))=="3131 A more liquid than expected error was encountered by the Liquid Handler." || 
													trim(getDetailedTableInfo2("vl_results_abbott",($worksheetID?"worksheetID='$worksheetID' and ":"")."sampleID='$sampleID' order by created desc limit 1","result"))=="3152 The specified submerge position for the requested liquid volume exceeds the calibrated Z bottom" || 
														trim(getDetailedTableInfo2("vl_results_abbott",($worksheetID?"worksheetID='$worksheetID' and ":"")."sampleID='$sampleID' order by created desc limit 1","result"))=="4455 Unable to process result, instrument response is invalid." || 
															trim(getDetailedTableInfo2("vl_results_abbott",($worksheetID?"worksheetID='$worksheetID' and ":"")."sampleID='$sampleID' order by created desc limit 1","result"))=="A no liquid detected error was encountered by the Liquid Handler." || 
																trim(getDetailedTableInfo2("vl_results_abbott",($worksheetID?"worksheetID='$worksheetID' and ":"")."sampleID='$sampleID' order by created desc limit 1","result"))=="Failed          Internal control cycle number is too high. Valid range is [18.48, 22.48]." || 
																	trim(getDetailedTableInfo2("vl_results_abbott",($worksheetID?"worksheetID='$worksheetID' and ":"")."sampleID='$sampleID' order by created desc limit 1","result"))=="Failed          Failed            Internal control cycle number is too high. Valid range is [18.48," || 
																		trim(getDetailedTableInfo2("vl_results_abbott",($worksheetID?"worksheetID='$worksheetID' and ":"")."sampleID='$sampleID' order by created desc limit 1","result"))=="Failed          Failed          Internal control cycle number is too high. Valid range is [18.48, 2" || 
																			trim(getDetailedTableInfo2("vl_results_abbott",($worksheetID?"worksheetID='$worksheetID' and ":"")."sampleID='$sampleID' order by created desc limit 1","result"))=="OPEN" || 
																				trim(getDetailedTableInfo2("vl_results_abbott",($worksheetID?"worksheetID='$worksheetID' and ":"")."sampleID='$sampleID' order by created desc limit 1","result"))=="There is insufficient volume in the vessel to perform an aspirate or dispense operation." || 
																					trim(getDetailedTableInfo2("vl_results_abbott",($worksheetID?"worksheetID='$worksheetID' and ":"")."sampleID='$sampleID' order by created desc limit 1","result"))=="Unable to process result, instrument response is invalid." || 
																						trim(substr(getDetailedTableInfo2("vl_results_abbott",($worksheetID?"worksheetID='$worksheetID' and ":"")."sampleID='$sampleID' order by created desc limit 1","flags"),0,47))=="4442 Internal control cycle number is too high.")) {
				return 1;
			}
		break;
		case "roche":
			if(getDetailedTableInfo3("vl_results_roche","SampleID='$sampleID'","count(id)","num")>1 && 
				getDetailedTableInfo2("vl_results_roche","SampleID='$sampleID' and (Result='Failed' or Result='Invalid'))","id")) {
				return 1;
			}
		break;
	}
}

/**
* function to log result overrides
*/
function logResultOverride($sampleID,$worksheetID,$result) {
	global $datetime,$user;
	
	//validate
	$sampleID=validate($sampleID);
	$worksheetID=validate($worksheetID);
	$result=validate($result);
	
	$id=0;
	$id=getDetailedTableInfo2("vl_results_override","sampleID='$sampleID' and worksheetID='$worksheetID'","id");
	//avoid duplicates
	if(!$id) {
		//insert into vl_results_override
		mysqlquery("insert into vl_results_override 
				(sampleID,worksheetID,result,created,createdby) 
				values 
				('$sampleID','$worksheetID','$result','$datetime','$user')");
	} else {
		//log table change
		logTableChange("vl_results_override","result",$id,getDetailedTableInfo2("vl_results_override","id='$id'","result"),$result);
		//update vl_results_override
		mysqlquery("update vl_results_override set result='$result' where id='$id'");
	}
}
?>