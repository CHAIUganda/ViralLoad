<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

//validation
$worksheetID=validate($worksheetID);
$factor=validate($factor);

$type=0;
$type=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' order by created limit 1","machineType");
$machineType=0;
$machineType=$type;
//worksheet type returned as an ID e.g 1, 2, 3
$worksheetType=0;
$worksheetType=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","worksheetType");
$worksheetName=0;
$worksheetName=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","worksheetName");
$worksheetReferenceNumber=0;
$worksheetReferenceNumber=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","worksheetReferenceNumber");

if($uploadResults) {
	//validate data
	$error="";
	//is this Items's code is identical to another, decline it
	if(!$_FILES['userfile']['tmp_name']) {
		$error.="<br />No File Uploaded.<br />";
	}
	
	//input data
	if(!$error) {
		//has file been uploaded
		if(is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			//filename
			$fileOriginalName=0;
			$fileOriginalName=$_FILES['userfile']['name'];
			$fileOriginalName=addslashes($fileOriginalName);
			//temp name
			$tmpName=0;
			$tmpName=$_FILES['userfile']['tmp_name'];
			//size
			$fileSize=0;
			$fileSize=$_FILES['userfile']['size'];
			//type
			$fileType=0;
			$fileType=$_FILES['userfile']['type'];
			//extension
			$extension=0;
			$extension=ext($fileOriginalName);
			//check type
			if($type=="abbott") { //text files only, for abbott
				if($extension!="txt") {
					$error.="<br>Incorrect file extension '.$extension'. Try saving the document as a 'txt' file";
				}
			} elseif($type=="roche") { //csv files only, for abbott
				if($extension!="csv") {
					$error.="<br>Incorrect file extension '.$extension'. Try saving the document as a 'csv' file";
				}
			}
			if($_FILES['userfile']['size']>$default_maxUploadSize) {
				$error.="<br>File size is greater than the accepted ".number_format((float)$default_maxUploadSize/1000)." KB. Please reduce the size of the file then attempt to upload it again.";
			}
			if(!$error) {
				//added/modified
				$added=0;
				$modified=0;

				//log the factor
				$factorID=0;
				$factorID=getDetailedTableInfo2("vl_results_multiplicationfactor","worksheetID='$worksheetID' limit 1","id");
				if(!$factorID) {
					mysqlquery("insert into vl_results_multiplicationfactor 
									(worksheetID,factor,created,createdby) 
									values 
									('$worksheetID','$factor','$datetime','$trailSessionUser')");
				} else {
					logTableChange("vl_results_multiplicationfactor","worksheetID",$factorID,getDetailedTableInfo2("vl_results_multiplicationfactor","id='$factorID'","worksheetID"),$worksheetID);
					logTableChange("vl_results_multiplicationfactor","factor",$factorID,getDetailedTableInfo2("vl_results_multiplicationfactor","id='$factorID'","factor"),$factor);
			
					mysqlquery("update vl_results_multiplicationfactor set 
									worksheetID='$worksheetID', 
									factor='$factor' 
									where 
									id='$factorID'");
				}
				
				//roche, csv file
				if($type=="roche") {
					//load file 
					$file = fopen($_FILES['userfile']['tmp_name'], 'r');
					$count=0;
					while(($line = fgetcsv($file,0,","))!==FALSE) {
						$count+=1;
						//ignore the header
						if($count>1) { 
							$PatientName=0;
							$PatientName=$line[0];
							
							$PatientID=0;
							$PatientID=$line[1];
							
							$OrderNumber=0;
							$OrderNumber=$line[2];
							
							$OrderDateTime=0;
							$OrderDateTime=$line[3];
							
							$SampleID=0;
							$SampleID=$line[4];
							
							$SampleType=0;
							$SampleType=$line[5];
							
							$BatchID=0;
							$BatchID=$line[6];
							
							$Test=0;
							$Test=$line[7];
							
							$Result=0;
							$Result=$line[8];
							
							$Unit=0;
							$Unit=$line[9];
							
							$Flags=0;
							$Flags=$line[10];
							
							$AcceptedOp=0;
							$AcceptedOp=$line[11];
							
							$AcceptedDateTime=0;
							$AcceptedDateTime=$line[12];
							
							$Comment=0;
							$Comment=$line[13];
							
							$GeneralLotNumber=0;
							$GeneralLotNumber=$line[14];
							
							$GeneralLotExpirationDate=0;
							$GeneralLotExpirationDate=$line[15];
							
							$SamplePrepKitLotNumber=0;
							$SamplePrepKitLotNumber=$line[16];
							
							$SamplePrepKitLotExpirationDate=0;
							$SamplePrepKitLotExpirationDate=$line[17];
							
							$PCRKitLotNumber=0;
							$PCRKitLotNumber=$line[18];
							
							$PCRKitExpirationDate=0;
							$PCRKitExpirationDate=$line[19];
							
							$LPCLowLimit=0;
							$LPCLowLimit=$line[20];
							
							$LPCHighLimit=0;
							$LPCHighLimit=$line[21];
							
							$MPCLowLimit=0;
							$MPCLowLimit=$line[22];
							
							$MPCHighLimit=0;
							$MPCHighLimit=$line[23];
							
							$HPCLowLimit=0;
							$HPCLowLimit=$line[24];
							
							$HPCHighLimit=0;
							$HPCHighLimit=$line[25];
							
							$PreparationInstrumentID=0;
							$PreparationInstrumentID=$line[26];
							
							$PreparationStartDateTime=0;
							$PreparationStartDateTime=$line[27];
							
							$PreparationEndDateTime=0;
							$PreparationEndDateTime=$line[28];
							
							$PreparationRackPos=0;
							$PreparationRackPos=$line[29];
							
							$PreparationRackID=0;
							$PreparationRackID=$line[30];
							
							$PreparationRackType=0;
							$PreparationRackType=$line[31];
							
							$PreparationTubeID=0;
							$PreparationTubeID=$line[32];
							
							$PreparationTubeType=0;
							$PreparationTubeType=$line[33];
							
							$PreparationTubePos=0;
							$PreparationTubePos=$line[34];
							
							$PreparationBatchID=0;
							$PreparationBatchID=$line[35];
							
							$AmplificationInstrumentID=0;
							$AmplificationInstrumentID=$line[36];
							
							$AmplificationStartDateTime=0;
							$AmplificationStartDateTime=$line[37];
							
							$AmplificationEndDateTime=0;
							$AmplificationEndDateTime=$line[38];
							
							$AmplificationTCID=0;
							$AmplificationTCID=$line[39];
							
							$AmplificationRackID=0;
							$AmplificationRackID=$line[40];
							
							$AmplificationRackType=0;
							$AmplificationRackType=$line[41];
							
							$AmplificationTubeID=0;
							$AmplificationTubeID=$line[42];
							
							$AmplificationTubeType=0;
							$AmplificationTubeType=$line[43];
							
							$AmplificationTubePos=0;
							$AmplificationTubePos=$line[44];
							
							$AmplificationBatchID=0;
							$AmplificationBatchID=$line[45];
							
							$DetectionInstrumentID=0;
							$DetectionInstrumentID=$line[46];
							
							$DetectionStartDateTime=0;
							$DetectionStartDateTime=$line[47];
							
							$DetectionEndDateTime=0;
							$DetectionEndDateTime=$line[48];
							
							$DetectionRackPos=0;
							$DetectionRackPos=$line[49];
							
							$DetectionRackID=0;
							$DetectionRackID=$line[50];
							
							$DetectionRackType=0;
							$DetectionRackType=$line[51];
							
							$DetectionTubeID=0;
							$DetectionTubeID=$line[52];
							
							$DetectionTubeType=0;
							$DetectionTubeType=$line[53];
							
							$DetectionTubePos=0;
							$DetectionTubePos=$line[54];
							
							$DetectionBatchID=0;
							$DetectionBatchID=$line[55];
							
							$IngredientCH1=0;
							$IngredientCH1=$line[56];
							
							$IngredientCH2=0;
							$IngredientCH2=$line[57];
							
							$IngredientCH3=0;
							$IngredientCH3=$line[58];
							
							$IngredientCH4=0;
							$IngredientCH4=$line[59];
							
							$CTMElbowCH1=0;
							$CTMElbowCH1=$line[60];
							
							$CTMElbowCH2=0;
							$CTMElbowCH2=$line[61];
							
							$CTMElbowCH3=0;
							$CTMElbowCH3=$line[62];
							
							$CTMElbowCH4=0;
							$CTMElbowCH4=$line[63];
							
							$CTMRFICH1=0;
							$CTMRFICH1=$line[64];
							
							$CTMRFICH2=0;
							$CTMRFICH2=$line[65];
							
							$CTMRFICH3=0;
							$CTMRFICH3=$line[66];
							
							$CTMRFICH4=0;
							$CTMRFICH4=$line[67];
							
							$CTMAFICH1=0;
							$CTMAFICH1=$line[68];
							
							$CTMAFICH2=0;
							$CTMAFICH2=$line[69];
							
							$CTMAFICH3=0;
							$CTMAFICH3=$line[70];
							
							$CTMAFICH4=0;
							$CTMAFICH4=$line[71];
							
							$CTMCalibCoeffa=0;
							$CTMCalibCoeffa=$line[72];
							
							$CTMCalibCoeffb=0;
							$CTMCalibCoeffb=$line[73];
							
							$CTMCalibCoeffc=0;
							$CTMCalibCoeffc=$line[74];
							
							$CTMCalibCoeffd=0;
							$CTMCalibCoeffd=$line[75];
							
							$CASampleValue=0;
							$CASampleValue=$line[76];
							
							$QSCopy=0;
							$QSCopy=$line[77];
							
							$CATarget1=0;
							$CATarget1=$line[78];
							
							$CATarget2=0;
							$CATarget2=$line[79];
							
							$CATarget3=0;
							$CATarget3=$line[80];
							
							$CATarget4=0;
							$CATarget4=$line[81];
							
							$CATarget5=0;
							$CATarget5=$line[82];
							
							$CATarget6=0;
							$CATarget6=$line[83];
							
							$CAQS1=0;
							$CAQS1=$line[84];
							
							$CAQS2=0;
							$CAQS2=$line[85];
							
							$CAQS3=0;
							$CAQS3=$line[86];
							
							$CAQS4=0;
							$CAQS4=$line[87];
							
							//only insert records with sample IDs, not control samples
							if($SampleID) {
								//first time insertion
								if(!getDetailedTableInfo2("vl_results_roche","SampleID='$SampleID' and worksheetID='$worksheetID' limit 1","id")) {
									mysqlquery("insert into vl_results_roche 
													(worksheetID,
														PatientName,PatientID,OrderNumber,OrderDateTime,
														SampleID,SampleType,BatchID,Test,
														Result,Unit,Flags,AcceptedOp,
														AcceptedDateTime,Comment,GeneralLotNumber,GeneralLotExpirationDate,
														SamplePrepKitLotNumber,SamplePrepKitLotExpirationDate,PCRKitLotNumber,PCRKitExpirationDate,
														LPCLowLimit,LPCHighLimit,MPCLowLimit,MPCHighLimit,
														HPCLowLimit,HPCHighLimit,PreparationInstrumentID,PreparationStartDateTime,
														PreparationEndDateTime,PreparationRackPos,PreparationRackID,PreparationRackType,
														PreparationTubeID,PreparationTubeType,PreparationTubePos,PreparationBatchID,
														AmplificationInstrumentID,AmplificationStartDateTime,AmplificationEndDateTime,AmplificationTCID,
														AmplificationRackID,AmplificationRackType,AmplificationTubeID,AmplificationTubeType,
														AmplificationTubePos,AmplificationBatchID,DetectionInstrumentID,DetectionStartDateTime,
														DetectionEndDateTime,DetectionRackPos,DetectionRackID,DetectionRackType,
														DetectionTubeID,DetectionTubeType,DetectionTubePos,DetectionBatchID,
														IngredientCH1,IngredientCH2,IngredientCH3,IngredientCH4,
														CTMElbowCH1,CTMElbowCH2,CTMElbowCH3,CTMElbowCH4,
														CTMRFICH1,CTMRFICH2,CTMRFICH3,CTMRFICH4,
														CTMAFICH1,CTMAFICH2,CTMAFICH3,CTMAFICH4,
														CTMCalibCoeffa,CTMCalibCoeffb,CTMCalibCoeffc,CTMCalibCoeffd,
														CASampleValue,QSCopy,CATarget1,CATarget2,
														CATarget3,CATarget4,CATarget5,CATarget6,
														CAQS1,CAQS2,CAQS3,CAQS4,
														created,createdby)
													values 
													('$worksheetID',
														'$PatientName','$PatientID','$OrderNumber','$OrderDateTime',
														'$SampleID','$SampleType','$BatchID','$Test',
														'$Result','$Unit','$Flags','$AcceptedOp',
														'$AcceptedDateTime','$Comment','$GeneralLotNumber','$GeneralLotExpirationDate',
														'$SamplePrepKitLotNumber','$SamplePrepKitLotExpirationDate','$PCRKitLotNumber','$PCRKitExpirationDate',
														'$LPCLowLimit','$LPCHighLimit','$MPCLowLimit','$MPCHighLimit',
														'$HPCLowLimit','$HPCHighLimit','$PreparationInstrumentID','$PreparationStartDateTime',
														'$PreparationEndDateTime','$PreparationRackPos','$PreparationRackID','$PreparationRackType',
														'$PreparationTubeID','$PreparationTubeType','$PreparationTubePos','$PreparationBatchID',
														'$AmplificationInstrumentID','$AmplificationStartDateTime','$AmplificationEndDateTime','$AmplificationTCID',
														'$AmplificationRackID','$AmplificationRackType','$AmplificationTubeID','$AmplificationTubeType',
														'$AmplificationTubePos','$AmplificationBatchID','$DetectionInstrumentID','$DetectionStartDateTime',
														'$DetectionEndDateTime','$DetectionRackPos','$DetectionRackID','$DetectionRackType',
														'$DetectionTubeID','$DetectionTubeType','$DetectionTubePos','$DetectionBatchID',
														'$IngredientCH1','$IngredientCH2','$IngredientCH3','$IngredientCH4',
														'$CTMElbowCH1','$CTMElbowCH2','$CTMElbowCH3','$CTMElbowCH4',
														'$CTMRFICH1','$CTMRFICH2','$CTMRFICH3','$CTMRFICH4',
														'$CTMAFICH1','$CTMAFICH2','$CTMAFICH3','$CTMAFICH4',
														'$CTMCalibCoeffa','$CTMCalibCoeffb','$CTMCalibCoeffc','$CTMCalibCoeffd',
														'$CASampleValue','$QSCopy','$CATarget1','$CATarget2',
														'$CATarget3','$CATarget4','$CATarget5','$CATarget6',
														'$CAQS1','$CAQS2','$CAQS3','$CAQS4',
														'$datetime','$trailSessionUser')");
									//automatically log whether this sample should be repeated
									$rocheSampleID=0;
									$rocheSampleID=getDetailedTableInfo2("vl_samples","vlSampleID='$SampleID' limit 1","id");
									logRepeat("roche",$rocheSampleID,$worksheetID,$Result,$Flags);
									//log
									$added+=1;
								} else {
									$modify=0;
									$modify=getDetailedTableInfo2("vl_results_roche","SampleID='$SampleID' and worksheetID='$worksheetID' limit 1","id");
									
									//log the changes
									logTableChange("vl_results_roche","PatientName",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","PatientName"),$PatientName);
									logTableChange("vl_results_roche","PatientID",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","PatientID"),$PatientID);
									logTableChange("vl_results_roche","OrderNumber",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","OrderNumber"),$OrderNumber);
									logTableChange("vl_results_roche","OrderDateTime",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","OrderDateTime"),$OrderDateTime);
									logTableChange("vl_results_roche","SampleID",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","SampleID"),$SampleID);
									logTableChange("vl_results_roche","SampleType",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","SampleType"),$SampleType);
									logTableChange("vl_results_roche","BatchID",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","BatchID"),$BatchID);
									logTableChange("vl_results_roche","Test",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","Test"),$Test);
									logTableChange("vl_results_roche","Result",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","Result"),$Result);
									logTableChange("vl_results_roche","Unit",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","Unit"),$Unit);
									logTableChange("vl_results_roche","Flags",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","Flags"),$Flags);
									logTableChange("vl_results_roche","AcceptedOp",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","AcceptedOp"),$AcceptedOp);
									logTableChange("vl_results_roche","AcceptedDateTime",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","AcceptedDateTime"),$AcceptedDateTime);
									logTableChange("vl_results_roche","Comment",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","Comment"),$Comment);
									logTableChange("vl_results_roche","GeneralLotNumber",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","GeneralLotNumber"),$GeneralLotNumber);
									logTableChange("vl_results_roche","GeneralLotExpirationDate",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","GeneralLotExpirationDate"),$GeneralLotExpirationDate);
									logTableChange("vl_results_roche","SamplePrepKitLotNumber",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","SamplePrepKitLotNumber"),$SamplePrepKitLotNumber);
									logTableChange("vl_results_roche","SamplePrepKitLotExpirationDate",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","SamplePrepKitLotExpirationDate"),$SamplePrepKitLotExpirationDate);
									logTableChange("vl_results_roche","PCRKitLotNumber",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","PCRKitLotNumber"),$PCRKitLotNumber);
									logTableChange("vl_results_roche","PCRKitExpirationDate",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","PCRKitExpirationDate"),$PCRKitExpirationDate);
									logTableChange("vl_results_roche","LPCLowLimit",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","LPCLowLimit"),$LPCLowLimit);
									logTableChange("vl_results_roche","LPCHighLimit",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","LPCHighLimit"),$LPCHighLimit);
									logTableChange("vl_results_roche","MPCLowLimit",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","MPCLowLimit"),$MPCLowLimit);
									logTableChange("vl_results_roche","MPCHighLimit",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","MPCHighLimit"),$MPCHighLimit);
									logTableChange("vl_results_roche","HPCLowLimit",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","HPCLowLimit"),$HPCLowLimit);
									logTableChange("vl_results_roche","HPCHighLimit",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","HPCHighLimit"),$HPCHighLimit);
									logTableChange("vl_results_roche","PreparationInstrumentID",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","PreparationInstrumentID"),$PreparationInstrumentID);
									logTableChange("vl_results_roche","PreparationStartDateTime",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","PreparationStartDateTime"),$PreparationStartDateTime);
									logTableChange("vl_results_roche","PreparationEndDateTime",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","PreparationEndDateTime"),$PreparationEndDateTime);
									logTableChange("vl_results_roche","PreparationRackPos",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","PreparationRackPos"),$PreparationRackPos);
									logTableChange("vl_results_roche","PreparationRackID",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","PreparationRackID"),$PreparationRackID);
									logTableChange("vl_results_roche","PreparationRackType",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","PreparationRackType"),$PreparationRackType);
									logTableChange("vl_results_roche","PreparationTubeID",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","PreparationTubeID"),$PreparationTubeID);
									logTableChange("vl_results_roche","PreparationTubeType",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","PreparationTubeType"),$PreparationTubeType);
									logTableChange("vl_results_roche","PreparationTubePos",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","PreparationTubePos"),$PreparationTubePos);
									logTableChange("vl_results_roche","PreparationBatchID",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","PreparationBatchID"),$PreparationBatchID);
									logTableChange("vl_results_roche","AmplificationInstrumentID",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","AmplificationInstrumentID"),$AmplificationInstrumentID);
									logTableChange("vl_results_roche","AmplificationStartDateTime",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","AmplificationStartDateTime"),$AmplificationStartDateTime);
									logTableChange("vl_results_roche","AmplificationEndDateTime",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","AmplificationEndDateTime"),$AmplificationEndDateTime);
									logTableChange("vl_results_roche","AmplificationTCID",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","AmplificationTCID"),$AmplificationTCID);
									logTableChange("vl_results_roche","AmplificationRackID",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","AmplificationRackID"),$AmplificationRackID);
									logTableChange("vl_results_roche","AmplificationRackType",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","AmplificationRackType"),$AmplificationRackType);
									logTableChange("vl_results_roche","AmplificationTubeID",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","AmplificationTubeID"),$AmplificationTubeID);
									logTableChange("vl_results_roche","AmplificationTubeType",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","AmplificationTubeType"),$AmplificationTubeType);
									logTableChange("vl_results_roche","AmplificationTubePos",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","AmplificationTubePos"),$AmplificationTubePos);
									logTableChange("vl_results_roche","AmplificationBatchID",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","AmplificationBatchID"),$AmplificationBatchID);
									logTableChange("vl_results_roche","DetectionInstrumentID",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","DetectionInstrumentID"),$DetectionInstrumentID);
									logTableChange("vl_results_roche","DetectionStartDateTime",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","DetectionStartDateTime"),$DetectionStartDateTime);
									logTableChange("vl_results_roche","DetectionEndDateTime",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","DetectionEndDateTime"),$DetectionEndDateTime);
									logTableChange("vl_results_roche","DetectionRackPos",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","DetectionRackPos"),$DetectionRackPos);
									logTableChange("vl_results_roche","DetectionRackID",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","DetectionRackID"),$DetectionRackID);
									logTableChange("vl_results_roche","DetectionRackType",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","DetectionRackType"),$DetectionRackType);
									logTableChange("vl_results_roche","DetectionTubeID",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","DetectionTubeID"),$DetectionTubeID);
									logTableChange("vl_results_roche","DetectionTubeType",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","DetectionTubeType"),$DetectionTubeType);
									logTableChange("vl_results_roche","DetectionTubePos",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","DetectionTubePos"),$DetectionTubePos);
									logTableChange("vl_results_roche","DetectionBatchID",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","DetectionBatchID"),$DetectionBatchID);
									logTableChange("vl_results_roche","IngredientCH1",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","IngredientCH1"),$IngredientCH1);
									logTableChange("vl_results_roche","IngredientCH2",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","IngredientCH2"),$IngredientCH2);
									logTableChange("vl_results_roche","IngredientCH3",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","IngredientCH3"),$IngredientCH3);
									logTableChange("vl_results_roche","IngredientCH4",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","IngredientCH4"),$IngredientCH4);
									logTableChange("vl_results_roche","CTMElbowCH1",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CTMElbowCH1"),$CTMElbowCH1);
									logTableChange("vl_results_roche","CTMElbowCH2",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CTMElbowCH2"),$CTMElbowCH2);
									logTableChange("vl_results_roche","CTMElbowCH3",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CTMElbowCH3"),$CTMElbowCH3);
									logTableChange("vl_results_roche","CTMElbowCH4",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CTMElbowCH4"),$CTMElbowCH4);
									logTableChange("vl_results_roche","CTMRFICH1",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CTMRFICH1"),$CTMRFICH1);
									logTableChange("vl_results_roche","CTMRFICH2",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CTMRFICH2"),$CTMRFICH2);
									logTableChange("vl_results_roche","CTMRFICH3",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CTMRFICH3"),$CTMRFICH3);
									logTableChange("vl_results_roche","CTMRFICH4",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CTMRFICH4"),$CTMRFICH4);
									logTableChange("vl_results_roche","CTMAFICH1",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CTMAFICH1"),$CTMAFICH1);
									logTableChange("vl_results_roche","CTMAFICH2",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CTMAFICH2"),$CTMAFICH2);
									logTableChange("vl_results_roche","CTMAFICH3",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CTMAFICH3"),$CTMAFICH3);
									logTableChange("vl_results_roche","CTMAFICH4",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CTMAFICH4"),$CTMAFICH4);
									logTableChange("vl_results_roche","CTMCalibCoeffa",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CTMCalibCoeffa"),$CTMCalibCoeffa);
									logTableChange("vl_results_roche","CTMCalibCoeffb",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CTMCalibCoeffb"),$CTMCalibCoeffb);
									logTableChange("vl_results_roche","CTMCalibCoeffc",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CTMCalibCoeffc"),$CTMCalibCoeffc);
									logTableChange("vl_results_roche","CTMCalibCoeffd",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CTMCalibCoeffd"),$CTMCalibCoeffd);
									logTableChange("vl_results_roche","CASampleValue",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CASampleValue"),$CASampleValue);
									logTableChange("vl_results_roche","QSCopy",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","QSCopy"),$QSCopy);
									logTableChange("vl_results_roche","CATarget1",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CATarget1"),$CATarget1);
									logTableChange("vl_results_roche","CATarget2",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CATarget2"),$CATarget2);
									logTableChange("vl_results_roche","CATarget3",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CATarget3"),$CATarget3);
									logTableChange("vl_results_roche","CATarget4",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CATarget4"),$CATarget4);
									logTableChange("vl_results_roche","CATarget5",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CATarget5"),$CATarget5);
									logTableChange("vl_results_roche","CATarget6",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CATarget6"),$CATarget6);
									logTableChange("vl_results_roche","CAQS1",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CAQS1"),$CAQS1);
									logTableChange("vl_results_roche","CAQS2",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CAQS2"),$CAQS2);
									logTableChange("vl_results_roche","CAQS3",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CAQS3"),$CAQS3);
									logTableChange("vl_results_roche","CAQS4",$modify,getDetailedTableInfo2("vl_results_roche","id='$modify'","CAQS4"),$CAQS4);
									
									//implement the changes
									mysqlquery("update vl_results_roche set 
													worksheetID='$worksheetID', 
													PatientName='$PatientName', 
													PatientID='$PatientID', 
													OrderNumber='$OrderNumber', 
													OrderDateTime='$OrderDateTime', 
													SampleID='$SampleID', 
													SampleType='$SampleType', 
													BatchID='$BatchID', 
													Test='$Test', 
													Result='$Result', 
													Unit='$Unit', 
													Flags='$Flags', 
													AcceptedOp='$AcceptedOp', 
													AcceptedDateTime='$AcceptedDateTime', 
													Comment='$Comment', 
													GeneralLotNumber='$GeneralLotNumber', 
													GeneralLotExpirationDate='$GeneralLotExpirationDate', 
													SamplePrepKitLotNumber='$SamplePrepKitLotNumber', 
													SamplePrepKitLotExpirationDate='$SamplePrepKitLotExpirationDate', 
													PCRKitLotNumber='$PCRKitLotNumber', 
													PCRKitExpirationDate='$PCRKitExpirationDate', 
													LPCLowLimit='$LPCLowLimit', 
													LPCHighLimit='$LPCHighLimit', 
													MPCLowLimit='$MPCLowLimit', 
													MPCHighLimit='$MPCHighLimit', 
													HPCLowLimit='$HPCLowLimit', 
													HPCHighLimit='$HPCHighLimit', 
													PreparationInstrumentID='$PreparationInstrumentID', 
													PreparationStartDateTime='$PreparationStartDateTime', 
													PreparationEndDateTime='$PreparationEndDateTime', 
													PreparationRackPos='$PreparationRackPos', 
													PreparationRackID='$PreparationRackID', 
													PreparationRackType='$PreparationRackType', 
													PreparationTubeID='$PreparationTubeID', 
													PreparationTubeType='$PreparationTubeType', 
													PreparationTubePos='$PreparationTubePos', 
													PreparationBatchID='$PreparationBatchID', 
													AmplificationInstrumentID='$AmplificationInstrumentID', 
													AmplificationStartDateTime='$AmplificationStartDateTime', 
													AmplificationEndDateTime='$AmplificationEndDateTime', 
													AmplificationTCID='$AmplificationTCID', 
													AmplificationRackID='$AmplificationRackID', 
													AmplificationRackType='$AmplificationRackType', 
													AmplificationTubeID='$AmplificationTubeID', 
													AmplificationTubeType='$AmplificationTubeType', 
													AmplificationTubePos='$AmplificationTubePos', 
													AmplificationBatchID='$AmplificationBatchID', 
													DetectionInstrumentID='$DetectionInstrumentID', 
													DetectionStartDateTime='$DetectionStartDateTime', 
													DetectionEndDateTime='$DetectionEndDateTime', 
													DetectionRackPos='$DetectionRackPos', 
													DetectionRackID='$DetectionRackID', 
													DetectionRackType='$DetectionRackType', 
													DetectionTubeID='$DetectionTubeID', 
													DetectionTubeType='$DetectionTubeType', 
													DetectionTubePos='$DetectionTubePos', 
													DetectionBatchID='$DetectionBatchID', 
													IngredientCH1='$IngredientCH1', 
													IngredientCH2='$IngredientCH2', 
													IngredientCH3='$IngredientCH3', 
													IngredientCH4='$IngredientCH4', 
													CTMElbowCH1='$CTMElbowCH1', 
													CTMElbowCH2='$CTMElbowCH2', 
													CTMElbowCH3='$CTMElbowCH3', 
													CTMElbowCH4='$CTMElbowCH4', 
													CTMRFICH1='$CTMRFICH1', 
													CTMRFICH2='$CTMRFICH2', 
													CTMRFICH3='$CTMRFICH3', 
													CTMRFICH4='$CTMRFICH4', 
													CTMAFICH1='$CTMAFICH1', 
													CTMAFICH2='$CTMAFICH2', 
													CTMAFICH3='$CTMAFICH3', 
													CTMAFICH4='$CTMAFICH4', 
													CTMCalibCoeffa='$CTMCalibCoeffa', 
													CTMCalibCoeffb='$CTMCalibCoeffb', 
													CTMCalibCoeffc='$CTMCalibCoeffc', 
													CTMCalibCoeffd='$CTMCalibCoeffd', 
													CASampleValue='$CASampleValue', 
													QSCopy='$QSCopy', 
													CATarget1='$CATarget1', 
													CATarget2='$CATarget2', 
													CATarget3='$CATarget3', 
													CATarget4='$CATarget4', 
													CATarget5='$CATarget5', 
													CATarget6='$CATarget6', 
													CAQS1='$CAQS1', 
													CAQS2='$CAQS2', 
													CAQS3='$CAQS3', 
													CAQS4='$CAQS4' 
													where 
													id='$modify'");
									//automatically log whether this sample should be repeated
									$rocheSampleID=0;
									$rocheSampleID=getDetailedTableInfo2("vl_samples","vlSampleID='$SampleID' limit 1","id");
									logRepeat("roche",$rocheSampleID,$worksheetID,$Result,$Flags);
									//log
									$modified+=1;
								}
							}
						}
					}
					fclose($file);
				} elseif($type=="abbott") {
					$file = fopen($_FILES['userfile']['tmp_name'], 'r');
					$count=0;
					$beginLoad=0;
					$beginLoadLine=0;
					$runStartTime=0;
					$runCompletionTime=0;
					while(($line = fgets($file))!==FALSE) {
						if(substr(trim($line),0,14)=="RUN START TIME") {
							$runStartTime=preg_replace("/RUN START TIME	/is","",$line);
							$runStartTime=trim($runStartTime);
						}
						if(substr(trim($line),0,19)=="RUN COMPLETION TIME") {
							$runCompletionTime=preg_replace("/RUN COMPLETION TIME/is","",$line);
							$runCompletionTime=preg_replace("/dd\/MM\/yyyy/is","",$runCompletionTime);
							$runCompletionTime=trim($runCompletionTime);
						}
						/*
						* only process data after finding this line
						* SAMPLE LOCATION	SAMPLE ID	SAMPLE TYPE	ASSAY NAME	ASSAY VERSION	RESULT	INTERPRETATION	FLAGS	TARGET CYCLE NUMBER	IC CYCLE NUMBER	ERROR CODE/DESCRIPTION	ASSAY CALIBRATION TIME	REAGENT LOT NUMBER	REAGENT LOT EXPIRATION DATE	CONTROL LOT NUMBER	CONTROL EXPIRATION DATE	CONTROL RANGE	CALIBRATOR LOT NUMBER	CALIBRATOR EXPIRATION DATE	CALIBRATOR LOG CONCENTRATION	RESULT COMMENT	TARGET MR	IC MR
						*/
						$count+=1;
						if(trim($line)=="SAMPLE LOCATION	SAMPLE ID	SAMPLE TYPE	ASSAY NAME	ASSAY VERSION	RESULT	INTERPRETATION	FLAGS	TARGET CYCLE NUMBER	IC CYCLE NUMBER	ERROR CODE/DESCRIPTION	ASSAY CALIBRATION TIME	REAGENT LOT NUMBER	REAGENT LOT EXPIRATION DATE	CONTROL LOT NUMBER	CONTROL EXPIRATION DATE	CONTROL RANGE	CALIBRATOR LOT NUMBER	CALIBRATOR EXPIRATION DATE	CALIBRATOR LOG CONCENTRATION	RESULT COMMENT	TARGET MR	IC MR") {
							$beginLoad=1;
							$beginLoadLine=$count;
						}
						//ignore the header
						if($beginLoad && $count>$beginLoadLine) {
							//split line into its constituents
							$data=array();
							$data=preg_split("/[\t]+/", trim($line));
							 
							$sampleLocation=0;
							$sampleLocation=$data[0];
							
							$sampleID=0;
							$sampleID=$data[1];
							
							$sampleType=0;
							$sampleType=$data[2];
							
							$assayName=0;
							$assayName=$data[3];
							
							$assayVersion=0;
							$assayVersion=$data[4];
							
							$result=0;
							$result=$data[5];
							
							$interpretation=0;
							$interpretation=$data[6];
							
							$flags=0;
							$flags=$data[7];
							
							$targetCycleNumber=0;
							$targetCycleNumber=$data[8];
							
							$icCycleNumber=0;
							$icCycleNumber=$data[9];
							
							$errorCodeDescription=0;
							$errorCodeDescription=$data[10];
							
							$assayCalibrationTime=0;
							$assayCalibrationTime=$data[11];
							
							$reagentLotNumber=0;
							$reagentLotNumber=$data[12];
							
							$reagentLotExpirationDate=0;
							$reagentLotExpirationDate=$data[13];
							
							$controlLotNumber=0;
							$controlLotNumber=$data[14];
							
							$controlExpirationDate=0;
							$controlExpirationDate=$data[15];
							
							$controlRange=0;
							$controlRange=$data[16];
							
							$calibratorLotNumber=0;
							$calibratorLotNumber=$data[17];
							
							$calibratorExpirationDate=0;
							$calibratorExpirationDate=$data[18];
							
							$calibratorLogConcentration=0;
							$calibratorLogConcentration=$data[19];
							
							$resultComment=0;
							$resultComment=$data[20];
							
							$targetMR=0;
							$targetMR=$data[21];
							
							$icMR=0;
							$icMR=$data[22];
							
							//only insert records with sample IDs, not control samples
							if($sampleID && $sampleID!="Control") {
								//first time insertion
								if(!getDetailedTableInfo2("vl_results_abbott","sampleID='$sampleID' and worksheetID='$worksheetID' limit 1","id")) {
									mysqlquery("insert into vl_results_abbott 
													(worksheetID,
														sampleLocation,sampleID,sampleType,assayName,
														assayVersion,result,interpretation,flags,
														targetCycleNumber,icCycleNumber,errorCodeDescription,assayCalibrationTime,
														reagentLotNumber,reagentLotExpirationDate,controlLotNumber,controlExpirationDate,
														controlRange,calibratorLotNumber,calibratorExpirationDate,calibratorLogConcentration,
														resultComment,targetMR,icMR,
														created,createdby)
													values 
													('$worksheetID',
														'$sampleLocation','$sampleID','$sampleType','$assayName',
														'$assayVersion','$result','$interpretation','$flags',
														'$targetCycleNumber','$icCycleNumber','$errorCodeDescription','$assayCalibrationTime',
														'$reagentLotNumber','$reagentLotExpirationDate','$controlLotNumber','$controlExpirationDate',
														'$controlRange','$calibratorLotNumber','$calibratorExpirationDate','$calibratorLogConcentration',
														'$resultComment','$targetMR','$icMR',
														'$datetime','$trailSessionUser')");
									//automatically log whether this sample should be repeated
									$abbottSampleID=0;
									$abbottSampleID=getDetailedTableInfo2("vl_samples","vlSampleID='$sampleID' limit 1","id");
									logRepeat("abbott",$abbottSampleID,$worksheetID,$result,$flags);
									//log
									$added+=1;
								} else {
									$modify=0;
									$modify=getDetailedTableInfo2("vl_results_abbott","sampleID='$sampleID' and worksheetID='$worksheetID' limit 1","id");
									
									//log the changes
									logTableChange("vl_results_abbott","sampleLocation",$modify,getDetailedTableInfo2("vl_results_abbott","id='$modify'","sampleLocation"),$sampleLocation);
									logTableChange("vl_results_abbott","sampleID",$modify,getDetailedTableInfo2("vl_results_abbott","id='$modify'","sampleID"),$sampleID);
									logTableChange("vl_results_abbott","sampleType",$modify,getDetailedTableInfo2("vl_results_abbott","id='$modify'","sampleType"),$sampleType);
									logTableChange("vl_results_abbott","assayName",$modify,getDetailedTableInfo2("vl_results_abbott","id='$modify'","assayName"),$assayName);
									logTableChange("vl_results_abbott","assayVersion",$modify,getDetailedTableInfo2("vl_results_abbott","id='$modify'","assayVersion"),$assayVersion);
									logTableChange("vl_results_abbott","result",$modify,getDetailedTableInfo2("vl_results_abbott","id='$modify'","result"),$result);
									logTableChange("vl_results_abbott","interpretation",$modify,getDetailedTableInfo2("vl_results_abbott","id='$modify'","interpretation"),$interpretation);
									logTableChange("vl_results_abbott","flags",$modify,getDetailedTableInfo2("vl_results_abbott","id='$modify'","flags"),$flags);
									logTableChange("vl_results_abbott","targetCycleNumber",$modify,getDetailedTableInfo2("vl_results_abbott","id='$modify'","targetCycleNumber"),$targetCycleNumber);
									logTableChange("vl_results_abbott","icCycleNumber",$modify,getDetailedTableInfo2("vl_results_abbott","id='$modify'","icCycleNumber"),$icCycleNumber);
									logTableChange("vl_results_abbott","errorCodeDescription",$modify,getDetailedTableInfo2("vl_results_abbott","id='$modify'","errorCodeDescription"),$errorCodeDescription);
									logTableChange("vl_results_abbott","assayCalibrationTime",$modify,getDetailedTableInfo2("vl_results_abbott","id='$modify'","assayCalibrationTime"),$assayCalibrationTime);
									logTableChange("vl_results_abbott","reagentLotNumber",$modify,getDetailedTableInfo2("vl_results_abbott","id='$modify'","reagentLotNumber"),$reagentLotNumber);
									logTableChange("vl_results_abbott","reagentLotExpirationDate",$modify,getDetailedTableInfo2("vl_results_abbott","id='$modify'","reagentLotExpirationDate"),$reagentLotExpirationDate);
									logTableChange("vl_results_abbott","controlLotNumber",$modify,getDetailedTableInfo2("vl_results_abbott","id='$modify'","controlLotNumber"),$controlLotNumber);
									logTableChange("vl_results_abbott","controlExpirationDate",$modify,getDetailedTableInfo2("vl_results_abbott","id='$modify'","controlExpirationDate"),$controlExpirationDate);
									logTableChange("vl_results_abbott","controlRange",$modify,getDetailedTableInfo2("vl_results_abbott","id='$modify'","controlRange"),$controlRange);
									logTableChange("vl_results_abbott","calibratorLotNumber",$modify,getDetailedTableInfo2("vl_results_abbott","id='$modify'","calibratorLotNumber"),$calibratorLotNumber);
									logTableChange("vl_results_abbott","calibratorExpirationDate",$modify,getDetailedTableInfo2("vl_results_abbott","id='$modify'","calibratorExpirationDate"),$calibratorExpirationDate);
									logTableChange("vl_results_abbott","calibratorLogConcentration",$modify,getDetailedTableInfo2("vl_results_abbott","id='$modify'","calibratorLogConcentration"),$calibratorLogConcentration);
									logTableChange("vl_results_abbott","resultComment",$modify,getDetailedTableInfo2("vl_results_abbott","id='$modify'","resultComment"),$resultComment);
									logTableChange("vl_results_abbott","targetMR",$modify,getDetailedTableInfo2("vl_results_abbott","id='$modify'","targetMR"),$targetMR);
									logTableChange("vl_results_abbott","icMR",$modify,getDetailedTableInfo2("vl_results_abbott","id='$modify'","icMR"),$icMR);
									
									//implement the changes
									mysqlquery("update vl_results_abbott set 
													worksheetID='$worksheetID', 
													sampleLocation='$sampleLocation',
													sampleID='$sampleID',
													sampleType='$sampleType',
													assayName='$assayName',
													assayVersion='$assayVersion',
													result='$result',
													interpretation='$interpretation',
													flags='$flags',
													targetCycleNumber='$targetCycleNumber',
													icCycleNumber='$icCycleNumber',
													errorCodeDescription='$errorCodeDescription',
													assayCalibrationTime='$assayCalibrationTime',
													reagentLotNumber='$reagentLotNumber',
													reagentLotExpirationDate='$reagentLotExpirationDate',
													controlLotNumber='$controlLotNumber',
													controlExpirationDate='$controlExpirationDate',
													controlRange='$controlRange',
													calibratorLotNumber='$calibratorLotNumber',
													calibratorExpirationDate='$calibratorExpirationDate',
													calibratorLogConcentration='$calibratorLogConcentration',
													resultComment='$resultComment',
													targetMR='$targetMR',
													icMR='$icMR' 
													where 
													id='$modify'");
									//automatically log whether this sample should be repeated
									$abbottSampleID=0;
									$abbottSampleID=getDetailedTableInfo2("vl_samples","vlSampleID='$sampleID' limit 1","id");
									logRepeat("abbott",$abbottSampleID,$worksheetID,$result,$flags);
									//log
									$modified+=1;
								}
							}
						}
					}
					fclose($file);
					
					//log the $runStartTime and $runCompletionTime
					if(!getDetailedTableInfo2("vl_results_abbott_runtimes","worksheetID='$worksheetID' limit 1","id")) {
						mysqlquery("insert into vl_results_abbott_runtimes 
										(worksheetID,runStartTime,runCompletionTime,
											created,createdby)
										values 
										('$worksheetID','".getRawFormattedDateLessDay($runStartTime)." ".getFormattedTime($runStartTime)."','".getRawFormattedDateLessDay($runCompletionTime)." ".getFormattedTime($runCompletionTime)."',
											'$datetime','$trailSessionUser')");
					}
				}
			}
		} else {
			$error.="<br>File could not be uploaded. Consider contacting your Systems Administrator.";
		}

		//redirect
		go("/worksheets/success/".($added?$added:0)."/".($modified?$modified:0)."/");
	}
}
?>
<script Language="JavaScript" Type="text/javascript">
<!--
function validate(worksheets) {
	//check for missing information
	if(!document.worksheets.factor.value) {
		alert('Missing Mandatory Field: Factor by which results are to be multiplied');
		document.worksheets.factor.focus();
		return (false);
	}
	if(!document.worksheets.userfile.value) {
		alert('Missing Mandatory Field: Select File to Upload');
		return (false);
	}
	return (true);
}
//-->
</script>
<form name="worksheets" method="post" action="/worksheets/upload.results/<?=$worksheetID?>/" onsubmit="return validate(this)" enctype="multipart/form-data">
<table width="100%" border="0" class="vl">
          <? if($success) { ?>
            <tr>
                <td class="vl_success">Results Loaded Successfully!</td>
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
              <td class="toplinks" style="padding:0px 0px 10px 0px"><a class="toplinks" href="/dashboard/">HOME</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/worksheets/">WORKSHEETS</a></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                  <fieldset style="width: 85%">
                    <legend><strong>RESULT FILE SPECIFICATIONS</strong></legend>
                      <table width="100%" border="0" class="vl">
                      <tr>
                        <td width="20%" style="padding:0px 0px 5px 0px">Machine&nbsp;Name:</td>
                        <td width="80%" style="padding:0px 0px 5px 0px"><?=$worksheetName?></td>
                      </tr>
                      <tr>
                        <td style="padding:0px 0px 5px 0px">Reference Number:</td>
                        <td style="padding:0px 0px 5px 0px"><?=$worksheetReferenceNumber?></td>
                      </tr>
                      <tr>
                        <td style="padding:0px 0px 5px 0px">Machine Type:</td>
                        <td style="padding:0px 0px 5px 0px"><?=($machineType=="abbott"?"Abbott":"Roche")?></td>
                      </tr>
                      <tr>
                        <td style="padding:0px 0px 5px 0px">Worksheet Type:</td>
                        <td style="padding:0px 0px 5px 0px"><?=getDetailedTableInfo2("vl_appendix_sampletype","id='$worksheetType' limit 1","appendix")?></td>
                      </tr>
                        <tr>
                          <td>Multiply Results by:</td>
                          <td><input type="text" name="factor" id="factor" value="<?=($factor?$factor:1)?>" onkeyup="return isNumber(this,'1')" class="search_pre" size="5" maxlength="10" /></td>
                        </tr>
                        <tr>
                          <td>Select&nbsp;Results&nbsp;File&nbsp;<font class="vl_red">*</font></td>
                          <td><input name="userfile" type="file" class="search" size="28" /></td>
                        </tr>
                      </table>
                    </fieldset>
                </td>
            </tr>
            <tr>
              <td style="padding:10px 0px 0px 0px"><input type="submit" name="uploadResults" id="uploadResults" class="button" value="  Upload Results  " /></td>
            </tr>
            <tr>
	            <td style="padding:20px 0px 0px 0px"><a href="/worksheets/">Return to Worksheets</a> :: <a href="/dashboard/">Return to Dashboard</a></td>
            </tr>
          </table>
</form>