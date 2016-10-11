<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}
?>
  <table width="100%" border="0">
    <tr>
      <td width="65%" valign="top">
		<? if($saved) { ?>
            <table width="100%" border="0" class="vl">
                <tr>
                    <td class="vl_success">Facility Added!</td>
                </tr>
                <tr>
                    <td><img src="/facility/images/spacer.gif" width="3" height="3" /></td>
                </tr>
            </table>
        <? } ?>
		<?
        switch($option) {
            case modify:
				//check for missing variables
				$error=0;
				$error="";
				//facility
				if(!$newFacilityID)
					$error.="<br>No New Facility provided";

				//process
				if(!$error) {
					//get all samples from this facility
					$query=0;
					$query=mysqlquery("select * from vl_samples where facilityID='$facilityID'");
					if(mysqlnumrows($query)) {
						while($q=mysqlfetcharray($query)) {
							//log table change
							logTableChange("vl_samples","facilityID",$q["id"],$facilityID,$newFacilityID);
							//update vl_facilities
							mysqlquery("update vl_samples set facilityID='$newFacilityID' where id='$q[id]'");
						}
						//remove the old facility
						if(isQuery("select * from vl_facilities where id='$facilityID'")) {
							//remove
							logDataRemoval("delete from vl_facilities where id='$facilityID'");
							mysqlquery("delete from vl_facilities where id='$facilityID'");
							//redirect
							go("?act=facilities&districtID=$districtID&nav=configuration&removed=1");
						}
					}
				}
				
				//flag
				$removed=1;
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
?>
        <script Language="JavaScript" Type="text/javascript">
		<!--
        function checkForm(facilitiesForm) {
			//missing facility
			if(!document.facilitiesForm.newFacilityID.value) {
				alert('Missing Mandatory Field: New Facility');
				document.facilitiesForm.newFacilityID.focus();
				return (false);
			}
            return (true);
        }
        //-->
        </script>
        
        <form name="facilitiesForm" method="post" action="?act=facilitiesmigratesamples&districtID=<?=$districtID?>&nav=configuration" onsubmit="return checkForm(this)">
          <table width="90%" border="0" class="vl">
		<? if($added) { ?>
            <tr>
              <td class="vl_success">Added!</td>
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
		<? } ?>
            <tr>
              <td style="border-bottom:1px solid #cccccc; padding-bottom:10px">
              	<div style="padding:0px 0px 5px 0px">Before deleting <strong><?=getDetailedTableInfo2("vl_facilities","id='$facilityID'","facility")?></strong> from the list of facilities, first select the new Facility to which the <strong><?=number_format((float)getDetailedTableInfo2("vl_samples","facilityID='$facilityID'","count(id)","num"))?></strong> sample(s) from <strong><?=getDetailedTableInfo2("vl_facilities","id='$facilityID'","facility")?></strong> are to be moved to.</div>
                <div class="vls_grey" style="padding:5px 0px 0px 0px">This is done to avoid a situation where reports are generated from the ViralLoad system but show samples as not having facilities because the facilities have been deleted.</div>
              </td>
            </tr>
            <tr> 
              <td style="padding:10px 0px 10px 0px"><table width="100%" border="0" class="vl">
                <tr>
                  <td width="30%">New Facility</td>
                  <td width="70%"><select name="newFacilityID" id="newFacilityID" class="search">
						<?
						$query=0;
						$query=mysqlquery("select * from vl_facilities order by facility");
						echo "<option value=\"\" selected=\"selected\">Select New Facility</option>";
						if(mysqlnumrows($query)) {
							while($q=mysqlfetcharray($query)) {
								echo "<option value=\"$q[id]\">$q[facility]</option>";
							}
                        }
                        ?>
                        </select></td>
                </tr>
              </table></td>
            </tr>
            <tr> 
              <td style="border-top:1px solid #cccccc; padding-top:10px"> 
              <input type="submit" name="button" id="button" value=" Migrate Samples to New Facility, then Delete <?=getDetailedTableInfo2("vl_facilities","id='$facilityID'","facility")?> " /> 
              <input name="act" type="hidden" id="act" value="facilitiesmigratesamples">
              <input name="districtID" type="hidden" id="districtID" value="<?=$districtID?>">
              <input name="facilityID" type="hidden" id="facilityID" value="<?=$facilityID?>">
              <input name="option" type="hidden" id="option" value="modify">
              </td>
            </tr>
          </table>
        </form>
      </td>
      <td width="35%" valign="top" style="padding:3px 0px 0px 12px">
        <table border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #d5d5d5" width="100%">
          <tr>
            <td style="padding:10px"><table width="100%" border="0" class="vl">
              <tr>
                <td><strong>SELECT NEW FACILITY</strong></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td bgcolor="#d5d5d5" style="padding:10px">Indicate the new facility to samples are to be moved. This is aimed at maintaining data integrity within the ViralLoad system.</td>
              </tr>
            </table></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>