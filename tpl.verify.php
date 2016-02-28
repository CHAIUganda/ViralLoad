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
            <div><strong>VERIFY SAMPLES</strong></div>
            <div class="vls_grey" style="padding:5px 0px 0px 0px">Samples</div>
        </td>
        <td width="20%" align="right"><img src="/images/icon.samples.verify.small.gif" border="0"></td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td style="padding:20px 30px 30px 0px">
	<?
	switch($sub) {
		case approvereject:
			include "tpl.verify.approvereject.php";
		break;
		case findedit:
			include "tpl.verify.modify.php";
		break;
		case modify:
			include "tpl.verify.modify.php";
		break;
		case capture:
			include "tpl.verify.capture.php";
		break;
		default:
			include "tpl.verify.default.php";
		break;
	}
	?>
    </td>
  </tr>
</table>
