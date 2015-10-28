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
            <div><strong>MANAGE WORKSHEETS</strong></div>
            <div class="vls_grey" style="padding:5px 0px 0px 0px">Worksheets</div>
        </td>
        <td width="20%" align="right"><img src="/images/icon.worksheets.small.gif" border="0"></td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td style="padding:20px 30px 30px 0px">
	<?
	switch($sub) {
		case modify:
			if(getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$modify' and machineType='roche' limit 1","id")) {
				include "tpl.worksheets.modify.roche.php";
			} elseif(getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$modify' and machineType='abbott' limit 1","id")) {
				include "tpl.worksheets.modify.abbott.php";
			}
		break;
		case manage:
			include "tpl.worksheets.manage.php";
		break;
		case capture2:
			include "tpl.worksheets.capture.2.php";
		break;
		case capture1roche:
			include "tpl.worksheets.capture.1.roche.php";
		break;
		case capture1abbott:
			include "tpl.worksheets.capture.1.abbott.php";
		break;
		case uploadresults:
			include "tpl.worksheets.uploadresults.php";
		break;
		case viewdetail:
			include "tpl.worksheets.viewdetail.php";
		break;
		default:
			include "tpl.worksheets.default.php";
		break;
	}
	?>
    </td>
  </tr>
</table>
