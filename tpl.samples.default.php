<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

$sampleRef=0;
if($sampleReferenceNumber) {
	$sampleRef=vlDecrypt($sampleReferenceNumber);
}
?>
<table width="100%" border="0" class="vl">
			<? if($success) { ?>
					<tr>
						<td colspan="2" class="vl_success">Sample Captured Successfully<?=($sampleRef?" with Sample Reference Number <strong>$sampleRef</strong>":"")?>!</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
			<?  } elseif($modified) { ?>
            <tr>
                <td colspan="2" class="vl_success">Sample Modifications Saved!</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
          <? } elseif($reviewed) { ?>
            <tr>
                <td colspan="2" class="vl_success">Review Notes Captured Successfully!</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
          <? } elseif($incorrect) { ?>
            <tr>
                <td colspan="2" class="vl_error">Incorrect Form or Sample Reference Number! Please try again.</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <? } ?>
            <tr>
                <td width="5%" align="center" valign="top"><a href="/samples/" class="vll_grey"><img src="/images/icon.samples.gif" border="0" /></a></td>
                <td width="95%" style="padding:0px 0px 0px 30px"><table width="100%" cellpadding="0" cellspacing="0" border="0" class="vl">
                  <tr>
                    <td><strong>Samples</strong></td>
                  </tr>
                  <tr>
                    <td class="vls_grey">Samples Management</td>
                  </tr>
                  <tr>
                    <td style="padding:20px 0px 10px 0px; border-bottom: 1px dashed #dfe6e6">
                    	<a href="/samples/capture/">Input a New Sample</a>
                        <div class="vls_grey" style="padding:2px 0px 0px 0px">Input Samples as retrieved from Envelopes</div>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding:10px 0px 15px 0px; border-bottom: 1px dashed #dfe6e6">
                    	<strong>Edit Samples</strong>
                        <div class="vls_grey" style="padding:2px 0px 0px 0px">Edit Data on Previously Captured Samples, Search for the Sample by <strong>Form #</strong> or <strong>Sample Reference Number</strong></div>
                        <div class="vls_grey" style="padding:5px 0px 0px 0px">
							<script Language="JavaScript" Type="text/javascript">
                            <!--
                            function checkForm(findSample) {
                                //check for missing information
                                if(!document.findSample.sample.value || document.findSample.sample.value=='Search by Form Number') {
                                    alert('Please input a Form # before Proceeding');
                                    document.findSample.sample.focus();
                                    return (false);
                                }
                                return (true);
                            }
                            //-->
                            </script>
                              <!--<form name="findSample" id="findSample" method="post" action="/samples/find.and.edit/" onsubmit="return checkForm(this)">-->
                              <form name="findSample" id="findSample" method="post" action="/samples/find.and.edit/">
                              <table width="40%" border="0" cellspacing="0" cellpadding="0" class="vl">
                                <tr>
                                  <td width="5%" style="padding:0px 5px 0px 0px"><input type="text" name="sample" id="sample" value="Search by Form Number" class="search_pre" size="20" maxlength="100" onfocus="if(value=='Search by Form Number') {value=''}" onblur="if(value=='') {value='Search by Form Number'}" /></td>
                                  <td width="95%"><input type="submit" name="Submit" class="buttonsmall" value="   Find and Edit Sample   " /></td>
                                </tr>
                              </table>
                              </form>
                        </div>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding:10px 0px 0px 0px">
                    	<a href="/samples/manage.patients/">Manage Patients</a>
                        <div class="vls_grey" style="padding:2px 0px 0px 0px">Edit Data on Previously Captured Patients</div>
                    </td>
                  </tr>
                </table></td>
            </tr>
          </table>