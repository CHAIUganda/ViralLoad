<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}
?>
  <!--<div style="height: 250px; width: 100%; overflow: auto; padding:5px">-->
  <div style="padding:5px">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <? if($success) { ?>
      <tr>
        <td class="vl_success">
            <strong>Report Created!</strong><br />
            <div class="vls_grey" style="padding:5px 0px 0px 0px"><img src="/images/arrow_right.gif" border="0"> <a href="<?=vlDecrypt($pathtofile)?>" class="vls_grey">Click here to Download the Report.</a></div>
        </td>
      </tr>
    <? } ?>
      <tr>
        <td valign="top" style="padding:10px 0px; border-bottom: 1px dashed #CCC">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="vl_tdnoborder" width="5%"><img src="/images/download.excel.gif" width="55" height="55" alt="downloads" border="0" /></td>
                <td class="vl_tdnoborder" width="95%">
                    <div style="padding:0px 0px 5px 2px">Cumulative Number of Samples Received</div>
					<script Language="JavaScript" Type="text/javascript">
                    <!--
                    function checkForm(searchForm) {
                        if(document.pressed == '   Download Excel   ') {
                            document.modifyForm.action ="/download/samples.received.excel/";
                        } else if(document.pressed == '   Download CSV   ') {
                            document.modifyForm.action ="/download/samples.received.csv/";
                        }
                        return (true);
                    }
                    //-->
                    </script>
                    <form name="modifyForm" method="post" action="/download/samples.received.excel/" onsubmit="return checkForm(this)">
                        <table width="100%" border="0" class="nms">
                          <tr>
                            <td width="5%">From:</td>
                            <td width="95%"><table width="10%" border="0" cellspacing="0" cellpadding="0" class="nms">
                              <tr>
                                <td><select name="fromDay" id="fromDay" class="search">
                                  <?
                                    for($j=1;$j<=31;$j++) {
                                        echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
                                    }
                                    ?>
                                </select></td>
                                <td style="padding:0px 0px 0px 5px"><select name="fromMonth" id="fromMonth" class="search">
                                  <? echo "<option value=\"".getFormattedDateMonth($datetime)."\" selected=\"selected\">".getFormattedDateMonthname($datetime)."</option>"; ?>
                                  <option value="01">January</option>
                                  <option value="02">February</option>
                                  <option value="03">March</option>
                                  <option value="04">April</option>
                                  <option value="05">May</option>
                                  <option value="06">June</option>
                                  <option value="07">July</option>
                                  <option value="08">August</option>
                                  <option value="09">September</option>
                                  <option value="10">October</option>
                                  <option value="11">November</option>
                                  <option value="12">December</option>
                                </select></td>
                                <td style="padding:0px 0px 0px 5px"><select name="fromYear" id="fromYear" class="search">
                                  <?
                                        for($j=getFormattedDateYear($datetime);$j>=(getCurrentYear()-10);$j--) {
                                            echo "<option value=\"$j\">$j</option>";
                                        }
                                        ?>
                                </select></td>
                              </tr>
                            </table></td>
                          </tr>
                          <tr>
                            <td>To:</td>
                            <td><table width="10%" border="0" cellspacing="0" cellpadding="0" class="nms">
                              <tr>
                                <td><select name="toDay" id="toDay" class="search">
                                  <?
                                    for($j=1;$j<=31;$j++) {
                                        echo "<option value=\"".($j<10?"0$j":$j)."\" ".($j==getFormattedDateDay(getDualInfoWithAlias("last_day(now())","lastmonth"))?"selected=\"selected\"":"").">$j</option>";
                                    }
                                    ?>
                                </select></td>
                    
                                <td style="padding:0px 0px 0px 5px"><select name="toMonth" id="toMonth" class="search">
                                  <? echo "<option value=\"".getFormattedDateMonth($datetime)."\" selected=\"selected\">".getFormattedDateMonthname($datetime)."</option>"; ?>
                                  <option value="01">January</option>
                                  <option value="02">February</option>
                                  <option value="03">March</option>
                                  <option value="04">April</option>
                                  <option value="05">May</option>
                                  <option value="06">June</option>
                                  <option value="07">July</option>
                                  <option value="08">August</option>
                                  <option value="09">September</option>
                                  <option value="10">October</option>
                                  <option value="11">November</option>
                                  <option value="12">December</option>
                                </select></td>
                                <td style="padding:0px 0px 0px 5px"><select name="toYear" id="toYear" class="search">
                                  <?
                                        for($j=getFormattedDateYear($datetime);$j<=(getCurrentYear()+50);$j++) {
                                            echo "<option value=\"$j\">$j</option>";
                                        }
                                        ?>
                                </select></td>
                              </tr>
                            </table></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td style="padding:0px 0px 0px 0px">
                                <input type="submit" name="downloadXLS" id="downloadXLS" onclick="document.pressed=this.value" value="   Download Excel   " class="button" />
                                <input type="submit" name="downloadCSV" id="downloadCSV" onclick="document.pressed=this.value" value="   Download CSV   "    class="button" />
                            </td>
                          </tr>
                        </table>
                    </form>
                </td>
              </tr>
            </table>
        </td>
      </tr>
      <tr>
        <td valign="top" style="padding:10px 0px; border-bottom: 1px dashed #CCC">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="vl_tdnoborder" width="5%"><img src="/images/download.excel.gif" width="55" height="55" alt="downloads" border="0" /></td>
                <td class="vl_tdnoborder" width="95%">
                    <div style="padding:0px 0px 2px 2px">Download Facilities</div>
                    <div style="padding:3px 0px 5px 2px"><a href="/download/facilities.excel/">Download current list of facilities</a></div>
                </td>
              </tr>
            </table></td>
      </tr>
      <tr>
        <td valign="top" style="padding:10px 0px">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="vl_tdnoborder" width="5%"><img src="/images/download.excel.gif" width="55" height="55" alt="downloads" border="0" /></td>
                <td class="vl_tdnoborder" width="95%">
                    <div style="padding:0px 0px 5px 2px">ViralLoad Books Allocated to Facility</div>
					<script Language="JavaScript" Type="text/javascript">
                    <!--
                    function checkForm(searchForm) {
                        if(document.pressed == '   Download Excel   ') {
                            document.vlBooksForm.action ="/download/clinical.request.forms.excel/";
                        } else if(document.pressed == '   Download CSV   ') {
                            document.vlBooksForm.action ="/download/samples.received.csv/";
                        }
                        return (true);
                    }
                    //-->
                    </script>
                    <form name="vlBooksForm" method="post" action="/download/clinical.request.forms.excel/" onsubmit="return checkForm(this)">
                        <table width="100%" border="0" class="nms">
                          <tr>
                            <td width="5%">From:</td>
                            <td width="95%"><table width="10%" border="0" cellspacing="0" cellpadding="0" class="nms">
                              <tr>
                                <td><select name="fromDay" id="fromDay" class="search">
                                  <?
                                    for($j=1;$j<=31;$j++) {
                                        echo "<option value=\"".($j<10?"0$j":$j)."\">$j</option>";
                                    }
                                    ?>
                                </select></td>
                                <td style="padding:0px 0px 0px 5px"><select name="fromMonth" id="fromMonth" class="search">
                                  <? echo "<option value=\"".getFormattedDateMonth($datetime)."\" selected=\"selected\">".getFormattedDateMonthname($datetime)."</option>"; ?>
                                  <option value="01">January</option>
                                  <option value="02">February</option>
                                  <option value="03">March</option>
                                  <option value="04">April</option>
                                  <option value="05">May</option>
                                  <option value="06">June</option>
                                  <option value="07">July</option>
                                  <option value="08">August</option>
                                  <option value="09">September</option>
                                  <option value="10">October</option>
                                  <option value="11">November</option>
                                  <option value="12">December</option>
                                </select></td>
                                <td style="padding:0px 0px 0px 5px"><select name="fromYear" id="fromYear" class="search">
                                  <?
                                        for($j=getFormattedDateYear($datetime);$j>=(getCurrentYear()-10);$j--) {
                                            echo "<option value=\"$j\">$j</option>";
                                        }
                                        ?>
                                </select></td>
                              </tr>
                            </table></td>
                          </tr>
                          <tr>
                            <td>To:</td>
                            <td><table width="10%" border="0" cellspacing="0" cellpadding="0" class="nms">
                              <tr>
                                <td><select name="toDay" id="toDay" class="search">
                                  <?
                                    for($j=1;$j<=31;$j++) {
                                        echo "<option value=\"".($j<10?"0$j":$j)."\" ".($j==getFormattedDateDay(getDualInfoWithAlias("last_day(now())","lastmonth"))?"selected=\"selected\"":"").">$j</option>";
                                    }
                                    ?>
                                </select></td>
                    
                                <td style="padding:0px 0px 0px 5px"><select name="toMonth" id="toMonth" class="search">
                                  <? echo "<option value=\"".getFormattedDateMonth($datetime)."\" selected=\"selected\">".getFormattedDateMonthname($datetime)."</option>"; ?>
                                  <option value="01">January</option>
                                  <option value="02">February</option>
                                  <option value="03">March</option>
                                  <option value="04">April</option>
                                  <option value="05">May</option>
                                  <option value="06">June</option>
                                  <option value="07">July</option>
                                  <option value="08">August</option>
                                  <option value="09">September</option>
                                  <option value="10">October</option>
                                  <option value="11">November</option>
                                  <option value="12">December</option>
                                </select></td>
                                <td style="padding:0px 0px 0px 5px"><select name="toYear" id="toYear" class="search">
                                  <?
                                        for($j=getFormattedDateYear($datetime);$j<=(getCurrentYear()+50);$j++) {
                                            echo "<option value=\"$j\">$j</option>";
                                        }
                                        ?>
                                </select></td>
                              </tr>
                            </table></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td style="padding:0px 0px 0px 0px">
                                <input type="submit" name="downloadXLS" id="downloadXLS" onclick="document.pressed=this.value" value="   Download Excel   " class="button" />
                                <!--<input type="submit" name="downloadCSV" id="downloadCSV" onclick="document.pressed=this.value" value="   Download CSV   "    class="button" />-->
                            </td>
                          </tr>
                        </table>
                    </form>
                </td>
              </tr>
            </table>
        </td>
      </tr>
    </table>
  </div>
