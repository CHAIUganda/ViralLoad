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
                    <td class="vl_success">Region Added!</td>
                </tr>
                <tr>
                    <td><img src="/region/images/spacer.gif" width="3" height="3" /></td>
                </tr>
            </table>
        <? } ?>
		<?
        switch($option) {
            case add:
				//check for missing variables
				$error=0;
				$error="";
				//region
				if(!$region)
					$error.="<br>No Region provided";

				//process
				if(!$error) {
					//ensure no duplicates
					if(!isQuery("select * from vl_regions where region='$region'")) {
						//insert into vl_regions
						mysqlquery("insert into vl_regions 
								(region,created,createdby) 
								values 
								('$region','$datetime','$_SESSION[VLADMIN]')");
						//flag
						$added=1;
					} else {
						$error.="<br>The supplied Region <strong>$region</strong> is already within the system";
					}
				}
            break;
            case modify:
				//log table change
				logTableChange("vl_regions","region",$id,getDetailedTableInfo2("vl_regions","id='$id'","region"),$region);
				//update vl_regions
				mysqlquery("update vl_regions set region='$region' where id='$id'");
				//flag
				$modified=1;
            break;
            case remove:
				if(isQuery("select * from vl_regions where id='$id'")) {
					$query=0;
					$query=mysqlquery("select * from vl_districts where regionID='$id'");
					if(mysqlnumrows($query)) {
						while($q=mysqlfetcharray($query)) {
							//remove dependencies
							logDataRemoval("delete from vl_facilities where districtID='$q[id]'");
							mysqlquery("delete from vl_facilities where districtID='$q[id]'");
						}
					}
					//remove dependencies
					logDataRemoval("delete from vl_districts where regionID='$id'");
					mysqlquery("delete from vl_districts where regionID='$id'");
					//remove
					logDataRemoval("delete from vl_regions where id='$id'");
					mysqlquery("delete from vl_regions where id='$id'");
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
        function checkForm(regionsForm) {
			//missing region
			if(!document.regionsForm.region.value) {
				alert('Missing Mandatory Field: Region');
				document.regionsForm.region.focus();
				return (false);
			}
            return (true);
        }
        //-->
        </script>
        
        <form name="regionsForm" method="post" action="?act=regions&nav=configuration" onsubmit="return checkForm(this)">
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
              <td class="vl_success"><?=number_format((float)$modified)?> region<?=($modified!=1?"s":"x")?> modified!</td>
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
              <td style="border-bottom:1px solid #cccccc; padding-bottom:10px">Add Region</td>
            </tr>
        <? } else { ?>
            <tr>
              <td style="border-bottom:1px solid #cccccc; padding-bottom:10px">Manage Regions</td>
            </tr>
        <? } ?>
            <tr> 
              <td style="padding:10px 0px 10px 0px"><table width="100%" border="0" class="vl">
                <tr>
                  <td width="30%">Region</td>
                  <td width="70%"><input type="text" name="region" id="region" class="search" size="25" value="<?=($id?getDetailedTableInfo2("vl_regions","id='$id'","region"):"")?>"></td>
                </tr>
              </table></td>
            </tr>
            <tr> 
              <td style="border-top:1px solid #cccccc; padding-top:10px"> 
              <input type="submit" name="button" id="button" value="   Save   " /> 
              <? if($task=="modify") { ?>
              <button type="button" id="button" name="button" value="button" onclick="document.location.href='?act=regions&nav=configuration'">&nbsp;&nbsp;Cancel&nbsp;&nbsp;</button>
              <input name="id" type="hidden" id="id" value="<?=$id?>"> 
              <? } ?>
              <input name="act" type="hidden" id="act" value="regions">
              <input name="option" type="hidden" id="option" value="<?=$task?>">
              </td>
            </tr>
          </table>
        </form>

		<?
        $query=0;
        $query=mysqlquery("select * from vl_regions order by region");
		$num=0;
		$num=mysqlnumrows($query);
        if($num) {
        ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="vl_tdsub" style="padding-left:20px"><strong>Region</strong></td>
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
							$districts=0;
							$districts=getDetailedTableInfo3("vl_districts","regionID='$q[id]'","count(id)","num");
							$facilities=0;
							$facilities=getDetailedTableInfo3("vl_regions,vl_districts,vl_facilities","vl_regions.id=vl_districts.regionID and vl_districts.id=vl_facilities.districtID and vl_regions.id='$q[id]'","count(distinct vl_facilities.id)","num");
                        ?>
                            <tr>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="70%">
                                <div><a href="?act=districts&regionID=<?=$q["id"]?>&nav=configuration"><?=$q["region"]?></a></div>
                                <div class="vls_grey" style="padding:3px 0px"><?=number_format((float)$districts)?> District<?=($districts==1?"":"s")?>, <?=number_format((float)$facilities)?> Facilit<?=($facilities==1?"y":"ies")?></div>
								</td>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="30%"><a href="?act=regions&nav=configuration&modify=modify&id=<?=$q["id"]?>">edit</a> :: <a href="javascript:if(confirm('Are you sure?')) { document.location.href='?act=regions&nav=configuration&option=remove&id=<?=$q["id"]?>'; }">delete</a></td>
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
            <td style="padding:10px"><table width="100%" border="0" class="vl">
              <tr>
                <td><strong>MANAGE REGIONS, DISTRICTS AND FACILITIES</strong></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td bgcolor="#d5d5d5" style="padding:10px">Create, Delete or Manage Regions</td>
              </tr>
            </table></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>