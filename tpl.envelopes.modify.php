<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

//validation
$modify=validate($modify);

if($saveEnvelope) {
	//validate data
	$error=0;
	$error=checkFormFields("Envelope_Number::$envelopeNumber Batch_Number::$batchNumber Facility_Name::$facilityID");

	//is this Envelopes's envelopeNumber unique
	if(getDetailedTableInfo2("vl_envelopes","envelopeNumber='$envelopeNumber' and id!='$modify' limit 1","id")) {
		$error.="<br />Another Envelope with the same Envelope Number <strong>$envelopeNumber</strong> already exists.<br />Kindly provide an alternative Envelope Number<br />";
	}
	
	//is this Batch's batchNumber unique
	if(getDetailedTableInfo2("vl_envelopes","batchNumber='$batchNumber' and id!='$modify' limit 1","id")) {
		$error.="<br />Another Envelope with the same Batch Number <strong>$batchNumber</strong> already exists.<br />Kindly provide an alternative Batch Number<br />";
	}
	
	//input data
	if(!$error) {
		//formate date
		$currentDateTime=0;
		$currentDateTime="$receivedDateYear-$receivedDateMonth-$receivedDateDay ".getFormattedTime(getDetailedTableInfo2("vl_envelopes","id='$modify' limit 1","created"));

		//log changes
		logTableChange("vl_envelopes","envelopeNumber",$modify,getDetailedTableInfo2("vl_envelopes","id='$modify'","envelopeNumber"),$envelopeNumber);
		logTableChange("vl_envelopes","batchNumber",$modify,getDetailedTableInfo2("vl_envelopes","id='$modify'","batchNumber"),$batchNumber);
		logTableChange("vl_envelopes","districtID",$modify,getDetailedTableInfo2("vl_envelopes","id='$modify'","districtID"),$districtID);
		logTableChange("vl_envelopes","hubID",$modify,getDetailedTableInfo2("vl_envelopes","id='$modify'","hubID"),$hubID);
		logTableChange("vl_envelopes","facilityID",$modify,getDetailedTableInfo2("vl_envelopes","id='$modify'","facilityID"),$facilityID);
		logTableChange("vl_envelopes","created",$modify,getDetailedTableInfo2("vl_envelopes","id='$modify'","created"),$currentDateTime);

		mysqlquery("update vl_envelopes set 
						envelopeNumber='$envelopeNumber', 
						batchNumber='$batchNumber', 
						districtID='$districtID', 
						hubID='$hubID', 
						facilityID='$facilityID', 
						created='$currentDateTime' 
						where 
						id='$modify'");

		//redirect to home with updates on the tracking number
		go("/envelopes/modified/");
	}
} else {
	//prepopulate
	$envelopeNumber=0;
	$envelopeNumber=getDetailedTableInfo2("vl_envelopes","id='$modify' limit 1","envelopeNumber");
	
	$batchNumber=0;
	$batchNumber=getDetailedTableInfo2("vl_envelopes","id='$modify' limit 1","batchNumber");

	$districtID=0;
	$districtID=getDetailedTableInfo2("vl_envelopes","id='$modify' limit 1","districtID");

	$hubID=0;
	$hubID=getDetailedTableInfo2("vl_envelopes","id='$modify' limit 1","hubID");

	$facilityID=0;
	$facilityID=getDetailedTableInfo2("vl_envelopes","id='$modify' limit 1","facilityID");

	$created=0;
	$created=getDetailedTableInfo2("vl_envelopes","id='$modify' limit 1","created");
}
?>
<script Language="JavaScript" Type="text/javascript">
<!--
function validate(envelopes) {
	//check for missing information
	if(!document.envelopes.envelopeNumber.value) {
		alert('Missing Mandatory Field: Envelope Code');
		document.envelopes.envelopeNumber.focus();
		return (false);
	}
	if(!document.envelopes.batchNumber.value) {
		alert('Missing Mandatory Field: Envelope Pack Size');
		document.envelopes.batchNumber.focus();
		return (false);
	}
	if(!document.envelopes.facilityID.value) {
		alert('Missing Mandatory Field: Facility Name');
		document.envelopes.facilityID.focus();
		return (false);
	}
	return (true);
}
//-->
</script>
<form name="envelopes" method="post" action="/envelopes/manage/modify/<?=$modify?>/" onsubmit="return validate(this)">
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
              <td class="toplinks" style="padding:0px 0px 10px 0px"><a class="toplinks" href="/dashboard/">HOME</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/envelopes/">CONTRACTS</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/envelopes/modify/">Contracts&nbsp;Modify</a></td>
            </tr>
            <tr>
                <td><strong>Modify Contract</strong></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                  <fieldset style="width: 85%">
                    <legend><strong>ENVELOPE SPECIFICATIONS</strong></legend>
                      <table width="100%" border="0" class="vl">
                        <tr>
                          <td width="20%">Envelope&nbsp;#&nbsp;<font class="vl_red">*</font></td>
                          <td width="80%"><input type="text" name="envelopeNumber" id="envelopeNumber" value="<?=$envelopeNumber?>" class="search_pre" size="20" maxlength="100" /></td>
                        </tr>
                        <tr>
                          <td>Batch&nbsp;#&nbsp;<font class="vl_red">*</font></td>
                          <td><input type="text" name="batchNumber" id="batchNumber" value="<?=$batchNumber?>" class="search_pre" size="20" maxlength="100" /></td>
                        </tr>
                        <tr>
<td>Facility&nbsp;Name&nbsp;<font class="vl_red">*</font></td>
                          <td>
                          <select name="facilityID" id="facilityID" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_facilities where facility!='' order by facility");
								if($facilityID) {
									echo "<option value=\"$facilityID\" selected=\"selected\">".getDetailedTableInfo2("vl_facilities","id='$facilityID'","facility")."</option>";
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
                        </tr>
                        <tr>
                          <td>Hub</td>
                          <td>
                          <select name="hubID" id="hubID" class="search">
                                <?
								$query=0;
								$query=mysqlquery("select * from vl_hubs order by hub");
								if($hubID) {
									echo "<option value=\"$hubID\" selected=\"selected\">".getDetailedTableInfo2("vl_hubs","id='$hubID'","hub")."</option>";
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
									echo "<option value=\"$districtID\" selected=\"selected\">".getDetailedTableInfo2("vl_districts","id='$districtID'","district")."</option>";
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
                        <tr>
                          <td>Date&nbsp;Received&nbsp;<font class="vl_red">*</font></td>
                          <td>
<table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td><select name="receivedDateDay" id="receivedDateDay" class="search">
                                          <?
                                            echo "<option value=\"".getFormattedDateDay($created)."\" selected=\"selected\">".getFormattedDateDay($created)."</option>";
											for($j=1;$j<=31;$j++) {
                                                echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
                                            }
                                            ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="receivedDateMonth" id="receivedDateMonth" class="search">
                                          <? echo "<option value=\"".getFormattedDateMonth($created)."\" selected=\"selected\">".getFormattedDateMonthname($created)."</option>"; ?>
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
                                        <td style="padding:0px 0px 0px 5px"><select name="receivedDateYear" id="receivedDateYear" class="search">
                                          <?
										  		echo "<option value=\"".getFormattedDateYear($created)."\" selected=\"selected\">".getFormattedDateYear($created)."</option>";
                                                for($j=getFormattedDateYear($datetime);$j>=(getCurrentYear()-10);$j--) {
                                                    echo "<option value=\"$j\">$j</option>";
                                                }
                                                ?>
                                          </select></td>
                                        </tr>
                                    </table></td>
                        </tr>
                      </table>
                    </fieldset>
                </td>
            </tr>
            <tr>
              <td style="padding:10px 0px 0px 0px"><input type="submit" name="saveEnvelope" id="saveEnvelope" class="button" value="     Save Changes to Envelope     " /></td>
            </tr>
            <tr>
	            <td style="padding:20px 0px 0px 0px"><a href="/envelopes/">Return to Envelopes</a> :: <a href="/dashboard/">Return to Dashboard</a></td>
            </tr>
          </table>
</form>