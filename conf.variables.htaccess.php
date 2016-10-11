<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* POST AND GET VARIABLES
*/

$all=0;
$all=getVariable("all");

$alphabet=0;
$alphabet=getVariable("alphabet");
 
$amc=0;
$amc=getVariable("amc");

$appendix=0;
$appendix=getVariable("appendix");

$bookingReferenceNumber=0;
$bookingReferenceNumber=getVariable("bookingReferenceNumber");

$button=0;
$button=getVariable("button");

$calloffID=0;
$calloffID=getVariable("calloffID");
 
$certificateOfAnalysisAvailable=0;
$certificateOfAnalysisAvailable=getVariable("certificateOfAnalysisAvailable");

$checkall=0;
$checkall=getVariable("checkall");

$code=0;
$code=getVariable("code");

$confirmCallOff=0;
$confirmCallOff=getVariable("confirmCallOff");

$contractEmployeeContact=0;
$contractEmployeeContact=getVariable("contractEmployeeContact");

$contractID=0;
$contractID=getVariable("contractID");
 
$contractNewReferenceNumber=0;
$contractNewReferenceNumber=getVariable("contractNewReferenceNumber");

$contractReferenceNumber=0;
$contractReferenceNumber=getVariable("contractReferenceNumber");

$createOrder=0;
$createOrder=getVariable("createOrder");

$currentpassword=0;
$currentpassword=getVariable("currentpassword");

$directlogin=0;
$directlogin=getVariable("directlogin");
 
$disp=0;
$disp=getVariable("disp");

$editQuantityAccepted=0;
$editQuantityAccepted=getVariable("editQuantityAccepted");

$email=0;
$email=getVariable("email");

$encryptedScheduleReferenceNumber=0;
$encryptedScheduleReferenceNumber=getVariable("encryptedScheduleReferenceNumber");
 
$expiryDateIsSatisfactory=0;
$expiryDateIsSatisfactory=getVariable("expiryDateIsSatisfactory");

$export=0;
$export=getVariable("export");

$fail=0;
$fail=getVariable("fail");

$fail=0;
$fail=getVariable("fail");
 
$firstTime=0;
$firstTime=getVariable("firstTime");
 
$id=0;
$id=getVariable("id");
 
$inactive=0;
$inactive=getVariable("inactive");
 
$issueID=0;
$issueID=getVariable("issueID");

$issueRating=0;
$issueRating=getVariable("issueRating");
 
$issueReference=0;
$issueReference=getVariable("issueReference");
 
$issueStatus=0;
$issueStatus=getVariable("issueStatus");
 
$itemCode=0;
$itemCode=getVariable("itemCode");
 
$itemMeetsNMSSpecifications=0;
$itemMeetsNMSSpecifications=getVariable("itemMeetsNMSSpecifications");

$itemMeetsSupplierSpecifications=0;
$itemMeetsSupplierSpecifications=getVariable("itemMeetsSupplierSpecifications");

$lastDeliveryID=0;
$lastDeliveryID=getVariable("lastDeliveryID");

$logChange=0;
$logChange=getVariable("logChange");

$logNextDeliveryDate=0;
$logNextDeliveryDate=getVariable("logNextDeliveryDate");

$login=0;
$login=getVariable("login");

$matchesCountryOfOrigin=0;
$matchesCountryOfOrigin=getVariable("matchesCountryOfOrigin");

$matchesManufacturerName=0;
$matchesManufacturerName=getVariable("matchesManufacturerName");

$minimumQuantity=0;
$minimumQuantity=getVariable("minimumQuantity");

$modified=0;
$modified=getVariable("modified");
 
$modifiedItems=0;
$modifiedItems=getVariable("modifiedItems");
 
$modify=0;
$modify=getVariable("modify");
 
$ndaApprovalAvailable=0;
$ndaApprovalAvailable=getVariable("ndaApprovalAvailable");

$newpassword=0;
$newpassword=getVariable("newpassword");

$newpassword2=0;
$newpassword2=getVariable("newpassword2");

$nextDeliveryDateDay=0;
$nextDeliveryDateDay=getVariable("nextDeliveryDateDay");

$nextDeliveryDateMonth=0;
$nextDeliveryDateMonth=getVariable("nextDeliveryDateMonth");

$nextDeliveryDateYear=0;
$nextDeliveryDateYear=getVariable("nextDeliveryDateYear");

$option=0;
$option=getVariable("option");

$orderID=0;
$orderID=getVariable("orderID");
 
$orderRef=0;
$orderRef=getVariable("orderRef");
 
$orderRefTitle=0;
$orderRefTitle=getVariable("orderRefTitle");

$packSize=0;
$packSize=getVariable("packSize");

$pass=0;
$pass=getVariable("pass");

$performanceBondReceived=0;
$performanceBondReceived=getVariable("performanceBondReceived");

$pg=0;
$pg=getVariable("pg");
 
$presentedContractID=0;
$presentedContractID=getVariable("presentedContractID");

$presentedDate=0;
$presentedDate=getVariable("presentedDate");
 
$presentedItemCode=0;
$presentedItemCode=getVariable("presentedItemCode");

$price=0;
$price=getVariable("price");

$proceed=0;
$proceed=getVariable("proceed");
 
$providePerformanceBond=0;
$providePerformanceBond=getVariable("providePerformanceBond");

$quantityAccepted=0;
$quantityAccepted=getVariable("quantityAccepted");

$quantityDelivered=0;
$quantityDelivered=getVariable("quantityDelivered");

$redirect=0;
$redirect=getVariable("redirect");

$redirect=0;
$redirect=getVariable("redirect");
 
$referenceNumber=0;
$referenceNumber=getVariable("referenceNumber");

$remindEmail=0;
$remindEmail=getVariable("remindEmail");
 
$remove=0;
$remove=getVariable("remove");
 
$reviewed=0;
$reviewed=getVariable("reviewed");
 
$save=0;
$save=getVariable("save");

$saveContract=0;
$saveContract=getVariable("saveContract");

$saveContractAdd=0;
$saveContractAdd=getVariable("saveContractAdd");

$saveContractReview=0;
$saveContractReview=getVariable("saveContractReview");

$saveEditQuantityAccepted=0;
$saveEditQuantityAccepted=getVariable("saveEditQuantityAccepted");

$saveItem=0;
$saveItem=getVariable("saveItem");

$saveItems=0;
$saveItems=getVariable("saveItems");

$saveLastDeliveryID=0;
$saveLastDeliveryID=getVariable("saveLastDeliveryID");

$saveOrder=0;
$saveOrder=getVariable("saveOrder");

$scheduleID=0;
$scheduleID=getVariable("scheduleID");
 
$scheduleReferenceNumber=0;
$scheduleReferenceNumber=getVariable("scheduleReferenceNumber");

$scheduleReferenceNumberSubmit=0;
$scheduleReferenceNumberSubmit=getVariable("scheduleReferenceNumberSubmit");

$searchFilter=0;
$searchFilter=getVariable("searchFilter");

$searchFilterURL=0;
$searchFilterURL=getVariable("searchFilterURL");

$searchQuery=0;
$searchQuery=getVariable("searchQuery");

$searchQueryURL=0;
$searchQueryURL=getVariable("searchQueryURL");

$selectedItemCode=0;
$selectedItemCode=getVariable("selectedItemCode");
 
$sentTo=0;
$sentTo=getVariable("sentTo");

$sub=0;
$sub=getVariable("sub");

$Submit=0;
$Submit=getVariable("Submit");

$submitDetails=0;
$submitDetails=getVariable("submitDetails");

$success=0;
$success=getVariable("success");
 
$supplierCode=0;
$supplierCode=getVariable("supplierCode");

$supplierEmail=0;
$supplierEmail=getVariable("supplierEmail");

$supplierID=0;
$supplierID=getVariable("supplierID");
  
$supplierNames=0;
$supplierNames=getVariable("supplierNames");

$supplierPhone=0;
$supplierPhone=getVariable("supplierPhone");

$taxInvoiceAvailable=0;
$taxInvoiceAvailable=getVariable("taxInvoiceAvailable");

$undo=0;
$undo=getVariable("undo");
 
$view=0;
$view=getVariable("view");

$supplierContract=0;
$supplierContract=getVariable("supplierContract");

$supplierContractItemCode=0;
$supplierContractItemCode=getVariable("supplierContractItemCode");

$duplicate=0;
$duplicate=getVariable("duplicate");

$newDeliveryDate=0;
$newDeliveryDate=getVariable("newDeliveryDate");

$reasonForDateChange=0;
$reasonForDateChange=getVariable("reasonForDateChange");

$supplierAddress=0;
$supplierAddress=getVariable("supplierAddress");

$supplierPrimaryContact=0;
$supplierPrimaryContact=getVariable("supplierPrimaryContact");

$reviewNotes=0;
$reviewNotes=getVariable("reviewNotes");

$supplierCommentsField=0;
$supplierCommentsField=getVariable("supplierCommentsField");

$deliveryComments=0;
$deliveryComments=getVariable("deliveryComments");

$description=0;
$description=getVariable("description");

$detaileddescription=0;
$detaileddescription=getVariable("detaileddescription");

$supplierLeadingMessage=0;
$supplierLeadingMessage=getVariable("supplierLeadingMessage");

$supplierTrailingMessage=0;
$supplierTrailingMessage=getVariable("supplierTrailingMessage");

$reasonForDateChange=0;
$reasonForDateChange=getVariable("reasonForDateChange");

//form select type variables
$contractCurrency=0;
$contractCurrency=getVariable("contractCurrency");

$contractEndDateDay=0;
$contractEndDateDay=getVariable("contractEndDateDay");

$contractEndDateMonth=0;
$contractEndDateMonth=getVariable("contractEndDateMonth");

$contractEndDateYear=0;
$contractEndDateYear=getVariable("contractEndDateYear");

$contractNotificationToEndDate=0;
$contractNotificationToEndDate=getVariable("contractNotificationToEndDate");

$contractNotificationToReviewDate=0;
$contractNotificationToReviewDate=getVariable("contractNotificationToReviewDate");

$contractReviews=0;
$contractReviews=getVariable("contractReviews");

$contractStartDateDay=0;
$contractStartDateDay=getVariable("contractStartDateDay");

$contractStartDateMonth=0;
$contractStartDateMonth=getVariable("contractStartDateMonth");

$contractStartDateYear=0;
$contractStartDateYear=getVariable("contractStartDateYear");

$currency=0;
$currency=getVariable("currency");

$dateDeliveredDay=0;
$dateDeliveredDay=getVariable("dateDeliveredDay");

$dateDeliveredMonth=0;
$dateDeliveredMonth=getVariable("dateDeliveredMonth");

$dateDeliveredYear=0;
$dateDeliveredYear=getVariable("dateDeliveredYear");

$fromDay=0;
$fromDay=getVariable("fromDay");

$fromMonth=0;
$fromMonth=getVariable("fromMonth");

$fromYear=0;
$fromYear=getVariable("fromYear");

$orderCallOffDateDay=0;
$orderCallOffDateDay=getVariable("orderCallOffDateDay");

$orderCallOffDateMonth=0;
$orderCallOffDateMonth=getVariable("orderCallOffDateMonth");

$orderCallOffDateYear=0;
$orderCallOffDateYear=getVariable("orderCallOffDateYear");

$ordertypegroupingID=0;
$ordertypegroupingID=getVariable("ordertypegroupingID");

$position=0;
$position=getVariable("position");

$primaryContactMobile=0;
$primaryContactMobile=getVariable("primaryContactMobile");

$reviewContractTerm=0;
$reviewContractTerm=getVariable("reviewContractTerm");

$reviewRecommendation=0;
$reviewRecommendation=getVariable("reviewRecommendation");

$reviewType=0;
$reviewType=getVariable("reviewType");

$searchFilter=0;
$searchFilter=getVariable("searchFilter");

$supplierContract=0;
$supplierContract=getVariable("supplierContract");

$supplierLeadTimeMetric=0;
$supplierLeadTimeMetric=getVariable("supplierLeadTimeMetric");

$supplierLeadTimeNumeric=0;
$supplierLeadTimeNumeric=getVariable("supplierLeadTimeNumeric");

$therapeuticgroupingID=0;
$therapeuticgroupingID=getVariable("therapeuticgroupingID");

$toDay=0;
$toDay=getVariable("toDay");

$toMonth=0;
$toMonth=getVariable("toMonth");

$toYear=0;
$toYear=getVariable("toYear");

$validFromDay=0;
$validFromDay=getVariable("validFromDay");

$validFromMonth=0;
$validFromMonth=getVariable("validFromMonth");

$validFromYear=0;
$validFromYear=getVariable("validFromYear");

$validToDay=0;
$validToDay=getVariable("validToDay");

$validToMonth=0;
$validToMonth=getVariable("validToMonth");

$validToYear=0;
$validToYear=getVariable("validToYear");

$vengroupingID=0;
$vengroupingID=getVariable("vengroupingID");

$vengroupingMinistryID=0;
$vengroupingMinistryID=getVariable("vengroupingMinistryID");

$vengroupingNMSID=0;
$vengroupingNMSID=getVariable("vengroupingNMSID");

$submitForm=0;
$submitForm=getVariable("submitForm");
//text type variables which likely contain HTML
$supplierAddress=0;
$supplierAddress=getVariableLessHTML("supplierAddress");

$reviewNotes=0;
$reviewNotes=getVariableLessHTML("reviewNotes");

$supplierCommentsField=0;
$supplierCommentsField=getVariableLessHTML("supplierCommentsField");

$deliveryComments=0;
$deliveryComments=getVariableLessHTML("deliveryComments");

$description=0;
$description=getVariableLessHTML("description");

$detaileddescription=0;
$detaileddescription=getVariableLessHTML("detaileddescription");

$supplierLeadingMessage=0;
$supplierLeadingMessage=getVariableLessHTML("supplierLeadingMessage");

$supplierTrailingMessage=0;
$supplierTrailingMessage=getVariableLessHTML("supplierTrailingMessage");

$reasonForDateChange=0;
$reasonForDateChange=getVariableLessHTML("reasonForDateChange");

//password type variables
$pass=0;
$pass=getVariableRAW("pass");

$currentpassword=0;
$currentpassword=getVariableRAW("currentpassword");

$newpassword2=0;
$newpassword2=getVariableRAW("newpassword2");

$newpassword=0;
$newpassword=getVariableRAW("newpassword");

$passwordsuccess=0;
$passwordsuccess=getVariableRAW("passwordsuccess");
?>