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
				//check for missing variables
				$error=0;
				$error="";
				//generate password
				//password
				if($password) {
					/*
					* check password
					* must be alphanumeric
					* must have upper and lower case characters
					* must be at least 8 characters in length
					*/
					if(!preg_match("/([0-9]+)/s", $password)) {
						$error.="<br>Password should contain at least one (1) numeric entry";
					} 
					if(!preg_match("/([a-z]+)/s", $password)) {
						$error.="<br>Password should contain at least one (1) lower case Character";
					}
					if(!preg_match("/([A-Z]+)/s", $password)) {
						//$error.="<br>Password should contain at least one (1) UPPER CASE Character";
					}
					if(!preg_match("/([^a-zA-Z0-9]+)/s", $password)) {
						//$error.="<br>Password should contain at least one (1) Special Character e.g. @#%&amp;!?";
					}
					if(strlen($password)<$default_passwordLength) {
						$error.="<br>Password should be at least $default_passwordLength characters in length";
					}
				} else {
					$password=generatePassword();
				}
				//email
				if(!$email) 
					$error.="<br>No Email provided";

				//process
				if(!$error) {
					//ensure no duplicates
					if(!isQuery("select * from vl_users where email='$email'")) {
						//insert into vl_users
						mysqlquery("insert into vl_users 
								(names,password,xp,email,phone,active,role,created,createdby) 
								values 
								('$names','".vlRencrypt($password)."','".vlSimpleEncrypt($password)."','$email','$phone',1,'$role','$datetime','$_SESSION[VLADMIN]')");

						//log within history
						$vlUserID=0;
						$vlUserID=getDetailedTableInfo2("vl_users","id>0 order by created desc limit 1","id");
						if($vlUserID) {
							mysqlquery("insert into vl_users_history 
									(userID,history,created,createdby) 
									values 
									('$vlUserID','".vlSimpleEncrypt($password)."','$datetime','$_SESSION[VLADMIN]')");
						}

						//file uploaded
						if($_FILES['userfile']['tmp_name']) {
							//has file been uploaded
							if(is_uploaded_file($_FILES['userfile']['tmp_name'])) {
								//filename
								$fileOriginalName=0;
								$fileOriginalName=$_FILES['userfile']['name'];
								$fileOriginalName=addslashes($fileOriginalName);
								//temp name
								$tmpName=0;
								$tmpName=$_FILES['userfile']['tmp_name'];
								//size
								$fileSize=0;
								$fileSize=$_FILES['userfile']['size'];
								//type
								$fileType=0;
								$fileType=$_FILES['userfile']['type'];
								//extension
								$extension=0;
								$extension=ext($fileOriginalName);
								//check to ensure this is xls
								if($extension!="jpg" && $extension!="jpeg" && $extension!="gif" && $extension!="png") {
									$error.="<br>Incorrect file extension '.$extension'. Only JPG, JPEG, PNG and GIF images are acceptable";
								}
								if($_FILES['userfile']['size']>$default_maxUploadSize) {
									$error.="<br>File size is greater than the accepted ".number_format((float)$default_maxUploadSize/1000)." KB. Please reduce the size of the file then attempt to upload it again.";
								}
								if(!$error) {
									$system_default_path.="images/signatures/";
									$server_url.="/images/signatures/";
									if(move_uploaded_file($_FILES['userfile']['tmp_name'],$system_default_path."signature.$vlUserID.$extension")) {
										//update vl_users
										mysqlquery("update vl_users set 
														signaturePATH='$system_default_path"."signature.$vlUserID.$extension',
														signatureURL='$server_url"."signature.$vlUserID.$extension' 
														where id='$vlUserID'");
									}
								}
							}
						}
							
						//email this user and inform them of their new account
						$from=0;
						$from="info@$home_domain";
											
						$fromName=0;
						$fromName="System";
																
						//subject
						$subject=0;
						$subject="Account Credentials";

//the message
$message=0;
$message="
Your Account on the \"VL\" System has been created with the following credentials: 

Email: $email
Password: $password 

LOGIN INSTRUCTIONS:
1. Open Internet Explorer
2. Visit http://$default_systemIP/ from your Web Browser.
3. Login with your Email and Password

Kind regards, 
System Team";
							
						//mail the user
						sendPlainEmail($email,$from,$fromName,$subject,"",$message);
							
						//flag
						$added=1;
					} else {
						$error.="<br>The supplied Email <strong>$email</strong> is already within the system";
					}
				}
            break;
            case modify:
				//check for missing variables
				$error=0;
				$error="";
				//password
				if($password) {
					/*
					* check password
					* must be alphanumeric
					* must have upper and lower case characters
					* must be at least 8 characters in length
					*/
					if(!preg_match("/([0-9]+)/s", $password)) {
						$error.="<br>Password should contain at least one (1) numeric entry";
					} 
					if(!preg_match("/([a-z]+)/s", $password)) {
						$error.="<br>Password should contain at least one (1) lower case Character";
					}
					if(!preg_match("/([A-Z]+)/s", $password)) {
						//$error.="<br>Password should contain at least one (1) UPPER CASE Character";
					}
					if(!preg_match("/([^a-zA-Z0-9]+)/s", $password)) {
						//$error.="<br>Password should contain at least one (1) Special Character e.g. @#%&amp;!?";
					}
					if(strlen($password)<$default_passwordLength) {
						$error.="<br>Password should be at least $default_passwordLength characters in length";
					}
				}
				//password must not match any of last $default_passwordsHistory historical passwords
				$query=0;
				$query=mysqlquery("select * from vl_users_history where userID='$id' order by created desc limit $default_passwordsHistory");
				if(mysqlnumrows($query)) {
					while($q=mysqlfetcharray($query)) {
						if($password && $q["history"] && (vlSimpleDecrypt($q["history"])==hash("sha256",$password))) {
							$error.="<br>Please select another Password. Your current choice of New Password is similar to a previously used Password on this very Account.";
						}
					}
				}
				//email
				if(!$email) 
					$error.="<br>No Email provided";

				//process
				if(!$error) {
					//log table change
					logTableChange("vl_users","names",$id,getDetailedTableInfo2("vl_users","id='$id'","names"),$names);
					logTableChange("vl_users","email",$id,getDetailedTableInfo2("vl_users","id='$id'","email"),$email);
					logTableChange("vl_users","phone",$id,getDetailedTableInfo2("vl_users","id='$id'","phone"),$phone);
					logTableChange("vl_users","role",$id,getDetailedTableInfo2("vl_users","id='$id'","role"),$role);
					logTableChange("vl_users","password",$id,"old password","new password");
					//update vl_users
					mysqlquery("update vl_users set 
									names='$names',
									email='$email',
									role='$role',
									".($password?"xp='".vlSimpleEncrypt($password)."',":"")."
									password='".vlRencrypt($password)."',
									phone='$phone' 
									where id='$id'");
					//log within history
					mysqlquery("insert into vl_users_history 
							(userID,history,created,createdby) 
							values 
							('$id','".vlSimpleEncrypt($password)."','$datetime','$_SESSION[VLADMIN]')");

					//file uploaded
					if($_FILES['userfile']['tmp_name']) {
						//has file been uploaded
						if(is_uploaded_file($_FILES['userfile']['tmp_name'])) {
							//filename
							$fileOriginalName=0;
							$fileOriginalName=$_FILES['userfile']['name'];
							$fileOriginalName=addslashes($fileOriginalName);
							//temp name
							$tmpName=0;
							$tmpName=$_FILES['userfile']['tmp_name'];
							//size
							$fileSize=0;
							$fileSize=$_FILES['userfile']['size'];
							//type
							$fileType=0;
							$fileType=$_FILES['userfile']['type'];
							//extension
							$extension=0;
							$extension=ext($fileOriginalName);
							//check to ensure this is xls
							if($extension!="jpg" && $extension!="jpeg" && $extension!="gif" && $extension!="png") {
								$error.="<br>Incorrect file extension '.$extension'. Only JPG, JPEG, PNG and GIF images are acceptable";
							}
							if($_FILES['userfile']['size']>$default_maxUploadSize) {
								$error.="<br>File size is greater than the accepted ".number_format((float)$default_maxUploadSize/1000)." KB. Please reduce the size of the file then attempt to upload it again.";
							}
							if(!$error) {
								$system_default_path.="images/signatures/";
								$server_url.="/images/signatures/";
								if(move_uploaded_file($_FILES['userfile']['tmp_name'],$system_default_path."signature.$id.$extension")) {
									//update vl_users
									mysqlquery("update vl_users set 
													signaturePATH='$system_default_path"."signature.$id.$extension',
													signatureURL='$server_url"."signature.$id.$extension' 
													where id='$id'");
								}
							}
						}
					}
						
					//email this user and inform them of their new account
					$from=0;
					$from="info@$home_domain";
											
					$fromName=0;
					$fromName="System";
																
					//subject
					$subject=0;
					$subject="Password Change";

//the message
$message=0;
$message="
Your Password on the \"VL\" System has been changed to the following credentials: 

Email: $email
Password: $password 

LOGIN INSTRUCTIONS:
1. Open Internet Explorer
2. Visit http://$default_systemIP/ from your Web Browser.
3. Login with your Email and Password

Kind regards, 
System Team";
							
					//mail the user
					sendPlainEmail($email,$from,$fromName,$subject,"",$message);

					//flag
					$modified=1;
				}
            break;
            case activate:
				//log table change
				logTableChange("vl_users","activate",$id,getDetailedTableInfo2("vl_users","id='$id'","activate"),"1");
				//update vl_users
				mysqlquery("update vl_users set active='1' where id='$id'");
				//flag
				$id=0;
				$activated=1;
            break;
            case deactivate:
				//log table change
				logTableChange("vl_users","activate",$id,getDetailedTableInfo2("vl_users","id='$id'","activate"),"0");
				//update vl_users
				mysqlquery("update vl_users set active='0' where id='$id'");
				//flag
				$id=0;
				$deactivated=1;
            break;
            case remove:
				if(isQuery("select * from vl_users where id='$id'")) {
					//remove
					if(is_file(getDetailedTableInfo2("vl_users","id='$id'","signaturePATH"))) {
						unlink(getDetailedTableInfo2("vl_users","id='$id'","signaturePATH"));
					}
					logDataRemoval("delete from vl_users where id='$id'");
					mysqlquery("delete from vl_users where id='$id'");
					logDataRemoval("delete from vl_users_history where userID='$id'");
					mysqlquery("delete from vl_users_history where userID='$id'");
					logDataRemoval("delete from vl_users_permissions where userID='$id'");
					mysqlquery("delete from vl_users_permissions where userID='$id'");
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
			if(!document.adminsForm.userfile.value) {
				if(!document.adminsForm.names.value) {
					alert("Please provide the Firstname");
					document.adminsForm.names.focus();
					return (false);
				}
				if(!document.adminsForm.lastname.value) {
					alert("Please provide the Lastname");
					document.adminsForm.lastname.focus();
					return (false);
				}
				if(!document.adminsForm.email.value) {
					alert("Please provide the Email");
					document.adminsForm.email.focus();
					return (false);
				}
			}
            return (true);
        }
        //-->
        </script>
        
        <form name="adminsForm" method="post" action="?act=users&nav=configuration" enctype="multipart/form-data" onsubmit="return checkForm(this)">
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
		<? } else if($activated) { ?>
            <tr>
              <td class="vl_success">Activated!</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
		<? } else if($deactivated) { ?>
            <tr>
              <td class="vl_success">Deactivated!</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
		<? } ?>
        <? if($task=="add") { ?>
            <tr>
              <td style="border-bottom:1px solid #cccccc; padding-bottom:10px">Add User</td>
            </tr>
        <? } else { ?>
            <tr>
              <td style="border-bottom:1px solid #cccccc; padding-bottom:10px">Manage Users</td>
            </tr>
        <? } ?>
            <tr> 
              <td style="padding:10px 0px 10px 0px"><table width="100%" border="0" class="vl">
                <tr>
                  <td width="30%">Names</td>
                  <td width="70%"><input type="text" name="names" id="names" class="search" size="25" value="<?=($id?getDetailedTableInfo2("vl_users","id='$id'","names"):"")?>"></td>
                </tr>
                <tr>
                  <td>Email</td>
                  <td><input type="text" name="email" id="email" class="search" size="25" value="<?=($id?getDetailedTableInfo2("vl_users","id='$id'","email"):"")?>"></td>
                </tr>
                <? //if($task!="add") { ?>
                <tr>
                  <td>Password</td>
                  <td><input type="password" name="password" id="password" class="search" size="25" value=""></td>
                </tr>
                <? //} ?>
                <tr>
                  <td>Phone</td>
                  <td><input type="text" name="phone" id="phone" class="search" size="25" value="<?=($id?getDetailedTableInfo2("vl_users","id='$id'","phone"):"+")?>"></td>
                </tr>
                <? if($id && getDetailedTableInfo2("vl_users","id='$id'","signatureURL")) { ?>
                <tr>
                  <td>&nbsp;</td>
                  <td><img src="<?=getDetailedTableInfo2("vl_users","id='$id'","signatureURL")?>" /></td>
                </tr>
                <? } ?>
                <tr>
                  <td>Signature</td>
                  <td><input name="userfile" type="file" class="search" size="28" /></td>
                </tr>
                <tr>
                  <td>Role</td>
                  <td><select name="role" id="role" class="search">
						<?
						if($id) {
							echo "<option value=\"".getDetailedTableInfo2("vl_users","id='$id'","role")."\">".getDetailedTableInfo2("vl_users","id='$id'","role")."</option>";
						} else {
							echo "<option value=\"\" selected=\"selected\">Select Role</option>";
						}
						?>
                        <option value="Lab Technologist">Lab Technologist</option>
                        <option value="Lab Manager">Lab Manager</option>
                        <option value="Other">Other</option>
                      </select></td>
                </tr>
               <? if($id && $option!="remove") { ?>
                <tr>
                  <td>Last Login</td>
                  <td><?=($id?getFormattedDate(getDetailedTableInfo2("vl_users","id='$id'","lastLogin")):"Unavailable")?></td>
                </tr>
               <? } ?>
              </table></td>
            </tr>
            <tr> 
              <td style="border-top:1px solid #cccccc; padding-top:10px"> 
              <input type="submit" name="button" id="button" value="   Save   " /> 
              <? if($task=="modify") { ?>
              <button type="button" id="button" name="button" value="button" onclick="document.location.href='?act=users&nav=configuration'">&nbsp;&nbsp;Cancel&nbsp;&nbsp;</button>
              <input name="id" type="hidden" id="id" value="<?=$id?>"> 
              <? } ?>
              <input name="act" type="hidden" id="act" value="users">
              <input name="option" type="hidden" id="option" value="<?=$task?>">
              </td>
            </tr>
          </table>
        </form>

		<?
        $query=0;
        $query=mysqlquery("select * from vl_users order by email");
		$num=0;
		$num=mysqlnumrows($query);
        if($num) {
        ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="vl_tdsub" width="160" style="padding-left:16px"><strong>Email</strong></td>
                          <td class="vl_tdsub" width="70"><strong>Active</strong></td>
                          <td class="vl_tdsub" width="190"><strong>Last Login</strong></td>
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
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="160"><?=$q["email"]?><br /><font class="vls_grey"><?=$q["names"]?></font></td>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="70"><?=($q["active"]?"<a href='?act=users&nav=configuration&option=deactivate&id=$q[id]'>Deactivate</a>":"<a href='?act=users&nav=configuration&option=activate&id=$q[id]'>Activate</a>")?></td>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="80"><?=(getFormattedDate($q["lastLogin"])?getFormattedDateLessDay($q["lastLogin"]):"Unavailable")?></td>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" width="100"><a href="?act=users&nav=configuration&modify=modify&id=<?=$q["id"]?>">edit</a> :: <a href="javascript:if(confirm('Are you sure?')) { document.location.href='?act=users&nav=configuration&option=remove&id=<?=$q["id"]?>'; }">delete</a></td>
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
                <td><strong>MANAGE USERS</strong></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td bgcolor="#d5d5d5" style="padding:10px">Create, Delete or Manage Users</td>
              </tr>
            </table></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>