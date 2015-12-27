<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

//encrypted samples
if($encryptedSample) {
	$searchQuery=validate(vlDecrypt($encryptedSample));
	if(!$machineType) {
		$machineType="abbott";
	}
}

//determine the sampleID field
$sampleIDField=0;
$resultField=0;
$machineType=validate($machineType);
$worksheetID=validate($worksheetID);
$resultsTable=0;
if($machineType=="abbott") {
	$resultsTable="vl_results_abbott";
	$sampleIDField="sampleID";
	$resultField="result";
} elseif($machineType=="roche") {
	$resultsTable="vl_results_roche";
	$sampleIDField="SampleID";
	$resultField="Result";
}
			
//pages
if(!$pg) {
	$pg=1;
}

$offset=0;
$offset=($pg-1)*$rowsToDisplay;

//proceed with query
$rawQuery=0;
$query=0;
$xRawQuery=0;
$xquery=0;
$sampleIDFieldArray=array();
$worksheetIDArray=array();
$abbott=0;
$roche=0;
if(!$searchQuery) {
	$rawQuery="select $resultsTable.* from $resultsTable,vl_samples where vl_samples.vlSampleID=$resultsTable.sampleID ".($worksheetID?" and $resultsTable.worksheetID='$worksheetID'":"").($facilityID?" and vl_samples.facilityID='$facilityID'":"").($showPrinted?" and vl_samples.id in (select sampleID from vl_logs_printedresults)":" and vl_samples.id not in (select sampleID from vl_logs_printedresults)")." order by if(vl_samples.lrCategory='',1,0),vl_samples.lrCategory, if(vl_samples.lrEnvelopeNumber='',1,0),vl_samples.lrEnvelopeNumber, if(vl_samples.lrNumericID='',1,0),vl_samples.lrNumericID, $resultsTable.$sampleIDField desc limit $offset, $rowsToDisplay";
	$xRawQuery="select $resultsTable.* from $resultsTable,vl_samples where vl_samples.vlSampleID=$resultsTable.sampleID ".($worksheetID?" and $resultsTable.worksheetID='$worksheetID'":"").($facilityID?" and vl_samples.facilityID='$facilityID'":"").($showPrinted?" and vl_samples.id in (select sampleID from vl_logs_printedresults)":" and vl_samples.id not in (select sampleID from vl_logs_printedresults)")." order by if(vl_samples.lrCategory='',1,0),vl_samples.lrCategory, if(vl_samples.lrEnvelopeNumber='',1,0),vl_samples.lrEnvelopeNumber, if(vl_samples.lrNumericID='',1,0),vl_samples.lrNumericID, $resultsTable.$sampleIDField desc";
	$query=mysqlquery($rawQuery);
	$xquery=mysqlquery($xRawQuery);
	if(mysqlnumrows($query)) {
		$q=array();
		while($q=mysqlfetcharray($query)) {
			$sampleIDFieldArray[]=$q["$sampleIDField"];
			$worksheetIDArray[]=$q["worksheetID"];
		}
	} else {
		//try searching within those not yet printed
		if($showPrinted) {
			$showPrinted=0;
		} else {
			$showPrinted=1;
		}
		$rawQuery="select $resultsTable.* from $resultsTable,vl_samples where vl_samples.vlSampleID=$resultsTable.sampleID ".($worksheetID?" and $resultsTable.worksheetID='$worksheetID'":"").($facilityID?" and vl_samples.facilityID='$facilityID'":"").($showPrinted?" and vl_samples.id in (select sampleID from vl_logs_printedresults)":" and vl_samples.id not in (select sampleID from vl_logs_printedresults)")." order by if(vl_samples.lrCategory='',1,0),vl_samples.lrCategory, if(vl_samples.lrEnvelopeNumber='',1,0),vl_samples.lrEnvelopeNumber, if(vl_samples.lrNumericID='',1,0),vl_samples.lrNumericID, $resultsTable.$sampleIDField desc limit $offset, $rowsToDisplay";
		$xRawQuery="select $resultsTable.* from $resultsTable,vl_samples where vl_samples.vlSampleID=$resultsTable.sampleID ".($worksheetID?" and $resultsTable.worksheetID='$worksheetID'":"").($facilityID?" and vl_samples.facilityID='$facilityID'":"").($showPrinted?" and vl_samples.id in (select sampleID from vl_logs_printedresults)":" and vl_samples.id not in (select sampleID from vl_logs_printedresults)")." order by if(vl_samples.lrCategory='',1,0),vl_samples.lrCategory, if(vl_samples.lrEnvelopeNumber='',1,0),vl_samples.lrEnvelopeNumber, if(vl_samples.lrNumericID='',1,0),vl_samples.lrNumericID, $resultsTable.$sampleIDField desc";
		$query=mysqlquery($rawQuery);
		$xquery=mysqlquery($xRawQuery);
		if(mysqlnumrows($query)) {
			$q=array();
			while($q=mysqlfetcharray($query)) {
				$sampleIDFieldArray[]=$q["$sampleIDField"];
				$worksheetIDArray[]=$q["worksheetID"];
			}
		} else {
			//revert the show printed flag
			if($showPrinted) {
				$showPrinted=0;
			} else {
				$showPrinted=1;
			}
		}
	}
} else {
	//abbott results
	$resultsTable="vl_results_abbott";
	$sampleIDField="sampleID";
	$resultField="result";
	$machineType="abbott";
	//abbott results
	$rawQuery="select $resultsTable.* from 
								$resultsTable,vl_samples,vl_samples_worksheetcredentials,vl_patients  
									where 
										vl_samples.vlSampleID=$resultsTable.sampleID and 
											vl_samples_worksheetcredentials.id=$resultsTable.worksheetID and 
												vl_patients.id=vl_samples.patientID and 
													(vl_samples.vlSampleID like '%$searchQuery%' or 
														vl_samples.formNumber like '%$searchQuery%' or 
															vl_samples_worksheetcredentials.worksheetReferenceNumber like '%$searchQuery%' or 
																vl_patients.artNumber like '%$searchQuery%' or 
																	vl_patients.otherID like '%$searchQuery%' or 
																		concat(vl_samples.lrCategory,vl_samples.lrEnvelopeNumber,'/',vl_samples.lrNumericID) like '%$searchQuery%') 
																			order by if(vl_samples.lrCategory='',1,0),vl_samples.lrCategory, if(vl_samples.lrEnvelopeNumber='',1,0),vl_samples.lrEnvelopeNumber, if(vl_samples.lrNumericID='',1,0),vl_samples.lrNumericID, $resultsTable.$sampleIDField desc limit $offset, $rowsToDisplay";
	$xRawQuery="select $resultsTable.* from 
								$resultsTable,vl_samples,vl_samples_worksheetcredentials,vl_patients 
									where 
										vl_samples.vlSampleID=$resultsTable.sampleID and 
											vl_samples_worksheetcredentials.id=$resultsTable.worksheetID and 
												vl_patients.id=vl_samples.patientID and 
													(vl_samples.vlSampleID like '%$searchQuery%' or 
														vl_samples.formNumber like '%$searchQuery%' or 
															vl_samples_worksheetcredentials.worksheetReferenceNumber like '%$searchQuery%' or 
																vl_patients.artNumber like '%$searchQuery%' or 
																	vl_patients.otherID like '%$searchQuery%' or 
																		concat(vl_samples.lrCategory,vl_samples.lrEnvelopeNumber,'/',vl_samples.lrNumericID) like '%$searchQuery%') 
																			order by if(vl_samples.lrCategory='',1,0),vl_samples.lrCategory, if(vl_samples.lrEnvelopeNumber='',1,0),vl_samples.lrEnvelopeNumber, if(vl_samples.lrNumericID='',1,0),vl_samples.lrNumericID, $resultsTable.$sampleIDField desc";
	$query=mysqlquery($rawQuery);
	$xquery=mysqlquery($xRawQuery);
	if(mysqlnumrows($xquery)) {
		$q=array();
		while($q=mysqlfetcharray($xquery)) {
			$sampleIDFieldArray[]=$q["$sampleIDField"];
			$worksheetIDArray[]=$q["worksheetID"];
		}
		//machine flag
		$abbott=1;
	}
	
	//roche results
	$resultsTable="vl_results_roche";
	$sampleIDField="SampleID";
	$resultField="Result";
	$machineType="roche";

	$rawQuery="select $resultsTable.* from 
								$resultsTable,vl_samples,vl_samples_worksheetcredentials,vl_patients  
									where 
										vl_samples.vlSampleID=$resultsTable.sampleID and 
											vl_samples_worksheetcredentials.id=$resultsTable.worksheetID and 
												vl_patients.id=vl_samples.patientID and 
													(vl_samples.vlSampleID like '%$searchQuery%' or 
														vl_samples.formNumber like '%$searchQuery%' or 
															vl_samples_worksheetcredentials.worksheetReferenceNumber like '%$searchQuery%' or 
																vl_patients.artNumber like '%$searchQuery%' or 
																	vl_patients.otherID like '%$searchQuery%' or 
																		concat(vl_samples.lrCategory,vl_samples.lrEnvelopeNumber,'/',vl_samples.lrNumericID) like '%$searchQuery%') 
																			order by if(vl_samples.lrCategory='',1,0),vl_samples.lrCategory, if(vl_samples.lrEnvelopeNumber='',1,0),vl_samples.lrEnvelopeNumber, if(vl_samples.lrNumericID='',1,0),vl_samples.lrNumericID, $resultsTable.$sampleIDField desc limit $offset, $rowsToDisplay";
	$xRawQuery="select $resultsTable.* from 
								$resultsTable,vl_samples,vl_samples_worksheetcredentials,vl_patients 
									where 
										vl_samples.vlSampleID=$resultsTable.sampleID and 
											vl_samples_worksheetcredentials.id=$resultsTable.worksheetID and 
												vl_patients.id=vl_samples.patientID and 
													(vl_samples.vlSampleID like '%$searchQuery%' or 
														vl_samples.formNumber like '%$searchQuery%' or 
															vl_samples_worksheetcredentials.worksheetReferenceNumber like '%$searchQuery%' or 
																vl_patients.artNumber like '%$searchQuery%' or 
																	vl_patients.otherID like '%$searchQuery%' or 
																		concat(vl_samples.lrCategory,vl_samples.lrEnvelopeNumber,'/',vl_samples.lrNumericID) like '%$searchQuery%') 
																			order by if(vl_samples.lrCategory='',1,0),vl_samples.lrCategory, if(vl_samples.lrEnvelopeNumber='',1,0),vl_samples.lrEnvelopeNumber, if(vl_samples.lrNumericID='',1,0),vl_samples.lrNumericID, $resultsTable.$sampleIDField desc";
	$query=mysqlquery($rawQuery);
	$xquery=mysqlquery($xRawQuery);

	if(mysqlnumrows($xquery)) {
		$q=array();
		while($q=mysqlfetcharray($xquery)) {
			$sampleIDFieldArray[]=$q["$sampleIDField"];
			$worksheetIDArray[]=$q["worksheetID"];
		}
		//machine flag
		$roche=1;
	}

	//are these results printed or not
	$showPrinted=0;
	for($i=0;$i<count($sampleIDFieldArray);$i++) {
		$printSampleID=0;
		$printSampleID=getDetailedTableInfo2("vl_samples","vlSampleID='$sampleIDFieldArray[$i]'","id");
		//is this id printed
		if(getDetailedTableInfo2("vl_logs_printedresults","sampleID='$printSampleID'","id")) {
			$showPrinted=1;
			break;
		}
	}
	
	//rejected results
	$resultsTable="vl_samples_verify";
	$machineType="all";

	$rawQuery="select $resultsTable.*,vl_samples.vlSampleID vlSampleID from 
								$resultsTable,vl_samples,vl_patients 
									where 
										vl_samples.id=$resultsTable.sampleID and 
											vl_patients.id=vl_samples.patientID and 
												$resultsTable.outcome='Rejected' and 
													(vl_samples.vlSampleID like '%$searchQuery%' or 
														vl_samples.formNumber like '%$searchQuery%' or 
															vl_patients.artNumber like '%$searchQuery%' or 
																vl_patients.otherID like '%$searchQuery%' or 
																	concat(vl_samples.lrCategory,vl_samples.lrEnvelopeNumber,'/',vl_samples.lrNumericID) like '%$searchQuery%') 
																		order by if(vl_samples.lrCategory='',1,0),vl_samples.lrCategory, if(vl_samples.lrEnvelopeNumber='',1,0),vl_samples.lrEnvelopeNumber, if(vl_samples.lrNumericID='',1,0),vl_samples.lrNumericID limit $offset, $rowsToDisplay";
	$xRawQuery="select $resultsTable.*,vl_samples.vlSampleID vlSampleID from 
								$resultsTable,vl_samples,vl_patients 
									where 
										vl_samples.id=$resultsTable.sampleID and 
											vl_patients.id=vl_samples.patientID and 
												$resultsTable.outcome='Rejected' and 
													(vl_samples.vlSampleID like '%$searchQuery%' or 
														vl_samples.formNumber like '%$searchQuery%' or 
															vl_patients.artNumber like '%$searchQuery%' or 
																vl_patients.otherID like '%$searchQuery%' or 
																	concat(vl_samples.lrCategory,vl_samples.lrEnvelopeNumber,'/',vl_samples.lrNumericID) like '%$searchQuery%') 
																		order by if(vl_samples.lrCategory='',1,0),vl_samples.lrCategory, if(vl_samples.lrEnvelopeNumber='',1,0),vl_samples.lrEnvelopeNumber, if(vl_samples.lrNumericID='',1,0),vl_samples.lrNumericID";
	$query=mysqlquery($rawQuery);
	$xquery=mysqlquery($xRawQuery);
	$rejectionOverride=0;

	if(mysqlnumrows($xquery)) {
		$q=array();
		while($q=mysqlfetcharray($xquery)) {
			$sampleIDFieldArray[]=$q["vlSampleID"];
			//$worksheetIDArray[]=$q["worksheetID"];
			//change rejection override flag to 1
			$rejectionOverride=1;
			$machineType="rejected";
		}
		//machine flag
		//$roche=1;
	}

	//are these results printed or not
	$showPrinted=0;
	for($i=0;$i<count($sampleIDFieldArray);$i++) {
		$printSampleID=0;
		$printSampleID=getDetailedTableInfo2("vl_samples","vlSampleID='$sampleIDFieldArray[$i]'","id");
		//is this id printed
		if(getDetailedTableInfo2("vl_logs_printedrejectedresults","sampleID='$printSampleID'","id")) {
			$showPrinted=1;
			break;
		}
	}
}

//number pages
$numberRecords=0;
$numberRecords=mysqlnumrows($xquery);
$numberPages=0;
$numberPages=ceil($numberRecords/$rowsToDisplay);

//results found in both abbott and roche machines
if($roche && $abbott) {
	$machineType="all";
} elseif(!$roche && $abbott) {
	$machineType="abbott";
} elseif($roche && !$abbott) {
	$machineType="roche";
}

//results
$resultsAbbott=0;
$resultsAbbott=getDetailedTableInfo3("vl_results_abbott,vl_samples","vl_samples.vlSampleID=vl_results_abbott.sampleID".($showPrinted?" and vl_samples.id in (select sampleID from vl_logs_printedresults)":" and vl_samples.id not in (select sampleID from vl_logs_printedresults)"),"count(vl_results_abbott.sampleID)","num");
$resultsRoche=0;
$resultsRoche=getDetailedTableInfo3("vl_results_roche,vl_samples","vl_samples.vlSampleID=vl_results_roche.SampleID".($showPrinted?" and vl_samples.id in (select sampleID from vl_logs_printedresults)":" and vl_samples.id not in (select sampleID from vl_logs_printedresults)"),"count(vl_results_roche.SampleID)","num");
$rejectedSamples=0;
$rejectedSamples=getDetailedTableInfo3("vl_samples_verify","outcome='Rejected'".($showPrinted?" and sampleID in (select sampleID from vl_logs_printedrejectedresults)":" and sampleID not in (select sampleID from vl_logs_printedrejectedresults)"),"count(id)","num");
if($rejectionOverride) {
	$rejectedSamples=getDetailedTableInfo3("vl_samples_verify","outcome='Rejected'".($showPrinted?" and sampleID in (select sampleID from vl_logs_printedrejectedresults)":" and sampleID not in (select sampleID from vl_logs_printedrejectedresults)"),"count(id)","num");
}
$resultsPrinted=0;
$resultsPrinted=getDetailedTableInfo3("vl_results_abbott,vl_samples","vl_samples.vlSampleID=vl_results_abbott.sampleID and vl_samples.id in (select sampleID from vl_logs_printedresults)","count(vl_results_abbott.sampleID)","num")+getDetailedTableInfo3("vl_results_roche,vl_samples","vl_samples.vlSampleID=vl_results_roche.SampleID and vl_samples.id in (select sampleID from vl_logs_printedresults)","count(vl_results_roche.SampleID)","num");
$resultsNotPrinted=0;
$resultsNotPrinted=getDetailedTableInfo3("vl_results_abbott,vl_samples","vl_samples.vlSampleID=vl_results_abbott.sampleID and vl_samples.id not in (select sampleID from vl_logs_printedresults)","count(vl_results_abbott.sampleID)","num")+getDetailedTableInfo3("vl_results_roche,vl_samples","vl_samples.vlSampleID=vl_results_roche.SampleID and vl_samples.id not in (select sampleID from vl_logs_printedresults)","count(vl_results_roche.SampleID)","num");
?>
<script Language="JavaScript" Type="text/javascript">
<!--
function reloadWorksheet(theField) {
	if(theField.value) {
		document.location.href="<?=$home_url?>results/<?=$machineType?>/pg/<?=$pg?>/"+theField.value+"/";
	}
}

function validate(results) {
	//ammend the form action variable
	if(document.pressed == '  Print Selected Results  ') {
		document.results.target = "_blank";
		document.results.action = "/results/print/batch/<?=$machineType?>/";
	} else if(document.pressed == '  Dispatch Selected Results  ') {
		document.results.action = "/results/dispatch/";
	} else if(document.pressed == '  Dispatch All <?=number_format((float)$numberRecords)?> Results  ') {
		document.results.action = "/results/dispatch/all/";
	} else if(document.pressed == '  Repeat Selected Samples  ') {
		document.results.action = "/results/repeat/";
	} else if(document.pressed == '  Filter  ') {
		document.results.action = "/results/<?=$machineType?>/pg/<?=$pg?>/"+(document.results.worksheetID.value?document.results.worksheetID.value:0)+"/"+(document.results.facilityID.value?document.results.facilityID.value:0)+"/";
	}
	return (true);
}
//-->
</script>
<form name="results" method="post" action="/results/print/batch/<?=$machineType?>/" onsubmit="return validate(this)">
<!--<form name="results" method="post" action="/results/print/batch/<?=$machineType?>/">-->
<table width="100%" border="0" class="vl">
			<? if(!$resultsRoche && !$resultsAbbott) { ?>
            <tr>
                <td class="vl_error">
                There are No Results on the System!<br />
				<a href="/worksheets/">Click Here to create a Worksheet then Upload Results.</a></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
			<? } elseif($dispatchedSamples) { ?>
            <tr>
                <td class="vl_success">
                <div><strong>Samples Successfully Logged as Dispatched!</strong></div>
                <div class="vls_grey" style="padding:3px 0px"><strong><?=number_format((float)$added)?></strong> sample<?=($added==1?"":"s")?> added to the "dispatched" list.</div>
                <div class="vls_grey" style="padding:0px 0px 3px 0px"><strong><?=number_format((float)$modified)?></strong> sample<?=($modified==1?"":"s")?> modified within the "dispatched" list.</div>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
			<? } elseif($repeatedSamples) { ?>
            <tr>
                <td class="vl_success">
                <div><strong><?=($added!=0?"Samples Successfully ":"No Samples ")?>Scheduled to be Repeated!</strong></div>
                <div class="vls_grey" style="padding:3px 0px"><strong><?=number_format((float)$added)?></strong> sample<?=($added==1?"":"s")?> added to the "repeat" schedule.</div>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <? } ?>
            <tr>
              <td>
	<table width="100%" border="0" class="vl" cellspacing="0" cellpadding="0">
		<?
		//how many pages are there?
		if($numberPages>1) {
			echo "<tr><td style=\"padding:0px 0px 10px 0px\" class=\"vls_grey\"><strong>Pages:</strong> ".displayPagesLinks(($worksheetID || $facilityID?"/results/$machineType/".($worksheetID?"$worksheetID/":"0/").($facilityID?"$facilityID/":"0/").($showPrinted?"printed/":"")."pg/":"/results/$machineType/".($showPrinted?"printed/":"")."pg/"),1,$numberPages,($pg?$pg:1),$default_radius)."</td></tr>";
		}
        ?>
                <tr>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="90%" valign="bottom"><table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <? if(!$showPrinted) { ?>
                          <td class="bluetab_active"><?="Not&nbsp;Yet&nbsp;Printed&nbsp;(".number_format((float)$resultsNotPrinted).")"?></td>
                          <? } else { ?>
                          <td class="bluetab_inactive"><a href="/results/">
                            <?="Not&nbsp;Yet&nbsp;Printed&nbsp;(".number_format((float)$resultsNotPrinted).")"?>
                          </a></td>
                          <? } ?>
                          <td bgcolor="#C4CCCC"><img src="/images/spacer.gif" width="1" height="1" /></td>
                          <? if($showPrinted) { ?>
                          <td class="bluetab_active"><?="Printed&nbsp;(".number_format((float)$resultsPrinted).")"?></td>
                          <? } else { ?>
                          <td class="bluetab_inactive"><a href="/results/printed/">
                            <?="Printed&nbsp;(".number_format((float)$resultsPrinted).")"?>
                          </a></td>
                          <? } ?>
                          <td bgcolor="#C4CCCC"><img src="/images/spacer.gif" width="1" height="1" /></td>
                        </tr>
                      </table></td>
                      <td width="5%" align="right" style="padding:0px 3px 3px 0px">
                      					<select name="facilityID" id="facilityID" 
                                            style="width:150px; z-index:+1;"
                                            onchange="this.blur();"
                                            onblur="this.style.width='150px';" 
											class="search">
                                            <?
                                            $queryF=0;
                                            $queryF=mysqlquery("select * from vl_facilities where facility!='' order by facility");
											if($facilityID) {
												echo "<option value=\"\">All Facilities</option>";
												echo "<option value=\"$facilityID\" selected=\"selected\">".getDetailedTableInfo2("vl_facilities","id='$facilityID' limit 1","facility")."</option>";
											} else {
												echo "<option value=\"\" selected=\"selected\">Select Facility</option>";
											}
                                            if(mysqlnumrows($queryF)) {
                                                while($qF=mysqlfetcharray($queryF)) {
                                                    echo "<option value=\"$qF[id]\">$qF[facility]</option>";
                                                }
                                            }
                                            ?>
                                        </select></td>
                      <td width="5%" align="right" style="padding:0px 3px 3px 0px"><!--<select name="worksheetID" id="worksheetID" class="search" onchange="reloadWorksheet(this)">-->
                      			<select name="worksheetID" id="worksheetID" class="search">
                                <?
								$queryW=0;
								$queryW=mysqlquery("select distinct $resultsTable.worksheetID worksheetID, vl_samples_worksheetcredentials.worksheetReferenceNumber worksheetReferenceNumber from $resultsTable,vl_samples_worksheetcredentials where vl_samples_worksheetcredentials.id=$resultsTable.worksheetID order by vl_samples_worksheetcredentials.created desc limit $default_numberWorksheets");
								if($worksheetID) {
									echo "<option value=\"\">All Worksheets</option>";
									echo "<option value=\"$worksheetID\" selected=\"selected\">".getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","worksheetReferenceNumber")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select ".($machineType=="abbott"?"Abbott":"Roche")." Worksheet</option>";
								}
								if(mysqlnumrows($queryW)) {
									while($qW=mysqlfetcharray($queryW)) {
										echo "<option value=\"$qW[worksheetID]\">$qW[worksheetReferenceNumber]</option>";
									}
								}
								?>
                                </select></td>
                      <td width="5%" align="right" style="padding:0px 0px 3px 0px"><input type="submit" name="filterResults" id="filterResults" class="buttonsmall" value="  Filter  " onclick="document.pressed=this.value" /></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td style="border: 1px solid #d5e6cf; padding:20px">
                    <!-- Start Printed/Not Printed Items -->
	                    <table width="100%" border="0" class="vl" cellspacing="0" cellpadding="0">
                            <tr>
                              <td valign="bottom"><table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <? if($machineType=="abbott") { ?>
                                  <td class="tab_active"><?="Abbott&nbsp;(".number_format((float)$resultsAbbott).")"?></td>
                                  <? } else { ?>
                                  <td class="tab_inactive"><a href="/results/abbott/<?=($showPrinted?"printed/":"")?>">
                                    <?="Abbott&nbsp;(".number_format((float)$resultsAbbott).")"?>
                                  </a></td>
                                  <? } ?>
                                  <td bgcolor="#C4CCCC"><img src="/images/spacer.gif" width="1" height="1" /></td>
                                  <? if($machineType=="roche") { ?>
                                  <td class="tab_active"><?="Roche&nbsp;(".number_format((float)$resultsRoche).")"?></td>
                                  <? } else { ?>
                                  <td class="tab_inactive"><a href="/results/roche/<?=($showPrinted?"printed/":"")?>">
                                    <?="Roche&nbsp;(".number_format((float)$resultsRoche).")"?>
                                  </a></td>
                                  <? } ?>
                                  <? if($machineType=="all") { ?>
                                  <td bgcolor="#C4CCCC"><img src="/images/spacer.gif" width="1" height="1" /></td>
                                  <td class="tab_active"><?="All"?></td>
                                  <? } ?>
                                  <td bgcolor="#C4CCCC"><img src="/images/spacer.gif" width="1" height="1" /></td>
                                  <? if($machineType=="rejected") { ?>
                                  <td class="tab_active"><?="Rejected&nbsp;Samples&nbsp;(".number_format((float)$rejectedSamples).")"?></td>
                                  <? } else { ?>
                                  <td class="bluetab_inactive"><a href="/results/rejected/<?=($showPrinted?"printed/":"")?>">
                                    <?="Rejected&nbsp;Samples&nbsp;(".number_format((float)$rejectedSamples).")"?>
                                  </a></td>
                                  <? } ?>
                                  <td bgcolor="#C4CCCC"><img src="/images/spacer.gif" width="1" height="1" /></td>
                                </tr>
                              </table></td>
                            </tr>
                            <tr>
                              <td style="border: 1px solid #d5e6cf; padding:20px">
                                <div style="height: 250px; width: 100%; overflow: auto; padding:5px">
                                  <? 
                                  //if(mysqlnumrows($query)) { 
                                  if(count($sampleIDFieldArray)) {
                                  ?>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td class="vl_tdsub" width="1%"><input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"></td>
                                          <td class="vl_tdsub" width="1%"><strong>#</strong></td>
                                          <td class="vl_tdsub" width="10%"><strong>Sample&nbsp;Details</strong></td>
                                          <td class="vl_tdsub" width="10%"><strong>Patient&nbsp;Details</strong></td>
                                          <td class="vl_tdsub" width="63%"><strong>Result</strong></td>
                                          <td class="vl_tdsub" width="1%"><strong>Dispatched</strong></td>
                                          <td class="vl_tdsub" width="2%"><strong>Repeat</strong></td>
                                          <td class="vl_tdsub" width="2%"><strong>Printed</strong></td>
                                          <td class="vl_tdsub" width="10%"><strong>Actions</strong></td>
                                        </tr>
                                        <?
                                        $count=0;
                                        $count=$offset;
                                        for($i=0;$i<count($sampleIDFieldArray);$i++) {
                                            $count+=1;
                                            $formNumber=0;
                                            $formNumber=getDetailedTableInfo2("vl_samples","vlSampleID='$sampleIDFieldArray[$i]'","formNumber");
                                            $sampleID=0;
                                            $sampleID=getDetailedTableInfo2("vl_samples","vlSampleID='$sampleIDFieldArray[$i]'","id");
                                            $sampleReferenceNumber=0;
                                            $sampleReferenceNumber=$sampleIDFieldArray[$i];
                                            $patientID=0;
                                            $patientID=getDetailedTableInfo2("vl_samples","vlSampleID='$sampleIDFieldArray[$i]'","patientID");
                                            $patientART=0;
                                            $patientART=getDetailedTableInfo2("vl_patients","id='$patientID'","artNumber");
                                            $otherID=0;
                                            $otherID=getDetailedTableInfo2("vl_patients","id='$patientID'","otherID");
                                            $worksheetUniqueID=0;
                                            $worksheetUniqueID=$worksheetIDArray[$i];
                                            $worksheetMachineType=0;
                                            $worksheetMachineType=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetUniqueID'","machineType");
                                            /*
                                            * 9/Feb/15: 
                                            * Issac Ssewanyana (sewyisaac@yahoo.co.uk, CPHL) suggested the introduction of a location and 
                                            * rejection ID hence the introduction of the variables below
                                            */
                                            $locationID=0;
                                            $locationID=getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","lrCategory").getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","lrEnvelopeNumber").(getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","lrNumericID")?"/".getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","lrNumericID"):"");
                                            /*
                                            * 8/Sept/14: 
                                            * Hellen (hnansumba@gmail.com, CPHL) requested the Worksheet Name be removed given it is
                                            * a duplicate of the Worksheet Reference Number
                                            */
                                            $worksheetReferenceNumber=0;
                                            $worksheetReferenceNumber=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetUniqueID'","worksheetReferenceNumber");
                                            //factor
                                            $factor=0;
                                            $factor=getDetailedTableInfo2("vl_results_multiplicationfactor","worksheetID='$worksheetUniqueID' limit 1","factor");
                                            if(!$factor) {
                                                $factor=1;
                                            }
                                            //results
                                            $result=0;
                                            $result=getVLResult(($worksheetMachineType?$worksheetMachineType:$machineType),$worksheetUniqueID,$sampleIDFieldArray[$i],$factor);
											//is there a rejected reason, as a result of searching and finding a result within rejected samples
											if($rejectionOverride) {
												$outcomeReasonsID=0;
												$outcomeReasonsID=getDetailedTableInfo2("vl_samples_verify","sampleID='$sampleID' order by created desc limit 1","outcomeReasonsID");
												$sampleTypeID=0;
												$sampleTypeID=getDetailedTableInfo2("vl_samples","id='$sampleID' order by created desc limit 1","sampleTypeID");
												//rejected result
												$result=getDetailedTableInfo2("vl_appendix_samplerejectionreason","id='$outcomeReasonsID' and sampleTypeID='$sampleTypeID'","appendix");
											}
                                            //printed
                                            $printed=0;
                                            if(getDetailedTableInfo2("vl_logs_printedresults","sampleID='$sampleID' and worksheetID='$worksheetUniqueID' limit 1","id")) {
                                                $printed="<font color=\"#009900\">Yes</font>";
                                            } else {
                                                $printed="<font color=\"#FF0000\">No</font>";
                                            }
                                            //dispatched
                                            $dispatched=0;
                                            if(getDetailedTableInfo2("vl_logs_dispatchedresults","sampleID='$sampleID' and worksheetID='$worksheetUniqueID' limit 1","id")) {
                                                $dispatched="<a href=\"#\" onclick=\"iDisplayMessage('/results/preview.dispatched/$sampleID/$worksheetUniqueID/')\"><font color=\"#009900\">Yes</font></a>";
                                            } else {
                                                $dispatched="<font color=\"#FF0000\">No</font>";
                                            }
                                            //repeat
                                            $repeat=0;
                                            $noprint=0;
                                            if(getDetailedTableInfo2("vl_logs_samplerepeats","sampleID='$sampleID' and oldWorksheetID='$worksheetUniqueID' limit 1","id")) {
                                                //scheduled or repeated
                                                if(getDetailedTableInfo2("vl_logs_samplerepeats","sampleID='$sampleID' and oldWorksheetID='$worksheetUniqueID' and withWorksheetID='' limit 1","id")) {
                                                    $repeat="<a href=\"#\" onclick=\"iDisplayMessage('/results/preview.repeat.scheduled/$sampleID/$worksheetUniqueID/')\"><font color=\"#FF0000\">Scheduled</font></a>";
                                                    $noprint=1;
                                                } else {
                                                    $repeat="<a href=\"#\" onclick=\"iDisplayMessage('/results/preview.repeat.repeated/$sampleID/$worksheetUniqueID/')\"><font color=\"#009900\">Repeated</font></a>";
                                                }
                                            } else {
                                                $repeat="<font color=\"#009900\">No</font>";
                                            }
											//facility name
											$facilityName=0;
											$facilityName=getDetailedTableInfo2("vl_facilities","id='".getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","facilityID")."' limit 1","facility");
                                        ?>
                                            <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                                                <td class="vl_tdstandard"><input name="sampleResultCheckbox[]" type="checkbox" id="sampleResultCheckbox[]" value="<?=$sampleID."|".$worksheetUniqueID?>"></td>
                                                <td class="vl_tdstandard"><?=$count?></td>
                                                <td class="vl_tdstandard">
                                                    <div><strong>Form&nbsp;Number:</strong>&nbsp;<a href="#" onclick="iDisplayMessage('/verify/preview/<?=$sampleID?>/<?=$pg?>/noedit/')"><?=$formNumber?></a></div>
                                                    <div class="vls_grey" style="padding:3px 0px"><strong>Sample&nbsp;Ref&nbsp;#:</strong>&nbsp;<?=$sampleReferenceNumber?></div>
                                                    <? if(!$rejectionOverride) { ?><div class="vls_grey" style="padding:0px 0px 3px 0px"><strong>Worksheet&nbsp;Ref&nbsp;#:</strong>&nbsp;<?=$worksheetReferenceNumber?></div><? } ?>
                                                    <? if($locationID) { ?><div class="vls_grey" style="padding:0px 0px 3px 0px"><strong>Location&nbsp;ID:</strong>&nbsp;<?=$locationID?></div><? } ?>
                                                    <div class="vls_grey" style="padding:0px 0px 3px 0px"><strong>Facility:</strong>&nbsp;<?=$facilityName?></div>
                                                </td>
                                                <td class="vl_tdstandard">
                                                    <div><strong>ART&nbsp;#:</strong>&nbsp;<?=preg_replace("/ /s","&nbsp;",$patientART)?></div>
                                                    <? if($otherID) { ?><div class="vls_grey" style="padding:3px 0px"><strong>Other&nbsp;ID:</strong>&nbsp;<?=$otherID?></div><? } ?>
                                                </td>
                                                <td class="vl_tdstandard">
                                                	<? 
													if($rejectionOverride) { 
														echo "<div><font color=\"#FF0000\">Rejected</font> with reason <strong>$result</strong></div>";
													} else {
													?>
                                                    <div><strong>Result:</strong>&nbsp;<?=$result?></div>
                                                    <div class="vls_grey" style="padding:3px 0px"><strong>Factor:</strong>&nbsp;x<?=$factor?></div>
                                                    <? } ?>
                                                </td>
                                                <td class="vl_tdstandard" align="center"><?=$dispatched?></td>
                                                <td class="vl_tdstandard" align="center"><?=$repeat?></td>
                                                <td class="vl_tdstandard" align="center"><?=$printed?></td>
                                                <td class="vl_tdstandard"><div class="vls_grey" style="padding:3px 0px 0px 0px"><? if($machineType=="rejected" && !$noprint) { echo "<a href=\"/results/print.rejected/$sampleID/\" target=\"_blank\">Print</a>&nbsp;::&nbsp;"; } elseif($machineType!="rejected" && !$noprint) { ?><a href="/results/print/<?=$sampleID?>/<?=$worksheetUniqueID?>/<?=($worksheetMachineType?$worksheetMachineType:$machineType)?>/" target="_blank">Print</a>&nbsp;::&nbsp;<? } ?><a href="/results/dispatch/<?=$sampleID?>/<?=$worksheetUniqueID?>/">Dispatch</a></div></td>
                                            </tr>
                                        <? } ?>
                                   </table>
                                <? 
                                } else { 
                                    if($searchQuery) {
                                    ?>
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td class="vl_error">There are No Results matching the Search Criteria <strong><?=$searchQuery?></strong>!</td>
                                        </tr>
                                    </table>
                                    <? 
                                    } else {
                                ?>
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td class="vl_error">No <?=$machineType?> Results Found<?=($facilityID || $worksheetID?" within ":"").($facilityID && !$worksheetID?getDetailedTableInfo2("vl_facilities","id='$facilityID' limit 1","facility")." Facility":"").(!$facilityID && $worksheetID?getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","worksheetReferenceNumber")." Worksheet":"").($facilityID && $worksheetID?getDetailedTableInfo2("vl_facilities","id='$facilityID' limit 1","facility")." Facility and ".getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetID' limit 1","worksheetReferenceNumber")." Worksheet":"")?></td>
                                    </tr>
                                </table>
                                <? 
                                    }
                                } 
                                ?>
                                  </div>
                  			</td>
                        </tr>
                    </table>
                    <!-- End Printed/Not Printed Items -->
              </td>
            </tr>
            <tr>
              <td style="padding:10px 0px 10px 0px">
              	<input type="hidden" name="rawQuery" id="rawQuery" value="<?=vlEncrypt($rawQuery)?>" />
              	<input type="hidden" name="xRawQuery" id="xRawQuery" value="<?=vlEncrypt($xRawQuery)?>" />
              	<input type="hidden" name="machineType" id="machineType" value="<?=$machineType?>" />
              	<input type="hidden" name="sampleIDField" id="sampleIDField" value="<?=$sampleIDField?>" />
              	<input type="submit" name="printResults" id="printResults" class="button" value="  Print Selected Results  " onclick="document.pressed=this.value" /> 
                <input type="submit" name="dispatchResults" id="dispatchResults" class="button" value="  Dispatch Selected Results  " onclick="document.pressed=this.value" /> 
				<? if(($facilityID || $worksheetID) && $numberPages>1) { ?><input type="submit" name="dispatchResults" id="dispatchResults" class="button" value="  Dispatch All <?=number_format((float)$numberRecords)?> Results  " onclick="document.pressed=this.value" /><? } ?><!--<input type="submit" name="repeatSamples" id="repeatSamples" class="button" value="  Repeat Selected Samples  " onclick="document.pressed=this.value" />--></td>
          </tr>
            <tr>
	            <td style="padding:10px 0px 0px 0px; border-top: 1px dashed #CCC"><a href="/dashboard/">Return to Dashboard</a></td>
            </tr>
		</table>
              </td>
            </tr>
          </table>
</form>