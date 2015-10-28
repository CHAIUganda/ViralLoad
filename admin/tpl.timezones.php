<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

switch($c_option) {
	case add:
		//ensure no duplicates
		$dupquery=0;
		$dupquery=mysqlquery("select * from vl_timezones where (country='$country' and timezone='$timezone')");
		if(!mysqlnumrows($dupquery)) {
			//insert into vl_variables
			mysqlquery("insert into vl_timezones 
					(country,timezone,variableDescription,created,createdby) 
					values 
					('$country','$timezone','$variableDescription','$datetime','$AN7ADMIN[email]')");
			
			go("?act=timezones&nav=configuration");
		} else {
			echo "<script>alert('The country or timezone you are entering is already in use. Please select another!'); window.history.go(-1);</script>";
		}
	break;
	case modify:
		//modify vl_variables
		mysqlquery("update vl_timezones set 
				country='$country', 
				timezone='$timezone', 
				modified='$datetime', 
				modifiedby='$AN7ADMIN[email]' where id='$id'");
		go("?act=timezones&nav=configuration");
	break;
	case remove:
		logDataRemoval("delete from vl_timezones where id='$id'");
		mysqlquery("delete from vl_timezones where id='$id'");
		go("?act=timezones&nav=configuration");
	break;
	default:
		if($modify) {
			$task="modify";
			//get the details for this id
			$query=0;
			$query=mysqlquery("select * from vl_timezones where id='$id'");
			$countryvalue=0;
			$timezonevalue=0;
			$createdvalue=0;
			$createdbyvalue=0;
			$modifiedvalue=0;
			$modifiedbyvalue=0;
			if(mysqlnumrows($query)) {
				$countryvalue=mysqlresult($query,0,'country');
				$timezonevalue=mysqlresult($query,0,'timezone');
				$createdvalue=getFormattedDate(mysqlresult($query,0,'created'))." at ".getFormattedTime(mysqlresult($query,0,'created'));
				$createdbyvalue=mysqlresult($query,0,'createdby');
				$modifiedvalue=getFormattedDate(mysqlresult($query,0,'modified'))." at ".getFormattedTime(mysqlresult($query,0,'modified'));
				$modifiedbyvalue=mysqlresult($query,0,'modifiedby');
			}
		} else {
			$task="add";
		}
		?>
<script Language="JavaScript" Type="text/javascript"><!--
function checkForm(adminForm) {
	if (!adminForm.timezone.value) {
		alert("Please create a timezone.");
		adminForm.timezone.focus();
		return (false);
	}
	if (!adminForm.country.value) {
		alert("Please create a country.");
		adminForm.country.focus();
		return (false);
	}
	return (true);
}
//--></script>

<form name="adminForm" method="post" action="../admin/?" onsubmit="return checkForm(this)">
  <table width="90%" border="0" class="vl">
    <tr> 
      <td colspan="2" bgcolor="#ececff"><strong><?=$task?> GMT country timezone:</strong></td>
    </tr>
    <tr> 
      <td width="26%" align="right"><strong>country:</strong></td>
      <td width="74%"> <input type="text" name="country" id="country" class="search" size="25" value="<?=$countryvalue?>" maxlength="250"> 
      </td>
    </tr>
    <tr> 
      <td width="26%" align="right"><strong>timezone:</strong></td>
      <td width="74%"> <input type="text" name="timezone" id="timezone" class="search" size="10" value="<?=$timezonevalue?>" maxlength="250"> 
      </td>
    </tr>
<? if($modify) { ?>
    <tr>
      <td colspan="2"><img src="images/horizontal_300.gif" width="300" height="1" vspace="3"></td>
    </tr>
    <tr>
      <td align="right"><strong>details:</strong></td>
      <td><p><strong>Created on:</strong>        <?=$createdvalue?><br>
          <strong>Created by:</strong>          <?=$createdbyvalue?><br>
          <strong>Last modified on:</strong>          <?=$modifiedvalue?><br>
          <strong>Last modified by:</strong>          <?=$modifiedbyvalue?>
        </p>
        </td>
    </tr>
    <tr>
      <td colspan="2"><img src="images/horizontal_300.gif" width="300" height="1" vspace="3"></td>
    </tr>
<? } ?>
    <tr> 
      <td width="26%">&nbsp;</td>
      <td width="74%"> 
	  <input name="Submit" type="image" value="save" src="images/buttons/save.gif"> 
	  <? if($task=="modify") { ?>
	  <a href="../admin/?act=timezones&nav=configuration"><img src="images/buttons/cancel.gif" border="0"></a> 
	  <input name="id" type="hidden" id="id" value="<?=$id?>"> 
	  <? } ?>
	  <input name="c_option" type="hidden" id="c_option" value="<?=$task?>"> 
        <input name="a" type="hidden" id="a" value="timezones"> 
		<input name="act" type="hidden" id="act" value="timezones">
      </td>
    </tr>
    <tr>
      <td colspan="2"><img src="/images/spacer.gif" width="3" height="3"></td>
    </tr>
    <tr>
      <td colspan="2"><img src="images/horizontal_300.gif" width="300" height="1" vspace="3"></td>
    </tr>
  </table>
</form>

<?
$query=0;
$query=mysqlquery("select * from vl_timezones order by country");
if(mysqlnumrows($query)) {
	?>
	<table width="95%" border="0" class="vl">
      <tr bgcolor="#ececff">
        <td width="60%"><strong>country</strong></td>
        <td width="30%"><strong>GMT timezone</strong></td>
        <td colspan="2" align="center"><strong>options</strong></td>
      </tr>
<?
$count=1;
$q=array();
while($q=mysqlfetcharray($query)) {
	if($count%2) {
		$color="#FFFFFF";
	} else {
		$color="#F1F1FF";
	}
?>
      <tr bgcolor="<?=$color?>">
        <td width="60%"><?=$q["country"]?></td>
        <td width="30%"><?=$q["timezone"]?></td>
        <td width="5%" align="center">[&nbsp;<a href="../admin/?act=timezones&nav=configuration&modify=modify&id=<?=$q["id"]?>">edit</a>&nbsp;]</td>
        <td width="5%" align="center">[&nbsp;<a href="javascript:if(confirm('Are you sure?')) { document.location.href='?act=timezones&nav=configuration&c_option=remove&id=<?=$q["id"]?>'; }">delete</a>&nbsp;]</td>
      </tr>
<? 
	$count++;
} 
?>
</table>
	<?
	}
	break;
}
?>