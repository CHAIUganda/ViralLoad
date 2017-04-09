<?
//register a globals variable for security
$GLOBALS['vlDC']=true;
include "conf.php";
//log url
logURL(getThisURL(),getPostVariables());

//load variables
$option=getValidatedVariable("option");
$successsws=getValidatedVariable("successsws");
$searchQuery=getValidatedVariable("searchQuery");
$searchFilter=getValidatedVariable("searchFilter");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Viral Load</title>
<link href="/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css">

<link href="/css/vl.css" rel="stylesheet" type="text/css">
<link href="/css/vl2.css" rel="stylesheet" type="text/css">
<link href="/css/jsdialog.css" rel="stylesheet" media="screen" type="text/css">
<link href="/css/dhtmlxcombo.css" rel="stylesheet" type="text/css">
<link href="/css/datepicker.jquery.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/css/jquery-ui.css">

<link   href="/css/select2.min.css" rel="stylesheet" />


<? $vlDC->printJavascript(); ?>
<? if($_SESSION["VLEMAIL"]) { ?>
	<script src="/js/vl.custom.js"></script>
    <script src="/js/tooltip.js"></script>
    <script src="/js/checkUncheck.js"></script>
    <script src="/js/loadField.js"></script>
    <script src="/js/limit.js"></script>
    <script src="/js/jsdialog-ajax.js"></script>
    <script src="/js/jsdialog-message.js"></script>
    <script src="/js/jsdialog-ajax-dynamic-content.js"></script>
    <script src="/js/jsdialog-popup.js"></script>

	<script src="/js/validation.js"></script>
    <script src="/js/dhtmlxcombo_extra.js"></script>
    <script src="/js/dhtmlxcommon.js"></script>
    <script src="/js/dhtmlxcombo.js"></script>

	<script src="/js/datepicker.jquery.min.js"></script>
    <script src="/js/datepicker.jquery.plugin.js"></script>
    <script src="/js/datepicker.jquery.js"></script>

    <script src="/js/jquery-2.1.3.min.js"></script>
    <script src="/js/jquery-ui.js"></script>
    <script src="/js/select2.min.js" type="text/javascript"></script>
    
    <script>
	//image path
	window.dhx_globalImgPath="/images/";

    var mins;
    var secs;
    function cd() {
        mins = 1 * m("<?=$default_systemIdleWait?>");
        secs = 0 + s(":01");
        redo();
    }
    
    function cdreset() {
        mins = 1 * m("<?=$default_systemIdleWait?>");
        secs = 0 + s(":01");
    }
    </script>
    <script src="/js/countdown.js"></script>
    <script>
        function iDisplayMessage(phpFile) {
           displayMessage(phpFile,'jsdialog_vl');
           return false;
        }
        //countdown
        window.onload = cd;
    </script>
    <style type="text/css">
    <!--
    body {
        background-color: #F3F4FF;
        margin: 0px;
    }
    -->
    </style>
    </head>
    <? 
    if($option=="worksheets" && $print && $worksheetID) {
		//print the worksheet
        echo "<body onLoad=\"window.open('/worksheets/print.detail/$worksheetID/', 'printWorksheet', 'width=1000, height=600, toolbar=no, location=no, directories=no, resizable=no, status=yes, scrollbars=yes'), cd()\" onMouseMove=\"cdreset()\" onKeyPress=\"cdreset()\">";
    } else {
        echo "<body onMouseMove=\"cdreset()\" onKeyPress=\"cdreset()\">";
    }
    ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="vl">
      <tr>
        <td bgcolor="#333333">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="5%" style="padding:0px 0px 0px 115px"><img src="/images/top.logo.ministry.of.health.gif" /></td>
                    <td width="95%" align="right" class="vls_white" style="padding:0px 115px 0px 0px">Welcome <?=$_SESSION["VLEMAIL"]?> :: <a class="vls_white" href="/dashboard/">Home</a> :: <a class="vls_white" href="/help/">Help</a> :: <a class="vls_white" href="/logout/">Logout</a> Auto Logout in<span id="countdownID"></span> mins:secs</td>
                </tr>
            </table>
        </td>
      </tr>
      <tr>
      	<td background="/images/bg.header.jpg">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="1%" style="padding:0px 0px 0px 115px"><img src="/images/uganda.emblem.jpg" /></td>
                    <td width="4%"><img src="/images/viral.load.jpg" /></td>
                    <td width="90%" align="right">
					<script Language="JavaScript" Type="text/javascript">
                    <!--
                    function checkSearchForm(trackForm) {
                        //check for missing information
                        if(!document.trackForm.searchQuery.value) {
                            alert('Please input a Search Term before proceeding');
                            document.trackForm.searchQuery.focus();
                            return (false);
                        }
                        if(!document.trackForm.searchFilter.value) {
                            alert('Please select the Search Filter');
                            document.trackForm.searchFilter.focus();
                            return (false);
                        }
                        return (true);
                    }
                    //-->
                    </script>
                      <form name="trackForm" method="post" action="/search/" onsubmit="return checkSearchForm(this)">
                      <table width="40%" border="0" cellspacing="0" cellpadding="0" class="vls_white">
                        <tr>
                          <td width="5%" style="padding:0px 10px 0px 0px" align="right">Search&nbsp;for:</td>
                          <td width="95%"><input type="text" name="searchQuery" id="searchQuery" value="<?=$searchQuery?>" class="search_pre" size="30" maxlength="100" onfocus="if(value=='') {value=''}" onblur="if(value=='') {value=''}" /></td>
                        </tr>
                        <tr>
                          <td style="padding:5px 10px 0px 0px" align="right">Within:</td>
                          <td style="padding:5px 0px 0px 0px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="5%"><div style="padding:0px; width:140px; overflow:hidden;">
                          <select name="searchFilter" id="searchFilter" 
                                style="width:140px; z-index:+1;"
                                onactivate="this.style.width='auto';"
                                onchange="this.blur();"
                                onblur="this.style.width='140px';" 
                                class="search">
                                <? 
								if($searchFilter) {
									echo "<option value=\"$searchFilter\" selected>$searchFilter</option>";
								} else {
									echo "<option value=\"\" selected>Select Search Filter</option>";
								}
								?>
                                <? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='samples' limit 1","id")) { ?>
                                <option value="Samples">Samples</option>
                                <option value="Patients">Patients</option>
								<? } ?>
                                <? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='verifySamples' limit 1","id")) { ?>
                                <option value="VerifySamples">Verify Samples</option>
								<? } ?>
                                <? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='unVerifySamples' limit 1","id")) { ?>
                                <option value="unVerifySamples">Reverse Approval of Samples</option>
								<? } ?>
                                <? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='worksheets' limit 1","id")) { ?>
                                <option value="Worksheets">Worksheets</option>
								<? } ?>
                                <? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='generateForms' limit 1","id")) { ?>
                                <option value="GeneratedForms">Generated Forms</option>
								<? } ?>
                                <? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='results' limit 1","id")) { ?>
                                <option value="Results">Results</option>
						        <? } ?>
                          </select>
                        </div></td>
                                <td width="95%" style="padding:0px 0px 0px 5px"><input type="submit" name="Submit" class="buttonred" value=" Find " /></td>
                              </tr>
                            </table>
                        </td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td style="padding:3px 0px 0px 0px"><a href="/search.advanced/" class="vls_white">Advanced Search</a></td>
                        </tr>
                      </table>
                      </form>
                    </td>
                    <td width="5%" style="padding:0px 115px 0px 0px"><img src="/images/hiv.logo.jpg" /></td>
                </tr>
            </table>
        </td>
      </tr>
      <tr>
        <td bgcolor="#cccccc" style="padding:10px 10px 10px 130px" class="vll_grey">
        <a href="/dashboard/" class="vll_grey">Home</a> 
         <? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='samples' limit 1","id")) { ?>
        :: <a href="/samples/capture/" class="vll_grey">New Sample</a> 
        <? } ?>
        <? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='samples' limit 1","id")) { ?>
        :: <a href="/samples/" class="vll_grey">Samples</a> 
        <? } ?>
      	<? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='verifySamples' limit 1","id")) { ?>
        :: <a href="/verify/" class="vll_grey">Verify Samples</a> 
        <? } ?>
      	<? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='worksheets' limit 1","id")) { ?>
        :: <a href="/worksheets/" class="vll_grey">Worksheets</a> 
        <? } ?>
      	<? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='generateForms' limit 1","id")) { ?>
        :: <a href="/generateforms/" class="vll_grey">Generate&nbsp;Forms</a> 
        <? } ?>
        <? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='intervene' limit 1","id")) { ?>
        :: <a href="/intervene/" class="vll_grey">Intervention for Data QC</a> 
        <? } ?>
      	<? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='results' limit 1","id")) { ?>
        :: <a href="/results/" class="vll_grey">Results</a> 
        <? } ?>
        <? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='results' limit 1","id")) { ?>
        :: <a href="/results_new/" class="vll_grey">Results (Browser Printing)</a> 
        <? } ?>
      	<? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='reports' limit 1","id") || 
				getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='reportsQC' limit 1","id")) { ?>
        :: <a href="/reports/" class="vll_grey">Reports</a></td>
        <? } ?>
      </tr>
      <tr>
        <td align="center">
        		<table width="90%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td valign="top">
                        <!-- Begin Center Table -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="100%" bgcolor="#FFFFFF" style="padding:20px 30px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td>
                        <?
                        switch($option) {
                            case search:
								//log the query
								logSearchQuery($searchQuery,$searchFilter);
								//process the query
								if($advancedSearch) {
									$envelopeNumberFrom=validate($envelopeNumberFrom);
									$envelopeNumberTo=validate($envelopeNumberTo);
									switch($searchFilter) {
										case "Samples":
											go("/samples/find.and.edit/search/".vlEncrypt($envelopeNumberFrom)."/".vlEncrypt($envelopeNumberTo)."/");
										break;
										case "Patients":
											go("/samples/manage.patients/search/".vlEncrypt($envelopeNumberFrom)."/".vlEncrypt($envelopeNumberTo)."/");
										break;
										case "VerifySamples":
											go("/verify/search/".vlEncrypt($envelopeNumberFrom)."/".vlEncrypt($envelopeNumberTo)."/");
										break;
										case "unVerifySamples":
											go("/verify/search.unverified/".vlEncrypt($envelopeNumberFrom)."/".vlEncrypt($envelopeNumberTo)."/");
										break;
										case "Worksheets":
											go("/worksheets/manage/search/".vlEncrypt($envelopeNumberFrom)."/".vlEncrypt($envelopeNumberTo)."/");
										break;
										case "GeneratedForms":
											go("/generateforms/search/".vlEncrypt($envelopeNumberFrom)."/".vlEncrypt($envelopeNumberTo)."/1/");
										break;
										case "Results":
											go("/results/search/".vlEncrypt($envelopeNumberFrom)."/".vlEncrypt($envelopeNumberTo)."/");
										break;
										case advanced:
											include "tpl.search.advanced.php";
										break;
									}
								} else {
									switch($searchFilter) {
										case "Samples":
											go("/samples/find.and.edit/search/".vlEncrypt($searchQuery)."/");
										break;
										case "Patients":
											go("/samples/manage.patients/search/".vlEncrypt($searchQuery)."/");
										break;
										case "VerifySamples":
											go("/verify/search/".vlEncrypt($searchQuery)."/");
										break;
										case "unVerifySamples":
											go("/verify/search.unverified/".vlEncrypt($searchQuery)."/");
										break;
										case "Worksheets":
											go("/worksheets/manage/search/".vlEncrypt($searchQuery)."/");
										break;
										case "GeneratedForms":
											go("/generateforms/search/".vlEncrypt($searchQuery)."/1/");
										break;
										case "Results":
											go("/results/search/".vlEncrypt($searchQuery)."/");
										break;
										case advanced:
											include "tpl.search.advanced.php";
										break;
									}
								}
                            break;
                            case generateforms:
                                include "tpl.generateforms.php";
                            break;
                            case worksheets:
                                include "tpl.worksheets.php";
                            break;
							case reports:
                                include "tpl.reports.php";
                            break;
                            case 'results_new':
                                include "tpl.results_new.php";
                                break;
							case results:
                                include "tpl.results.php";
                            break;
                            case verify:
                                include "tpl.verify.php";
                            break;
                            case samples:
                                include "tpl.samples.php";
                            break;
                            case envelopes:
                                include "tpl.envelopes.php";
                            break;
                            case reports:
                                include "tpl.reports.php";
                            break;
                            case help:
                                include "tpl.help.php";
                            break;
                            case support:
                                include "tpl.support.php";
                            break;
                            case reports:
                                include "tpl.reports.php";
                            break;

                            case 'facilityID':
                                echo "1000000000000000000000000";
                            break;
                            case 'intervene':
                                include "tpl.intervene.php";
                            break;
                            case logout:
                                //kill session
                                session_start();
                                //session_unregister("VLEMAIL");
                                session_destroy();
                                go("/bye/");
                            break;
                            case dashboard:
                            default:
                                include "tpl.dashboard.php";
                            break;
                        }
                        ?>
                                    </td>
                                  </tr>
                                </table></td>
                                <td background="/images/bg.right.gif"><img src="/images/spacer.gif" width="11" height="11"></td>
                              </tr>
                            </table></td>
                          </tr>
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td background="/images/bg.bottom.gif" width="100%"><img src="/images/spacer.gif" width="11" height="11"></td>
                                <td background="/images/bg.bottom.right.gif"><img src="/images/spacer.gif" width="11" height="11"></td>
                              </tr>
                            </table></td>
                          </tr>
                        </table>
                        <!-- End Center Table -->
                        </td>
                      </tr>
					<!-- Begin Footer -->
                      <tr>
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="50%" class="vls" style="padding:10px 0px 0px 0px"><strong>Viral Load</strong></td>
                            <td width="50%" class="vl" align="right" style="padding:10px 7px 0px 0px">Ministry of Health, The Republic of Uganda &copy; Copyright <?=getCurrentYear()?></td>
                          </tr>
                        </table>
                        </td>
                      </tr>
					<!-- End Footer -->
                    </table>
</td>
      </tr>
    </table>
    </body>
<? } else { ?>
    <style type="text/css">
    <!--
    body {
        background-color: #f5f5f5;
        margin: 7px 7px 0px 7px;
        background-repeat: no-repeat;
        background-position: center left;
        background-attachment:fixed;
    }
    -->
    </style>
    </head>
    <body>
	<?
	switch($option) {
		default:
			include "tpl.home.prelogin.php";
		break;
	}
	?>
    </body>
<? } ?>
</html>
<? 
//close connection
mysqli_close($db); 
?>
