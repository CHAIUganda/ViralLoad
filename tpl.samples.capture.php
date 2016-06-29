<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

if($saveSample || $proceedWithWarningGender || $proceedWithWarningVLRepeatTesting || $proceedWithWarningAlternativeFacilities) {
	//validations
	$lrCategory=validate($lrCategory);
	$lrEnvelopeNumber=validate($lrEnvelopeNumber);
	if($lrEnvelopeNumber=="Envelope #") {
		$lrEnvelopeNumber="";
	}
	$lrNumericID=validate($lrNumericID);
	
	$formNumber=validate($formNumber);
	$facilityID=validate($facilityID);
	$f_res=mysqlquery("select hubID,districtID from vl_facilities where id='$facilityID' LIMIT 1");
	$f_row=mysqlfetcharray($f_res);
	$hubID= $f_row['hubID'];
	$districtID=$f_row['districtID'];

	$collectionDateDay=validate($collectionDateDay);
	$collectionDateMonth=validate($collectionDateMonth);
	$collectionDateYear=validate($collectionDateYear);
	$receiptDateDay=validate($receiptDateDay);
	$receiptDateMonth=validate($receiptDateMonth);
	$receiptDateYear=validate($receiptDateYear);
	$sampleTypeID=validate($sampleTypeID);

	$artNumber=validate($artNumber);
	//clean art number by removing - . / and spaces
	//$artNumber=preg_replace("/\-/s","",$artNumber);
	//$artNumber=preg_replace("/\./s","",$artNumber);
	//$artNumber=preg_replace("/\//s","",$artNumber);
	//$artNumber=preg_replace("/\s/s","",$artNumber);
	
	$otherID=validate($otherID);
	$gender=validate($gender);
	$dateOfBirthDay=validate($dateOfBirthDay);
	$dateOfBirthMonth=validate($dateOfBirthMonth);
	$dateOfBirthYear=validate($dateOfBirthYear);
	$dateOfBirthAge=validate($dateOfBirthAge);
	$dateOfBirthIn=validate($dateOfBirthIn);
	$patientPhone=validate($patientPhone);

	$treatmentLast6Months=validate($treatmentLast6Months);
	$treatmentInitiationDateDay=validate($treatmentInitiationDateDay);
	$treatmentInitiationDateMonth=validate($treatmentInitiationDateMonth);
	$treatmentInitiationDateYear=validate($treatmentInitiationDateYear);
	$currentRegimenID=validate($currentRegimenID);
	$treatmentInitiationID=validate($treatmentInitiationID);
	$treatmentInitiationOther=validate($treatmentInitiationOther);
	$treatmentStatusID=validate($treatmentStatusID);
	$reasonForFailureID=validate($reasonForFailureID);
	$viralLoadTestingID=validate($viralLoadTestingID);
	$pregnant=validate($pregnant);
	$pregnantANCNumber=validate($pregnantANCNumber);
	$breastfeeding=validate($breastfeeding);
	$activeTBStatus=validate($activeTBStatus);
	$tbTreatmentPhaseID=validate($tbTreatmentPhaseID);
	$arvAdherenceID=validate($arvAdherenceID);
	
	if($viralLoadTestingIndication=="vlTestingRoutineMonitoring") {
		$routineMonitoringLastVLDateDay=validate($routineMonitoringLastVLDateDay);
		$routineMonitoringLastVLDateMonth=validate($routineMonitoringLastVLDateMonth);
		$routineMonitoringLastVLDateYear=validate($routineMonitoringLastVLDateYear);
		$routineMonitoringValue=validate($routineMonitoringValue);
		$routineMonitoringSampleTypeID=validate($routineMonitoringSampleTypeID);
		$routineMonitoringLastVLDate=0;
		$routineMonitoringLastVLDate="$routineMonitoringLastVLDateYear-$routineMonitoringLastVLDateMonth-$routineMonitoringLastVLDateDay";
	}
	
	if($viralLoadTestingIndication=="vlTestingRepeatTesting") {
		$repeatVLTestLastVLDateDay=validate($repeatVLTestLastVLDateDay);
		$repeatVLTestLastVLDateMonth=validate($repeatVLTestLastVLDateMonth);
		$repeatVLTestLastVLDateYear=validate($repeatVLTestLastVLDateYear);
		$repeatVLTestValue=validate($repeatVLTestValue);
		$repeatVLTestSampleTypeID=validate($repeatVLTestSampleTypeID);
		$repeatVLTestLastVLDate=0;
		$repeatVLTestLastVLDate="$repeatVLTestLastVLDateYear-$repeatVLTestLastVLDateMonth-$repeatVLTestLastVLDateDay";
	}
	
	if($viralLoadTestingIndication=="vlTestingSuspectedTreatmentFailure") {
		$suspectedTreatmentFailureLastVLDateDay=validate($suspectedTreatmentFailureLastVLDateDay);
		$suspectedTreatmentFailureLastVLDateMonth=validate($suspectedTreatmentFailureLastVLDateMonth);
		$suspectedTreatmentFailureLastVLDateYear=validate($suspectedTreatmentFailureLastVLDateYear);
		$suspectedTreatmentFailureValue=validate($suspectedTreatmentFailureValue);
		$suspectedTreatmentFailureSampleTypeID=validate($suspectedTreatmentFailureSampleTypeID);
		$suspectedTreatmentFailureLastVLDate=0;
		$suspectedTreatmentFailureLastVLDate="$suspectedTreatmentFailureLastVLDateYear-$suspectedTreatmentFailureLastVLDateMonth-$suspectedTreatmentFailureLastVLDateDay";
	}
	
	$otherRegimen=validate($otherRegimen);
	
	//validate data
	$warnings=0;
	$warnings="";

	$error=0;
	$error=checkFormFields("Form_Number::$formNumber Facility_Name::$facilityID Gender::$gender Sample_Type::$sampleTypeID Current_Regimen::$currentRegimenID Treatment_Status::$treatmentStatusID Viral_Load_Testing::$viralLoadTestingID Pregnancy_Status::$pregnant Breastfeeding_Status::$breastfeeding Viral_Load_Testing::$viralLoadTestingID Whether_Patient_has_been_on_Treatment_for_last_6_months::$treatmentLast6Months");

	//to be edited: ensure lrCategory, lrEnvelopeNumber and lrNumericID are unique if supplied
	if($lrCategory && $lrEnvelopeNumber && $lrEnvelopeNumber!="Envelope #" && $lrNumericID) {
		if(getDetailedTableInfo2("vl_samples","lrCategory='$lrCategory' and lrEnvelopeNumber='$lrEnvelopeNumber' and lrNumericID='$lrNumericID' limit 1","id")) {
			$error.="<br /><strong>Duplicate ".($lrCategory=="V"?"Location":"Rejection")." ID '$lrCategory"."$lrEnvelopeNumber/$lrNumericID'.</strong><br />The ".($lrCategory=="V"?"Location":"Rejection")." ID <strong>$lrCategory"."$lrEnvelopeNumber/$lrNumericID</strong> was entered on <strong>".getFormattedDate(getDetailedTableInfo2("vl_samples","lrCategory='$lrCategory' and lrEnvelopeNumber='$lrEnvelopeNumber' and lrNumericID='$lrNumericID' limit 1","created"))."</strong> by <strong>".getDetailedTableInfo2("vl_samples","lrCategory='$lrCategory' and lrEnvelopeNumber='$lrEnvelopeNumber' and lrNumericID='$lrNumericID' limit 1","createdby")."</strong> <a href=\"#\" onclick=\"iDisplayMessage('/verify/preview/".getDetailedTableInfo2("vl_samples","lrCategory='$lrCategory' and lrEnvelopeNumber='$lrEnvelopeNumber' and lrNumericID='$lrNumericID' limit 1","id")."/1/noedit/')\">Click here to see the entry</a>.<br /> Kindly input this record with an alternative ".($lrCategory=="V"?"Location":"Rejection")." ID.<br />";
		}
	}

	//to be activated: ensure lrCategory, lrEnvelopeNumber and lrNumericID are supplied
	if(!$lrCategory && (!$lrEnvelopeNumber || $lrEnvelopeNumber==$default_envelopeNumber) && !$lrNumericID) {
		$error.="<br /><strong>".($lrCategory=="V"?"Location":"Rejection")." ID is Missing</strong><br />Kindly provide a ".($lrCategory=="V"?"Location or Rejection":"Rejection or Location")." ID<br />";
	}
	
	//to be edited: ensure envelope number is valid
	if($lrCategory && $lrEnvelopeNumber && $lrEnvelopeNumber!="Envelope #" && $lrNumericID) {
		if(!preg_match("/^[0-9]{4}[\-]{1}[0-9]{1,5}$/",$lrEnvelopeNumber)) {
			$error.="<br /><strong>Incorrect Envelope Number '$lrEnvelopeNumber'.</strong><br />Correct Envelope Number Format is ".getFormattedDateYearShort($datetime).getFormattedDateMonth($datetime)."-00001.<br /> Kindly resubmit with a Valid Envelope Number.<br />";
		}
	}

	//ensure form number is unique
	if(getDetailedTableInfo2("vl_samples","formNumber='$formNumber' limit 1","id")) {
		$error.="<br /><strong>Duplicate Form Number '$formNumber'.</strong><br />The Form Number <strong>$formNumber</strong> was entered on <strong>".getFormattedDate(getDetailedTableInfo2("vl_samples","formNumber='$formNumber' limit 1","created"))."</strong> by <strong>".getDetailedTableInfo2("vl_samples","formNumber='$formNumber' limit 1","createdby")."</strong> <a href=\"#\" onclick=\"iDisplayMessage('/verify/preview/".getDetailedTableInfo2("vl_samples","formNumber='$formNumber' limit 1","id")."/1/noedit/')\">Click here to see the entry</a>.<br /> Kindly input this record with an alternative Form Number.<br />";
	}

	//ensure form number is numeric
		/*if(!is_numeric($formNumber)) {
			$error.="<br /><strong>Form Number '$formNumber' is Not Numeric.</strong><br />The Form Number should be Numeric i.e it should not contain alphanumeric characters e.g A-Z.<br />";
		}*/

	//ensure form number is valid
	if(!getDetailedTableInfo2("vl_forms_clinicalrequest","formNumber='$formNumber' or formNumber='".($formNumber/1)."' limit 1","id")) {
		$error.="<br /><strong>Invalid Form Number '$formNumber'.</strong><br />The Form Number <strong>$formNumber</strong> does not exist in the list of valid Form Numbers.<br /> Kindly input this record with a valid Form Number.<br />";
	}

	//ensure facility is valid
	if(!getDetailedTableInfo2("vl_facilities","id='$facilityID'","id")) {
		$error.="<br /><strong>Incorrect Facility '$facilityID'.</strong><br />Kindly select an existing Facility from the list or Request an Administrator to first add this Facility '$facilityID' to the System's Database before Proceeding.<br />";
	}
	
	//are both ART and Other ID Number are missing
	if(!$artNumber && !$otherID) {
		$error.="<br /><strong>ART Number is Missing</strong><br />Kindly provide an ART Number<br />";
	}
	
	//date of birth missing?
	/* 7/Sept/15
	* Request from vbigira@clintonhealthaccess.org
	* Just got another urgent request from CPHL: They would like the VL database to have an additional entry for patients 
	* whose date of birth or age is not given by the facility. This was initially a 'must-have' option but with massive 
	* numbers of forms returning without this information, they would like to have the option of leaving it as a "Left blank". 
	* This can be put after the age variable.
	if((!$dateOfBirthDay || !$dateOfBirthMonth || !$dateOfBirthYear) && (!$dateOfBirthAge || !$dateOfBirthIn)) {
		$error.="<br /><strong>Date of Birth Missing</strong><br />Kindly provide the Date of Birth<br />";
	}
	*/
	
	//is both date of birth and age in years/months missing?
	$dateOfBirth=0;
	if($dateOfBirthYear && $dateOfBirthMonth && $dateOfBirthDay) {
		$dateOfBirth="$dateOfBirthYear-$dateOfBirthMonth-$dateOfBirthDay";
	} else {
		if($dateOfBirthIn=="Months") {
			$dateOfBirth=subtractFromDate($datetime,($dateOfBirthAge*30.5));
			/*
			* 20/Jun/14: suggestion by stakeholders;
			* If Patient Provides "Age in Years", then date of birth should be == 1/Jan (year when the date was computed)
			*/
			$dateOfBirth=getFormattedDateYear($dateOfBirth)."-01-01";
		} elseif($dateOfBirthIn=="Years") {
			$dateOfBirth=subtractFromDate($datetime,($dateOfBirthAge*12*30.5));
			/*
			* 20/Jun/14: suggestion by stakeholders;
			* If Patient Provides "Age in Years", then date of birth should be == 1/Jan (year when the date was computed)
			*/
			$dateOfBirth=getFormattedDateYear($dateOfBirth)."-01-01";
		} else {
			/* 7/Sept/15
			* Request from vbigira@clintonhealthaccess.org
			* Just got another urgent request from CPHL: They would like the VL database to have an additional entry for patients 
			* whose date of birth or age is not given by the facility. This was initially a 'must-have' option but with massive 
			* numbers of forms returning without this information, they would like to have the option of leaving it as a "Left blank". 
			* This can be put after the age variable.
			$error.="<br /><strong>Date of Birth is Missing</strong><br />Kindly provide the Date of Birth or Age in Months/Years<br />";
			*/
		}
	}
	
	/*
	* is pregnant, ANC # should be provided
	* 20/Jun/14: suggestion by stakeholders;
	* ANC Number is desirable but not mandatory
	if($pregnant=="Yes" && !$pregnantANCNumber) {
		$error.="<br /><strong>ANC Number is Missing</strong><br />Kindly provide an ANC Number<br />";
	}
	*/

	//sample collection date
	if((!$collectionDateDay || !$collectionDateMonth || !$collectionDateYear) && !$noCollectionDateSupplied) {
		$error.="<br /><strong>Sample Collection Date Missing</strong><br />Kindly provide the Sample Collection Date<br />";
	}

	//treatment initiation date
	if((!$treatmentInitiationDateDay || !$treatmentInitiationDateMonth || !$treatmentInitiationDateYear) && !$noTreatmentInitiationDateSupplied) {
		$error.="<br /><strong>Treatment Initiation Date Missing</strong><br />Kindly provide the Treatment Initiation Date<br />";
	}

	//is gender male and pregnancy set to yes?
	if($gender=="Male" && $pregnant=="Yes") {
		$error.="<br /><strong>Possible Error</strong><br /> Gender has been supplied as Male however Patient has also been reported as being Pregnant.<br />";
	}

	//is gender male and breastfeeding set to yes?
	if($gender=="Male" && $breastfeeding=="Yes") {
		$error.="<br /><strong>Possible Error</strong><br /> Gender has been supplied as Male however Patient has also been reported as Breastfeeding.<br />";
	}

	//is gender male and pregnancy set to yes?
	if($gender=="Male" && $treatmentInitiationID==getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix='PMTCT/Option B+' limit 1","id")) {
		$error.="<br /><strong>Possible Error</strong><br /> Gender has been supplied as Male however Patient has also been reported with Treatment Initiation as PMTCT/Option B+.<br />";
	}

	//concatenations
	if($collectionDateYear && $collectionDateMonth && $collectionDateDay) {
		$collectionDate=0;
		$collectionDate="$collectionDateYear-$collectionDateMonth-$collectionDateDay";
		//ensure collection date is not greater than the current date
		if(getStandardDateDifference($datetime,$collectionDate)<0) {
			$error.="<br /><strong>Sample Collection Date ".getFormattedDateLessDay($collectionDate)." should not be in the future.</strong><br />Kindly provide an alternative Sample Collection Date<br />";
		}
	}

	if($treatmentInitiationDateYear && $treatmentInitiationDateMonth && $treatmentInitiationDateDay) {
		$treatmentInitiationDate=0;
		$treatmentInitiationDate="$treatmentInitiationDateYear-$treatmentInitiationDateMonth-$treatmentInitiationDateDay";
		//ensure treatment initiation date is not greater than the current date
		if(getStandardDateDifference($datetime,$treatmentInitiationDate)<0) {
			$error.="<br /><strong>Treatment Initiation Date ".getFormattedDateLessDay($treatmentInitiationDate)." should not be in the future.</strong><br />Kindly provide an alternative Treatment Initiation Date<br />";
		}
		//ensure treatment initiation date is not before 01-01-1990
		if(getStandardDateDifference($treatmentInitiationDate,"1990-01-01")<0) {
			$error.="<br /><strong>Treatment Initiation Date ".getFormattedDateLessDay($treatmentInitiationDate)." should not be prior to 01/Jan/1990.</strong><br />Kindly provide an alternative Treatment Initiation Date<br />";
		}
	}
	
	if($receiptDateYear && $receiptDateMonth && $receiptDateDay) {
		$receiptDate=0;
		$receiptDate="$receiptDateYear-$receiptDateMonth-$receiptDateDay";
		//ensure received at CPHL date is not greater than the current date
		if(getStandardDateDifference($datetime,$receiptDate)<0) {
			$error.="<br /><strong>Received at CPHL Date ".getFormattedDateLessDay($receiptDate)." should not be in the future.</strong><br />Kindly provide an alternative Date<br />";
		}
		//ensure received at CPHL date is not before 01-05-2014
		if(getStandardDateDifference($receiptDate,"2014-05-01")<0) {
			$error.="<br /><strong>Received at CPHL Date ".getFormattedDateLessDay($receiptDate)." should not be prior to 01/May/2014.</strong><br />Kindly provide an alternative Date<br />";
		}
		//ensure collection date is not greater than the received at CPHL date
		if($collectionDateYear && $collectionDateMonth && $collectionDateDay && getStandardDateDifference($receiptDate,$collectionDate)<0) {
			$error.="<br /><strong>Sample Collection Date ".getFormattedDateLessDay($collectionDate)." should not be greater than the Received at CPHL Date ".getFormattedDateLessDay($receiptDate).".</strong><br />Kindly provide an alternative Sample Collection Date<br />";
		}
	}

	//ensure facility is active
	if(!getDetailedTableInfo2("vl_facilities","id='$facilityID' limit 1","active")) {
		$error.="<br /><strong>Facility '".getDetailedTableInfo2("vl_facilities","id='$facilityID' limit 1","facility")."' is not active.</strong><br />Please select an alternative active facility or contact your administrator about activating this facility.<br />";
	}

	/*
	* split $lrEnvelopeNumber based on -, then validate based on the envelope number
	* 0001 >= plasma <= 0500
	* 0501 >= DBS 
	*/
	$lrEnvelopeNumberArray=array();
	$lrEnvelopeNumberArray=explode("-",$lrEnvelopeNumber);
	$envelopeNumber=0;
	$envelopeNumber=$lrEnvelopeNumberArray[1]/1;
	if($sampleTypeID==getDetailedTableInfo2("vl_appendix_sampletype","appendix like '%plasma%' limit 1","id") && $envelopeNumber>500) {
		$error.="<br /><strong>Incorrect Envelope Number $lrEnvelopeNumber for a Plasma Type Sample.</strong><br />The valid range for plasma type samples is $lrEnvelopeNumberArray[0]-0001 to $lrEnvelopeNumberArray[0]-0500.<br />";
	} elseif($sampleTypeID==getDetailedTableInfo2("vl_appendix_sampletype","appendix like '%DBS%' limit 1","id") && $envelopeNumber<=500) {
		$error.="<br /><strong>Incorrect Envelope Number $lrEnvelopeNumber for a DBS Type Sample.</strong><br />The valid range for DBS type samples is $lrEnvelopeNumberArray[0]-0501 and above.<br />";
	}

	//possible contradicting gender
	$uniqueID=0;
	if($artNumber || $otherID) {

		$artNumberModified=0;
		$artNumberModified=$artNumber;
		//clean art number by removing - . / and spaces
		$artNumberModified=preg_replace("/\-/s","",$artNumberModified);
		$artNumberModified=preg_replace("/\./s","",$artNumberModified);
		$artNumberModified=preg_replace("/\//s","",$artNumberModified);
		$artNumberModified=preg_replace("/\s/s","",$artNumberModified);
		
		$uniqueID=$facilityID."-".($artNumber?"A-$artNumberModified":"O-$otherID");
	}

	$mostRecentGender=0;
	$mostRecentGender=getDetailedTableInfo2("vl_patients","uniqueID='$uniqueID' and (artNumber='$artNumber' or otherID='$otherID') order by created desc limit 1","gender");
	$mostRecentGenderCreated=0;
	$mostRecentGenderCreated=getDetailedTableInfo2("vl_patients","uniqueID='$uniqueID' and (artNumber='$artNumber' or otherID='$otherID') order by created desc limit 1","created");
	$mostRecentGenderCreatedBy=0;
	$mostRecentGenderCreatedBy=getDetailedTableInfo2("vl_patients","uniqueID='$uniqueID' and (artNumber='$artNumber' or otherID='$otherID') order by created desc limit 1","createdby");
	if($mostRecentGender && 
		(($mostRecentGender=="Female" && $gender=="Male") || 
			($mostRecentGender=="Male" && $gender=="Female")) && 
				!$proceedWithWarningGender) {
		$warnings.="<div style=\"padding: 10px 0px 0px 0px\">Possible Data Contradiction!</div>
					<div style=\"padding: 5px 0px 0px 0px\" class=\"vls_grey\">The patient with ".($artNumber?"ART <strong>$artNumber</strong>":"").($artNumber && $otherID?", ":"").($otherID?"Other ID <strong>$otherID</strong>":"")." created on <strong>".getFormattedDate($mostRecentGenderCreated)."</strong> by <strong>$mostRecentGenderCreatedBy</strong> was last created with the gender <strong>$mostRecentGender</strong>.</div>
					<div style=\"padding: 5px 0px 0px 0px\" class=\"vls_grey\">You are however currently submitting this patient with the gender <strong>$gender</strong>. If the gender you have supplied is accurate, click \"Proceed Anyway\" otherwise, change the gender to <strong>$mostRecentGender</strong> then click \"Save Samples\" to proceed.</div>
					<div style=\"padding: 10px 0px 10px 0px\" class=\"trailanalyticss_grey\"><input type=\"submit\" name=\"proceedWithWarningGender\" class=\"button\" value=\"   Proceed Anyway   \" /></div>";
	}

	//If the same ART or Other ID # has been received from the same facility, but with different form numbers and within 5 months of each other, raise a warning
	$mostRecentPatientID=0;
	$mostRecentPatientID=getDetailedTableInfo2("vl_patients","uniqueID='$uniqueID' and (artNumber='$artNumber' or otherID='$otherID') order by created desc limit 1","id");
	$mostRecentPatientSampleID=0;
	$mostRecentPatientSampleID=getDetailedTableInfo2("vl_samples","patientID='$mostRecentPatientID' and (vlSampleID in (select sampleID from vl_results_abbott) or vlSampleID in (select SampleID from vl_results_roche)) order by created desc limit 1","vlSampleID");
	$mostRecentPatientIDTested=0;
	$mostRecentPatientIDTested=getDetailedTableInfo2("vl_results_abbott","sampleID='$mostRecentPatientSampleID' order by created desc limit 1","created");
	if(!$mostRecentPatientIDTested) {
		$mostRecentPatientIDTested=getDetailedTableInfo2("vl_results_roche","SampleID='$mostRecentPatientSampleID' order by created desc limit 1","created");
	}
	if($mostRecentPatientSampleID && 
		$mostRecentPatientIDTested && 
			getStandardDateDifference($datetime,$mostRecentPatientIDTested)<($default_viralLoadRepeatTestWindow*30.5) && 
				!$proceedWithWarningVLRepeatTesting) {


		$last_result=getDetailedTableInfo2("vl_results_merged","vlSampleID='$mostRecentPatientSampleID' order by created desc limit 1","resultAlphanumeric");
		$last_collection_date=getDetailedTableInfo2("vl_samples","vlSampleID='$mostRecentPatientSampleID' order by created desc limit 1","collectionDate");

		$warnings.="<div style=\"padding: 10px 0px 0px 0px\">Possible Repeat Viral Load Test occuring within $default_viralLoadRepeatTestWindow months!</div>
					<div style=\"padding: 5px 0px 0px 0px\" class=\"vls_grey\">The patient with ".($artNumber?"ART <strong>$artNumber</strong>":"").($artNumber && $otherID?", ":"").($otherID?"Other ID <strong>$otherID</strong>":"")." was last tested on <strong>".getFormattedDate($mostRecentPatientIDTested)."</strong> i.e ".round(getStandardDateDifference($datetime,$mostRecentPatientIDTested)/30.5)." month(s) back (less than $default_viralLoadRepeatTestWindow months ago).</div>
					
					<div style=\"padding: 5px 0px 0px 0px\" class=\"vls_grey\"> Last Sample Reference #: $mostRecentPatientSampleID</div>
					<div style=\"padding: 5px 0px 0px 0px\" class=\"vls_grey\"> Last Collection Date:".getFormattedDate($last_collection_date)."</div>
					<div style=\"padding: 5px 0px 0px 0px\" class=\"vls_grey\"> Last Result: $last_result</div>
					<div style=\"padding: 5px 0px 0px 0px\" class=\"vls_grey\">Repeat Viral Load tests are carried out at intervals of $default_viralLoadRepeatTestWindow months or more. If this is an authentic repeat Viral Load test, then click \"Proceed Anyway\" otherwise, input a different patient then click \"Save Samples\" to proceed.</div>
					<div style=\"padding: 10px 0px 10px 0px\" class=\"trailanalyticss_grey\"><input type=\"submit\" name=\"proceedWithWarningVLRepeatTesting\" class=\"button\" value=\"   Proceed Anyway   \" /></div>";
	}

	//If the facilityID is different from that of the facility this form was dispatched to, inform the user
	//reference number based on form number
	$refNumberByFormNumber=0;
	$refNumberByFormNumber=getDetailedTableInfo2("vl_forms_clinicalrequest","formNumber='$formNumber' limit 1","refNumber");
	//facility ID based on form number
	$facilityIDByFormNumber=0;
	$facilityIDByFormNumber=getDetailedTableInfo2("vl_forms_clinicalrequest_dispatch","refNumber='$refNumberByFormNumber' limit 1","facilityID");
	if($facilityIDByFormNumber && $facilityIDByFormNumber!=$facilityID && !$proceedWithWarningAlternativeFacilities) {
		$warnings.="<div style=\"padding: 10px 0px 0px 0px\">Possible incorrect Facility!</div>
					<div style=\"padding: 5px 0px 0px 0px\" class=\"vls_grey\">The form with number <strong>$formNumber</strong> was originally dispatched to facility <strong>".getDetailedTableInfo2("vl_facilities","id='$facilityIDByFormNumber' limit 1","facility")."</strong></div>
					<div style=\"padding: 5px 0px 0px 0px\" class=\"vls_grey\">Your submission indicates it's being returned from another facility <strong>".getDetailedTableInfo2("vl_facilities","id='$facilityID' limit 1","facility")."</strong>. If this is the case, then click \"Proceed Anyway\" otherwise, input amend the facility then click \"Save Samples\" to proceed.</div>
					<div style=\"padding: 10px 0px 10px 0px\" class=\"trailanalyticss_grey\"><input type=\"submit\" name=\"proceedWithWarningAlternativeFacilities\" class=\"button\" value=\"   Proceed Anyway   \" /></div>";
	}

	//input data
	if(!$error && !$warnings) {
		//concatenations
		$uniqueID=0;
		if($artNumber || $otherID) {
			$artNumberModified=0;
			$artNumberModified=$artNumber;
			//clean art number by removing - . / and spaces
			$artNumberModified=preg_replace("/\-/s","",$artNumberModified);
			$artNumberModified=preg_replace("/\./s","",$artNumberModified);
			$artNumberModified=preg_replace("/\//s","",$artNumberModified);
			$artNumberModified=preg_replace("/\s/s","",$artNumberModified);
			
			$uniqueID=$facilityID."-".($artNumber?"A-$artNumberModified":"O-$otherID");
		}

		//log patient, if unique
		$patientID=0;
		if(!getDetailedTableInfo2("vl_patients","uniqueID='$uniqueID' and artNumber='$artNumber' and otherID='$otherID' limit 1","id")) {
			mysqlquery("insert into vl_patients 
							(uniqueID,artNumber,otherID,gender,".(!$noDateOfBirthSupplied?"dateOfBirth,":"")."created,createdby) 
							values 
							('$uniqueID','$artNumber','$otherID','$gender',".(!$noDateOfBirthSupplied?"'$dateOfBirth',":"")."'$datetime','$trailSessionUser')");
			if(mysqlerror())
				die("1: ".mysqlerror());
			$patientID=getDetailedTableInfo2("vl_patients","uniqueID='$uniqueID' and (artNumber='$artNumber' or otherID='$otherID') limit 1","id");
		} else {
			$patientID=getDetailedTableInfo2("vl_patients","uniqueID='$uniqueID' and (artNumber='$artNumber' or otherID='$otherID') limit 1","id");
			//log changes to the patient's gender
			if($proceedWithWarningGender) {
				//log updates
				logTableChange("vl_patients","gender",$patientID,getDetailedTableInfo2("vl_patients","id='$patientID'","gender"),$gender);
				$genderChangeLogID=0;
				$genderChangeLogID=getDetailedTableInfo2("vl_logs_tables","createdby='$trailSessionUser' order by id desc limit 1","id");
				if(!$noDateOfBirthSupplied) { 
					logTableChange("vl_patients","dateOfBirth",$patientID,getDetailedTableInfo2("vl_patients","id='$patientID'","dateOfBirth"),$dateOfBirth); 
					$dateOfBirthChangeLogID=0;
					$dateOfBirthChangeLogID=getDetailedTableInfo2("vl_logs_tables","createdby='$trailSessionUser' order by id desc limit 1","id");
				}

				//update patients database
				mysqlquery("update vl_patients set 
								".(!$noDateOfBirthSupplied?"dateOfBirth='$dateOfBirth', ":"dateOfBirth='', ")."
								gender='$gender' 
								where 
								id='$patientID'");

				//log the change of gender warning
				mysqlquery("insert into vl_logs_warnings 
							(logCategory,logDetails,logTableID,created,createdby) 
							values 
							('changedPatientsGender','Gender changed from $mostRecentGender to $gender for Patient with ".($artNumber?"ART $artNumber":"").($artNumber && $otherID?", ":"").($otherID?"Other ID $otherID":"")." from Facility ".getDetailedTableInfo2("vl_facilities","id='$facilityID' limit 1","facility").".','$genderChangeLogID','$datetime','$trailSessionUser')");

				//log the change of date of birth warning
				/*
				if(!$noDateOfBirthSupplied) { 
					mysqlquery("insert into vl_logs_warnings 
								(logCategory,logDetails,logTableID,created,createdby) 
								values 
								('changedPatientsDateOfBirth','Date of Birth changed from ".getDetailedTableInfo2("vl_patients","uniqueID='$uniqueID' and (artNumber='$artNumber' or otherID='$otherID') order by created desc limit 1","dateOfBirth")." to $dateOfBirth for Patient with ".($artNumber?"ART $artNumber":"").($artNumber && $otherID?", ":"").($otherID?"Other ID $otherID":"")." from Facility ".getDetailedTableInfo2("vl_facilities","id='$facilityID' limit 1","facility").".','$dateOfBirthChangeLogID','$datetime','$trailSessionUser')");
				}
				*/
			}
		}

		//log the repeated viral load test
		if($proceedWithWarningVLRepeatTesting) {
			//log the change of gender warning
			mysqlquery("insert into vl_logs_warnings 
						(logCategory,logDetails,logTableID,created,createdby) 
						values 
						('capturedRepeatVLTest','Captured a repeat VL Test after ".round(getStandardDateDifference($datetime,$mostRecentPatientIDTested)/30.5)." month(s) or ".getStandardDateDifference($datetime,$mostRecentPatientIDTested)." day(s) instead of the recommended $default_viralLoadRepeatTestWindow month(s) for Patient with ".($artNumber?"ART $artNumber":"").($artNumber && $otherID?", ":"").($otherID?"Other ID $otherID":"")." from Facility ".getDetailedTableInfo2("vl_facilities","id='$facilityID' limit 1","facility").".','','$datetime','$trailSessionUser')");
		}

		//log the alternative facility captured
		if($proceedWithWarningAlternativeFacilities) {
			//log the change of gender warning
			mysqlquery("insert into vl_logs_warnings 
						(logCategory,logDetails,logTableID,created,createdby) 
						values 
						('capturedAlternativeFacilityForFormNumber','Form Number $formNumber dispatched to Facility ".getDetailedTableInfo2("vl_facilities","id='$facilityID' limit 1","facility")." but received from Facility ".getDetailedTableInfo2("vl_facilities","id='$facilityIDByFormNumber' limit 1","facility").".','','$datetime','$trailSessionUser')");
		}

		//log patient phone number, if unique
		if($patientPhone && !getDetailedTableInfo2("vl_patients_phone","patientID='$patientID' and phone='$patientPhone' limit 1","id")) {
			mysqlquery("insert into vl_patients_phone 
							(patientID,phone,created,createdby) 
							values 
							('$patientID','$patientPhone','$datetime','$trailSessionUser')");
			if(mysqlerror())
				die("2: ".mysqlerror());
		}

		//log patient samples, if unique
		if(!getDetailedTableInfo2("vl_samples","patientID='$patientID' and formNumber='$formNumber' limit 1","id")) {
			//unique sample number
			$vlSampleID=0;
			$vlSampleID=generateSampleNumber();
			
			mysqlquery("insert into vl_samples 
							(patientID,
								patientUniqueID,vlSampleID,formNumber,districtID,
								hubID,facilityID,currentRegimenID,pregnant,
								pregnantANCNumber,breastfeeding,activeTBStatus,collectionDate,
								receiptDate,treatmentLast6Months,treatmentInitiationDate,sampleTypeID,
								viralLoadTestingID,treatmentInitiationID,treatmentInitiationOther,treatmentStatusID,
								reasonForFailureID,tbTreatmentPhaseID,arvAdherenceID,
								
								vlTestingRoutineMonitoring,
								routineMonitoringLastVLDate,
								routineMonitoringValue,
								routineMonitoringSampleTypeID,
								
								vlTestingRepeatTesting,
								repeatVLTestLastVLDate,
								repeatVLTestValue,
								repeatVLTestSampleTypeID,
								
								vlTestingSuspectedTreatmentFailure,
								suspectedTreatmentFailureLastVLDate,
								suspectedTreatmentFailureValue,
								suspectedTreatmentFailureSampleTypeID,
								
								lrCategory,lrEnvelopeNumber,lrNumericID,
								
								created,createdby) 
							values 
							('$patientID',
								'$uniqueID','$vlSampleID','$formNumber','$districtID',
								'$hubID','$facilityID','$currentRegimenID','$pregnant',
								'$pregnantANCNumber','$breastfeeding','$activeTBStatus','$collectionDate',
								'$receiptDate','$treatmentLast6Months','$treatmentInitiationDate','$sampleTypeID',
								'$viralLoadTestingID','$treatmentInitiationID','$treatmentInitiationOther','$treatmentStatusID',
								'$reasonForFailureID','$tbTreatmentPhaseID','$arvAdherenceID',
								
								'".($viralLoadTestingIndication=="vlTestingRoutineMonitoring"?1:0)."',
								'".($viralLoadTestingIndication=="vlTestingRoutineMonitoring"?$routineMonitoringLastVLDate:"")."',
								'".($viralLoadTestingIndication=="vlTestingRoutineMonitoring"?$routineMonitoringValue:"")."',
								'".($viralLoadTestingIndication=="vlTestingRoutineMonitoring"?$routineMonitoringSampleTypeID:"")."',
								
								'".($viralLoadTestingIndication=="vlTestingRepeatTesting"?1:0)."',
								'".($viralLoadTestingIndication=="vlTestingRepeatTesting"?$repeatVLTestLastVLDate:"")."',
								'".($viralLoadTestingIndication=="vlTestingRepeatTesting"?$repeatVLTestValue:"")."',
								'".($viralLoadTestingIndication=="vlTestingRepeatTesting"?$repeatVLTestSampleTypeID:"")."',
								
								'".($viralLoadTestingIndication=="vlTestingSuspectedTreatmentFailure"?1:0)."',
								'".($viralLoadTestingIndication=="vlTestingSuspectedTreatmentFailure"?$suspectedTreatmentFailureLastVLDate:"")."',
								'".($viralLoadTestingIndication=="vlTestingSuspectedTreatmentFailure"?$suspectedTreatmentFailureValue:"")."',
								'".($viralLoadTestingIndication=="vlTestingSuspectedTreatmentFailure"?$suspectedTreatmentFailureSampleTypeID:"")."',
								
								'$lrCategory','$lrEnvelopeNumber','$lrNumericID',
								
								'$datetime','$trailSessionUser')");
			
			if(mysqlerror())
				die("3: ".mysqlerror());
			
			//get latest sample ID, log $otherRegimen if any
			if($otherRegimen) {
				$latestSampleID=0;
				$latestSampleID=getDetailedTableInfo2("vl_samples","createdby='$trailSessionUser' order by created desc limit 1","id");
				//log other regimen
				logSampleOtherRegimen($latestSampleID,$currentRegimenID,$otherRegimen);
			} else {
				logDataRemoval("delete from vl_samples_otherregimen where sampleID='$latestSampleID'");
				mysqlquery("delete from vl_samples_otherregimen where sampleID='$latestSampleID'");
			}

			//review logs and fix any duplicates
			fixDuplicateSampleIDs();
			
			//redirect accordingly
			go("/samples/success/".vlEncrypt(getDetailedTableInfo2("vl_samples","createdby='$trailSessionUser' order by created desc limit 1","vlSampleID"))."/");
		} else {
			$error.="<br /><strong>Duplicate Data Entry</strong><br /> Patient with ART Number <strong>$artNumber</strong> from <strong>".getDetailedTableInfo2("vl_facilities","id='$facilityID' limit 1","facility")."</strong> has already been entered with Form Number <strong>$formNumber</strong>.<br /> Kindly input this record with an alternative Form or ART Number.<br />";
		}
	}
}
?>
<script Language="JavaScript" Type="text/javascript">
<!--
function validate(samples) {
	//check for missing information
	if(!document.samples.lrCategory.value) {
		alert('Missing Mandatory Field: Location/Rejection ID');
		document.samples.lrCategory.focus();
		return (false);
	}
	if(!document.samples.lrEnvelopeNumber.value || document.samples.lrEnvelopeNumber.value=='<?=$default_envelopeNumber?>') {
		alert('Missing Mandatory Field: Location/Rejection ID');
		document.samples.lrEnvelopeNumber.focus();
		return (false);
	}
	if(!document.samples.lrNumericID.value) {
		alert('Missing Mandatory Field: Location/Rejection ID');
		document.samples.lrNumericID.focus();
		return (false);
	}
	if(!document.samples.formNumber.value) {
		alert('Missing Mandatory Field: Form Number');
		document.samples.formNumber.focus();
		return (false);
	}
	if(!document.samples.facilityID.value) {
		alert('Missing Mandatory Field: Facility Name');
		document.samples.facilityID.focus();
		return (false);
	}
	if(!document.samples.artNumber.value && !document.samples.otherID.value) {
		alert('Missing Mandatory Field: ART Number');
		document.samples.artNumber.focus();
		return (false);
	}
	if(!document.samples.gender.value) {
		alert('Missing Mandatory Field: Gender');
		document.samples.gender.focus();
		return (false);
	}
	/*
	if((!document.samples.dateOfBirthDay.value || !document.samples.dateOfBirthMonth.value || !document.samples.dateOfBirthYear.value) && (!document.samples.dateOfBirthAge.value || !document.samples.dateOfBirthIn.value)) {
		alert('Missing Mandatory Field: Date of Birth or Patient Age');
		document.samples.dateOfBirthDay.focus();
		return (false);
	}
	*/
	if(!document.samples.collectionDateDay.value || !document.samples.collectionDateMonth.value || !document.samples.collectionDateYear.value) {
		alert('Missing Mandatory Field: Sample Collection Date');
		document.samples.collectionDateDay.focus();
		return (false);
	}
	if(!document.samples.sampleTypeID.value) {
		alert('Missing Mandatory Field: Sample Type');
		document.samples.sampleTypeID.focus();
		return (false);
	}
	if(!document.samples.currentRegimenID.value) {
		alert('Missing Mandatory Field: Current Regimen');
		document.samples.currentRegimenID.focus();
		return (false);
	}
	if(!document.samples.treatmentStatusID.value) {
		alert('Missing Mandatory Field: Patient Treatment Line');
		document.samples.treatmentStatusID.focus();
		return (false);
	}
	if(!document.samples.viralLoadTestingID.value) {
		alert('Missing Mandatory Field: Viral Load Testing');
		document.samples.viralLoadTestingID.focus();
		return (false);
	}
	if(!document.samples.pregnant.value) {
		alert('Missing Mandatory Field: Pregnancy Status');
		document.samples.pregnant.focus();
		return (false);
	}
	/*
	if(document.samples.pregnant.value=="Yes" && !document.samples.pregnantANCNumber.value) {
		alert('Missing Mandatory Field: Pregnancy Status Supplied but no ANC Number');
		document.samples.pregnantANCNumber.focus();
		return (false);
	}
	*/
	if(!document.samples.breastfeeding.value) {
		alert('Missing Mandatory Field: Breastfeeding Status');
		document.samples.breastfeeding.focus();
		return (false);
	}
	if(!document.samples.viralLoadTestingID.value) {
		alert('Missing Mandatory Field: Viral Load Testing');
		document.samples.viralLoadTestingID.focus();
		return (false);
	}
	if(!document.samples.treatmentInitiationDateDay.value || !document.samples.treatmentInitiationDateMonth.value || !document.samples.treatmentInitiationDateYear.value) {
		alert('Missing Mandatory Field: Treatment Initiation Date');
		document.samples.treatmentInitiationDateDay.focus();
		return (false);
	}
	if(!document.samples.treatmentLast6Months.value) {
		alert('Missing Mandatory Field: Whether Patient has been on Treatment for last 6 months');
		document.samples.treatmentLast6Months.focus();
		return (false);
	}
	//logical
	if(document.samples.gender.value=="Male" && document.samples.pregnant.value=="Yes") {
		alert('Possible Error: Gender is indicated as Male, Patient should not be reported as Pregnant.');
		return (false);
	}
	if(document.samples.gender.value=="Male" && document.samples.breastfeeding.value=="Yes") {
		alert('Possible Error: Gender is indicated as Male, Patient should not be reported as Breastfeeding.');
		return (false);
	}
	if(document.samples.gender.value=="Male" && document.samples.treatmentInitiationID.value=="<?=getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix='PMTCT/Option B+' limit 1","id")?>") {
		alert('Possible Error: Gender is indicated as Male, Patient should not be reported with Treatment Initiation as PMTCT/Option B+.');
		return (false);
	}
	return (true);
}

function checkGender(theField) {
	if(theField.value=="Male") {
		document.samples.pregnant.value="No";
		checkPregnancy(document.samples.pregnant);
		document.samples.breastfeeding.value="No";
	} else {
		document.samples.pregnant.value="";
		checkPregnancy(document.samples.pregnant);
		document.samples.breastfeeding.value="";
	}
}

function checkOptions(theField) {
	if(theField.value=='<?=getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix like '%other%' limit 1","id")?>') {
		loadInput('treatmentInitiationOther','treatmentInitiationIDTD','');
	} else {
		document.getElementById("treatmentInitiationIDTD").innerHTML="";
	}
}

function checkPregnancy(theField) {
	if(theField.value=='Yes') {
		document.getElementById("pregnancyTextID").innerHTML="ANC Number";
		loadInput('pregnantANCNumber','pregnancyID','');
	} else {
		document.getElementById("pregnancyTextID").innerHTML="";
		document.getElementById("pregnancyID").innerHTML="";
	}
}


function checkForHubDistrict(){
	//facilityID
	var theFacilityID=document.samples.facilityID.value;
	
	document.getElementById("checkHubDistrictID").innerHTML=" ";
	//get hub
	vlDC_XloadHub('samples','checkHubDistrictID','hubID','hubTextID',theFacilityID);
	//get district
	vlDC_XloadDistrict('samples','checkHubDistrictID','districtID','districtTextID',theFacilityID);
}

function checkMonthDay(theField) {
	if(theField.value && !document.samples.dateOfBirthMonth.value && !document.samples.dateOfBirthDay.value) {
		//default to first day/month
		document.samples.dateOfBirthDay.value="01";
		document.samples.dateOfBirthMonth.value="01"
	}
}

function selectVLTesting(theField) {
	//auto select viralLoadTestingIndication, options are vlTestingRoutineMonitoring, vlTestingRepeatTesting, vlTestingSuspectedTreatmentFailure
	if(theField.value==<?=getDetailedTableInfo2("vl_appendix_viralloadtesting","appendix like '%Routine monitoring%' limit 1","id")?>) {
		document.getElementById("vlTestingRoutineMonitoring").checked = true;
	} else if(theField.value==<?=getDetailedTableInfo2("vl_appendix_viralloadtesting","appendix like '%Repeat viral load%' limit 1","id")?>) {
		document.getElementById("vlTestingRepeatTesting").checked = true;
	} else if(theField.value==<?=getDetailedTableInfo2("vl_appendix_viralloadtesting","appendix like '%Suspected treatment failure%' limit 1","id")?>) {
		document.getElementById("vlTestingSuspectedTreatmentFailure").checked = true;
	} else if(theField.value==<?=getDetailedTableInfo2("vl_appendix_viralloadtesting","appendix like '%Left Blank%' limit 1","id")?>) {
		document.getElementById("vlTestingRoutineMonitoring").checked = false;
		document.getElementById("vlTestingRepeatTesting").checked = false;
		document.getElementById("vlTestingSuspectedTreatmentFailure").checked = false;
	}
}

function validateEnvelopeNumber(field) {
	var clear=true;
	var val = field.value;
	var valueLength = val.length;
	//check position of the dash
	if(valueLength>4) {
		var key4=val.charAt(4);
		if(key4!="-") {
			//display message
			document.getElementById('envelopeInfoID').style.display='inline';
		} else {
			//hide
			document.getElementById('envelopeInfoID').style.display='none';
		}
	} else {
		//hide
		document.getElementById('envelopeInfoID').style.display='none';
	}
	//only numeric and - entries
	for(var i=0;i<valueLength;++i) {
		var new_key=val.charAt(i);
		if(((new_key<"0") || (new_key>"9")) && !(new_key=="-")) {
			clear=false;
			break;
		}
	}
	//effect the clearing
	if(!clear) {
		var submission = val.substring(0,(valueLength-1));
		field.value=submission;
	}
}

function matchRegimenTreatmentLine(theField) {
	switch(theField.value) {
		<?
		$query=0;
		$query=mysqlquery("select * from vl_appendix_regimen order by position");
		if(mysqlnumrows($query)) {
			while($q=mysqlfetcharray($query)) {
				$varTreatmentStatus=0;
				$varTreatmentStatus=getDetailedTableInfo2("vl_appendix_treatmentstatus","id='$q[treatmentStatusID]' limit 1","appendix");
				if(!$varTreatmentStatus) {
					$varTreatmentStatus="No Patient Treatment Line Available";
				}
				echo "
				case \"$q[id]\":
					document.getElementById(\"treatmentStatusTextID\").innerHTML=\"$varTreatmentStatus\";
					document.samples.treatmentStatusID.value=\"$q[treatmentStatusID]\";
				break;
				";
			}
		}
		?>
	}
}

function captureOtherRegimen(theField) {
	if(theField.value=='<?=getDetailedTableInfo2("vl_appendix_regimen","appendix like '%Other%' limit 1","id")?>') {
		loadInput('otherRegimen','otherRegimenID','');
	} else {
		document.getElementById("otherRegimenID").innerHTML="";
	}
}

function loadArtHistory(artObject,facilityID) {
	//load history
	vlDC_XloadArtHistory(artObject.value,facilityID);
}

function disableEnableDateOfBirth(checkedObject) {
	if(checkedObject.checked==true) {
		//has been checked
		document.samples.dateOfBirthIn.disabled=true;
		document.samples.dateOfBirthAge.disabled=true;
		document.samples.dateOfBirthYear.disabled=true;
		document.samples.dateOfBirthMonth.disabled=true;
		document.samples.dateOfBirthDay.disabled=true;
	} else if(checkedObject.checked==false) {
		//has been unchecked
		document.samples.dateOfBirthIn.disabled=false;
		document.samples.dateOfBirthAge.disabled=false;
		document.samples.dateOfBirthYear.disabled=false;
		document.samples.dateOfBirthMonth.disabled=false;
		document.samples.dateOfBirthDay.disabled=false;
	}
}

function disableEnableTreatmentInitiationDate(checkedObject) {
	if(checkedObject.checked==true) {
		//has been checked
		document.samples.treatmentInitiationDateYear.disabled=true;
		document.samples.treatmentInitiationDateMonth.disabled=true;
		document.samples.treatmentInitiationDateDay.disabled=true;
	} else if(checkedObject.checked==false) {
		//has been unchecked
		document.samples.treatmentInitiationDateYear.disabled=false;
		document.samples.treatmentInitiationDateMonth.disabled=false;
		document.samples.treatmentInitiationDateDay.disabled=false;
	}
}

function disableEnableCollectionDate(checkedObject) {
	if(checkedObject.checked==true) {
		//has been checked
		document.samples.collectionDateYear.disabled=true;
		document.samples.collectionDateMonth.disabled=true;
		document.samples.collectionDateDay.disabled=true;
	} else if(checkedObject.checked==false) {
		//has been unchecked
		document.samples.collectionDateYear.disabled=false;
		document.samples.collectionDateMonth.disabled=false;
		document.samples.collectionDateDay.disabled=false;
	}
}

function loadFacilityFromFormNumber(formNumberObject,formName,fieldID,facilityIDField){
	//get hub
	vlDC_XloadFacilityFromFormName(formNumberObject.value,formName,fieldID,facilityIDField);
	getHubDistrict();
}


//-->
</script>
<!--<form name="samples" method="post" action="/samples/capture/" onsubmit="return validate(this)">-->
<form name="samples" method="post" action="/samples/capture/">
<table width="100%" border="0" class="vl">
          <? if($success) { ?>
            <tr>
                <td class="vl_success">Data Captured Successfully!</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
			<? } elseif($error) { ?>
            <tr>
                <td class="vl_error"><?=$error?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <? 
			} 
			
			if($warnings) { 
			?>
            <tr>
                <td class="vl_warning"><?=$warnings?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <? } ?>
            <tr>
              <td class="toplinks" style="padding:0px 0px 10px 0px"><a class="toplinks" href="/dashboard/">HOME</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/samples/">SAMPLES</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/samples/capture/">Samples&nbsp;Capture</a></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                  <fieldset style="width: 100%">
            <legend><strong>FORM/FACILITY CREDENTIALS</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
						<table width="100%" border="0" class="vl">
                          <tr>
                            <td></td>
                            <td><span id="envelopeInfoID" class="hint-down" style="margin-top: -50px; width: 310px; display:none">Incorrect Envelope # Format. Correct Format is <?=getFormattedDateYearShort($datetime).getFormattedDateMonth($datetime)."-001"?>. <span class="hint-down-pointer"></span></span></td>
                          </tr>
                            <tr>
                              <td width="20%">Location/Rejection&nbsp;ID</td>
                              <td width="80%">
                                <table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                  <tr>
                                    <td><select name="lrCategory" id="lrCategory" class="search" style="height:21px">
                                    		<?
											if($lrCategory) {
												echo "<option value=\"$lrCategory\" selected=\"selected\">$lrCategory</option>
														<option value=\"V\">V</option>
														<option value=\"R\">R</option>";
											} else {
												echo "<option value=\"V\" selected=\"selected\">V</option>
														<option value=\"R\">R</option>";
											}
											?>
                                          </select></td>
                                    <td style="padding:0px 0px 0px 3px"><input type="text" name="lrEnvelopeNumber" id="lrEnvelopeNumber" value="<?=($lrEnvelopeNumber?$lrEnvelopeNumber:$default_envelopeNumber)?>" class="search_pre" size="10" maxlength="10" onkeyup="return validateEnvelopeNumber(this)" onfocus="if(value=='Envelope #') {value=''}" onblur="if(value=='') {value='Envelope #'}" /></td>
                                    <td style="padding:0px 3px">/</td>
                                    <td><input type="text" name="lrNumericID" id="lrNumericID" value="<?=$lrNumericID?>" class="search_pre" size="1" maxlength="3" onkeyup="return isNumber(this,'0')" /></td>
                                    </tr>
                                </table>
                            </td>
                            </tr>
                            <tr>
                              <td>Form&nbsp;#&nbsp;<font class="vl_red">*</font></td>
                              <td><input type="text" name="formNumber" id="formNumber" value="<?=$formNumber?>" size="15" maxlength="50" onchange="fetchFacilityID(this.value)" /></td>
                            </tr>
                        <tr>
                          <td>Facility&nbsp;Name&nbsp;<font class="vl_red">*</font></td>
                          <td>
                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td width="20%" id="facilityIDField">                                 
                                            <?
                                            $query=0;
                                            $query=mysqlquery("select f.*,d.district,h.hub from vl_facilities AS f 
                                            				  left join vl_districts AS d on f.districtID=d.id 
                                            				  left join vl_hubs AS h on f.hubID=h.id
                                            				  where facility!='' order by facility");
                                            $facilities_arr=array();
                                            $facilities_arr2=array();
                                            if(mysqlnumrows($query)) {
                                                while($q=mysqlfetcharray($query)) {                                          
                                                    $facilities_arr[$q['id']]=array('district'=>$q['district'],'hub'=>$q['hub'],'hubID'=>$q['hubID'],'districtID'=>$q['districtID']);
                                                    $facilities_arr2[$q['id']]=$q['facility'];
                                                }
                                            }
                                            ?>
                       
                                        <?=MyHTML::select('facilityID',"",$facilities_arr2,"select facility",array("id"=>"fclty","onchange"=>"getHubDistrict(),loadArtHistory(document.samples.artNumber,document.samples.facilityID.value)"))?>
                                        <script>
                                           /* var z = dhtmlXComboFromSelect("facilityID");
                                            z.enableFilteringMode(true);*/
                                            var facilities_json=<?php echo json_encode($facilities_arr) ?>;

                                            function getHubDistrict(){
                                            	var theFacilityID=document.samples.facilityID.value;
                                            	if(!theFacilityID) theFacilityID=$("#facilityID option:selected").val();
                                            	//console.log("attempting to get ...");
                                            	var f_obj=facilities_json[theFacilityID];
                                            	$("#hubTextID").html(f_obj.hub);
                                            	$("#districtTextID").html(f_obj.district);
                                            	$("#hubID").val(f_obj.hubID);
                                            	$("#districtID").val(f_obj.districtID);
                                            }

                                        </script>
                                    </td>
                                    <td width="80%" style="padding:0px 0px 0px 10px" id="checkHubDistrictID">&nbsp;</td>
                                  </tr>
                                </table>
                          </td>
                        </tr>
                        <tr>
                          <td>Hub</td>
                          <td>
                          	<div class="vls_grey" style="padding:5px 0px" id="hubTextID">
                          		<?=($hubID?getDetailedTableInfo2("vl_hubs","id='$hubID' limit 1","hub"):"Input Form Number or Select Facility, First")?>
                          	</div>
                          	<input type="hidden" name="hubID" id="hubID" value="<?=($hubID?$hubID:"")?>" />                      
                         
                          </td>
                        </tr>

                        <tr>
                          <td>District</td>
                          <td>
                          	<div class="vls_grey" style="padding:5px 0px" id="districtTextID">
                          		<?=($districtID?getDetailedTableInfo2("vl_districts","id='$districtID' limit 1","district"):"Input Form Number or Select Facility, First")?>
                          	</div>
                          	<input type="hidden" name="districtID" id="districtID" value="<?=($districtID?$districtID:"")?>" />                         
                          </td>
                        </tr>
                      </table>
                        </div>
                  </fieldset>
                </td>
            </tr>
            <tr>
                <td>
                  <fieldset style="width: 100%">
            <legend><strong>SAMPLE DETAILS</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
						<table width="100%" border="0" class="vl">
                            <tr>
							<td width="20%">Collection&nbsp;Date&nbsp;<font class="vl_red">*</font></td>
                              <td width="80%">
								<table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td><select name="collectionDateDay" id="collectionDateDay" class="search" <?=($noCollectionDateSupplied?"disabled=\"disabled\"":"")?>>
                                          <?
											if($collectionDate) {
												echo "<option value=\"".getFormattedDateDay($collectionDate)."\" selected=\"selected\">".getFormattedDateDay($collectionDate)."</option>";
											} else {
												echo "<option value=\"".getFormattedDateDay($datetime)."\" selected=\"selected\">".getFormattedDateDay($datetime)."</option>";
											}
											for($j=1;$j<=31;$j++) {
                                                echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
                                            }
                                            ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="collectionDateMonth" id="collectionDateMonth" class="search" <?=($noCollectionDateSupplied?"disabled=\"disabled\"":"")?>>
                                          <? 
											if($collectionDate) {
												echo "<option value=\"".getFormattedDateMonth($collectionDate)."\" selected=\"selected\">".getFormattedDateMonthname($collectionDate)."</option>";
											} else {
												echo "<option value=\"".getFormattedDateMonth($datetime)."\" selected=\"selected\">".getFormattedDateMonthname($datetime)."</option>"; 
											}
										  ?>
                                          <option value="01">Jan</option>
                                          <option value="02">Feb</option>
                                          <option value="03">Mar</option>
                                          <option value="04">Apr</option>
                                          <option value="05">May</option>
                                          <option value="06">Jun</option>
                                          <option value="07">Jul</option>
                                          <option value="08">Aug</option>
                                          <option value="09">Sept</option>
                                          <option value="10">Oct</option>
                                          <option value="11">Nov</option>
                                          <option value="12">Dec</option>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="collectionDateYear" id="collectionDateYear" class="search" <?=($noCollectionDateSupplied?"disabled=\"disabled\"":"")?>>
                                          		<?
												if($collectionDate) {
													echo "<option value=\"".getFormattedDateYear($collectionDate)."\" selected=\"selected\">".getFormattedDateYear($collectionDate)."</option>";
												} else {
													echo "<option value=\"".getFormattedDateYear($datetime)."\" selected=\"selected\">".getFormattedDateYear($datetime)."</option>"; 
												}
                                                for($j=(getFormattedDateYear($datetime)-1);$j>=(getCurrentYear()-10);$j--) {
                                                    echo "<option value=\"$j\">$j</option>";
                                                }
                                                ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><input name="noCollectionDateSupplied" type="checkbox" id="noCollectionDateSupplied" value="1" onclick="disableEnableCollectionDate(this);" <?=($noCollectionDateSupplied?"checked=\"checked\"":"")?> /></td>
                                        <td style="padding:0px 0px 0px 5px">No&nbsp;Collection&nbsp;Date&nbsp;supplied</td>
                                        </tr>
                                    </table>
                              </td>
                            </tr>
                            <tr>
							<td width="20%">Received&nbsp;at&nbsp;CPHL&nbsp;<font class="vl_red">*</font></td>
                              <td width="80%">
								<table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td><select name="receiptDateDay" id="receiptDateDay" class="search">
                                          <?
											if($receiptDate) {
												echo "<option value=\"".getFormattedDateDay($receiptDate)."\" selected=\"selected\">".getFormattedDateDay($receiptDate)."</option>";
											} else {
												echo "<option value=\"".getFormattedDateDay($datetime)."\" selected=\"selected\">".getFormattedDateDay($datetime)."</option>";
											}
											for($j=1;$j<=31;$j++) {
                                                echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
                                            }
                                            ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="receiptDateMonth" id="receiptDateMonth" class="search">
                                          <? 
											if($receiptDate) {
												echo "<option value=\"".getFormattedDateMonth($receiptDate)."\" selected=\"selected\">".getFormattedDateMonthname($receiptDate)."</option>";
											} else {
												echo "<option value=\"".getFormattedDateMonth($datetime)."\" selected=\"selected\">".getFormattedDateMonthname($datetime)."</option>"; 
											}
										  ?>
                                          <option value="01">Jan</option>
                                          <option value="02">Feb</option>
                                          <option value="03">Mar</option>
                                          <option value="04">Apr</option>
                                          <option value="05">May</option>
                                          <option value="06">Jun</option>
                                          <option value="07">Jul</option>
                                          <option value="08">Aug</option>
                                          <option value="09">Sept</option>
                                          <option value="10">Oct</option>
                                          <option value="11">Nov</option>
                                          <option value="12">Dec</option>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="receiptDateYear" id="receiptDateYear" class="search">
                                          		<?
												if($receiptDate) {
													echo "<option value=\"".getFormattedDateYear($receiptDate)."\" selected=\"selected\">".getFormattedDateYear($receiptDate)."</option>";
												} else {
													echo "<option value=\"".getFormattedDateYear($datetime)."\" selected=\"selected\">".getFormattedDateYear($datetime)."</option>"; 
												}
                                                for($j=getFormattedDateYear($datetime);$j>=2014;$j--) {
                                                    echo "<option value=\"$j\">$j</option>";
                                                }
                                                ?>
                                          </select>
                                      </td>
                                        </tr>
                                    </table>
                              </td>
                            </tr>
                            <tr>
                              <td>Sample&nbsp;Type&nbsp;<font class="vl_red">*</font></td>
                              <td>
								<select name="sampleTypeID" id="sampleTypeID" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_appendix_sampletype order by position");
								if($sampleTypeID) {
									echo "<option value=\"$sampleTypeID\" selected=\"selected\">".getDetailedTableInfo2("vl_appendix_sampletype","id='$sampleTypeID' limit 1","appendix")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Sample Type</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[appendix]</option>";
									}
								}
								?>
                                </select>
                              </td>
                        </tr>
                      </table>
                        </div>
                  </fieldset>
                </td>
            </tr>
            <tr>
                <td>
                  <fieldset style="width: 100%">
            <legend><strong>PATIENT INFORMATION</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
                            <tr>
                              <td width="100%"><table width="100%" border="0" class="vl">
                                <tr>
                                  <td width="20%">ART&nbsp;Number&nbsp;<font class="vl_red">*</font></td>
                                  <td width="80%"><input type="text" name="artNumber" id="artNumber" value="<?=$artNumber?>" class="search_pre" size="25" maxlength="20" onkeyup="return loadArtHistory(this,document.samples.facilityID.value)" /></td>
                                </tr>
                                <tr>
                                  <td>Other&nbsp;ID</td>
                                  <td><input type="text" name="otherID" id="otherID" value="<?=$otherID?>" class="search_pre" size="25" maxlength="50" /></td>
                                </tr>
                                <tr>
                                  <td>Gender&nbsp;<font class="vl_red">*</font></td>
                                  <td><select name="gender" id="gender" class="search" onchange="checkGender(this)">
                                    <?
									if($gender) {
										echo "<option value=\"$gender\" selected=\"selected\">$gender</option>";
									} else {
										echo "<option value=\"\" selected=\"selected\">Select Gender</option>";
									}
									?>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Missing Gender">Missing Gender</option>
                                  </select></td>
                                </tr>
                                <tr>
                                  <td>Date&nbsp;of&nbsp;Birth</td>
                                  <td><table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                    <tr>
                                      <td><select name="dateOfBirthDay" id="dateOfBirthDay" class="search" <?=($noDateOfBirthSupplied?"disabled=\"disabled\"":"")?>>
                                        <?
										  	if($dateOfBirth) {
												echo "<option value=\"".getFormattedDateDay($dateOfBirth)."\" selected=\"selected\">".getFormattedDateDay($dateOfBirth)."</option>";
											} else {
	                                            echo "<option value=\"\" selected=\"selected\">Select Date</option>";
											}
											for($j=1;$j<=31;$j++) {
                                                echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
                                            }
                                            ?>
                                      </select></td>
                                      <td style="padding:0px 0px 0px 5px"><select name="dateOfBirthMonth" id="dateOfBirthMonth" class="search" <?=($noDateOfBirthSupplied?"disabled=\"disabled\"":"")?>>
                                        <? 
										  	if($dateOfBirth) {
												echo "<option value=\"".getFormattedDateMonth($dateOfBirth)."\" selected=\"selected\">".getFormattedDateMonthname($dateOfBirth)."</option>";
											} else {
	                                            echo "<option value=\"\" selected=\"selected\">Select Month</option>"; 
											}
										  ?>
                                        <option value="01">Jan</option>
                                        <option value="02">Feb</option>
                                        <option value="03">Mar</option>
                                        <option value="04">Apr</option>
                                        <option value="05">May</option>
                                        <option value="06">Jun</option>
                                        <option value="07">Jul</option>
                                        <option value="08">Aug</option>
                                        <option value="09">Sept</option>
                                        <option value="10">Oct</option>
                                        <option value="11">Nov</option>
                                        <option value="12">Dec</option>
                                      </select></td>
                                      <td style="padding:0px 0px 0px 5px"><select name="dateOfBirthYear" id="dateOfBirthYear" class="search" onchange="checkMonthDay(this)" <?=($noDateOfBirthSupplied?"disabled=\"disabled\"":"")?>>
                                        <?
												if($dateOfBirth) {
													echo "<option value=\"".getFormattedDateYear($dateOfBirth)."\" selected=\"selected\">".getFormattedDateYear($dateOfBirth)."</option>";
												} else {
													echo "<option value=\"\" selected=\"selected\">Select Year</option>";
												}
                                                for($j=getFormattedDateYear(getDualInfoWithAlias("last_day(now())","lastmonth"));$j>=(getCurrentYear()-100);$j--) {
                                                    echo "<option value=\"$j\">$j</option>";
                                                }
                                                ?>
                                      </select></td>
                                      <td style="padding:0px 5px 0px 5px">or</td>
                                      <td style="padding:0px 0px 0px 5px"><select name="dateOfBirthAge" id="dateOfBirthAge" class="search" <?=($noDateOfBirthSupplied?"disabled=\"disabled\"":"")?>>
                                        <?
												if($dateOfBirthAge) {
													echo "<option value=\"$dateOfBirthAge\" selected=\"selected\">$dateOfBirthAge</option>";
												} else {
													echo "<option value=\"\" selected=\"selected\">Select Age</option>";
												}
                                                for($j=1;$j<=120;$j++) {
                                                    echo "<option value=\"$j\">$j</option>";
                                                }
                                                ?>
                                      </select></td>
                                      <td style="padding:0px 0px 0px 5px"><select name="dateOfBirthIn" id="dateOfBirthIn" class="search" <?=($noDateOfBirthSupplied?"disabled=\"disabled\"":"")?>>
                                        <?
													if($dateOfBirthIn) {
														echo "<option value=\"$dateOfBirthIn\" selected=\"selected\">$dateOfBirthIn</option>";
													} else {
														echo "<option value=\"\" selected=\"selected\">in Years or Months</option>";
													}
												?>
                                        <option value="Years">Years</option>
                                        <option value="Months">Months</option>
                                      </select></td>
                                      <td style="padding:0px 0px 0px 5px">or</td>
                                      <td style="padding:0px 0px 0px 5px"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                          <tr>
                                            <td width="1%"><input name="noDateOfBirthSupplied" type="checkbox" id="noDateOfBirthSupplied" value="1" onclick="disableEnableDateOfBirth(this);" <?=($noDateOfBirthSupplied?"checked=\"checked\"":"")?> /></td>
                                            <td width="99%" style="padding:0px 0px 0px 5px">No&nbsp;date&nbsp;of&nbsp;birth&nbsp;supplied</td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                  </table></td>
                                </tr>
                                <tr>
                                  <td>Patient&nbsp;Phone</td>
                                  <td><table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                    <tr>
                                      <td><input type="text" name="patientPhone" id="patientPhone" value="<?=$patientPhone?>" class="search_pre" size="15" maxlength="20" /></td>
                                    </tr>
                                  </table></td>
                                </tr>
                              </table></td>
                              <td id="artNumberHistoryID" style="padding:0px 0px 0px 10px">&nbsp;</td>
                            </tr>
                          </table>
                        </div>
                  </fieldset>
                </td>
            </tr>
            <tr>
                <td>
                  <fieldset style="width: 100%">
                    <legend><strong>TREATMENT INFORMATION</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
                          <table width="100%" border="0" class="vl">
                            <tr>
                              <td width="20%">
                              	Has&nbsp;Patient&nbsp;Been&nbsp;on&nbsp;Treatment
                                for&nbsp;the&nbsp;Last&nbsp;6&nbsp;Months&nbsp;<font class="vl_red">*</font>
                                </td>
                              <td width="80%">
								<select name="treatmentLast6Months" id="treatmentLast6Months" class="search">
                                <?
								if($treatmentLast6Months) {
									echo "<option value=\"$treatmentLast6Months\" selected=\"selected\">$treatmentLast6Months</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Appropriate Status</option>";
								}
								echo "<option value=\"Yes\">Yes</option>";
								echo "<option value=\"No\">No</option>";
								echo "<option value=\"Left Blank\">Left Blank</option>";
								?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>Treatment&nbsp;Initiation&nbsp;Date&nbsp;<font class="vl_red">*</font></td>
                              <td>
                                  <table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td><select name="treatmentInitiationDateDay" id="treatmentInitiationDateDay" class="search" <?=($noTreatmentInitiationDateSupplied?"disabled=\"disabled\"":"")?>>
                                          <?
											if($treatmentInitiationDate) {
												echo "<option value=\"".getFormattedDateDay($treatmentInitiationDate)."\" selected=\"selected\">".getFormattedDateDay($treatmentInitiationDate)."</option>";
											} else {
	                                            echo "<option value=\"\" selected=\"selected\">Select Date</option>";
											}
											for($j=1;$j<=31;$j++) {
                                                echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
                                            }
                                            ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="treatmentInitiationDateMonth" id="treatmentInitiationDateMonth" class="search" <?=($noTreatmentInitiationDateSupplied?"disabled=\"disabled\"":"")?>>
                                          <? 
											if($treatmentInitiationDate) {
												echo "<option value=\"".getFormattedDateMonth($treatmentInitiationDate)."\" selected=\"selected\">".getFormattedDateMonthname($treatmentInitiationDate)."</option>";
											} else {
	                                            echo "<option value=\"\" selected=\"selected\">Select Month</option>"; 
											}
										  ?>
                                          <option value="01">Jan</option>
                                          <option value="02">Feb</option>
                                          <option value="03">Mar</option>
                                          <option value="04">Apr</option>
                                          <option value="05">May</option>
                                          <option value="06">Jun</option>
                                          <option value="07">Jul</option>
                                          <option value="08">Aug</option>
                                          <option value="09">Sept</option>
                                          <option value="10">Oct</option>
                                          <option value="11">Nov</option>
                                          <option value="12">Dec</option>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="treatmentInitiationDateYear" id="treatmentInitiationDateYear" class="search" <?=($noTreatmentInitiationDateSupplied?"disabled=\"disabled\"":"")?>>
                                          		<?
												if($treatmentInitiationDate) {
													echo "<option value=\"".getFormattedDateYear($treatmentInitiationDate)."\" selected=\"selected\">".getFormattedDateYear($treatmentInitiationDate)."</option>";
												} else {
													echo "<option value=\"\" selected=\"selected\">Select Year</option>";
												}
												for($j=getFormattedDateYear(getDualInfoWithAlias("last_day(now())","lastmonth"));$j>=1990;$j--) {
                                                    echo "<option value=\"$j\">$j</option>";
                                                }
                                                ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><input name="noTreatmentInitiationDateSupplied" type="checkbox" id="noTreatmentInitiationDateSupplied" value="1" onclick="disableEnableTreatmentInitiationDate(this);" <?=($noTreatmentInitiationDateSupplied?"checked=\"checked\"":"")?> /></td>
                                        <td style="padding:0px 0px 0px 5px">No&nbsp;Treatment&nbsp;Initiation&nbsp;Date&nbsp;supplied</td>
                                        </tr>
                                    </table>
                              </td>
                            </tr>
                            <tr>
                              <td>Current&nbsp;Regimen&nbsp;<font class="vl_red">*</font></td>
                              <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td width="5%"><select name="currentRegimenID" id="currentRegimenID" class="search" onchange="matchRegimenTreatmentLine(this), captureOtherRegimen(this)">
                                    <?
								$query=0;
								$query=mysqlquery("select * from vl_appendix_regimen order by position");
								if($currentRegimenID) {
									echo "<option value=\"$currentRegimenID\" selected=\"selected\">".getDetailedTableInfo2("vl_appendix_regimen","id='$currentRegimenID' limit 1","appendix")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Current Regimen</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[appendix]</option>";
									}
								}
								?>
                                  </select></td>
                                  <td width="95%" style="padding: 0px 0px 0px 5px" id="otherRegimenID">
								<?
									if($currentRegimenID && $currentRegimenID==getDetailedTableInfo2("vl_appendix_regimen","appendix like '%Other%' limit 1","id")) {
										echo "<input type=\"text\" name=\"otherRegimen\" id=\"otherRegimen\" value=\"$otherRegimen\" class=\"search_pre\" size=\"25\" maxlength=\"50\" />";
									}
								?>
                                  </td>
                                </tr>
                              </table></td>
                            </tr>
                            <tr>
                              <td>Indication&nbsp;for&nbsp;Treatment&nbsp;Initiation</td>
                              <td>
								<select name="treatmentInitiationID" id="treatmentInitiationID" class="search" onchange="checkOptions(this)">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_appendix_treatmentinitiation order by position");
								if($treatmentInitiationID) {
									echo "<option value=\"$treatmentInitiationID\" selected=\"selected\">".getDetailedTableInfo2("vl_appendix_treatmentinitiation","id='$treatmentInitiationID' limit 1","appendix")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Treatment Initiation</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[appendix]</option>";
									}
								}
								?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td></td>
                              <td id="treatmentInitiationIDTD"></td>
                            </tr>
                            <tr>
                              <td>Patient&nbsp;Treatment&nbsp;Line&nbsp;<font class="vl_red">*</font></td>
                              <td><div class="vls_grey" style="padding:0px 0px 4px 0px" id="treatmentStatusTextID"><?=($treatmentStatusID?getDetailedTableInfo2("vl_appendix_treatmentstatus","id='$treatmentStatusID' limit 1","appendix"):"Select Current Regimen First")?></div><input type="hidden" name="treatmentStatusID" id="treatmentStatusID" value="<?=($treatmentStatusID?$treatmentStatusID:"")?>" />
								<!--
                                <select name="treatmentStatusID" id="treatmentStatusID" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_appendix_treatmentstatus order by position");
								if($treatmentStatusID) {
									echo "<option value=\"$treatmentStatusID\" selected=\"selected\">".getDetailedTableInfo2("vl_appendix_treatmentstatus","id='$treatmentStatusID' limit 1","appendix")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Treatment Status</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[appendix]</option>";
									}
								}
								?>
                                </select>
                                -->
                              </td>
                            </tr>
                            <tr>
                              <td>Failure&nbsp;Reason</td>
                              <td>
								<select name="reasonForFailureID" id="reasonForFailureID" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_appendix_failurereason order by position");
								if($reasonForFailureID) {
									echo "<option value=\"$reasonForFailureID\" selected=\"selected\">".getDetailedTableInfo2("vl_appendix_failurereason","id='$reasonForFailureID' limit 1","appendix")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Failure Reason</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[appendix]</option>";
									}
								}
								?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>Viral&nbsp;Load&nbsp;Testing&nbsp;<font class="vl_red">*</font></td>
                              <td>
								<select name="viralLoadTestingID" id="viralLoadTestingID" class="search" onchange="selectVLTesting(this)">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_appendix_viralloadtesting order by position");
								if($viralLoadTestingID) {
									echo "<option value=\"$viralLoadTestingID\" selected=\"selected\">".getDetailedTableInfo2("vl_appendix_viralloadtesting","id='$viralLoadTestingID' limit 1","appendix")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Viral Load Testing</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[appendix]</option>";
									}
								}
								?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>Pregnant&nbsp;<font class="vl_red">*</td>
                              <td>
								<select name="pregnant" id="pregnant" class="search" onchange="checkPregnancy(this)">
                                <?
								if($pregnant) {
									echo "<option value=\"$pregnant\" selected=\"selected\">$pregnant</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Pregnancy Status</option>";
								}
								echo "<option value=\"Yes\">Yes</option>";
								echo "<option value=\"No\">No</option>";
								echo "<option value=\"Left Blank\">Left Blank</option>";
								?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td id="pregnancyTextID"></td>
                              <td id="pregnancyID"></td>
                            </tr>
                            <tr>
                              <td>Breastfeeding&nbsp;<font class="vl_red">*</td>
                              <td>
								<select name="breastfeeding" id="breastfeeding" class="search">
                                <?
								if($breastfeeding) {
									echo "<option value=\"$breastfeeding\" selected=\"selected\">$breastfeeding</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Breastfeeding Status</option>";
								}
								echo "<option value=\"Yes\">Yes</option>";
								echo "<option value=\"No\">No</option>";
								echo "<option value=\"Left Blank\">Left Blank</option>";
								?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>Active&nbsp;TB&nbsp;Status</td>
                              <td>
								<select name="activeTBStatus" id="activeTBStatus" class="search">
                                <?
								if($activeTBStatus) {
									echo "<option value=\"$activeTBStatus\" selected=\"selected\">$activeTBStatus</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Active TB Status</option>";
								}
								echo "<option value=\"Yes\">Yes</option>";
								echo "<option value=\"No\">No</option>";
								?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>TB&nbsp;Treatment&nbsp;Phase</td>
                              <td>
								<select name="tbTreatmentPhaseID" id="tbTreatmentPhaseID" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_appendix_tbtreatmentphase order by position");
								if($tbTreatmentPhaseID) {
									echo "<option value=\"$tbTreatmentPhaseID\" selected=\"selected\">".getDetailedTableInfo2("vl_appendix_tbtreatmentphase","id='$tbTreatmentPhaseID' limit 1","appendix")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Failure Reason</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[appendix]</option>";
									}
								}
								?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>ARV&nbsp;Adherence</td>
                              <td>
								<select name="arvAdherenceID" id="arvAdherenceID" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_appendix_arvadherence order by position");
								if($arvAdherenceID) {
									echo "<option value=\"$arvAdherenceID\" selected=\"selected\">".getDetailedTableInfo2("vl_appendix_arvadherence","id='$arvAdherenceID' limit 1","appendix")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select ARV Adherence</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[appendix]</option>";
									}
								}
								?>
                                </select>
                              </td>
                            </tr>
                          </table>
                        </div>
                  </fieldset>
                </td>
            </tr>
            <tr>
              <td>
				<fieldset style="width: 100%">
					<legend><strong>INDICATION FOR VIRAL LOAD TESTING</strong></legend>
						<div style="padding:5px 0px 0px 0px">
						<table width="100%" border="0" class="vl">
                            <tr>
                              <td width="1%"><input name="viralLoadTestingIndication" id="vlTestingRoutineMonitoring" type="radio" value="vlTestingRoutineMonitoring" <?=($viralLoadTestingIndication=="vlTestingRoutineMonitoring"?" checked=\"checked\"":"")?> /></td>
                              <td width="69%">Routine Monitoring</td>
                              <td width="5%" align="right">Last&nbsp;Viral&nbsp;Load&nbsp;Date:</td>
                              <td width="5%" style="padding:0px 0px 0px 5px">
                                  <table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td><select name="routineMonitoringLastVLDateDay" id="routineMonitoringLastVLDateDay" class="search">
                                          <?
											if($routineMonitoringLastVLDate) {
												echo "<option value=\"".getFormattedDateDay($routineMonitoringLastVLDate)."\" selected=\"selected\">".getFormattedDateDay($routineMonitoringLastVLDate)."</option>";
											} else {
	                                            echo "<option value=\"\" selected=\"selected\">Select Date</option>";
											}
											for($j=1;$j<=31;$j++) {
                                                echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
                                            }
                                            ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="routineMonitoringLastVLDateMonth" id="routineMonitoringLastVLDateMonth" class="search">
                                          <? 
											if($routineMonitoringLastVLDate) {
												echo "<option value=\"".getFormattedDateMonth($routineMonitoringLastVLDate)."\" selected=\"selected\">".getFormattedDateMonthname($routineMonitoringLastVLDate)."</option>";
											} else {
	                                            echo "<option value=\"\" selected=\"selected\">Select Month</option>"; 
											}
										  ?>
                                          <option value="01">Jan</option>
                                          <option value="02">Feb</option>
                                          <option value="03">Mar</option>
                                          <option value="04">Apr</option>
                                          <option value="05">May</option>
                                          <option value="06">Jun</option>
                                          <option value="07">Jul</option>
                                          <option value="08">Aug</option>
                                          <option value="09">Sept</option>
                                          <option value="10">Oct</option>
                                          <option value="11">Nov</option>
                                          <option value="12">Dec</option>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="routineMonitoringLastVLDateYear" id="routineMonitoringLastVLDateYear" class="search">
                                          		<?
												if($routineMonitoringLastVLDate) {
													echo "<option value=\"".getFormattedDateYear($routineMonitoringLastVLDate)."\" selected=\"selected\">".getFormattedDateYear($routineMonitoringLastVLDate)."</option>";
												} else {
													echo "<option value=\"\" selected=\"selected\">Select Year</option>";
												}
												for($j=getFormattedDateYear(getDualInfoWithAlias("last_day(now())","lastmonth"));$j>=(getCurrentYear()-10);$j--) {
                                                    echo "<option value=\"$j\">$j</option>";
                                                }
                                                ?>
                                          </select></td>
                                        </tr>
                                    </table>
                              </td>
                              <td width="5%" style="padding: 0px 0px 0px 10px" align="right">Value:</td>
                              <td width="5%" style="padding:0px 0px 0px 5px"><input type="text" name="routineMonitoringValue" id="routineMonitoringValue" value="<?=$routineMonitoringValue?>" class="search_pre" size="7" maxlength="10" /></td>
                              <td width="5%" align="right">Sample&nbsp;Type:</td>
                              <td width="5%" style="padding:0px 0px 0px 5px">
								<select name="routineMonitoringSampleTypeID" id="routineMonitoringSampleTypeID" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_appendix_sampletype order by position");
								if($routineMonitoringSampleTypeID) {
									echo "<option value=\"$routineMonitoringSampleTypeID\" selected=\"selected\">".getDetailedTableInfo2("vl_appendix_sampletype","id='$routineMonitoringSampleTypeID' limit 1","appendix")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Sample Type</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[appendix]</option>";
									}
								}
								?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td><input name="viralLoadTestingIndication" id="vlTestingRepeatTesting" type="radio" value="vlTestingRepeatTesting" <?=($viralLoadTestingIndication=="vlTestingRepeatTesting"?" checked=\"checked\"":"")?> /></td>
                              <td>Repeat Viral Load Test after detectable viraemia and 6 months adherence counseling</td>
                              <td width="10%" align="right">Last&nbsp;Viral&nbsp;Load&nbsp;Date:</td>
                              <td width="10%" style="padding:0px 0px 0px 5px">
                                  <table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td><select name="repeatVLTestLastVLDateDay" id="repeatVLTestLastVLDateDay" class="search">
                                          <?
											if($repeatVLTestLastVLDate) {
												echo "<option value=\"".getFormattedDateDay($repeatVLTestLastVLDate)."\" selected=\"selected\">".getFormattedDateDay($repeatVLTestLastVLDate)."</option>";
											} else {
	                                            echo "<option value=\"\" selected=\"selected\">Select Date</option>";
											}
											for($j=1;$j<=31;$j++) {
                                                echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
                                            }
                                            ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="repeatVLTestLastVLDateMonth" id="repeatVLTestLastVLDateMonth" class="search">
                                          <? 
											if($repeatVLTestLastVLDate) {
												echo "<option value=\"".getFormattedDateMonth($repeatVLTestLastVLDate)."\" selected=\"selected\">".getFormattedDateMonthname($repeatVLTestLastVLDate)."</option>";
											} else {
	                                            echo "<option value=\"\" selected=\"selected\">Select Month</option>"; 
											}
										  ?>
                                          <option value="01">Jan</option>
                                          <option value="02">Feb</option>
                                          <option value="03">Mar</option>
                                          <option value="04">Apr</option>
                                          <option value="05">May</option>
                                          <option value="06">Jun</option>
                                          <option value="07">Jul</option>
                                          <option value="08">Aug</option>
                                          <option value="09">Sept</option>
                                          <option value="10">Oct</option>
                                          <option value="11">Nov</option>
                                          <option value="12">Dec</option>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="repeatVLTestLastVLDateYear" id="repeatVLTestLastVLDateYear" class="search">
                                          		<?
												if($repeatVLTestLastVLDate) {
													echo "<option value=\"".getFormattedDateYear($repeatVLTestLastVLDate)."\" selected=\"selected\">".getFormattedDateYear($repeatVLTestLastVLDate)."</option>";
												} else {
													echo "<option value=\"\" selected=\"selected\">Select Year</option>";
												}
												for($j=getFormattedDateYear(getDualInfoWithAlias("last_day(now())","lastmonth"));$j>=(getCurrentYear()-10);$j--) {
                                                    echo "<option value=\"$j\">$j</option>";
                                                }
                                                ?>
                                          </select></td>
                                        </tr>
                                    </table>
                              </td>
                              <td style="padding: 0px 0px 0px 10px" align="right">Value:</td>
                              <td style="padding:0px 0px 0px 5px"><input type="text" name="repeatVLTestValue" id="repeatVLTestValue" value="<?=$repeatVLTestValue?>" class="search_pre" size="7" maxlength="10" /></td>
                              <td align="right">Sample&nbsp;Type:</td>
                              <td style="padding:0px 0px 0px 5px">
								<select name="repeatVLTestSampleTypeID" id="repeatVLTestSampleTypeID" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_appendix_sampletype order by position");
								if($repeatVLTestSampleTypeID) {
									echo "<option value=\"$repeatVLTestSampleTypeID\" selected=\"selected\">".getDetailedTableInfo2("vl_appendix_sampletype","id='$repeatVLTestSampleTypeID' limit 1","appendix")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Sample Type</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[appendix]</option>";
									}
								}
								?>
                                </select>
                              </td>
                            </tr>
                            <tr>
							<td><input name="viralLoadTestingIndication" id="vlTestingSuspectedTreatmentFailure" type="radio" value="vlTestingSuspectedTreatmentFailure" <?=($viralLoadTestingIndication=="vlTestingSuspectedTreatmentFailure"?" checked=\"checked\"":"")?> /></td>
                              <td>Suspected Treatment Failure</td>
                              <td width="10%" align="right">Last&nbsp;Viral&nbsp;Load&nbsp;Date:</td>
                              <td width="10%" style="padding:0px 0px 0px 5px">
                                  <table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td><select name="suspectedTreatmentFailureLastVLDateDay" id="suspectedTreatmentFailureLastVLDateDay" class="search">
                                          <?
											if($suspectedTreatmentFailureLastVLDate) {
												echo "<option value=\"".getFormattedDateDay($suspectedTreatmentFailureLastVLDate)."\" selected=\"selected\">".getFormattedDateDay($suspectedTreatmentFailureLastVLDate)."</option>";
											} else {
	                                            echo "<option value=\"\" selected=\"selected\">Select Date</option>";
											}
											for($j=1;$j<=31;$j++) {
                                                echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
                                            }
                                            ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="suspectedTreatmentFailureLastVLDateMonth" id="suspectedTreatmentFailureLastVLDateMonth" class="search">
                                          <? 
											if($suspectedTreatmentFailureLastVLDate) {
												echo "<option value=\"".getFormattedDateMonth($suspectedTreatmentFailureLastVLDate)."\" selected=\"selected\">".getFormattedDateMonthname($suspectedTreatmentFailureLastVLDate)."</option>";
											} else {
	                                            echo "<option value=\"\" selected=\"selected\">Select Month</option>"; 
											}
										  ?>
                                          <option value="01">Jan</option>
                                          <option value="02">Feb</option>
                                          <option value="03">Mar</option>
                                          <option value="04">Apr</option>
                                          <option value="05">May</option>
                                          <option value="06">Jun</option>
                                          <option value="07">Jul</option>
                                          <option value="08">Aug</option>
                                          <option value="09">Sept</option>
                                          <option value="10">Oct</option>
                                          <option value="11">Nov</option>
                                          <option value="12">Dec</option>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="suspectedTreatmentFailureLastVLDateYear" id="suspectedTreatmentFailureLastVLDateYear" class="search">
                                          		<?
												if($suspectedTreatmentFailureLastVLDate) {
													echo "<option value=\"".getFormattedDateYear($suspectedTreatmentFailureLastVLDate)."\" selected=\"selected\">".getFormattedDateYear($suspectedTreatmentFailureLastVLDate)."</option>";
												} else {
													echo "<option value=\"\" selected=\"selected\">Select Year</option>";
												}
												for($j=getFormattedDateYear(getDualInfoWithAlias("last_day(now())","lastmonth"));$j>=(getCurrentYear()-10);$j--) {
                                                    echo "<option value=\"$j\">$j</option>";
                                                }
                                                ?>
                                          </select></td>
                                        </tr>
                                    </table>
                              </td>
                              <td style="padding: 0px 0px 0px 10px" align="right">Value:</td>
                              <td style="padding:0px 0px 0px 5px"><input type="text" name="suspectedTreatmentFailureValue" id="suspectedTreatmentFailureValue" value="<?=$suspectedTreatmentFailureValue?>" class="search_pre" size="7" maxlength="10" /></td>
                              <td align="right">Sample&nbsp;Type:</td>
                              <td style="padding:0px 0px 0px 5px">
								<select name="suspectedTreatmentFailureSampleTypeID" id="suspectedTreatmentFailureSampleTypeID" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_appendix_sampletype order by position");
								if($suspectedTreatmentFailureSampleTypeID) {
									echo "<option value=\"$suspectedTreatmentFailureSampleTypeID\" selected=\"selected\">".getDetailedTableInfo2("vl_appendix_sampletype","id='$suspectedTreatmentFailureSampleTypeID' limit 1","appendix")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Sample Type</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[appendix]</option>";
									}
								}
								?>
                                </select>
                              </td>
                            </tr>
						</table>
                        </div>
                </fieldset>
              </td>
            </tr>
            <tr>
              <td style="padding:10px 0px 0px 0px"><input type="submit" name="saveSample" id="saveSample" class="button" value="  Save Samples  " /></td>
            </tr>
            <tr>
	            <td style="padding:20px 0px 0px 0px"><a href="/samples/">Return to Samples</a> :: <a href="/dashboard/">Return to Dashboard</a></td>
            </tr>
          </table>
</form>

<script type="text/javascript">
$(document).ready(function() {
  $("#fclty").select2({placeholder:"Select facility",allowClear:true,width:"250"});
  var f_number=$("#formNumber").val();
  if(f_number!="" && $("#fclty").val()==""){
  	fetchFacilityID(f_number);
  }
});

function fetchFacilityID(formNumber){
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari 
		xmlhttp=new XMLHttpRequest();
	}else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			$("#fclty").select2('val',xmlhttp.responseText);
		}
	}

	xmlhttp.open("GET","/get_facility_id/"+formNumber+"/",true);
	xmlhttp.send();
}

</script>