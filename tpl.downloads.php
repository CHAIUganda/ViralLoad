<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

switch($options) {
	case "samplesreceivedcsv":
		//ensure the download path exists
		$path=0;
		$path=$system_default_path."downloads.reports/";
		$pathURL=0;
		$pathURL=$home_url."downloads.reports/";
		if(!is_dir($path)) {
			mkdir($path,0755);
		}
		
		//create File Name
		$downloadFileName=0;
		$downloadFileName="download.".$options.".csv";
		
		//initiate the excel render
		$xls=0;
		$xls="";
		
		//period
		$from=0;
		$from="$fromYear-$fromMonth-$fromDay";
		$to=0;
		$to="$toYear-$toMonth-$toDay";
		
		$query=0;
		if($fromYear && $fromMonth && $fromDay && $toYear && $toMonth && $toDay) {
			$query=mysqlquery("select * from vl_samples where date(created)>='$from' and date(created)<='$to' order by created");
		} else {
			$query=mysqlquery("select * from vl_samples order by created");
		}
		if(mysqlnumrows($query)) {
			//flush the db
			mysqlquery("delete from vl_output_samplescsv");
			//insert the headers
			mysqlquery("insert into vl_output_samplescsv 
							(FormNumber,LocationID,SampleID,Facility,
								District,Hub,IP,DateofCollection,
								SampleType,PatientART,PatientOtherID,Gender,
								Age,PhoneNumber,HasPatientBeenontreatment,DateofTreatmentInitiation,
								CurrentRegimen,OtherRegimen,IndicationforTreatmentInitiation,WhichTreatmentLineisPatienton,ReasonforFailure,
								IsPatientPregnant,ANCNumber,IsPatientBreastfeeding,PatienthasActiveTB,
								IfYesaretheyon,ARVAdherence,RoutineMonitoring,LastViralLoadDate1,
								LastViralLoadValue1,SampleType1,RepeatViralLoadTest,LastViralLoadDate2,
								LastViralLoadValue2,SampleType2,SuspectedTreatmentFailure,LastViralLoadDate3,
								LastViralLoadValue3,SampleType3,Tested,LastWorksheet,
								MachineType,VLResult,DateTimeApproved,DateTimeRejected,
								RejectionReason,DateTimeAddedtoWorksheet,DateTimeLatestResultsUploaded,DateTimeResultsPrinted,
								DateReceivedatCPHL,DateTimeFirstPrinted,DateTimeSamplewasCaptured) 
							values 
							('Form Number','Location ID','Sample ID','Facility',
								'District','Hub','Implementing Partner','Date of Collection',
								'Sample Type','Patient ART','Patient OtherID','Gender',
								'Age (Years)','Phone Number','Has Patient Been on treatment for at least 6 months','Date of Treatment Initiation',
								'Current Regimen','Other Regimen','Indication for Treatment Initiation','Which Treatment Line is Patient on','Reason for Failure',
								'Is Patient Pregnant','ANC Number','Is Patient Breastfeeding','Patient has Active TB',
								'If Yes are they on','ARV Adherence','Routine Monitoring','Last Viral Load Date',
								'Value','Sample Type','Repeat Viral Load Test after Suspected Treatment Failure adherence counseling','Last Viral Load Date',
								'Value','Sample Type','Suspected Treatment Failure','Last Viral Load Date',
								'Value','Sample Type','Tested','Last Worksheet',
								'Machine Type','Result','Date/Time Approved',
								'Date/Time Rejected','Rejection Reason','Date/Time Added to Worksheet','Date/Time Latest Results Uploaded',
								'Date/Time Results Printed','Date Received at CPHL','Date/Time First Printed','Date/Time Sample was Captured')");
			//iterations
			while($q=mysqlfetcharray($query)) {
				//sample tested?
				$sampleTested=0;
				$machineType=0;
				$dateLatestResultsUploaded=0;
				$dateLatestResultsPrinted=0;
				//check if sample has been tested on Abbott
				if(getDetailedTableInfo2("vl_results_abbott","sampleID='$q[vlSampleID]' limit 1","id")) {
					$sampleTested="Yes";
					$dateLatestResultsUploaded=getDetailedTableInfo2("vl_results_abbott","sampleID='$q[vlSampleID]' order by created desc limit 1","created");
				} else {
					$sampleTested="No";
				}
				//check if sample has been printed
				if(getDetailedTableInfo2("vl_logs_printedresults","sampleID='$q[id]' and created!='' limit 1","id")) {
					$dateLatestResultsPrinted=getDetailedTableInfo2("vl_logs_printedresults","sampleID='$q[id]' and created!='' order by created desc limit 1","created");
				}
				if(!$dateLatestResultsPrinted) {
					$dateLatestResultsPrinted=getDetailedTableInfo2("vl_logs_printedrejectedresults","sampleID='$q[id]' and created!='' order by created desc limit 1","created");
				}
				//check if sample has been tested on Roche
				if($sampleTested=="No") {
					if(getDetailedTableInfo2("vl_results_roche","SampleID='$q[vlSampleID]' limit 1","id")) {
						$sampleTested="Yes";
						$dateLatestResultsUploaded=getDetailedTableInfo2("vl_results_roche","SampleID='$q[vlSampleID]' order by created desc limit 1","created");
					} else {
						$sampleTested="No";
					}
					if($sampleTested=="Yes") {
						$machineType="roche";
						$resultsTable="vl_results_roche";
						$sampleIDField="SampleID";
						$resultField="Result";
					}
				} else {
					if($sampleTested=="Yes") {
						$machineType="abbott";
						$resultsTable="vl_results_abbott";
						$sampleIDField="sampleID";
						$resultField="result";
					}
				}
				
				//last worksheet
				$lastWorksheetID=0;
				$lastWorksheetID=getDetailedTableInfo2("vl_samples_worksheet","sampleID='$q[id]' order by created desc limit 1","worksheetID");
				$lastWorksheetName=0;
				$lastWorksheetName=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$lastWorksheetID' limit 1","worksheetReferenceNumber");
				
				//factor
				$factor=1;
				$factor=getDetailedTableInfo2("vl_results_multiplicationfactor","worksheetID='$lastWorksheetID' limit 1","factor");
				
				//results
				$result=0;
				$result=getVLResult($machineType,$lastWorksheetID,$q["vlSampleID"],$factor);
				
				//date first printed
				$dateFirstPrinted=0;
				$dateFirstPrinted=getDetailedTableInfo2("vl_logs_printedresults","sampleID='$q[id]' order by created limit 1","created");
				if(!$dateFirstPrinted) {
					$dateFirstPrinted=getDetailedTableInfo2("vl_logs_printedrejectedresults","sampleID='$q[id]' order by created limit 1","created");
				}
				
				//insert the data
				mysqlquery("insert into vl_output_samplescsv 
								(FormNumber,LocationID,SampleID,Facility,
									District,Hub,IP,DateofCollection,
									SampleType,PatientART,PatientOtherID,Gender,
									Age,PhoneNumber,HasPatientBeenontreatment,DateofTreatmentInitiation,
									CurrentRegimen,OtherRegimen,IndicationforTreatmentInitiation,WhichTreatmentLineisPatienton,ReasonforFailure,
									IsPatientPregnant,ANCNumber,IsPatientBreastfeeding,PatienthasActiveTB,
									IfYesaretheyon,ARVAdherence,RoutineMonitoring,LastViralLoadDate1,
									LastViralLoadValue1,SampleType1,RepeatViralLoadTest,LastViralLoadDate2,
									LastViralLoadValue2,SampleType2,SuspectedTreatmentFailure,LastViralLoadDate3,
									LastViralLoadValue3,SampleType3,Tested,LastWorksheet,
									MachineType,VLResult,DateTimeApproved,DateTimeRejected,
									RejectionReason,DateTimeAddedtoWorksheet,DateTimeLatestResultsUploaded,DateTimeResultsPrinted,
									DateReceivedatCPHL,DateTimeFirstPrinted,DateTimeSamplewasCaptured) 
								values 
								('$q[formNumber]','$q[lrCategory]$q[lrEnvelopeNumber]/$q[lrNumericID]','$q[vlSampleID]','".preg_replace("/,/s","",getDetailedTableInfo2("vl_facilities","id='$q[facilityID]' limit 1","facility"))."',
									'".preg_replace("/,/s","",getDetailedTableInfo2("vl_districts","id='$q[districtID]' limit 1","district"))."','".preg_replace("/,/s","",getDetailedTableInfo2("vl_hubs","id='$q[hubID]' limit 1","hub"))."','".preg_replace("/,/s","",getDetailedTableInfo2("vl_ips","id='".getDetailedTableInfo2("vl_facilities","id='$q[facilityID]' limit 1","ipID")."' limit 1","ip"))."','".getRawFormattedDateLessDay($q["collectionDate"])."',
									'".preg_replace("/,/s","",getDetailedTableInfo2("vl_appendix_sampletype","id='$q[sampleTypeID]' limit 1","appendix"))."','".preg_replace("/,/s","",getDetailedTableInfo2("vl_patients","id='$q[patientID]' limit 1","artNumber"))."','".preg_replace("/,/s","",getDetailedTableInfo2("vl_patients","id='$q[patientID]' limit 1","otherID"))."','".preg_replace("/,/s","",getDetailedTableInfo2("vl_patients","id='$q[patientID]' limit 1","gender"))."',
									'".(getDateDifference(getDetailedTableInfo2("vl_patients","id='$q[patientID]' limit 1","dateOfBirth"),$datetime)?ceil(getDateDifference(getDetailedTableInfo2("vl_patients","id='$q[patientID]' limit 1","dateOfBirth"),$datetime)/365):"")."','".preg_replace("/,/s","",getDetailedTableInfo2("vl_patients_phone","patientID='$q[patientID]' order by created desc limit 1","phone"))."','$q[treatmentLast6Months]','".getRawFormattedDateLessDay($q["treatmentInitiationDate"])."',
									'".preg_replace("/,/s","",getDetailedTableInfo2("vl_appendix_regimen","id='$q[currentRegimenID]' limit 1","appendix"))."','".preg_replace("/,/s","",getDetailedTableInfo2("vl_samples_otherregimen","sampleID='$q[id]' and currentRegimenID='$q[currentRegimenID]' limit 1","otherRegimen"))."','".preg_replace("/,/s","",($q["treatmentInitiationID"]?getDetailedTableInfo2("vl_appendix_treatmentinitiation","id='$q[treatmentInitiationID]' limit 1","appendix"):$q["treatmentInitiationOther"]))."','".preg_replace("/,/s","",getDetailedTableInfo2("vl_appendix_treatmentstatus","id='$q[treatmentStatusID]' limit 1","appendix"))."','".preg_replace("/,/s","",getDetailedTableInfo2("vl_appendix_failurereason","id='$q[reasonForFailureID]' limit 1","appendix"))."',
									'$q[pregnant]','$q[pregnantANCNumber]','$q[breastfeeding]','$q[activeTBStatus]',
									'".preg_replace("/,/s","",getDetailedTableInfo2("vl_appendix_tbtreatmentphase","id='$q[tbTreatmentPhaseID]' limit 1","appendix"))."','".preg_replace("/,/s","",getDetailedTableInfo2("vl_appendix_arvadherence","id='$q[arvAdherenceID]' limit 1","appendix"))."','".($q["vlTestingRoutineMonitoring"]?"Yes":"No")."','".getRawFormattedDateLessDay($q["routineMonitoringLastVLDate"])."',
									'$q[routineMonitoringValue]','".preg_replace("/,/s","",getDetailedTableInfo2("vl_appendix_sampletype","id='$q[routineMonitoringSampleTypeID]' limit 1","appendix"))."','".($q["vlTestingRepeatTesting"]?"Yes":"No")."','".getRawFormattedDateLessDay($q["repeatVLTestLastVLDate"])."',
									'$q[repeatVLTestValue]','".preg_replace("/,/s","",getDetailedTableInfo2("vl_appendix_sampletype","id='$q[repeatVLTestSampleTypeID]' limit 1","appendix"))."','".($q["vlTestingSuspectedTreatmentFailure"]?"Yes":"No")."','".getRawFormattedDateLessDay($q["suspectedTreatmentFailureLastVLDate"])."',
									'$q[suspectedTreatmentFailureValue]','".preg_replace("/,/s","",getDetailedTableInfo2("vl_appendix_sampletype","id='$q[suspectedTreatmentFailureSampleTypeID]' limit 1","appendix"))."','$sampleTested','".preg_replace("/,/s","",$lastWorksheetName)."',
									'$machineType','".preg_replace("/,/s","",preg_replace("/&lt;/is","<",$result))."','".(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Accepted' and created!='' limit 1","id")?getRawFormattedDateLessDay(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Accepted' limit 1","created")):"")." ".(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Accepted' and created!='' limit 1","id")?getFormattedTimeLessS(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Accepted' limit 1","created")):"")."',
									'".(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Rejected' and created!='' limit 1","id")?getRawFormattedDateLessDay(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Rejected' limit 1","created")):"")." ".(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Rejected' and created!='' limit 1","id")?getFormattedTimeLessS(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Rejected' limit 1","created")):"")."',
									'".preg_replace("/'/s","\'",preg_replace("/,/s","",getDetailedTableInfo2("vl_appendix_samplerejectionreason","id='".getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Rejected' limit 1","outcomeReasonsID")."' limit 1","appendix")))."','".(getDetailedTableInfo2("vl_samples_worksheet","sampleID='$q[id]' and created!='' limit 1","id")?getRawFormattedDateLessDay(getDetailedTableInfo2("vl_samples_worksheet","sampleID='$q[id]' order by created desc limit 1","created")):"")." ".(getDetailedTableInfo2("vl_samples_worksheet","sampleID='$q[id]' and created!='' limit 1","id")?getFormattedTimeLessS(getDetailedTableInfo2("vl_samples_worksheet","sampleID='$q[id]' order by created desc limit 1","created")):"")."',
									'".($dateLatestResultsUploaded?getRawFormattedDateLessDay($dateLatestResultsUploaded):"")." ".($dateLatestResultsUploaded?getFormattedTimeLessS($dateLatestResultsUploaded):"")."','".($dateLatestResultsPrinted?getRawFormattedDateLessDay($dateLatestResultsPrinted):"")." ".($dateLatestResultsPrinted?getFormattedTimeLessS($dateLatestResultsPrinted):"")."',
									'".getRawFormattedDateLessDay($q["receiptDate"])." 10:00','".($dateFirstPrinted?getRawFormattedDateLessDay($dateFirstPrinted):"")." ".($dateFirstPrinted?getFormattedTimeLessS($dateFirstPrinted):"")."','".getRawFormattedDateLessDay($q["created"])." ".getFormattedTimeLessS($q["created"])."')");
			}
		}

		//to avoid conflicts, remove file if exists
		if(file_exists("$path"."$downloadFileName")) {
			unlink("$path"."$downloadFileName");
		}
		//export the file
		mysqlquery("select distinct * into outfile '$path"."$downloadFileName' FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' from vl_output_samplescsv");

		go("/reports/downloads/success/".vlEncrypt("$pathURL"."$downloadFileName")."/");
	break;
	case "samplesreceivedexcel":
		//filename of the excel file to be downloaded
		$filename=0;
		$filename="excel.samplesreceived.".getFormattedDateCRB($datetime).".xls";

		//create an instance of the class
		$xls = new ExportXLS($filename);

		//period
		$from=0;
		$from="$fromYear-$fromMonth-$fromDay";
		$to=0;
		$to="$toYear-$toMonth-$toDay";
		
		$query=0;
		if($fromYear && $fromMonth && $fromDay && $toYear && $toMonth && $toDay) {
			$query=mysqlquery("select * from vl_samples where date(created)>='$from' and date(created)<='$to' order by created");
		} else {
			$query=mysqlquery("select * from vl_samples order by created");
		}
		if(mysqlnumrows($query)) {
			//header
			$header=array();
			$header[]="Form Number";
			$header[]="Location ID";
			$header[]="Sample ID";
			$header[]="Facility";
			$header[]="District";
			$header[]="Hub";
			$header[]="Implementing Partner";
			$header[]="Date of Collection";
			$header[]="Sample Type";
			$header[]="Patient ART";
			$header[]="Patient OtherID";
			$header[]="Gender";
			$header[]="Age (Years)";
			$header[]="Phone Number";
			$header[]="Has Patient Been on treatment for at least 6 months";
			$header[]="Date of Treatment Initiation";
			$header[]="Current Regimen";
			$header[]="Other Regimen";
			$header[]="Indication for Treatment Initiation";
			$header[]="Which Treatment Line is Patient on";
			$header[]="Reason for Failure";
			$header[]="Is Patient Pregnant";
			$header[]="ANC Number";
			$header[]="Is Patient Breastfeeding";
			$header[]="Patient has Active TB";
			$header[]="If Yes are they on";
			$header[]="ARV Adherence";
			$header[]="Routine Monitoring";
			$header[]="Last Viral Load Date";
			$header[]="Value";
			$header[]="Sample Type";
			$header[]="Repeat Viral Load Test after Suspected Treatment Failure adherence counseling";
			$header[]="Last Viral Load Date";
			$header[]="Value";
			$header[]="Sample Type";
			$header[]="Suspected Treatment Failure";
			$header[]="Last Viral Load Date";
			$header[]="Value";
			$header[]="Sample Type";
			$header[]="Tested";
			$header[]="Last Worksheet";
			$header[]="Machine Type";
			$header[]="Result";
			$header[]="Date/Time Approved";
			$header[]="Date/Time Rejected";
			$header[]="Rejection Reason";
			$header[]="Date/Time Added to Worksheet";
			$header[]="Date/Time Latest Results Uploaded";
			$header[]="Date/Time Results Printed";
			$header[]="Date Received at CPHL";
			$header[]="Date/Time First Printed";
			$header[]="Date/Time Sample was Captured";
			$xls->addHeader($header);
			//iterations
			while($q=mysqlfetcharray($query)) {
				//sample tested?
				$sampleTested=0;
				$machineType=0;
				$dateLatestResultsUploaded=0;
				$dateLatestResultsPrinted=0;
				//check if sample has been tested on Abbott
				if(getDetailedTableInfo2("vl_results_abbott","sampleID='$q[vlSampleID]' limit 1","id")) {
					$sampleTested="Yes";
					$dateLatestResultsUploaded=getDetailedTableInfo2("vl_results_abbott","sampleID='$q[vlSampleID]' order by created desc limit 1","created");
				} else {
					$sampleTested="No";
				}
				//check if sample has been printed
				if(getDetailedTableInfo2("vl_logs_printedresults","sampleID='$q[id]' and created!='' limit 1","id")) {
					$dateLatestResultsPrinted=getDetailedTableInfo2("vl_logs_printedresults","sampleID='$q[id]' and created!='' order by created desc limit 1","created");
				}
				if(!$dateLatestResultsPrinted) {
					$dateLatestResultsPrinted=getDetailedTableInfo2("vl_logs_printedrejectedresults","sampleID='$q[id]' and created!='' order by created desc limit 1","created");
				}
				//check if sample has been tested on Roche
				if($sampleTested=="No") {
					if(getDetailedTableInfo2("vl_results_roche","SampleID='$q[vlSampleID]' limit 1","id")) {
						$sampleTested="Yes";
						$dateLatestResultsUploaded=getDetailedTableInfo2("vl_results_roche","SampleID='$q[vlSampleID]' order by created desc limit 1","created");
					} else {
						$sampleTested="No";
					}
					if($sampleTested=="Yes") {
						$machineType="roche";

						$resultsTable="vl_results_roche";
						$sampleIDField="SampleID";
						$resultField="Result";
					}
				} else {
					if($sampleTested=="Yes") {
						$machineType="abbott";
						$resultsTable="vl_results_abbott";
						$sampleIDField="sampleID";
						$resultField="result";
					}
				}
				
				//last worksheet
				$lastWorksheetID=0;
				$lastWorksheetID=getDetailedTableInfo2("vl_samples_worksheet","sampleID='$q[id]' order by created desc limit 1","worksheetID");
				$lastWorksheetName=0;
				$lastWorksheetName=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$lastWorksheetID' limit 1","worksheetReferenceNumber");
				
				//factor
				$factor=1;
				$factor=getDetailedTableInfo2("vl_results_multiplicationfactor","worksheetID='$lastWorksheetID' limit 1","factor");
				
				//results
				$result=0;
				$result=getVLResult($machineType,$lastWorksheetID,$q["vlSampleID"],$factor);
				
				//date first printed
				$dateFirstPrinted=0;
				$dateFirstPrinted=getDetailedTableInfo2("vl_logs_printedresults","sampleID='$q[id]' order by created limit 1","created");
				if(!$dateFirstPrinted) {
					$dateFirstPrinted=getDetailedTableInfo2("vl_logs_printedrejectedresults","sampleID='$q[id]' order by created limit 1","created");
				}
				
				//add rows
				$row=array();
				//Form Number
				$row[]="$q[formNumber]";
				//Location ID
				$row[]="$q[lrCategory]$q[lrEnvelopeNumber]/$q[lrNumericID]";
				//Sample ID
				$row[]=$q[vlSampleID];
				//Facility
				$row[]=getDetailedTableInfo2("vl_facilities","id='$q[facilityID]' limit 1","facility");
				//District
				$row[]=getDetailedTableInfo2("vl_districts","id='$q[districtID]' limit 1","district");
				//Hub
				$row[]=getDetailedTableInfo2("vl_hubs","id='$q[hubID]' limit 1","hub");
				//Implementing Partner
				$row[]=getDetailedTableInfo2("vl_ips","id='".getDetailedTableInfo2("vl_facilities","id='$q[facilityID]' limit 1","ipID")."' limit 1","ip");
				//Date of Collection
				$row[]=getRawFormattedDateLessDay($q["collectionDate"]);
				//Sample Type
				$row[]=getDetailedTableInfo2("vl_appendix_sampletype","id='$q[sampleTypeID]' limit 1","appendix");
				//Patient ART
				$row[]=getDetailedTableInfo2("vl_patients","id='$q[patientID]' limit 1","artNumber");
				//Patient OtherID
				$row[]=getDetailedTableInfo2("vl_patients","id='$q[patientID]' limit 1","otherID");
				//Gender
				$row[]=getDetailedTableInfo2("vl_patients","id='$q[patientID]' limit 1","gender");
				//Age (Years)
				$row[]=(getDateDifference(getDetailedTableInfo2("vl_patients","id='$q[patientID]' limit 1","dateOfBirth"),$datetime)?ceil(getDateDifference(getDetailedTableInfo2("vl_patients","id='$q[patientID]' limit 1","dateOfBirth"),$datetime)/365):"");
				//Phone Number
				$row[]=getDetailedTableInfo2("vl_patients_phone","patientID='$q[patientID]' order by created desc limit 1","phone");
				//Has Patient Been on treatment for at least 6 months
				$row[]=$q[treatmentLast6Months];
				//Date of Treatment Initiation
				$row[]=getRawFormattedDateLessDay($q["treatmentInitiationDate"]);
				//Current Regimen
				$row[]=getDetailedTableInfo2("vl_appendix_regimen","id='$q[currentRegimenID]' limit 1","appendix");
				//Other Regimen
				$row[]=getDetailedTableInfo2("vl_samples_otherregimen","sampleID='$q[id]' and currentRegimenID='$q[currentRegimenID]' limit 1","otherRegimen");
				//Indication for Treatment Initiation
				$row[]=($q["treatmentInitiationID"]?getDetailedTableInfo2("vl_appendix_treatmentinitiation","id='$q[treatmentInitiationID]' limit 1","appendix"):$q["treatmentInitiationOther"]);
				//Which Treatment Line is Patient on
				$row[]=getDetailedTableInfo2("vl_appendix_treatmentstatus","id='$q[treatmentStatusID]' limit 1","appendix");
				//Reason for Failure
				$row[]=getDetailedTableInfo2("vl_appendix_failurereason","id='$q[reasonForFailureID]' limit 1","appendix");
				//Is Patient Pregnant
				$row[]=$q[pregnant];
				//ANC Number
				$row[]=$q[pregnantANCNumber];
				//Is Patient Breastfeeding
				$row[]=$q[breastfeeding];
				//Patient has Active TB
				$row[]=$q[activeTBStatus];
				//If Yes are they on
				$row[]=getDetailedTableInfo2("vl_appendix_tbtreatmentphase","id='$q[tbTreatmentPhaseID]' limit 1","appendix");
				//ARV Adherence
				$row[]=getDetailedTableInfo2("vl_appendix_arvadherence","id='$q[arvAdherenceID]' limit 1","appendix");
				//Routine Monitoring
				$row[]=($q["vlTestingRoutineMonitoring"]?"Yes":"No");
				//Last Viral Load Date
				$row[]=getRawFormattedDateLessDay($q["routineMonitoringLastVLDate"]);
				//Value
				$row[]=$q[routineMonitoringValue];
				//Sample Type
				$row[]=getDetailedTableInfo2("vl_appendix_sampletype","id='$q[routineMonitoringSampleTypeID]' limit 1","appendix");
				//Repeat Viral Load Test after Suspected Treatment Failure adherence counseling
				$row[]=($q["vlTestingRepeatTesting"]?"Yes":"No");
				//Last Viral Load Date
				$row[]=getRawFormattedDateLessDay($q["repeatVLTestLastVLDate"]);
				//Value
				$row[]=$q[repeatVLTestValue];
				//Sample Type
				$row[]=getDetailedTableInfo2("vl_appendix_sampletype","id='$q[repeatVLTestSampleTypeID]' limit 1","appendix");
				//Suspected Treatment Failure
				$row[]=($q["vlTestingSuspectedTreatmentFailure"]?"Yes":"No");
				//Last Viral Load Date
				$row[]=getRawFormattedDateLessDay($q["suspectedTreatmentFailureLastVLDate"]);
				//Value
				$row[]=$q[suspectedTreatmentFailureValue];
				//Sample Type
				$row[]=getDetailedTableInfo2("vl_appendix_sampletype","id='$q[suspectedTreatmentFailureSampleTypeID]' limit 1","appendix");
				//Tested
				$row[]=$sampleTested;
				//Last Worksheet
				$row[]=$lastWorksheetName;
				//Machine Type
				$row[]=$machineType;
				//Result
				$row[]=preg_replace("/&lt;/is","<",$result);
				//Date/Time Approved
				$row[]=(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Accepted' and created!='' limit 1","id")?getRawFormattedDateLessDay(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Accepted' limit 1","created")):"")." ".(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Accepted' and created!='' limit 1","id")?getFormattedTimeLessS(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Accepted' limit 1","created")):"");
				//Date/Time Rejected
				$row[]=(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Rejected' and created!='' limit 1","id")?getRawFormattedDateLessDay(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Rejected' limit 1","created")):"")." ".(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Rejected' and created!='' limit 1","id")?getFormattedTimeLessS(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Rejected' limit 1","created")):"");
				//Rejection Reason
				$row[]=getDetailedTableInfo2("vl_appendix_samplerejectionreason","id='".getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Rejected' limit 1","outcomeReasonsID")."' limit 1","appendix");
				//Date/Time Added to Worksheet
				$row[]=(getDetailedTableInfo2("vl_samples_worksheet","sampleID='$q[id]' and created!='' limit 1","id")?getRawFormattedDateLessDay(getDetailedTableInfo2("vl_samples_worksheet","sampleID='$q[id]' order by created desc limit 1","created")):"")." ".(getDetailedTableInfo2("vl_samples_worksheet","sampleID='$q[id]' and created!='' limit 1","id")?getFormattedTimeLessS(getDetailedTableInfo2("vl_samples_worksheet","sampleID='$q[id]' order by created desc limit 1","created")):"");
				//Date/Time Latest Results Uploaded
				$row[]=($dateLatestResultsUploaded?getRawFormattedDateLessDay($dateLatestResultsUploaded):"")." ".($dateLatestResultsUploaded?getFormattedTimeLessS($dateLatestResultsUploaded):"");
				//Date/Time Results Printed
				$row[]=($dateLatestResultsPrinted?getRawFormattedDateLessDay($dateLatestResultsPrinted):"")." ".($dateLatestResultsPrinted?getFormattedTimeLessS($dateLatestResultsPrinted):"");
				//Date Received at CPHL
				$row[]=getRawFormattedDateLessDay($q["receiptDate"])." 10:00";
				//Date/Time First Printed
				$row[]=($dateFirstPrinted?getRawFormattedDateLessDay($dateFirstPrinted):"")." ".($dateFirstPrinted?getFormattedTimeLessS($dateFirstPrinted):"");
				//Date/Time Sample was Captured
				$row[]=getRawFormattedDateLessDay($q["created"])." ".getFormattedTimeLessS($q["created"]);
				$xls->addRow($row);
			}
			//output to browser
			$xls->sendFile();
		}
	break;
	case "facilitiesexcel":
		//filename of the excel file to be downloaded
		$filename=0;
		$filename="excel.facilities.".getFormattedDateCRB($datetime).".xls";

		//create an instance of the class
		$xls = new ExportXLS($filename);

		$query=0;
		$query=mysqlquery("select * from vl_facilities order by facility");

		if(mysqlnumrows($query)) {
			//header
			$header=array();
			$header[]="Facility";
			$header[]="District";
			$header[]="Hub";
			$header[]="Date Created";
			$xls->addHeader($header);
			//iterations
			while($q=mysqlfetcharray($query)) {
				//variables
				$facility=0;
				$facility=$q["facility"];
				$district=0;
				$district=getDetailedTableInfo2("vl_districts","id='$q[districtID]' limit 1","district");
				$hub=0;
				$hub=getDetailedTableInfo2("vl_hubs","id='$q[hubID]' limit 1","hub");
				$dateCreated=0;
				$dateCreated=getRawFormattedDateLessDay($q["created"]);
				
				//add rows
				$row=array();
				//facility
				$row[]=$facility;
				//district
				$row[]=$district;
				//hub
				$row[]=$hub;
				//date created
				$row[]=$dateCreated;
				//append
				$xls->addRow($row);
			}
			//output to browser
			$xls->sendFile();
		}
	break;
	case "multipleresultsexcel":
		//filename of the excel file to be downloaded
		$filename=0;
		$filename="excel.patientsmultipleresults.".getFormattedDateCRB($datetime).".xlsx";
		header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		
		//abbottResults
		$query=0;
		$query=mysqlquery("select patientID,facilityID,count(vlSampleID) num from vl_samples where vlSampleID in (select sampleID from vl_results_abbott) group by patientID having num>1 order by num desc");
		//header
		$headerAbbottResults = array(
			'Patient ART'=>'string',
			'Patient Other ID'=>'string',
			'Facility'=>'string',
			'Number of Results'=>'string',
			'Results'=>'string');
		//iterations
		$dataAbbottResults=array();
		if(mysqlnumrows($query)) {
			while($q=mysqlfetcharray($query)) {
				//patients from abbott
				$patientART=0;
				$patientART=getDetailedTableInfo2("vl_patients","id='$q[patientID]'","artNumber");
				$patientOtherID=0;
				$patientOtherID=getDetailedTableInfo2("vl_patients","id='$q[patientID]'","otherID");
				$facility=0;
				$facility=getDetailedTableInfo2("vl_facilities","id='$q[facilityID]'","facility");
				$numberResults=0;
				$numberResults=number_format((float)$q["num"]);
				//results from abbott
				$results="";
				$rquery=0;
				$rquery=mysqlquery("select * from vl_samples where patientID='$q[patientID]'");
				$rcount=0;
				$rnum=0;
				$rnum=mysqlnumrows($rquery);
				if($rnum) {
					while($rq=mysqlfetcharray($rquery)) {
						//key variables
						$rcount+=1;
						$worksheetID=0;
						$worksheetID=getDetailedTableInfo2("vl_results_abbott","sampleID='$rq[vlSampleID]' order by created desc limit 1","worksheetID");
						$factor=0;
						$factor=getDetailedTableInfo2("vl_results_multiplicationfactor","worksheetID='$worksheetID' limit 1","factor");
						if(!$factor) {
							$factor=1;
						}
						//results
						$results.=getVLResult("abbott",$worksheetID,$rq["vlSampleID"],$factor).($rcount<$rnum?", ":"");
					}
				}
				//xls
				$dataAbbottResults[]=array($patientART,$patientOtherID,$facility,$numberResults,$results);
			}
		}
		
		//rocheResults
		$query=0;
		$query=mysqlquery("select patientID,facilityID,count(vlSampleID) num from vl_samples where vlSampleID in (select SampleID from vl_results_roche) group by patientID having num>1 order by num desc");
		//header
		$headerRocheResults = array(
			'Patient ART'=>'string',
			'Patient Other ID'=>'string',
			'Facility'=>'string',
			'Number of Results'=>'string',
			'Results'=>'string');
		//iterations
		$dataRocheResults=array();
		if(mysqlnumrows($query)) {
			while($q=mysqlfetcharray($query)) {
				//patients from roche
				$patientART=0;
				$patientART=getDetailedTableInfo2("vl_patients","id='$q[patientID]'","artNumber");
				$patientOtherID=0;
				$patientOtherID=getDetailedTableInfo2("vl_patients","id='$q[patientID]'","otherID");
				$facility=0;
				$facility=getDetailedTableInfo2("vl_facilities","id='$q[facilityID]'","facility");
				$numberResults=0;
				$numberResults=number_format((float)$q["num"]);
				//results from roche
				$results="";
				$rquery=0;
				$rquery=mysqlquery("select * from vl_samples where patientID='$q[patientID]'");
				$rcount=0;
				$rnum=0;
				$rnum=mysqlnumrows($rquery);
				if($rnum) {
					while($rq=mysqlfetcharray($rquery)) {
						//key variables
						$rcount+=1;
						$worksheetID=0;
						$worksheetID=getDetailedTableInfo2("vl_results_roche","SampleID='$rq[vlSampleID]' order by created desc limit 1","worksheetID");
						$factor=0;
						$factor=getDetailedTableInfo2("vl_results_multiplicationfactor","worksheetID='$worksheetID' limit 1","factor");
						if(!$factor) {
							$factor=1;
						}
						//results
						$results.=getVLResult("roche",$worksheetID,$rq["vlSampleID"],$factor).($rcount<$rnum?", ":"");
					}
				}
				//xls
				$dataRocheResults[]=array($patientART,$patientOtherID,$facility,$numberResults,$results);
			}
		}

		//output to xlsx
		$writer = new XLSXWriter();
		$writer->setAuthor($default_institutionName);
		$writer->writeSheet($dataAbbottResults,"abbott",$headerAbbottResults);
		$writer->writeSheet($dataRocheResults,"roche",$headerRocheResults);
		$writer->writeToStdOut();
	break;
	case "clinicalrequestformsexcel":
		//filename of the excel file to be downloaded
		$filename=0;
		$filename="excel.clinical.request.forms.".getFormattedDateCRB($datetime).".xls";

		//create an instance of the class
		$xls = new ExportXLS($filename);

		//period
		$from=0;
		$from="$fromYear-$fromMonth-$fromDay";
		$to=0;
		$to="$toYear-$toMonth-$toDay";
		
		$query=0;
		if($fromYear && $fromMonth && $fromDay && $toYear && $toMonth && $toDay) {
			$query=mysqlquery("select * from vl_forms_clinicalrequest where date(created)>='$from' and date(created)<='$to' order by created");
		} else {
			$query=mysqlquery("select * from vl_forms_clinicalrequest order by created");
		}

		if(mysqlnumrows($query)) {
			//header
			$header=array();
			$header[]="Form Number";
			$header[]="Reference Number";
			$header[]="Destination Facility";
			$header[]="Date Dispatched";
			$header[]="Received from Facility";
			$header[]="Date Received";
			$header[]="Date Created";
			$xls->addHeader($header);
			//iterations
			while($q=mysqlfetcharray($query)) {
				//variables
				$formNumber=0;
				$formNumber=$q["formNumber"];
				$refNumber=0;
				$refNumber=$q["refNumber"];
				$facilityID=0;
				$facilityID=getDetailedTableInfo2("vl_forms_clinicalrequest_dispatch","refNumber='$q[refNumber]' limit 1","facilityID");
				$facility=0;
				$facility=($facilityID?getDetailedTableInfo2("vl_facilities","id='$facilityID' limit 1","facility"):"N/A");
				$dateDispatched=0;
				$dateDispatched=($facilityID?getRawFormattedDateLessDay(getDetailedTableInfo2("vl_forms_clinicalrequest_dispatch","refNumber='$q[refNumber]' limit 1","dispatchDate")):"N/A");
				$receivedFacilityID=0;
				$receivedFacilityID=getDetailedTableInfo2("vl_samples","formNumber='$formNumber' limit 1","facilityID");
				$receivedFacility=0;
				$receivedFacility=($receivedFacilityID?getDetailedTableInfo2("vl_facilities","id='$receivedFacilityID' limit 1","facility"):"N/A");
				$dateReceived=0;
				$dateReceived=($receivedFacilityID?getRawFormattedDateLessDay(getDetailedTableInfo2("vl_samples","formNumber='$formNumber' limit 1","receiptDate")):"N/A");
				$dateCreated=0;
				$dateCreated=getRawFormattedDateLessDay($q["created"]);
				
				//add rows
				$row=array();
				//form number
				$row[]=$formNumber;
				//reference number
				$row[]=$refNumber;
				//facility
				$row[]=$facility;
				//date dispatched
				$row[]=$dateDispatched;
				//Received from Facility
				$row[]=$receivedFacility;
				//Date Received
				$row[]=$dateReceived;
				//date created
				$row[]=$dateCreated;
				//append
				$xls->addRow($row);
			}
			//output to browser
			$xls->sendFile();
		}
	break;
}
?>
