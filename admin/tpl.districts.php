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
                    <td class="vl_success">District Added!</td>
                </tr>
                <tr>
                    <td><img src="/district/images/spacer.gif" width="3" height="3" /></td>
                </tr>
            </table>
        <? } ?>
		<?
        switch($option) {
            case add:
				//check for missing variables
				$error=0;
				$error="";
				//district
				if(!$district)
					$error.="<br>No District provided";

				//process
				if(!$error) {
					//ensure no duplicates
					if(!isQuery("select * from vl_districts where district='$district'")) {
						//insert into vl_districts
						mysqlquery("insert into vl_districts 
								(district,regionID,created,createdby) 
								values 
								('$district','$formRegionID','$datetime','$_SESSION[VLADMIN]')");
						//flag
						$added=1;
					} else {
						$error.="<br>The supplied District <strong>$district</strong> is already within the system";
					}
				}
            break;
            case modify:
				//log table change
				logTableChange("vl_districts","regionID",$id,getDetailedTableInfo2("vl_districts","id='$id'","regionID"),$formRegionID);
				logTableChange("vl_districts","district",$id,getDetailedTableInfo2("vl_districts","id='$id'","district"),$district);
				//update vl_districts
				mysqlquery("update vl_districts set district='$district',regionID='$formRegionID' where id='$id'");

				//flag
				$modified=1;
            break;
            case remove:
				if(isQuery("select * from vl_districts where id='$id'")) {
					//remove dependencies
					logDataRemoval("delete from vl_facilities where districtID='$id'");
					mysqlquery("delete from vl_facilities where districtID='$id'");
					//remove
					logDataRemoval("delete from vl_districts where id='$id'");
					mysqlquery("delete from vl_districts where id='$id'");
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
        function checkForm(districtsForm) {
			//missing district
			if(!document.districtsForm.district.value) {
				alert('Missing Mandatory Field: District');
				document.districtsForm.district.focus();
				return (false);
			}
            return (true);
        }
        //-->
        </script>
        <form name="districtsForm" method="post" action="?act=districts&getRegionID=<?=$regionID?>&nav=configuration" onsubmit="return checkForm(this)">

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
              <td class="vl_success"><?=number_format((float)$modified)?> district<?=($modified!=1?"s":"x")?> modified!</td>
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
              <td style="border-bottom:1px solid #cccccc; padding-bottom:10px">Add District into the <?=getDetailedTableInfo2("vl_regions","id='$regionID'","region")?> Region</td>
            </tr>
        <? } else { ?>
            <tr>
              <td style="border-bottom:1px solid #cccccc; padding-bottom:10px">Manage Districts within the <?=getDetailedTableInfo2("vl_regions","id='$regionID'","region")?> Region</td>
            </tr>
        <? } ?>
            <tr> 
              <td style="padding:10px 0px 10px 0px"><table width="100%" border="0" class="vl">
                <tr>
                  <td width="30%">District</td>
                  <td width="70%"><input type="text" name="district" id="district" class="search" size="25" value="<?=($id?getDetailedTableInfo2("vl_districts","id='$id'","district"):"")?>"></td>
                </tr>
                <tr>
                  <td>Region</td>
                  <td><select name="formRegionID" id="formRegionID" class="search">
						<?
						$query=0;
						$query=mysqlquery("select * from vl_regions order by region");
						
						if($id) {
							echo "<option value=\"".getDetailedTableInfo2("vl_districts","id='$id'","regionID")."\" selected=\"selected\">".getDetailedTableInfo2("vl_regions","id='".getDetailedTableInfo2("vl_districts","id='$id'","regionID")."'","region")."</option>";
						} else {
							echo "<option value=\"$formRegionID\" selected=\"selected\">".getDetailedTableInfo2("vl_regions","id='$formRegionID'","region")."</option>";
						}
						
						if(mysqlnumrows($query)) {
							while($q=mysqlfetcharray($query)) {
								echo "<option value=\"$q[id]\">$q[region]</option>";
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
              <button type="button" id="button" name="button" value="button" onclick="document.location.href='?act=districts&regionID=<?=$regionID?>&nav=configuration'">&nbsp;&nbsp;Cancel&nbsp;&nbsp;</button>
              <input name="id" type="hidden" id="id" value="<?=$id?>"> 
              <? } ?>
              <input name="act" type="hidden" id="act" value="districts">
              <input name="regionID" type="hidden" id="regionID" value="<?=$regionID?>">
              <input name="option" type="hidden" id="option" value="<?=$task?>">
              </td>
            </tr>
          </table>
        </form>

		<?
        $query=0;
        $query=mysqlquery("select * from vl_districts where regionID='$regionID' order by district");
		$num=0;
		$num=mysqlnumrows($query);
        if($num) {
        ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="vl_tdsub" style="padding-left:20px"><strong>Districts in <?=getDetailedTableInfo2("vl_regions","id='$regionID'","region")?> Region</strong></td>
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
							$facilities=0;
							$facilities=getDetailedTableInfo3("vl_facilities","districtID='$q[id]'","count(id)","num");
                        ?>
                            <tr>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="70%">
                                <div><a href="?act=facilities&districtID=<?=$q["id"]?>&nav=configuration"><?=$q["district"]?></a></div>
                                <div class="vls_grey" style="padding:3px 0px"><?=number_format((float)$facilities)?> Facilit<?=($facilities==1?"y":"ies")?></div>
                                </td>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="30%"><a href="?act=districts&regionID=<?=$regionID?>&nav=configuration&modify=modify&id=<?=$q["id"]?>">edit</a> :: <a href="javascript:if(confirm('Are you sure?')) { document.location.href='?act=districts&regionID=<?=$regionID?>&nav=configuration&option=remove&id=<?=$q["id"]?>'; }">delete</a></td>
                            </tr>
                        <? } ?>
                    </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="padding:5px 0px"><a href="?act=regions&nav=configuration">Return to List of Regions</a></td>
            </tr>
        </table>
		<? } ?>
      </td>
      <td width="35%" valign="top" style="padding:3px 0px 0px 12px">
      <div>
        <table border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #d5d5d5" width="100%">
          <tr>
            <td style="padding:10px"><table width="100%" border="0" class="vl">
              <tr>
                <td><strong>MANAGE DISTRICTS IN <?=getDetailedTableInfo2("vl_regions","id='$regionID'","region")?> REGION</strong></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td bgcolor="#d5d5d5" style="padding:10px">Create, Delete or Manage Districts</td>
              </tr>
            </table></td>
          </tr>
        </table>
        </div>
        <div style="padding:10px 0px 0px 0px">
			<script Language="JavaScript" Type="text/javascript">
            <!--
            function checkFacilityFilterForm(filterPatients) {
                if(!document.findFacility.facilityName.value) {
                    alert('Missing Mandatory Field: Facility Name');
                    document.findFacility.facilityName.focus();
                    return (false);
                }
                return (true);
            }
            //-->
            </script>
            
            <form name="findFacility" method="post" action="?act=facilities&nav=configuration" onsubmit="return checkFacilityFilterForm(this)">
                <table border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #d5d5d5" width="100%">
                  <tr>
                    <td style="padding:10px"><table width="100%" border="0" class="vl">
                      <tr>
                        <td><strong>SEARCH FACILITIES</strong></td>
                      </tr>
                      <tr>
                        <td style="padding:10px 0px"><input type="text" name="facilityName" id="facilityName" value="Enter Facility Name" class="searchLarge" size="20" maxlength="50" onfocus="if(value=='Enter Facility Name') {value=''}" onblur="if(value=='') {value='Enter Facility Name'}" /></td>
                      </tr>
                      <tr>
                        <td>
                        	<input type="submit" name="button" id="button" value="   Find Facility   " />
                            <input name="districtID" type="hidden" id="districtID" value="<?=$districtID?>">
                        </td>
                      </tr>
                    </table></td>
                  </tr>
                </table>
            </form>
        </div>
      </td>
    </tr>
  </table>