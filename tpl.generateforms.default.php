<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

$refNumber=validate($refNumber);
?>
<table width="100%" border="0" class="vl">
          <? if($success) { ?>
            <tr>
                <td class="vl_success">Data Captured Successfully!</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
          <? } elseif($modified) { ?>
            <tr>
                <td class="vl_success">Data Changes Captured Successfully!</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
          <? } elseif($reviewed) { ?>
            <tr>
                <td class="vl_success">Review Notes Captured Successfully!</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
          <? } elseif($generated) { ?>
            <tr>
                <td class="vl_success">
                <div>Forms Generated Successfully with <strong>Reference # <?=$refNumber?></strong>!</div>
                <div class="vls_grey"><a href="/generateforms/download/log/<?=$refNumber?>/" class="vls_grey" target="_blank">Click Here to Download</a></div>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <? } ?>
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="5%" align="center" style="padding:0px 6px"><img src="/images/icon.form.gif" border="0" alt="Generate Forms" /></td>
                          <td width="95%" style="padding:0px 0px 0px 30px"><table width="80%" cellpadding="0" cellspacing="0" border="0" class="vl">
                            <tr>
                              <td><strong>Clinical Request Forms</strong></td>
                            </tr>
                            <tr>
                              <td class="vls_grey">Select from either of the options below.</td>
                            </tr>
                            <!--
                            <tr>
    	                        <td style="padding:10px 0px 10px 0px; border-bottom: 1px dashed #dfe6e6">
								<script Language="JavaScript" Type="text/javascript">
                                <!--
                                function checkForm(generateForm) {
                                    //check for missing information
                                    if(!document.generateForm.facilityID.value) {
                                        alert('Please select a Facility for which to generate');
                                        document.generateForm.facilityID.focus();
                                        return (false);
                                    }

                                    if(!document.generateForm.numberOfForms.value) {
                                        alert('Please the number of Forms to generate');
                                        document.generateForm.numberOfForms.focus();
                                        return (false);
                                    }
                                    return (true);
                                }
                                //
                                </script>
                                  <form name="generateForm" method="post" action="/generateforms/execute/" onsubmit="return checkForm(this)">
                                  <table width="40%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td style="padding:5px 0px">
                                      <select name="numberOfForms" id="numberOfForms" class="search">
                                            <option value="" selected="selected">Select Number of Forms to Generate</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="125">125</option>
                                            </select>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td><input type="submit" name="Submit" class="button" value="     Generate Forms     " /></td>
                                    </tr>
                                  </table>
                                  </form>
                                </td>
                            </tr>
                            <tr>
    	                        <td style="padding:10px 0px 0px 0px"><a href="/generateforms/download/">Download Historical Forms</a> <font class="vls_grey">(<?=number_format((float)getDetailedTableInfo3("vl_forms_clinicalrequest","id!=''","count(id)","num"))?>)</font></td>
                            </tr>
                            <tr>
    	                        <td style="padding:10px 0px 0px 0px"><a href="/generateforms/email/">Email Downloadable Forms</a></td>
                            </tr>
                            -->
                            <tr>
    	                        <td style="padding:10px 0px 0px 0px"><a href="/generateforms/dispatch/">Update Form Dispatch Status</a></td>
                            </tr>
                            <tr>
    	                        <td style="padding:10px 0px 0px 0px"><a href="/generateforms/dispatch/new/">Add Form Batch/Dispatch Status</a></td>
                            </tr>
                          </table></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding:30px 0px 0px 0px">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="5%" align="center"><img src="/images/icon.track.gif" border="0" alt="Search" /></td>
                          <td width="95%" style="padding:0px 0px 0px 30px"><table width="100%" cellpadding="0" cellspacing="0" border="0" class="vl">
                            <tr>
                              <td><strong>Search Forms</strong></td>
                            </tr>
                            <tr>
                              <td class="vls_grey">Find an Form by Form or Reference #</td>
                            </tr>
                            <tr>
                              <td style="padding:5px 0px 0px 0px">
                            <script Language="JavaScript" Type="text/javascript">
                            <!--
                            function checkForm(trackForm) {
                                //check for missing information
                                if(!document.trackForm.searchQuery.value) {
                                    alert('Please input a Search Term before proceeding');
                                    document.trackForm.searchQuery.focus();
                                    return (false);
                                }
                                return (true);
                            }
                            //-->
                            </script>
                              <form name="trackForm" method="post" action="/generateforms/search/" onsubmit="return checkForm(this)">
                              <table width="40%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td style="padding:5px 0px 10px 0px"><input type="text" name="searchQuery" id="searchQuery" value="" class="search_pre" size="40" maxlength="100" onfocus="if(value=='') {value=''}" onblur="if(value=='') {value=''}" /></td>
                                </tr>
                                <tr>
                                  <td><input type="submit" name="Submit" class="button" value="     Find Form     " /></td>
                                </tr>
                              </table>
                              </form>
                              </td>
                            </tr>
                          </table></td>
                        </tr>
                    </table>
                </td>
            </tr>
          </table>