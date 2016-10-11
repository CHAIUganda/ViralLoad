<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="border-bottom: 1px solid #e7e7e7; padding:0px 0px 15px 0px">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
      <tr>
        <td width="80%">
            <div><strong>DISPATCH RESULTS</strong></div>
            <div class="vls_grey" style="padding:5px 0px 0px 0px">Results</div>
        </td>
        <td width="20%" align="right"><img src="/images/icon.results.small.gif" border="0"></td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td style="padding:20px 30px 30px 0px">
	<?
	switch($sub) {
		case repeat:
			include "tpl.results.repeat.php";
		break;
		case dispatch:
			include "tpl.results.dispatch.php";
		break;
		case findedit:
			include "tpl.results.modify.php";
		break;
		default:
			switch($machineType) {
				case "rejected":
					include "tpl.results.rejected.php";
				break;
				default:
					include "tpl.results.default.php";
				break;
			}
		break;
	}
	?>
    </td>
  </tr>
</table>
