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
                    <td class="vl_success">Permission Added!</td>
                </tr>
                <tr>
                    <td><img src="/admin/images/spacer.gif" width="3" height="3" /></td>
                </tr>
            </table>
        <? } ?>
		<?
        switch($option) {
            case modify:
				$query=0;
				$query=mysqlquery("select * from vl_users_permissions where userID='$id'");
				if(mysqlnumrows($query)) {
					while($q=mysqlfetcharray($query)) {
						$permission=0;
						$xpermission=0;
						$xpermission=$q["permission"];
						$permission=$$xpermission;
						//log table change
						logTableChange("vl_users_permissions","permission",$q["id"],$q["permission"],$permission);
					}
				}

				//delete all user permissions
				mysqlquery("delete from vl_users_permissions where userID='$id'");

				//insert
				if($samples) { mysqlquery("insert into vl_users_permissions (userID,permission,created,createdby) values ('$id','samples','$datetime','$_SESSION[VLADMIN]')"); $added+=1; }
				if($verifySamples) { mysqlquery("insert into vl_users_permissions (userID,permission,created,createdby) values ('$id','verifySamples','$datetime','$_SESSION[VLADMIN]')"); $added+=1; }
				if($unVerifySamples) { mysqlquery("insert into vl_users_permissions (userID,permission,created,createdby) values ('$id','unVerifySamples','$datetime','$_SESSION[VLADMIN]')"); $added+=1; }
				if($worksheets) { mysqlquery("insert into vl_users_permissions (userID,permission,created,createdby) values ('$id','worksheets','$datetime','$_SESSION[VLADMIN]')"); $added+=1; }
				if($generateForms) { mysqlquery("insert into vl_users_permissions (userID,permission,created,createdby) values ('$id','generateForms','$datetime','$_SESSION[VLADMIN]')"); $added+=1; }
				if($results) { mysqlquery("insert into vl_users_permissions (userID,permission,created,createdby) values ('$id','results','$datetime','$_SESSION[VLADMIN]')"); $added+=1; }
				if($reports) { mysqlquery("insert into vl_users_permissions (userID,permission,created,createdby) values ('$id','reports','$datetime','$_SESSION[VLADMIN]')"); $added+=1; }
				if($reportsQC) { mysqlquery("insert into vl_users_permissions (userID,permission,created,createdby) values ('$id','reportsQC','$datetime','$_SESSION[VLADMIN]')"); $added+=1; }
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
		<? if($added) { ?>
            <table width="100%" border="0" class="vl">
                <tr>
                  <td class="vl_success"><?=number_format((float)$added)?> Permission<?=($added>1?"s":"")?> added/altered for <?=getDetailedTableInfo2("vl_users","id='$id'","names")?>!</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
            </table>
		<? } ?>

        <? if($task=="modify") { ?>
        <script Language="JavaScript" Type="text/javascript">
		<!--
        function checkForm(adminsForm) {
            return (true);
        }
        //-->
        </script>
        
        <form name="adminsForm" method="post" action="?act=permissions&nav=configuration" onsubmit="return checkForm(this)">
          <table width="100%" border="0" class="vl">
            <tr>
              <td style="border-bottom:1px solid #cccccc; padding-bottom:10px">Manage Permissions for <strong><?=getDetailedTableInfo2("vl_users","id='$id'","names")?></strong></td>
            </tr>
            <tr> 
              <td style="padding:10px 0px 10px 0px">
              
              <div style="height: 300px; border: 1px solid #ccccff; overflow: auto">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
                    <tr>
                      <td width="1%" class="vl_tdsub"><input type="checkbox" name="checkall" onClick="checkUncheckAll(this);"></td>
                      <td width="99%" class="vl_tdsub"><strong>Role</strong></td>
                    </tr>
                    <tr>
                      <td class="vl_tdstandard"><input name="samples" type="checkbox" id="samples" <?=(getDetailedTableInfo2("vl_users_permissions","userID='$id' and permission='samples'","id")?"checked=\"checked\"":"")?> value="1" /></td>
                      <td class="vl_tdstandard">Input/Manage Samples</td>
                    </tr>
                    <tr>
                      <td class="vl_tdstandard"><input name="verifySamples" type="checkbox" id="verifySamples" <?=(getDetailedTableInfo2("vl_users_permissions","userID='$id' and permission='verifySamples'","id")?"checked=\"checked\"":"")?> value="1" /></td>
                      <td class="vl_tdstandard">Verify Samples</td>
                    </tr>
                    <tr>
                      <td class="vl_tdstandard"><input name="unVerifySamples" type="checkbox" id="unVerifySamples" <?=(getDetailedTableInfo2("vl_users_permissions","userID='$id' and permission='unVerifySamples'","id")?"checked=\"checked\"":"")?> value="1" /></td>
                      <td class="vl_tdstandard">Reverse Approval of Samples</td>
                    </tr>
                    <tr>
                      <td class="vl_tdstandard"><input name="worksheets" type="checkbox" id="worksheets" <?=(getDetailedTableInfo2("vl_users_permissions","userID='$id' and permission='worksheets'","id")?"checked=\"checked\"":"")?> value="1" /></td>
                      <td class="vl_tdstandard">Generate Worksheets</td>
                    </tr>
                    <tr>
                      <td class="vl_tdstandard"><input name="generateForms" type="checkbox" id="generateForms" <?=(getDetailedTableInfo2("vl_users_permissions","userID='$id' and permission='generateForms'","id")?"checked=\"checked\"":"")?> value="1" /></td>
                      <td class="vl_tdstandard">Generate Clinical Request Forms</td>
                    </tr>
                    <tr>
                      <td class="vl_tdstandard"><input name="results" type="checkbox" id="results" <?=(getDetailedTableInfo2("vl_users_permissions","userID='$id' and permission='results'","id")?"checked=\"checked\"":"")?> value="1" /></td>
                      <td class="vl_tdstandard">View Results</td>
                    </tr>
                    <tr>
                      <td class="vl_tdstandard"><input name="reports" type="checkbox" id="reports" <?=(getDetailedTableInfo2("vl_users_permissions","userID='$id' and permission='reports'","id")?"checked=\"checked\"":"")?> value="1" /></td>
                      <td class="vl_tdstandard">View Reports</td>
                    </tr>
                    <tr>
                      <td class="vl_tdnoborder"><input name="reportsQC" type="checkbox" id="reportsQC" <?=(getDetailedTableInfo2("vl_users_permissions","userID='$id' and permission='reportsQC'","id")?"checked=\"checked\"":"")?> value="1" /></td>
                      <td class="vl_tdnoborder">View Reports (Quality Control)</td>
                    </tr>
                  </table>
                  </div>
                                
              </td>
            </tr>
            <tr> 
              <td style="border-top:1px solid #cccccc; padding-top:10px"> 
              <input type="submit" name="button" id="button" value="   Save   " /> 
              <? if($task=="modify") { ?>
              <button type="button" id="button" name="button" value="button" onclick="document.location.href='?act=permissions&nav=configuration'">&nbsp;&nbsp;Cancel&nbsp;&nbsp;</button>
              <input name="id" type="hidden" id="id" value="<?=$id?>"> 
              <? } ?>
              <input name="act" type="hidden" id="act" value="permissions">
              <input name="option" type="hidden" id="option" value="<?=$task?>">
              </td>
            </tr>
          </table>
        </form>
        <? } ?>

		<?
        $query=0;
        $query=mysqlquery("select * from vl_users order by names,email");
		$num=0;
		$num=mysqlnumrows($query);
        if($num && $task=="add") {
        ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
            <tr>
              <td style="border-bottom:1px solid #cccccc; padding-bottom:10px">Select User Account</td>
            </tr>
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="vl_tdsub" style="padding-left:16px" width="58%"><strong>Names</strong></td>
                          <td class="vl_tdsub" width="42%"><strong>Permissions</strong></td>
                        </tr>
					</table>
                </td>
            </tr>
            <tr>
                <td style="padding:5px 0px 5px 0px" align="center">
                	<div style="height: 200px; border: 1px solid #ccccff; overflow: auto">
					<table width="95%" border="0" cellspacing="0" cellpadding="0" class="trailanalytics">
                    	<?
                        $count=0;
                        $q=array();
                        while($q=mysqlfetcharray($query)) {
                            $count+=1;
							$permissions=0;
							$permissions=getDetailedTableInfo3("vl_users_permissions","userID='$q[id]'","count(id)","num");
                        ?>
                            <tr>
                                <td width="60%" class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>"><a href="?act=permissions&nav=configuration&task=modify&id=<?=$q["id"]?>"><?=$q["names"]?></a></td>
                                <td width="40%" class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>"><?=number_format((float)$permissions)?></td>
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
                <td><strong>MANAGE PERMISSIONS</strong></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td bgcolor="#d5d5d5" style="padding:10px">Create, Delete or Manage Permissions</td>
              </tr>
            </table></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>