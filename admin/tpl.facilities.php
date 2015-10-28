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
            case add:
				//check for missing variables
				$error=0;
				$error="";
				//facility
				if(!$facility)
					$error.="<br>No Facility provided";

				//process
				if(!$error) {
					//ensure no duplicates
					if(!isQuery("select * from vl_facilities where facility='$facility' and districtID='$districtID'")) {
						//insert into vl_facilities
						mysqlquery("insert into vl_facilities 
								(facility,districtID,phone,email,
									contactPerson,physicalAddress,returnAddress,
									created,createdby) 
								values 
								('$facility','$districtID','$phone','$email',
									'$contactPerson','$physicalAddress','$returnAddress',
									'$datetime','$_SESSION[VLADMIN]')");
						//flag
						$added=1;
					} else {
						$error.="<br>The supplied Facility <strong>$facility</strong> has already been provided for ".getDetailedTableInfo2("vl_districts","id='$districtID'","district")." District.";
					}
				}
            break;
            case modify:
				//log table change
				logTableChange("vl_facilities","districtID",$id,getDetailedTableInfo2("vl_facilities","id='$id'","districtID"),$districtID);
				logTableChange("vl_facilities","facility",$id,getDetailedTableInfo2("vl_facilities","id='$id'","facility"),$facility);
				logTableChange("vl_facilities","phone",$id,getDetailedTableInfo2("vl_facilities","id='$id'","phone"),$phone);
				logTableChange("vl_facilities","email",$id,getDetailedTableInfo2("vl_facilities","id='$id'","email"),$email);
				logTableChange("vl_facilities","contactPerson",$id,getDetailedTableInfo2("vl_facilities","id='$id'","contactPerson"),$contactPerson);
				logTableChange("vl_facilities","physicalAddress",$id,getDetailedTableInfo2("vl_facilities","id='$id'","physicalAddress"),$physicalAddress);
				logTableChange("vl_facilities","returnAddress",$id,getDetailedTableInfo2("vl_facilities","id='$id'","returnAddress"),$returnAddress);
				//update vl_facilities
				mysqlquery("update vl_facilities set 
									facility='$facility',
									phone='$phone',
									email='$email',
									contactPerson='$contactPerson',
									physicalAddress='$physicalAddress',
									returnAddress='$returnAddress',
									districtID='$districtID' 
									where 
									id='$id'");
				//flag
				$modified=1;
            break;
            case remove:
				if(isQuery("select * from vl_facilities where id='$id'")) {
					//remove
					logDataRemoval("delete from vl_facilities where id='$id'");
					mysqlquery("delete from vl_facilities where id='$id'");
					//flag
					$removed=1;
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
?>
        <script Language="JavaScript" Type="text/javascript">
		<!--
        function checkForm(facilitiesForm) {
			//missing facility
			if(!document.facilitiesForm.facility.value) {
				alert('Missing Mandatory Field: Facility');
				document.facilitiesForm.facility.focus();
				return (false);
			}
            return (true);
        }
        //-->
        </script>
        
        <form name="facilitiesForm" method="post" action="?act=facilities&districtID=<?=$districtID?>&nav=configuration" onsubmit="return checkForm(this)">
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
              <td class="vl_success"><?=number_format((float)$modified)?> facility<?=($modified!=1?"s":"x")?> modified!</td>
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
		<? } ?>
        <? if($task=="add") { ?>
            <tr>
              <td style="border-bottom:1px solid #cccccc; padding-bottom:10px">Add Facility into <?=getDetailedTableInfo2("vl_districts","id='$districtID'","district")?> District</td>
            </tr>
        <? } else { ?>
            <tr>
              <td style="border-bottom:1px solid #cccccc; padding-bottom:10px">Manage Facilities within <?=getDetailedTableInfo2("vl_districts","id='$districtID'","district")?> District</td>
            </tr>
        <? } ?>
            <tr> 
              <td style="padding:10px 0px 10px 0px"><table width="100%" border="0" class="vl">
                <tr>
                  <td width="30%">Facility</td>
                  <td width="70%"><input type="text" name="facility" id="facility" class="search" size="25" value="<?=($id?getDetailedTableInfo2("vl_facilities","id='$id'","facility"):"")?>"></td>
                </tr>
                <tr>
                  <td>Phone</td>
                  <td><input type="text" name="phone" id="phone" class="search" size="20" value="<?=($id?getDetailedTableInfo2("vl_facilities","id='$id'","phone"):"")?>"></td>
                </tr>
                <tr>
                  <td>Email</td>
                  <td><input type="text" name="email" id="email" class="search" size="20" value="<?=($id?getDetailedTableInfo2("vl_facilities","id='$id'","email"):"")?>"></td>
                </tr>
                <tr>
                  <td>Contact&nbsp;Person</td>
                  <td><input type="text" name="contactPerson" id="contactPerson" class="search" size="25" value="<?=($id?getDetailedTableInfo2("vl_facilities","id='$id'","contactPerson"):"")?>"></td>
                </tr>
                <tr>
                  <td>Physical&nbsp;Address</td>
                  <td><textarea name="physicalAddress" id="physicalAddress" cols="40" rows="2" class="searchLarge"><?=($id?getDetailedTableInfo2("vl_facilities","id='$id'","physicalAddress"):"")?></textarea></td>
                </tr>
                <tr>
                  <td>Return&nbsp;Address</td>
                  <td><textarea name="returnAddress" id="returnAddress" cols="40" rows="2" class="searchLarge"><?=($id?getDetailedTableInfo2("vl_facilities","id='$id'","returnAddress"):"")?></textarea></td>
                </tr>
                <tr>
                  <td>District</td>
                  <td><select name="districtID" id="districtID" class="search">
						<?
						$query=0;
						$query=mysqlquery("select * from vl_districts order by district");
						
						if($id) {
							echo "<option value=\"".getDetailedTableInfo2("vl_facilities","id='$id'","districtID")."\" selected=\"selected\">".getDetailedTableInfo2("vl_districts","id='".getDetailedTableInfo2("vl_facilities","id='$id'","districtID")."'","district")."</option>";
						} else {
							echo "<option value=\"$districtID\" selected=\"selected\">".getDetailedTableInfo2("vl_districts","id='$districtID'","district")."</option>";
						}
						
						if(mysqlnumrows($query)) {
							while($q=mysqlfetcharray($query)) {
								echo "<option value=\"$q[id]\">$q[district]</option>";
							}
                        }
                        ?>
                        </select></td>
                </tr>
              </table></td>
            </tr>
            <tr> 
              <td style="border-top:1px solid #cccccc; padding-top:10px"> 
              <input type="submit" name="button" id="button" value="   Save   " /> 
              <? if($task=="modify") { ?>
              <button type="button" id="button" name="button" value="button" onclick="document.location.href='?act=facilities&districtID=<?=$districtID?>&nav=configuration'">&nbsp;&nbsp;Cancel&nbsp;&nbsp;</button>
              <input name="id" type="hidden" id="id" value="<?=$id?>"> 
              <? } ?>
              <input name="act" type="hidden" id="act" value="facilities">
              <input name="districtID" type="hidden" id="districtID" value="<?=$districtID?>">
              <input name="option" type="hidden" id="option" value="<?=$task?>">
              </td>
            </tr>
          </table>
        </form>

		<?
        $query=0;
        $query=mysqlquery("select * from vl_facilities where districtID='$districtID' order by facility");
		$num=0;
		$num=mysqlnumrows($query);
        if($num) {
        ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="vl_tdsub" style="padding-left:20px"><strong>Facilities in <?=getDetailedTableInfo2("vl_districts","id='$districtID'","district")?> District</strong></td>
                        </tr>
					</table>
                </td>
            </tr>
            <tr>
                <td style="padding:5px 0px 5px 0px" align="center">
                	<div style="height: 200px; border: 1px solid #ccccff; overflow: auto">
					<table width="95%" border="0" cellspacing="0" cellpadding="0" class="vl">
                    	<?
                        $count=0;
                        $q=array();
                        while($q=mysqlfetcharray($query)) {
                            $count+=1;
                        ?>
                            <tr>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="70%"><?=$q["facility"]?></td>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="30%"><a href="?act=facilities&districtID=<?=$districtID?>&nav=configuration&modify=modify&id=<?=$q["id"]?>">edit</a> :: <a href="javascript:if(confirm('Are you sure?')) { document.location.href='?act=facilities&districtID=<?=$districtID?>&nav=configuration&option=remove&id=<?=$q["id"]?>'; }">delete</a></td>
                            </tr>
                        <? } ?>
                    </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="padding:5px 0px"><a href="?act=regions&nav=configuration">Return to List of Regions</a> :: <a href="?act=districts&regionID=<?=getDetailedTableInfo2("vl_districts","id='$districtID'","regionID")?>&nav=configuration">Return to List of Districts in <?=getDetailedTableInfo2("vl_regions","id='".getDetailedTableInfo2("vl_districts","id='$districtID'","regionID")."'","region")?> Region</a></td>
            </tr>
        </table>
		<? } ?>
      </td>
      <td width="35%" valign="top" style="padding:3px 0px 0px 12px">
        <table border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #d5d5d5" width="100%">
          <tr>
            <td style="padding:10px"><table width="100%" border="0" class="vl">
              <tr>
                <td><strong>MANAGE FACILITIES IN <?=getDetailedTableInfo2("vl_districts","id='$districtID'","district")?> DISTRICT</strong></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td bgcolor="#d5d5d5" style="padding:10px">Create, Delete or Manage Facilities</td>
              </tr>
            </table></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>