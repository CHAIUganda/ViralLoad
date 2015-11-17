<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

//validation
$modify=validate($modify);
$artNumber=validate($artNumber);
$otherID=validate($otherID);
$gender=validate($gender);
$dateOfBirthDay=validate($dateOfBirthDay);
$dateOfBirthMonth=validate($dateOfBirthMonth);
$dateOfBirthYear=validate($dateOfBirthYear);
$patientPhone=validate($patientPhone);

if($savePatient) {
	//validate data
	$error=0;
	$error=checkFormFields("Gender::$gender");

	//is both ART and Other ID Number are missing
	if(!$artNumber && !$otherID) {
		$error.="<br /><strong>ART Number is Missing</strong><br />Kindly provide an ART Number<br />";
	}
	
	//date of birth missing?
	/* 7/Sept/15
	* Request from vbigira@clintonhealthaccess.org
	* Just got another urgent request from CPHL: They would like the VL database to have an additional entry for patients 
	* whose date of birth or age is not given by the facility. This was initially a 'must-have' option but with massive 
	* numbers of forms returning without this information, they would like to have the option of leaving it as a "Left blank". 
	* This can be put after the age variable.
	if(!$dateOfBirthDay || !$dateOfBirthMonth || !$dateOfBirthYear) {
		$error.="<br /><strong>Date of Birth Missing</strong><br />Kindly provide the Date of Birth<br />";
	}
	*/
	
	//is both date of birth and age in years/months missing?
	$dateOfBirth=0;
	if($dateOfBirthYear && $dateOfBirthMonth && $dateOfBirthDay) {
		$dateOfBirth="$dateOfBirthYear-$dateOfBirthMonth-$dateOfBirthDay";
	}

	//input data
	if(!$error) {
		//log changes
		logTableChange("vl_patients","artNumber",$modify,getDetailedTableInfo2("vl_patients","id='$modify'","artNumber"),$artNumber);
		logTableChange("vl_patients","otherID",$modify,getDetailedTableInfo2("vl_patients","id='$modify'","otherID"),$otherID);
		logTableChange("vl_patients","gender",$modify,getDetailedTableInfo2("vl_patients","id='$modify'","gender"),$gender);
		logTableChange("vl_patients","dateOfBirth",$modify,getDetailedTableInfo2("vl_patients","id='$modify'","dateOfBirth"),$dateOfBirth);

		mysqlquery("update vl_patients set 
						artNumber='$artNumber', 
						otherID='$otherID', 
						gender='$gender', 
						dateOfBirth='$dateOfBirth' 
						where 
						id='$modify'");

		//log patient phone number, if unique
		if($patientPhone) {
			$phoneID=0;
			$phoneID=getDetailedTableInfo2("vl_patients_phone","patientID='$modify' and phone='$patientPhone' limit 1","id");
			if(!$phoneID) {
				mysqlquery("insert into vl_patients_phone 
								(patientID,phone,created,createdby) 
								values 
								('$modify','$patientPhone','$datetime','$trailSessionUser')");
			} else {
				//log this change
				logTableChange("vl_patients_phone","created",$phoneID,getDetailedTableInfo2("vl_patients_phone","id='$phoneID'","created"),$datetime);
				mysqlquery("update vl_patients_phone set created='$datetime' where id='$phoneID'");
			}
		}

		//redirect to home with updates on the tracking number
		go("/samples/modified/");
	}
} else {
	//prepopulate
	$artNumber=0;
	$artNumber=getDetailedTableInfo2("vl_patients","id='$modify' limit 1","artNumber");
	
	$otherID=0;
	$otherID=getDetailedTableInfo2("vl_patients","id='$modify' limit 1","otherID");
	
	$gender=0;
	$gender=getDetailedTableInfo2("vl_patients","id='$modify' limit 1","gender");
	
	$dateOfBirth=0;
	$dateOfBirth=getDetailedTableInfo2("vl_patients","id='$modify' limit 1","dateOfBirth");

	$patientPhone=0;
	$patientPhone=getDetailedTableInfo2("vl_patients_phone","patientID='$modify' order by created desc limit 1","phone");
}
?>
<script Language="JavaScript" Type="text/javascript">
<!--
function validate(envelopes) {
	//check for missing information
	if(!document.samples.artNumber.value && !document.samples.otherID.value) {
		alert('Missing Mandatory Field: ART Number');
		document.samples.artNumber.focus();
		return (false);
	}
	if(!document.samples.gender.value) {
		alert('Missing Mandatory Field: Gender');
		document.samples.gender.focus();
		return (false);
	}
	/*
	if((!document.samples.dateOfBirthDay.value || !document.samples.dateOfBirthMonth.value || !document.samples.dateOfBirthYear.value) && (!document.samples.dateOfBirthAge.value || !document.samples.dateOfBirthIn.value)) {
		alert('Missing Mandatory Field: Date of Birth or Patient Age');
		document.samples.dateOfBirthDay.focus();
		return (false);
	}
	*/
	return (true);
}
//-->
</script>
<!--<form name="samples" method="post" action="/samples/manage.patients/modify/<?=$modify?>/" onsubmit="return validate(this)">-->
<form name="samples" method="post" action="/samples/manage.patients/modify/<?=$modify?>/">
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
              <td class="toplinks" style="padding:0px 0px 10px 0px"><a class="toplinks" href="/dashboard/">HOME</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/samples/">SAMPLES</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/samples/manage.patients/">Patients</a></td>
            </tr>
            <tr>
                <td><strong>Modify Patient's Information</strong></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                  <fieldset style="width: 100%">
            <legend><strong>PATIENT INFORMATION</strong></legend>
                        <div style="padding:5px 0px 0px 0px">
                          <table width="100%" border="0" class="vl">
                            <tr>
                              <td width="20%">ART&nbsp;Number&nbsp;<font class="vl_red">*</font></td>
                              <td width="80%"><input type="text" name="artNumber" id="artNumber" value="<?=$artNumber?>" class="search_pre" size="25" maxlength="20" /></td>
                            </tr>
                            <tr>
                              <td>Other&nbsp;ID</td>
                              <td><input type="text" name="otherID" id="otherID" value="<?=$otherID?>" class="search_pre" size="25" maxlength="50" /></td>
                            </tr>
                            <tr>
                              <td>Gender&nbsp;<font class="vl_red">*</font></td>
                              <td>
								<select name="gender" id="gender" class="search" onchange="checkGender(this)">
                                	<?
									if($gender) {
										echo "<option value=\"$gender\" selected=\"selected\">$gender</option>";
									} else {
										echo "<option value=\"\" selected=\"selected\">Select Gender</option>";
									}
									?>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Missing Gender">Missing Gender</option>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>Date&nbsp;of&nbsp;Birth</td>
                              <td>
                                  <table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td><select name="dateOfBirthDay" id="dateOfBirthDay" class="search">
                                          <?
										  	if($dateOfBirth) {
												echo "<option value=\"".getFormattedDateDay($dateOfBirth)."\" selected=\"selected\">".getFormattedDateDay($dateOfBirth)."</option>";
											} else {
	                                            echo "<option value=\"\" selected=\"selected\">Select Date</option>";
											}
											for($j=1;$j<=31;$j++) {
                                                echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
                                            }
                                            ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="dateOfBirthMonth" id="dateOfBirthMonth" class="search">
                                          <? 
										  	if($dateOfBirth) {
												echo "<option value=\"".getFormattedDateMonth($dateOfBirth)."\" selected=\"selected\">".getFormattedDateMonthname($dateOfBirth)."</option>";
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
                                        <td style="padding:0px 0px 0px 5px"><select name="dateOfBirthYear" id="dateOfBirthYear" class="search">
                                          		<?
												if($dateOfBirth) {
													echo "<option value=\"".getFormattedDateYear($dateOfBirth)."\" selected=\"selected\">".getFormattedDateYear($dateOfBirth)."</option>";
												} else {
													echo "<option value=\"\" selected=\"selected\">Select Year</option>";
												}
                                                for($j=getFormattedDateYear(getDualInfoWithAlias("last_day(now())","lastmonth"));$j>=(getCurrentYear()-100);$j--) {
                                                    echo "<option value=\"$j\">$j</option>";
                                                }
                                                ?>
                                          </select></td>
                                        </tr>
                                    </table>
                              </td>
                            </tr>
                            <tr>
                              <td>Patient&nbsp;Phone</td>
                              <td><table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td><input type="text" name="patientPhone" id="patientPhone" value="<?=$patientPhone?>" class="search_pre" size="15" maxlength="20" /></td>
                                        </tr>
                                    </table></td>
                            </tr>
                          </table>
                        </div>
                  </fieldset>
                </td>
            </tr>
            <tr>
              <td style="padding:10px 0px 0px 0px"><input type="submit" name="savePatient" id="savePatient" class="button" value="     Save Changes to Patient     " /></td>
            </tr>
            <tr>
	            <td style="padding:20px 0px 0px 0px"><a href="/samples/">Return to Samples</a> :: <a href="/dashboard/">Return to Dashboard</a></td>
            </tr>
          </table>
</form>