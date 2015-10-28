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

//results
$resultsAbbott=0;
$resultsAbbott=getDetailedTableInfo3("vl_results_abbott,vl_samples","vl_samples.vlSampleID=vl_results_abbott.sampleID".($showPrinted?" and vl_samples.id in (select sampleID from vl_logs_printedresults)":""),"count(vl_results_abbott.sampleID)","num");
$resultsRoche=0;
$resultsRoche=getDetailedTableInfo3("vl_results_roche,vl_samples","vl_samples.vlSampleID=vl_results_roche.SampleID".($showPrinted?" and vl_samples.id in (select sampleID from vl_logs_printedresults)":""),"count(vl_results_roche.SampleID)","num");
$rejectedSamples=0;
$rejectedSamples=getDetailedTableInfo3("vl_samples_verify","outcome='Rejected'".($showPrinted?" and sampleID in (select sampleID from vl_logs_printedrejectedresults)":" and sampleID not in (select sampleID from vl_logs_printedrejectedresults)"),"count(id)","num");
$resultsPrinted=0;
$resultsPrinted=getDetailedTableInfo3("vl_results_abbott,vl_samples","vl_samples.vlSampleID=vl_results_abbott.sampleID and vl_samples.id in (select sampleID from vl_logs_printedresults)","count(vl_results_abbott.sampleID)","num")+getDetailedTableInfo3("vl_results_roche,vl_samples","vl_samples.vlSampleID=vl_results_roche.SampleID and vl_samples.id in (select sampleID from vl_logs_printedresults)","count(vl_results_roche.SampleID)","num");
$resultsNotPrinted=0;
$resultsNotPrinted=getDetailedTableInfo3("vl_results_abbott,vl_samples","vl_samples.vlSampleID=vl_results_abbott.sampleID and vl_samples.id not in (select sampleID from vl_logs_printedresults)","count(vl_results_abbott.sampleID)","num")+getDetailedTableInfo3("vl_results_roche,vl_samples","vl_samples.vlSampleID=vl_results_roche.SampleID and vl_samples.id not in (select sampleID from vl_logs_printedresults)","count(vl_results_roche.SampleID)","num");

//pages
if(!$pg) {
	$pg=1;
}

$offset=0;
$offset=($pg-1)*$rowsToDisplay;

//proceed with query
$rawQuery=0;
$xRawQuery=0;
$query=0;
$xquery=0;
if(!$searchQuery) {
	$rawQuery="select distinct vl_samples.*,vl_samples_verify.outcomeReasonsID outcomeReasonsID from 
								vl_samples_verify,vl_samples 
									where 
										vl_samples.id=vl_samples_verify.sampleID and 
											".($facilityID?"vl_samples.facilityID='$facilityID' and ":"")."
												vl_samples_verify.outcome='Rejected' 
													".($showPrinted?" and vl_samples.id in (select sampleID from vl_logs_printedrejectedresults)":" and vl_samples.id not in (select sampleID from vl_logs_printedrejectedresults)")." 
														order by if(vl_samples.lrCategory='',1,0),vl_samples.lrCategory, if(vl_samples.lrEnvelopeNumber='',1,0),vl_samples.lrEnvelopeNumber, if(vl_samples.lrNumericID='',1,0),vl_samples.lrNumericID, vl_samples_verify.created desc limit $offset, $rowsToDisplay";
	$xRawQuery="select distinct vl_samples.*,vl_samples_verify.outcomeReasonsID outcomeReasonsID from 
								vl_samples_verify,vl_samples 
									where 
										vl_samples.id=vl_samples_verify.sampleID and 
											".($facilityID?"vl_samples.facilityID='$facilityID' and ":"")."
												vl_samples_verify.outcome='Rejected' 
													".($showPrinted?" and vl_samples.id in (select sampleID from vl_logs_printedrejectedresults)":" and vl_samples.id not in (select sampleID from vl_logs_printedrejectedresults)")." 
														order by if(vl_samples.lrCategory='',1,0),vl_samples.lrCategory, if(vl_samples.lrEnvelopeNumber='',1,0),vl_samples.lrEnvelopeNumber, if(vl_samples.lrNumericID='',1,0),vl_samples.lrNumericID, vl_samples_verify.created desc";
	$query=mysqlquery($rawQuery);
	$xquery=mysqlquery($xRawQuery);
} else {
	$rawQuery="select distinct vl_samples.*,vl_samples_verify.outcomeReasonsID outcomeReasonsID from 
								vl_samples_verify,vl_samples,vl_patients 
									where 
										vl_samples.id=vl_samples_verify.sampleID and 
											vl_samples.patientID=vl_patients.id and 
												vl_samples_verify.outcome='Rejected' and 
													".($facilityID?"vl_samples.facilityID='$facilityID' and ":"")."
														(vl_samples.vlSampleID like '%$searchQuery%' or 
															vl_samples.formNumber like '%$searchQuery%' or 
																vl_patients.artNumber like '%$searchQuery%' or 
																	vl_patients.otherID like '%$searchQuery%') 
																		".($showPrinted?" and vl_samples.id in (select sampleID from vl_logs_printedrejectedresults)":" and vl_samples.id not in (select sampleID from vl_logs_printedrejectedresults)")." 
																			order by if(vl_samples.lrCategory='',1,0),vl_samples.lrCategory, if(vl_samples.lrEnvelopeNumber='',1,0),vl_samples.lrEnvelopeNumber, if(vl_samples.lrNumericID='',1,0),vl_samples.lrNumericID, vl_samples_verify.created desc limit $offset, $rowsToDisplay";
	$xRawQuery="select distinct vl_samples.*,vl_samples_verify.outcomeReasonsID outcomeReasonsID from 
								vl_samples_verify,vl_samples,vl_patients 
									where 
										vl_samples.id=vl_samples_verify.sampleID and 
											vl_samples.patientID=vl_patients.id and 
												vl_samples_verify.outcome='Rejected' and 
													".($facilityID?"vl_samples.facilityID='$facilityID' and ":"")."
														(vl_samples.vlSampleID like '%$searchQuery%' or 
															vl_samples.formNumber like '%$searchQuery%' or 
																vl_patients.artNumber like '%$searchQuery%' or 
																	vl_patients.otherID like '%$searchQuery%') 
																		".($showPrinted?" and vl_samples.id in (select sampleID from vl_logs_printedrejectedresults)":" and vl_samples.id not in (select sampleID from vl_logs_printedrejectedresults)")." 
																			order by if(vl_samples.lrCategory='',1,0),vl_samples.lrCategory, if(vl_samples.lrEnvelopeNumber='',1,0),vl_samples.lrEnvelopeNumber, if(vl_samples.lrNumericID='',1,0),vl_samples.lrNumericID, vl_samples_verify.created desc";
	$query=mysqlquery($rawQuery);
	$xquery=mysqlquery($xRawQuery);
}
//number pages
$numberRecords=0;
$numberRecords=mysqlnumrows($xquery);
$numberPages=0;
$numberPages=ceil($numberRecords/$rowsToDisplay);
?>
<script Language="JavaScript" Type="text/javascript">
<!--
function validate(results) {
	//ammend the form action variable
	if(document.pressed == '  Print Results (Batch)  ') {
		document.results.target = "_blank";
		document.results.action ="/results/print.rejected/batch/";
	} else if(document.pressed == '  Dispatch Selected Results  ') {
		document.results.action ="/results/dispatch/";
	} else if(document.pressed == '  Dispatch All <?=number_format((float)$numberRecords)?> Results  ') {
		document.results.action = "/results/dispatch/all/";
	} else if(document.pressed == '  Filter  ') {
		document.results.action = "/results/<?=$machineType?>/pg/<?=$pg?>/0/"+(document.results.facilityID.value?document.results.facilityID.value:0)+"/";
	}
	return (true);
}
//-->
</script>
<form name="results" method="post" action="/results/print.rejected/batch/" onsubmit="return validate(this)">
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
                      <td width="5%" align="right" style="padding:0px 3px 3px 0px"><select name="facilityID" id="facilityID" class="search">
                                            <?
                                            $queryF=0;
                                            $queryF=mysqlquery("select * from vl_facilities where facility!='' order by facility");
											if($facilityID) {
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
                                  <td class="bluetab_active"><?="Abbott&nbsp;(".number_format((float)$resultsAbbott).")"?></td>
                                  <? } else { ?>
                                  <td class="bluetab_inactive"><a href="/results/abbott/">
                                    <?="Abbott&nbsp;(".number_format((float)$resultsAbbott).")"?>
                                  </a></td>
                                  <? } ?>
                                  <td bgcolor="#C4CCCC"><img src="/images/spacer.gif" width="1" height="1" /></td>
                                  <? if($machineType=="roche") { ?>
                                  <td class="bluetab_active"><?="Roche&nbsp;(".number_format((float)$resultsRoche).")"?></td>
                                  <? } else { ?>
                                  <td class="bluetab_inactive"><a href="/results/roche/">
                                    <?="Roche&nbsp;(".number_format((float)$resultsRoche).")"?>
                                  </a></td>
                                  <? } ?>
                                  <td bgcolor="#C4CCCC"><img src="/images/spacer.gif" width="1" height="1" /></td>
                                  <? if($machineType=="rejected") { ?>
                                  <td class="bluetab_active"><?="Rejected&nbsp;Samples&nbsp;(".number_format((float)$rejectedSamples).")"?></td>
                                  <? } else { ?>
                                  <td class="bluetab_inactive"><a href="/results/rejected/">
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
                              <? if(mysqlnumrows($query)) { ?>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td class="vl_tdsub" width="1%"><input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"></td>
                                      <td class="vl_tdsub" width="1%"><strong>#</strong></td>
                                      <td class="vl_tdsub" width="10%"><strong>Sample&nbsp;Details</strong></td>
                                      <td class="vl_tdsub" width="10%"><strong>Patient&nbsp;Details</strong></td>
                                      <td class="vl_tdsub" width="5%"><strong>Status</strong></td>
                                      <td class="vl_tdsub" width="58%"><strong>Rejection&nbsp;Reason</strong></td>
                                      <td class="vl_tdsub" width="2%"><strong>Dispatched</strong></td>
                                      <td class="vl_tdsub" width="3%"><strong>Printed</strong></td>
                                      <td class="vl_tdsub" width="10%"><strong>Actions</strong></td>
                                    </tr>
                                    <?
                                    $count=0;
                                    $count=$offset;
                                    $q=array();
                                    while($q=mysqlfetcharray($query)) {
                                        $count+=1;
                                        $formNumber=0;
                                        $formNumber=$q["formNumber"];
                                        $sampleID=0;
                                        $sampleID=$q["id"];
                                        $sampleReferenceNumber=0;
                                        $sampleReferenceNumber=$q["vlSampleID"];
                                        $patientID=0;
                                        $patientID=$q["patientID"];
                                        $patientART=0;
                                        $patientART=getDetailedTableInfo2("vl_patients","id='$patientID'","artNumber");
                                        $otherID=0;
                                        $otherID=getDetailedTableInfo2("vl_patients","id='$patientID'","otherID");
                    
                                        $rejectionReason=0;
                                        $rejectionReason=getDetailedTableInfo2("vl_appendix_samplerejectionreason","id='$q[outcomeReasonsID]' and sampleTypeID='$q[sampleTypeID]'","appendix");
                    
                                        /*
                                        * 9/Feb/15: 
                                        * Issac Ssewanyana (sewyisaac@yahoo.co.uk, CPHL) suggested the introduction of a location and 
                                        * rejection ID hence the introduction of the variables below
                                        */
                                        $rejectionID=0;
                                        $rejectionID=getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","lrCategory").getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","lrEnvelopeNumber").(getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","lrNumericID")?"/".getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","lrNumericID"):"");
                    
                                        //dispatched
                                        $dispatched=0;
                                        if(getDetailedTableInfo2("vl_logs_dispatchedresults","sampleID='$sampleID' limit 1","id")) {
                                            $dispatched="<a href=\"#\" onclick=\"iDisplayMessage('/results/preview.dispatched/$sampleID/0/')\"><font color=\"#009900\">Yes</font></a>";
                                        } else {
                                            $dispatched="<font color=\"#FF0000\">No</font>";
                                        }
                                        
                                        //printed
                                        $printed=0;
                                        if(getDetailedTableInfo2("vl_logs_printedrejectedresults","sampleID='$sampleID' limit 1","id")) {
                                            $printed="<font color=\"#009900\">Yes</font>";
                                        } else {
                                            $printed="<font color=\"#FF0000\">No</font>";
                                        }
										
										//facility name
										$facilityName=0;
										$facilityName=getDetailedTableInfo2("vl_facilities","id='".getDetailedTableInfo2("vl_samples","id='$sampleID' limit 1","facilityID")."' limit 1","facility");
                                    ?>
                                        <tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
                                            <td class="vl_tdstandard"><input name="sampleResultCheckbox[]" type="checkbox" id="sampleResultCheckbox[]" value="<?=$sampleID?>"></td>
                                            <td class="vl_tdstandard"><?=$count?></td>
                                            <td class="vl_tdstandard">
                                                <div><strong>Form&nbsp;Number:</strong>&nbsp;<a href="#" onclick="iDisplayMessage('/verify/preview/<?=$sampleID?>/<?=$pg?>/noedit/')"><?=$formNumber?></a></div>
                                                <div class="vls_grey" style="padding:3px 0px"><strong>Sample&nbsp;Ref&nbsp;#:</strong>&nbsp;<?=$sampleReferenceNumber?></div>
                                                <? if($rejectionID) { ?><div class="vls_grey" style="padding:0px 0px 3px 0px"><strong>Rejection&nbsp;ID:</strong>&nbsp;<?=$rejectionID?></div><? } ?>
                                                <div class="vls_grey" style="padding:0px 0px 3px 0px"><strong>Facility:</strong>&nbsp;<?=$facilityName?></div>
                                            </td>
                                            <td class="vl_tdstandard">
                                                <div><strong>ART&nbsp;#:</strong>&nbsp;<?=preg_replace("/ /s","&nbsp;",$patientART)?></div>
                                                <? if($otherID) { ?><div class="vls_grey" style="padding:3px 0px"><strong>Other&nbsp;ID:</strong>&nbsp;<?=$otherID?></div><? } ?>
                                            </td>
                                            <td class="vl_tdstandard"><font color="#FF0000">Rejected</font></td>
                                            <td class="vl_tdstandard"><?=$rejectionReason?></td>
                                            <td class="vl_tdstandard" align="center"><?=$dispatched?></td>
                                            <td class="vl_tdstandard" align="center"><?=$printed?></td>
                                            <td class="vl_tdstandard"><div class="vls_grey" style="padding:3px 0px 0px 0px"><a href="/results/print.rejected/<?=$sampleID?>/" target="_blank">Print&nbsp;Result</a>&nbsp;::&nbsp;<a href="/results/dispatch/<?=$sampleID?>/0/">Dispatch</a></div></td>
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
                <input type="submit" name="printResults" id="printResults" class="button" value="  Print Selected Results  " onclick="document.pressed=this.value" /> 
                <input type="submit" name="dispatchResults" id="dispatchResults" class="button" value="  Dispatch Selected Results  " onclick="document.pressed=this.value" /> 
                <? if($facilityID && $numberPages>1) { ?><input type="submit" name="dispatchResults" id="dispatchResults" class="button" value="  Dispatch All <?=number_format((float)$numberRecords)?> Results  " onclick="document.pressed=this.value" /><? } ?>
          </tr>
            <tr>
	            <td style="padding:10px 0px 0px 0px; border-top: 1px dashed #CCC"><a href="/dashboard/">Return to Dashboard</a></td>
            </tr>
		</table>	
              </td>
            </tr>
          </table>
</form>