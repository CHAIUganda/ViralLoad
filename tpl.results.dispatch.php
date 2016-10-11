<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

//validation
$sampleID=validate($sampleID);
$worksheetID=validate($worksheetID);
$facilityID=validate($facilityID);
$dispatchedDateDay=validate($dispatchedDateDay);
$dispatchedDateMonth=validate($dispatchedDateMonth);
$dispatchedDateYear=validate($dispatchedDateYear);
$all=validate($all);
$sampleIDField=validate($sampleIDField);
$machineType=validate($machineType);

$rawDecryptedQuery=0;
$rawDecryptedQuery=vlDecrypt($rawQuery);
$xRawDecryptedQuery=0;
$xRawDecryptedQuery=vlDecrypt($xRawQuery);
		
//if all, assign variables to $sampleResultHidden
if($all) {
	$query=0;
	$query=mysqlquery($xRawDecryptedQuery);
	if(mysqlnumrows($query)) {
		$sampleResultHidden=array();
		$sampleResultCheckbox=array();
		$q=array();
		while($q=mysqlfetcharray($query)) {
			//key variables
			if($machineType!="rejected") {
				$worksheetUniqueID=0;
				$worksheetUniqueID=$q["worksheetID"];
				//array assignment
				$sampleResultHidden[]=getDetailedTableInfo2("vl_samples","vlSampleID='$q[$sampleIDField]'","id")."|".$worksheetUniqueID;
				$sampleResultCheckbox[]=getDetailedTableInfo2("vl_samples","vlSampleID='$q[$sampleIDField]'","id")."|".$worksheetUniqueID;
			} else {
				//array assignment
				$sampleResultHidden[]=$q["id"];
				$sampleResultCheckbox[]=$q["id"];
			}
		}
	}
}

if($saveDispatchDate) {
	//validate data
	$error=0;
	$error=checkFormFields("Dispatched_Date::$dispatchedDateYear Dispatched_Date::$dispatchedDateMonth Dispatched_Date::$dispatchedDateDay Facility::$facilityID");

	//ensure facility is valid
	if(!getDetailedTableInfo2("vl_facilities","id='$facilityID'","id")) {
		$error.="<br /><strong>Incorrect Facility '$facilityID'.</strong><br />Kindly select an existing Facility from the list or Request an Administrator to first add this Facility '$facilityID' to the System's Database before Proceeding.<br />";
	}
	
	//input data
	if(!$error) {
		//formate date
		$dispatchedDate=0;
		$dispatchedDate="$dispatchedDateYear-$dispatchedDateMonth-$dispatchedDateDay";
		
		//add or update
		$added=0;
		$updated=0;
		if(count($sampleResultHidden)) {
			foreach($sampleResultHidden as $sample) {
				//split variable
				$sampleResult=array();
				$sampleResult=explode("|",$sample);
				//key variables
				$sampleID=0;
				$sampleID=validate($sampleResult[0]);
				$sampleReferenceNumber=0;
				$sampleReferenceNumber=getDetailedTableInfo2("vl_samples","id='$sampleID'","vlSampleID");
				$worksheetID=0;
				$worksheetID=validate($sampleResult[1]);
				//derive machine type
				if(!$machineType) {
					$machineType=0;
					if(getDetailedTableInfo2("vl_results_abbott","sampleID='$sampleReferenceNumber' and worksheetID='$worksheetID' limit 1","id")) {
						$machineType="abbott";
					} elseif(getDetailedTableInfo2("vl_results_roche","SampleID='$sampleReferenceNumber' and worksheetID='$worksheetID' limit 1","id")) {
						$machineType="roche";
					} else {
						$machineType="rejected";
					}
				}
				//log dispatch
				$id=0;
				$id=getDetailedTableInfo2("vl_logs_dispatchedresults","sampleID='$sampleID' and worksheetID='$worksheetID' limit 1","id");
				if(!$id) {
					mysqlquery("insert into vl_logs_dispatchedresults 
									(sampleID,worksheetID,facilityID,dateDispatched,created,createdby) 
									values 
									('$sampleID','$worksheetID','$facilityID','$dispatchedDate','$datetime','$trailSessionUser')");
					//flags
					$added+=1;
				} else {
					//log changes
					logTableChange("vl_logs_dispatchedresults","dateDispatched",$id,getDetailedTableInfo2("vl_logs_dispatchedresults","id='$id'","dateDispatched"),$dispatchedDate);
					logTableChange("vl_logs_dispatchedresults","facilityID",$id,getDetailedTableInfo2("vl_logs_dispatchedresults","id='$id'","facilityID"),$facilityID);
					mysqlquery("update vl_logs_dispatchedresults set 
												dispatchDate='$dispatchedDate',
												facilityID='$facilityID' 
												where 
												id='$id'");
					//flags
					$modified+=1;
				}
			}
		}

		//redirect to home with updates on the tracking number
		go("/results/dispatched/".($added?$added:"0")."/".($modified?$modified:"0")."/$machineType/");
	}
}
?>
<script Language="JavaScript" Type="text/javascript">
<!--
function validate(dispatchForm) {
	//check for missing information
	if(!document.dispatchForm.facilityID.value) {
		alert('Missing Mandatory Field: Destination Facility. Please select a known Facility from the list.');
		document.dispatchForm.facilityID.focus();
		return (false);
	}
	if(!document.dispatchForm.dispatchedDateDay.value || !document.dispatchForm.dispatchedDateMonth.value || !document.dispatchForm.dispatchedDateYear.value) {
		alert('Missing Mandatory Field: Date Dispatched');
		document.dispatchForm.dispatchedDateDay.focus();
		return (false);
	}
	return (true);
}
//-->
</script>
<!--<form name="dispatchForm" method="post" action="/results/dispatch/<?=($sampleID && $worksheetUniqueID?"$sampleID/$worksheetUniqueID/":"")?>" onsubmit="return validate(this)">-->
<form name="dispatchForm" method="post" action="/results/dispatch/<?=($sampleID && $worksheetUniqueID?"$sampleID/$worksheetUniqueID/":"")?>">
<table width="100%" border="0" class="vl">
			<? if($error) { ?>
            <tr>
                <td class="vl_error"><?=$error?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <? } ?>
            <tr>
              <td class="toplinks" style="padding:0px 0px 10px 0px"><a class="toplinks" href="/dashboard/">HOME</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/results/">RESULTS</a></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                  <fieldset style="width: 85%">
                    <legend><strong>DISPATCH CREDENTIALS</strong></legend>
                      <table width="100%" border="0" class="vl">
                        <tr>
                          <td width="20%">Date&nbsp;Dispatched&nbsp;<font class="vl_red">*</font></td>
                          <td width="80%">
									<table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td><select name="dispatchedDateDay" id="dispatchedDateDay" class="search">
                                          <?
                                            echo "<option value=\"".getFormattedDateDay($oldDispatchedDate?$oldDispatchedDate:$datetime)."\" selected=\"selected\">".getFormattedDateDay($oldDispatchedDate?$oldDispatchedDate:$datetime)."</option>";
											for($j=1;$j<=31;$j++) {
                                                echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
                                            }
                                            ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="dispatchedDateMonth" id="dispatchedDateMonth" class="search">
                                          <? echo "<option value=\"".getFormattedDateMonth($oldDispatchedDate?$oldDispatchedDate:$datetime)."\" selected=\"selected\">".getFormattedDateMonthname($oldDispatchedDate?$oldDispatchedDate:$datetime)."</option>"; ?>
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
                                        <td style="padding:0px 0px 0px 5px"><select name="dispatchedDateYear" id="dispatchedDateYear" class="search">
                                          <?
                                                for($j=getFormattedDateYear($oldDispatchedDate?$oldDispatchedDate:$datetime);$j>=(getCurrentYear()-10);$j--) {
                                                    echo "<option value=\"$j\">$j</option>";
                                                }
                                                ?>
                                          </select></td>
                                        </tr>
                                    </table></td>
                        </tr>
                        <tr>
                          <td>To&nbsp;Facility&nbsp;(Destination)&nbsp;<font class="vl_red">*</font></td>
                          <td><select name="facilityID" id="facilityID" class="search">
                                            <?
                                            $query=0;
                                            $query=mysqlquery("select * from vl_facilities where facility!='' order by facility");
											if($facilityID) {
												echo "<option value=\"$facilityID\" selected=\"selected\">".getDetailedTableInfo2("vl_facilities","id='$facilityID' limit 1","facility")."</option>";
											} else {
												echo "<option value=\"\" selected=\"selected\"></option>";
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
                        </tr>
                      </table>
                    </fieldset>
                </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <? 
			if($sampleID || count($sampleResultCheckbox)) { 
			?>
            <tr>
                <td>
                  <fieldset style="width: 85%">
                    <legend><strong>SELECTED SAMPLES</strong></legend>
					<div style="height: 250px; width: 100%; overflow: auto; padding:5px">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td class="vl_tdsub" width="1%"><strong>#</strong></td>
                              <td class="vl_tdsub" width="10%"><strong>Sample&nbsp;Details</strong></td>
                              <td class="vl_tdsub" width="10%"><strong>Patient&nbsp;Details</strong></td>
                              <td class="vl_tdsub" width="79%"><strong>Result</strong></td>
                            </tr>
                            <?
							if($sampleID) {
								$num=1;
								$count=1;
								$formNumber=0;
								$formNumber=getDetailedTableInfo2("vl_samples","id='$sampleID'","formNumber");
								$sampleReferenceNumber=0;
								$sampleReferenceNumber=getDetailedTableInfo2("vl_samples","id='$sampleID'","vlSampleID");
								$sampleTypeID=0;
								$sampleTypeID=getDetailedTableInfo2("vl_samples","id='$sampleID'","sampleTypeID");
								$patientID=0;
								$patientID=getDetailedTableInfo2("vl_samples","id='$sampleID'","patientID");
								$patientART=0;
								$patientART=getDetailedTableInfo2("vl_patients","id='$patientID'","artNumber");
								$otherID=0;
								$otherID=getDetailedTableInfo2("vl_patients","id='$patientID'","otherID");
								
								//derive machine type
								$machineType=0;
								if(getDetailedTableInfo2("vl_results_abbott","sampleID='$sampleReferenceNumber' and worksheetID='$worksheetID' limit 1","id")) {
									$machineType="abbott";
								} elseif(getDetailedTableInfo2("vl_results_roche","SampleID='$sampleReferenceNumber' and worksheetID='$worksheetID' limit 1","id")) {
									$machineType="roche";
								} else {
									$machineType="rejected";
								}

								//in case this is not a rejected sample
								$worksheetUniqueID=0;
								$worksheetReferenceNumber=0;
								$factor=0;
								$result=0;
								$rejectionReason=0;
								$outcomeReasonID=0;
								if($worksheetID) {
									//worksheets
									$worksheetUniqueID=$worksheetID;
									$worksheetReferenceNumber=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetUniqueID'","worksheetReferenceNumber");
									//factor
									$factor=getDetailedTableInfo2("vl_results_multiplicationfactor","worksheetID='$worksheetUniqueID' limit 1","factor");
									if(!$factor) {
										$factor=1;
									}
									//results
									$result=getVLResult($machineType,$worksheetID,$sampleReferenceNumber,$factor);
								} else {
									$outcomeReasonID=getDetailedTableInfo2("vl_samples_verify","sampleID='$sampleID' and outcome='Rejected' limit 1","outcomeReasonsID");
									$rejectionReason=getDetailedTableInfo2("vl_appendix_samplerejectionreason","id='$outcomeReasonID' and sampleTypeID='$sampleTypeID'","appendix");
								}
								?>
								<tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
									<td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>"><?=$count?><input name="sampleResultHidden[]" type="hidden" id="sampleResultHidden[]" value="<?=$sampleID."|".$worksheetUniqueID?>"></td>
									<td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>">
										<div><strong>Form&nbsp;Number:</strong>&nbsp;<a href="#" onclick="iDisplayMessage('/verify/preview/<?=$sampleID?>/1/noedit/')"><?=$formNumber?></a></div>
										<div class="vls_grey" style="padding:3px 0px"><strong>Sample&nbsp;Ref&nbsp;#:</strong>&nbsp;<?=$sampleReferenceNumber?></div>
										<? if($worksheetReferenceNumber) { ?><div class="vls_grey" style="padding:0px 0px 3px 0px"><strong>Worksheet&nbsp;Ref&nbsp;#:</strong>&nbsp;<?=$worksheetReferenceNumber?></div><? } ?>
									</td>
									<td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>">
										<div><strong>ART&nbsp;#:</strong>&nbsp;<?=preg_replace("/ /s","&nbsp;",$patientART)?></div>
										<? if($otherID) { ?><div class="vls_grey" style="padding:3px 0px"><strong>Other&nbsp;ID:</strong>&nbsp;<?=$otherID?></div><? } ?>
									</td>
									<td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>">
                                    	<? if($worksheetID) { ?>
										<div><strong>Result:</strong>&nbsp;<?=$result?></div>
										<div class="vls_grey" style="padding:3px 0px"><strong>Factor:</strong>&nbsp;x<?=$factor?></div>
                                        <? } else { ?>
										<div><font color="#990000">Rejected</font></div>
										<div class="vls_red" style="padding:3px 0px"><strong>Reason:</strong>&nbsp;<?=$rejectionReason?></div>
                                        <? } ?>
									</td>
								</tr>
								<?
							} elseif(count($sampleResultCheckbox)) {
								$count=0;
								$num=0;
								$num=count($sampleResultCheckbox);
								foreach($sampleResultCheckbox as $sample) {
									$count+=1;
									//split variable
									$sampleResult=array();
									$sampleResult=explode("|",$sample);
									//key variables
									$sampleID=0;
									$sampleID=validate($sampleResult[0]);
									$worksheetID=0;
									$worksheetID=validate($sampleResult[1]);
									//form credentials
									$formNumber=0;
									$formNumber=getDetailedTableInfo2("vl_samples","id='$sampleID'","formNumber");
									$sampleReferenceNumber=0;
									$sampleReferenceNumber=getDetailedTableInfo2("vl_samples","id='$sampleID'","vlSampleID");
									$sampleTypeID=0;
									$sampleTypeID=getDetailedTableInfo2("vl_samples","id='$sampleID'","sampleTypeID");
									$patientID=0;
									$patientID=getDetailedTableInfo2("vl_samples","id='$sampleID'","patientID");
									$patientART=0;
									$patientART=getDetailedTableInfo2("vl_patients","id='$patientID'","artNumber");
									$otherID=0;
									$otherID=getDetailedTableInfo2("vl_patients","id='$patientID'","otherID");

									//derive machine type
									$machineType=0;
									if(getDetailedTableInfo2("vl_results_abbott","sampleID='$sampleReferenceNumber' and worksheetID='$worksheetID' limit 1","id")) {
										$machineType="abbott";
									} elseif(getDetailedTableInfo2("vl_results_roche","SampleID='$sampleReferenceNumber' and worksheetID='$worksheetID' limit 1","id")) {
										$machineType="roche";
									} else {
										$machineType="rejected";
									}

									//in case this is not a rejected sample
									$worksheetUniqueID=0;
									$worksheetReferenceNumber=0;
									$factor=0;
									$result=0;
									$rejectionReason=0;
									$outcomeReasonID=0;
									if($worksheetID) {
										//worksheets
										$worksheetUniqueID=$worksheetID;
										$worksheetReferenceNumber=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetUniqueID'","worksheetReferenceNumber");
										//factor
										$factor=getDetailedTableInfo2("vl_results_multiplicationfactor","worksheetID='$worksheetUniqueID' limit 1","factor");
										if(!$factor) {
											$factor=1;
										}
										//results
										$result=getVLResult($machineType,$worksheetID,$sampleReferenceNumber,$factor);
									} else {
										$outcomeReasonID=getDetailedTableInfo2("vl_samples_verify","sampleID='$sampleID' and outcome='Rejected' limit 1","outcomeReasonsID");
										$rejectionReason=getDetailedTableInfo2("vl_appendix_samplerejectionreason","id='$outcomeReasonID' and sampleTypeID='$sampleTypeID'","appendix");
									}
									?>
									<tr onMouseover="this.bgColor='#f0e6dd'" onMouseout="this.bgColor='#FFFFFF'">
										<td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>"><?=$count?><input name="sampleResultHidden[]" type="hidden" id="sampleResultHidden[]" value="<?=$sampleID."|".$worksheetUniqueID?>"></td>
										<td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>">
											<div><strong>Form&nbsp;Number:</strong>&nbsp;<a href="#" onclick="iDisplayMessage('/verify/preview/<?=$sampleID?>/1/noedit/')"><?=$formNumber?></a></div>
											<div class="vls_grey" style="padding:3px 0px"><strong>Sample&nbsp;Ref&nbsp;#:</strong>&nbsp;<?=$sampleReferenceNumber?></div>
										<? if($worksheetReferenceNumber) { ?><div class="vls_grey" style="padding:0px 0px 3px 0px"><strong>Worksheet&nbsp;Ref&nbsp;#:</strong>&nbsp;<?=$worksheetReferenceNumber?></div><? } ?>
										</td>
										<td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>">
											<div><strong>ART&nbsp;#:</strong>&nbsp;<?=preg_replace("/ /s","&nbsp;",$patientART)?></div>
											<? if($otherID) { ?><div class="vls_grey" style="padding:3px 0px"><strong>Other&nbsp;ID:</strong>&nbsp;<?=$otherID?></div><? } ?>
										</td>
										<td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>">
											<? if($worksheetID) { ?>
                                            <div><strong>Result:</strong>&nbsp;<?=$result?></div>
                                            <div class="vls_grey" style="padding:3px 0px"><strong>Factor:</strong>&nbsp;x<?=$factor?></div>
                                            <? } else { ?>
                                            <div><font color="#990000">Rejected</font></div>
                                            <div class="vls_red" style="padding:3px 0px"><strong>Reason:</strong>&nbsp;<?=$rejectionReason?></div>
                                            <? } ?>
										</td>
									</tr>
									<?
								}
							}
							?>
                       </table>
                    </div>
                    </fieldset>
                </td>
            </tr>
            <tr>
              <td style="padding:10px 0px 0px 0px">
              <input type="hidden" name="machineType" id="machineType" value="<?=$machineType?>" />
              <input type="submit" name="saveDispatchDate" id="saveDispatchDate" class="button" value="  Save Dispatch Details  " />
              </td>
            </tr>
            <? } else { ?>
            <tr>
                <td class="vl_error">No Samples Found. <a href="/results/">Click here to teturn</a> and Select Samples to be Dispatched.</td>
            </tr>
            <? } ?>
            <tr>
	            <td style="padding:20px 0px 0px 0px"><a href="/results/">Return to Results</a> :: <a href="/dashboard/">Return to Dashboard</a></td>
            </tr>
          </table>
</form>