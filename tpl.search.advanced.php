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
            <div><strong>ADVANCED SEARCH</strong></div>
            <div class="vls_grey" style="padding:5px 0px 0px 0px">Advanced data location</div>
        </td>
        <td width="20%" align="right"><img src="/images/icon.search.small.gif" border="0"></td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td style="padding:20px 30px 30px 0px">
		<script Language="JavaScript" Type="text/javascript">
        <!--
        function validate(advancedSearchForm) {
            //check for missing information
            if(!document.advancedSearchForm.envelopeNumberFrom.value) {
                alert('Missing Mandatory Field: From Envelope Number');
                document.advancedSearchForm.envelopeNumberFrom.focus();
                return (false);
            }
            if(!document.advancedSearchForm.envelopeNumberTo.value) {
                alert('Missing Mandatory Field: To Envelope Number');
                document.advancedSearchForm.envelopeNumberTo.focus();
                return (false);
            }
            if(!document.advancedSearchForm.searchFilter.value) {
                alert('Missing Mandatory Field: Search Within');
                document.advancedSearchForm.searchFilter.focus();
                return (false);
            }
            return (true);
        }
        //-->
        </script>
        <form name="advancedSearchForm" method="post" action="/search/" onsubmit="return validate(this)">
        <table width="100%" border="0" class="vl">
          <? if($success) { ?>
            <tr>
                <td class="vl_success">Data Captured Successfully!</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
			<? } elseif($error) { ?>
            <tr>
                <td class="vl_error"><?=$error?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <? } ?>
            <tr>
              <td class="toplinks" style="padding:0px 0px 10px 0px"><a class="toplinks" href="/dashboard/">HOME</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/search.advanced/">ADVANCED SEARCH</a></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                  <fieldset style="width: 85%">
                    <legend><strong>SEARCH CREDENTIALS</strong></legend>
                      <table width="100%" border="0" class="vl">
                        <tr>
                          <td width="20%">Envelope&nbsp;Number&nbsp;(from)&nbsp;<font class="vl_red">*</font></td>
                          <td width="80%"><input type="text" name="envelopeNumberFrom" id="envelopeNumberFrom" value="" class="search_pre" size="15" maxlength="100" /></td>
                        </tr>
                        <tr>
                          <td>Envelope&nbsp;Number&nbsp;(to)&nbsp;<font class="vl_red">*</font></td>
                          <td><input type="text" name="envelopeNumberTo" id="envelopeNumberTo" value="" class="search_pre" size="15" maxlength="100" /></td>
                        </tr>
                        <tr>
                          <td>Within&nbsp;<font class="vl_red">*</font></td>
                          <td><div style="padding:0px; width:140px; overflow:hidden;">
                          <select name="searchFilter" id="searchFilter" 
                                style="width:140px; z-index:+1;"
                                onactivate="this.style.width='auto';"
                                onchange="this.blur();"
                                onblur="this.style.width='140px';" 
                                class="search">
                                <? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='samples' limit 1","id")) { ?>
                                <!--
                                <option value="Samples">Samples</option>
                                <option value="Patients">Patients</option>
                                -->
								<? } ?>
                                <? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='verifySamples' limit 1","id")) { ?>
                                <option value="VerifySamples">Verify Samples</option>
								<? } ?>
                                <? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='unVerifySamples' limit 1","id")) { ?>
                                <!--<option value="unVerifySamples">Reverse Approval of Samples</option>-->
								<? } ?>
                                <? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='worksheets' limit 1","id")) { ?>
                                <!--<option value="Worksheets">Worksheets</option>-->
								<? } ?>
                                <? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='generateForms' limit 1","id")) { ?>
                                <!--<option value="GeneratedForms">Generated Forms</option>-->
								<? } ?>
                                <? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='results' limit 1","id")) { ?>
                                <!--<option value="Results">Results</option>-->
						        <? } ?>
                          </select>
                        </div></td>
                        </tr>
                        <!--
                        <tr>
                          <td>Date&nbsp;Dispatched&nbsp;<font class="vl_red">*</font></td>
                          <td>
                            <table width="10%" border="0" cellspacing="0" cellpadding="0" class="vl">
                              <tr>
                                <td><select name="dispatchedDateDay" id="dispatchedDateDay" class="search">
                                  <?
                                    echo "<option value=\"".getFormattedDateDay($oldDispatchedDate?$oldDispatchedDate:$datetime)."\" selected=\"selected\">".getFormattedDateDay($oldDispatchedDate?$oldDispatchedDate:$datetime)."</option>";
                                    for($j=1;$j<=31;$j++) {
                                        echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
                                    }
                                    ?>
                                  </select></td>
                                <td style="padding:0px 0px 0px 5px"><select name="dispatchedDateMonth" id="dispatchedDateMonth" class="search">
                                  <? echo "<option value=\"".getFormattedDateMonth($oldDispatchedDate?$oldDispatchedDate:$datetime)."\" selected=\"selected\">".getFormattedDateMonthname($oldDispatchedDate?$oldDispatchedDate:$datetime)."</option>"; ?>
                                  <option value="01">Jan</option>
                                  <option value="02">Feb</option>
                                  <option value="03">Mar</option>
                                  <option value="04">Apr</option>
                                  <option value="05">May</option>
                                  <option value="06">Jun</option>
                                  <option value="07">Jul</option>
                                  <option value="08">Aug</option>
                                  <option value="09">Sept</option>
                                  <option value="10">Oct</option>
                                  <option value="11">Nov</option>
                                  <option value="12">Dec</option>
                                  </select></td>
                                <td style="padding:0px 0px 0px 5px"><select name="dispatchedDateYear" id="dispatchedDateYear" class="search">
                                  <?
                                        for($j=getFormattedDateYear($oldDispatchedDate?$oldDispatchedDate:$datetime);$j>=(getCurrentYear()-10);$j--) {
                                            echo "<option value=\"$j\">$j</option>";
                                        }
                                        ?>
                                  </select></td>
                                </tr>
                            </table></td>
                        </tr>
                        -->
                      </table>
                    </fieldset>
                </td>
            </tr>
            <tr>
              <td style="padding:10px 0px 0px 0px">
              <input type="hidden" name="advancedSearch" id="advancedSearch" value="1" />
              <input type="submit" name="searchData" id="searchData" class="button" value="  Search  " />
              </td>
            </tr>
            <tr>
	            <td style="padding:20px 0px 0px 0px"><a href="/dashboard/">Return to Dashboard</a></td>
            </tr>
          </table>
		</form>
    </td>
  </tr>
</table>
