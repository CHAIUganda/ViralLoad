<? 
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";
?>
<link href="css/vl.css" rel="stylesheet" type="text/css">
<link href="css/jsdialog.css" rel="stylesheet" media="screen" type="text/css">
<link href="css/dhtmlxcombo.css" rel="stylesheet" type="text/css">
<title>Trailanalytics Administration</title>
<style type="text/css">
<!--
body {
	margin-top: 0px;
	margin-bottom: 0px;
}
-->
</style>
<script src="js/calendarPopup.js"></script>
<script src="js/checkUncheck.js"></script>
<script src="js/loadField.js"></script>
<script src="js/limit.js"></script>
<script src="js/jsdialog-ajax.js"></script>
<script src="js/jsdialog-message.js"></script>
<script src="js/jsdialog-ajax-dynamic-content.js"></script>
<script src="js/jsdialog-popup.js"></script>
<script src="js/dhtmlxcombo_extra.js"></script>
<script src="js/dhtmlxcommon.js"></script>
<script src="js/dhtmlxcombo.js"></script>
<script Language="JavaScript" Type="text/javascript">
<!--
	//image path
	window.dhx_globalImgPath="images/";

	function navigate(navigation,displayID) {
		var navigationLink;
		switch(navigation) {
			default:
			case 'HOME':
				navigationLink = '<? include "nav.home.php"; ?>';
				document.getElementById(displayID).innerHTML=navigationLink;
			break;
			case 'DATAMANAGEMENT':
				navigationLink = '<? include "nav.datamanagement.php"; ?>';
				document.getElementById(displayID).innerHTML=navigationLink;
			break;
			case 'CONFIGURATION':
				navigationLink = '<? include "nav.configuration.php"; ?>';
				document.getElementById(displayID).innerHTML=navigationLink;
			break;
			case 'STATISTICS':
				navigationLink = '<? include "nav.statistics.php"; ?>';
				document.getElementById(displayID).innerHTML=navigationLink;
			break;
		}
	}

	function iDisplayMessage(phpFile) {
	   displayMessage(phpFile,'jsdialog_vl');
	   return false;
	}
//--></script>
<body <? if($showeditor) { ?>onLoad="HTMLArea.replaceAll();"<? } ?>>
<p>&nbsp;</p>
<table width="770" border="0" align="center" cellpadding="0" cellspacing="0" class="vl_border">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><img src="/admin/images/logo.jpg" width="124" height="80"></td>
        <td width="100%" background="/admin/images/logo.bg.jpg" valign="bottom" style="padding:5px"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl_white">
          <tr>
            <td width="80%">&nbsp;</td>
            <td style="padding:0px 1px 1px 0px"><img src="/admin/images/bottom_arrow_white.gif"></td>
            <td style="padding:3px 5px 3px 3px" onMouseOver="navigate('HOME','navigationID')"><a href="?" class="vl_white_link">HOME</a></td>
            <td style="padding:0px 1px 1px 0px"><img src="/admin/images/bottom_arrow_white.gif"></td>
            <td style="padding:3px 5px 3px 3px" onMouseOver="navigate('DATAMANAGEMENT','navigationID')"><a href="?" class="vl_white_link">DATA&nbsp;MANAGEMENT</a></td>
            <td style="padding:0px 1px 1px 0px"><img src="/admin/images/bottom_arrow_white.gif"></td>
            <td style="padding:3px 5px 3px 3px" onMouseOver="navigate('CONFIGURATION','navigationID')"><a href="?" class="vl_white_link">CONFIGURATION</a></td>
            <td style="padding:0px 1px 1px 0px"><img src="/admin/images/bottom_arrow_white.gif"></td>
            <td style="padding:3px 5px 3px 3px" onMouseOver="navigate('STATISTICS','navigationID')"><a href="?" class="vl_white_link">STATISTICS</a></td>
            <td style="padding:0px 1px 1px 0px"><img src="/admin/images/bottom_arrow_white.gif"></td>
            <td style="padding:3px 5px 3px 3px"><a href="?act=logout" class="vl_white_link">LOGOUT</a></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td bgcolor="#e9eaea" height="25"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="5%">&nbsp;</td>
        <td width="95%" style="padding:3px 10px 3px 0px" id="navigationID" align="right" class="vl_grey">
		<?
		switch($nav) {
			default:
			case "home":
				include "nav.home.php";
			break;
			case "datamanagement":
				include "nav.datamanagement.php";
			break;
			case "configuration":
				include "nav.configuration.php";
			break;
			case "statistics":
				include "nav.statistics.php";
			break;
		}
		?>
        </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td style="border-bottom:1px solid #cecfd1"><img src="/admin/images/spacer.gif" width="1" height="1"></td>
  </tr>
  <tr>
    <td style="padding:15px" class="vl" height="300" valign="top"><?
switch($act) {
	//START APPENDICES
	case aregimen:
		include "tpl.appendix.regimen.php"; 
	break;
	case aarvadherence:
		include "tpl.appendix.arvadherence.php"; 
	break;
	case afailurereason:
		include "tpl.appendix.failurereason.php"; 
	break;
	case asampletype:
		include "tpl.appendix.sampletype.php"; 
	break;
	case atbtreatmentphase:
		include "tpl.appendix.tbtreatmentphase.php"; 
	break;
	case atreatmentinitiation:
		include "tpl.appendix.treatmentinitiation.php"; 
	break;
	case atreatmentstatus:
		include "tpl.appendix.treatmentstatus.php"; 
	break;
	case aviralloadtesting:
		include "tpl.appendix.viralloadtesting.php"; 
	break;
	case asamplerejectionreasons:
		include "tpl.appendix.samplerejectionreasons.php"; 
	break;
	//END APPENDICES
	
	//START CONFIGURATION
	case linkPatient:
		include "tpl.samples.linkpatient.php"; 
	break;
	case addPatient:
		include "tpl.samples.addpatient.php"; 
	break;
	case repeatSamples:
		include "tpl.samples.repeat.php"; 
	break;
	case markSamplesAsFailed:
		include "tpl.worksheets.mark.samples.as.failed.php"; 
	break;
	case worksheetSamples:
		include "tpl.worksheets.samples.php"; 
	break;
	case hubsfacilities:
		include "tpl.hubs.facilities.php"; 
	break;
	case hubs:
		include "tpl.hubs.php"; 
	break;
	case facilities:
		include "tpl.facilities.php"; 
	break;
	case districts:
		include "tpl.districts.php"; 
	break;
	case regions:
		include "tpl.regions.php"; 
	break;
	case mails:
		include "tpl.statistics.mails.php"; 
	break;
	case timezones:
		include "tpl.timezones.php"; 
	break;
	case statistics:
		include "tpl.statistics.php"; 
	break;
	case statisticschanges:
		include "tpl.statistics.changes.php"; 
	break;
	case statisticsremovals:
		include "tpl.statistics.removals.php"; 
	break;
	case administrators:
		include "tpl.administrators.php"; 
	break;
	//END CONFIGURATION
	
	//USER MANAGEMENT Permissions
	case permissions:
		include "tpl.permissions.php"; 
	break;
	//USER MANAGEMENT
	case users:
		include "tpl.users.php"; 
	break;
	//USER MANAGEMENT Admins
	case admins:
		include "tpl.admins.php"; 
	break;
	//INDEX PAGE introduction
	case introduction:
		include "tpl.introduction.php"; 
	break;
	case login:
		include "inc.login.php"; 
	break;
	case logout:
		//make log entry
		mysqlquery("update vl_admins set login='$datetime' where email='$_SESSION[VLADMIN]'");
		//session_unregister('VLADMIN');
		session_destroy();
		go("?");
	break;
	case today:
		include "inc.today.php";
	break;
	default:
		include "inc.check.php";
	break;  
}
?></td>
  </tr>
  <tr>
    <td class="vl" style="padding:15px" align="center">VIRAL LOAD ADMIN &copy; Copyright <?=getCurrentYear()?></td>
  </tr>
</table></td>
  </tr>
</table>