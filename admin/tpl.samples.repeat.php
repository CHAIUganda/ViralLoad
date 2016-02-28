<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

if($searchQuery) {
	$searchQuery=validate($searchQuery);
}
?>
  <table width="100%" border="0">
    <tr>
      <td width="65%" valign="top">
		<? if($saved) { ?>
            <table width="100%" border="0" class="vl">
                <tr>
                    <td class="vl_success">Sample Added!</td>
                </tr>
                <tr>
                    <td><img src="/admin/images/spacer.gif" width="3" height="3" /></td>
                </tr>
            </table>
        <? } ?>
		<?
        switch($option) {
            case add:
				//validate
				$sampleID=validate($sampleID);

				//check for missing variables
				$error=0;
				$error="";

				//split sample by ,
				$sampleIDArray=array();
				$sampleIDtoAdd=array();
				$sampleIDArray=explode(",",$sampleID);
				if(count($sampleIDArray)) {
					foreach($sampleIDArray as $sID) {
						$sID=trim($sID);
						//ensure sampleID is valid
						if(!getDetailedTableInfo2("vl_samples","vlSampleID='$sID'","id")) {
							$error.="<br />Incorrect SampleID <strong>$sID</strong>.";
						} else {
							$sampleIDtoAdd[]=$sID;
						}
					}
					
					if(!$error) {
						foreach($sampleIDtoAdd as $sToAdd) {
							$sToAdd=trim($sToAdd);
							//key variables
							$numericSampleID=0;
							$numericSampleID=getDetailedTableInfo2("vl_samples","vlSampleID='$sToAdd'","id");
							$sampleReferenceNumber=0;
							$sampleReferenceNumber=$sToAdd;
							
							//derive worksheet
							$worksheetID=0;
							$worksheetIDCreated=0;
							$worksheetID=getDetailedTableInfo2("vl_samples_worksheet","sampleID='$numericSampleID' order by created desc limit 1","worksheetID");
							$worksheetIDCreated=getDetailedTableInfo2("vl_samples_worksheet","sampleID='$numericSampleID' order by created desc limit 1","created");
							
							//log repeat
							if(!getDetailedTableInfo2("vl_logs_samplerepeats","sampleID='$numericSampleID' and oldWorksheetID='$worksheetID' limit 1","id")) {
								mysqlquery("insert into vl_logs_samplerepeats  
												(sampleID,oldWorksheetID,created,createdby) 
												values 
												('$numericSampleID','$worksheetID','$datetime','$_SESSION[VLADMIN]')");
								//flags
								$added+=1;
							} else {
								$error.="<br />SampleID <strong>$sToAdd</strong> has already been scheduled for repeat.";
							}
						}
					}
				}
            break;
            case remove:
				if(getDetailedTableInfo2("vl_logs_samplerepeats","id='$id'","id")) {
					logDataRemoval("delete from vl_logs_samplerepeats where id='$id'");
					mysqlquery("delete from vl_logs_samplerepeats where id='$id'");
					//flag
					$removed=1;
				}
				
				//redirect
				if($removed) {
					go("?act=repeatSamples&nav=datamanagement&removed=1");
				}
            break;
            default:
                if($modify) {
                    $task="modify";
                }
            break;
		}
		
		//set task
		if(!$task) {
			$task="add";
		}
?>
        <script Language="JavaScript" Type="text/javascript">
		<!--
        function checkForm(adminsForm) {
			if(!document.adminsForm.sampleID.value) {
				alert('Missing Mandatory Field: Sample ID');
				document.adminsForm.sampleID.focus();
				return (false);
			}
            return (true);
        }
        //-->
        </script>
        
        <form name="adminsForm" method="post" action="?act=repeatSamples&nav=datamanagement" enctype="multipart/form-data" onsubmit="return checkForm(this)">
          <table width="90%" border="0" class="vl">
		<? if($added) { ?>
            <tr>
              <td class="vl_success">
              <?
				echo "The following Samples were Successfully Scheduled for Repeat:<br>";
				foreach($sampleIDtoAdd as $sToAdd) {
					$sToAdd=trim($sToAdd);
					echo "$sToAdd<br>";
				}
			  ?>
              </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
		<? } if($removed) { ?>
            <tr>
              <td class="vl_success">Removed from Repeat Schedule!</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
		<? } if($error) { ?>
            <tr>
              <td class="vl_error">Unable to process your submission due to the following error(s): <?=$error?></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
        <? 
		}
		if($task=="add") { ?>
            <tr>
              <td style="border-bottom:1px solid #cccccc; padding-bottom:10px">Schedule Sample(s) for Repeat</td>
            </tr>
        <? } ?>
            <tr> 
              <td style="padding:10px 0px 10px 0px">
						<table width="100%" border="0" class="vl">
                            <tr>
                              <td width="20%">Sample&nbsp;ID&nbsp;<font class="vl_red">*</font></td>
                              <td width="80%">
                              <textarea name="sampleID" id="sampleID" cols="40" rows="5" class="searchLarge"></textarea>
                              <div class="vls_grey" style="padding:3px 0px 0px 0px">Separate multiple SampleIDs with a <strong>,</strong></div>
                              </td>
                            </tr>
                          </table>
              </td>
            </tr>
            <tr> 
              <td style="border-top:1px solid #cccccc; padding-top:10px"> 
              <input type="submit" name="button" id="button" value=" Schedule Sample(s) for Repeat " /> 
              <button type="button" id="button" name="button" value="button" onclick="document.location.href='?act=repeatSamples&nav=datamanagement'">&nbsp;&nbsp;Cancel&nbsp;&nbsp;</button>
              <input name="act" type="hidden" id="act" value="repeatSamples">
              <input name="option" type="hidden" id="option" value="<?=$task?>">
              </td>
            </tr>
          </table>
        </form>

		<?
		$query=0;
		$query=mysqlquery("select * from vl_logs_samplerepeats where withWorksheetID='' order by created");
		$num=0;
		$num=mysqlnumrows($query);
        if($num) {
        ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
            <tr>
                <td style="padding:5px 0px 5px 0px" align="center">
                	<div style="height: 200px; border: 1px solid #ccccff; overflow: auto">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
                        <tr>
                          <td class="vl_tdsub" width="80%" style="padding-left:16px"><strong>Sample&nbsp;Details</strong></td>
                          <td class="vl_tdsub" width="10%" align="center"><strong>Options</strong></td>
                        </tr>
                    	<?
                        $count=0;
                        $q=array();
                        while($q=mysqlfetcharray($query)) {
                            $count+=1;
							$patientID=0;
							$patientID=getDetailedTableInfo2("vl_samples","id='$q[sampleID]'","patientID");
							$artNumber=0;
							$artNumber=getDetailedTableInfo2("vl_patients","id='$patientID'","artNumber");
							$otherID=0;
							$otherID=getDetailedTableInfo2("vl_patients","id='$patientID'","otherID");
							$sampleID=0;
							$sampleID=getDetailedTableInfo2("vl_samples","id='$q[sampleID]'","vlSampleID");
							$created=0;
							$created=getDetailedTableInfo2("vl_samples_verify","sampleID='$q[sampleID]'","created");
							$createdby=0;
							$createdby=getDetailedTableInfo2("vl_samples_verify","sampleID='$q[sampleID]'","createdby");
                        ?>
                            <tr>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>">
                                    <div><strong><?=($artNumber?"ART Number":"")?><?=($artNumber && $otherID?"/":"")?><?=($otherID?"OtherID":"")?>:</strong> <?=($artNumber?$artNumber:"")?><?=($artNumber && $otherID?"/":"")?><?=($otherID?$otherID:"")?></div>
                                    <div class="vls_grey" style="padding:3px 0px 0px 0px"><strong>SampleID:</strong> <?=$sampleID?></div>
                                    <div class="vls_grey" style="padding:3px 0px 0px 0px"><strong>Scheduled on:</strong> <?=getFormattedDate($created)?> by <?=$createdby?></div>
                                </td>
                                <td class="<?=($count<$num?"vl_tdstandard":"vl_tdnoborder")?>" align="center"><a href="javascript:if(confirm('Are you sure?')) { document.location.href='?act=repeatSamples&nav=datamanagement&option=remove&id=<?=$q["id"]?>'; }">delete</a></td>
                            </tr>
                        <? } ?>
                    </table>
                    </div>
                </td>
            </tr>
        </table>
		<? } ?>
      </td>
      <td width="35%" valign="top" style="padding:3px 0px 0px 12px">
        <table border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #d5d5d5" width="100%">
          <tr>
            <td style="padding:10px">
            <table width="100%" border="0" class="vl">
              <tr>
                <td><strong>REPEAT SAMPLES</strong></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td bgcolor="#d5d5d5" style="padding:10px">Samples Scheduled for Repeat</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>