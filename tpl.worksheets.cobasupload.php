<?
//security check
if(!$GLOBALS['vlDC'] || !$_SESSION["VLEMAIL"]) {
	die("<font face=arial size=2>You must be logged in to view this page.</font>");
}

function insertVLResults($machineType="", $values="", $repeats="", $merged_values=""){
	$values = trim($values, ",");
	$repeats = trim($repeats, ",");
	$merged_values = trim($merged_values, ",");

	mysqlquery("insert into vl_results_roche (worksheetID, SampleID,Result,created,createdby) values  $values");
	/*if(!empty($repeats)){
		mysqlquery("insert into vl_logs_samplerepeats 
			(sampleID,oldWorksheetID,created,createdby) 
			values $repeats");
	}*/

	if(!empty($merged_values)){
		mysqlquery("insert ignore into vl_results_merged 
			(machine,worksheetID,vlSampleID,resultAlphanumeric,
			resultNumeric,suppressed,created,createdby) 
			values $merged_values ");
	}
									
}


if($uploadResults) {
	//validate data
	$error="";
	//is this Items's code is identical to another, decline it
	if(!$_FILES['userfile']['tmp_name']) {
		$error.="<br />No File Uploaded.<br />";
	}
	
	//input data
	if(!$error) {
		//has file been uploaded
		if(is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			//filename
			$fileOriginalName=0;
			$fileOriginalName=$_FILES['userfile']['name'];
			$fileOriginalName=addslashes($fileOriginalName);
			//temp name
			$tmpName=0;
			$tmpName=$_FILES['userfile']['tmp_name'];
			//size
			$fileSize=0;
			$fileSize=$_FILES['userfile']['size'];
			//type
			$fileType=0;
			$fileType=$_FILES['userfile']['type'];
			//extension
			$extension=0;
			$extension=ext($fileOriginalName);
			//check type
			if($type=="abbott") { //text files only, for abbott
				if($extension!="txt") {
					$error.="<br>Incorrect file extension '.$extension'. Try saving the document as a 'txt' file";
				}
			} elseif($type=="roche") { //csv files only, for abbott
				if($extension!="csv") {
					$error.="<br>Incorrect file extension '.$extension'. Try saving the document as a 'csv' file";
				}
			}
			if($_FILES['userfile']['size']>$default_maxUploadSize) {
				$error.="<br>File size is greater than the accepted ".number_format((float)$default_maxUploadSize/1000)." KB. Please reduce the size of the file then attempt to upload it again.";
			}
			if(!$error) {
				//added/modified
				$added=0;
				$modified=0;

				//log the factor
				$last_wid = getDetailedTableInfo2("vl_samples_worksheetcredentials"," 1 order by id desc limit 1","id");
				$worksheetID = $last_wid+1;
				$ref_number = generateWorksheetReferenceNumber();
				mysqlquery("INSERT INTO vl_samples_worksheetcredentials 
								(id, worksheetReferenceNumber, machineType,  includeCalibrators, stage, created, createdby )
								VALUES $worksheetID,'$ref_number','roche', 0, 'has_results', '$datetime', '$trailSessionUser'");

				$factorID=0;
				$factorID=getDetailedTableInfo2("vl_results_multiplicationfactor","worksheetID='$worksheetID' limit 1","id");
				
				if(!$factorID) {
					mysqlquery("insert into vl_results_multiplicationfactor 
									(worksheetID,factor,created,createdby) 
									values 
									('$worksheetID','$factor','$datetime','$trailSessionUser')");
				} else {
					logTableChange("vl_results_multiplicationfactor","worksheetID",$factorID,getDetailedTableInfo2("vl_results_multiplicationfactor","id='$factorID'","worksheetID"),$worksheetID);
					logTableChange("vl_results_multiplicationfactor","factor",$factorID,getDetailedTableInfo2("vl_results_multiplicationfactor","id='$factorID'","factor"),$factor);
			
					mysqlquery("update vl_results_multiplicationfactor set 
									worksheetID='$worksheetID', 
									factor='$factor' 
									where 
									id='$factorID'");
				}

				$roche_insert_values = "";
				$log_repeat_values = "";
				$merged_values = "";
				$smpl_wksht_values = "";
				//load file 
				$file = fopen($_FILES['userfile']['tmp_name'], 'r');
				$count=0;
				while(($line = fgetcsv($file,0,","))!==FALSE) {
					$count+=1;
					//ignore the header
					$sampleID = $line[1];
					$result = $line[5];

					$s_id = getDetailedTableInfo2("vl_samples","vlSampleID='$sampleID' limit 1","id");

					if($s_id){
						$smpl_wksht_values .= "('$worksheetID', '$s_id', '$datetime', '$trailSessionUser'),";
						$roche_insert_values.="('$worksheetID',  '$SampleID', '$Result','$datetime','$trailSessionUser'),";

						$resultAlphanumeric = getVLNumericResult($Result,$machineType,$factor);
						//numeric result
						$resultNumeric=0;
						$resultNumeric=getVLNumericResultOnly($resultAlphanumeric);

						$is_res_valid=isResultValid($resultAlphanumeric); //checks for validity of the
						$spprssn_status=isSuppressed($is_res_valid,$resultNumeric); //get the suppression status
						

						//log into vl_results_merged
						$merged_values .= "('roche','$worksheetID','$SampleID','$resultAlphanumeric',
											'$resultNumeric','$spprssn_status','$datetime','$trailSessionUser'),";
					}

				}
				if(!empty($roche_insert_values)) insertVLResults("roche", $roche_insert_values, $log_repeat_values, $merged_values);
				
				if(!empty($smpl_wksht_values)) mysqlquery("INSERT INTO vl_samples_worksheet (worksheetID, sampleID, created, createdby) VALUES ".trim($smpl_wksht_values, ","));
				fclose($file);
				
			}
		} else {
			$error.="<br>File could not be uploaded. Consider contacting your Systems Administrator.";
		}

		//redirect
		go("/worksheets/success/".($added?$added:0)."/".($modified?$modified:0)."/");
	}
}
?>
<script Language="JavaScript" Type="text/javascript">
<!--
function validate(worksheets) {
	//check for missing information
	if(!document.worksheets.factor.value) {
		alert('Missing Mandatory Field: Factor by which results are to be multiplied');
		document.worksheets.factor.focus();
		return (false);
	}
	if(!document.worksheets.userfile.value) {
		alert('Missing Mandatory Field: Select File to Upload');
		return (false);
	}
	return (true);
}
//-->
</script>
<form name="worksheets" method="post" action="/worksheets/cobasupload/" onsubmit="return validate(this)" enctype="multipart/form-data">
<!--<form name="worksheets" method="post" action="/worksheets/upload.results/<?=$worksheetID?>/" enctype="multipart/form-data">-->
<table width="100%" border="0" class="vl">
          <? if($success) { ?>
            <tr>
                <td class="vl_success">Results Loaded Successfully!</td>
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
              <td class="toplinks" style="padding:0px 0px 10px 0px"><a class="toplinks" href="/dashboard/">HOME</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="toplinks" href="/worksheets/">WORKSHEETS</a></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                  <fieldset style="width: 85%">
                    <legend><strong>RESULT FILE SPECIFICATIONS</strong></legend>
                      <table width="100%" border="0" class="vl">
                     
                      <tr>
                        <td style="padding:0px 0px 5px 0px">Reference Number:</td>
                        <td style="padding:0px 0px 5px 0px"><i>Will be created after uploading</i></td>
                      
                        <tr>
                          <td>Multiply Results by:</td>
                          <td><input type="text" name="factor" id="factor" value="<?=($factor?$factor:1)?>" onkeyup="return isNumber(this,'1')" class="search_pre" size="5" maxlength="10" /></td>
                        </tr>
                        <tr>
                          <td>Select&nbsp;Results&nbsp;File&nbsp;<font class="vl_red">*</font></td>
                          <td><input id="file" name="userfile" type="file" class="search" size="28" /></td>
                        </tr>
                      </table>
                  </fieldset>
                </td>
            </tr>
            <tr>
              <td style="padding:10px 0px 0px 0px">
              	<div id="check_message" style="font-size:16px;color:red"></div>
              	<input type="submit" name="uploadResults" id="uploadResults" class="button" value="  Upload Results  " style = "display:none" /></td>
            </tr>
            <tr>
	            <td style="padding:20px 0px 0px 0px"><a href="/worksheets/">Return to Worksheets</a> :: <a href="/dashboard/">Return to Dashboard</a></td>
            </tr>
          </table>
</form>
<script type="text/javascript">

$("#file").on("change", function(){
	var form = $('form')[1];
	var formData = new FormData(form);
	formData.append('file', this.files[0]);
	formData.append('machine', "roche");
	$.ajax({
	   url : '/check_results/',
	   type : 'POST',
	   data : formData,
	   processData: false,  // tell jQuery not to process the data
	   contentType: false,  // tell jQuery not to set contentType
	   success : function(data) {
	       if(data==1){
	       	$("#check_message").hide();
	       	$("#uploadResults").show();
	       }else{
	       	$("#uploadResults").hide();
	       	var $el = $('#file');
		   $el.wrap('<form>').closest('form').get(0).reset();
		   $el.unwrap();	
		   $("#check_message").html("<b>"+data+"</b>");       
	       	//alert('the worksheet has duplicate samples');
	       }
	   }
	});
});

</script>