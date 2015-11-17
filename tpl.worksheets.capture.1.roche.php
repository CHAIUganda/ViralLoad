<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

//validation
/*
* 8/Sept/14: 
* Hellen (hnansumba@gmail.com, CPHL) requested the Worksheet Name be removed given it is
* a duplicate of the Worksheet Reference Number
*/
//$worksheetName=validate($worksheetName);
$worksheetReferenceNumber=validate($worksheetReferenceNumber);
$worksheetType=validate($worksheetType);
$machineType=validate($machineType);

$samplePrep=validate($samplePrep);
$samplePrepExpiryDateDay=validate($samplePrepExpiryDateDay);
$samplePrepExpiryDateMonth=validate($samplePrepExpiryDateMonth);
$samplePrepExpiryDateYear=validate($samplePrepExpiryDateYear);

$bulkLysisBuffer=validate($bulkLysisBuffer);
$bulkLysisBufferExpiryDateDay=validate($bulkLysisBufferExpiryDateDay);
$bulkLysisBufferExpiryDateMonth=validate($bulkLysisBufferExpiryDateMonth);
$bulkLysisBufferExpiryDateYear=validate($bulkLysisBufferExpiryDateYear);

$assayDateDay=validate($assayDateDay);
$assayDateMonth=validate($assayDateMonth);
$assayDateYear=validate($assayDateYear);

$includeCalibrators=validate($includeCalibrators);

if($proceed) {
	//validate data
	$samplePrepExpiryDate=0;
	$samplePrepExpiryDate="$samplePrepExpiryDateYear-$samplePrepExpiryDateMonth-$samplePrepExpiryDateDay";
	$bulkLysisBufferExpiryDate=0;
	$bulkLysisBufferExpiryDate="$bulkLysisBufferExpiryDateYear-$bulkLysisBufferExpiryDateMonth-$bulkLysisBufferExpiryDateDay";
	$assayDate=0;
	$assayDate="$assayDateYear-$assayDateMonth-$assayDateDay";

	//errors
	$error=0;
	$error=checkFormFields("Worksheet_Reference_Number::$worksheetReferenceNumber Worksheet_Type::$worksheetType Machine_Type::$machineType HI2CAP::$samplePrep HI2CAP_Expiry_Date::$samplePrepExpiryDate");
	
	//is this Envelopes's envelopeNumber unique
	if(getDetailedTableInfo2("vl_samples_worksheetcredentials","worksheetType='$worksheetType' and worksheetReferenceNumber='$worksheetReferenceNumber' limit 1","id")) {
		$error.="<br />Another Worksheet with the same Reference and Type i.e <strong>$worksheetReferenceNumber and $worksheetType</strong> (resp) already exists.<br />Kindly select an alternative Name, Reference Number or Type<br />";
	}
	
	//input data
	if(!$error) {
		//first time insertion
		mysqlquery("insert into vl_samples_worksheetcredentials 
						(worksheetReferenceNumber,worksheetType,samplePrep,samplePrepExpiryDate,
							bulkLysisBuffer,bulkLysisBufferExpiryDate,
							assayDate,includeCalibrators,machineType,created,createdby)
						values 
						('$worksheetReferenceNumber','$worksheetType','$samplePrep','$samplePrepExpiryDate',
							'$bulkLysisBuffer','$bulkLysisBufferExpiryDate',
							'$assayDate','".($includeCalibrators==1?1:0)."','$machineType','$datetime','$trailSessionUser')");
		//worksheet ID
		$worksheetID=0;
		$worksheetID=getDetailedTableInfo2("vl_samples_worksheetcredentials","worksheetType='$worksheetType' and worksheetReferenceNumber='$worksheetReferenceNumber' order by created desc limit 1","id");
		//redirect
		go("/worksheets/capture.2/$worksheetID/");
	}
}
?>
<script Language="JavaScript" Type="text/javascript">
<!--
function validate(worksheets) {
	//check for missing information
	/*
	if(!document.worksheets.worksheetName.value) {
		alert('Missing Mandatory Field: Worksheet Name');
		document.worksheets.worksheetName.focus();
		return (false);
	}
	*/
	if(!document.worksheets.worksheetReferenceNumber.value) {
		alert('Missing Mandatory Field: Worksheet Reference Number');
		document.worksheets.worksheetReferenceNumber.focus();
		return (false);
	}
	if(!document.worksheets.worksheetType.value) {
		alert('Missing Mandatory Field: Worksheet Type');
		document.worksheets.worksheetType.focus();
		return (false);
	}
	if(!document.worksheets.machineType.value) {
		alert('Missing Mandatory Field: Machine Type');
		document.worksheets.machineType.focus();
		return (false);
	}
	if(!document.worksheets.samplePrep.value) {
		alert('Missing Mandatory Field: HI2CAP');
		document.worksheets.samplePrep.focus();
		return (false);
	}
	if(!document.worksheets.samplePrepExpiryDateDay.value || !document.worksheets.samplePrepExpiryDateMonth.value || !document.worksheets.samplePrepExpiryDateYear.value) {
		alert('Missing Mandatory Field: HI2CAP Expiry Date');
		document.worksheets.samplePrepExpiryDateDay.focus();
		return (false);
	}
	if(document.worksheets.machineType.value!='roche' && !document.worksheets.calibrator.value) {
		alert('Missing Mandatory Field: Calibrator');
		document.worksheets.calibrator.focus();
		return (false);
	}
	if(document.worksheets.machineType.value!='roche' && (!document.worksheets.calibratorExpiryDateDay.value || !document.worksheets.calibratorExpiryDateMonth.value || !document.worksheets.calibratorExpiryDateYear.value)) {
		alert('Missing Mandatory Field: Calibrator Expiry Date');
		document.worksheets.calibratorExpiryDateDay.focus();
		return (false);
	}
	if(!document.worksheets.assayDateDay.value || !document.worksheets.assayDateMonth.value || !document.worksheets.assayDateYear.value) {
		alert('Missing Mandatory Field: Asa Date');
		document.worksheets.assayDateDay.focus();
		return (false);
	}
	return (true);
}
//-->
</script>
<!--<form name="worksheets" method="post" action="/worksheets/capture.1.roche/" onsubmit="return validate(this)">-->
<form name="worksheets" method="post" action="/worksheets/capture.1.roche/">
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
                    <legend><strong>STEP 1/3: WORKSHEET CREDENTIALS</strong> (Next Step >> Select Worksheet Samples)</legend>
                      <table width="100%" border="0" class="vl">
                      	<!--
                        <tr>
                          <td width="20%" style="padding:0px 0px 5px 0px">Worksheet&nbsp;Name&nbsp;<font class="vl_red">*</font></td>
                          <td width="80%" style="padding:0px 0px 5px 0px"><input type="text" name="worksheetName" id="worksheetName" value="<?=$worksheetName?>" class="search_pre" size="20" maxlength="100" /></td>
                        </tr>
                        -->
                        <tr>
                          <td width="20%" style="padding:0px 0px 5px 0px">Worksheet&nbsp;Reference&nbsp;Number&nbsp;<font class="vl_red">*</font></td>
                          <td width="80%" style="padding:0px 0px 5px 0px"><input type="text" name="worksheetReferenceNumber" id="worksheetReferenceNumber" value="<?=generateWorksheetReferenceNumber()?>" class="search_pre" size="20" maxlength="100" /></td>
                        </tr>
                        <tr>
                          <td style="padding:0px 0px 5px 0px">Machine&nbsp;Type&nbsp;<font class="vl_red">*</font></td>
                          <td style="padding:0px 0px 5px 0px">Roche <input name="machineType" id="machineType" value="roche" type="hidden" /></td>
                        </tr>
                        <tr>
                          <td style="padding:0px 0px 15px 0px; border-bottom: 1px dashed #dfe6e6">Worksheet&nbsp;Type&nbsp;<font class="vl_red">*</font></td>
                          <td style="padding:0px 0px 15px 0px; border-bottom: 1px dashed #dfe6e6">
								<select name="worksheetType" id="worksheetType" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_appendix_sampletype order by position");
								if($worksheetType) {
									echo "<option value=\"$worksheetType\" selected=\"selected\">".getDetailedTableInfo2("vl_appendix_sampletype","id='$worksheetType' limit 1","appendix")."</option>";
								} else {
									echo "<option value=\"\" selected=\"selected\">Select Worksheet Type</option>";
								}
								if(mysqlnumrows($query)) {
									while($q=mysqlfetcharray($query)) {
										echo "<option value=\"$q[id]\">$q[appendix]</option>";
									}
								}
								?>
                                </select></td>
                        </tr>

                        <tr>
                          <td style="padding:10px 0px 5px 0px">HI2CAP&nbsp;<font class="vl_red">*</font></td>
                          <td style="padding:10px 0px 5px 0px"><input type="text" name="samplePrep" id="samplePrep" value="<?=$samplePrep?>" class="search_pre" size="20" maxlength="100" /></td>
                        </tr>
                        <tr>
                          <td style="padding:0px 0px 15px 0px; border-bottom: 1px dashed #dfe6e6">HI2CAP&nbsp;Expiry&nbsp;Date&nbsp;<font class="vl_red">*</font></td>
                          <td style="padding:0px 0px 15px 0px; border-bottom: 1px dashed #dfe6e6">
								<table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td><select name="samplePrepExpiryDateDay" id="samplePrepExpiryDateDay" class="search">
                                          <?
											if($samplePrepExpiryDate) {
												echo "<option value=\"".getFormattedDateDay($samplePrepExpiryDate)."\" selected=\"selected\">".getFormattedDateDay($samplePrepExpiryDate)."</option>";
											} else {
												echo "<option value=\"\" selected=\"selected\">Select Day</option>";
											}
											for($j=1;$j<=31;$j++) {
												echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
											}
                                            ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px">
                                        <select name="samplePrepExpiryDateMonth" id="samplePrepExpiryDateMonth" class="search">
                                          	<? 
											if($samplePrepExpiryDate) {
												echo "<option value=\"".getFormattedDateMonth($samplePrepExpiryDate)."\" selected=\"selected\">".getFormattedDateMonthname($samplePrepExpiryDate)."</option>";
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
                                        <td style="padding:0px 0px 0px 5px"><select name="samplePrepExpiryDateYear" id="samplePrepExpiryDateYear" class="search">
                                          <?
											if($samplePrepExpiryDate) {
												echo "<option value=\"".getFormattedDateYear($samplePrepExpiryDate)."\" selected=\"selected\">".getFormattedDateYear($samplePrepExpiryDate)."</option>";
											} else {
												echo "<option value=\"\" selected=\"selected\">Select Year</option>"; 
											}
											
											for($j=getFormattedDateYear($datetime);$j<=(getCurrentYear()+10);$j++) {
												echo "<option value=\"$j\">$j</option>";
											}
                                            ?>
                                          </select></td>
                                        </tr>
                                    </table></td>
                        </tr>

                        <tr>
                          <td style="padding:10px 0px 5px 0px">Bulk&nbsp;Lysis&nbsp;Buffer</td>
                          <td style="padding:10px 0px 5px 0px"><input type="text" name="bulkLysisBuffer" id="bulkLysisBuffer" value="<?=$bulkLysisBuffer?>" class="search_pre" size="20" maxlength="100" /></td>
                        </tr>
                        <tr>
                          <td style="padding:0px 0px 15px 0px; border-bottom: 1px dashed #dfe6e6">Bulk&nbsp;Lysis&nbsp;Buffer&nbsp;Expiry&nbsp;Date</td>
                          <td style="padding:0px 0px 15px 0px; border-bottom: 1px dashed #dfe6e6">
								<table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td><select name="bulkLysisBufferExpiryDateDay" id="bulkLysisBufferExpiryDateDay" class="search">
                                          <?
											if($bulkLysisBufferExpiryDate) {
												echo "<option value=\"".getFormattedDateDay($bulkLysisBufferExpiryDate)."\" selected=\"selected\">".getFormattedDateDay($bulkLysisBufferExpiryDate)."</option>";
											} else {
												echo "<option value=\"\" selected=\"selected\">Select Day</option>";
											}
											for($j=1;$j<=31;$j++) {
												echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
											}
                                            ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px">
                                        <select name="bulkLysisBufferExpiryDateMonth" id="bulkLysisBufferExpiryDateMonth" class="search">
                                          	<? 
											if($bulkLysisBufferExpiryDate) {
												echo "<option value=\"".getFormattedDateMonth($bulkLysisBufferExpiryDate)."\" selected=\"selected\">".getFormattedDateMonthname($bulkLysisBufferExpiryDate)."</option>";
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
                                        <td style="padding:0px 0px 0px 5px"><select name="bulkLysisBufferExpiryDateYear" id="bulkLysisBufferExpiryDateYear" class="search">
                                          <?
											if($bulkLysisBufferExpiryDate) {
												echo "<option value=\"".getFormattedDateYear($bulkLysisBufferExpiryDate)."\" selected=\"selected\">".getFormattedDateYear($bulkLysisBufferExpiryDate)."</option>";
											} else {
												echo "<option value=\"\" selected=\"selected\">Select Year</option>"; 
											}
											
											for($j=getFormattedDateYear($datetime);$j<=(getCurrentYear()+10);$j++) {
												echo "<option value=\"$j\">$j</option>";
											}
                                            ?>
                                          </select></td>
                                        </tr>
                                    </table></td>
                        </tr>

                        <tr>
                          <td style="padding:10px 0px 15px 0px; border-bottom: 1px dashed #dfe6e6">Assay&nbsp;Date&nbsp;<font class="vl_red">*</font></td>
                          <td style="padding:10px 0px 15px 0px; border-bottom: 1px dashed #dfe6e6">
								<table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td><select name="assayDateDay" id="assayDateDay" class="search">
                                          <?
											if($assayDate) {
												echo "<option value=\"".getFormattedDateDay($assayDate)."\" selected=\"selected\">".getFormattedDateDay($assayDate)."</option>";
											} else {
												echo "<option value=\"\" selected=\"selected\">Select Day</option>";
											}
											for($j=1;$j<=31;$j++) {
												echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
											}
                                            ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px">
                                        <select name="assayDateMonth" id="assayDateMonth" class="search">
                                          	<? 
											if($assayDate) {
												echo "<option value=\"".getFormattedDateMonth($assayDate)."\" selected=\"selected\">".getFormattedDateMonthname($assayDate)."</option>";
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
                                        <td style="padding:0px 0px 0px 5px"><select name="assayDateYear" id="assayDateYear" class="search">
                                          <?
											if($assayDate) {
												echo "<option value=\"".getFormattedDateYear($assayDate)."\" selected=\"selected\">".getFormattedDateYear($assayDate)."</option>";
											} else {
												echo "<option value=\"\" selected=\"selected\">Select Year</option>"; 
											}
											
											for($j=getFormattedDateYear($datetime);$j<=(getCurrentYear()+10);$j++) {
												echo "<option value=\"$j\">$j</option>";
											}
                                            ?>
                                          </select></td>
                                        </tr>
                                    </table></td>
                        </tr>
                        <tr>
                          <td style="padding:10px 0px 5px 0px">Include&nbsp;Calibrators&nbsp;in&nbsp;Worksheet</td>
                          <td style="padding:10px 0px 5px 0px"><input name="includeCalibrators" id="includeCalibrators" type="checkbox" value="1" <?=($includeCalibrators?"checked=\"checked\"":"")?> /></td>
                        </tr>
                      </table>
                    </fieldset>
                </td>
            </tr>
            <tr>
              <td style="padding:10px 0px 0px 0px"><input type="submit" name="proceed" id="proceed" class="button" value="  Proceed to Select Worksheet Samples  " /></td>
            </tr>
            <tr>
	            <td style="padding:20px 0px 0px 0px"><a href="/worksheets/">Return to Worksheets</a> :: <a href="/dashboard/">Return to Dashboard</a></td>
            </tr>
          </table>
</form>