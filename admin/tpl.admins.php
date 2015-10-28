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
                    <td class="vl_success">Administrator Added!</td>
                </tr>
                <tr>
                    <td><img src="/admin/images/spacer.gif" width="3" height="3" /></td>
                </tr>
            </table>
        <? } ?>
		<?
        switch($option) {
            case add:
				//check for missing variables
				$error=0;
				$error="";
				//username
				if(!$username)
					$error.="<br>No Username provided";
				//password
				if(!$password) 
					$error.="<br>No Password provided";
				//email
				if(!$email) 
					$error.="<br>No Email provided";

				//process
				if(!$error) {
					//ensure no duplicates
					if(!isQuery("select * from vl_admins where username='$username'")) {
						//insert into vl_admins
						mysqlquery("insert into vl_admins 
								(username,password,email,phone,created,createdby) 
								values 
								('$username','".vlSimpleEncrypt($password)."','$email','$phone','$datetime','$_SESSION[VLADMIN]')");
						//flag
						$added=1;
					} else {
						$error.="<br>The supplied Username <strong>$username</strong> is already within the system";
					}
				}
            break;
            case modify:
				//log table change
				logTableChange("vl_admins","username",$id,getDetailedTableInfo2("vl_admins","id='$id'","username"),$username);
				logTableChange("vl_admins","email",$id,getDetailedTableInfo2("vl_admins","id='$id'","email"),$email);
				logTableChange("vl_admins","phone",$id,getDetailedTableInfo2("vl_admins","id='$id'","phone"),$phone);
				logTableChange("vl_admins","password",$id,"old password","new password");
				//update vl_admins
				mysqlquery("update vl_admins set 
								username='$username',
								email='$email',
								".($password?"password='".vlSimpleEncrypt($password)."',":"")."
								phone='$phone' 
								where id='$id'");
				//flag
				$modified=1;
            break;
            case remove:
				if(isQuery("select * from vl_admins where id='$id'")) {
					//remove
					logDataRemoval("delete from vl_admins where id='$id'");
					mysqlquery("delete from vl_admins where id='$id'");
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
        function checkForm(adminsForm) {
            return (true);
        }
        //-->
        </script>
        
        <form name="adminsForm" method="post" action="?act=admins&nav=configuration" onsubmit="return checkForm(this)">
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
              <td class="vl_success"><?=number_format((float)$modified)?> admin<?=($modified!=1?"s":"")?> modified!</td>
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
              <td style="border-bottom:1px solid #cccccc; padding-bottom:10px">Add Admin</td>
            </tr>
        <? } else { ?>
            <tr>
              <td style="border-bottom:1px solid #cccccc; padding-bottom:10px">Manage Admins</td>
            </tr>
        <? } ?>
            <tr> 
              <td style="padding:10px 0px 10px 0px"><table width="100%" border="0" class="vl">
                <tr>
                  <td width="30%">Username</td>
                  <td width="70%"><input type="text" name="username" id="username" class="search" size="25" value="<?=($id?getDetailedTableInfo2("vl_admins","id='$id'","username"):"")?>"></td>
                </tr>
                <tr>
                  <td>Password</td>
                  <td><input type="password" name="password" id="password" class="search" size="25" value=""></td>
                </tr>
                <tr>
                  <td>Email</td>
                  <td><input type="text" name="email" id="email" class="search" size="25" value="<?=($id?getDetailedTableInfo2("vl_admins","id='$id'","email"):"")?>"></td>
                </tr>
                <tr>
                  <td>Phone</td>
                  <td><input type="text" name="phone" id="phone" class="search" size="25" value="<?=($id?getDetailedTableInfo2("vl_admins","id='$id'","phone"):"+")?>"></td>
                </tr>
               <? if($id && $option!="remove") { ?>
                <tr>
                  <td>Last Login</td>
                  <td><?=($id?getFormattedDate(getDetailedTableInfo2("vl_admins","id='$id'","login")):"Unavailable")?></td>
                </tr>
               <? } ?>
              </table></td>
            </tr>
            <tr> 
              <td style="border-top:1px solid #cccccc; padding-top:10px"> 
              <input type="submit" name="button" id="button" value="   Save   " /> 
              <? if($task=="modify") { ?>
              <button type="button" id="button" name="button" value="button" onclick="document.location.href='?act=admins&nav=configuration'">&nbsp;&nbsp;Cancel&nbsp;&nbsp;</button>
              <input name="id" type="hidden" id="id" value="<?=$id?>"> 
              <? } ?>
              <input name="act" type="hidden" id="act" value="admins">
              <input name="option" type="hidden" id="option" value="<?=$task?>">
              </td>
            </tr>
          </table>
        </form>

		<?
        $query=0;
        $query=mysqlquery("select * from vl_admins order by username");
		$num=0;
		$num=mysqlnumrows($query);
        if($num) {
        ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="vl_tdsub" width="21%" style="padding-left:16px"><strong>Username</strong></td>
                          <td class="vl_tdsub" width="38%"><strong>Email</strong></td>
                          <td class="vl_tdsub" width="39%"><strong>Last Login</strong></td>
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
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="20%"><?=$q["username"]?></td>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="40%"><?=$q["email"]?></td>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="20%"><?=(getFormattedDate($q["login"])?getFormattedDateLessDay($q["login"]):"Unavailable")?></td>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="20%"><a href="?act=admins&nav=configuration&modify=modify&id=<?=$q["id"]?>">edit</a> :: <a href="javascript:if(confirm('Are you sure?')) { document.location.href='?act=admins&nav=configuration&option=remove&id=<?=$q["id"]?>'; }">delete</a></td>
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
                <td><strong>MANAGE ADMINISTRATORS</strong></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td bgcolor="#d5d5d5" style="padding:10px">Create, Delete or Manage Administrators</td>
              </tr>
            </table></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>