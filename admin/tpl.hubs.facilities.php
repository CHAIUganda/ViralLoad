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
                    <td class="vl_success">Facilities Assigned to <?=getDetailedTableInfo2("vl_hubs","id='$hubID'","hub")?>!</td>
                </tr>
                <tr>
                    <td><img src="/facility/images/spacer.gif" width="3" height="3" /></td>
                </tr>
            </table>
		<?
		}
		
        switch($option) {
			case commit:
				//commit to kcb_datacba111
				if(count($commitRecord)) {
					foreach($commitRecord as $fc) {
						mysqlquery("update vl_facilities set hubID='$hubID' where id='$fc'");
					}
				}
				go("?act=hubsfacilities&hubID=$hubID&nav=configuration&districtID=$districtID&saved=1");
			break;
		}
		
		//ran primary query
		$query=0;
        $query=mysqlquery("select * from vl_facilities ".($districtID?"where districtID='$districtID'":"")." order by facility");
		$num=0;
		$num=mysqlnumrows($query);
        if($num) {
        ?>
<script language="JavaScript" type="text/javascript">
<!--
function checkForm(rulesForm) {
	return (true);
}
//-->
</script>
<form action="?act=hubsfacilities&nav=configuration" method="post" name="removeDataForm" id="removeDataForm" onsubmit="return checkForm(this)">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
  <tr>
    <td style="padding:0px 0px 5px 0px"><strong>Select Facilities Serviced by <?=getDetailedTableInfo2("vl_hubs","id='$hubID'","hub")?></strong></td>
  </tr>
  <tr>
    <td style="padding:5px 0px 5px 0px"><div style="height: 400px; border: 1px solid #ccccff; overflow: auto">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="trailanalytics">
          <tr>
            <td class="vl_tdsub"><input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"></td>
            <td class="vl_tdsub" width="60%"><strong>Facility</strong></td>
            <td class="vl_tdsub" width="40%"><select name="position" id="position" class="search" onchange="document.location.href='?act=hubsfacilities&hubID=<?=$hubID?>&nav=configuration&districtID='+this.value">
						<?
						if($districtID) {
							echo "<option value=\"$districtID\" selected=\"selected\">".getDetailedTableInfo2("vl_districts","id='$districtID'","district")."</option>";
							echo "<option value=\"\">All Districts</option>";
						} else {
							echo "<option value=\"\" selected=\"selected\">Filter by District</option>";
						}
						$queryD=0;
						$queryD=mysqlquery("select * from vl_districts order by district");
						if(mysqlnumrows($queryD)) {
                        	while($qD=mysqlfetcharray($queryD)) {
		                        echo "<option value=\"$qD[id]\">$qD[district]</option>";
							}
                        }
                        ?>
                        </select></td>
          </tr>
        <?
		$count=0;
		$q=array();
		while($q=mysqlfetcharray($query)) {
			$count+=1;
		?>
        <tr>
          <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>"><input name="commitRecord[]" type="checkbox" id="commitRecord[]" value="<?="$q[id]"?>" <?=($fcsNumber?"checked=\"checked\"":"")?>></td>
          <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>">
		  <div><?=$q["facility"]?></div>
          <? if($q["hubID"]) { ?><div class="vls_grey" style="padding:3px 0px">Currently Serviced by: <?=getDetailedTableInfo2("vl_hubs","id='$q[hubID]'","hub")?></div><? } ?>
          </td>
          <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>"><?=getDetailedTableInfo2("vl_districts","id='$q[districtID]'","district")?></td>
        </tr>
        <? } ?>
      </table>
    </div></td>
  </tr>
  <tr>
    <td style="padding-top:10px"><input type="submit" name="button" id="button" value="   Assign to <?=getDetailedTableInfo2("vl_hubs","id='$hubID'","hub")?>   " /> 
									<input name="commit" type="hidden" id="commit" value="1" />
										<input name="act" type="hidden" id="act" value="hubsfacilities" />
                                        	<input name="option" type="hidden" id="option" value="commit" />
	                                    		<input name="hubID" type="hidden" id="hubID" value="<?=$hubID?>" />
                                                	<input name="districtID" type="hidden" id="districtID" value="<?=$districtID?>" /></td>
  </tr>
</table>
</form>
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