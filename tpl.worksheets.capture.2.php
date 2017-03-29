<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

$worksheetID=validate($worksheetID);
$machineType=0;
$machineType=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","machineType");
$includeCalibrators=0;
$includeCalibrators=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","includeCalibrators");
$tye=0;
$type=$machineType;
//worksheet type returned as an ID e.g 1, 2, 3
$worksheetType=0;
$worksheetType=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","worksheetType");
/*
* 8/Sept/14: 
* Hellen (hnansumba@gmail.com, CPHL) requested the Worksheet Name be removed given it is
* a duplicate of the Worksheet Reference Number
*/
//$worksheetName=0;
//$worksheetName=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","worksheetName");
$worksheetReferenceNumber=0;
$worksheetReferenceNumber=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","worksheetReferenceNumber");

//create worksheet
if($proceed) {
	if(count($no_spots_samples)>0){
		define("NO_SPOTS_RES","Invalid test result. There is insufficient sample to repeat the assay");
		foreach($no_spots_samples AS $s_id){
			$data=array("sampleID"=>$s_id,
						"worksheetID"=>$worksheetID,
						"result"=>NO_SPOTS_RES,
						"created"=>$datetime,
						"createdby"=>$trailSessionUser);
			insertData($data,"vl_results_override");
		}
	}

	if(count($worksheetSamples)) {
		//first delete all samples from this worksheet
		mysqlquery("delete from vl_samples_worksheet where worksheetID='$worksheetID'");
		$insert_sql = "";
		foreach($worksheetSamples as $ws) {
			$ws=validate($ws);
			$insert_sql .= "('$worksheetID','$ws','$datetime','$trailSessionUser'),";
		}
		$insert_sql = trim($insert_sql, ",");
		
		mysqlquery("insert into vl_samples_worksheet 
							(worksheetID,sampleID,created,createdby) 
							values $insert_sql
							");
		//redirect
		if($modify) {
			go("/worksheets/manage/changed/");
		} else {
			go("/worksheets/manage/print/$worksheetID/");
		}
	}
}

$controls=0;
$calibrators=0;
if($machineType=="roche") {
	$controls=$default_numberControlsRoche;
	$columns=6;
	$calibrators=$default_numberCalibratorsRoche;
} else {
	$machineType=="abbott";
	$controls=$default_numberControlsAbbott;
	$columns=8;
	$calibrators=$default_numberCalibratorsAbbott;
}

//prepare the contents
$contents=array();
$failedcontents=array();

//controls
for($i=1;$i<=$controls;$i++) {
	if($i==1) {
    	$pc="<div align=\"center\" style=\"padding: 20px 0px 2px 0px\" class=\"vl\">1</div>
				<div align=\"center\" style=\"padding: 2px 0px 20px 0px\">Negative Control<br><strong>NC</strong></div>";
	} elseif($i==2) {
		$pc="<div align=\"center\" style=\"padding: 20px 0px 2px 0px\" class=\"vl\">2</div>
				<div align=\"center\" style=\"padding: 2px 0px 20px 0px\">Positive Control<br><strong>PC</strong></div>";
	} elseif($i==3) {
		$pc="<div align=\"center\" style=\"padding: 20px 0px 2px 0px\" class=\"vl\">3</div>
				<div align=\"center\" style=\"padding: 2px 0px 20px 0px\"><strong>HPC</strong></div>";
	}
	$contents[]=$pc;
}

//calibrators
if($includeCalibrators) {
	$totalContents=0;
	$totalContents=count($contents)+1;
	for($i=$totalContents;$i<=(($totalContents+$calibrators)-1);$i++) {
		$pc="<div align=\"center\" style=\"padding: 20px 0px 2px 0px\" class=\"vl\">$i</div>
					<div align=\"center\" style=\"padding: 2px 0px 20px 0px\"><strong>Calibrator</strong></div>";
		$contents[]=$pc;
	}
}

//eligible samples
$query=0;
//$query=mysqlquery("select distinct sampleID from vl_samples_verify,vl_samples where vl_samples.id=vl_samples_verify.sampleID and vl_samples.sampleTypeID='$worksheetType' and vl_samples_verify.sampleID not in (select distinct sampleID from vl_samples_worksheet) and vl_samples_verify.outcome='Accepted' order by if(vl_samples.lrCategory='',1,0),vl_samples.lrCategory, if(vl_samples.lrEnvelopeNumber='',1,0),vl_samples.lrEnvelopeNumber, if(vl_samples.lrNumericID='',1,0),vl_samples.lrNumericID");

$cols = "s.id, s.vlSampleID, p.artNumber, s.formNumber, s.lrCategory, s.lrEnvelopeNumber, s.lrNumericID";

$more_sql ="FROM vl_samples AS s
			LEFT JOIN vl_patients AS p ON s.patientID=p.id
			LEFT JOIN vl_samples_verify AS v ON s.id=v.sampleID 
			LEFT JOIN vl_samples_worksheet AS sw ON s.id=sw.sampleID
			WHERE v.outcome='Accepted' AND sw.id IS NULL AND s.sampleTypeID='$worksheetType'";

$num_pending_testing = 0;

$num_pending_result0 = mysqlquery("SELECT count( DISTINCT s.id) AS c $more_sql");
if(mysqlnumrows($num_pending_result0)){
	$n_row = mysqlfetcharray($num_pending_result0);
	$num_pending_testing = $n_row['c'] ." samples pending testing";
}

$sql = "SELECT $cols $more_sql 		
		GROUP BY s.id
		ORDER BY lrCategory,lrEnvelopeNumber,lrNumericID ASC
		LIMIT 400";

$query = mysqlquery($sql);
if(mysqlnumrows($query)) {
	$i=count($contents);
	while($q=mysqlfetcharray($query)) {
		//controls
		$i+=1;
		//key variables
		/*$sampleID=0;
		$sampleID=$q["sampleID"];
		$patientID=0;
		$patientID=getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","patientID");
		$sampleNumber=0;
		$sampleNumber=getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","vlSampleID");
		$sampleType=0;
		$sampleType=getDetailedTableInfo2("vl_appendix_sampletype","id='".getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","sampleTypeID")."' limit 1","appendix");
		$locationID=0;
		$locationID=getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","lrCategory").getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","lrEnvelopeNumber").(getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","lrNumericID")?"/".getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","lrNumericID"):"");
		$formNumber=0;
		$formNumber=getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","formNumber");
		$patientART=0;
		$patientART=getDetailedTableInfo2("vl_patients","id='$patientID' limit 1","artNumber");*/

		$sampleID = $q['id'];
		$sampleNumber = $q['vlSampleID'];
		$locationID = $q['lrCategory'].$q['lrEnvelopeNumber'].'/'.$q['lrNumericID'];
		$formNumber = $q['formNumber'];
		$patientART = $q['artNumber'];
		
		$contents[]="<div align=\"center\" class=\"vls\">$i</div>
						<div align=\"center\" class=\"vls\" style=\"padding:3px 0px 0px 0px\">Patient ART #: $patientART</div> 
						<div align=\"center\" class=\"vls\" style=\"padding:1px 0px 0px 0px\">Sample #: $sampleNumber</div> 
						".($locationID?"<div align=\"center\" class=\"vls\" style=\"padding:1px 0px 0px 0px\">Location ID: $locationID</div> ":"")."
						<div align=\"center\" class=\"vls\" style=\"padding:1px 0px 0px 0px\">Form #: $formNumber</div> 
						<div align=\"center\" style=\"padding:5px 0px\"><img src=\"/worksheets/image/".vlEncrypt($sampleNumber)."/\" /></div>
						<div align=\"center\"><input type=\"checkbox\" class='samples' name=\"worksheetSamples[]\" id=\"worksheetSamples[]\" value=\"$sampleID\" onclick=\"updateCounter(this)\" /></div>";
	}
}

//rejected samples that have never been accepted in subsequent runs
$query=0;
/*$query=mysqlquery("select vl_logs_samplerepeats.* from 
							vl_logs_samplerepeats,vl_samples where 
								vl_logs_samplerepeats.sampleID=vl_samples.id and 
									vl_samples.sampleTypeID='$worksheetType' and 
										vl_logs_samplerepeats.withWorksheetID='' and 
											vl_samples.vlSampleID not in (select distinct sampleID from vl_results_override) 
												order by if(vl_samples.lrCategory='',1,0),vl_samples.lrCategory, if(vl_samples.lrEnvelopeNumber='',1,0),vl_samples.lrEnvelopeNumber, if(vl_samples.lrNumericID='',1,0),vl_samples.lrNumericID");
*/

$cols = "s.id, s.vlSampleID, p.artNumber, s.formNumber, s.lrCategory, s.lrEnvelopeNumber, s.lrNumericID";

$more_sql ="FROM vl_logs_samplerepeats AS rpt
			LEFT JOIN vl_samples AS s ON rpt.sampleID = s.id
			LEFT JOIN vl_patients AS p ON s.patientID=p.id
			LEFT JOIN vl_results_override AS ovr ON s.vlSampleID=ovr.sampleID
			WHERE rpt.withWorksheetID = '' AND s.sampleTypeID='$worksheetType' AND ovr.id IS NULL";

/*$num_pending_retesting = 0;
$num_pending_result1 = mysqlquery("SELECT count(DISTINCT s.id) AS c $more_sql");
if(mysqlnumrows($num_pending_result1)) $num_pending_retesting =  mysqlfetcharray($num_pending_result1)['c']." samples pending retesting";
*/
$sql = "SELECT $cols $more_sql		
		ORDER BY lrCategory,lrEnvelopeNumber,lrNumericID ASC
		";
$query = mysqlquery($sql);

if(mysqlnumrows($query)) {
	$i=0;
	while($q=mysqlfetcharray($query)) {
		//controls
		$i+=1;
		//key variables
		/*$sampleID=0;
		$sampleID=getDetailedTableInfo2("vl_samples","id='$q[sampleID]' limit 1","vlSampleID");
		$patientID=0;
		$patientID=getDetailedTableInfo2("vl_samples","vlSampleID='$sampleID' limit 1","patientID");
		$sampleNumber=0;
		$sampleNumber=$sampleID;
		$sampleType=0;
		$sampleType=getDetailedTableInfo2("vl_appendix_sampletype","id='".getDetailedTableInfo2("vl_samples","vlSampleID='$sampleID' limit 1","sampleTypeID")."' limit 1","appendix");
		$locationID=0;
		$locationID=getDetailedTableInfo2("vl_samples","vlSampleID='$sampleID' limit 1","lrCategory").getDetailedTableInfo2("vl_samples","vlSampleID='$sampleID' limit 1","lrEnvelopeNumber").(getDetailedTableInfo2("vl_samples","vlSampleID='$sampleID' limit 1","lrNumericID")?"/".getDetailedTableInfo2("vl_samples","vlSampleID='$sampleID' limit 1","lrNumericID"):"");
		$formNumber=0;
		$formNumber=getDetailedTableInfo2("vl_samples","vlSampleID='$sampleID' limit 1","formNumber");
		$patientART=0;
		$patientART=getDetailedTableInfo2("vl_patients","id='$patientID' limit 1","artNumber");

		$smpl_id=getDetailedTableInfo2("vl_samples","vlSampleID='$sampleID' limit 1","id");*/

		$smpl_id = $q['id'];
		$sampleNumber = $q['vlSampleID'];
		$locationID = $q['lrCategory'].$q['lrEnvelopeNumber'].'/'.$q['lrNumericID'];
		$formNumber = $q['formNumber'];
		$patientART = $q['artNumber'];

		$stLikeRadio="setLikeRadioOldSmpls(\"$smpl_id\",\"n\")";
		$stLikeRadio2="setLikeRadioOldSmpls(\"$smpl_id\",\"r\")";
		
		$failedcontents[]="<div align=\"center\" class=\"vls\">$i</div>
						<div align=\"center\" class=\"vls\" style=\"padding:3px 0px 0px 0px\">Patient ART #: $patientART</div> 
						<div align=\"center\" class=\"vls\" style=\"padding:1px 0px 0px 0px\">Sample #: $sampleNumber</div> 
						".($locationID?"<div align=\"center\" class=\"vls\" style=\"padding:1px 0px 0px 0px\">Location ID: $locationID</div> ":"")."
						<div align=\"center\" class=\"vls\" style=\"padding:1px 0px 0px 0px\">Form #: $formNumber</div> 
						<div align=\"center\" style=\"padding:5px 0px\"><img src=\"/worksheets/image/".vlEncrypt($sampleNumber)."/\" /></div>
						<div align=\"center\" class='check-boxes'>
							<label><input type=\"checkbox\" onchange='$stLikeRadio2' class='samples' name=\"worksheetSamples[]\" id=\"r$smpl_id\" value=\"".$smpl_id."\" onclick=\"updateCounter(this)\" /> <span>retest</span></label>
							<label><input type='checkbox' onchange='$stLikeRadio' id='n$smpl_id' name='no_spots_samples[]' value='$sampleNumber'> <span>no spots</span></label>
						</div>";
	}
}
?>
<script Language="JavaScript" Type="text/javascript">
<!--
function updateCounter(obj) {
	if(obj.checked) {
		//get current count
		var count=+document.worksheets.numberSamples.value;
		count+=1;
		document.worksheets.numberSamples.value=count;
		//display update
		document.getElementById('selectedSamplesID').innerHTML='Selected Samples '+count;
		//activate proceed
		<? 
		if($includeCalibrators) {
			if($machineType=="roche") { 
				echo "if(count==".(24-$controls-$calibrators).") {";
			} elseif($machineType=="abbott") { 
				echo "if(count==".(96-$controls-$calibrators)." || count==".(72-$controls-$calibrators)." || count==".(48-$controls-$calibrators)." || count==".(24-$controls-$calibrators).") {";
			}
		} else {
			if($machineType=="roche") { 
				echo "if(count==".(24-$controls).") {";
			} elseif($machineType=="abbott") { 
				echo "if(count==".(96-$controls)." || count==".(72-$controls)." || count==".(48-$controls)." || count==".(24-$controls).") {";
			}
		}
		?>
			document.worksheets.proceed.disabled=false;
		} else {
			document.worksheets.proceed.disabled=true;
		}
	} else {
		//get current count
		var count=+document.worksheets.numberSamples.value;
		if(count>0) {
			count-=1;
		}
		document.worksheets.numberSamples.value=count;
		//display update
		document.getElementById('selectedSamplesID').innerHTML='Selected Samples '+count;
		//activate proceed
		<? 
		if($includeCalibrators) {
			if($machineType=="roche") { 
				echo "if(count==".(24-$controls-$calibrators).") {";
			} elseif($machineType=="abbott") { 
				echo "if(count==".(96-$controls-$calibrators)." || count==".(72-$controls-$calibrators)." || count==".(48-$controls-$calibrators)." || count==".(24-$controls-$calibrators).") {";
			}
		} else {
			if($machineType=="roche") { 
				echo "if(count==".(24-$controls).") {";
			} elseif($machineType=="abbott") { 
				echo "if(count==".(96-$controls)." || count==".(72-$controls)." || count==".(48-$controls)." || count==".(24-$controls).") {";
			}
		}
		?>
			document.worksheets.proceed.disabled=false;
		} else {
			document.worksheets.proceed.disabled=true;
		}
	}
}

function checkFirstBoxes(numberBoxes) {
	var theForm = document.worksheets, z = 0;
	//first uncheck everything
	/*for(z=0; z<theForm.length;z++){
		if(theForm[z].type == 'checkbox') {
			theForm[z].checked = false;
			//update
			updateCounter(theForm[z]);
		}
	}*/

	$('.samples').each(function() { //loop through each checkbox
        this.checked = false; //deselect all checkboxes with class "samples"   
        updateCounter(this);                    
     }); 
	//then check the selected boxes
	for(z=0; z<numberBoxes;z++){
		if(theForm[z].type == 'checkbox') {
			theForm[z].checked = true;
			//update
			updateCounter(theForm[z]);
		}
	}
}

function setLikeRadioOldSmpls(nr,type){
	var itemn=document.getElementById("n"+nr);
	var itemr=document.getElementById("r"+nr);
	if(type=='r'){
		if(itemr.checked){
			itemn.checked=false;
		}
	}else{
		if(itemn.checked){
			if(itemr.checked){
				itemr.checked=false;
				updateCounter(itemr);
			}
		}
	}	 
}

//-->
</script>
<form name="worksheets" method="post" action="/worksheets/capture.2/<?=$worksheetID?>/">
<div class="vl" style="padding:0px 0px 10px 0px; border-bottom: 1px dashed #cccccc">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="vls">
	<? if(!$modify) { ?>
      <tr>
        <td class="vl_success">
        	<div>Worksheet Created!</div>
            <div class="vls_grey" style="padding:5px 0px"><strong>Next Step:</strong> Select the Samples for inclusion within this Worksheet.</div>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    <? } ?>
      <!--
      <tr>
        <td style="padding:0px 0px 3px 0px"><strong>Machine Name:</strong> <?=$worksheetName?></td>
      </tr>
      -->
      <tr>
        <td style="padding:0px 0px 3px 0px"><strong>Reference Number:</strong> <?=$worksheetReferenceNumber?></td>
      </tr>
      <tr>
        <td style="padding:0px 0px 3px 0px"><strong>Machine Type:</strong> <?=($machineType=="abbott"?"Abbott":"Roche")?></td>
      </tr>
      <tr>
        <td style="padding:0px 0px 3px 0px"><strong>Worksheet Type:</strong> <?=getDetailedTableInfo2("vl_appendix_sampletype","id='$worksheetType' limit 1","appendix")?></td>
      </tr>
      <? if($machineType=="roche") { ?>
      <tr>
        <td style="padding:5px 0px 5px 0px; border-top: 1px dashed #cccccc"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
          <tr style="background-color:#e8e8e8">
            <td width="50%" align="center" style="padding: 5px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc"><strong>Sample Prep</strong></td>
            <td width="50%" align="center" style="padding: 5px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc; border-right: 1px solid #ccc"><strong>Bulk Lysis Buffer</strong></td>
          </tr>
          <tr>
            <td align="center" style="padding: 5px; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc"><?=($worksheetID?"<div>".getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","samplePrep")."</div><div class=\"vls_grey\"><strong>Exp&nbsp;Date:</strong>&nbsp;".getFormattedDateLessDay(getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","samplePrepExpiryDate"))."</div>":"&nbsp;")?></td>
            <td align="center" style="padding: 5px; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc; border-right: 1px solid #ccc"><?=($worksheetID?"<div>".getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","bulkLysisBuffer")."</div><div class=\"vls_grey\"><strong>Exp&nbsp;Date:</strong>&nbsp;".getFormattedDateLessDay(getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","bulkLysisBufferExpiryDate"))."</div>":"&nbsp;")?></td>
          </tr>
        </table></td>
      </tr>
      <? } elseif($machineType=="abbott") { ?>
      <tr>
        <td style="padding:5px 0px 5px 0px; border-top: 1px dashed #cccccc"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
          <tr style="background-color:#e8e8e8">
            <td width="20%" align="center" style="padding: 5px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc"><strong>Sample Prep</strong></td>
            <td width="20%" align="center" style="padding: 5px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc"><strong>Bulk Lysis Buffer</strong></td>
            <td width="20%" align="center" style="padding: 5px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc"><strong>Control</strong></td>
            <td width="20%" align="center" style="padding: 5px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc"><strong>Calibrator</strong></td>
            <td width="20%" align="center" style="padding: 5px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc; border-right: 1px solid #ccc"><strong>Amplification Kit</strong></td>
          </tr>
          <tr>
            <td align="center" style="padding: 5px; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc"><?=($worksheetID?"<div>".getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","samplePrep")."</div><div class=\"vls_grey\"><strong>Exp&nbsp;Date:</strong>&nbsp;".getFormattedDateLessDay(getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","samplePrepExpiryDate"))."</div>":"&nbsp;")?></td>
            <td align="center" style="padding: 5px; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc"><?=($worksheetID?"<div>".getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","bulkLysisBuffer")."</div><div class=\"vls_grey\"><strong>Exp&nbsp;Date:</strong>&nbsp;".getFormattedDateLessDay(getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","bulkLysisBufferExpiryDate"))."</div>":"&nbsp;")?></td>
            <td align="center" style="padding: 5px; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc"><?=($worksheetID?"<div>".getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","control")."</div><div class=\"vls_grey\"><strong>Exp&nbsp;Date:</strong>&nbsp;".getFormattedDateLessDay(getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","controlExpiryDate"))."</div>":"&nbsp;")?></td>
            <td align="center" style="padding: 5px; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc"><?=($worksheetID?"<div>".getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","calibrator")."</div><div class=\"vls_grey\"><strong>Exp&nbsp;Date:</strong>&nbsp;".getFormattedDateLessDay(getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","calibratorExpiryDate"))."</div>":"&nbsp;")?></td>
            <td align="center" style="padding: 5px; border-bottom: 1px solid #ccc; border-left: 1px solid #ccc; border-right: 1px solid #ccc"><?=($worksheetID?"<div>".getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","amplificationKit")."</div><div class=\"vls_grey\"><strong>Exp&nbsp;Date:</strong>&nbsp;".getFormattedDateLessDay(getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","amplificationKitExpiryDate"))."</div>":"&nbsp;")?></td>
          </tr>
        </table></td>
      </tr>
      <? } ?>
    </table>
</div>
<div style="padding:10px 0px 0px 0px"><strong>Quick Select Options <span class="pending-stat">(<?=$num_pending_testing ?>)</span></strong> </div>
<? 
if($machineType=="roche") { 
	$numberCheckBoxes1=21;
	if($includeCalibrators) {
		$numberCheckBoxes1-=$calibrators;
	}
?>
<div style="padding:5px 0px 10px 0px; border-bottom: 1px dashed #cccccc"><a href="#vl" onclick="checkFirstBoxes(<?=$numberCheckBoxes1?>);">Select the First <?=$numberCheckBoxes1?> Samples</a></div>
<? 
} elseif($machineType=="abbott") { 
	$numberCheckBoxes1=21;
	$numberCheckBoxes2=45;
	$numberCheckBoxes3=69;
	$numberCheckBoxes4=93;
	if($includeCalibrators) {
		$numberCheckBoxes1-=$calibrators;
		$numberCheckBoxes2-=$calibrators;
		$numberCheckBoxes3-=$calibrators;
		$numberCheckBoxes4-=$calibrators;
	}
?>
<div style="padding:5px 0px 10px 0px; border-bottom: 1px dashed #cccccc"><a href="#vl" onclick="checkFirstBoxes(<?=$numberCheckBoxes1?>);">Select the First <?=$numberCheckBoxes1?> Samples</a> :: <a href="#vl" onclick="checkFirstBoxes(<?=$numberCheckBoxes2?>);">Select the First <?=$numberCheckBoxes2?> Samples</a> :: <a href="#vl" onclick="checkFirstBoxes(<?=$numberCheckBoxes3?>);">Select the First <?=$numberCheckBoxes3?> Samples</a> :: <a href="#vl" onclick="checkFirstBoxes(<?=$numberCheckBoxes4?>);">Select the First <?=$numberCheckBoxes4?> Samples</a></div>
<? } ?>
<br />
<div style="height: 350px; width: 100%; overflow: auto; padding:5px; border: 1px solid #d5e6cf">
    <table width="100%" border="0" class="vl">
				<?
				//abbott has 12 columns, so start by getting total number of rows
				$numberRows=0;
				$numberRows=ceil(count($contents)/$columns);
				$dataCounter=0;
				
				for($i=1;$i<=$numberRows;$i++) {
					//start row
					echo "<tr style=\"background:#dddddd\">";
					//populate the cells
					for($j=1;$j<=$columns;$j++) {
						if($dataCounter<count($contents)) {
							echo "<td bgcolor=\"#dddddd\" style=\"padding:5px\" valign=\"bottom\">$contents[$dataCounter]</td>";
						} else {
							echo "<td bgcolor=\"#dddddd\" style=\"padding:5px\" valign=\"bottom\"><div style=\"width: 134px\">&nbsp;</div></td>";
						}
						$dataCounter+=1;
					}
					//end row
					echo "</tr>";
				}
				?>
    </table>
</div>
<? if(count($failedcontents)) { ?>
<div style="padding:20px 0px 10px 0px; border-bottom: 1px dashed #cccccc"><strong>Failed Samples from Previous Runs </strong></div>
<br />
<div style="height: 350px; width: 100%; overflow: auto; padding:5px; border: 1px solid #d5e6cf">
    <table width="100%" border="0" class="vl">
		<?
		//abbott has 12 columns, so start by getting total number of rows
		$numberRows=0;
		$numberRows=ceil(count($failedcontents)/$columns);
		$dataCounter=0;
		
		for($i=1;$i<=$numberRows;$i++) {
			//start row
			echo "<tr style=\"background:#dddddd\">";
			//populate the cells
			for($j=1;$j<=$columns;$j++) {
				if($dataCounter<count($failedcontents)) {
					echo "<td bgcolor=\"#dddddd\" style=\"padding:5px\" valign=\"bottom\">$failedcontents[$dataCounter]</td>";
				} else {
					echo "<td bgcolor=\"#dddddd\" style=\"padding:5px\" valign=\"bottom\"><div style=\"width: 134px\">&nbsp;</div></td>";
				}
				$dataCounter+=1;
			}
			//end row
			echo "</tr>";
		}
		?>
    </table>
</div>
<? } ?>
<div style="padding:10px 0px 0px 0px" id="selectedSamplesID">Selected Samples 0</div>
<div style="padding:5px 0px 5px 0px" class="vls_grey">
	<? 
	if($machineType=="roche") { 
		if($includeCalibrators) {
			echo "<strong>Note:</strong> Save Button below will become active only when ".(24-$controls-$calibrators)." samples (+ $controls controls and $calibrators calibrators) have been selected for addition to the Worksheet";
		} else {
			echo "<strong>Note:</strong> Save Button below will become active only when ".(24-$controls)." samples (+ $controls controls) have been selected for addition to the Worksheet";
		}
	} elseif($machineType=="abbott") {
		if($includeCalibrators) {
			echo "<strong>Note:</strong> Save Button below will become active only when ".(96-$controls-$calibrators).", ".(72-$controls-$calibrators).", ".(48-$controls-$calibrators)." or ".(24-$controls-$calibrators)." samples (+ $controls controls and $calibrators calibrators) have been selected for addition to the Worksheet";
		} else {
			echo "<strong>Note:</strong> Save Button below will become active only when ".(96-$controls).", ".(72-$controls).", ".(48-$controls)." or ".(24-$controls)." samples (+ $controls controls) have been selected for addition to the Worksheet";
		}
	}
	?>
</div>
<div style="padding:5px 0px 0px 0px">
	<? if($modify) { ?>
	<input type="submit" name="proceed" id="proceed" class="button" value="  Save Changes to Worksheet  " disabled="disabled" />
    <? } else { ?>
    <input type="submit" name="proceed" id="proceed" class="button" value="  Save and Proceed to Print Worksheet  " disabled="disabled" />
    <? } ?>
    <input name="modify" type="hidden" id="modify" value="<?=$modify?>" />
    <input name="numberSamples" type="hidden" id="numberSamples" value="" />
</div>
</form>
<style type="text/css">
.pending-stat{
	font-size: 14px;
	color: #AA9933;
}
</style>