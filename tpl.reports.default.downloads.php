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
                    function checkFormMF(searchForm) {
                        if(document.pressed == '   Download Excel   ') {
                            document.modifyForm.action ="/download/samples.received.excel/";
                        } else if(document.pressed == '   Download CSV   ') {
                            document.modifyForm.action ="/download/samples.received.csv/";
                        }
                        return (true);
                    }
                    //-->
                    </script>
                    <form name="modifyForm" method="post" action="/download/samples.received.excel/" onsubmit="return checkFormMF(this)">
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
                                        for($j=(getFormattedDateYear($datetime)-5);$j<=(getCurrentYear()+50);$j++) {
                                            echo "<option value=\"$j\"".($j==getFormattedDateYear($datetime)?" selected=\"selected\"":"").">$j</option>";
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
            </table>
          </td>
      </tr>
      <tr>
        <td valign="top" style="padding:10px 0px; border-bottom: 1px dashed #CCC">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="vl_tdnoborder" width="5%"><img src="/images/download.excel.gif" width="55" height="55" alt="downloads" border="0" /></td>
                <td class="vl_tdnoborder" width="95%">
                  <div style="padding:3px 0px 5px 2px">
                   From: <input type="text" name="fro_date_tor" id="fro_date_tor" class="pick_date date_box" placeholder=" dd/mm/yyyy" maxlength="10">
                   To: <input type="text" name="to_date_tor" id="to_date_tor" class="pick_date date_box" placeholder=" dd/mm/yyyy " maxlength="10">
                 </div>
                    <div style="padding:3px 0px 5px 2px"><a href="#" onclick="testOR()">Test out come report</a></div>
                </td>
              </tr>
            </table>
          </td>
      </tr>
      <tr>
        <td valign="top" style="padding:10px 0px<?=(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='reportsQC' limit 1","id")?"; border-bottom: 1px dashed #CCC":"")?>">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="vl_tdnoborder" width="5%"><img src="/images/download.excel.gif" width="55" height="55" alt="downloads" border="0" /></td>
                <td class="vl_tdnoborder" width="95%">
                    <div style="padding:0px 0px 5px 2px">ViralLoad Books Allocated to Facility</div>
					<script Language="JavaScript" Type="text/javascript">
                    <!--
                    function checkFormBF(searchForm) {
                        if(document.pressed == '   Download Excel   ') {
                            document.vlBooksForm.action ="/download/clinical.request.forms.excel/";
                        } else if(document.pressed == '   Download CSV   ') {
                            document.vlBooksForm.action ="/download/samples.received.csv/";
                        }
                        return (true);
                    }
                    //-->
                    </script>
                    <form name="vlBooksForm" method="post" action="/download/clinical.request.forms.excel/" onsubmit="return checkFormBF(this)">
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
                                        for($j=(getFormattedDateYear($datetime)-5);$j<=(getCurrentYear()+50);$j++) {
                                            echo "<option value=\"$j\"".($j==getFormattedDateYear($datetime)?" selected=\"selected\"":"").">$j</option>";
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
      <? if(getDetailedTableInfo2("vl_users_permissions","userID='".getUserID($trailSessionUser)."' and permission='reportsQC' limit 1","id")) { ?>
      <tr>
        <td valign="top" style="padding:10px 0px">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="vl_tdnoborder" width="5%"><img src="/images/download.excel.gif" width="55" height="55" alt="downloads" border="0" /></td>
                <td class="vl_tdnoborder" width="95%">
                    <div style="padding:0px 0px 2px 2px">Patients with multiple, and different results</div>
                    <div style="padding:3px 0px 5px 2px"><a href="/download/patients.multiple.results.excel/">Download list of patients with multiple, and different results</a></div>
                </td>
              </tr>
            </table></td>
      </tr>
      <? } ?>
    </table>
  </div>
  <script type="text/javascript">
  function empty(e) {
    switch (e) {
      case "":
      case 0:
      case "0":
      case null:
      case false:
       case typeof this == "undefined":
       return true;
       default:
       return false;
     }
   }

  function stdDate(str){
    var arr=str.split("/");
    if(arr.length<3){
      return "";
    }
    return arr[2]+"-"+arr[1]+"-"+arr[0];
  }

  function strtotime(std_date){
    var obj=new Date(std_date);
    var micro_secs=obj.getTime();
    return micro_secs/1000;
  }

  function testOR(){
    var fro_date=stdDate($("#fro_date_tor").val());
    var to_date=stdDate($("#to_date_tor").val());

    var fro_s=strtotime(fro_date);
    var to_s=strtotime(to_date);

    if(empty(fro_s)||isNaN(fro_s) ||empty(to_s)||isNaN(to_s)){
      alert("Please enter right dates");
      return false;
    }else if (fro_s>to_s){
      alert("The fro date can't be after the to date");
      return false;
    }else{
      return window.location.assign("/test_outcome_report/"+fro_s+"/"+to_s);
    }

  }


   $(function() { $( ".pick_date" ).datepicker(); });
  </script>
