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
            <div><strong>MANAGE SAMPLES</strong></div>
            <div class="vls_grey" style="padding:5px 0px 0px 0px">Samples</div>
        </td>
        <td width="20%" align="right"><img src="/images/icon.samples.small.gif" border="0"></td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td style="padding:20px 30px 30px 0px">
	<?
	switch($sub) {
		case modifypatients:
			include "tpl.samples.patients.modify.php";
		break;
		case managepatients:
			include "tpl.samples.patients.manage.php";
		break;
		case modify:
			include "tpl.samples.modify.php";
		break;
		case findedit:
			//validate
			if($encryptedSample) {
				$sample=validate(vlDecrypt($encryptedSample));
			} else {
				$sample=validate($sample);
			}
			if(getDetailedTableInfo2("vl_samples","formNumber='$sample' or formNumber='".($sample/1)."' or vlSampleID='$sample' or concat(lrCategory,lrEnvelopeNumber,'/',lrNumericID)='$sample' limit 1","id")) {
				include "tpl.samples.modify.php";
			} else {
				//redirect accordingly
				go("/samples/incorrect/");
			}
		break;
		case capture:
			include "tpl.samples.capture.php";
		break;
		default:
			include "tpl.samples.default.php";
		break;
	}
	?>
    </td>
  </tr>
</table>
