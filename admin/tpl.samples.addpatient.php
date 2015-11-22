<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

if($searchQuery) {
	$searchQuery=validate($searchQuery);
}
?>
  <table width="100%" border="0">
    <tr>
      <td width="65%" valign="top">
		<? if($saved) { ?>
            <table width="100%" border="0" class="vl">
                <tr>
                    <td class="vl_success">User Added!</td>
                </tr>
                <tr>
                    <td><img src="/admin/images/spacer.gif" width="3" height="3" /></td>
                </tr>
            </table>
        <? } ?>
		<?
        switch($option) {
            case add:
				//validate
				$artNumber=validate($artNumber);
				$otherID=validate($otherID);
				$gender=validate($gender);
				$dateOfBirthDay=validate($dateOfBirthDay);
				$dateOfBirthMonth=validate($dateOfBirthMonth);
				$dateOfBirthYear=validate($dateOfBirthYear);
				$dateOfBirthAge=validate($dateOfBirthAge);
				$dateOfBirthIn=validate($dateOfBirthIn);
				$patientPhone=validate($patientPhone);

				//check for missing variables
				$error=0;
				$error="";

				//check for facilityID
				if(!$facilityID) {
					$error.="<br /><strong>Facility Missing</strong><br />Kindly select the Facility<br />";
				} else {
					//ensure facility is valid
					if(!getDetailedTableInfo2("vl_facilities","id='$facilityID'","id")) {
						$error.="<br /><strong>Facility Incorrect.</strong><br />Kindly select an existing Facility from the list or first add the New Facility <a href=\"?act=regions&nav=configuration\" target=\"_blank\">here</a><br />";
					}
				}

				//check for gender
				if(!$gender) {
					$error.="<br /><strong>Gender Field Missing</strong><br />Kindly select the Gender<br />";
				}

				//are both ART and Other ID Number are missing
				if(!$artNumber && !$otherID) {
					$error.="<br /><strong>ART Number is Missing</strong><br />Kindly provide an ART Number<br />";
				}
				
				/* 7/Sept/15
				* Request from vbigira@clintonhealthaccess.org
				* Just got another urgent request from CPHL: They would like the VL database to have an additional entry for patients 
				* whose date of birth or age is not given by the facility. This was initially a 'must-have' option but with massive 
				* numbers of forms returning without this information, they would like to have the option of leaving it as a "Left blank". 
				* This can be put after the age variable.
				//date of birth missing?
				if((!$dateOfBirthDay || !$dateOfBirthMonth || !$dateOfBirthYear) && (!$dateOfBirthAge || !$dateOfBirthIn)) {
					$error.="<br /><strong>Date of Birth Missing</strong><br />Kindly provide the Date of Birth<br />";
				}
				*/
				
				//is both date of birth and age in years/months missing?
				$dateOfBirth=0;
				if($dateOfBirthYear && $dateOfBirthMonth && $dateOfBirthDay) {
					$dateOfBirth="$dateOfBirthYear-$dateOfBirthMonth-$dateOfBirthDay";
				} else {
					if($dateOfBirthIn=="Months") {
						$dateOfBirth=subtractFromDate($datetime,($dateOfBirthAge*30.5));
						/*
						* 20/Jun/14: suggestion by stakeholders;
						* If Patient Provides "Age in Years", then date of birth should be == 1/Jan (year when the date was computed)
						*/
						$dateOfBirth=getFormattedDateYear($dateOfBirth)."-01-01";
					} elseif($dateOfBirthIn=="Years") {
						$dateOfBirth=subtractFromDate($datetime,($dateOfBirthAge*12*30.5));
						/*
						* 20/Jun/14: suggestion by stakeholders;
						* If Patient Provides "Age in Years", then date of birth should be == 1/Jan (year when the date was computed)
						*/
						$dateOfBirth=getFormattedDateYear($dateOfBirth)."-01-01";
					} else {
						/* 7/Sept/15
						* Request from vbigira@clintonhealthaccess.org
						* Just got another urgent request from CPHL: They would like the VL database to have an additional entry for patients 
						* whose date of birth or age is not given by the facility. This was initially a 'must-have' option but with massive 
						* numbers of forms returning without this information, they would like to have the option of leaving it as a "Left blank". 
						* This can be put after the age variable.
						$error.="<br /><strong>Date of Birth is Missing</strong><br />Kindly provide the Date of Birth or Age in Months/Years<br />";
						*/
					}
				}

				//process
				if(!$error) {
					//concatenations
					$uniqueID=0;
					if($artNumber || $otherID) {
						$uniqueID=$facilityID."-".($artNumber?"A-$artNumber":"O-$otherID");
					}
			
					//log patient, if unique
					$patientID=0;
					if(!getDetailedTableInfo2("vl_patients","uniqueID='$uniqueID' and artNumber='$artNumber' and otherID='$otherID' limit 1","id")) {
						mysqlquery("insert into vl_patients 
										(uniqueID,artNumber,otherID,gender,".(!$noDateOfBirthSupplied?"dateOfBirth,":"")."created,createdby) 
										values 
										('$uniqueID','$artNumber','$otherID','$gender',".(!$noDateOfBirthSupplied?"'$dateOfBirth',":"")."'$datetime','$_SESSION[VLADMIN]')");

						//patient ID
						$patientID=getDetailedTableInfo2("vl_patients","uniqueID='$uniqueID' and (artNumber='$artNumber' or otherID='$otherID') limit 1","id");

						//added flag
						$added=1;
					} else {
						$error.="<br /><strong>Duplicate Patient Entry.</strong><br />This patient was provided on <strong>".getFormattedDate(getDetailedTableInfo2("vl_patients","uniqueID='$uniqueID' and artNumber='$artNumber' and otherID='$otherID' limit 1","created"))."</strong> by <strong>".getDetailedTableInfo2("vl_patients","uniqueID='$uniqueID' and artNumber='$artNumber' and otherID='$otherID' limit 1","createdby")."</strong><br />";
					}
			
					//log patient phone number, if unique
					if($patientPhone && !getDetailedTableInfo2("vl_patients_phone","patientID='$patientID' and phone='$patientPhone' limit 1","id")) {
						mysqlquery("insert into vl_patients_phone 
										(patientID,phone,created,createdby) 
										values 
										('$patientID','$patientPhone','$datetime','$_SESSION[VLADMIN]')");
					}
				}

				//redirect
				if($added) {
					go("?act=addPatient&nav=datamanagement&added=1");
				}
            break;
            case modify:
				//prepare date of birth
				$dateOfBirth=0;
				if($dateOfBirthYear && $dateOfBirthMonth && $dateOfBirthDay) {
					$dateOfBirth="$dateOfBirthYear-$dateOfBirthMonth-$dateOfBirthDay";
				}

				//log table change
				logTableChange("vl_patients","artNumber",$id,getDetailedTableInfo2("vl_patients","id='$id'","artNumber"),$artNumber);
				logTableChange("vl_patients","otherID",$id,getDetailedTableInfo2("vl_patients","id='$id'","otherID"),$otherID);
				logTableChange("vl_patients","gender",$id,getDetailedTableInfo2("vl_patients","id='$id'","gender"),$gender);
				if(!$noDateOfBirthSupplied) { logTableChange("vl_patients","dateOfBirth",$id,getDetailedTableInfo2("vl_patients","id='$id'","dateOfBirth"),$dateOfBirth); }
				//update vl_patients
				mysqlquery("update vl_patients set 
										artNumber='$artNumber',
										otherID='$otherID',
										".(!$noDateOfBirthSupplied?"dateOfBirth='$dateOfBirth', ":"dateOfBirth='', ")."
										gender='$gender' 
										where id='$id'");
				//log patient phone number, if unique
				if($patientPhone && !getDetailedTableInfo2("vl_patients_phone","patientID='$id' and phone='$patientPhone' limit 1","id")) {
					mysqlquery("insert into vl_patients_phone 
									(patientID,phone,created,createdby) 
									values 
									('$id','$patientPhone','$datetime','$_SESSION[VLADMIN]')");
				}
				//flag
				$modified=1;

				//redirect
				go("?act=addPatient&nav=datamanagement&modified=1");
            break;
            case remove:
				if(isQuery("select * from vl_patients where id='$id'")) {
					logDataRemoval("delete from vl_patients where id='$id'");
					mysqlquery("delete from vl_patients where id='$id'");
					//flag
					$removed=1;
				}
				
				//redirect
				if($removed) {
					go("?act=addPatient&nav=datamanagement&removed=1");
				}
            break;
            default:
                if($modify) {
                    $task="modify";
                }
            break;
		}
		
		//set task
		if(!$task) {
			$task="add";
		}
		
		//if $id and date of birth
		if($id) {
			if(!isDateAuthentic(getDetailedTableInfo2("vl_patients","id='$id' limit 1","dateOfBirth"))) {
				$noDateOfBirthSupplied=1;
			} else {
				$noDateOfBirthSupplied=0;
			}
		}
?>
        <script Language="JavaScript" Type="text/javascript">
		<!--
        function checkForm(adminsForm) {
			<? if(!$id) { ?>
			if(!document.adminsForm.facilityID.value) {
				alert('Missing Mandatory Field: Facility Name');
				document.adminsForm.facilityID.focus();
				return (false);
			}
			<? } ?>
			if(!document.adminsForm.artNumber.value && !document.adminsForm.otherID.value) {
				alert('Missing Mandatory Field: ART Number');
				document.adminsForm.artNumber.focus();
				return (false);
			}
			if(!document.adminsForm.gender.value) {
				alert('Missing Mandatory Field: Gender');
				document.adminsForm.gender.focus();
				return (false);
			}
			/*
			if((!document.adminsForm.dateOfBirthDay.value || !document.adminsForm.dateOfBirthMonth.value || !document.adminsForm.dateOfBirthYear.value) && (!document.adminsForm.dateOfBirthAge.value || !document.adminsForm.dateOfBirthIn.value)) {
				alert('Missing Mandatory Field: Date of Birth or Patient Age');
				document.adminsForm.dateOfBirthDay.focus();
				return (false);
			}
			*/
            return (true);
        }

		function checkMonthDay(theField) {
			if(theField.value && !document.adminsForm.dateOfBirthMonth.value && !document.adminsForm.dateOfBirthDay.value) {
				//default to first day/month
				document.adminsForm.dateOfBirthDay.value="01";
				document.adminsForm.dateOfBirthMonth.value="01"
			}
		}
		
		function disableEnableDateOfBirth(checkedObject) {
			if(checkedObject.checked==true) {
				//has been checked
				<? if(!$id) { ?>
				document.adminsForm.dateOfBirthIn.disabled=true;
				document.adminsForm.dateOfBirthAge.disabled=true;
				<? } ?>
				document.adminsForm.dateOfBirthYear.disabled=true;
				document.adminsForm.dateOfBirthMonth.disabled=true;
				document.adminsForm.dateOfBirthDay.disabled=true;
			} else if(checkedObject.checked==false) {
				//has been unchecked
				<? if(!$id) { ?>
				document.adminsForm.dateOfBirthIn.disabled=false;
				document.adminsForm.dateOfBirthAge.disabled=false;
				<? } ?>
				document.adminsForm.dateOfBirthYear.disabled=false;
				document.adminsForm.dateOfBirthMonth.disabled=false;
				document.adminsForm.dateOfBirthDay.disabled=false;
			}
		}
        //-->
        </script>
        
        <form name="adminsForm" method="post" action="?act=addPatient&nav=datamanagement" enctype="multipart/form-data" onsubmit="return checkForm(this)">
          <table width="90%" border="0" class="vl">
		<? if($added) { ?>
            <tr>
              <td class="vl_success">Added!</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
		<? } else if($modified) { ?>
            <tr>
              <td class="vl_success"><?=number_format((float)$modified)?> user<?=($modified!=1?"s":"")?> modified!</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
		<? } else if($removed) { ?>
            <tr>
              <td class="vl_success">Removed!</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
		<? } else if($error) { ?>
            <tr>
              <td class="vl_error">Unable to process your submission due to the following error(s): <?=$error?></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
		<? 
		} else if($searchQuery) { 
			$queryResults=0;
			$queryResults=getDetailedTableInfo3("vl_patients","artNumber like '%$searchQuery%' or otherID like '%$searchQuery%'","count(id)","num");
			if($queryResults) {
		?>
            <tr>
              <td class="vl_success"><?=$queryResults?> result<?=($queryResults==1?"":"s")?> found while searching for <strong><?=$searchQuery?></strong></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
		<? } else { ?>
            <tr>
              <td class="vl_error">No results found while searching for <strong><?=$searchQuery?></strong></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
		<? } ?>
        <? 
		}
		if($task=="add") { ?>
            <tr>
              <td style="border-bottom:1px solid #cccccc; padding-bottom:10px">Add User</td>
            </tr>
        <? } else { ?>
            <tr>
              <td style="border-bottom:1px solid #cccccc; padding-bottom:10px">Manage Users</td>
            </tr>
        <? } ?>
            <tr> 
              <td style="padding:10px 0px 10px 0px">
						<table width="100%" border="0" class="vl">
							<? if(!$id) { ?>
                            <tr>
                            <td>Facility&nbsp;Name&nbsp;<font class="vl_red">*</font></td>
                              <td>
                                <select name="facilityID" id="facilityID" class="search" onchange="checkForHubDistrict()">
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
							<? } ?>
                            <tr>
                              <td width="20%">ART&nbsp;Number&nbsp;<font class="vl_red">*</font></td>
                              <td width="80%"><input type="text" name="artNumber" id="artNumber" value="<?=($id?getDetailedTableInfo2("vl_patients","id='$id' limit 1","artNumber"):$artNumber)?>" class="search" size="25" maxlength="20" /></td>
                            </tr>
                            <tr>
                              <td>Other&nbsp;ID</td>
                              <td><input type="text" name="otherID" id="otherID" value="<?=($id?getDetailedTableInfo2("vl_patients","id='$id' limit 1","otherID"):$otherID)?>" class="search" size="25" maxlength="50" /></td>
                            </tr>
                            <tr>
                              <td>Gender&nbsp;<font class="vl_red">*</font></td>
                              <td>
								<select name="gender" id="gender" class="search" onchange="checkGender(this)">
                                	<?
									if($id) {
										echo "<option value=\"".getDetailedTableInfo2("vl_patients","id='$id' limit 1","gender")."\" selected=\"selected\">".getDetailedTableInfo2("vl_patients","id='$id' limit 1","gender")."</option>";
									} elseif($gender) {
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
                                        <td><select name="dateOfBirthDay" id="dateOfBirthDay" class="search" <?=($noDateOfBirthSupplied?"disabled=\"disabled\"":"")?>>
                                          <?
										  	if($id) {
												echo "<option value=\"".getFormattedDateDay(getDetailedTableInfo2("vl_patients","id='$id' limit 1","dateOfBirth"))."\" selected=\"selected\">".getFormattedDateDay(getDetailedTableInfo2("vl_patients","id='$id' limit 1","dateOfBirth"))."</option>";
											} elseif($dateOfBirth) {
												echo "<option value=\"".getFormattedDateDay($dateOfBirth)."\" selected=\"selected\">".getFormattedDateDay($dateOfBirth)."</option>";
											} else {
	                                            echo "<option value=\"\" selected=\"selected\">Select Date</option>";
											}
											for($j=1;$j<=31;$j++) {
                                                echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
                                            }
                                            ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="dateOfBirthMonth" id="dateOfBirthMonth" class="search" <?=($noDateOfBirthSupplied?"disabled=\"disabled\"":"")?>>
                                          <? 
										  	if($id) {
												echo "<option value=\"".getFormattedDateMonth(getDetailedTableInfo2("vl_patients","id='$id' limit 1","dateOfBirth"))."\" selected=\"selected\">".getFormattedDateMonthname(getDetailedTableInfo2("vl_patients","id='$id' limit 1","dateOfBirth"))."</option>";
											} elseif($dateOfBirth) {
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
                                        <td style="padding:0px 0px 0px 5px"><select name="dateOfBirthYear" id="dateOfBirthYear" class="search" onchange="checkMonthDay(this)" <?=($noDateOfBirthSupplied?"disabled=\"disabled\"":"")?>>
                                          		<?
												if($id) {
													echo "<option value=\"".getFormattedDateYear(getDetailedTableInfo2("vl_patients","id='$id' limit 1","dateOfBirth"))."\" selected=\"selected\">".getFormattedDateYear(getDetailedTableInfo2("vl_patients","id='$id' limit 1","dateOfBirth"))."</option>";
												} elseif($dateOfBirth) {
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
                            <? if(!$id) { ?>
                            <tr>
							  <td>or</td>
                              <td>
                                  <table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td style="padding:0px 0px 0px 0px"><select name="dateOfBirthAge" id="dateOfBirthAge" class="search" <?=($noDateOfBirthSupplied?"disabled=\"disabled\"":"")?>>
                                          		<?
												if($dateOfBirthAge) {
													echo "<option value=\"$dateOfBirthAge\" selected=\"selected\">$dateOfBirthAge</option>";
												} else {
													echo "<option value=\"\" selected=\"selected\">Select Age</option>";
												}
                                                for($j=1;$j<=120;$j++) {
                                                    echo "<option value=\"$j\">$j</option>";
                                                }
                                                ?>
                                          </select></td>
                                        <td style="padding:0px 0px 0px 5px"><select name="dateOfBirthIn" id="dateOfBirthIn" class="search" <?=($noDateOfBirthSupplied?"disabled=\"disabled\"":"")?>>
                                          		<?
													if($dateOfBirthIn) {
														echo "<option value=\"$dateOfBirthIn\" selected=\"selected\">$dateOfBirthIn</option>";
													} else {
														echo "<option value=\"\" selected=\"selected\">in Years or Months</option>";
													}
												?>
                                                    <option value="Years">Years</option>
                                                    <option value="Months">Months</option>
                                          </select></td>
                                        </tr>
                                    </table>
                              </td>
                            </tr>
                            <? } ?>
                            <tr>
							  <td>or</td>
                              <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                  <tr>
                                    <td width="1%"><input name="noDateOfBirthSupplied" type="checkbox" id="noDateOfBirthSupplied" value="1" onclick="disableEnableDateOfBirth(this);" <?=($noDateOfBirthSupplied?"checked=\"checked\"":"")?> /></td>
                                    <td width="99%" style="padding:0px 0px 0px 5px">No&nbsp;date&nbsp;of&nbsp;birth&nbsp;supplied</td>
                                  </tr>
                                </table></td>
                            </tr>
                            <tr>
                              <td>Patient&nbsp;Phone</td>
                              <td><table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                      <tr>
                                        <td><input type="text" name="patientPhone" id="patientPhone" value="<?=($id?getDetailedTableInfo2("vl_patients_phone","patientID='$id' order by created desc limit 1","phone"):$patientPhone)?>" class="search" size="15" maxlength="20" /></td>
                                        </tr>
                                    </table></td>
                            </tr>
                          </table>
              </td>
            </tr>
            <tr> 
              <td style="border-top:1px solid #cccccc; padding-top:10px"> 
              <input type="submit" name="button" id="button" value="   Save   " /> 
              <button type="button" id="button" name="button" value="button" onclick="document.location.href='?act=addPatient&nav=datamanagement'">&nbsp;&nbsp;Cancel&nbsp;&nbsp;</button>
              <? if($task=="modify") { ?>
              <input name="id" type="hidden" id="id" value="<?=$id?>"> 
              <? } ?>
              <input name="act" type="hidden" id="act" value="addPatient">
              <input name="option" type="hidden" id="option" value="<?=$task?>">
              </td>
            </tr>
          </table>
        </form>

		<?
        $query=0;
		if($searchQuery) {
	        $query=mysqlquery("select * from vl_patients where artNumber like '%$searchQuery%' or otherID like '%$searchQuery%' order by created desc");
		} else {
	        $query=mysqlquery("select * from vl_patients order by created desc limit 100");
		}
		$num=0;
		$num=mysqlnumrows($query);
        if($num) {
        ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
            <tr>
                <td style="padding:5px 0px 5px 0px" align="center">
                	<div style="height: 200px; border: 1px solid #ccccff; overflow: auto">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
                        <tr>
                          <td class="vl_tdsub" width="30%" style="padding-left:16px"><strong>ART&nbsp;Number</strong></td>
                          <td class="vl_tdsub" width="30%"><strong>Other&nbsp;ID</strong></td>
                          <td class="vl_tdsub" width="5%"><strong>Samples</strong></td>
                          <td class="vl_tdsub" width="15%"><strong>Created</strong></td>
                          <td class="vl_tdsub" width="20%"><strong>Options</strong></td>
                        </tr>
                    	<?
                        $count=0;
                        $q=array();
                        while($q=mysqlfetcharray($query)) {
                            $count+=1;
							$numberSamples=0;
							$numberSamples=getDetailedTableInfo3("vl_samples","patientID='$q[id]'","count(id)","num");
                        ?>
                            <tr>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="30%"><?=($q["artNumber"]?$q["artNumber"]:"&nbsp;")?></td>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="30%"><?=($q["otherID"]?$q["otherID"]:"&nbsp;")?></td>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="5%" align="center"><?=number_format((float)$numberSamples)?></td>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="15%"><?=getFormattedDateLessDay($q["created"])?></td>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="20%"><a href="?act=addPatient&nav=datamanagement&modify=modify&id=<?=$q["id"]?>">edit</a>&nbsp;::&nbsp;<a href="javascript:if(confirm('Are you sure?')) { document.location.href='?act=addPatient&nav=datamanagement&option=remove&id=<?=$q["id"]?>'; }">delete</a></td>
                            </tr>
                        <? } ?>
                    </table>
                    </div>
                </td>
            </tr>
        </table>
		<? } ?>
      </td>
      <td width="35%" valign="top" style="padding:3px 0px 0px 12px">
        <table border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #d5d5d5" width="100%">
          <tr>
            <td style="padding:10px">
        <script Language="JavaScript" Type="text/javascript">
		<!--
        function checkFilterForm(filterPatients) {
			if(!document.filterPatients.searchQuery.value) {
				alert('Missing Mandatory Field: Search Query');
				document.filterPatients.searchQuery.focus();
				return (false);
			}
            return (true);
        }
        //-->
        </script>
        
        <form name="filterPatients" method="post" action="?act=addPatient&nav=datamanagement" onsubmit="return checkFilterForm(this)">
            <table width="100%" border="0" class="vl">
              <tr>
                <td><strong>MANAGE PATIENTS</strong></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td bgcolor="#d5d5d5" style="padding:10px">Create, Delete or Manage Users</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>
                <div>Find Patient</div>
                <div style="padding:3px 0px" class="vls_grey">Search by ART Number or Other ID</div>
                </td>
              </tr>
              <tr>
                <td><input type="text" name="searchQuery" id="searchQuery" value="" class="search" size="7" maxlength="20" /></td>
              </tr>
              <tr>
                <td><input type="submit" name="button" id="button" value="   Search   " /></td>
              </tr>
            </table>
            </form>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>