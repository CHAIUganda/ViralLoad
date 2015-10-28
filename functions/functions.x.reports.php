<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* REPORTS SPECIFIC FUNCTIONS: LOAD REPORT DATA
*/

/**
* update report summary
*/
function XupdateReportSummary($reportType,$from,$to,$version) {
	//globals
	global $institutionShortName;
	
	//variables
	$output=0;
	$output="";
	
	$objResponse = new vlDCResponse();
	switch($reportType) {
		case "BC":
			//update vl_profiles_bc with data from vl_profiles_j25
			$queryJ=0;
			$queryJ=mysqlquery("select * from vl_profiles_j25 where date(created)>='$from' and date(created)<='$to'");
			if(mysqlnumrows($queryJ)) {
				while($qJ=mysqlfetcharray($queryJ)) {
					$clientNumber=0;
					$clientNumber=getDetailedTableInfo2("vl_profiles_accounts","accountNumber='$qJ[destinationAccount]'","clientNumber");
					$ChequeAccountOpenedDate=0;
					$ChequeAccountOpenedDate=getFormattedDateCRB(getDetailedTableInfo2("vl_profiles_accounts","accountNumber='$qJ[destinationAccount]'","dateOpened"));
					$ChequeAccountBalanceAmount=0;
					$ChequeAccountBalanceAmount=getDetailedTableInfo2("vl_profiles_accounts_balances","accountNumber='$qJ[destinationAccount]' and dateLogged='".getRawFormattedDateLessDay($qJ["created"])."'","balance");
					if($clientNumber) {
						$BranchIdentificationCode=0;
						$BranchIdentificationCode=getDetailedTableInfo2("vl_profiles_customers","clientNumber='$clientNumber'","branchCode");
						$PIClientClassification=0;
						$PIClientClassification=getDetailedTableInfo2("vl_profiles_customers","clientNumber='$clientNumber'","classificationCode");
						//avoid duplicate entries
						if(!isQuery("select * from vl_profiles_bc where 
										version='$version' and 
										ClientNumber='$clientNumber' and 
										BranchIdentificationCode='$BranchIdentificationCode' and 
										PIClientClassification='$PIClientClassification' and 
										ChequeAccountReferenceNumber='$qJ[destinationAccount]' and 
										ChequeAccountOpenedDate='$ChequeAccountOpenedDate' and 
										ChequeAccountClassification='$PIClientClassification' and 
										ChequeAccountType='$PIClientClassification' and 
										ChequeAccountBalanceAmount='$ChequeAccountBalanceAmount' and 
										BalanceIndicator='".($ChequeAccountBalanceAmount>=0?getDetailedTableInfo2("vl_appendix_debit","appendixvalue like '%credit%' and version='$version'","appendixcode"):getDetailedTableInfo2("vl_appendix_debit","appendixvalue like '%debit%' and version='$version'","appendixcode"))."' and 
										ChequeNumber='$qJ[processingNumber]' and 
										ChequeAmount='$qJ[amount]' and 
										ChequeCurrency='$qJ[currency]' and 
										BeneficiaryNameOrPayee='$qJ[beneficiaryName]' and 
										ChequeBounceDate='".getFormattedDateCRB($qJ["created"])."' and 
										ChequeAccountBounceReason='".getDetailedTableInfo2("vl_appendix_chequeaccountbouncereason_bou","appendixcode='$qJ[recordType]' and version='$version'","matchingCRBCode")."' and 
										created='$qJ[created]' and 
										createdby='$qJ[createdby]'")) {
								//insert data
								mysqlquery("insert into vl_profiles_bc 
												(version,ClientNumber,BranchIdentificationCode,
												PIClientClassification,ChequeAccountReferenceNumber,ChequeAccountOpenedDate,ChequeAccountClassification,
												ChequeAccountType,ChequeAccountBalanceAmount,BalanceIndicator,ChequeNumber,
												ChequeAmount,ChequeCurrency,BeneficiaryNameOrPayee,ChequeBounceDate,
												ChequeAccountBounceReason,created,createdby) 
												values 
												('$version','$clientNumber','$BranchIdentificationCode',
												'$PIClientClassification','$qJ[destinationAccount]','$ChequeAccountOpenedDate','$PIClientClassification',
												'$PIClientClassification','$ChequeAccountBalanceAmount','".($ChequeAccountBalanceAmount>=0?getDetailedTableInfo2("vl_appendix_debit","appendixvalue like '%credit%' and version='$version'","appendixcode"):getDetailedTableInfo2("vl_appendix_debit","appendixvalue like '%debit%' and version='$version'","appendixcode"))."','$qJ[processingNumber]',
												'$qJ[amount]','$qJ[currency]','$qJ[beneficiaryName]','".getFormattedDateCRB($qJ["created"])."',
												'".getDetailedTableInfo2("vl_appendix_chequeaccountbouncereason_bou","appendixcode='$qJ[recordType]'","matchingCRBCode")."','$qJ[created]','$qJ[createdby]')");
						}
					}
				}
			}
			
			$output="
				<strong>Report Summary:</strong><br /><br />
				<strong>File Type:</strong> $reportType<br />
				<strong>Period:</strong> ".getFormattedDateLessDaySlash($from)." to ".getFormattedDateLessDaySlash($to)."<br />
				<strong>Total J File Records:</strong> ".number_format((float)getDetailedTableInfo3("vl_profiles_j25","created>='$from' and created<='$to'","count(*)","num"))."<br>
				<strong>Total BC Records:</strong> ".number_format((float)getDetailedTableInfo3("vl_profiles_customers,vl_profiles_bc","vl_profiles_bc.ClientNumber=vl_profiles_customers.clientNumber and 
																					vl_profiles_bc.ChequeBounceDate>='".getFormattedDateCRB($from)."' and 
																					vl_profiles_bc.ChequeBounceDate<='".getFormattedDateCRB($to)."'","count(distinct vl_profiles_bc.ClientNumber,vl_profiles_bc.ChequeNumber)","num"));
			$objResponse->addAssign("summaryID","innerHTML",$output);
		break;
		case "CAP":
			$output="
				<strong>Report Summary:</strong><br /><br />
				<strong>File Type:</strong> $reportType<br />
				<strong>Period:</strong> ".getFormattedDateLessDaySlash($from)." to ".getFormattedDateLessDaySlash($to)."<br />
				<strong>Total Records:</strong> ".number_format((float)getDetailedTableInfo3("vl_profiles_customers,vl_profiles_loans","vl_profiles_loans.ClientNumber=vl_profiles_customers.clientNumber and 
																							(vl_profiles_loans.CreditApplicationDate!='0' and vl_profiles_loans.CreditApplicationDate!='' and vl_profiles_loans.CreditApplicationDate>='".getFormattedDateCRB($from)."' and vl_profiles_loans.CreditApplicationDate<='".getFormattedDateCRB($to)."') or 
																							(date(vl_profiles_loans.created)>='".getRawFormattedDateLessDay($from)."' and date(vl_profiles_loans.created)<='".getRawFormattedDateLessDay($to)."') and 
																							substring(vl_profiles_loans.CreditApplicationReference,1,".strlen($institutionShortName).")='$institutionShortName'","count(distinct vl_profiles_loans.ClientNumber,vl_profiles_loans.CreditApplicationReference,vl_profiles_loans.CreditApplicationStatus)","num"));
			$objResponse->addAssign("summaryID","innerHTML",$output);
		break;
		case "CBA":
			$output="
				<strong>Report Summary:</strong><br /><br />
				<strong>File Type:</strong> $reportType<br />
				<strong>Period:</strong> To ".getFormattedDateLessDaySlash($to)."<br />
				<strong>Total Records:</strong> ".number_format((float)getDetailedTableInfo3("vl_profiles_customers,vl_profiles_loans","vl_profiles_loans.ClientNumber=vl_profiles_customers.clientNumber and 
																					((vl_profiles_loans.CreditAccountClosureDate>='".getFormattedDateYear($to).getFormattedDateMonth($to)."01' and vl_profiles_loans.CreditAccountClosureDate<='$to') or 
																					(vl_profiles_loans.DateSettled>='".getFormattedDateYear($to).getFormattedDateMonth($to)."01' and vl_profiles_loans.DateSettled<='$to') or 
																					vl_profiles_loans.CreditAccountClosureDate='' or 
																					vl_profiles_loans.DateSettled='' or 
																					vl_profiles_loans.CreditAccountClosureReason='') and 
																					vl_profiles_loans.CreditApplicationStatus='".getDetailedTableInfo2("vl_appendix_creditapplicationstatus","appendixvalue like '%approved%' and version='$version'","appendixcode")."' and 
																					substring(vl_profiles_loans.CreditApplicationReference,1,".strlen($institutionShortName).")!='$institutionShortName'","count(distinct vl_profiles_loans.ClientNumber,vl_profiles_loans.CreditApplicationReference)","num"));
			$objResponse->addAssign("summaryID","innerHTML",$output);
		break;
		case "FRA":
			$output="
				<strong>Report Summary:</strong><br /><br />
				<strong>File Type:</strong> $reportType<br />
				<strong>Period:</strong> ".getFormattedDateLessDaySlash($from)." to ".getFormattedDateLessDaySlash($to)."<br />
				<strong>Total Records:</strong> ".number_format((float)getDetailedTableInfo3("vl_profiles_fra","date(created)>='$from' and date(created)<='$to'","count(id)","num"));
			$objResponse->addAssign("summaryID","innerHTML",$output);
		break;
		case "BS":
			$output="
				<strong>Report Summary:</strong><br /><br />
				<strong>File Type:</strong> $reportType<br />
				<strong>Period:</strong> To ".getFormattedDateLessDaySlash($to)."<br />
				<strong>Total Records:</strong> ".number_format((float)getDetailedTableInfo3("vl_profiles_customers,vl_profiles_bs,vl_profiles_loans","vl_profiles_bs.BorrowerClientNumber=vl_profiles_customers.clientNumber and 
																					vl_profiles_loans.ClientNumber=vl_profiles_bs.BorrowerClientNumber and 
																					vl_profiles_loans.CreditApplicationReference=vl_profiles_bs.BorrowerAccountReference and 
																					((vl_profiles_loans.CreditAccountClosureDate>='".getFormattedDateYear($to).getFormattedDateMonth($to)."01' and vl_profiles_loans.CreditAccountClosureDate<='$to') or 
																					(vl_profiles_loans.DateSettled>='".getFormattedDateYear($to).getFormattedDateMonth($to)."01' and vl_profiles_loans.DateSettled<='$to') or 
																					vl_profiles_loans.CreditAccountClosureDate='' or 
																					vl_profiles_loans.DateSettled='' or 
																					vl_profiles_loans.CreditAccountClosureReason='') and 
																					vl_profiles_loans.CreditApplicationStatus='".getDetailedTableInfo2("vl_appendix_creditapplicationstatus","appendixvalue like '%approved%' and version='$version'","appendixcode")."' and 
																					substring(vl_profiles_loans.CreditApplicationReference,1,".strlen($institutionShortName).")!='$institutionShortName'","count(distinct vl_profiles_bs.BorrowerClientNumber,vl_profiles_bs.BorrowerAccountReference,vl_profiles_bs.StakeholderClientNumber)","num"));
			$objResponse->addAssign("summaryID","innerHTML",$output);
		break;
		case "CCG":
			$output="
				<strong>Report Summary:</strong><br /><br />
				<strong>File Type:</strong> $reportType<br />
				<strong>Period:</strong> To ".getFormattedDateLessDaySlash($to)."<br />
				<strong>Total Records:</strong> ".number_format((float)getDetailedTableInfo3("vl_profiles_customers,vl_profiles_ccg,vl_profiles_loans","vl_profiles_ccg.BorrowerClientNumber=vl_profiles_customers.clientNumber and 
																					vl_profiles_loans.ClientNumber=vl_profiles_ccg.BorrowerClientNumber and 
																					vl_profiles_loans.CreditApplicationReference=vl_profiles_ccg.BorrowerAccountReference and 
																					((vl_profiles_loans.CreditAccountClosureDate>='".getFormattedDateYear($to).getFormattedDateMonth($to)."01' and vl_profiles_loans.CreditAccountClosureDate<='$to') or 
																					(vl_profiles_loans.DateSettled>='".getFormattedDateYear($to).getFormattedDateMonth($to)."01' and vl_profiles_loans.DateSettled<='$to') or 
																					vl_profiles_loans.CreditAccountClosureDate='' or 
																					vl_profiles_loans.DateSettled='' or 
																					vl_profiles_loans.CreditAccountClosureReason='') and 
																					vl_profiles_loans.CreditApplicationStatus='".getDetailedTableInfo2("vl_appendix_creditapplicationstatus","appendixvalue like '%approved%' and version='$version'","appendixcode")."' and 
																					substring(vl_profiles_loans.CreditApplicationReference,1,".strlen($institutionShortName).")!='$institutionShortName'","count(distinct vl_profiles_ccg.BorrowerClientNumber,vl_profiles_ccg.BorrowerAccountReference,vl_profiles_ccg.GuarantorClientNumber)","num"));
			$objResponse->addAssign("summaryID","innerHTML",$output);
		break;
		case "CMC":
			$output="
				<strong>Report Summary:</strong><br /><br />
				<strong>File Type:</strong> $reportType<br />
				<strong>Period:</strong> To ".getFormattedDateLessDaySlash($to)."<br />
				<strong>Total Records:</strong> ".number_format((float)getDetailedTableInfo3("vl_profiles_customers,vl_profiles_cmc,vl_profiles_loans","vl_profiles_cmc.BorrowerClientNumber=vl_profiles_customers.clientNumber and 
																					vl_profiles_loans.ClientNumber=vl_profiles_cmc.BorrowerClientNumber and 
																					vl_profiles_loans.CreditApplicationReference=vl_profiles_cmc.BorrowerAccountReference and 
																					((vl_profiles_loans.CreditAccountClosureDate>='".getFormattedDateYear($to).getFormattedDateMonth($to)."01' and vl_profiles_loans.CreditAccountClosureDate<='$to') or 
																					(vl_profiles_loans.DateSettled>='".getFormattedDateYear($to).getFormattedDateMonth($to)."01' and vl_profiles_loans.DateSettled<='$to') or 
																					vl_profiles_loans.CreditAccountClosureDate='' or 
																					vl_profiles_loans.DateSettled='' or 
																					vl_profiles_loans.CreditAccountClosureReason='') and 
																					vl_profiles_loans.CreditApplicationStatus='".getDetailedTableInfo2("vl_appendix_creditapplicationstatus","appendixvalue like '%approved%' and version='$version'","appendixcode")."' and 
																					substring(vl_profiles_loans.CreditApplicationReference,1,".strlen($institutionShortName).")!='$institutionShortName'","count(distinct vl_profiles_cmc.BorrowerClientNumber,vl_profiles_cmc.BorrowerAccountReference,vl_profiles_cmc.CollateralReferenceNr)","num"));
			$objResponse->addAssign("summaryID","innerHTML",$output);
		break;
		case "IB":
			$output="
				<strong>Report Summary:</strong><br /><br />
				<strong>File Type:</strong> $reportType<br />
				<strong>Total Branches:</strong> ".number_format((float)getDetailedTableInfo3("vl_profiles_ib","id!=''","count(id)","num"));
			$objResponse->addAssign("summaryID","innerHTML",$output);
		break;
		case "PI":
			$output="
				<strong>Report Summary:</strong><br /><br />
				<strong>File Type:</strong> $reportType<br />
				<strong>Total Records:</strong> ".number_format((float)getDetailedTableInfo3("vl_profiles_pi","id!=''","count(id)","num"));
			$objResponse->addAssign("summaryID","innerHTML",$output);
		break;
		case "PIS":
			$output="
				<strong>Report Summary:</strong><br /><br />
				<strong>File Type:</strong> $reportType<br />
				<strong>Total Records:</strong> ".number_format((float)getDetailedTableInfo3("vl_profiles_pis","id!=''","count(distinct ClientNumber)","num"));
			$objResponse->addAssign("summaryID","innerHTML",$output);
		break;
	}
	return $objResponse->getXML();
}
?>