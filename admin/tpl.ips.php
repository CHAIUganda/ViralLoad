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
                    <td class="vl_success">IP Added!</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </table>
        <? } ?>
		<?
        switch($option) {
            case add:
				//check for missing variables
				$error=0;
				$error="";
				//ip
				if(!$ip)
					$error.="<br>No IP provided";

				//process
				if(!$error) {
					//ensure no duplicates
					if(!isQuery("select * from vl_ips where ip='$ip'")) {
						//insert into vl_ips
						mysqlquery("insert into vl_ips 
								(ip,created,createdby) 
								values 
								('$ip','$datetime','$_SESSION[VLADMIN]')");
						//flag
						$added=1;
					} else {
						$error.="<br>The supplied IP <strong>$ip</strong> is already within the system";
					}
				}
            break;
            case modify:
				//log table change
				logTableChange("vl_ips","ip",$id,getDetailedTableInfo2("vl_ips","id='$id'","ip"),$ip);
				//update vl_ips
				mysqlquery("update vl_ips set ip='$ip' where id='$id'");
				//flag
				$modified=1;
            break;
            case remove:
				if(isQuery("select * from vl_ips where id='$id'")) {
					//remove
					logDataRemoval("delete from vl_ips where id='$id'");
					mysqlquery("delete from vl_ips where id='$id'");
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
        function checkForm(ipsForm) {
			//missing ip
			if(!document.ipsForm.ip.value) {
				alert('Missing Mandatory Field: IP');
				document.ipsForm.ip.focus();
				return (false);
			}
            return (true);
        }
        //-->
        </script>
        
        <form name="ipsForm" method="post" action="?act=ips&nav=configuration" onsubmit="return checkForm(this)">
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
              <td class="vl_success"><?=number_format((float)$modified)?> ip<?=($modified!=1?"s":"")?> modified!</td>
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
              <td style="border-bottom:1px solid #cccccc; padding-bottom:10px">Add IP</td>
            </tr>
        <? } else { ?>
            <tr>
              <td style="border-bottom:1px solid #cccccc; padding-bottom:10px">Manage IPs</td>
            </tr>
        <? } ?>
            <tr> 
              <td style="padding:10px 0px 10px 0px"><table width="100%" border="0" class="vl">
                <tr>
                  <td width="30%">IP</td>
                  <td width="70%"><input type="text" name="ip" id="ip" class="search" size="25" value="<?=($id?getDetailedTableInfo2("vl_ips","id='$id'","ip"):"")?>"></td>
                </tr>
              </table></td>
            </tr>
            <tr> 
              <td style="border-top:1px solid #cccccc; padding-top:10px"> 
              <input type="submit" name="button" id="button" value="   Save   " /> 
              <? if($task=="modify") { ?>
              <button type="button" id="button" name="button" value="button" onclick="document.location.href='?act=ips&nav=configuration'">&nbsp;&nbsp;Cancel&nbsp;&nbsp;</button>
              <input name="id" type="hidden" id="id" value="<?=$id?>"> 
              <? } ?>
              <input name="act" type="hidden" id="act" value="ips">
              <input name="option" type="hidden" id="option" value="<?=$task?>">
              </td>
            </tr>
          </table>
        </form>

		<?
        $query=0;
        $query=mysqlquery("select * from vl_ips order by ip");
		$num=0;
		$num=mysqlnumrows($query);
        if($num) {
        ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="vl_tdsub" style="padding-left:20px"><strong>IP</strong></td>
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
							$facilities=getDetailedTableInfo3("vl_facilities","ipID='$q[id]'","count(id)","num");
                        ?>
                            <tr>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="70%">
                                <div><a href="?act=ipsfacilities&ipID=<?=$q["id"]?>&nav=configuration"><?=$q["ip"]?></a></div>
                                <div class="vls_grey" style="padding:3px 0px"><?=number_format((float)$facilities)?> Facilit<?=($facilities==1?"y":"ies")?></div>
								</td>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="30%"><a href="?act=ips&nav=configuration&modify=modify&id=<?=$q["id"]?>">edit</a> :: <a href="javascript:if(confirm('Are you sure?')) { document.location.href='?act=ips&nav=configuration&option=remove&id=<?=$q["id"]?>'; }">delete</a></td>
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
                <td><strong>MANAGE IPS</strong></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td bgcolor="#d5d5d5" style="padding:10px">Create, Delete or Manage IPs</td>
              </tr>
            </table></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>