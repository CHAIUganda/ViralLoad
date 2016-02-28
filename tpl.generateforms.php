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
            <div><strong>GENERATE FORMS</strong></div>
            <div class="vls_grey" style="padding:5px 0px 0px 0px">Clinical Request Forms</div>
        </td>
        <td width="20%" align="right"><img src="/images/icon.form.small.gif" border="0"></td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td style="padding:20px 30px 30px 0px">
	<?
	switch($sub) {
		case dispatchnew:
			include "tpl.generateforms.dispatch.new.php";
		break;		
		case logdownload:
			//log the download
			logDownloadedVLClinicalForms($refNumber);
			//redirect to download
			go("/downloads.forms/Clinical.Request.Form.$refNumber.pdf");
		break;
		case dispatchadd:
			include "tpl.generateforms.dispatch.add.php";
		break;		
		case dispatch:
			include "tpl.generateforms.dispatch.php";
		break;		
		case download:
			include "tpl.generateforms.download.php";
		break;		
		case search:
			include "tpl.generateforms.search.php";
		break;
		case manage:
			include "tpl.generateforms.manage.php";
		break;
		case executesingular:
			include "tpl.preview.generateforms.executesingular.php";
		break;
		case execute:
			include "tpl.generateforms.execute.php";
		break;
		default:
			include "tpl.generateforms.default.php";
		break;
	}
	?>
    </td>
  </tr>
</table>
