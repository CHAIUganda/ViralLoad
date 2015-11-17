<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

//validation
if(!$modify) {
	//$modify=getDetailedTableInfo2("vl_samples","formNumber='$sample' or vlSampleID='$sample' limit 1","id");
	$modify=getDetailedTableInfo2("vl_samples","formNumber='$sample' or formNumber='".($sample/1)."' or vlSampleID='$sample' or concat(lrCategory,lrEnvelopeNumber,'/',lrNumericID)='$sample' limit 1","id");
}

if($saveChanges) {
	//validations
	$lrCategory=validate($lrCategory);
	$lrEnvelopeNumber=validate($lrEnvelopeNumber);
	if($lrEnvelopeNumber=="Envelope #") {
		$lrEnvelopeNumber="";
	}
	$lrNumericID=validate($lrNumericID);

	$formNumber=validate($formNumber);
	$facilityID=validate($facilityID);
	$hubID=validate($hubID);
	$districtID=validate($districtID);

	$collectionDateDay=validate($collectionDateDay);
	$collectionDateMonth=validate($collectionDateMonth);
	$collectionDateYear=validate($collectionDateYear);
	$receiptDateDay=validate($receiptDateDay);
	$receiptDateMonth=validate($receiptDateMonth);
	$receiptDateYear=validate($receiptDateYear);
	$sampleTypeID=validate($sampleTypeID);

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
	
	//validate data
	$error=0;
	$error=checkFormFields("Form_Number::$formNumber Facility_Name::$facilityID Sample_Type::$sampleTypeID Current_Regimen::$currentRegimenID Treatment_Status::$treatmentStatusID Viral_Load_Testing::$viralLoadTestingID Pregnancy_Status::$pregnant Breastfeeding_Status::$breastfeeding Viral_Load_Testing::$viralLoadTestingID Whether_Patient_has_been_on_Treatment_for_last_6_months::$treatmentLast6Months");
	
	//to be edited: ensure lrCategory, lrEnvelopeNumber and lrNumericID are unique if supplied
	if($lrCategory && $lrEnvelopeNumber && $lrEnvelopeNumber!="Envelope #" && $lrNumericID) {
		if(getDetailedTableInfo2("vl_samples","lrCategory='$lrCategory' and lrEnvelopeNumber='$lrEnvelopeNumber' and lrNumericID='$lrNumericID' and id!='$modify' limit 1","id")) {
			$error.="<br /><strong>Duplicate ".($lrCategory=="V"?"Location":"Rejection")." ID '$lrCategory"."$lrEnvelopeNumber/$lrNumericID'.</strong><br />The ".($lrCategory=="V"?"Location":"Rejection")." ID <strong>$lrCategory"."$lrEnvelopeNumber/$lrNumericID</strong> was entered on <strong>".getFormattedDate(getDetailedTableInfo2("vl_samples","lrCategory='$lrCategory' and lrEnvelopeNumber='$lrEnvelopeNumber' and lrNumericID='$lrNumericID' and id!='$modify' limit 1","created"))."</strong> by <strong>".getDetailedTableInfo2("vl_samples","lrCategory='$lrCategory' and lrEnvelopeNumber='$lrEnvelopeNumber' and lrNumericID='$lrNumericID' and id!='$modify' limit 1","createdby")."</strong> <a href=\"#\" onclick=\"iDisplayMessage('/verify/preview/".getDetailedTableInfo2("vl_samples","lrCategory='$lrCategory' and lrEnvelopeNumber='$lrEnvelopeNumber' and lrNumericID='$lrNumericID' and id!='$modify' limit 1","id")."/1/noedit/')\">Click here to see the entry</a>.<br /> Kindly input this record with an alternative ".($lrCategory=="V"?"Location":"Rejection")." ID.<br />";
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
	if(getDetailedTableInfo2("vl_samples","formNumber='$formNumber' and id!='$modify' limit 1","id")) {
		$error.="<br /><strong>Duplicate Form Number '$formNumber'.</strong><br />The Form Number <strong>$formNumber</strong> was entered on <strong>".getFormattedDate(getDetailedTableInfo2("vl_samples","formNumber='$formNumber' limit 1","created"))."</strong> by <strong>".getDetailedTableInfo2("vl_samples","formNumber='$formNumber' limit 1","createdby")."</strong> <a href=\"#\" onclick=\"iDisplayMessage('/verify/preview/".getDetailedTableInfo2("vl_samples","formNumber='$formNumber' limit 1","id")."/1/noedit/')\">Click here to see the entry</a>.<br /> Kindly input this record with an alternative Form Number.<br />";
	}

	//ensure form number is numeric
	if(!is_numeric($formNumber)) {
		$error.="<br /><strong>Form Number '$formNumber' is Not Numeric.</strong><br />The Form Number should be Numeric i.e it should not contain alphanumeric characters e.g A-Z.<br />";
	}

	//ensure form number is valid
	if(!getDetailedTableInfo2("vl_forms_clinicalrequest","formNumber='$formNumber' or formNumber='".($formNumber/1)."' limit 1","id")) {
		$error.="<br /><strong>Invalid Form Number '$formNumber'.</strong><br />The Form Number <strong>$formNumber</strong> does not exist in the list of valid Form Numbers.<br /> Kindly input this record with a valid Form Number.<br />";
	}


	//ensure facility is valid
	if(!getDetailedTableInfo2("vl_facilities","id='$facilityID'","id")) {
		$error.="<br /><strong>Incorrect Facility '$facilityID'.</strong><br />Kindly select an existing Facility from the list or Request an Administrator to first add this Facility '$facilityID' to the System's Database before Proceeding.<br />";
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

	//input data
	if(!$error) {
		//log changes
		logTableChange("vl_samples","lrCategory",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","lrCategory"),$lrCategory);
		logTableChange("vl_samples","lrEnvelopeNumber",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","lrEnvelopeNumber"),$lrEnvelopeNumber);
		logTableChange("vl_samples","lrNumericID",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","lrNumericID"),$lrNumericID);
		
		logTableChange("vl_samples","formNumber",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","formNumber"),$formNumber);
		logTableChange("vl_samples","districtID",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","districtID"),$districtID);
		logTableChange("vl_samples","hubID",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","hubID"),$hubID);
		logTableChange("vl_samples","facilityID",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","facilityID"),$facilityID);
		logTableChange("vl_samples","currentRegimenID",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","currentRegimenID"),$currentRegimenID);
		logTableChange("vl_samples","pregnant",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","pregnant"),$pregnant);
		logTableChange("vl_samples","pregnantANCNumber",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","pregnantANCNumber"),$pregnantANCNumber);
		logTableChange("vl_samples","breastfeeding",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","breastfeeding"),$breastfeeding);
		logTableChange("vl_samples","activeTBStatus",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","activeTBStatus"),$activeTBStatus);
		logTableChange("vl_samples","collectionDate",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","collectionDate"),$collectionDate);
		logTableChange("vl_samples","receiptDate",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","receiptDate"),$receiptDate);
		logTableChange("vl_samples","treatmentLast6Months",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","treatmentLast6Months"),$treatmentLast6Months);
		logTableChange("vl_samples","treatmentInitiationDate",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","treatmentInitiationDate"),$treatmentInitiationDate);
		logTableChange("vl_samples","sampleTypeID",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","sampleTypeID"),$sampleTypeID);
		logTableChange("vl_samples","viralLoadTestingID",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","viralLoadTestingID"),$viralLoadTestingID);
		logTableChange("vl_samples","treatmentInitiationID",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","treatmentInitiationID"),$treatmentInitiationID);
		logTableChange("vl_samples","treatmentInitiationOther",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","treatmentInitiationOther"),$treatmentInitiationOther);
		logTableChange("vl_samples","treatmentStatusID",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","treatmentStatusID"),$treatmentStatusID);
		logTableChange("vl_samples","reasonForFailureID",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","reasonForFailureID"),$reasonForFailureID);
		logTableChange("vl_samples","tbTreatmentPhaseID",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","tbTreatmentPhaseID"),$tbTreatmentPhaseID);
		logTableChange("vl_samples","arvAdherenceID",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","arvAdherenceID"),$arvAdherenceID);

		logTableChange("vl_samples","viralLoadTestingIndication",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","viralLoadTestingIndication"),$viralLoadTestingIndication);

		logTableChange("vl_samples","routineMonitoringLastVLDate",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","routineMonitoringLastVLDate"),$routineMonitoringLastVLDate);
		logTableChange("vl_samples","routineMonitoringValue",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","routineMonitoringValue"),$routineMonitoringValue);
		logTableChange("vl_samples","routineMonitoringSampleTypeID",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","routineMonitoringSampleTypeID"),$routineMonitoringSampleTypeID);

		logTableChange("vl_samples","repeatVLTestLastVLDate",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","repeatVLTestLastVLDate"),$repeatVLTestLastVLDate);
		logTableChange("vl_samples","repeatVLTestValue",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","repeatVLTestValue"),$repeatVLTestValue);
		logTableChange("vl_samples","repeatVLTestSampleTypeID",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","repeatVLTestSampleTypeID"),$repeatVLTestSampleTypeID);

		logTableChange("vl_samples","suspectedTreatmentFailureLastVLDate",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","suspectedTreatmentFailureLastVLDate"),$suspectedTreatmentFailureLastVLDate);
		logTableChange("vl_samples","suspectedTreatmentFailureValue",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","suspectedTreatmentFailureValue"),$suspectedTreatmentFailureValue);
		logTableChange("vl_samples","suspectedTreatmentFailureSampleTypeID",$modify,getDetailedTableInfo2("vl_samples","id='$modify'","suspectedTreatmentFailureSampleTypeID"),$suspectedTreatmentFailureSampleTypeID);

		mysqlquery("update vl_samples set 
						lrCategory='$lrCategory',
						lrEnvelopeNumber='$lrEnvelopeNumber',
						lrNumericID='$lrNumericID',
						
						formNumber='$formNumber',
						districtID='$districtID',
						hubID='$hubID',
						facilityID='$facilityID',
						currentRegimenID='$currentRegimenID',
						pregnant='$pregnant',
						pregnantANCNumber='$pregnantANCNumber',
						breastfeeding='$breastfeeding',
						activeTBStatus='$activeTBStatus',
						collectionDate='$collectionDate',
						receiptDate='$receiptDate',
						treatmentLast6Months='$treatmentLast6Months',
						treatmentInitiationDate='$treatmentInitiationDate',
						sampleTypeID='$sampleTypeID',
						viralLoadTestingID='$viralLoadTestingID',
						treatmentInitiationID='$treatmentInitiationID',
						treatmentInitiationOther='$treatmentInitiationOther',
						treatmentStatusID='$treatmentStatusID',
						reasonForFailureID='$reasonForFailureID',
						tbTreatmentPhaseID='$tbTreatmentPhaseID',

						".($viralLoadTestingIndication=="vlTestingRoutineMonitoring"?"vlTestingRoutineMonitoring='1',":"vlTestingRoutineMonitoring='0',")."
						".($viralLoadTestingIndication=="vlTestingRoutineMonitoring"?"routineMonitoringLastVLDate='$routineMonitoringLastVLDate',":"routineMonitoringLastVLDate='',")."
						".($viralLoadTestingIndication=="vlTestingRoutineMonitoring"?"routineMonitoringValue='$routineMonitoringValue',":"routineMonitoringValue='',")."
						".($viralLoadTestingIndication=="vlTestingRoutineMonitoring"?"routineMonitoringSampleTypeID='$routineMonitoringSampleTypeID',":"routineMonitoringSampleTypeID='',")."

						".($viralLoadTestingIndication=="vlTestingRepeatTesting"?"vlTestingRepeatTesting='1',":"vlTestingRepeatTesting='0',")."
						".($viralLoadTestingIndication=="vlTestingRepeatTesting"?"repeatVLTestLastVLDate='$repeatVLTestLastVLDate',":"repeatVLTestLastVLDate='',")."
						".($viralLoadTestingIndication=="vlTestingRepeatTesting"?"repeatVLTestValue='$repeatVLTestValue',":"repeatVLTestValue='',")."
						".($viralLoadTestingIndication=="vlTestingRepeatTesting"?"repeatVLTestSampleTypeID='$repeatVLTestSampleTypeID',":"repeatVLTestSampleTypeID='',")."

						".($viralLoadTestingIndication=="vlTestingSuspectedTreatmentFailure"?"vlTestingSuspectedTreatmentFailure='1',":"vlTestingSuspectedTreatmentFailure='0',")."
						".($viralLoadTestingIndication=="vlTestingSuspectedTreatmentFailure"?"suspectedTreatmentFailureLastVLDate='$suspectedTreatmentFailureLastVLDate',":"suspectedTreatmentFailureLastVLDate='',")."
						".($viralLoadTestingIndication=="vlTestingSuspectedTreatmentFailure"?"suspectedTreatmentFailureValue='$suspectedTreatmentFailureValue',":"suspectedTreatmentFailureValue='',")."
						".($viralLoadTestingIndication=="vlTestingSuspectedTreatmentFailure"?"suspectedTreatmentFailureSampleTypeID='$suspectedTreatmentFailureSampleTypeID',":"suspectedTreatmentFailureSampleTypeID='',")."

						arvAdherenceID='$arvAdherenceID' 
						where 
						id='$modify'");

		//redirect to home with updates on the tracking number
		go("/samples/modified/");
	}
} else {
	//prepopulate
	$lrCategory=0;
	$lrCategory=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","lrCategory");

	$lrEnvelopeNumber=0;
	$lrEnvelopeNumber=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","lrEnvelopeNumber");

	$lrNumericID=0;
	$lrNumericID=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","lrNumericID");

	$formNumber=0;
	$formNumber=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","formNumber");
	
	$districtID=0;
	$districtID=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","districtID");
	
	$hubID=0;
	$hubID=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","hubID");
	
	$facilityID=0;
	$facilityID=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","facilityID");
	
	$currentRegimenID=0;
	$currentRegimenID=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","currentRegimenID");
	
	$pregnant=0;
	$pregnant=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","pregnant");
	
	$pregnantANCNumber=0;
	$pregnantANCNumber=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","pregnantANCNumber");
	
	$breastfeeding=0;
	$breastfeeding=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","breastfeeding");
	
	$activeTBStatus=0;
	$activeTBStatus=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","activeTBStatus");
	
	$collectionDate=0;
	$collectionDate=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","collectionDate");
	
	$receiptDate=0;
	$receiptDate=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","receiptDate");
	
	$treatmentLast6Months=0;
	$treatmentLast6Months=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","treatmentLast6Months");
	
	$treatmentInitiationDate=0;
	$treatmentInitiationDate=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","treatmentInitiationDate");
	if($treatmentInitiationDate=="0000-00-00") {
		$treatmentInitiationDate="";
	}
	
	$sampleTypeID=0;
	$sampleTypeID=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","sampleTypeID");
	
	$viralLoadTestingID=0;
	$viralLoadTestingID=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","viralLoadTestingID");
	
	$treatmentInitiationID=0;
	$treatmentInitiationID=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","treatmentInitiationID");
	
	$treatmentInitiationOther=0;
	$treatmentInitiationOther=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","treatmentInitiationOther");
	
	$treatmentStatusID=0;
	$treatmentStatusID=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","treatmentStatusID");
	
	$reasonForFailureID=0;
	$reasonForFailureID=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","reasonForFailureID");
	
	$tbTreatmentPhaseID=0;
	$tbTreatmentPhaseID=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","tbTreatmentPhaseID");
	
	$arvAdherenceID=0;
	$arvAdherenceID=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","arvAdherenceID");
	
	$viralLoadTestingIndication=0;
	$vlTestingRoutineMonitoring=0;
	$vlTestingRoutineMonitoring=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","vlTestingRoutineMonitoring");
	if($vlTestingRoutineMonitoring) {
		$viralLoadTestingIndication="vlTestingRoutineMonitoring";
	}
	
	$routineMonitoringLastVLDate=0;
	$routineMonitoringLastVLDate=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","routineMonitoringLastVLDate");
	if($routineMonitoringLastVLDate=="0000-00-00") {
		$routineMonitoringLastVLDate="";
	}
	
	$routineMonitoringValue=0;
	$routineMonitoringValue=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","routineMonitoringValue");
	
	$routineMonitoringSampleTypeID=0;
	$routineMonitoringSampleTypeID=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","routineMonitoringSampleTypeID");
	
	$vlTestingRepeatTesting=0;
	$vlTestingRepeatTesting=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","vlTestingRepeatTesting");
	if($vlTestingRepeatTesting) {
		$viralLoadTestingIndication="vlTestingRepeatTesting";
	}
	
	$repeatVLTestLastVLDate=0;
	$repeatVLTestLastVLDate=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","repeatVLTestLastVLDate");
	if($repeatVLTestLastVLDate=="0000-00-00") {
		$repeatVLTestLastVLDate="";
	}
	
	$repeatVLTestValue=0;
	$repeatVLTestValue=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","repeatVLTestValue");
	
	$repeatVLTestSampleTypeID=0;
	$repeatVLTestSampleTypeID=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","repeatVLTestSampleTypeID");
	
	$vlTestingSuspectedTreatmentFailure=0;
	$vlTestingSuspectedTreatmentFailure=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","vlTestingSuspectedTreatmentFailure");
	if($vlTestingSuspectedTreatmentFailure) {
		$viralLoadTestingIndication="vlTestingSuspectedTreatmentFailure";
	}
	
	$suspectedTreatmentFailureLastVLDate=0;
	$suspectedTreatmentFailureLastVLDate=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","suspectedTreatmentFailureLastVLDate");
	if($suspectedTreatmentFailureLastVLDate=="0000-00-00") {
		$suspectedTreatmentFailureLastVLDate="";
	}
	
	$suspectedTreatmentFailureValue=0;
	$suspectedTreatmentFailureValue=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","suspectedTreatmentFailureValue");
	
	$suspectedTreatmentFailureSampleTypeID=0;
	$suspectedTreatmentFailureSampleTypeID=getDetailedTableInfo2("vl_samples","id='$modify' limit 1","suspectedTreatmentFailureSampleTypeID");
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
	if((!document.samples.dateOfBirthDay.value || !document.samples.dateOfBirthMonth.value || !document.samples.dateOfBirthYear.value) && (!document.samples.dateOfBirthAge.value || !document.samples.dateOfBirthIn.value)) {
		alert('Missing Mandatory Field: Date of Birth or Patient Age');
		document.samples.dateOfBirthDay.focus();
		return (false);
	}
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
		alert('Missing Mandatory Field: Treatment Status');
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
	if(theField.value==<?=getDetailedTableInfo2("vl_appendix_treatmentinitiation","appendix like '%other%' limit 1","id")?>) {
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
	
	document.getElementById("checkHubDistrictID").innerHTML="loading hub and district ...";
	//get hub
	vlDC_XloadHub('samples','checkHubDistrictID','hubID',theFacilityID);
	//get district
	vlDC_XloadDistrict('samples','checkHubDistrictID','districtID',theFacilityID);
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
//-->
</script>
<? //if(!getDetailedTableInfo2("vl_samples_verify","sampleID='$modify'","outcome")) { ?>
<!--<form name="samples" method="post" action="/samples/find.and.edit/" onsubmit="return validate(this)">-->
<form name="samples" method="post" action="/samples/find.and.edit/">
<? //} ?>
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
            <? } ?>
            <tr>
              <td class="toplinks" style="padding:0px 0px 10px 0px"><a class="toplinks" href="/dashboard/">HOME</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/samples/">SAMPLES</a></td>
            </tr>
            <? //if(getDetailedTableInfo2("vl_samples_verify","sampleID='$modify'","outcome")) { ?>
            <!--
            <tr>
              <td class="vl_error">
              	<div>This Sample's Status was changed to <strong><?=getDetailedTableInfo2("vl_samples_verify","sampleID='$modify'","outcome")?></strong> on <strong><?=getFormattedDate(getDetailedTableInfo2("vl_samples_verify","sampleID='$modify'","created"))?></strong> by <strong><?=getDetailedTableInfo2("vl_samples_verify","sampleID='$modify'","createdby")?></strong></div>
                <div style="padding:3px 0px 0px 0px" class="vls_grey">This sample can therefore no longer be modified!</div>
                <div style="padding:1px 0px 0px 0px" class="vls_grey"><a href="#" onclick="iDisplayMessage('/verify/status/<?=$modify?>/<?=getDetailedTableInfo2("vl_samples_verify","sampleID='$modify'","outcome")?>/')">Click to View <?=getDetailedTableInfo2("vl_samples_verify","sampleID='$modify'","outcome")?> Details!</a></div>
              </td>
            </tr>
            -->
            <? //} ?>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                  <fieldset style="width: 100%">
            <legend><strong>FORM/FACILITY CREDENTIALS</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
						<table width="100%" border="0" class="vl">
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
                              <td><input type="text" name="formNumber" id="formNumber" value="<?=$formNumber?>" class="search_pre" size="15" maxlength="50" /></td>
                            </tr>
                        <tr>
                          <td>Facility&nbsp;Name&nbsp;<font class="vl_red">*</font></td>
                          <td>
                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td width="20%">
                                        <select name="facilityID" id="facilityID" class="search" onchange="checkForHubDistrict()">
                                            <?
                                            $query=0;
                                            $query=mysqlquery("select * from vl_facilities where facility!='' order by facility");
                                            if($facilityID) {
                                                echo "<option value=\"$facilityID\" selected=\"selected\">".getDetailedTableInfo2("vl_facilities","id='$facilityID' limit 1","facility")."</option>";
                                            } else {
                                                echo "<option value=\"\" selected=\"selected\">Select Facility</option>";
                                            }
                                            if(mysqlnumrows($query)) {
                                                while($q=mysqlfetcharray($query)) {
                                                    echo "<option value=\"$q[id]\">$q[facility]</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                        <script>
                                            var z = dhtmlXComboFromSelect("facilityID");
                                            z.enableFilteringMode(true);
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
                          <select name="hubID" id="hubID" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_hubs order by hub");
								if($hubID) {
									echo "<option value=\"$hubID\" selected=\"selected\">".getDetailedTableInfo2("vl_hubs","id='$hubID' limit 1","hub")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Hub</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[hub]</option>";
									}
								}
								?>
                                </select>
                          </td>
                        </tr>
                        <tr>
                          <td>District</td>
                          <td>
                          <select name="districtID" id="districtID" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_districts order by district");
								if($districtID) {
									echo "<option value=\"$districtID\" selected=\"selected\">".getDetailedTableInfo2("vl_districts","id='$districtID' limit 1","district")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select District</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[district]</option>";
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
            <legend><strong>SAMPLE DETAILS</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
						<table width="100%" border="0" class="vl">
                            <tr>
							<td width="20%">Collection&nbsp;Date&nbsp;<font class="vl_red">*</font></td>
                              <td width="80%">
								<table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td><select name="collectionDateDay" id="collectionDateDay" class="search">
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
                                        <td style="padding:0px 0px 0px 5px"><select name="collectionDateMonth" id="collectionDateMonth" class="search">
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
                                        <td style="padding:0px 0px 0px 5px"><select name="collectionDateYear" id="collectionDateYear" class="search">
                                          		<?
												if($collectionDate) {
													echo "<option value=\"".getFormattedDateYear($collectionDate)."\" selected=\"selected\">".getFormattedDateYear($collectionDate)."</option>";
												} else {
													echo "<option value=\"".getFormattedDateYear($datetime)."\" selected=\"selected\">".getFormattedDateYear($datetime)."</option>"; 
												}
                                                for($j=(getFormattedDateYear($datetime));$j>=(getCurrentYear()-10);$j--) {
                                                    echo "<option value=\"$j\">$j</option>";
                                                }
                                                ?>
                                          </select></td>
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
                                          </select></td>
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
                                        <td><select name="treatmentInitiationDateDay" id="treatmentInitiationDateDay" class="search">
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
                                        <td style="padding:0px 0px 0px 5px"><select name="treatmentInitiationDateMonth" id="treatmentInitiationDateMonth" class="search">
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
                                        <td style="padding:0px 0px 0px 5px"><select name="treatmentInitiationDateYear" id="treatmentInitiationDateYear" class="search">
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
                                        </tr>
                                    </table>
                              </td>
                            </tr>
                            <tr>
                              <td>Current&nbsp;Regimen&nbsp;<font class="vl_red">*</font></td>
                              <td>
								<select name="currentRegimenID" id="currentRegimenID" class="search">
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
                                </select>
                              </td>
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
                              <td>
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
            <? //if(!getDetailedTableInfo2("vl_samples_verify","sampleID='$modify'","outcome")) { ?>
            <tr>
              <td style="padding:10px 0px 0px 0px">
              	<input type="hidden" name="modify" id="modify" value="<?=validate($modify)?>" />
              	<input type="hidden" name="sample" id="sample" value="<?=validate($sample)?>" />
                <input type="submit" name="saveChanges" id="saveChanges" class="button" value="  Save Changes  " />
              </td>
            </tr>
            <? //} ?>
            <tr>
	            <td style="padding:20px 0px 0px 0px"><a href="/samples/">Return to Samples</a> :: <a href="/dashboard/">Return to Dashboard</a></td>
            </tr>
          </table>
<? //if(!getDetailedTableInfo2("vl_samples_verify","sampleID='$modify'","outcome")) { ?>
</form>
<? //} ?>