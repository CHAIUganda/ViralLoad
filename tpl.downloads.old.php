<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";

switch($options) {
	case "samplesreceivedcsv":
		//excel
		header("Content-type: application/vnd.ms-excel");
		header('Content-Disposition: attachment; filename="download.'.$options.'.csv"');
		
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
			//header
			$xls.="Form Number,Location ID,Sample ID,Facility,District,Hub,Date of Collection,Sample Type,Patient ART,Patient OtherID,Gender,Age (Years),Phone Number,Has Patient Been on treatment for at least 6 months,Date of Treatment Initiation,Current Regimen,Indication for Treatment Initiation,Which Treatment Line is Patient on,Reason for Failure,Is Patient Pregnant,ANC Number,Is Patient Breastfeeding,Patient has Active TB,If Yes are they on,ARV Adherence,Routine Monitoring,Last Viral Load Date,Value,Sample Type,Repeat Viral Load Test after Suspected Treatment Failure adherence counseling,Last Viral Load Date,Value,Sample Type,Suspected Treatment Failure,Last Viral Load Date,Value,Sample Type,Tested,Last Worksheet,Machine Type,Result,Date Approved,Date Rejected,Rejection Reason,Date Added to Worksheet,Date Latest Results Uploaded,Date Results Printed,Date Received at CPHL,Date First Printed\n";
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
				
				//xls
				$xls.="$q[formNumber],$q[lrCategory]$q[lrEnvelopeNumber]/$q[lrNumericID],$q[vlSampleID],".preg_replace("/,/s","",getDetailedTableInfo2("vl_facilities","id='$q[facilityID]' limit 1","facility")).",".preg_replace("/,/s","",getDetailedTableInfo2("vl_districts","id='$q[districtID]' limit 1","district")).",".preg_replace("/,/s","",getDetailedTableInfo2("vl_hubs","id='$q[hubID]' limit 1","hub")).",".getRawFormattedDateLessDay($q["collectionDate"]).",".preg_replace("/,/s","",getDetailedTableInfo2("vl_appendix_sampletype","id='$q[sampleTypeID]' limit 1","appendix")).",".preg_replace("/,/s","",getDetailedTableInfo2("vl_patients","id='$q[patientID]' limit 1","artNumber")).",".preg_replace("/,/s","",getDetailedTableInfo2("vl_patients","id='$q[patientID]' limit 1","otherID")).",".preg_replace("/,/s","",getDetailedTableInfo2("vl_patients","id='$q[patientID]' limit 1","gender")).",".ceil(getDateDifference(getDetailedTableInfo2("vl_patients","id='$q[patientID]' limit 1","dateOfBirth"),$datetime)/365).",".preg_replace("/,/s","",getDetailedTableInfo2("vl_patients_phone","patientID='$q[patientID]' order by created desc limit 1","phone")).",$q[treatmentLast6Months],".getRawFormattedDateLessDay($q["treatmentInitiationDate"]).",".preg_replace("/,/s","",getDetailedTableInfo2("vl_appendix_regimen","id='$q[currentRegimenID]' limit 1","appendix")).",".preg_replace("/,/s","",($q["treatmentInitiationID"]?getDetailedTableInfo2("vl_appendix_treatmentinitiation","id='$q[treatmentInitiationID]' limit 1","appendix"):$q["treatmentInitiationOther"])).",".preg_replace("/,/s","",getDetailedTableInfo2("vl_appendix_treatmentstatus","id='$q[treatmentStatusID]' limit 1","appendix")).",".preg_replace("/,/s","",getDetailedTableInfo2("vl_appendix_failurereason","id='$q[reasonForFailureID]' limit 1","appendix")).",$q[pregnant],$q[pregnantANCNumber],$q[breastfeeding],$q[activeTBStatus],".preg_replace("/,/s","",getDetailedTableInfo2("vl_appendix_tbtreatmentphase","id='$q[tbTreatmentPhaseID]' limit 1","appendix")).",".preg_replace("/,/s","",getDetailedTableInfo2("vl_appendix_arvadherence","id='$q[arvAdherenceID]' limit 1","appendix")).",".($q["vlTestingRoutineMonitoring"]?"Yes":"No").",".getRawFormattedDateLessDay($q["routineMonitoringLastVLDate"]).",$q[routineMonitoringValue],".preg_replace("/,/s","",getDetailedTableInfo2("vl_appendix_sampletype","id='$q[routineMonitoringSampleTypeID]' limit 1","appendix")).",".($q["vlTestingRepeatTesting"]?"Yes":"No").",".getRawFormattedDateLessDay($q["repeatVLTestLastVLDate"]).",$q[repeatVLTestValue],".preg_replace("/,/s","",getDetailedTableInfo2("vl_appendix_sampletype","id='$q[repeatVLTestSampleTypeID]' limit 1","appendix")).",".($q["vlTestingSuspectedTreatmentFailure"]?"Yes":"No").",".getRawFormattedDateLessDay($q["suspectedTreatmentFailureLastVLDate"]).",$q[suspectedTreatmentFailureValue],".preg_replace("/,/s","",getDetailedTableInfo2("vl_appendix_sampletype","id='$q[suspectedTreatmentFailureSampleTypeID]' limit 1","appendix")).",$sampleTested,".preg_replace("/,/s","",$lastWorksheetName).",$machineType,".preg_replace("/,/s","",preg_replace("/&lt;/is","<",$result)).",".(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Accepted' and created!='' limit 1","id")?getRawFormattedDateLessDay(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Accepted' limit 1","created")):"").",".(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Rejected' and created!='' limit 1","id")?getRawFormattedDateLessDay(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Rejected' limit 1","created")):"").",".preg_replace("/,/s","",getDetailedTableInfo2("vl_appendix_samplerejectionreason","id='".getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Rejected' limit 1","outcomeReasonsID")."' limit 1","appendix")).",".(getDetailedTableInfo2("vl_samples_worksheet","sampleID='$q[id]' and created!='' limit 1","id")?getRawFormattedDateLessDay(getDetailedTableInfo2("vl_samples_worksheet","sampleID='$q[id]' order by created desc limit 1","created")):"").",".($dateLatestResultsUploaded?getRawFormattedDateLessDay($dateLatestResultsUploaded):"").",".($dateLatestResultsPrinted?getRawFormattedDateLessDay($dateLatestResultsPrinted):"").",".getRawFormattedDateLessDay($q["receiptDate"]).",".($dateFirstPrinted?getRawFormattedDateLessDay($dateFirstPrinted):"")."\n";	
			}
		}

		echo $xls;
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
			$header[]="Date Approved";
			$header[]="Date Rejected";
			$header[]="Rejection Reason";
			$header[]="Date Added to Worksheet";
			$header[]="Date Latest Results Uploaded";
			$header[]="Date Results Printed";
			$header[]="Date Received at CPHL";
			$header[]="Date First Printed";
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
				$row[]=$q[formNumber];
				$row[]="$q[lrCategory]$q[lrEnvelopeNumber]/$q[lrNumericID]";
				$row[]=$q[vlSampleID];
				$row[]=getDetailedTableInfo2("vl_facilities","id='$q[facilityID]' limit 1","facility");
				$row[]=getDetailedTableInfo2("vl_districts","id='$q[districtID]' limit 1","district");
				$row[]=getDetailedTableInfo2("vl_hubs","id='$q[hubID]' limit 1","hub");
				$row[]=getRawFormattedDateLessDay($q["collectionDate"]);
				$row[]=getDetailedTableInfo2("vl_appendix_sampletype","id='$q[sampleTypeID]' limit 1","appendix");
				$row[]=getDetailedTableInfo2("vl_patients","id='$q[patientID]' limit 1","artNumber");
				$row[]=getDetailedTableInfo2("vl_patients","id='$q[patientID]' limit 1","otherID");
				$row[]=getDetailedTableInfo2("vl_patients","id='$q[patientID]' limit 1","gender");
				$row[]=ceil(getDateDifference(getDetailedTableInfo2("vl_patients","id='$q[patientID]' limit 1","dateOfBirth"),$datetime)/365);
				$row[]=getDetailedTableInfo2("vl_patients_phone","patientID='$q[patientID]' order by created desc limit 1","phone");
				$row[]=$q[treatmentLast6Months];
				$row[]=getRawFormattedDateLessDay($q["treatmentInitiationDate"]);
				$row[]=getDetailedTableInfo2("vl_appendix_regimen","id='$q[currentRegimenID]' limit 1","appendix");
				$row[]=($q["treatmentInitiationID"]?getDetailedTableInfo2("vl_appendix_treatmentinitiation","id='$q[treatmentInitiationID]' limit 1","appendix"):$q["treatmentInitiationOther"]);
				$row[]=getDetailedTableInfo2("vl_appendix_treatmentstatus","id='$q[treatmentStatusID]' limit 1","appendix");
				$row[]=getDetailedTableInfo2("vl_appendix_failurereason","id='$q[reasonForFailureID]' limit 1","appendix");
				$row[]=$q[pregnant];
				$row[]=$q[pregnantANCNumber];
				$row[]=$q[breastfeeding];
				$row[]=$q[activeTBStatus];
				$row[]=getDetailedTableInfo2("vl_appendix_tbtreatmentphase","id='$q[tbTreatmentPhaseID]' limit 1","appendix");
				$row[]=getDetailedTableInfo2("vl_appendix_arvadherence","id='$q[arvAdherenceID]' limit 1","appendix");
				$row[]=($q["vlTestingRoutineMonitoring"]?"Yes":"No");
				$row[]=getRawFormattedDateLessDay($q["routineMonitoringLastVLDate"]);
				$row[]=$q[routineMonitoringValue];
				$row[]=getDetailedTableInfo2("vl_appendix_sampletype","id='$q[routineMonitoringSampleTypeID]' limit 1","appendix");
				$row[]=($q["vlTestingRepeatTesting"]?"Yes":"No");
				$row[]=getRawFormattedDateLessDay($q["repeatVLTestLastVLDate"]);
				$row[]=$q[repeatVLTestValue];
				$row[]=getDetailedTableInfo2("vl_appendix_sampletype","id='$q[repeatVLTestSampleTypeID]' limit 1","appendix");
				$row[]=($q["vlTestingSuspectedTreatmentFailure"]?"Yes":"No");
				$row[]=getRawFormattedDateLessDay($q["suspectedTreatmentFailureLastVLDate"]);
				$row[]=$q[suspectedTreatmentFailureValue];
				$row[]=getDetailedTableInfo2("vl_appendix_sampletype","id='$q[suspectedTreatmentFailureSampleTypeID]' limit 1","appendix");
				$row[]=$sampleTested;
				$row[]=$lastWorksheetName;
				$row[]=$machineType;
				$row[]=preg_replace("/&lt;/is","<",$result);
				$row[]=(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Accepted' and created!='' limit 1","id")?getRawFormattedDateLessDay(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Accepted' limit 1","created")):"");
				$row[]=(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Rejected' and created!='' limit 1","id")?getRawFormattedDateLessDay(getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Rejected' limit 1","created")):"");
				$row[]=getDetailedTableInfo2("vl_appendix_samplerejectionreason","id='".getDetailedTableInfo2("vl_samples_verify","sampleID='$q[id]' and outcome='Rejected' limit 1","outcomeReasonsID")."' limit 1","appendix");
				$row[]=(getDetailedTableInfo2("vl_samples_worksheet","sampleID='$q[id]' and created!='' limit 1","id")?getRawFormattedDateLessDay(getDetailedTableInfo2("vl_samples_worksheet","sampleID='$q[id]' order by created desc limit 1","created")):"");
				$row[]=($dateLatestResultsUploaded?getRawFormattedDateLessDay($dateLatestResultsUploaded):"");
				$row[]=($dateLatestResultsPrinted?getRawFormattedDateLessDay($dateLatestResultsPrinted):"");
				$row[]=getRawFormattedDateLessDay($q["receiptDate"]);
				$row[]=($dateFirstPrinted?getRawFormattedDateLessDay($dateFirstPrinted):"");
				$xls->addRow($row);
			}
			//output to browser
			$xls->sendFile();
		}
	break;
}
?>
