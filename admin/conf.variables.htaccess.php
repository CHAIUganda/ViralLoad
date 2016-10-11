<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* POST AND GET VARIABLES
*/

$a=0;
$a=getVariable("a");

$act=0;
$act=getVariable("act");

$activated=0;
$activated=getVariable("activated");

$appendix=0;
$appendix=getVariable("appendix");

$approveUpdatesOnCallOffOrder=0;
$approveUpdatesOnCallOffOrder=getVariable("approveUpdatesOnCallOffOrder");

$budgetAmount=0;
$budgetAmount=getVariable("budgetAmount");

$budgetCurrency=0;
$budgetCurrency=getVariable("budgetCurrency");

$button=0;
$button=getVariable("button");

$c_option=0;
$c_option=getVariable("c_option");

$checkall=0;
$checkall=getVariable("checkall");

$confirmCallOff=0;
$confirmCallOff=getVariable("confirmCallOff");

$confirmCallOffOrder=0;
$confirmCallOffOrder=getVariable("confirmCallOffOrder");

$contractManagersEmail=0;
$contractManagersEmail=getVariable("contractManagersEmail");

$corporationSecretaryName=0;
$corporationSecretaryName=getVariable("corporationSecretaryName");

$country=0;
$country=getVariable("country");

$createdby=0;
$createdby=getVariable("createdby");

$createCallOffOrder=0;
$createCallOffOrder=getVariable("createCallOffOrder");

$createOrder=0;
$createOrder=getVariable("createOrder");

$deactivated=0;
$deactivated=getVariable("deactivated");

$deliveries=0;
$deliveries=getVariable("deliveries");

$deliveriesAdditionalDeliveryDates=0;
$deliveriesAdditionalDeliveryDates=getVariable("deliveriesAdditionalDeliveryDates");

$deliveriesQuantityAccepted=0;
$deliveriesQuantityAccepted=getVariable("deliveriesQuantityAccepted");

$email=0;
$email=getVariable("email");

$emailID=0;
$emailID=getVariable("emailID");

$employeeContactTitle=0;
$employeeContactTitle=getVariable("employeeContactTitle");

$employeeContactEmail=0;
$employeeContactEmail=getVariable("employeeContactEmail");

$error=0;
$error=getVariable("error");

$export=0;
$export=getVariable("export");

$facilityID=0;
$facilityID=getVariable("facilityID");

$generalManagerName=0;
$generalManagerName=getVariable("generalManagerName");

$headFinanceName=0;
$headFinanceName=getVariable("headFinanceName");

$headProcurementName=0;
$headProcurementName=getVariable("headProcurementName");

$id=0;
$id=getVariable("id");

$items=0;
$items=getVariable("items");

$logChange=0;
$logChange=getVariable("logChange");

$logUpdatesOnCallOffOrder=0;
$logUpdatesOnCallOffOrder=getVariable("logUpdatesOnCallOffOrder");

$manageCallOffOrder=0;
$manageCallOffOrder=getVariable("manageCallOffOrder");

$manageContracts=0;
$manageContracts=getVariable("manageContracts");

$manageSuppliers=0;
$manageSuppliers=getVariable("manageSuppliers");

$modified=0;
$modified=getVariable("modified");

$modify=0;
$modify=getVariable("modify");

$names=0;
$names=getVariable("names");

$nav=0;
$nav=getVariable("nav");

$newFacilityID=0;
$newFacilityID=getVariable("newFacilityID");

$nms_name=0;
$nms_name=getVariable("nms_name");

$nms_pass=0;
$nms_pass=getVariable("nms_pass");

$option=0;
$option=getVariable("option");

$password=0;
$password=getVariable("password");

$phone=0;
$phone=getVariable("phone");

$printCallOffOrder=0;
$printCallOffOrder=getVariable("printCallOffOrder");

$removed=0;
$removed=getVariable("removed");

$reports=0;
$reports=getVariable("reports");

$REQ=0;
$REQ=getVariable("REQ");

$reviewDeliverySchedules=0;
$reviewDeliverySchedules=getVariable("reviewDeliverySchedules");

$saveContract=0;
$saveContract=getVariable("saveContract");

$saveContractAdd=0;
$saveContractAdd=getVariable("saveContractAdd");

$saveContractReview=0;
$saveContractReview=getVariable("saveContractReview");

$saved=0;
$saved=getVariable("saved");

$saveItem=0;
$saveItem=getVariable("saveItem");

$saveItems=0;
$saveItems=getVariable("saveItems");

$saveOrder=0;
$saveOrder=getVariable("saveOrder");

$scheduleReferenceNumberSubmit=0;
$scheduleReferenceNumberSubmit=getVariable("scheduleReferenceNumberSubmit");

$searchCallOffOrder=0;
$searchCallOffOrder=getVariable("searchCallOffOrder");

$searchContracts=0;
$searchContracts=getVariable("searchContracts");

$searchItems=0;
$searchItems=getVariable("searchItems");

$searchSuppliers=0;
$searchSuppliers=getVariable("searchSuppliers");

$showeditor=0;
$showeditor=getVariable("showeditor");

$signatoryName=0;
$signatoryName=getVariable("signatoryName");

$signatoryTitle=0;
$signatoryTitle=getVariable("signatoryTitle");

$soption=0;
$soption=getVariable("soption");

$subject=0;
$subject=getVariable("subject");

$Submit=0;
$Submit=getVariable("Submit");

$submitDetails=0;
$submitDetails=getVariable("submitDetails");

$task=0;
$task=getVariable("task");

$theDate=0;
$theDate=getVariable("theDate");

$theDeliveries=0;
$theDeliveries=getVariable("theDeliveries");

$theMonth=0;
$theMonth=getVariable("theMonth");

$theYear=0;
$theYear=getVariable("theYear");

$timezone=0;
$timezone=getVariable("timezone");

$town=0;
$town=getVariable("town");

$username=0;
$username=getVariable("username");

$x=0;
$x=getVariable("x");

$xdetails=0;
$xdetails=getVariable("xdetails");

$xmonth=0;
$xmonth=getVariable("xmonth");

$xyear=0;
$xyear=getVariable("xyear");

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

$uploadSuppliers=0;
$uploadSuppliers=getVariableLessHTML("uploadSuppliers");

$uploadContracts=0;
$uploadContracts=getVariableLessHTML("uploadContracts");

$uploadContractDeliverables=0;
$uploadContractDeliverables=getVariableLessHTML("uploadContractDeliverables");
?>