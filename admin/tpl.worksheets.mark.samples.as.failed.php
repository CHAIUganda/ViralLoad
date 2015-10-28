<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}
?>
  <table width="100%" border="0">
    <tr>
      <td width="65%" valign="top">
      
		<? if($option=="remove") { ?>
            <table width="100%" border="0" class="vl">
                <tr>
                    <td class="vl_success">Samples Removed from Available List!</td>
                </tr>
                <tr>
                    <td><img src="/admin/images/spacer.gif" width="3" height="3" /></td>
                </tr>
            </table>
        <? } ?>
		<?
        switch($option) {
            case removeoverride:
				//delete from vl_samples_verify
				if($id) {
					logDataRemoval("delete from vl_results_override where id='$id'");
					mysqlquery("delete from vl_results_override where id='$id'");
					$removed=1;
				}
            break;
            case override:
				//delete from vl_samples_verify
				if(count($samplesID)) {
					$added=0;
					$modified=0;
					foreach($samplesID as $sID) {
						//split $sID based on ::
						$dataArray=array();
						$dataArray=explode("::",$sID);
						$sampleID=0;
						$sampleID=$dataArray[0];
						$worksheetID=0;
						$worksheetID=$dataArray[1];
						$id=0;
						$id=getDetailedTableInfo2("vl_results_override","sampleID='$sampleID' and worksheetID='$worksheetID'","id");
						//avoid duplicates
						if(!$id) {
							//insert into vl_results_override
							mysqlquery("insert into vl_results_override 
									(sampleID,worksheetID,result,created,createdby) 
									values 
									('$sampleID','$worksheetID','$result','$datetime','$_SESSION[VLADMIN]')");
							//added
							$added+=1;
						} else {
							//log table change
							logTableChange("vl_results_override","result",$id,getDetailedTableInfo2("vl_results_override","id='$id'","result"),$result);
							//update vl_results_override
							mysqlquery("update vl_results_override set result='$result' where id='$id'");
							//modified
							$modified+=1;
						}
					}
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
		<? if($added || $modified) { ?>
            <table width="100%" border="0" class="vl">
                <tr>
                  <td class="vl_success"><?=number_format((float)$added)?> Override<?=($added==1?"":"s")?> Added, <?=number_format((float)$modified)?> Override<?=($modified==1?"":"s")?> Modified!</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
            </table>
		<? } ?>
		<? if($removed) { ?>
            <table width="100%" border="0" class="vl">
                <tr>
                  <td class="vl_success">Override Removed!</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
            </table>
		<? } ?>

		<?
		//array declaration
		$sampleArray=array();
		$worksheetArray=array();
		$machineArray=array();
		
		//rejected samples that have never been accepted in subsequent runs, start with roche machines
		$query=0;
		$query=mysqlquery("select distinct SampleID sampleID, worksheetID from vl_results_roche where Result='Failed'");
		if(mysqlnumrows($query)) {
			while($q=mysqlfetcharray($query)) {
				$sampleArray[]=$q["sampleID"];
				$worksheetArray[]=$q["worksheetID"];
				$machineArray[]="roche";
			}
		}

		//then progress to abbott machines
		$query=0;
		$query=mysqlquery("select distinct sampleID, worksheetID from vl_results_abbott 
									where 
										trim(result)='-1.00' or 
											trim(result)='3153 There is insufficient volume in the vessel to perform an aspirate or dispense operation.' or 
												trim(result)='3109 A no liquid detected error was encountered by the Liquid Handler.' or 
													trim(result)='A no liquid detected error was encountered by the Liquid Handler.' or 
														trim(result)='Unable to process result, instrument response is invalid.' or 
															trim(result)='3118 A clot limit passed error was encountered by the Liquid Handler.' or 
																trim(result)='3130 A less liquid than expected error was encountered by the Liquid Handler.' or 
																	trim(result)='3131 A more liquid than expected error was encountered by the Liquid Handler.' or 
																		trim(result)='3152 The specified submerge position for the requested liquid volume exceeds the calibrated Z bottom' or 
																			trim(result)='4455 Unable to process result, instrument response is invalid.' or 
																				trim(result)='A no liquid detected error was encountered by the Liquid Handler.' or 
																					trim(result)='Failed          Internal control cycle number is too high. Valid range is [18.48, 22.48].' or 
																						trim(result)='Failed          Failed            Internal control cycle number is too high. Valid range is [18.48,' or 
																							trim(result)='Failed          Failed          Internal control cycle number is too high. Valid range is [18.48, 2' or 
																								trim(result)='OPEN' or 
																									trim(result)='There is insufficient volume in the vessel to perform an aspirate or dispense operation.' or 
																										trim(result)='Unable to process result, instrument response is invalid.' or 
																											trim(substr(flags,1,47))='4442 Internal control cycle number is too high.'");

		if(mysqlnumrows($query)) {
			while($q=mysqlfetcharray($query)) {
				$sampleArray[]=$q["sampleID"];
				$worksheetArray[]=$q["worksheetID"];
				$machineArray[]="abbott";
			}
		}

		//get distinct
		//$sampleArray=array_unique($sampleArray);
		//$worksheetArray=array_unique($worksheetArray);
        ?>
        <script Language="JavaScript" Type="text/javascript">
		<!--
        function checkForm(adminsForm) {
			//avoid blanks
			if(!document.adminsForm.result.value) {
				alert('Missing Mandatory Field: Result Message');
				document.adminsForm.result.focus();
				return false;
			}
			
			//confirm
			if(confirm('Are you sure?')) {
	            return (true);
			} else {
	            return (false);
			}
        }
        //-->
        </script>
        
        <form name="adminsForm" method="post" action="?act=markSamplesAsFailed&nav=datamanagement" onsubmit="return checkForm(this)">
          <table width="100%" border="0" class="vl">
            <tr>
              <td style="border-bottom:1px solid #cccccc; padding-bottom:10px">Available Sample<?=(count($sampleArray)==1?"":"s")?> (<?=number_format((float)count($sampleArray))?>)</td>
            </tr>
            <tr>
              <td style="border-bottom:1px solid #cccccc; padding:10px 0px">
              	<!--<input type="text" name="result" id="result" class="search" size="45" value="Invalid test result. There is insufficient sample to repeat the assay." maxlength="250" />-->
                <input type="text" name="result" id="result" class="search" size="45" value="There is No Result Given. The Test Failed the Quality Control Criteria. We advise you send a a new sample." maxlength="250" />
              </td>
            </tr>
            <tr> 
              <td style="padding:10px 0px 10px 0px">
              
              <div style="height: 300px; border: 1px solid #ccccff; overflow: auto">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="vl">
                    <tr>
                      <td width="1%" class="vl_tdsub"><input type="checkbox" name="checkall" onClick="checkUncheckAll(this);"></td>
                      <td width="79%" class="vl_tdsub">Sample Details</td>
                      <td width="20%" class="vl_tdsub">Override&nbsp;Options</td>
                    </tr>
<?
if(count($sampleArray)) {
	for($i=0;$i<count($sampleArray);$i++) {
		$class=0;
		if($i<count($sampleArray)-1) {
			$class="vl_tdstandard";
		} else {
			$class="vl_tdnoborder";
		}
		$worksheet=0;
		$worksheet=getDetailedTableInfo2("vl_samples_worksheetcredentials","id='$worksheetArray[$i]'","worksheetReferenceNumber");
		$patientID=0;
		$patientID=getDetailedTableInfo2("vl_samples","id='$sampleArray[$i]'","patientID");
		$artNumber=0;
		$artNumber=getDetailedTableInfo2("vl_patients","id='$patientID'","artNumber");
		$sampleID=0;
		$sampleID=$sampleArray[$i];
		$created=0;
		$created=getDetailedTableInfo2("vl_samples_verify","sampleID='$sampleArray[$i]'","created");
		$createdby=0;
		$createdby=getDetailedTableInfo2("vl_samples_verify","sampleID='$sampleArray[$i]'","createdby");
		$id=0;
		$id=getDetailedTableInfo2("vl_results_override","sampleID='$sampleArray[$i]' and worksheetID='$worksheetArray[$i]'","id");
		$result=0;
		$result=getDetailedTableInfo2("vl_results_override","sampleID='$sampleArray[$i]' and worksheetID='$worksheetArray[$i]'","result");
?>
        <tr>
          <td class="<?=$class?>"><input name="samplesID[]" type="checkbox" id="samplesID[]" value="<?=$sampleArray[$i]."::".$worksheetArray[$i]?>" /></td>
          <td class="<?=$class?>">
		  	<div><strong>ART Number:</strong> <?=$artNumber?></div>
		  	<div class="vls_grey" style="padding:3px 0px 0px 0px"><strong>SampleID:</strong> <?=$sampleID?></div>
		  	<div class="vls_grey" style="padding:3px 0px 0px 0px"><strong>Worksheet:</strong> <?=$worksheet?></div>
		  	<div class="vls_grey" style="padding:3px 0px 0px 0px"><strong>Machine:</strong> <?=$machineArray[$i]?></div>
          </td>
          <? if($id) { ?>
          <td class="<?=$class?>" align="center">
          <div><a href="javascript:if(confirm('Are you sure?')) { document.location.href='?act=markSamplesAsFailed&nav=datamanagement&option=removeoverride&id=<?=$id?>'; }">Remove&nbsp;Override</a></div>
          <div class="vls_grey" style="padding:3px 0px"><?=$result?></div>
          </td>
          <? } else { ?>
          <td class="<?=$class?>" align="center">NA</td>
          <? } ?>
        </tr>
<?
	}
}
?>
                  </table>
                  </div>
                                
              </td>
            </tr>
            <tr> 
              <td style="border-top:1px solid #cccccc; padding-top:10px"> 
              <input type="submit" name="button" id="button" value="  Override Sample's Result  " /> 
              <? if($task=="modify") { ?>
              <button type="button" id="button" name="button" value="button" onclick="document.location.href='?act=markSamplesAsFailed&nav=datamanagement'">&nbsp;&nbsp;Cancel&nbsp;&nbsp;</button>
              <input name="id" type="hidden" id="id" value="<?=$id?>"> 
              <? } ?>
              <input name="act" type="hidden" id="act" value="markSamplesAsFailed">
              <input name="option" type="hidden" id="option" value="override">
              </td>
            </tr>
          </table>
        </form>
      
      </td>
      <td width="35%" valign="top" style="padding:3px 0px 0px 12px">
        <table border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #d5d5d5" width="100%">
          <tr>
            <td style="padding:10px"><table width="100%" border="0" class="vl">
              <tr>
                <td><strong>REMOVE WORKSHEET SAMPLES</strong></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td bgcolor="#d5d5d5" style="padding:10px">Check the sample to be removed from the list of available samples for inclusion into a Worksheet</td>
              </tr>
            </table></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>