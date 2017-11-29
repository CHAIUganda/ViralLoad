<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

//validation
$serialNumberStart=validate($serialNumberStart);
$serialNumberEnd=validate($serialNumberEnd);
$dispatchedDateDay=validate($dispatchedDateDay);
$dispatchedDateMonth=validate($dispatchedDateMonth);
$dispatchedDateYear=validate($dispatchedDateYear);
$facilityID=validate($facilityID);

if($saveDate) {
	//validate data
	$error=0;
	$error=checkFormFields("Dispatched_Date::$dispatchedDateYear Dispatched_Date::$dispatchedDateMonth Dispatched_Date::$dispatchedDateDay Facility::$facilityID Starting_Serial_Number::$serialNumberStart Ending_Serial_Number::$serialNumberEnd");
	if($serialNumberEnd==1) $serialNumberEnd=0;
	//input data
	if(!$error) {
		//formate date
		$dispatchedDate=0;
		$dispatchedDate="$dispatchedDateYear-$dispatchedDateMonth-$dispatchedDateDay";
		
		$batchNumber=0;
		$batchNumber=getFormattedDateYearShort($datetime).getFormattedDateMonth($datetime).getFormattedDateDay($datetime).getFormattedHour($datetime).getFormattedMinutes($datetime);
		
		$serialNumberStart/=1;
		$serialNumberEnd/=1;
		
		//log the forms
		for($i=$serialNumberStart;$i<=($serialNumberStart+$serialNumberEnd);$i++) {
			generateFormNumber($i,$batchNumber);
		}
		//log dispatch
		if(!getDetailedTableInfo2("vl_forms_clinicalrequest_dispatch","refNumber='$batchNumber' limit 1","id")) {
			mysqlquery("insert into vl_forms_clinicalrequest_dispatch 
							(refNumber,dispatchDate,facilityID,created,createdby) 
							values 
							('$batchNumber','$dispatchedDate','$facilityID','$datetime','$trailSessionUser')");
		} else {
			//log changes
			logTableChange("vl_forms_clinicalrequest_dispatch","dispatchDate",getDetailedTableInfo2("vl_forms_clinicalrequest_dispatch","refNumber='$batchNumber'","id"),getDetailedTableInfo2("vl_forms_clinicalrequest_dispatch","refNumber='$batchNumber'","dispatchDate"),$dispatchedDate);
			logTableChange("vl_forms_clinicalrequest_dispatch","facilityID",getDetailedTableInfo2("vl_forms_clinicalrequest_dispatch","refNumber='$batchNumber'","id"),getDetailedTableInfo2("vl_forms_clinicalrequest_dispatch","refNumber='$batchNumber'","facilityID"),$facilityID);
			mysqlquery("update vl_forms_clinicalrequest_dispatch set 
										dispatchDate='$dispatchedDate',
										facilityID='$facilityID' 
										where 
										refNumber='$batchNumber'");
		}

		//redirect to home with updates on the tracking number
		go("/generateforms/dispatch/success/");
	}
}
?>
<script Language="JavaScript" Type="text/javascript">
<!--
function validate(envelopes) {
	//check for missing information
	if(!document.envelopes.serialNumberStart.value) {
		alert('Missing Mandatory Field: Starting Serial Number');
		document.envelopes.serialNumberStart.focus();
		return (false);
	}
	if(!document.envelopes.serialNumberEnd.value) {
		alert('Missing Mandatory Field: Number of Forms');
		document.envelopes.serialNumberEnd.focus();
		return (false);
	}
	if(!document.envelopes.dispatchedDateDay.value || !document.envelopes.dispatchedDateMonth.value || !document.envelopes.dispatchedDateYear.value) {
		alert('Missing Mandatory Field: Date Dispatched');
		document.envelopes.dispatchedDateDay.focus();
		return (false);
	}
	if(!document.envelopes.facilityID.value) {
		alert('Missing Mandatory Field: Destination Facility');
		document.envelopes.facilityID.focus();
		return (false);
	}
	return (true);
}
//-->
</script>
<!--<form name="envelopes" method="post" action="/generateforms/dispatch/new/" onsubmit="return validate(this)">-->
<form name="envelopes" method="post" action="/generateforms/dispatch/new/">
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
              <td class="toplinks" style="padding:0px 0px 10px 0px"><a class="toplinks" href="/dashboard/">HOME</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/generateforms/">GENERATE FORMS</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/generateforms/dispatch/new/">Add Form Batch/Dispatch Status</a></td>
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
                          <td width="20%">Serial&nbsp;Number&nbsp;(start)&nbsp;<font class="vl_red">*</font></td>
                          <td width="80%"><input type="text" name="serialNumberStart" id="serialNumberStart" value="<?=$serialNumberStart?>" class="search_pre" size="15" maxlength="50" onkeyup="return isNumber(this,'0')" /></td>
                        </tr>
                        <tr>
                          <td>Number&nbsp;of&nbsp;Forms&nbsp;<font class="vl_red">*</font></td>
                          <td>
							<select name="serialNumberEnd" id="serialNumberEnd" class="search">
                <option value="1">1</option>
                <option value="49">49</option>
                <option value="50">50</option>
							<?
							//echo "<option value=\"50\" selected=\"selected\">50</option>";
							//echo "<option value=\"".($serialNumberEnd?$serialNumberEnd:1)."\" selected=\"selected\">".($serialNumberEnd?$serialNumberEnd:1)."</option>";
							/*
							for($j=1;$j<=$default_formsPerBook;$j++) {
								echo "<option value=\"$j\">$j</option>";
							}
							*/
							?>
							</select>
                          </td>
                        </tr>
                        <tr>
                          <td>Date&nbsp;Dispatched&nbsp;<font class="vl_red">*</font></td>
                          <td>
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
											if($oldFacilityID) {
												echo "<option value=\"$oldFacilityID\" selected=\"selected\">".getDetailedTableInfo2("vl_facilities","id='$oldFacilityID' limit 1","facility")."</option>";
											} else {
												echo "<option value=\"\" selected=\"selected\">Select Facility</option>";
											}
                                            if(mysqlnumrows($query)) {
                                                while($q=mysqlfetcharray($query)) {
                                                    echo "<option value=\"$q[id]\">$q[facility]</option>";
                                                }
                                            }
                                            ?>
                                        </select></td>
                        </tr>
                      </table>
                    </fieldset>
                </td>
            </tr>
            <tr>
              <td style="padding:10px 0px 0px 0px"><input type="submit" name="saveDate" id="saveDate" class="button" value="  Save  " /></td>
            </tr>
            <tr>
	            <td style="padding:20px 0px 0px 0px"><a href="/generateforms/">Return to Generate Forms</a> :: <a href="/dashboard/">Return to Dashboard</a></td>
            </tr>
          </table>
</form>