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
            case modify:
				//validate
				$artNumber=validate($artNumber);
				$otherID=validate($otherID);
				$facilityID=validate($facilityID);
				$formNumber=validate($formNumber);
				$sampleID=validate($sampleID);

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

				//concatenations
				$uniqueID=0;
				$uniqueIDlength=0;
				if($artNumber || $otherID) {
					$uniqueID=$facilityID."-".($artNumber?"A-$artNumber":"O-$otherID");
					$uniqueIDlength=strlen($facilityID."-".($artNumber?"A-":"O-"));
				}
		
				//log patient, if unique
				$patientID=0;
				$patientID=getDetailedTableInfo2("vl_patients","substr(uniqueID,1,$uniqueIDlength)='".substr($uniqueID,0,$uniqueIDlength)."' and (".($artNumber?"artNumber='$artNumber'":"").($artNumber && $otherID?" or otherID='$otherID'":(!$artNumber && $otherID?"otherID='$otherID'":"")).") limit 1","id");
				if(!$patientID) {
					$error.="<br /><strong>Incorrect ART Number <strong>$artNumber</strong>, Other ID <strong>$otherID</strong> or Facility <strong>".getDetailedTableInfo2("vl_facilities","id='$facilityID'","facility")."</strong>.</strong><br />The supplied Patient Credentials could not be found within the system<br />";
				}

				//ensure form number or sampleID is valid
				$sampleUniqueID=0;
				$sampleUniqueID=getDetailedTableInfo2("vl_samples","formNumber='$formNumber' or vlSampleID='$sampleID' order by created desc limit 1","id");
				if(!$sampleUniqueID) {
					$error.="<br /><strong>Unknown Form Number <strong>$formNumber</strong> or Sample Reference Number <strong>$sampleID</strong>.</strong><br />The supplied Sample Credentials could not be found within the system<br />";
				}
				
				if(!$error) {
					//remove all historical links to this sample
					if($unlinkPatient) {
						//log table change
						$query=0;
						$query=mysqlquery("select * from vl_samples where patientID='$patientID'");
						if(mysqlnumrows($query)) {
							while($q=mysqlfetcharray($query)) {
								logTableChange("vl_samples","patientID",$q["id"],$q["patientID"],"");
								logTableChange("vl_samples","patientUniqueID",$q["id"],$q["patientID"],"");
							}
						}
						//update vl_samples
						mysqlquery("update vl_samples set patientID='',patientUniqueID='' where id='$sampleUniqueID'");
					}

					//remove all historical links to this sample
					if($unlinkForm) {
						//log table change
						$query=0;
						$query=mysqlquery("select * from vl_samples where formNumber='$formNumber'");
						if(mysqlnumrows($query)) {
							while($q=mysqlfetcharray($query)) {
								logTableChange("vl_samples","patientID",$q["id"],$q["patientID"],"");
								logTableChange("vl_samples","patientUniqueID",$q["id"],$q["patientID"],"");
							}
						}
						//update vl_samples
						mysqlquery("update vl_samples set patientID='',patientUniqueID='' where formNumber='$formNumber'");
					}

					//log table change
					logTableChange("vl_samples","patientID",$sampleUniqueID,getDetailedTableInfo2("vl_samples","id='$sampleUniqueID'","patientID"),$q["patientID"]);
					logTableChange("vl_samples","patientUniqueID",$sampleUniqueID,getDetailedTableInfo2("vl_samples","id='$sampleUniqueID'","patientUniqueID"),$q["patientID"]);
					//update vl_samples
					mysqlquery("update vl_samples set patientID='$patientID',patientUniqueID='$uniqueID' where id='$sampleUniqueID'");
	
					//flag
					$linked=1;
	
					//redirect
					go("?act=linkPatient&nav=datamanagement&linked=1");
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
			$task="modify";
		}
?>
        <script Language="JavaScript" Type="text/javascript">
		<!--
        function checkForm(adminsForm) {
			if(!document.adminsForm.facilityID.value) {
				alert('Missing Mandatory Field: Facility ID');
				document.adminsForm.facilityID.focus();
				return (false);
			}
			if(!document.adminsForm.artNumber.value && !document.adminsForm.otherID.value) {
				alert('Missing Mandatory Field: ART Number or Other ID');
				document.adminsForm.artNumber.focus();
				return (false);
			}
			if(!document.adminsForm.formNumber.value && !document.adminsForm.sampleID.value) {
				alert('Missing Mandatory Field: Form Number or Sample Reference Number');
				document.adminsForm.formNumber.focus();
				return (false);
			}
            return (true);
        }
        //-->
        </script>
        
        <form name="adminsForm" method="post" action="?act=linkPatient&nav=datamanagement" onsubmit="return checkForm(this)">
          <table width="90%" border="0" class="vl">
		<? if($linked) { ?>
            <tr>
              <td class="vl_success">Patient Successfully linked to Sample!</td>
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
        <? 
			}
		}
		?>
            <tr>
              <td style="border-bottom:1px solid #cccccc; padding-bottom:10px">Link Patient to Sample</td>
            </tr>
            <tr> 
              <td style="padding:10px 0px 10px 0px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="50%" valign="top"><table width="100%" border="0" class="vl">
                    <tr>
                      <td colspan="2"><strong>Link this Patient</strong></td>
                    </tr>
                    <tr>
                        <td width="20%">Facility&nbsp;Name&nbsp;<font class="vl_red">*</font></td>
                          <td width="80%">
                            <select name="facilityID" id="facilityID" class="search" onchange="checkForHubDistrict()">
                                <?
                                $query=0;
                                $query=mysqlquery("select * from vl_facilities where facility!='' order by facility");
								if($facilityID) {
									echo "<option value=\"$facilityID\" selected=\"selected\">".getDetailedTableInfo2("vl_facilities","id='$facilityID'","facility")."</option>";
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
                    <tr>
                      <td>ART&nbsp;Number</td>
                      <td><input type="text" name="artNumber" id="artNumber" value="<?=$artNumber?>" class="search" size="25" maxlength="20" /></td>
                    </tr>
                    <tr>
                      <td style="padding:0px 0px 10px 0px">Other&nbsp;ID</td>
                      <td style="padding:0px 0px 10px 0px"><input type="text" name="otherID" id="otherID" value="<?=$otherID?>" class="search" size="25" maxlength="50" /></td>
                    </tr>
                    <tr>
                      <td colspan="2" style="border-top:1px dashed #cccccc; padding:10px 0px"><strong>With this Sample</strong></td>
                    </tr>
                    <tr>
                      <td width="20%">Form&nbsp;Number</td>
                      <td width="80%"><input type="text" name="formNumber" id="formNumber" value="<?=$formNumber?>" class="search" size="10" maxlength="20" /></td>
                    </tr>
                    <tr>
                      <td>Sample&nbsp;Ref&nbsp;Number</td>
                      <td><input type="text" name="sampleID" id="sampleID" value="<?=$sampleID?>" class="search" size="10" maxlength="50" /></td>
                    </tr>
                  </table></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td style="border-top:1px solid #cccccc; padding:5px 0px"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
                <tr>
                  <td width="1%"><input name="unlinkPatient" type="checkbox" id="unlinkPatient" checked="checked" value="1" /></td>
                  <td width="99%" style="padding:0px 0px 0px 5px">Unlink this Patient from any previous Samples</td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td style="border-top:1px solid #cccccc; padding:5px 0px"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
                <tr>
                  <td width="1%"><input name="unlinkForm" type="checkbox" id="unlinkForm" checked="checked" value="1" /></td>
                  <td width="99%" style="padding:0px 0px 0px 5px">Unlink this Sample from any previous Patients</td>
                </tr>
              </table></td>
            </tr>
            <tr> 
              <td style="border-top:1px solid #cccccc; padding-top:10px"> 
              <input type="submit" name="button" id="button" value="   Link Patient to Sample   " /> 
              <button type="button" id="button" name="button" value="button" onclick="document.location.href='?act=linkPatient&nav=datamanagement'">&nbsp;&nbsp;Cancel&nbsp;&nbsp;</button>
              <? if($task=="modify") { ?>
              <input name="id" type="hidden" id="id" value="<?=$id?>"> 
              <? } ?>
              <input name="act" type="hidden" id="act" value="linkPatient">
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
                          <td class="vl_tdsub" width="40%"><strong>Sample Form Number(s)</strong></td>
                        </tr>
                    	<?
                        $count=0;
                        $q=array();
                        while($q=mysqlfetcharray($query)) {
                            $count+=1;
							$samples=0;
							$samples="";
							$squery=0;
							$squery=mysqlquery("select * from vl_samples where patientID='$q[id]'");
							if(mysqlnumrows($squery)) {
								while($sq=mysqlfetcharray($squery)) {
									$samples.="$sq[formNumber] ";
								}
							}
                        ?>
                            <tr>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>"><?=($q["artNumber"]?$q["artNumber"]:"&nbsp;")?></td>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>"><?=($q["otherID"]?$q["otherID"]:"&nbsp;")?></td>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>"><?=($samples?$samples:"&nbsp;")?></td>
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
        
        <form name="filterPatients" method="post" action="?act=linkPatient&nav=datamanagement" onsubmit="return checkFilterForm(this)">
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