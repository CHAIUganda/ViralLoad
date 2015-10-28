<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

/**
* USER SPECIFIC FUNCTIONS: LOAD USER DATA
*/

/**
* function to check the client number
* @param: $clientnumber
* @param: $formname
* @param: $id
* @param: $customertype
* @param: $version
*/
function XcheckClientNumber($clientNumber,$formname,$id,$customerType,$version) {
	global $datetime,$trailSessionUser;
	
	//if no id
	if(!$id) {
		$id="";
	}
	
	//flag
	$datafound=0;
    
    //prepare all relevant variables
    $II_RegistrationCertificateNumber=0;
    $II_TaxIdentificationNumber=0;
    $II_ValueAddedTaxNumber=0;
    $II_FCSNumber=0;
    $II_PassportNumber=0;
    $II_DriversLicenceIDNumber=0;
    $II_VotersPERNO=0;
    $II_DriversLicensePermitNumber=0;
    $II_NSSFNumber=0;
    $II_CountryID=0;
    $II_CountryIssuingAuthority=0;
    $II_Nationality=0;
    $II_PoliceIDNumber=0;
    $II_UPDFNumber=0;
    $II_KACITALicenseNumber=0;
    $II_CountryOfIssue=0;
    $GSCAFB_BusinessName=0;
    $GSCAFB_TradingName=0;
    $GSCAFB_ActivityDescription=0;
    $GSCAFB_IndustrySectorCode=0;
    $GSCAFB_DateRegistered=0;
    $GSCAFB_BusinessTypeCode=0;
    $GSCAFB_Surname=0;
    $GSCAFB_Forename1=0;
    $GSCAFB_Forename2=0;
    $GSCAFB_Forename3=0;
    $GSCAFB_Gender=0;
    $GSCAFB_MaritalStatus=0;
    $GSCAFB_DateofBirth=0;
    $EI_EmploymentType=0;
    $EI_PrimaryOccupation=0;
    $EI_EmployerName=0;
    $EI_EmployeeNumber=0;
    $EI_PeriodAtEmployer=0;
    $EI_IncomeBand=0;
    $EI_SalaryFrequency=0;
    $PCI_UnitNumber=0;
    $PCI_UnitName=0;
    $PCI_FloorNumber=0;
    $PCI_PlotorStreetNumber=0;
    $PCI_LCorStreetName=0;
    $PCI_Parish=0;
    $PCI_Suburb=0;
    $PCI_Village=0;
    $PCI_CountyorTown=0;
    $PCI_District=0;
    $PCI_Region=0;
    $PCI_POBoxNumber=0;
    $PCI_PostOfficeTown=0;
    $PCI_CountryCode=0;
    $PCI_PeriodAtAddress=0;
    $PCI_FlagofOwnership=0;
    $PCI_PrimaryCountryDiallingCode=0;
    $PCI_PrimaryAreaDiallingCode=0;
    $PCI_PrimaryTelephoneNumber=0;
    $PCI_OtherCountryDiallingCode=0;
    $PCI_OtherAreaDiallingCode=0;
    $PCI_OtherTelephoneNumber=0;
    $PCI_MobileCountryDiallingCode=0;
    $PCI_MobileAreaDiallingCode=0;
    $PCI_MobileNumber=0;
    $PCI_FAXCountryDiallingCode=0;
    $PCI_FAXAreaDiallingCode=0;
    $PCI_FAXNumber=0;
    $PCI_EmailAddress=0;
    $PCI_Website=0;
    $SCI_UnitNumber=0;
    $SCI_UnitName=0;
    $SCI_FloorNumber=0;
    $SCI_PlotorStreetNumber=0;
    $SCI_LCorStreetName=0;
    $SCI_Parish=0;
    $SCI_Suburb=0;
    $SCI_Village=0;
    $SCI_CountyorTown=0;
    $SCI_District=0;
    $SCI_Region=0;
    $SCI_POBoxNumber=0;
    $SCI_PostOfficeTown=0;
    $SCI_CountryCode=0;
    $SCI_PeriodAtAddress=0;
    $SCI_FlagofOwnership=0;
    $SCI_PrimaryCountryDiallingCode=0;
    $SCI_PrimaryAreaDiallingCode=0;
    $SCI_PrimaryTelephoneNumber=0;
    $SCI_OtherCountryDiallingCode=0;
    $SCI_OtherAreaDiallingCode=0;
    $SCI_OtherTelephoneNumber=0;
    $SCI_MobileCountryDiallingCode=0;
    $SCI_MobileAreaDiallingCode=0;
    $SCI_MobileNumber=0;
    $SCI_FAXCountryDiallingCode=0;
    $SCI_FAXAreaDiallingCode=0;
    $SCI_FAXNumber=0;
    $SCI_EmailAddress=0;
    $SCI_Website=0;
    
	//start by searching clientNumber in vl_profile_customers
	$query=0;
	$query=mysqlquery("select * from vl_profiles_customers where clientNumber='$clientNumber' order by created desc");
	if(mysqlnumrows($query)) {
		//flag
		$datafound=1;
        //get data
        $q=array();
        while($q=mysqlfetcharray($query)) {
            $II_RegistrationCertificateNumber=($II_RegistrationCertificateNumber?$II_RegistrationCertificateNumber:$q["II_RegistrationCertificateNumber"]);
            $II_TaxIdentificationNumber=($II_TaxIdentificationNumber?$II_TaxIdentificationNumber:$q["II_TaxIdentificationNumber"]);
            $II_ValueAddedTaxNumber=($II_ValueAddedTaxNumber?$II_ValueAddedTaxNumber:$q["II_ValueAddedTaxNumber"]);
            $II_FCSNumber=($II_FCSNumber?$II_FCSNumber:$q["II_FCSNumber"]);
            $II_PassportNumber=($II_PassportNumber?$II_PassportNumber:$q["II_PassportNumber"]);
            $II_DriversLicenceIDNumber=($II_DriversLicenceIDNumber?$II_DriversLicenceIDNumber:$q["II_DriversLicenceIDNumber"]);
            $II_VotersPERNO=($II_VotersPERNO?$II_VotersPERNO:$q["II_VotersPERNO"]);
            $II_DriversLicensePermitNumber=($II_DriversLicensePermitNumber?$II_DriversLicensePermitNumber:$q["II_DriversLicensePermitNumber"]);
            $II_NSSFNumber=($II_NSSFNumber?$II_NSSFNumber:$q["II_NSSFNumber"]);
            $II_CountryID=($II_CountryID?$II_CountryID:$q["II_CountryID"]);
            $II_CountryIssuingAuthority=($II_CountryIssuingAuthority?$II_CountryIssuingAuthority:$q["II_CountryIssuingAuthority"]);
            $II_Nationality=($II_Nationality?$II_Nationality:$q["II_Nationality"]);
            $II_PoliceIDNumber=($II_PoliceIDNumber?$II_PoliceIDNumber:$q["II_PoliceIDNumber"]);
            $II_UPDFNumber=($II_UPDFNumber?$II_UPDFNumber:$q["II_UPDFNumber"]);
            $II_KACITALicenseNumber=($II_KACITALicenseNumber?$II_KACITALicenseNumber:$q["II_KACITALicenseNumber"]);
            $II_CountryOfIssue=($II_CountryOfIssue?$II_CountryOfIssue:$q["II_CountryOfIssue"]);
            $GSCAFB_BusinessName=($GSCAFB_BusinessName?$GSCAFB_BusinessName:$q["GSCAFB_BusinessName"]);
            $GSCAFB_TradingName=($GSCAFB_TradingName?$GSCAFB_TradingName:$q["GSCAFB_TradingName"]);
            $GSCAFB_ActivityDescription=($GSCAFB_ActivityDescription?$GSCAFB_ActivityDescription:$q["GSCAFB_ActivityDescription"]);
            $GSCAFB_IndustrySectorCode=($GSCAFB_IndustrySectorCode?$GSCAFB_IndustrySectorCode:$q["GSCAFB_IndustrySectorCode"]);
            $GSCAFB_DateRegistered=($GSCAFB_DateRegistered?$GSCAFB_DateRegistered:$q["GSCAFB_DateRegistered"]);
            $GSCAFB_BusinessTypeCode=($GSCAFB_BusinessTypeCode?$GSCAFB_BusinessTypeCode:$q["GSCAFB_BusinessTypeCode"]);
            $GSCAFB_Surname=($GSCAFB_Surname?$GSCAFB_Surname:$q["GSCAFB_Surname"]);
            $GSCAFB_Forename1=($GSCAFB_Forename1?$GSCAFB_Forename1:$q["GSCAFB_Forename1"]);
            $GSCAFB_Forename2=($GSCAFB_Forename2?$GSCAFB_Forename2:$q["GSCAFB_Forename2"]);
            $GSCAFB_Forename3=($GSCAFB_Forename3?$GSCAFB_Forename3:$q["GSCAFB_Forename3"]);
            $GSCAFB_Gender=($GSCAFB_Gender?$GSCAFB_Gender:$q["GSCAFB_Gender"]);
            $GSCAFB_MaritalStatus=($GSCAFB_MaritalStatus?$GSCAFB_MaritalStatus:$q["GSCAFB_MaritalStatus"]);
            $GSCAFB_DateofBirth=($GSCAFB_DateofBirth?$GSCAFB_DateofBirth:$q["GSCAFB_DateofBirth"]);
            $EI_EmploymentType=($EI_EmploymentType?$EI_EmploymentType:$q["EI_EmploymentType"]);
            $EI_PrimaryOccupation=($EI_PrimaryOccupation?$EI_PrimaryOccupation:$q["EI_PrimaryOccupation"]);
            $EI_EmployerName=($EI_EmployerName?$EI_EmployerName:$q["EI_EmployerName"]);
            $EI_EmployeeNumber=($EI_EmployeeNumber?$EI_EmployeeNumber:$q["EI_EmployeeNumber"]);
            $EI_PeriodAtEmployer=($EI_PeriodAtEmployer?$EI_PeriodAtEmployer:$q["EI_PeriodAtEmployer"]);
            $EI_IncomeBand=($EI_IncomeBand?$EI_IncomeBand:$q["EI_IncomeBand"]);
            $EI_SalaryFrequency=($EI_SalaryFrequency?$EI_SalaryFrequency:$q["EI_SalaryFrequency"]);
            $PCI_UnitNumber=($PCI_UnitNumber?$PCI_UnitNumber:$q["PCI_UnitNumber"]);
            $PCI_UnitName=($PCI_UnitName?$PCI_UnitName:$q["PCI_UnitName"]);
            $PCI_FloorNumber=($PCI_FloorNumber?$PCI_FloorNumber:$q["PCI_FloorNumber"]);
            $PCI_PlotorStreetNumber=($PCI_PlotorStreetNumber?$PCI_PlotorStreetNumber:$q["PCI_PlotorStreetNumber"]);
            $PCI_LCorStreetName=($PCI_LCorStreetName?$PCI_LCorStreetName:$q["PCI_LCorStreetName"]);
            $PCI_Parish=($PCI_Parish?$PCI_Parish:$q["PCI_Parish"]);
            $PCI_Suburb=($PCI_Suburb?$PCI_Suburb:$q["PCI_Suburb"]);
            $PCI_Village=($PCI_Village?$PCI_Village:$q["PCI_Village"]);
            $PCI_CountyorTown=($PCI_CountyorTown?$PCI_CountyorTown:$q["PCI_CountyorTown"]);
            $PCI_District=($PCI_District?$PCI_District:$q["PCI_District"]);
            $PCI_Region=($PCI_Region?$PCI_Region:$q["PCI_Region"]);
            $PCI_POBoxNumber=($PCI_POBoxNumber?$PCI_POBoxNumber:$q["PCI_POBoxNumber"]);
            $PCI_PostOfficeTown=($PCI_PostOfficeTown?$PCI_PostOfficeTown:$q["PCI_PostOfficeTown"]);
            $PCI_CountryCode=($PCI_CountryCode?$PCI_CountryCode:$q["PCI_CountryCode"]);
            $PCI_PeriodAtAddress=($PCI_PeriodAtAddress?$PCI_PeriodAtAddress:$q["PCI_PeriodAtAddress"]);
            $PCI_FlagofOwnership=($PCI_FlagofOwnership?$PCI_FlagofOwnership:$q["PCI_FlagofOwnership"]);
            $PCI_PrimaryCountryDiallingCode=($PCI_PrimaryCountryDiallingCode?$PCI_PrimaryCountryDiallingCode:$q["PCI_PrimaryCountryDiallingCode"]);
            $PCI_PrimaryAreaDiallingCode=($PCI_PrimaryAreaDiallingCode?$PCI_PrimaryAreaDiallingCode:$q["PCI_PrimaryAreaDiallingCode"]);
            $PCI_PrimaryTelephoneNumber=($PCI_PrimaryTelephoneNumber?$PCI_PrimaryTelephoneNumber:$q["PCI_PrimaryTelephoneNumber"]);
            $PCI_OtherCountryDiallingCode=($PCI_OtherCountryDiallingCode?$PCI_OtherCountryDiallingCode:$q["PCI_OtherCountryDiallingCode"]);
            $PCI_OtherAreaDiallingCode=($PCI_OtherAreaDiallingCode?$PCI_OtherAreaDiallingCode:$q["PCI_OtherAreaDiallingCode"]);
            $PCI_OtherTelephoneNumber=($PCI_OtherTelephoneNumber?$PCI_OtherTelephoneNumber:$q["PCI_OtherTelephoneNumber"]);
            $PCI_MobileCountryDiallingCode=($PCI_MobileCountryDiallingCode?$PCI_MobileCountryDiallingCode:$q["PCI_MobileCountryDiallingCode"]);
            $PCI_MobileAreaDiallingCode=($PCI_MobileAreaDiallingCode?$PCI_MobileAreaDiallingCode:$q["PCI_MobileAreaDiallingCode"]);
            $PCI_MobileNumber=($PCI_MobileNumber?$PCI_MobileNumber:$q["PCI_MobileNumber"]);
            $PCI_FAXCountryDiallingCode=($PCI_FAXCountryDiallingCode?$PCI_FAXCountryDiallingCode:$q["PCI_FAXCountryDiallingCode"]);
            $PCI_FAXAreaDiallingCode=($PCI_FAXAreaDiallingCode?$PCI_FAXAreaDiallingCode:$q["PCI_FAXAreaDiallingCode"]);
            $PCI_FAXNumber=($PCI_FAXNumber?$PCI_FAXNumber:$q["PCI_FAXNumber"]);
            $PCI_EmailAddress=($PCI_EmailAddress?$PCI_EmailAddress:$q["PCI_EmailAddress"]);
            $PCI_Website=($PCI_Website?$PCI_Website:$q["PCI_Website"]);
            $SCI_UnitNumber=($SCI_UnitNumber?$SCI_UnitNumber:$q["SCI_UnitNumber"]);
            $SCI_UnitName=($SCI_UnitName?$SCI_UnitName:$q["SCI_UnitName"]);
            $SCI_FloorNumber=($SCI_FloorNumber?$SCI_FloorNumber:$q["SCI_FloorNumber"]);
            $SCI_PlotorStreetNumber=($SCI_PlotorStreetNumber?$SCI_PlotorStreetNumber:$q["SCI_PlotorStreetNumber"]);
            $SCI_LCorStreetName=($SCI_LCorStreetName?$SCI_LCorStreetName:$q["SCI_LCorStreetName"]);
            $SCI_Parish=($SCI_Parish?$SCI_Parish:$q["SCI_Parish"]);
            $SCI_Suburb=($SCI_Suburb?$SCI_Suburb:$q["SCI_Suburb"]);
            $SCI_Village=($SCI_Village?$SCI_Village:$q["SCI_Village"]);
            $SCI_CountyorTown=($SCI_CountyorTown?$SCI_CountyorTown:$q["SCI_CountyorTown"]);
            $SCI_District=($SCI_District?$SCI_District:$q["SCI_District"]);
            $SCI_Region=($SCI_Region?$SCI_Region:$q["SCI_Region"]);
            $SCI_POBoxNumber=($SCI_POBoxNumber?$SCI_POBoxNumber:$q["SCI_POBoxNumber"]);
            $SCI_PostOfficeTown=($SCI_PostOfficeTown?$SCI_PostOfficeTown:$q["SCI_PostOfficeTown"]);
            $SCI_CountryCode=($SCI_CountryCode?$SCI_CountryCode:$q["SCI_CountryCode"]);
            $SCI_PeriodAtAddress=($SCI_PeriodAtAddress?$SCI_PeriodAtAddress:$q["SCI_PeriodAtAddress"]);
            $SCI_FlagofOwnership=($SCI_FlagofOwnership?$SCI_FlagofOwnership:$q["SCI_FlagofOwnership"]);
            $SCI_PrimaryCountryDiallingCode=($SCI_PrimaryCountryDiallingCode?$SCI_PrimaryCountryDiallingCode:$q["SCI_PrimaryCountryDiallingCode"]);
            $SCI_PrimaryAreaDiallingCode=($SCI_PrimaryAreaDiallingCode?$SCI_PrimaryAreaDiallingCode:$q["SCI_PrimaryAreaDiallingCode"]);
            $SCI_PrimaryTelephoneNumber=($SCI_PrimaryTelephoneNumber?$SCI_PrimaryTelephoneNumber:$q["SCI_PrimaryTelephoneNumber"]);
            $SCI_OtherCountryDiallingCode=($SCI_OtherCountryDiallingCode?$SCI_OtherCountryDiallingCode:$q["SCI_OtherCountryDiallingCode"]);
            $SCI_OtherAreaDiallingCode=($SCI_OtherAreaDiallingCode?$SCI_OtherAreaDiallingCode:$q["SCI_OtherAreaDiallingCode"]);
            $SCI_OtherTelephoneNumber=($SCI_OtherTelephoneNumber?$SCI_OtherTelephoneNumber:$q["SCI_OtherTelephoneNumber"]);
            $SCI_MobileCountryDiallingCode=($SCI_MobileCountryDiallingCode?$SCI_MobileCountryDiallingCode:$q["SCI_MobileCountryDiallingCode"]);
            $SCI_MobileAreaDiallingCode=($SCI_MobileAreaDiallingCode?$SCI_MobileAreaDiallingCode:$q["SCI_MobileAreaDiallingCode"]);
            $SCI_MobileNumber=($SCI_MobileNumber?$SCI_MobileNumber:$q["SCI_MobileNumber"]);
            $SCI_FAXCountryDiallingCode=($SCI_FAXCountryDiallingCode?$SCI_FAXCountryDiallingCode:$q["SCI_FAXCountryDiallingCode"]);
            $SCI_FAXAreaDiallingCode=($SCI_FAXAreaDiallingCode?$SCI_FAXAreaDiallingCode:$q["SCI_FAXAreaDiallingCode"]);
            $SCI_FAXNumber=($SCI_FAXNumber?$SCI_FAXNumber:$q["SCI_FAXNumber"]);
            $SCI_EmailAddress=($SCI_EmailAddress?$SCI_EmailAddress:$q["SCI_EmailAddress"]);
            $SCI_Website=($SCI_Website?$SCI_Website:$q["SCI_Website"]);
        }
	}
    
	//then search ClientNumber in vl_datacap111
	$query=0;
	$query=mysqlquery("select * from vl_datacap111 where ClientNumber='$clientNumber' order by created desc");
	if(mysqlnumrows($query)) {
		//flag
		$datafound=1;
        //get data
        $q=array();
        while($q=mysqlfetcharray($query)) {
            $II_RegistrationCertificateNumber=($II_RegistrationCertificateNumber?$II_RegistrationCertificateNumber:$q["II_RegistrationCertificateNumber"]);
            $II_TaxIdentificationNumber=($II_TaxIdentificationNumber?$II_TaxIdentificationNumber:$q["II_TaxIdentificationNumber"]);
            $II_ValueAddedTaxNumber=($II_ValueAddedTaxNumber?$II_ValueAddedTaxNumber:$q["II_ValueAddedTaxNumber"]);
            $II_FCSNumber=($II_FCSNumber?$II_FCSNumber:$q["II_FCSNumber"]);
            $II_PassportNumber=($II_PassportNumber?$II_PassportNumber:$q["II_PassportNumber"]);
            $II_DriversLicenceIDNumber=($II_DriversLicenceIDNumber?$II_DriversLicenceIDNumber:$q["II_DriversLicenceIDNumber"]);
            $II_VotersPERNO=($II_VotersPERNO?$II_VotersPERNO:$q["II_VotersPERNO"]);
            $II_DriversLicensePermitNumber=($II_DriversLicensePermitNumber?$II_DriversLicensePermitNumber:$q["II_DriversLicensePermitNumber"]);
            $II_NSSFNumber=($II_NSSFNumber?$II_NSSFNumber:$q["II_NSSFNumber"]);
            $II_CountryID=($II_CountryID?$II_CountryID:$q["II_CountryID"]);
            $II_CountryIssuingAuthority=($II_CountryIssuingAuthority?$II_CountryIssuingAuthority:$q["II_CountryIssuingAuthority"]);
            $II_Nationality=($II_Nationality?$II_Nationality:$q["II_Nationality"]);
            $II_PoliceIDNumber=($II_PoliceIDNumber?$II_PoliceIDNumber:$q["II_PoliceIDNumber"]);
            $II_UPDFNumber=($II_UPDFNumber?$II_UPDFNumber:$q["II_UPDFNumber"]);
            $II_KACITALicenseNumber=($II_KACITALicenseNumber?$II_KACITALicenseNumber:$q["II_KACITALicenseNumber"]);
            $II_CountryOfIssue=($II_CountryOfIssue?$II_CountryOfIssue:$q["II_CountryOfIssue"]);
            $GSCAFB_BusinessName=($GSCAFB_BusinessName?$GSCAFB_BusinessName:$q["GSCAFB_BusinessName"]);
            $GSCAFB_TradingName=($GSCAFB_TradingName?$GSCAFB_TradingName:$q["GSCAFB_TradingName"]);
            $GSCAFB_ActivityDescription=($GSCAFB_ActivityDescription?$GSCAFB_ActivityDescription:$q["GSCAFB_ActivityDescription"]);
            $GSCAFB_IndustrySectorCode=($GSCAFB_IndustrySectorCode?$GSCAFB_IndustrySectorCode:$q["GSCAFB_IndustrySectorCode"]);
            $GSCAFB_DateRegistered=($GSCAFB_DateRegistered?$GSCAFB_DateRegistered:$q["GSCAFB_DateRegistered"]);
            $GSCAFB_BusinessTypeCode=($GSCAFB_BusinessTypeCode?$GSCAFB_BusinessTypeCode:$q["GSCAFB_BusinessTypeCode"]);
            $GSCAFB_Surname=($GSCAFB_Surname?$GSCAFB_Surname:$q["GSCAFB_Surname"]);
            $GSCAFB_Forename1=($GSCAFB_Forename1?$GSCAFB_Forename1:$q["GSCAFB_Forename1"]);
            $GSCAFB_Forename2=($GSCAFB_Forename2?$GSCAFB_Forename2:$q["GSCAFB_Forename2"]);
            $GSCAFB_Forename3=($GSCAFB_Forename3?$GSCAFB_Forename3:$q["GSCAFB_Forename3"]);
            $GSCAFB_Gender=($GSCAFB_Gender?$GSCAFB_Gender:$q["GSCAFB_Gender"]);
            $GSCAFB_MaritalStatus=($GSCAFB_MaritalStatus?$GSCAFB_MaritalStatus:$q["GSCAFB_MaritalStatus"]);
            $GSCAFB_DateofBirth=($GSCAFB_DateofBirth?$GSCAFB_DateofBirth:$q["GSCAFB_DateofBirth"]);
            $EI_EmploymentType=($EI_EmploymentType?$EI_EmploymentType:$q["EI_EmploymentType"]);
            $EI_PrimaryOccupation=($EI_PrimaryOccupation?$EI_PrimaryOccupation:$q["EI_PrimaryOccupation"]);
            $EI_EmployerName=($EI_EmployerName?$EI_EmployerName:$q["EI_EmployerName"]);
            $EI_EmployeeNumber=($EI_EmployeeNumber?$EI_EmployeeNumber:$q["EI_EmployeeNumber"]);
            $EI_PeriodAtEmployer=($EI_PeriodAtEmployer?$EI_PeriodAtEmployer:$q["EI_PeriodAtEmployer"]);
            $EI_IncomeBand=($EI_IncomeBand?$EI_IncomeBand:$q["EI_IncomeBand"]);
            $EI_SalaryFrequency=($EI_SalaryFrequency?$EI_SalaryFrequency:$q["EI_SalaryFrequency"]);
            $PCI_UnitNumber=($PCI_UnitNumber?$PCI_UnitNumber:$q["PCI_UnitNumber"]);
            $PCI_UnitName=($PCI_UnitName?$PCI_UnitName:$q["PCI_UnitName"]);
            $PCI_FloorNumber=($PCI_FloorNumber?$PCI_FloorNumber:$q["PCI_FloorNumber"]);
            $PCI_PlotorStreetNumber=($PCI_PlotorStreetNumber?$PCI_PlotorStreetNumber:$q["PCI_PlotorStreetNumber"]);
            $PCI_LCorStreetName=($PCI_LCorStreetName?$PCI_LCorStreetName:$q["PCI_LCorStreetName"]);
            $PCI_Parish=($PCI_Parish?$PCI_Parish:$q["PCI_Parish"]);
            $PCI_Suburb=($PCI_Suburb?$PCI_Suburb:$q["PCI_Suburb"]);
            $PCI_Village=($PCI_Village?$PCI_Village:$q["PCI_Village"]);
            $PCI_CountyorTown=($PCI_CountyorTown?$PCI_CountyorTown:$q["PCI_CountyorTown"]);
            $PCI_District=($PCI_District?$PCI_District:$q["PCI_District"]);
            $PCI_Region=($PCI_Region?$PCI_Region:$q["PCI_Region"]);
            $PCI_POBoxNumber=($PCI_POBoxNumber?$PCI_POBoxNumber:$q["PCI_POBoxNumber"]);
            $PCI_PostOfficeTown=($PCI_PostOfficeTown?$PCI_PostOfficeTown:$q["PCI_PostOfficeTown"]);
            $PCI_CountryCode=($PCI_CountryCode?$PCI_CountryCode:$q["PCI_CountryCode"]);
            $PCI_PeriodAtAddress=($PCI_PeriodAtAddress?$PCI_PeriodAtAddress:$q["PCI_PeriodAtAddress"]);
            $PCI_FlagofOwnership=($PCI_FlagofOwnership?$PCI_FlagofOwnership:$q["PCI_FlagofOwnership"]);
            $PCI_PrimaryCountryDiallingCode=($PCI_PrimaryCountryDiallingCode?$PCI_PrimaryCountryDiallingCode:$q["PCI_PrimaryCountryDiallingCode"]);
            $PCI_PrimaryAreaDiallingCode=($PCI_PrimaryAreaDiallingCode?$PCI_PrimaryAreaDiallingCode:$q["PCI_PrimaryAreaDiallingCode"]);
            $PCI_PrimaryTelephoneNumber=($PCI_PrimaryTelephoneNumber?$PCI_PrimaryTelephoneNumber:$q["PCI_PrimaryTelephoneNumber"]);
            $PCI_OtherCountryDiallingCode=($PCI_OtherCountryDiallingCode?$PCI_OtherCountryDiallingCode:$q["PCI_OtherCountryDiallingCode"]);
            $PCI_OtherAreaDiallingCode=($PCI_OtherAreaDiallingCode?$PCI_OtherAreaDiallingCode:$q["PCI_OtherAreaDiallingCode"]);
            $PCI_OtherTelephoneNumber=($PCI_OtherTelephoneNumber?$PCI_OtherTelephoneNumber:$q["PCI_OtherTelephoneNumber"]);
            $PCI_MobileCountryDiallingCode=($PCI_MobileCountryDiallingCode?$PCI_MobileCountryDiallingCode:$q["PCI_MobileCountryDiallingCode"]);
            $PCI_MobileAreaDiallingCode=($PCI_MobileAreaDiallingCode?$PCI_MobileAreaDiallingCode:$q["PCI_MobileAreaDiallingCode"]);
            $PCI_MobileNumber=($PCI_MobileNumber?$PCI_MobileNumber:$q["PCI_MobileNumber"]);
            $PCI_FAXCountryDiallingCode=($PCI_FAXCountryDiallingCode?$PCI_FAXCountryDiallingCode:$q["PCI_FAXCountryDiallingCode"]);
            $PCI_FAXAreaDiallingCode=($PCI_FAXAreaDiallingCode?$PCI_FAXAreaDiallingCode:$q["PCI_FAXAreaDiallingCode"]);
            $PCI_FAXNumber=($PCI_FAXNumber?$PCI_FAXNumber:$q["PCI_FAXNumber"]);
            $PCI_EmailAddress=($PCI_EmailAddress?$PCI_EmailAddress:$q["PCI_EmailAddress"]);
            $PCI_Website=($PCI_Website?$PCI_Website:$q["PCI_Website"]);
            $SCI_UnitNumber=($SCI_UnitNumber?$SCI_UnitNumber:$q["SCI_UnitNumber"]);
            $SCI_UnitName=($SCI_UnitName?$SCI_UnitName:$q["SCI_UnitName"]);
            $SCI_FloorNumber=($SCI_FloorNumber?$SCI_FloorNumber:$q["SCI_FloorNumber"]);
            $SCI_PlotorStreetNumber=($SCI_PlotorStreetNumber?$SCI_PlotorStreetNumber:$q["SCI_PlotorStreetNumber"]);
            $SCI_LCorStreetName=($SCI_LCorStreetName?$SCI_LCorStreetName:$q["SCI_LCorStreetName"]);
            $SCI_Parish=($SCI_Parish?$SCI_Parish:$q["SCI_Parish"]);
            $SCI_Suburb=($SCI_Suburb?$SCI_Suburb:$q["SCI_Suburb"]);
            $SCI_Village=($SCI_Village?$SCI_Village:$q["SCI_Village"]);
            $SCI_CountyorTown=($SCI_CountyorTown?$SCI_CountyorTown:$q["SCI_CountyorTown"]);
            $SCI_District=($SCI_District?$SCI_District:$q["SCI_District"]);
            $SCI_Region=($SCI_Region?$SCI_Region:$q["SCI_Region"]);
            $SCI_POBoxNumber=($SCI_POBoxNumber?$SCI_POBoxNumber:$q["SCI_POBoxNumber"]);
            $SCI_PostOfficeTown=($SCI_PostOfficeTown?$SCI_PostOfficeTown:$q["SCI_PostOfficeTown"]);
            $SCI_CountryCode=($SCI_CountryCode?$SCI_CountryCode:$q["SCI_CountryCode"]);
            $SCI_PeriodAtAddress=($SCI_PeriodAtAddress?$SCI_PeriodAtAddress:$q["SCI_PeriodAtAddress"]);
            $SCI_FlagofOwnership=($SCI_FlagofOwnership?$SCI_FlagofOwnership:$q["SCI_FlagofOwnership"]);
            $SCI_PrimaryCountryDiallingCode=($SCI_PrimaryCountryDiallingCode?$SCI_PrimaryCountryDiallingCode:$q["SCI_PrimaryCountryDiallingCode"]);
            $SCI_PrimaryAreaDiallingCode=($SCI_PrimaryAreaDiallingCode?$SCI_PrimaryAreaDiallingCode:$q["SCI_PrimaryAreaDiallingCode"]);
            $SCI_PrimaryTelephoneNumber=($SCI_PrimaryTelephoneNumber?$SCI_PrimaryTelephoneNumber:$q["SCI_PrimaryTelephoneNumber"]);
            $SCI_OtherCountryDiallingCode=($SCI_OtherCountryDiallingCode?$SCI_OtherCountryDiallingCode:$q["SCI_OtherCountryDiallingCode"]);
            $SCI_OtherAreaDiallingCode=($SCI_OtherAreaDiallingCode?$SCI_OtherAreaDiallingCode:$q["SCI_OtherAreaDiallingCode"]);
            $SCI_OtherTelephoneNumber=($SCI_OtherTelephoneNumber?$SCI_OtherTelephoneNumber:$q["SCI_OtherTelephoneNumber"]);
            $SCI_MobileCountryDiallingCode=($SCI_MobileCountryDiallingCode?$SCI_MobileCountryDiallingCode:$q["SCI_MobileCountryDiallingCode"]);
            $SCI_MobileAreaDiallingCode=($SCI_MobileAreaDiallingCode?$SCI_MobileAreaDiallingCode:$q["SCI_MobileAreaDiallingCode"]);
            $SCI_MobileNumber=($SCI_MobileNumber?$SCI_MobileNumber:$q["SCI_MobileNumber"]);
            $SCI_FAXCountryDiallingCode=($SCI_FAXCountryDiallingCode?$SCI_FAXCountryDiallingCode:$q["SCI_FAXCountryDiallingCode"]);
            $SCI_FAXAreaDiallingCode=($SCI_FAXAreaDiallingCode?$SCI_FAXAreaDiallingCode:$q["SCI_FAXAreaDiallingCode"]);
            $SCI_FAXNumber=($SCI_FAXNumber?$SCI_FAXNumber:$q["SCI_FAXNumber"]);
            $SCI_EmailAddress=($SCI_EmailAddress?$SCI_EmailAddress:$q["SCI_EmailAddress"]);
            $SCI_Website=($SCI_Website?$SCI_Website:$q["SCI_Website"]);
        }
	}
    
	//then search ClientNumber in vl_datacba111
	$query=0;
	$query=mysqlquery("select * from vl_datacba111 where ClientNumber='$clientNumber' order by created desc");
	if(mysqlnumrows($query)) {
		//flag
		$datafound=1;
        //get data
        $q=array();
        while($q=mysqlfetcharray($query)) {
            $II_RegistrationCertificateNumber=($II_RegistrationCertificateNumber?$II_RegistrationCertificateNumber:$q["II_RegistrationCertificateNumber"]);
            $II_TaxIdentificationNumber=($II_TaxIdentificationNumber?$II_TaxIdentificationNumber:$q["II_TaxIdentificationNumber"]);
            $II_ValueAddedTaxNumber=($II_ValueAddedTaxNumber?$II_ValueAddedTaxNumber:$q["II_ValueAddedTaxNumber"]);
            $II_FCSNumber=($II_FCSNumber?$II_FCSNumber:$q["II_FCSNumber"]);
            $II_PassportNumber=($II_PassportNumber?$II_PassportNumber:$q["II_PassportNumber"]);
            $II_DriversLicenceIDNumber=($II_DriversLicenceIDNumber?$II_DriversLicenceIDNumber:$q["II_DriversLicenceIDNumber"]);
            $II_VotersPERNO=($II_VotersPERNO?$II_VotersPERNO:$q["II_VotersPERNO"]);
            $II_DriversLicensePermitNumber=($II_DriversLicensePermitNumber?$II_DriversLicensePermitNumber:$q["II_DriversLicensePermitNumber"]);
            $II_NSSFNumber=($II_NSSFNumber?$II_NSSFNumber:$q["II_NSSFNumber"]);
            $II_CountryID=($II_CountryID?$II_CountryID:$q["II_CountryID"]);
            $II_CountryIssuingAuthority=($II_CountryIssuingAuthority?$II_CountryIssuingAuthority:$q["II_CountryIssuingAuthority"]);
            $II_Nationality=($II_Nationality?$II_Nationality:$q["II_Nationality"]);
            $II_PoliceIDNumber=($II_PoliceIDNumber?$II_PoliceIDNumber:$q["II_PoliceIDNumber"]);
            $II_UPDFNumber=($II_UPDFNumber?$II_UPDFNumber:$q["II_UPDFNumber"]);
            $II_KACITALicenseNumber=($II_KACITALicenseNumber?$II_KACITALicenseNumber:$q["II_KACITALicenseNumber"]);
            $II_CountryOfIssue=($II_CountryOfIssue?$II_CountryOfIssue:$q["II_CountryOfIssue"]);
            $GSCAFB_BusinessName=($GSCAFB_BusinessName?$GSCAFB_BusinessName:$q["GSCAFB_BusinessName"]);
            $GSCAFB_TradingName=($GSCAFB_TradingName?$GSCAFB_TradingName:$q["GSCAFB_TradingName"]);
            $GSCAFB_ActivityDescription=($GSCAFB_ActivityDescription?$GSCAFB_ActivityDescription:$q["GSCAFB_ActivityDescription"]);
            $GSCAFB_IndustrySectorCode=($GSCAFB_IndustrySectorCode?$GSCAFB_IndustrySectorCode:$q["GSCAFB_IndustrySectorCode"]);
            $GSCAFB_DateRegistered=($GSCAFB_DateRegistered?$GSCAFB_DateRegistered:$q["GSCAFB_DateRegistered"]);
            $GSCAFB_BusinessTypeCode=($GSCAFB_BusinessTypeCode?$GSCAFB_BusinessTypeCode:$q["GSCAFB_BusinessTypeCode"]);
            $GSCAFB_Surname=($GSCAFB_Surname?$GSCAFB_Surname:$q["GSCAFB_Surname"]);
            $GSCAFB_Forename1=($GSCAFB_Forename1?$GSCAFB_Forename1:$q["GSCAFB_Forename1"]);
            $GSCAFB_Forename2=($GSCAFB_Forename2?$GSCAFB_Forename2:$q["GSCAFB_Forename2"]);
            $GSCAFB_Forename3=($GSCAFB_Forename3?$GSCAFB_Forename3:$q["GSCAFB_Forename3"]);
            $GSCAFB_Gender=($GSCAFB_Gender?$GSCAFB_Gender:$q["GSCAFB_Gender"]);
            $GSCAFB_MaritalStatus=($GSCAFB_MaritalStatus?$GSCAFB_MaritalStatus:$q["GSCAFB_MaritalStatus"]);
            $GSCAFB_DateofBirth=($GSCAFB_DateofBirth?$GSCAFB_DateofBirth:$q["GSCAFB_DateofBirth"]);
            $EI_EmploymentType=($EI_EmploymentType?$EI_EmploymentType:$q["EI_EmploymentType"]);
            $EI_PrimaryOccupation=($EI_PrimaryOccupation?$EI_PrimaryOccupation:$q["EI_PrimaryOccupation"]);
            $EI_EmployerName=($EI_EmployerName?$EI_EmployerName:$q["EI_EmployerName"]);
            $EI_EmployeeNumber=($EI_EmployeeNumber?$EI_EmployeeNumber:$q["EI_EmployeeNumber"]);
            $EI_PeriodAtEmployer=($EI_PeriodAtEmployer?$EI_PeriodAtEmployer:$q["EI_PeriodAtEmployer"]);
            $EI_IncomeBand=($EI_IncomeBand?$EI_IncomeBand:$q["EI_IncomeBand"]);
            $EI_SalaryFrequency=($EI_SalaryFrequency?$EI_SalaryFrequency:$q["EI_SalaryFrequency"]);
            $PCI_UnitNumber=($PCI_UnitNumber?$PCI_UnitNumber:$q["PCI_UnitNumber"]);
            $PCI_UnitName=($PCI_UnitName?$PCI_UnitName:$q["PCI_UnitName"]);
            $PCI_FloorNumber=($PCI_FloorNumber?$PCI_FloorNumber:$q["PCI_FloorNumber"]);
            $PCI_PlotorStreetNumber=($PCI_PlotorStreetNumber?$PCI_PlotorStreetNumber:$q["PCI_PlotorStreetNumber"]);
            $PCI_LCorStreetName=($PCI_LCorStreetName?$PCI_LCorStreetName:$q["PCI_LCorStreetName"]);
            $PCI_Parish=($PCI_Parish?$PCI_Parish:$q["PCI_Parish"]);
            $PCI_Suburb=($PCI_Suburb?$PCI_Suburb:$q["PCI_Suburb"]);
            $PCI_Village=($PCI_Village?$PCI_Village:$q["PCI_Village"]);
            $PCI_CountyorTown=($PCI_CountyorTown?$PCI_CountyorTown:$q["PCI_CountyorTown"]);
            $PCI_District=($PCI_District?$PCI_District:$q["PCI_District"]);
            $PCI_Region=($PCI_Region?$PCI_Region:$q["PCI_Region"]);
            $PCI_POBoxNumber=($PCI_POBoxNumber?$PCI_POBoxNumber:$q["PCI_POBoxNumber"]);
            $PCI_PostOfficeTown=($PCI_PostOfficeTown?$PCI_PostOfficeTown:$q["PCI_PostOfficeTown"]);
            $PCI_CountryCode=($PCI_CountryCode?$PCI_CountryCode:$q["PCI_CountryCode"]);
            $PCI_PeriodAtAddress=($PCI_PeriodAtAddress?$PCI_PeriodAtAddress:$q["PCI_PeriodAtAddress"]);
            $PCI_FlagofOwnership=($PCI_FlagofOwnership?$PCI_FlagofOwnership:$q["PCI_FlagofOwnership"]);
            $PCI_PrimaryCountryDiallingCode=($PCI_PrimaryCountryDiallingCode?$PCI_PrimaryCountryDiallingCode:$q["PCI_PrimaryCountryDiallingCode"]);
            $PCI_PrimaryAreaDiallingCode=($PCI_PrimaryAreaDiallingCode?$PCI_PrimaryAreaDiallingCode:$q["PCI_PrimaryAreaDiallingCode"]);
            $PCI_PrimaryTelephoneNumber=($PCI_PrimaryTelephoneNumber?$PCI_PrimaryTelephoneNumber:$q["PCI_PrimaryTelephoneNumber"]);
            $PCI_OtherCountryDiallingCode=($PCI_OtherCountryDiallingCode?$PCI_OtherCountryDiallingCode:$q["PCI_OtherCountryDiallingCode"]);
            $PCI_OtherAreaDiallingCode=($PCI_OtherAreaDiallingCode?$PCI_OtherAreaDiallingCode:$q["PCI_OtherAreaDiallingCode"]);
            $PCI_OtherTelephoneNumber=($PCI_OtherTelephoneNumber?$PCI_OtherTelephoneNumber:$q["PCI_OtherTelephoneNumber"]);
            $PCI_MobileCountryDiallingCode=($PCI_MobileCountryDiallingCode?$PCI_MobileCountryDiallingCode:$q["PCI_MobileCountryDiallingCode"]);
            $PCI_MobileAreaDiallingCode=($PCI_MobileAreaDiallingCode?$PCI_MobileAreaDiallingCode:$q["PCI_MobileAreaDiallingCode"]);
            $PCI_MobileNumber=($PCI_MobileNumber?$PCI_MobileNumber:$q["PCI_MobileNumber"]);
            $PCI_FAXCountryDiallingCode=($PCI_FAXCountryDiallingCode?$PCI_FAXCountryDiallingCode:$q["PCI_FAXCountryDiallingCode"]);
            $PCI_FAXAreaDiallingCode=($PCI_FAXAreaDiallingCode?$PCI_FAXAreaDiallingCode:$q["PCI_FAXAreaDiallingCode"]);
            $PCI_FAXNumber=($PCI_FAXNumber?$PCI_FAXNumber:$q["PCI_FAXNumber"]);
            $PCI_EmailAddress=($PCI_EmailAddress?$PCI_EmailAddress:$q["PCI_EmailAddress"]);
            $PCI_Website=($PCI_Website?$PCI_Website:$q["PCI_Website"]);
            $SCI_UnitNumber=($SCI_UnitNumber?$SCI_UnitNumber:$q["SCI_UnitNumber"]);
            $SCI_UnitName=($SCI_UnitName?$SCI_UnitName:$q["SCI_UnitName"]);
            $SCI_FloorNumber=($SCI_FloorNumber?$SCI_FloorNumber:$q["SCI_FloorNumber"]);
            $SCI_PlotorStreetNumber=($SCI_PlotorStreetNumber?$SCI_PlotorStreetNumber:$q["SCI_PlotorStreetNumber"]);
            $SCI_LCorStreetName=($SCI_LCorStreetName?$SCI_LCorStreetName:$q["SCI_LCorStreetName"]);
            $SCI_Parish=($SCI_Parish?$SCI_Parish:$q["SCI_Parish"]);
            $SCI_Suburb=($SCI_Suburb?$SCI_Suburb:$q["SCI_Suburb"]);
            $SCI_Village=($SCI_Village?$SCI_Village:$q["SCI_Village"]);
            $SCI_CountyorTown=($SCI_CountyorTown?$SCI_CountyorTown:$q["SCI_CountyorTown"]);
            $SCI_District=($SCI_District?$SCI_District:$q["SCI_District"]);
            $SCI_Region=($SCI_Region?$SCI_Region:$q["SCI_Region"]);
            $SCI_POBoxNumber=($SCI_POBoxNumber?$SCI_POBoxNumber:$q["SCI_POBoxNumber"]);
            $SCI_PostOfficeTown=($SCI_PostOfficeTown?$SCI_PostOfficeTown:$q["SCI_PostOfficeTown"]);
            $SCI_CountryCode=($SCI_CountryCode?$SCI_CountryCode:$q["SCI_CountryCode"]);
            $SCI_PeriodAtAddress=($SCI_PeriodAtAddress?$SCI_PeriodAtAddress:$q["SCI_PeriodAtAddress"]);
            $SCI_FlagofOwnership=($SCI_FlagofOwnership?$SCI_FlagofOwnership:$q["SCI_FlagofOwnership"]);
            $SCI_PrimaryCountryDiallingCode=($SCI_PrimaryCountryDiallingCode?$SCI_PrimaryCountryDiallingCode:$q["SCI_PrimaryCountryDiallingCode"]);
            $SCI_PrimaryAreaDiallingCode=($SCI_PrimaryAreaDiallingCode?$SCI_PrimaryAreaDiallingCode:$q["SCI_PrimaryAreaDiallingCode"]);
            $SCI_PrimaryTelephoneNumber=($SCI_PrimaryTelephoneNumber?$SCI_PrimaryTelephoneNumber:$q["SCI_PrimaryTelephoneNumber"]);
            $SCI_OtherCountryDiallingCode=($SCI_OtherCountryDiallingCode?$SCI_OtherCountryDiallingCode:$q["SCI_OtherCountryDiallingCode"]);
            $SCI_OtherAreaDiallingCode=($SCI_OtherAreaDiallingCode?$SCI_OtherAreaDiallingCode:$q["SCI_OtherAreaDiallingCode"]);
            $SCI_OtherTelephoneNumber=($SCI_OtherTelephoneNumber?$SCI_OtherTelephoneNumber:$q["SCI_OtherTelephoneNumber"]);
            $SCI_MobileCountryDiallingCode=($SCI_MobileCountryDiallingCode?$SCI_MobileCountryDiallingCode:$q["SCI_MobileCountryDiallingCode"]);
            $SCI_MobileAreaDiallingCode=($SCI_MobileAreaDiallingCode?$SCI_MobileAreaDiallingCode:$q["SCI_MobileAreaDiallingCode"]);
            $SCI_MobileNumber=($SCI_MobileNumber?$SCI_MobileNumber:$q["SCI_MobileNumber"]);
            $SCI_FAXCountryDiallingCode=($SCI_FAXCountryDiallingCode?$SCI_FAXCountryDiallingCode:$q["SCI_FAXCountryDiallingCode"]);
            $SCI_FAXAreaDiallingCode=($SCI_FAXAreaDiallingCode?$SCI_FAXAreaDiallingCode:$q["SCI_FAXAreaDiallingCode"]);
            $SCI_FAXNumber=($SCI_FAXNumber?$SCI_FAXNumber:$q["SCI_FAXNumber"]);
            $SCI_EmailAddress=($SCI_EmailAddress?$SCI_EmailAddress:$q["SCI_EmailAddress"]);
            $SCI_Website=($SCI_Website?$SCI_Website:$q["SCI_Website"]);
        }
	}
    
	//then search ClientNumber in vl_databc111
	$query=0;
	$query=mysqlquery("select * from vl_databc111 where ClientNumber='$clientNumber' order by created desc");
	if(mysqlnumrows($query)) {
		//flag
		$datafound=1;
        //get data
        $q=array();
        while($q=mysqlfetcharray($query)) {
            $II_RegistrationCertificateNumber=($II_RegistrationCertificateNumber?$II_RegistrationCertificateNumber:$q["II_RegistrationCertificateNumber"]);
            $II_TaxIdentificationNumber=($II_TaxIdentificationNumber?$II_TaxIdentificationNumber:$q["II_TaxIdentificationNumber"]);
            $II_ValueAddedTaxNumber=($II_ValueAddedTaxNumber?$II_ValueAddedTaxNumber:$q["II_ValueAddedTaxNumber"]);
            $II_FCSNumber=($II_FCSNumber?$II_FCSNumber:$q["II_FCSNumber"]);
            $II_PassportNumber=($II_PassportNumber?$II_PassportNumber:$q["II_PassportNumber"]);
            $II_DriversLicenceIDNumber=($II_DriversLicenceIDNumber?$II_DriversLicenceIDNumber:$q["II_DriversLicenceIDNumber"]);
            $II_VotersPERNO=($II_VotersPERNO?$II_VotersPERNO:$q["II_VotersPERNO"]);
            $II_DriversLicensePermitNumber=($II_DriversLicensePermitNumber?$II_DriversLicensePermitNumber:$q["II_DriversLicensePermitNumber"]);
            $II_NSSFNumber=($II_NSSFNumber?$II_NSSFNumber:$q["II_NSSFNumber"]);
            $II_CountryID=($II_CountryID?$II_CountryID:$q["II_CountryID"]);
            $II_CountryIssuingAuthority=($II_CountryIssuingAuthority?$II_CountryIssuingAuthority:$q["II_CountryIssuingAuthority"]);
            $II_Nationality=($II_Nationality?$II_Nationality:$q["II_Nationality"]);
            $II_PoliceIDNumber=($II_PoliceIDNumber?$II_PoliceIDNumber:$q["II_PoliceIDNumber"]);
            $II_UPDFNumber=($II_UPDFNumber?$II_UPDFNumber:$q["II_UPDFNumber"]);
            $II_KACITALicenseNumber=($II_KACITALicenseNumber?$II_KACITALicenseNumber:$q["II_KACITALicenseNumber"]);
            $II_CountryOfIssue=($II_CountryOfIssue?$II_CountryOfIssue:$q["II_CountryOfIssue"]);
            $GSCAFB_BusinessName=($GSCAFB_BusinessName?$GSCAFB_BusinessName:$q["GSCAFB_BusinessName"]);
            $GSCAFB_TradingName=($GSCAFB_TradingName?$GSCAFB_TradingName:$q["GSCAFB_TradingName"]);
            $GSCAFB_ActivityDescription=($GSCAFB_ActivityDescription?$GSCAFB_ActivityDescription:$q["GSCAFB_ActivityDescription"]);
            $GSCAFB_IndustrySectorCode=($GSCAFB_IndustrySectorCode?$GSCAFB_IndustrySectorCode:$q["GSCAFB_IndustrySectorCode"]);
            $GSCAFB_DateRegistered=($GSCAFB_DateRegistered?$GSCAFB_DateRegistered:$q["GSCAFB_DateRegistered"]);
            $GSCAFB_BusinessTypeCode=($GSCAFB_BusinessTypeCode?$GSCAFB_BusinessTypeCode:$q["GSCAFB_BusinessTypeCode"]);
            $GSCAFB_Surname=($GSCAFB_Surname?$GSCAFB_Surname:$q["GSCAFB_Surname"]);
            $GSCAFB_Forename1=($GSCAFB_Forename1?$GSCAFB_Forename1:$q["GSCAFB_Forename1"]);
            $GSCAFB_Forename2=($GSCAFB_Forename2?$GSCAFB_Forename2:$q["GSCAFB_Forename2"]);
            $GSCAFB_Forename3=($GSCAFB_Forename3?$GSCAFB_Forename3:$q["GSCAFB_Forename3"]);
            $GSCAFB_Gender=($GSCAFB_Gender?$GSCAFB_Gender:$q["GSCAFB_Gender"]);
            $GSCAFB_MaritalStatus=($GSCAFB_MaritalStatus?$GSCAFB_MaritalStatus:$q["GSCAFB_MaritalStatus"]);
            $GSCAFB_DateofBirth=($GSCAFB_DateofBirth?$GSCAFB_DateofBirth:$q["GSCAFB_DateofBirth"]);
            $EI_EmploymentType=($EI_EmploymentType?$EI_EmploymentType:$q["EI_EmploymentType"]);
            $EI_PrimaryOccupation=($EI_PrimaryOccupation?$EI_PrimaryOccupation:$q["EI_PrimaryOccupation"]);
            $EI_EmployerName=($EI_EmployerName?$EI_EmployerName:$q["EI_EmployerName"]);
            $EI_EmployeeNumber=($EI_EmployeeNumber?$EI_EmployeeNumber:$q["EI_EmployeeNumber"]);
            $EI_PeriodAtEmployer=($EI_PeriodAtEmployer?$EI_PeriodAtEmployer:$q["EI_PeriodAtEmployer"]);
            $EI_IncomeBand=($EI_IncomeBand?$EI_IncomeBand:$q["EI_IncomeBand"]);
            $EI_SalaryFrequency=($EI_SalaryFrequency?$EI_SalaryFrequency:$q["EI_SalaryFrequency"]);
            $PCI_UnitNumber=($PCI_UnitNumber?$PCI_UnitNumber:$q["PCI_UnitNumber"]);
            $PCI_UnitName=($PCI_UnitName?$PCI_UnitName:$q["PCI_UnitName"]);
            $PCI_FloorNumber=($PCI_FloorNumber?$PCI_FloorNumber:$q["PCI_FloorNumber"]);
            $PCI_PlotorStreetNumber=($PCI_PlotorStreetNumber?$PCI_PlotorStreetNumber:$q["PCI_PlotorStreetNumber"]);
            $PCI_LCorStreetName=($PCI_LCorStreetName?$PCI_LCorStreetName:$q["PCI_LCorStreetName"]);
            $PCI_Parish=($PCI_Parish?$PCI_Parish:$q["PCI_Parish"]);
            $PCI_Suburb=($PCI_Suburb?$PCI_Suburb:$q["PCI_Suburb"]);
            $PCI_Village=($PCI_Village?$PCI_Village:$q["PCI_Village"]);
            $PCI_CountyorTown=($PCI_CountyorTown?$PCI_CountyorTown:$q["PCI_CountyorTown"]);
            $PCI_District=($PCI_District?$PCI_District:$q["PCI_District"]);
            $PCI_Region=($PCI_Region?$PCI_Region:$q["PCI_Region"]);
            $PCI_POBoxNumber=($PCI_POBoxNumber?$PCI_POBoxNumber:$q["PCI_POBoxNumber"]);
            $PCI_PostOfficeTown=($PCI_PostOfficeTown?$PCI_PostOfficeTown:$q["PCI_PostOfficeTown"]);
            $PCI_CountryCode=($PCI_CountryCode?$PCI_CountryCode:$q["PCI_CountryCode"]);
            $PCI_PeriodAtAddress=($PCI_PeriodAtAddress?$PCI_PeriodAtAddress:$q["PCI_PeriodAtAddress"]);
            $PCI_FlagofOwnership=($PCI_FlagofOwnership?$PCI_FlagofOwnership:$q["PCI_FlagofOwnership"]);
            $PCI_PrimaryCountryDiallingCode=($PCI_PrimaryCountryDiallingCode?$PCI_PrimaryCountryDiallingCode:$q["PCI_PrimaryCountryDiallingCode"]);
            $PCI_PrimaryAreaDiallingCode=($PCI_PrimaryAreaDiallingCode?$PCI_PrimaryAreaDiallingCode:$q["PCI_PrimaryAreaDiallingCode"]);
            $PCI_PrimaryTelephoneNumber=($PCI_PrimaryTelephoneNumber?$PCI_PrimaryTelephoneNumber:$q["PCI_PrimaryTelephoneNumber"]);
            $PCI_OtherCountryDiallingCode=($PCI_OtherCountryDiallingCode?$PCI_OtherCountryDiallingCode:$q["PCI_OtherCountryDiallingCode"]);
            $PCI_OtherAreaDiallingCode=($PCI_OtherAreaDiallingCode?$PCI_OtherAreaDiallingCode:$q["PCI_OtherAreaDiallingCode"]);
            $PCI_OtherTelephoneNumber=($PCI_OtherTelephoneNumber?$PCI_OtherTelephoneNumber:$q["PCI_OtherTelephoneNumber"]);
            $PCI_MobileCountryDiallingCode=($PCI_MobileCountryDiallingCode?$PCI_MobileCountryDiallingCode:$q["PCI_MobileCountryDiallingCode"]);
            $PCI_MobileAreaDiallingCode=($PCI_MobileAreaDiallingCode?$PCI_MobileAreaDiallingCode:$q["PCI_MobileAreaDiallingCode"]);
            $PCI_MobileNumber=($PCI_MobileNumber?$PCI_MobileNumber:$q["PCI_MobileNumber"]);
            $PCI_FAXCountryDiallingCode=($PCI_FAXCountryDiallingCode?$PCI_FAXCountryDiallingCode:$q["PCI_FAXCountryDiallingCode"]);
            $PCI_FAXAreaDiallingCode=($PCI_FAXAreaDiallingCode?$PCI_FAXAreaDiallingCode:$q["PCI_FAXAreaDiallingCode"]);
            $PCI_FAXNumber=($PCI_FAXNumber?$PCI_FAXNumber:$q["PCI_FAXNumber"]);
            $PCI_EmailAddress=($PCI_EmailAddress?$PCI_EmailAddress:$q["PCI_EmailAddress"]);
            $PCI_Website=($PCI_Website?$PCI_Website:$q["PCI_Website"]);
            $SCI_UnitNumber=($SCI_UnitNumber?$SCI_UnitNumber:$q["SCI_UnitNumber"]);
            $SCI_UnitName=($SCI_UnitName?$SCI_UnitName:$q["SCI_UnitName"]);
            $SCI_FloorNumber=($SCI_FloorNumber?$SCI_FloorNumber:$q["SCI_FloorNumber"]);
            $SCI_PlotorStreetNumber=($SCI_PlotorStreetNumber?$SCI_PlotorStreetNumber:$q["SCI_PlotorStreetNumber"]);
            $SCI_LCorStreetName=($SCI_LCorStreetName?$SCI_LCorStreetName:$q["SCI_LCorStreetName"]);
            $SCI_Parish=($SCI_Parish?$SCI_Parish:$q["SCI_Parish"]);
            $SCI_Suburb=($SCI_Suburb?$SCI_Suburb:$q["SCI_Suburb"]);
            $SCI_Village=($SCI_Village?$SCI_Village:$q["SCI_Village"]);
            $SCI_CountyorTown=($SCI_CountyorTown?$SCI_CountyorTown:$q["SCI_CountyorTown"]);
            $SCI_District=($SCI_District?$SCI_District:$q["SCI_District"]);
            $SCI_Region=($SCI_Region?$SCI_Region:$q["SCI_Region"]);
            $SCI_POBoxNumber=($SCI_POBoxNumber?$SCI_POBoxNumber:$q["SCI_POBoxNumber"]);
            $SCI_PostOfficeTown=($SCI_PostOfficeTown?$SCI_PostOfficeTown:$q["SCI_PostOfficeTown"]);
            $SCI_CountryCode=($SCI_CountryCode?$SCI_CountryCode:$q["SCI_CountryCode"]);
            $SCI_PeriodAtAddress=($SCI_PeriodAtAddress?$SCI_PeriodAtAddress:$q["SCI_PeriodAtAddress"]);
            $SCI_FlagofOwnership=($SCI_FlagofOwnership?$SCI_FlagofOwnership:$q["SCI_FlagofOwnership"]);
            $SCI_PrimaryCountryDiallingCode=($SCI_PrimaryCountryDiallingCode?$SCI_PrimaryCountryDiallingCode:$q["SCI_PrimaryCountryDiallingCode"]);
            $SCI_PrimaryAreaDiallingCode=($SCI_PrimaryAreaDiallingCode?$SCI_PrimaryAreaDiallingCode:$q["SCI_PrimaryAreaDiallingCode"]);
            $SCI_PrimaryTelephoneNumber=($SCI_PrimaryTelephoneNumber?$SCI_PrimaryTelephoneNumber:$q["SCI_PrimaryTelephoneNumber"]);
            $SCI_OtherCountryDiallingCode=($SCI_OtherCountryDiallingCode?$SCI_OtherCountryDiallingCode:$q["SCI_OtherCountryDiallingCode"]);
            $SCI_OtherAreaDiallingCode=($SCI_OtherAreaDiallingCode?$SCI_OtherAreaDiallingCode:$q["SCI_OtherAreaDiallingCode"]);
            $SCI_OtherTelephoneNumber=($SCI_OtherTelephoneNumber?$SCI_OtherTelephoneNumber:$q["SCI_OtherTelephoneNumber"]);
            $SCI_MobileCountryDiallingCode=($SCI_MobileCountryDiallingCode?$SCI_MobileCountryDiallingCode:$q["SCI_MobileCountryDiallingCode"]);
            $SCI_MobileAreaDiallingCode=($SCI_MobileAreaDiallingCode?$SCI_MobileAreaDiallingCode:$q["SCI_MobileAreaDiallingCode"]);
            $SCI_MobileNumber=($SCI_MobileNumber?$SCI_MobileNumber:$q["SCI_MobileNumber"]);
            $SCI_FAXCountryDiallingCode=($SCI_FAXCountryDiallingCode?$SCI_FAXCountryDiallingCode:$q["SCI_FAXCountryDiallingCode"]);
            $SCI_FAXAreaDiallingCode=($SCI_FAXAreaDiallingCode?$SCI_FAXAreaDiallingCode:$q["SCI_FAXAreaDiallingCode"]);
            $SCI_FAXNumber=($SCI_FAXNumber?$SCI_FAXNumber:$q["SCI_FAXNumber"]);
            $SCI_EmailAddress=($SCI_EmailAddress?$SCI_EmailAddress:$q["SCI_EmailAddress"]);
            $SCI_Website=($SCI_Website?$SCI_Website:$q["SCI_Website"]);
        }
	}
    
	//then search ClientNumber in vl_datacap109
	$query=0;
	$query=mysqlquery("select * from vl_datacap109 where ClientNumber='$clientNumber' order by created desc");
	if(mysqlnumrows($query)) {
		//flag
		$datafound=1;
        //get data
        $q=array();
        while($q=mysqlfetcharray($query)) {
            $II_RegistrationCertificateNumber=($II_RegistrationCertificateNumber?$II_RegistrationCertificateNumber:$q["II_RegistrationCertificateNumber"]);
            $II_TaxIdentificationNumber=($II_TaxIdentificationNumber?$II_TaxIdentificationNumber:$q["II_TaxIdentificationNumber"]);
            $II_ValueAddedTaxNumber=($II_ValueAddedTaxNumber?$II_ValueAddedTaxNumber:$q["II_ValueAddedTaxNumber"]);
            $II_FCSNumber=($II_FCSNumber?$II_FCSNumber:$q["II_FCSNumber"]);
            $II_PassportNumber=($II_PassportNumber?$II_PassportNumber:$q["II_PassportNumber"]);
            $II_DriversLicenceIDNumber=($II_DriversLicenceIDNumber?$II_DriversLicenceIDNumber:$q["II_DriversLicenceIDNumber"]);
            $II_VotersPERNO=($II_VotersPERNO?$II_VotersPERNO:$q["II_VotersPERNO"]);
            $II_DriversLicensePermitNumber=($II_DriversLicensePermitNumber?$II_DriversLicensePermitNumber:$q["II_DriversLicensePermitNumber"]);
            $II_NSSFNumber=($II_NSSFNumber?$II_NSSFNumber:$q["II_NSSFNumber"]);
            $II_CountryID=($II_CountryID?$II_CountryID:$q["II_CountryID"]);
            $II_CountryIssuingAuthority=($II_CountryIssuingAuthority?$II_CountryIssuingAuthority:$q["II_CountryIssuingAuthority"]);
            $II_Nationality=($II_Nationality?$II_Nationality:$q["II_Nationality"]);
            $II_PoliceIDNumber=($II_PoliceIDNumber?$II_PoliceIDNumber:$q["II_PoliceIDNumber"]);
            $II_UPDFNumber=($II_UPDFNumber?$II_UPDFNumber:$q["II_UPDFNumber"]);
            $II_KACITALicenseNumber=($II_KACITALicenseNumber?$II_KACITALicenseNumber:$q["II_KACITALicenseNumber"]);
            $II_CountryOfIssue=($II_CountryOfIssue?$II_CountryOfIssue:$q["II_CountryOfIssue"]);
            $GSCAFB_BusinessName=($GSCAFB_BusinessName?$GSCAFB_BusinessName:$q["GSCAFB_BusinessName"]);
            $GSCAFB_TradingName=($GSCAFB_TradingName?$GSCAFB_TradingName:$q["GSCAFB_TradingName"]);
            $GSCAFB_ActivityDescription=($GSCAFB_ActivityDescription?$GSCAFB_ActivityDescription:$q["GSCAFB_ActivityDescription"]);
            $GSCAFB_IndustrySectorCode=($GSCAFB_IndustrySectorCode?$GSCAFB_IndustrySectorCode:$q["GSCAFB_IndustrySectorCode"]);
            $GSCAFB_DateRegistered=($GSCAFB_DateRegistered?$GSCAFB_DateRegistered:$q["GSCAFB_DateRegistered"]);
            $GSCAFB_BusinessTypeCode=($GSCAFB_BusinessTypeCode?$GSCAFB_BusinessTypeCode:$q["GSCAFB_BusinessTypeCode"]);
            $GSCAFB_Surname=($GSCAFB_Surname?$GSCAFB_Surname:$q["GSCAFB_Surname"]);
            $GSCAFB_Forename1=($GSCAFB_Forename1?$GSCAFB_Forename1:$q["GSCAFB_Forename1"]);
            $GSCAFB_Forename2=($GSCAFB_Forename2?$GSCAFB_Forename2:$q["GSCAFB_Forename2"]);
            $GSCAFB_Forename3=($GSCAFB_Forename3?$GSCAFB_Forename3:$q["GSCAFB_Forename3"]);
            $GSCAFB_Gender=($GSCAFB_Gender?$GSCAFB_Gender:$q["GSCAFB_Gender"]);
            $GSCAFB_MaritalStatus=($GSCAFB_MaritalStatus?$GSCAFB_MaritalStatus:$q["GSCAFB_MaritalStatus"]);
            $GSCAFB_DateofBirth=($GSCAFB_DateofBirth?$GSCAFB_DateofBirth:$q["GSCAFB_DateofBirth"]);
            $EI_EmploymentType=($EI_EmploymentType?$EI_EmploymentType:$q["EI_EmploymentType"]);
            $EI_PrimaryOccupation=($EI_PrimaryOccupation?$EI_PrimaryOccupation:$q["EI_PrimaryOccupation"]);
            $EI_EmployerName=($EI_EmployerName?$EI_EmployerName:$q["EI_EmployerName"]);
            $EI_EmployeeNumber=($EI_EmployeeNumber?$EI_EmployeeNumber:$q["EI_EmployeeNumber"]);
            $EI_PeriodAtEmployer=($EI_PeriodAtEmployer?$EI_PeriodAtEmployer:$q["EI_PeriodAtEmployer"]);
            $EI_IncomeBand=($EI_IncomeBand?$EI_IncomeBand:$q["EI_IncomeBand"]);
            $EI_SalaryFrequency=($EI_SalaryFrequency?$EI_SalaryFrequency:$q["EI_SalaryFrequency"]);
            $PCI_UnitNumber=($PCI_UnitNumber?$PCI_UnitNumber:$q["PCI_UnitNumber"]);
            $PCI_UnitName=($PCI_UnitName?$PCI_UnitName:$q["PCI_UnitName"]);
            $PCI_FloorNumber=($PCI_FloorNumber?$PCI_FloorNumber:$q["PCI_FloorNumber"]);
            $PCI_PlotorStreetNumber=($PCI_PlotorStreetNumber?$PCI_PlotorStreetNumber:$q["PCI_PlotorStreetNumber"]);
            $PCI_LCorStreetName=($PCI_LCorStreetName?$PCI_LCorStreetName:$q["PCI_LCorStreetName"]);
            $PCI_Parish=($PCI_Parish?$PCI_Parish:$q["PCI_Parish"]);
            $PCI_Suburb=($PCI_Suburb?$PCI_Suburb:$q["PCI_Suburb"]);
            $PCI_Village=($PCI_Village?$PCI_Village:$q["PCI_Village"]);
            $PCI_CountyorTown=($PCI_CountyorTown?$PCI_CountyorTown:$q["PCI_CountyorTown"]);
            $PCI_District=($PCI_District?$PCI_District:$q["PCI_District"]);
            $PCI_Region=($PCI_Region?$PCI_Region:$q["PCI_Region"]);
            $PCI_POBoxNumber=($PCI_POBoxNumber?$PCI_POBoxNumber:$q["PCI_POBoxNumber"]);
            $PCI_PostOfficeTown=($PCI_PostOfficeTown?$PCI_PostOfficeTown:$q["PCI_PostOfficeTown"]);
            $PCI_CountryCode=($PCI_CountryCode?$PCI_CountryCode:$q["PCI_CountryCode"]);
            $PCI_PeriodAtAddress=($PCI_PeriodAtAddress?$PCI_PeriodAtAddress:$q["PCI_PeriodAtAddress"]);
            $PCI_FlagofOwnership=($PCI_FlagofOwnership?$PCI_FlagofOwnership:$q["PCI_FlagofOwnership"]);
            $PCI_PrimaryCountryDiallingCode=($PCI_PrimaryCountryDiallingCode?$PCI_PrimaryCountryDiallingCode:$q["PCI_PrimaryCountryDiallingCode"]);
            $PCI_PrimaryAreaDiallingCode=($PCI_PrimaryAreaDiallingCode?$PCI_PrimaryAreaDiallingCode:$q["PCI_PrimaryAreaDiallingCode"]);
            $PCI_PrimaryTelephoneNumber=($PCI_PrimaryTelephoneNumber?$PCI_PrimaryTelephoneNumber:$q["PCI_PrimaryTelephoneNumber"]);
            $PCI_OtherCountryDiallingCode=($PCI_OtherCountryDiallingCode?$PCI_OtherCountryDiallingCode:$q["PCI_OtherCountryDiallingCode"]);
            $PCI_OtherAreaDiallingCode=($PCI_OtherAreaDiallingCode?$PCI_OtherAreaDiallingCode:$q["PCI_OtherAreaDiallingCode"]);
            $PCI_OtherTelephoneNumber=($PCI_OtherTelephoneNumber?$PCI_OtherTelephoneNumber:$q["PCI_OtherTelephoneNumber"]);
            $PCI_MobileCountryDiallingCode=($PCI_MobileCountryDiallingCode?$PCI_MobileCountryDiallingCode:$q["PCI_MobileCountryDiallingCode"]);
            $PCI_MobileAreaDiallingCode=($PCI_MobileAreaDiallingCode?$PCI_MobileAreaDiallingCode:$q["PCI_MobileAreaDiallingCode"]);
            $PCI_MobileNumber=($PCI_MobileNumber?$PCI_MobileNumber:$q["PCI_MobileNumber"]);
            $PCI_FAXCountryDiallingCode=($PCI_FAXCountryDiallingCode?$PCI_FAXCountryDiallingCode:$q["PCI_FAXCountryDiallingCode"]);
            $PCI_FAXAreaDiallingCode=($PCI_FAXAreaDiallingCode?$PCI_FAXAreaDiallingCode:$q["PCI_FAXAreaDiallingCode"]);
            $PCI_FAXNumber=($PCI_FAXNumber?$PCI_FAXNumber:$q["PCI_FAXNumber"]);
            $PCI_EmailAddress=($PCI_EmailAddress?$PCI_EmailAddress:$q["PCI_EmailAddress"]);
            $PCI_Website=($PCI_Website?$PCI_Website:$q["PCI_Website"]);
            $SCI_UnitNumber=($SCI_UnitNumber?$SCI_UnitNumber:$q["SCI_UnitNumber"]);
            $SCI_UnitName=($SCI_UnitName?$SCI_UnitName:$q["SCI_UnitName"]);
            $SCI_FloorNumber=($SCI_FloorNumber?$SCI_FloorNumber:$q["SCI_FloorNumber"]);
            $SCI_PlotorStreetNumber=($SCI_PlotorStreetNumber?$SCI_PlotorStreetNumber:$q["SCI_PlotorStreetNumber"]);
            $SCI_LCorStreetName=($SCI_LCorStreetName?$SCI_LCorStreetName:$q["SCI_LCorStreetName"]);
            $SCI_Parish=($SCI_Parish?$SCI_Parish:$q["SCI_Parish"]);
            $SCI_Suburb=($SCI_Suburb?$SCI_Suburb:$q["SCI_Suburb"]);
            $SCI_Village=($SCI_Village?$SCI_Village:$q["SCI_Village"]);
            $SCI_CountyorTown=($SCI_CountyorTown?$SCI_CountyorTown:$q["SCI_CountyorTown"]);
            $SCI_District=($SCI_District?$SCI_District:$q["SCI_District"]);
            $SCI_Region=($SCI_Region?$SCI_Region:$q["SCI_Region"]);
            $SCI_POBoxNumber=($SCI_POBoxNumber?$SCI_POBoxNumber:$q["SCI_POBoxNumber"]);
            $SCI_PostOfficeTown=($SCI_PostOfficeTown?$SCI_PostOfficeTown:$q["SCI_PostOfficeTown"]);
            $SCI_CountryCode=($SCI_CountryCode?$SCI_CountryCode:$q["SCI_CountryCode"]);
            $SCI_PeriodAtAddress=($SCI_PeriodAtAddress?$SCI_PeriodAtAddress:$q["SCI_PeriodAtAddress"]);
            $SCI_FlagofOwnership=($SCI_FlagofOwnership?$SCI_FlagofOwnership:$q["SCI_FlagofOwnership"]);
            $SCI_PrimaryCountryDiallingCode=($SCI_PrimaryCountryDiallingCode?$SCI_PrimaryCountryDiallingCode:$q["SCI_PrimaryCountryDiallingCode"]);
            $SCI_PrimaryAreaDiallingCode=($SCI_PrimaryAreaDiallingCode?$SCI_PrimaryAreaDiallingCode:$q["SCI_PrimaryAreaDiallingCode"]);
            $SCI_PrimaryTelephoneNumber=($SCI_PrimaryTelephoneNumber?$SCI_PrimaryTelephoneNumber:$q["SCI_PrimaryTelephoneNumber"]);
            $SCI_OtherCountryDiallingCode=($SCI_OtherCountryDiallingCode?$SCI_OtherCountryDiallingCode:$q["SCI_OtherCountryDiallingCode"]);
            $SCI_OtherAreaDiallingCode=($SCI_OtherAreaDiallingCode?$SCI_OtherAreaDiallingCode:$q["SCI_OtherAreaDiallingCode"]);
            $SCI_OtherTelephoneNumber=($SCI_OtherTelephoneNumber?$SCI_OtherTelephoneNumber:$q["SCI_OtherTelephoneNumber"]);
            $SCI_MobileCountryDiallingCode=($SCI_MobileCountryDiallingCode?$SCI_MobileCountryDiallingCode:$q["SCI_MobileCountryDiallingCode"]);
            $SCI_MobileAreaDiallingCode=($SCI_MobileAreaDiallingCode?$SCI_MobileAreaDiallingCode:$q["SCI_MobileAreaDiallingCode"]);
            $SCI_MobileNumber=($SCI_MobileNumber?$SCI_MobileNumber:$q["SCI_MobileNumber"]);
            $SCI_FAXCountryDiallingCode=($SCI_FAXCountryDiallingCode?$SCI_FAXCountryDiallingCode:$q["SCI_FAXCountryDiallingCode"]);
            $SCI_FAXAreaDiallingCode=($SCI_FAXAreaDiallingCode?$SCI_FAXAreaDiallingCode:$q["SCI_FAXAreaDiallingCode"]);
            $SCI_FAXNumber=($SCI_FAXNumber?$SCI_FAXNumber:$q["SCI_FAXNumber"]);
            $SCI_EmailAddress=($SCI_EmailAddress?$SCI_EmailAddress:$q["SCI_EmailAddress"]);
            $SCI_Website=($SCI_Website?$SCI_Website:$q["SCI_Website"]);
        }
	}
    
	//then search ClientNumber in vl_datacba109
	$query=0;
	$query=mysqlquery("select * from vl_datacba109 where ClientNumber='$clientNumber' order by created desc");
	if(mysqlnumrows($query)) {
		//flag
		$datafound=1;
        //get data
        $q=array();
        while($q=mysqlfetcharray($query)) {
            $II_RegistrationCertificateNumber=($II_RegistrationCertificateNumber?$II_RegistrationCertificateNumber:$q["II_RegistrationCertificateNumber"]);
            $II_TaxIdentificationNumber=($II_TaxIdentificationNumber?$II_TaxIdentificationNumber:$q["II_TaxIdentificationNumber"]);
            $II_ValueAddedTaxNumber=($II_ValueAddedTaxNumber?$II_ValueAddedTaxNumber:$q["II_ValueAddedTaxNumber"]);
            $II_FCSNumber=($II_FCSNumber?$II_FCSNumber:$q["II_FCSNumber"]);
            $II_PassportNumber=($II_PassportNumber?$II_PassportNumber:$q["II_PassportNumber"]);
            $II_DriversLicenceIDNumber=($II_DriversLicenceIDNumber?$II_DriversLicenceIDNumber:$q["II_DriversLicenceIDNumber"]);
            $II_VotersPERNO=($II_VotersPERNO?$II_VotersPERNO:$q["II_VotersPERNO"]);
            $II_DriversLicensePermitNumber=($II_DriversLicensePermitNumber?$II_DriversLicensePermitNumber:$q["II_DriversLicensePermitNumber"]);
            $II_NSSFNumber=($II_NSSFNumber?$II_NSSFNumber:$q["II_NSSFNumber"]);
            $II_CountryID=($II_CountryID?$II_CountryID:$q["II_CountryID"]);
            $II_CountryIssuingAuthority=($II_CountryIssuingAuthority?$II_CountryIssuingAuthority:$q["II_CountryIssuingAuthority"]);
            $II_Nationality=($II_Nationality?$II_Nationality:$q["II_Nationality"]);
            $II_PoliceIDNumber=($II_PoliceIDNumber?$II_PoliceIDNumber:$q["II_PoliceIDNumber"]);
            $II_UPDFNumber=($II_UPDFNumber?$II_UPDFNumber:$q["II_UPDFNumber"]);
            $II_KACITALicenseNumber=($II_KACITALicenseNumber?$II_KACITALicenseNumber:$q["II_KACITALicenseNumber"]);
            $II_CountryOfIssue=($II_CountryOfIssue?$II_CountryOfIssue:$q["II_CountryOfIssue"]);
            $GSCAFB_BusinessName=($GSCAFB_BusinessName?$GSCAFB_BusinessName:$q["GSCAFB_BusinessName"]);
            $GSCAFB_TradingName=($GSCAFB_TradingName?$GSCAFB_TradingName:$q["GSCAFB_TradingName"]);
            $GSCAFB_ActivityDescription=($GSCAFB_ActivityDescription?$GSCAFB_ActivityDescription:$q["GSCAFB_ActivityDescription"]);
            $GSCAFB_IndustrySectorCode=($GSCAFB_IndustrySectorCode?$GSCAFB_IndustrySectorCode:$q["GSCAFB_IndustrySectorCode"]);
            $GSCAFB_DateRegistered=($GSCAFB_DateRegistered?$GSCAFB_DateRegistered:$q["GSCAFB_DateRegistered"]);
            $GSCAFB_BusinessTypeCode=($GSCAFB_BusinessTypeCode?$GSCAFB_BusinessTypeCode:$q["GSCAFB_BusinessTypeCode"]);
            $GSCAFB_Surname=($GSCAFB_Surname?$GSCAFB_Surname:$q["GSCAFB_Surname"]);
            $GSCAFB_Forename1=($GSCAFB_Forename1?$GSCAFB_Forename1:$q["GSCAFB_Forename1"]);
            $GSCAFB_Forename2=($GSCAFB_Forename2?$GSCAFB_Forename2:$q["GSCAFB_Forename2"]);
            $GSCAFB_Forename3=($GSCAFB_Forename3?$GSCAFB_Forename3:$q["GSCAFB_Forename3"]);
            $GSCAFB_Gender=($GSCAFB_Gender?$GSCAFB_Gender:$q["GSCAFB_Gender"]);
            $GSCAFB_MaritalStatus=($GSCAFB_MaritalStatus?$GSCAFB_MaritalStatus:$q["GSCAFB_MaritalStatus"]);
            $GSCAFB_DateofBirth=($GSCAFB_DateofBirth?$GSCAFB_DateofBirth:$q["GSCAFB_DateofBirth"]);
            $EI_EmploymentType=($EI_EmploymentType?$EI_EmploymentType:$q["EI_EmploymentType"]);
            $EI_PrimaryOccupation=($EI_PrimaryOccupation?$EI_PrimaryOccupation:$q["EI_PrimaryOccupation"]);
            $EI_EmployerName=($EI_EmployerName?$EI_EmployerName:$q["EI_EmployerName"]);
            $EI_EmployeeNumber=($EI_EmployeeNumber?$EI_EmployeeNumber:$q["EI_EmployeeNumber"]);
            $EI_PeriodAtEmployer=($EI_PeriodAtEmployer?$EI_PeriodAtEmployer:$q["EI_PeriodAtEmployer"]);
            $EI_IncomeBand=($EI_IncomeBand?$EI_IncomeBand:$q["EI_IncomeBand"]);
            $EI_SalaryFrequency=($EI_SalaryFrequency?$EI_SalaryFrequency:$q["EI_SalaryFrequency"]);
            $PCI_UnitNumber=($PCI_UnitNumber?$PCI_UnitNumber:$q["PCI_UnitNumber"]);
            $PCI_UnitName=($PCI_UnitName?$PCI_UnitName:$q["PCI_UnitName"]);
            $PCI_FloorNumber=($PCI_FloorNumber?$PCI_FloorNumber:$q["PCI_FloorNumber"]);
            $PCI_PlotorStreetNumber=($PCI_PlotorStreetNumber?$PCI_PlotorStreetNumber:$q["PCI_PlotorStreetNumber"]);
            $PCI_LCorStreetName=($PCI_LCorStreetName?$PCI_LCorStreetName:$q["PCI_LCorStreetName"]);
            $PCI_Parish=($PCI_Parish?$PCI_Parish:$q["PCI_Parish"]);
            $PCI_Suburb=($PCI_Suburb?$PCI_Suburb:$q["PCI_Suburb"]);
            $PCI_Village=($PCI_Village?$PCI_Village:$q["PCI_Village"]);
            $PCI_CountyorTown=($PCI_CountyorTown?$PCI_CountyorTown:$q["PCI_CountyorTown"]);
            $PCI_District=($PCI_District?$PCI_District:$q["PCI_District"]);
            $PCI_Region=($PCI_Region?$PCI_Region:$q["PCI_Region"]);
            $PCI_POBoxNumber=($PCI_POBoxNumber?$PCI_POBoxNumber:$q["PCI_POBoxNumber"]);
            $PCI_PostOfficeTown=($PCI_PostOfficeTown?$PCI_PostOfficeTown:$q["PCI_PostOfficeTown"]);
            $PCI_CountryCode=($PCI_CountryCode?$PCI_CountryCode:$q["PCI_CountryCode"]);
            $PCI_PeriodAtAddress=($PCI_PeriodAtAddress?$PCI_PeriodAtAddress:$q["PCI_PeriodAtAddress"]);
            $PCI_FlagofOwnership=($PCI_FlagofOwnership?$PCI_FlagofOwnership:$q["PCI_FlagofOwnership"]);
            $PCI_PrimaryCountryDiallingCode=($PCI_PrimaryCountryDiallingCode?$PCI_PrimaryCountryDiallingCode:$q["PCI_PrimaryCountryDiallingCode"]);
            $PCI_PrimaryAreaDiallingCode=($PCI_PrimaryAreaDiallingCode?$PCI_PrimaryAreaDiallingCode:$q["PCI_PrimaryAreaDiallingCode"]);
            $PCI_PrimaryTelephoneNumber=($PCI_PrimaryTelephoneNumber?$PCI_PrimaryTelephoneNumber:$q["PCI_PrimaryTelephoneNumber"]);
            $PCI_OtherCountryDiallingCode=($PCI_OtherCountryDiallingCode?$PCI_OtherCountryDiallingCode:$q["PCI_OtherCountryDiallingCode"]);
            $PCI_OtherAreaDiallingCode=($PCI_OtherAreaDiallingCode?$PCI_OtherAreaDiallingCode:$q["PCI_OtherAreaDiallingCode"]);
            $PCI_OtherTelephoneNumber=($PCI_OtherTelephoneNumber?$PCI_OtherTelephoneNumber:$q["PCI_OtherTelephoneNumber"]);
            $PCI_MobileCountryDiallingCode=($PCI_MobileCountryDiallingCode?$PCI_MobileCountryDiallingCode:$q["PCI_MobileCountryDiallingCode"]);
            $PCI_MobileAreaDiallingCode=($PCI_MobileAreaDiallingCode?$PCI_MobileAreaDiallingCode:$q["PCI_MobileAreaDiallingCode"]);
            $PCI_MobileNumber=($PCI_MobileNumber?$PCI_MobileNumber:$q["PCI_MobileNumber"]);
            $PCI_FAXCountryDiallingCode=($PCI_FAXCountryDiallingCode?$PCI_FAXCountryDiallingCode:$q["PCI_FAXCountryDiallingCode"]);
            $PCI_FAXAreaDiallingCode=($PCI_FAXAreaDiallingCode?$PCI_FAXAreaDiallingCode:$q["PCI_FAXAreaDiallingCode"]);
            $PCI_FAXNumber=($PCI_FAXNumber?$PCI_FAXNumber:$q["PCI_FAXNumber"]);
            $PCI_EmailAddress=($PCI_EmailAddress?$PCI_EmailAddress:$q["PCI_EmailAddress"]);
            $PCI_Website=($PCI_Website?$PCI_Website:$q["PCI_Website"]);
            $SCI_UnitNumber=($SCI_UnitNumber?$SCI_UnitNumber:$q["SCI_UnitNumber"]);
            $SCI_UnitName=($SCI_UnitName?$SCI_UnitName:$q["SCI_UnitName"]);
            $SCI_FloorNumber=($SCI_FloorNumber?$SCI_FloorNumber:$q["SCI_FloorNumber"]);
            $SCI_PlotorStreetNumber=($SCI_PlotorStreetNumber?$SCI_PlotorStreetNumber:$q["SCI_PlotorStreetNumber"]);
            $SCI_LCorStreetName=($SCI_LCorStreetName?$SCI_LCorStreetName:$q["SCI_LCorStreetName"]);
            $SCI_Parish=($SCI_Parish?$SCI_Parish:$q["SCI_Parish"]);
            $SCI_Suburb=($SCI_Suburb?$SCI_Suburb:$q["SCI_Suburb"]);
            $SCI_Village=($SCI_Village?$SCI_Village:$q["SCI_Village"]);
            $SCI_CountyorTown=($SCI_CountyorTown?$SCI_CountyorTown:$q["SCI_CountyorTown"]);
            $SCI_District=($SCI_District?$SCI_District:$q["SCI_District"]);
            $SCI_Region=($SCI_Region?$SCI_Region:$q["SCI_Region"]);
            $SCI_POBoxNumber=($SCI_POBoxNumber?$SCI_POBoxNumber:$q["SCI_POBoxNumber"]);
            $SCI_PostOfficeTown=($SCI_PostOfficeTown?$SCI_PostOfficeTown:$q["SCI_PostOfficeTown"]);
            $SCI_CountryCode=($SCI_CountryCode?$SCI_CountryCode:$q["SCI_CountryCode"]);
            $SCI_PeriodAtAddress=($SCI_PeriodAtAddress?$SCI_PeriodAtAddress:$q["SCI_PeriodAtAddress"]);
            $SCI_FlagofOwnership=($SCI_FlagofOwnership?$SCI_FlagofOwnership:$q["SCI_FlagofOwnership"]);
            $SCI_PrimaryCountryDiallingCode=($SCI_PrimaryCountryDiallingCode?$SCI_PrimaryCountryDiallingCode:$q["SCI_PrimaryCountryDiallingCode"]);
            $SCI_PrimaryAreaDiallingCode=($SCI_PrimaryAreaDiallingCode?$SCI_PrimaryAreaDiallingCode:$q["SCI_PrimaryAreaDiallingCode"]);
            $SCI_PrimaryTelephoneNumber=($SCI_PrimaryTelephoneNumber?$SCI_PrimaryTelephoneNumber:$q["SCI_PrimaryTelephoneNumber"]);
            $SCI_OtherCountryDiallingCode=($SCI_OtherCountryDiallingCode?$SCI_OtherCountryDiallingCode:$q["SCI_OtherCountryDiallingCode"]);
            $SCI_OtherAreaDiallingCode=($SCI_OtherAreaDiallingCode?$SCI_OtherAreaDiallingCode:$q["SCI_OtherAreaDiallingCode"]);
            $SCI_OtherTelephoneNumber=($SCI_OtherTelephoneNumber?$SCI_OtherTelephoneNumber:$q["SCI_OtherTelephoneNumber"]);
            $SCI_MobileCountryDiallingCode=($SCI_MobileCountryDiallingCode?$SCI_MobileCountryDiallingCode:$q["SCI_MobileCountryDiallingCode"]);
            $SCI_MobileAreaDiallingCode=($SCI_MobileAreaDiallingCode?$SCI_MobileAreaDiallingCode:$q["SCI_MobileAreaDiallingCode"]);
            $SCI_MobileNumber=($SCI_MobileNumber?$SCI_MobileNumber:$q["SCI_MobileNumber"]);
            $SCI_FAXCountryDiallingCode=($SCI_FAXCountryDiallingCode?$SCI_FAXCountryDiallingCode:$q["SCI_FAXCountryDiallingCode"]);
            $SCI_FAXAreaDiallingCode=($SCI_FAXAreaDiallingCode?$SCI_FAXAreaDiallingCode:$q["SCI_FAXAreaDiallingCode"]);
            $SCI_FAXNumber=($SCI_FAXNumber?$SCI_FAXNumber:$q["SCI_FAXNumber"]);
            $SCI_EmailAddress=($SCI_EmailAddress?$SCI_EmailAddress:$q["SCI_EmailAddress"]);
            $SCI_Website=($SCI_Website?$SCI_Website:$q["SCI_Website"]);
        }
	}
    
	//then search ClientNumber in vl_databc109
	$query=0;
	$query=mysqlquery("select * from vl_databc109 where ClientNumber='$clientNumber' order by created desc");
	if(mysqlnumrows($query)) {
		//flag
		$datafound=1;
        //get data
        $q=array();
        while($q=mysqlfetcharray($query)) {
            $II_RegistrationCertificateNumber=($II_RegistrationCertificateNumber?$II_RegistrationCertificateNumber:$q["II_RegistrationCertificateNumber"]);
            $II_TaxIdentificationNumber=($II_TaxIdentificationNumber?$II_TaxIdentificationNumber:$q["II_TaxIdentificationNumber"]);
            $II_ValueAddedTaxNumber=($II_ValueAddedTaxNumber?$II_ValueAddedTaxNumber:$q["II_ValueAddedTaxNumber"]);
            $II_FCSNumber=($II_FCSNumber?$II_FCSNumber:$q["II_FCSNumber"]);
            $II_PassportNumber=($II_PassportNumber?$II_PassportNumber:$q["II_PassportNumber"]);
            $II_DriversLicenceIDNumber=($II_DriversLicenceIDNumber?$II_DriversLicenceIDNumber:$q["II_DriversLicenceIDNumber"]);
            $II_VotersPERNO=($II_VotersPERNO?$II_VotersPERNO:$q["II_VotersPERNO"]);
            $II_DriversLicensePermitNumber=($II_DriversLicensePermitNumber?$II_DriversLicensePermitNumber:$q["II_DriversLicensePermitNumber"]);
            $II_NSSFNumber=($II_NSSFNumber?$II_NSSFNumber:$q["II_NSSFNumber"]);
            $II_CountryID=($II_CountryID?$II_CountryID:$q["II_CountryID"]);
            $II_CountryIssuingAuthority=($II_CountryIssuingAuthority?$II_CountryIssuingAuthority:$q["II_CountryIssuingAuthority"]);
            $II_Nationality=($II_Nationality?$II_Nationality:$q["II_Nationality"]);
            $II_PoliceIDNumber=($II_PoliceIDNumber?$II_PoliceIDNumber:$q["II_PoliceIDNumber"]);
            $II_UPDFNumber=($II_UPDFNumber?$II_UPDFNumber:$q["II_UPDFNumber"]);
            $II_KACITALicenseNumber=($II_KACITALicenseNumber?$II_KACITALicenseNumber:$q["II_KACITALicenseNumber"]);
            $II_CountryOfIssue=($II_CountryOfIssue?$II_CountryOfIssue:$q["II_CountryOfIssue"]);
            $GSCAFB_BusinessName=($GSCAFB_BusinessName?$GSCAFB_BusinessName:$q["GSCAFB_BusinessName"]);
            $GSCAFB_TradingName=($GSCAFB_TradingName?$GSCAFB_TradingName:$q["GSCAFB_TradingName"]);
            $GSCAFB_ActivityDescription=($GSCAFB_ActivityDescription?$GSCAFB_ActivityDescription:$q["GSCAFB_ActivityDescription"]);
            $GSCAFB_IndustrySectorCode=($GSCAFB_IndustrySectorCode?$GSCAFB_IndustrySectorCode:$q["GSCAFB_IndustrySectorCode"]);
            $GSCAFB_DateRegistered=($GSCAFB_DateRegistered?$GSCAFB_DateRegistered:$q["GSCAFB_DateRegistered"]);
            $GSCAFB_BusinessTypeCode=($GSCAFB_BusinessTypeCode?$GSCAFB_BusinessTypeCode:$q["GSCAFB_BusinessTypeCode"]);
            $GSCAFB_Surname=($GSCAFB_Surname?$GSCAFB_Surname:$q["GSCAFB_Surname"]);
            $GSCAFB_Forename1=($GSCAFB_Forename1?$GSCAFB_Forename1:$q["GSCAFB_Forename1"]);
            $GSCAFB_Forename2=($GSCAFB_Forename2?$GSCAFB_Forename2:$q["GSCAFB_Forename2"]);
            $GSCAFB_Forename3=($GSCAFB_Forename3?$GSCAFB_Forename3:$q["GSCAFB_Forename3"]);
            $GSCAFB_Gender=($GSCAFB_Gender?$GSCAFB_Gender:$q["GSCAFB_Gender"]);
            $GSCAFB_MaritalStatus=($GSCAFB_MaritalStatus?$GSCAFB_MaritalStatus:$q["GSCAFB_MaritalStatus"]);
            $GSCAFB_DateofBirth=($GSCAFB_DateofBirth?$GSCAFB_DateofBirth:$q["GSCAFB_DateofBirth"]);
            $EI_EmploymentType=($EI_EmploymentType?$EI_EmploymentType:$q["EI_EmploymentType"]);
            $EI_PrimaryOccupation=($EI_PrimaryOccupation?$EI_PrimaryOccupation:$q["EI_PrimaryOccupation"]);
            $EI_EmployerName=($EI_EmployerName?$EI_EmployerName:$q["EI_EmployerName"]);
            $EI_EmployeeNumber=($EI_EmployeeNumber?$EI_EmployeeNumber:$q["EI_EmployeeNumber"]);
            $EI_PeriodAtEmployer=($EI_PeriodAtEmployer?$EI_PeriodAtEmployer:$q["EI_PeriodAtEmployer"]);
            $EI_IncomeBand=($EI_IncomeBand?$EI_IncomeBand:$q["EI_IncomeBand"]);
            $EI_SalaryFrequency=($EI_SalaryFrequency?$EI_SalaryFrequency:$q["EI_SalaryFrequency"]);
            $PCI_UnitNumber=($PCI_UnitNumber?$PCI_UnitNumber:$q["PCI_UnitNumber"]);
            $PCI_UnitName=($PCI_UnitName?$PCI_UnitName:$q["PCI_UnitName"]);
            $PCI_FloorNumber=($PCI_FloorNumber?$PCI_FloorNumber:$q["PCI_FloorNumber"]);
            $PCI_PlotorStreetNumber=($PCI_PlotorStreetNumber?$PCI_PlotorStreetNumber:$q["PCI_PlotorStreetNumber"]);
            $PCI_LCorStreetName=($PCI_LCorStreetName?$PCI_LCorStreetName:$q["PCI_LCorStreetName"]);
            $PCI_Parish=($PCI_Parish?$PCI_Parish:$q["PCI_Parish"]);
            $PCI_Suburb=($PCI_Suburb?$PCI_Suburb:$q["PCI_Suburb"]);
            $PCI_Village=($PCI_Village?$PCI_Village:$q["PCI_Village"]);
            $PCI_CountyorTown=($PCI_CountyorTown?$PCI_CountyorTown:$q["PCI_CountyorTown"]);
            $PCI_District=($PCI_District?$PCI_District:$q["PCI_District"]);
            $PCI_Region=($PCI_Region?$PCI_Region:$q["PCI_Region"]);
            $PCI_POBoxNumber=($PCI_POBoxNumber?$PCI_POBoxNumber:$q["PCI_POBoxNumber"]);
            $PCI_PostOfficeTown=($PCI_PostOfficeTown?$PCI_PostOfficeTown:$q["PCI_PostOfficeTown"]);
            $PCI_CountryCode=($PCI_CountryCode?$PCI_CountryCode:$q["PCI_CountryCode"]);
            $PCI_PeriodAtAddress=($PCI_PeriodAtAddress?$PCI_PeriodAtAddress:$q["PCI_PeriodAtAddress"]);
            $PCI_FlagofOwnership=($PCI_FlagofOwnership?$PCI_FlagofOwnership:$q["PCI_FlagofOwnership"]);
            $PCI_PrimaryCountryDiallingCode=($PCI_PrimaryCountryDiallingCode?$PCI_PrimaryCountryDiallingCode:$q["PCI_PrimaryCountryDiallingCode"]);
            $PCI_PrimaryAreaDiallingCode=($PCI_PrimaryAreaDiallingCode?$PCI_PrimaryAreaDiallingCode:$q["PCI_PrimaryAreaDiallingCode"]);
            $PCI_PrimaryTelephoneNumber=($PCI_PrimaryTelephoneNumber?$PCI_PrimaryTelephoneNumber:$q["PCI_PrimaryTelephoneNumber"]);
            $PCI_OtherCountryDiallingCode=($PCI_OtherCountryDiallingCode?$PCI_OtherCountryDiallingCode:$q["PCI_OtherCountryDiallingCode"]);
            $PCI_OtherAreaDiallingCode=($PCI_OtherAreaDiallingCode?$PCI_OtherAreaDiallingCode:$q["PCI_OtherAreaDiallingCode"]);
            $PCI_OtherTelephoneNumber=($PCI_OtherTelephoneNumber?$PCI_OtherTelephoneNumber:$q["PCI_OtherTelephoneNumber"]);
            $PCI_MobileCountryDiallingCode=($PCI_MobileCountryDiallingCode?$PCI_MobileCountryDiallingCode:$q["PCI_MobileCountryDiallingCode"]);
            $PCI_MobileAreaDiallingCode=($PCI_MobileAreaDiallingCode?$PCI_MobileAreaDiallingCode:$q["PCI_MobileAreaDiallingCode"]);
            $PCI_MobileNumber=($PCI_MobileNumber?$PCI_MobileNumber:$q["PCI_MobileNumber"]);
            $PCI_FAXCountryDiallingCode=($PCI_FAXCountryDiallingCode?$PCI_FAXCountryDiallingCode:$q["PCI_FAXCountryDiallingCode"]);
            $PCI_FAXAreaDiallingCode=($PCI_FAXAreaDiallingCode?$PCI_FAXAreaDiallingCode:$q["PCI_FAXAreaDiallingCode"]);
            $PCI_FAXNumber=($PCI_FAXNumber?$PCI_FAXNumber:$q["PCI_FAXNumber"]);
            $PCI_EmailAddress=($PCI_EmailAddress?$PCI_EmailAddress:$q["PCI_EmailAddress"]);
            $PCI_Website=($PCI_Website?$PCI_Website:$q["PCI_Website"]);
            $SCI_UnitNumber=($SCI_UnitNumber?$SCI_UnitNumber:$q["SCI_UnitNumber"]);
            $SCI_UnitName=($SCI_UnitName?$SCI_UnitName:$q["SCI_UnitName"]);
            $SCI_FloorNumber=($SCI_FloorNumber?$SCI_FloorNumber:$q["SCI_FloorNumber"]);
            $SCI_PlotorStreetNumber=($SCI_PlotorStreetNumber?$SCI_PlotorStreetNumber:$q["SCI_PlotorStreetNumber"]);
            $SCI_LCorStreetName=($SCI_LCorStreetName?$SCI_LCorStreetName:$q["SCI_LCorStreetName"]);
            $SCI_Parish=($SCI_Parish?$SCI_Parish:$q["SCI_Parish"]);
            $SCI_Suburb=($SCI_Suburb?$SCI_Suburb:$q["SCI_Suburb"]);
            $SCI_Village=($SCI_Village?$SCI_Village:$q["SCI_Village"]);
            $SCI_CountyorTown=($SCI_CountyorTown?$SCI_CountyorTown:$q["SCI_CountyorTown"]);
            $SCI_District=($SCI_District?$SCI_District:$q["SCI_District"]);
            $SCI_Region=($SCI_Region?$SCI_Region:$q["SCI_Region"]);
            $SCI_POBoxNumber=($SCI_POBoxNumber?$SCI_POBoxNumber:$q["SCI_POBoxNumber"]);
            $SCI_PostOfficeTown=($SCI_PostOfficeTown?$SCI_PostOfficeTown:$q["SCI_PostOfficeTown"]);
            $SCI_CountryCode=($SCI_CountryCode?$SCI_CountryCode:$q["SCI_CountryCode"]);
            $SCI_PeriodAtAddress=($SCI_PeriodAtAddress?$SCI_PeriodAtAddress:$q["SCI_PeriodAtAddress"]);
            $SCI_FlagofOwnership=($SCI_FlagofOwnership?$SCI_FlagofOwnership:$q["SCI_FlagofOwnership"]);
            $SCI_PrimaryCountryDiallingCode=($SCI_PrimaryCountryDiallingCode?$SCI_PrimaryCountryDiallingCode:$q["SCI_PrimaryCountryDiallingCode"]);
            $SCI_PrimaryAreaDiallingCode=($SCI_PrimaryAreaDiallingCode?$SCI_PrimaryAreaDiallingCode:$q["SCI_PrimaryAreaDiallingCode"]);
            $SCI_PrimaryTelephoneNumber=($SCI_PrimaryTelephoneNumber?$SCI_PrimaryTelephoneNumber:$q["SCI_PrimaryTelephoneNumber"]);
            $SCI_OtherCountryDiallingCode=($SCI_OtherCountryDiallingCode?$SCI_OtherCountryDiallingCode:$q["SCI_OtherCountryDiallingCode"]);
            $SCI_OtherAreaDiallingCode=($SCI_OtherAreaDiallingCode?$SCI_OtherAreaDiallingCode:$q["SCI_OtherAreaDiallingCode"]);
            $SCI_OtherTelephoneNumber=($SCI_OtherTelephoneNumber?$SCI_OtherTelephoneNumber:$q["SCI_OtherTelephoneNumber"]);
            $SCI_MobileCountryDiallingCode=($SCI_MobileCountryDiallingCode?$SCI_MobileCountryDiallingCode:$q["SCI_MobileCountryDiallingCode"]);
            $SCI_MobileAreaDiallingCode=($SCI_MobileAreaDiallingCode?$SCI_MobileAreaDiallingCode:$q["SCI_MobileAreaDiallingCode"]);
            $SCI_MobileNumber=($SCI_MobileNumber?$SCI_MobileNumber:$q["SCI_MobileNumber"]);
            $SCI_FAXCountryDiallingCode=($SCI_FAXCountryDiallingCode?$SCI_FAXCountryDiallingCode:$q["SCI_FAXCountryDiallingCode"]);
            $SCI_FAXAreaDiallingCode=($SCI_FAXAreaDiallingCode?$SCI_FAXAreaDiallingCode:$q["SCI_FAXAreaDiallingCode"]);
            $SCI_FAXNumber=($SCI_FAXNumber?$SCI_FAXNumber:$q["SCI_FAXNumber"]);
            $SCI_EmailAddress=($SCI_EmailAddress?$SCI_EmailAddress:$q["SCI_EmailAddress"]);
            $SCI_Website=($SCI_Website?$SCI_Website:$q["SCI_Website"]);
        }
	}
    
	//then search ClientNumber in vl_datacap106
	$query=0;
	$query=mysqlquery("select * from vl_datacap106 where ClientNumber='$clientNumber' order by created desc");
	if(mysqlnumrows($query)) {
		//flag
		$datafound=1;
        //get data
        $q=array();
        while($q=mysqlfetcharray($query)) {
            $II_RegistrationCertificateNumber=($II_RegistrationCertificateNumber?$II_RegistrationCertificateNumber:$q["II_RegistrationCertificateNumber"]);
            $II_TaxIdentificationNumber=($II_TaxIdentificationNumber?$II_TaxIdentificationNumber:$q["II_TaxIdentificationNumber"]);
            $II_ValueAddedTaxNumber=($II_ValueAddedTaxNumber?$II_ValueAddedTaxNumber:$q["II_ValueAddedTaxNumber"]);
            $II_FCSNumber=($II_FCSNumber?$II_FCSNumber:$q["II_FCSNumber"]);
            $II_PassportNumber=($II_PassportNumber?$II_PassportNumber:$q["II_PassportNumber"]);
            $II_DriversLicenceIDNumber=($II_DriversLicenceIDNumber?$II_DriversLicenceIDNumber:$q["II_DriversLicence"]);
            $II_VotersPERNO=($II_VotersPERNO?$II_VotersPERNO:$q["II_VotersPERNO"]);
            $II_DriversLicensePermitNumber=($II_DriversLicensePermitNumber?$II_DriversLicensePermitNumber:$q["II_DriversLicence"]);
            $II_NSSFNumber="";
            $II_CountryID="";
            $II_CountryIssuingAuthority="";
            $II_Nationality="";
            $II_PoliceIDNumber="";
            $II_UPDFNumber="";
            $II_KACITALicenseNumber="";
            $II_CountryOfIssue="";
            $GSCAFB_BusinessName=($GSCAFB_BusinessName?$GSCAFB_BusinessName:$q["GSCAFB_BusinessName"]);
            $GSCAFB_TradingName=($GSCAFB_TradingName?$GSCAFB_TradingName:$q["GSCAFB_TradingName"]);
            $GSCAFB_ActivityDescription=($GSCAFB_ActivityDescription?$GSCAFB_ActivityDescription:$q["GSCAFB_ActivityDescription"]);
            $GSCAFB_IndustrySectorCode=($GSCAFB_IndustrySectorCode?$GSCAFB_IndustrySectorCode:$q["GSCAFB_IndustrySectorCode"]);
            $GSCAFB_DateRegistered=($GSCAFB_DateRegistered?$GSCAFB_DateRegistered:$q["GSCAFB_DateRegistered"]);
            $GSCAFB_BusinessTypeCode=($GSCAFB_BusinessTypeCode?$GSCAFB_BusinessTypeCode:$q["GSCAFB_BusinessTypeCode"]);
            $GSCAFB_Surname=($GSCAFB_Surname?$GSCAFB_Surname:$q["GSCAFB_Surname"]);
            $GSCAFB_Forename1=($GSCAFB_Forename1?$GSCAFB_Forename1:$q["GSCAFB_Forename1"]);
            $GSCAFB_Forename2=($GSCAFB_Forename2?$GSCAFB_Forename2:$q["GSCAFB_Forename2"]);
            $GSCAFB_Forename3=($GSCAFB_Forename3?$GSCAFB_Forename3:$q["GSCAFB_Forename3"]);
            $GSCAFB_Gender=($GSCAFB_Gender?$GSCAFB_Gender:$q["GSCAFB_Gender"]);
            $GSCAFB_MaritalStatus=($GSCAFB_MaritalStatus?$GSCAFB_MaritalStatus:$q["GSCAFB_MaritalStatus"]);
            $GSCAFB_DateofBirth=($GSCAFB_DateofBirth?$GSCAFB_DateofBirth:$q["GSCAFB_DateofBirth"]);
            $EI_EmploymentType=($EI_EmploymentType?$EI_EmploymentType:$q["EI_EmploymentType"]);
            $EI_PrimaryOccupation=($EI_PrimaryOccupation?$EI_PrimaryOccupation:$q["EI_PrimaryOccupation"]);
            $EI_EmployerName=($EI_EmployerName?$EI_EmployerName:$q["EI_EmployerName"]);
            $EI_EmployeeNumber=($EI_EmployeeNumber?$EI_EmployeeNumber:$q["EI_EmployeeNumber"]);
            $EI_PeriodAtEmployer=($EI_PeriodAtEmployer?$EI_PeriodAtEmployer:$q["EI_PeriodAtEmployer"]);
            $EI_IncomeBand=($EI_IncomeBand?$EI_IncomeBand:$q["EI_IncomeBand"]);
            $EI_SalaryFrequency=($EI_SalaryFrequency?$EI_SalaryFrequency:$q["EI_SalaryFrequency"]);
            $PCI_UnitNumber=($PCI_UnitNumber?$PCI_UnitNumber:$q["PCI_UnitNumber"]);
            $PCI_UnitName=($PCI_UnitName?$PCI_UnitName:$q["PCI_UnitName"]);
            $PCI_FloorNumber=($PCI_FloorNumber?$PCI_FloorNumber:$q["PCI_FloorNumber"]);
            $PCI_PlotorStreetNumber=($PCI_PlotorStreetNumber?$PCI_PlotorStreetNumber:$q["PCI_StreetNumber"]);
            $PCI_LCorStreetName=($PCI_LCorStreetName?$PCI_LCorStreetName:$q["PCI_StreetName"]);
            $PCI_Parish="";
            $PCI_Suburb=($PCI_Suburb?$PCI_Suburb:$q["PCI_VillageOrSuburb"]);
            $PCI_Village=($PCI_Village?$PCI_Village:$q["PCI_VillageOrSuburb"]);
            $PCI_CountyorTown=($PCI_CountyorTown?$PCI_CountyorTown:$q["PCI_Town"]);
            $PCI_District=($PCI_District?$PCI_District:$q["PCI_District"]);
            $PCI_Region=($PCI_Region?$PCI_Region:$q["PCI_Region"]);
            $PCI_POBoxNumber=($PCI_POBoxNumber?$PCI_POBoxNumber:$q["PCI_PostalAddressNumber"]);
            $PCI_PostOfficeTown=($PCI_PostOfficeTown?$PCI_PostOfficeTown:$q["PCI_PostalAddressNumber"]);
            $PCI_CountryCode=($PCI_CountryCode?$PCI_CountryCode:$q["PCI_CountryCode"]);
            $PCI_PeriodAtAddress=($PCI_PeriodAtAddress?$PCI_PeriodAtAddress:$q["PCI_PeriodAtAddress"]);
            $PCI_FlagofOwnership=($PCI_FlagofOwnership?$PCI_FlagofOwnership:$q["PCI_Flagforownership"]);
            $PCI_PrimaryCountryDiallingCode=($PCI_PrimaryCountryDiallingCode?$PCI_PrimaryCountryDiallingCode:$q["PCI_PrimaryCountryDiallingCode"]);
            $PCI_PrimaryAreaDiallingCode=($PCI_PrimaryAreaDiallingCode?$PCI_PrimaryAreaDiallingCode:$q["PCI_PrimaryAreaDiallingCode"]);
            $PCI_PrimaryTelephoneNumber=($PCI_PrimaryTelephoneNumber?$PCI_PrimaryTelephoneNumber:$q["PCI_PrimaryTelephoneNumber"]);
            $PCI_OtherCountryDiallingCode=($PCI_OtherCountryDiallingCode?$PCI_OtherCountryDiallingCode:$q["PCI_OtherCountryDiallingCode"]);
            $PCI_OtherAreaDiallingCode=($PCI_OtherAreaDiallingCode?$PCI_OtherAreaDiallingCode:$q["PCI_OtherAreaDiallingCode"]);
            $PCI_OtherTelephoneNumber=($PCI_OtherTelephoneNumber?$PCI_OtherTelephoneNumber:$q["PCI_OtherTelephoneNumber"]);
            $PCI_MobileCountryDiallingCode=($PCI_MobileCountryDiallingCode?$PCI_MobileCountryDiallingCode:$q["PCI_MobileCountryDiallingCode"]);
            $PCI_MobileAreaDiallingCode=($PCI_MobileAreaDiallingCode?$PCI_MobileAreaDiallingCode:$q["PCI_MobileAreaDiallingCode"]);
            $PCI_MobileNumber=($PCI_MobileNumber?$PCI_MobileNumber:$q["PCI_MobileNumber"]);
            $PCI_FAXCountryDiallingCode=($PCI_FAXCountryDiallingCode?$PCI_FAXCountryDiallingCode:$q["PCI_FAXCountryDiallingCode"]);
            $PCI_FAXAreaDiallingCode=($PCI_FAXAreaDiallingCode?$PCI_FAXAreaDiallingCode:$q["PCI_FAXAreaDiallingCode"]);
            $PCI_FAXNumber=($PCI_FAXNumber?$PCI_FAXNumber:$q["PCI_FAXNumber"]);
            $PCI_EmailAddress=($PCI_EmailAddress?$PCI_EmailAddress:$q["PCI_EmailAddress"]);
            $PCI_Website=($PCI_Website?$PCI_Website:$q["PCI_Website"]);
            $SCI_UnitNumber=($SCI_UnitNumber?$SCI_UnitNumber:$q["SCI_UnitNumber"]);
            $SCI_UnitName=($SCI_UnitName?$SCI_UnitName:$q["SCI_UnitName"]);
            $SCI_FloorNumber=($SCI_FloorNumber?$SCI_FloorNumber:$q["SCI_FloorNumber"]);
            $SCI_PlotorStreetNumber=($SCI_PlotorStreetNumber?$SCI_PlotorStreetNumber:$q["SCI_StreetNumber"]);
            $SCI_LCorStreetName=($SCI_LCorStreetName?$SCI_LCorStreetName:$q["SCI_StreetName"]);
            $SCI_Parish="";
            $SCI_Suburb=($SCI_Suburb?$SCI_Suburb:$q["SCI_VillageOrSuburb"]);
            $SCI_Village=($SCI_Village?$SCI_Village:$q["SCI_VillageOrSuburb"]);
            $SCI_CountyorTown=($SCI_CountyorTown?$SCI_CountyorTown:$q["SCI_Town"]);
            $SCI_District=($SCI_District?$SCI_District:$q["SCI_District"]);
            $SCI_Region=($SCI_Region?$SCI_Region:$q["SCI_Region"]);
            $SCI_POBoxNumber=($SCI_POBoxNumber?$SCI_POBoxNumber:$q["SCI_PostalAddressNumber"]);
            $SCI_PostOfficeTown=($SCI_PostOfficeTown?$SCI_PostOfficeTown:$q["SCI_PostalAddressNumber"]);
            $SCI_CountryCode=($SCI_CountryCode?$SCI_CountryCode:$q["SCI_CountryCode"]);
            $SCI_PeriodAtAddress=($SCI_PeriodAtAddress?$SCI_PeriodAtAddress:$q["SCI_PeriodAtAddress"]);
            $SCI_FlagofOwnership=($SCI_FlagofOwnership?$SCI_FlagofOwnership:$q["SCI_Flagforownership"]);
            $SCI_PrimaryCountryDiallingCode=($SCI_PrimaryCountryDiallingCode?$SCI_PrimaryCountryDiallingCode:$q["SCI_PrimaryCountryDiallingCode"]);
            $SCI_PrimaryAreaDiallingCode=($SCI_PrimaryAreaDiallingCode?$SCI_PrimaryAreaDiallingCode:$q["SCI_PrimaryAreaDiallingCode"]);
            $SCI_PrimaryTelephoneNumber=($SCI_PrimaryTelephoneNumber?$SCI_PrimaryTelephoneNumber:$q["SCI_PrimaryTelephoneNumber"]);
            $SCI_OtherCountryDiallingCode=($SCI_OtherCountryDiallingCode?$SCI_OtherCountryDiallingCode:$q["SCI_OtherCountryDiallingCode"]);
            $SCI_OtherAreaDiallingCode=($SCI_OtherAreaDiallingCode?$SCI_OtherAreaDiallingCode:$q["SCI_OtherAreaDiallingCode"]);
            $SCI_OtherTelephoneNumber=($SCI_OtherTelephoneNumber?$SCI_OtherTelephoneNumber:$q["SCI_OtherTelephoneNumber"]);
            $SCI_MobileCountryDiallingCode=($SCI_MobileCountryDiallingCode?$SCI_MobileCountryDiallingCode:$q["SCI_MobileCountryDiallingCode"]);
            $SCI_MobileAreaDiallingCode=($SCI_MobileAreaDiallingCode?$SCI_MobileAreaDiallingCode:$q["SCI_MobileAreaDiallingCode"]);
            $SCI_MobileNumber=($SCI_MobileNumber?$SCI_MobileNumber:$q["SCI_MobileNumber"]);
            $SCI_FAXCountryDiallingCode=($SCI_FAXCountryDiallingCode?$SCI_FAXCountryDiallingCode:$q["SCI_FAXCountryDiallingCode"]);
            $SCI_FAXAreaDiallingCode=($SCI_FAXAreaDiallingCode?$SCI_FAXAreaDiallingCode:$q["SCI_FAXAreaDiallingCode"]);
            $SCI_FAXNumber=($SCI_FAXNumber?$SCI_FAXNumber:$q["SCI_FAXNumber"]);
            $SCI_EmailAddress=($SCI_EmailAddress?$SCI_EmailAddress:$q["SCI_EmailAddress"]);
            $SCI_Website=($SCI_Website?$SCI_Website:$q["SCI_Website"]);
        }
	}
    
	//then search ClientNumber in vl_datacba106
	$query=0;
	$query=mysqlquery("select * from vl_datacba106 where ClientNumber='$clientNumber' order by created desc");
	if(mysqlnumrows($query)) {
		//flag
		$datafound=1;
        //get data
        $q=array();
        while($q=mysqlfetcharray($query)) {
            $II_RegistrationCertificateNumber=($II_RegistrationCertificateNumber?$II_RegistrationCertificateNumber:$q["II_RegistrationCertificateNumber"]);
            $II_TaxIdentificationNumber=($II_TaxIdentificationNumber?$II_TaxIdentificationNumber:$q["II_TaxIdentificationNumber"]);
            $II_ValueAddedTaxNumber=($II_ValueAddedTaxNumber?$II_ValueAddedTaxNumber:$q["II_ValueAddedTaxNumber"]);
            $II_FCSNumber=($II_FCSNumber?$II_FCSNumber:$q["II_FCSNumber"]);
            $II_PassportNumber=($II_PassportNumber?$II_PassportNumber:$q["II_PassportNumber"]);
            $II_DriversLicenceIDNumber=($II_DriversLicenceIDNumber?$II_DriversLicenceIDNumber:$q["II_DriversLicence"]);
            $II_VotersPERNO=($II_VotersPERNO?$II_VotersPERNO:$q["II_VotersPERNO"]);
            $II_DriversLicensePermitNumber=($II_DriversLicensePermitNumber?$II_DriversLicensePermitNumber:$q["II_DriversLicence"]);
            $II_NSSFNumber="";
            $II_CountryID="";
            $II_CountryIssuingAuthority="";
            $II_Nationality="";
            $II_PoliceIDNumber="";
            $II_UPDFNumber="";
            $II_KACITALicenseNumber="";
            $II_CountryOfIssue="";
            $GSCAFB_BusinessName=($GSCAFB_BusinessName?$GSCAFB_BusinessName:$q["GSCAFB_BusinessName"]);
            $GSCAFB_TradingName=($GSCAFB_TradingName?$GSCAFB_TradingName:$q["GSCAFB_TradingName"]);
            $GSCAFB_ActivityDescription=($GSCAFB_ActivityDescription?$GSCAFB_ActivityDescription:$q["GSCAFB_ActivityDescription"]);
            $GSCAFB_IndustrySectorCode=($GSCAFB_IndustrySectorCode?$GSCAFB_IndustrySectorCode:$q["GSCAFB_IndustrySectorCode"]);
            $GSCAFB_DateRegistered=($GSCAFB_DateRegistered?$GSCAFB_DateRegistered:$q["GSCAFB_DateRegistered"]);
            $GSCAFB_BusinessTypeCode=($GSCAFB_BusinessTypeCode?$GSCAFB_BusinessTypeCode:$q["GSCAFB_BusinessTypeCode"]);
            $GSCAFB_Surname=($GSCAFB_Surname?$GSCAFB_Surname:$q["GSCAFB_Surname"]);
            $GSCAFB_Forename1=($GSCAFB_Forename1?$GSCAFB_Forename1:$q["GSCAFB_Forename1"]);
            $GSCAFB_Forename2=($GSCAFB_Forename2?$GSCAFB_Forename2:$q["GSCAFB_Forename2"]);
            $GSCAFB_Forename3=($GSCAFB_Forename3?$GSCAFB_Forename3:$q["GSCAFB_Forename3"]);
            $GSCAFB_Gender=($GSCAFB_Gender?$GSCAFB_Gender:$q["GSCAFB_Gender"]);
            $GSCAFB_MaritalStatus=($GSCAFB_MaritalStatus?$GSCAFB_MaritalStatus:$q["GSCAFB_MaritalStatus"]);
            $GSCAFB_DateofBirth=($GSCAFB_DateofBirth?$GSCAFB_DateofBirth:$q["GSCAFB_DateofBirth"]);
            $EI_EmploymentType=($EI_EmploymentType?$EI_EmploymentType:$q["EI_EmploymentType"]);
            $EI_PrimaryOccupation=($EI_PrimaryOccupation?$EI_PrimaryOccupation:$q["EI_PrimaryOccupation"]);
            $EI_EmployerName=($EI_EmployerName?$EI_EmployerName:$q["EI_EmployerName"]);
            $EI_EmployeeNumber=($EI_EmployeeNumber?$EI_EmployeeNumber:$q["EI_EmployeeNumber"]);
            $EI_PeriodAtEmployer=($EI_PeriodAtEmployer?$EI_PeriodAtEmployer:$q["EI_PeriodAtEmployer"]);
            $EI_IncomeBand=($EI_IncomeBand?$EI_IncomeBand:$q["EI_IncomeBand"]);
            $EI_SalaryFrequency=($EI_SalaryFrequency?$EI_SalaryFrequency:$q["EI_SalaryFrequency"]);
            $PCI_UnitNumber=($PCI_UnitNumber?$PCI_UnitNumber:$q["PCI_UnitNumber"]);
            $PCI_UnitName=($PCI_UnitName?$PCI_UnitName:$q["PCI_UnitName"]);
            $PCI_FloorNumber=($PCI_FloorNumber?$PCI_FloorNumber:$q["PCI_FloorNumber"]);
            $PCI_PlotorStreetNumber=($PCI_PlotorStreetNumber?$PCI_PlotorStreetNumber:$q["PCI_StreetNumber"]);
            $PCI_LCorStreetName=($PCI_LCorStreetName?$PCI_LCorStreetName:$q["PCI_StreetName"]);
            $PCI_Parish="";
            $PCI_Suburb=($PCI_Suburb?$PCI_Suburb:$q["PCI_VillageOrSuburb"]);
            $PCI_Village=($PCI_Village?$PCI_Village:$q["PCI_VillageOrSuburb"]);
            $PCI_CountyorTown=($PCI_CountyorTown?$PCI_CountyorTown:$q["PCI_Town"]);
            $PCI_District=($PCI_District?$PCI_District:$q["PCI_District"]);
            $PCI_Region=($PCI_Region?$PCI_Region:$q["PCI_Region"]);
            $PCI_POBoxNumber=($PCI_POBoxNumber?$PCI_POBoxNumber:$q["PCI_PostalAddressNumber"]);
            $PCI_PostOfficeTown=($PCI_PostOfficeTown?$PCI_PostOfficeTown:$q["PCI_PostalAddressNumber"]);
            $PCI_CountryCode=($PCI_CountryCode?$PCI_CountryCode:$q["PCI_CountryCode"]);
            $PCI_PeriodAtAddress=($PCI_PeriodAtAddress?$PCI_PeriodAtAddress:$q["PCI_PeriodAtAddress"]);
            $PCI_FlagofOwnership=($PCI_FlagofOwnership?$PCI_FlagofOwnership:$q["PCI_Flagforownership"]);
            $PCI_PrimaryCountryDiallingCode=($PCI_PrimaryCountryDiallingCode?$PCI_PrimaryCountryDiallingCode:$q["PCI_PrimaryCountryDiallingCode"]);
            $PCI_PrimaryAreaDiallingCode=($PCI_PrimaryAreaDiallingCode?$PCI_PrimaryAreaDiallingCode:$q["PCI_PrimaryAreaDiallingCode"]);
            $PCI_PrimaryTelephoneNumber=($PCI_PrimaryTelephoneNumber?$PCI_PrimaryTelephoneNumber:$q["PCI_PrimaryTelephoneNumber"]);
            $PCI_OtherCountryDiallingCode=($PCI_OtherCountryDiallingCode?$PCI_OtherCountryDiallingCode:$q["PCI_OtherCountryDiallingCode"]);
            $PCI_OtherAreaDiallingCode=($PCI_OtherAreaDiallingCode?$PCI_OtherAreaDiallingCode:$q["PCI_OtherAreaDiallingCode"]);
            $PCI_OtherTelephoneNumber=($PCI_OtherTelephoneNumber?$PCI_OtherTelephoneNumber:$q["PCI_OtherTelephoneNumber"]);
            $PCI_MobileCountryDiallingCode=($PCI_MobileCountryDiallingCode?$PCI_MobileCountryDiallingCode:$q["PCI_MobileCountryDiallingCode"]);
            $PCI_MobileAreaDiallingCode=($PCI_MobileAreaDiallingCode?$PCI_MobileAreaDiallingCode:$q["PCI_MobileAreaDiallingCode"]);
            $PCI_MobileNumber=($PCI_MobileNumber?$PCI_MobileNumber:$q["PCI_MobileNumber"]);
            $PCI_FAXCountryDiallingCode=($PCI_FAXCountryDiallingCode?$PCI_FAXCountryDiallingCode:$q["PCI_FAXCountryDiallingCode"]);
            $PCI_FAXAreaDiallingCode=($PCI_FAXAreaDiallingCode?$PCI_FAXAreaDiallingCode:$q["PCI_FAXAreaDiallingCode"]);
            $PCI_FAXNumber=($PCI_FAXNumber?$PCI_FAXNumber:$q["PCI_FAXNumber"]);
            $PCI_EmailAddress=($PCI_EmailAddress?$PCI_EmailAddress:$q["PCI_EmailAddress"]);
            $PCI_Website=($PCI_Website?$PCI_Website:$q["PCI_Website"]);
            $SCI_UnitNumber=($SCI_UnitNumber?$SCI_UnitNumber:$q["SCI_UnitNumber"]);
            $SCI_UnitName=($SCI_UnitName?$SCI_UnitName:$q["SCI_UnitName"]);
            $SCI_FloorNumber=($SCI_FloorNumber?$SCI_FloorNumber:$q["SCI_FloorNumber"]);
            $SCI_PlotorStreetNumber=($SCI_PlotorStreetNumber?$SCI_PlotorStreetNumber:$q["SCI_StreetNumber"]);
            $SCI_LCorStreetName=($SCI_LCorStreetName?$SCI_LCorStreetName:$q["SCI_StreetName"]);
            $SCI_Parish="";
            $SCI_Suburb=($SCI_Suburb?$SCI_Suburb:$q["SCI_VillageOrSuburb"]);
            $SCI_Village=($SCI_Village?$SCI_Village:$q["SCI_VillageOrSuburb"]);
            $SCI_CountyorTown=($SCI_CountyorTown?$SCI_CountyorTown:$q["SCI_Town"]);
            $SCI_District=($SCI_District?$SCI_District:$q["SCI_District"]);
            $SCI_Region=($SCI_Region?$SCI_Region:$q["SCI_Region"]);
            $SCI_POBoxNumber=($SCI_POBoxNumber?$SCI_POBoxNumber:$q["SCI_PostalAddressNumber"]);
            $SCI_PostOfficeTown=($SCI_PostOfficeTown?$SCI_PostOfficeTown:$q["SCI_PostalAddressNumber"]);
            $SCI_CountryCode=($SCI_CountryCode?$SCI_CountryCode:$q["SCI_CountryCode"]);
            $SCI_PeriodAtAddress=($SCI_PeriodAtAddress?$SCI_PeriodAtAddress:$q["SCI_PeriodAtAddress"]);
            $SCI_FlagofOwnership=($SCI_FlagofOwnership?$SCI_FlagofOwnership:$q["SCI_Flagforownership"]);
            $SCI_PrimaryCountryDiallingCode=($SCI_PrimaryCountryDiallingCode?$SCI_PrimaryCountryDiallingCode:$q["SCI_PrimaryCountryDiallingCode"]);
            $SCI_PrimaryAreaDiallingCode=($SCI_PrimaryAreaDiallingCode?$SCI_PrimaryAreaDiallingCode:$q["SCI_PrimaryAreaDiallingCode"]);
            $SCI_PrimaryTelephoneNumber=($SCI_PrimaryTelephoneNumber?$SCI_PrimaryTelephoneNumber:$q["SCI_PrimaryTelephoneNumber"]);
            $SCI_OtherCountryDiallingCode=($SCI_OtherCountryDiallingCode?$SCI_OtherCountryDiallingCode:$q["SCI_OtherCountryDiallingCode"]);
            $SCI_OtherAreaDiallingCode=($SCI_OtherAreaDiallingCode?$SCI_OtherAreaDiallingCode:$q["SCI_OtherAreaDiallingCode"]);
            $SCI_OtherTelephoneNumber=($SCI_OtherTelephoneNumber?$SCI_OtherTelephoneNumber:$q["SCI_OtherTelephoneNumber"]);
            $SCI_MobileCountryDiallingCode=($SCI_MobileCountryDiallingCode?$SCI_MobileCountryDiallingCode:$q["SCI_MobileCountryDiallingCode"]);
            $SCI_MobileAreaDiallingCode=($SCI_MobileAreaDiallingCode?$SCI_MobileAreaDiallingCode:$q["SCI_MobileAreaDiallingCode"]);
            $SCI_MobileNumber=($SCI_MobileNumber?$SCI_MobileNumber:$q["SCI_MobileNumber"]);
            $SCI_FAXCountryDiallingCode=($SCI_FAXCountryDiallingCode?$SCI_FAXCountryDiallingCode:$q["SCI_FAXCountryDiallingCode"]);
            $SCI_FAXAreaDiallingCode=($SCI_FAXAreaDiallingCode?$SCI_FAXAreaDiallingCode:$q["SCI_FAXAreaDiallingCode"]);
            $SCI_FAXNumber=($SCI_FAXNumber?$SCI_FAXNumber:$q["SCI_FAXNumber"]);
            $SCI_EmailAddress=($SCI_EmailAddress?$SCI_EmailAddress:$q["SCI_EmailAddress"]);
            $SCI_Website=($SCI_Website?$SCI_Website:$q["SCI_Website"]);
        }
	}

	//then search ClientNumber in vl_databc106
	$query=0;
	$query=mysqlquery("select * from vl_databc106 where ClientNumber='$clientNumber' order by created desc");
	if(mysqlnumrows($query)) {
		//flag
		$datafound=1;
        //get data
        $q=array();
        while($q=mysqlfetcharray($query)) {
            $II_RegistrationCertificateNumber=($II_RegistrationCertificateNumber?$II_RegistrationCertificateNumber:$q["II_RegistrationCertificateNumber"]);
            $II_TaxIdentificationNumber=($II_TaxIdentificationNumber?$II_TaxIdentificationNumber:$q["II_TaxIdentificationNumber"]);
            $II_ValueAddedTaxNumber=($II_ValueAddedTaxNumber?$II_ValueAddedTaxNumber:$q["II_ValueAddedTaxNumber"]);
            $II_FCSNumber=($II_FCSNumber?$II_FCSNumber:$q["II_FCSNumber"]);
            $II_PassportNumber=($II_PassportNumber?$II_PassportNumber:$q["II_PassportNumber"]);
            $II_DriversLicenceIDNumber=($II_DriversLicenceIDNumber?$II_DriversLicenceIDNumber:$q["II_DriversLicence"]);
            $II_VotersPERNO=($II_VotersPERNO?$II_VotersPERNO:$q["II_VotersPERNO"]);
            $II_DriversLicensePermitNumber=($II_DriversLicensePermitNumber?$II_DriversLicensePermitNumber:$q["II_DriversLicence"]);
            $II_NSSFNumber="";
            $II_CountryID="";
            $II_CountryIssuingAuthority="";
            $II_Nationality="";
            $II_PoliceIDNumber="";
            $II_UPDFNumber="";
            $II_KACITALicenseNumber="";
            $II_CountryOfIssue="";
            $GSCAFB_BusinessName=($GSCAFB_BusinessName?$GSCAFB_BusinessName:$q["GSCAFB_BusinessName"]);
            $GSCAFB_TradingName=($GSCAFB_TradingName?$GSCAFB_TradingName:$q["GSCAFB_TradingName"]);
            $GSCAFB_ActivityDescription=($GSCAFB_ActivityDescription?$GSCAFB_ActivityDescription:$q["GSCAFB_ActivityDescription"]);
            $GSCAFB_IndustrySectorCode=($GSCAFB_IndustrySectorCode?$GSCAFB_IndustrySectorCode:$q["GSCAFB_IndustrySectorCode"]);
            $GSCAFB_DateRegistered=($GSCAFB_DateRegistered?$GSCAFB_DateRegistered:$q["GSCAFB_DateRegistered"]);
            $GSCAFB_BusinessTypeCode=($GSCAFB_BusinessTypeCode?$GSCAFB_BusinessTypeCode:$q["GSCAFB_BusinessTypeCode"]);
            $GSCAFB_Surname=($GSCAFB_Surname?$GSCAFB_Surname:$q["GSCAFB_Surname"]);
            $GSCAFB_Forename1=($GSCAFB_Forename1?$GSCAFB_Forename1:$q["GSCAFB_Forename1"]);
            $GSCAFB_Forename2=($GSCAFB_Forename2?$GSCAFB_Forename2:$q["GSCAFB_Forename2"]);
            $GSCAFB_Forename3=($GSCAFB_Forename3?$GSCAFB_Forename3:$q["GSCAFB_Forename3"]);
            $GSCAFB_Gender=($GSCAFB_Gender?$GSCAFB_Gender:$q["GSCAFB_Gender"]);
            $GSCAFB_MaritalStatus=($GSCAFB_MaritalStatus?$GSCAFB_MaritalStatus:$q["GSCAFB_MaritalStatus"]);
            $GSCAFB_DateofBirth=($GSCAFB_DateofBirth?$GSCAFB_DateofBirth:$q["GSCAFB_DateofBirth"]);
            $EI_EmploymentType=($EI_EmploymentType?$EI_EmploymentType:$q["EI_EmploymentType"]);
            $EI_PrimaryOccupation=($EI_PrimaryOccupation?$EI_PrimaryOccupation:$q["EI_PrimaryOccupation"]);
            $EI_EmployerName=($EI_EmployerName?$EI_EmployerName:$q["EI_EmployerName"]);
            $EI_EmployeeNumber=($EI_EmployeeNumber?$EI_EmployeeNumber:$q["EI_EmployeeNumber"]);
            $EI_PeriodAtEmployer=($EI_PeriodAtEmployer?$EI_PeriodAtEmployer:$q["EI_PeriodAtEmployer"]);
            $EI_IncomeBand=($EI_IncomeBand?$EI_IncomeBand:$q["EI_IncomeBand"]);
            $EI_SalaryFrequency=($EI_SalaryFrequency?$EI_SalaryFrequency:$q["EI_SalaryFrequency"]);
            $PCI_UnitNumber=($PCI_UnitNumber?$PCI_UnitNumber:$q["PCI_UnitNumber"]);
            $PCI_UnitName=($PCI_UnitName?$PCI_UnitName:$q["PCI_UnitName"]);
            $PCI_FloorNumber=($PCI_FloorNumber?$PCI_FloorNumber:$q["PCI_FloorNumber"]);
            $PCI_PlotorStreetNumber=($PCI_PlotorStreetNumber?$PCI_PlotorStreetNumber:$q["PCI_StreetNumber"]);
            $PCI_LCorStreetName=($PCI_LCorStreetName?$PCI_LCorStreetName:$q["PCI_StreetName"]);
            $PCI_Parish="";
            $PCI_Suburb=($PCI_Suburb?$PCI_Suburb:$q["PCI_VillageOrSuburb"]);
            $PCI_Village=($PCI_Village?$PCI_Village:$q["PCI_VillageOrSuburb"]);
            $PCI_CountyorTown=($PCI_CountyorTown?$PCI_CountyorTown:$q["PCI_Town"]);
            $PCI_District=($PCI_District?$PCI_District:$q["PCI_District"]);
            $PCI_Region=($PCI_Region?$PCI_Region:$q["PCI_Region"]);
            $PCI_POBoxNumber=($PCI_POBoxNumber?$PCI_POBoxNumber:$q["PCI_PostalAddressNumber"]);
            $PCI_PostOfficeTown=($PCI_PostOfficeTown?$PCI_PostOfficeTown:$q["PCI_PostalAddressNumber"]);
            $PCI_CountryCode=($PCI_CountryCode?$PCI_CountryCode:$q["PCI_CountryCode"]);
            $PCI_PeriodAtAddress=($PCI_PeriodAtAddress?$PCI_PeriodAtAddress:$q["PCI_PeriodAtAddress"]);
            $PCI_FlagofOwnership=($PCI_FlagofOwnership?$PCI_FlagofOwnership:$q["PCI_Flagforownership"]);
            $PCI_PrimaryCountryDiallingCode=($PCI_PrimaryCountryDiallingCode?$PCI_PrimaryCountryDiallingCode:$q["PCI_PrimaryCountryDiallingCode"]);
            $PCI_PrimaryAreaDiallingCode=($PCI_PrimaryAreaDiallingCode?$PCI_PrimaryAreaDiallingCode:$q["PCI_PrimaryAreaDiallingCode"]);
            $PCI_PrimaryTelephoneNumber=($PCI_PrimaryTelephoneNumber?$PCI_PrimaryTelephoneNumber:$q["PCI_PrimaryTelephoneNumber"]);
            $PCI_OtherCountryDiallingCode=($PCI_OtherCountryDiallingCode?$PCI_OtherCountryDiallingCode:$q["PCI_OtherCountryDiallingCode"]);
            $PCI_OtherAreaDiallingCode=($PCI_OtherAreaDiallingCode?$PCI_OtherAreaDiallingCode:$q["PCI_OtherAreaDiallingCode"]);
            $PCI_OtherTelephoneNumber=($PCI_OtherTelephoneNumber?$PCI_OtherTelephoneNumber:$q["PCI_OtherTelephoneNumber"]);
            $PCI_MobileCountryDiallingCode=($PCI_MobileCountryDiallingCode?$PCI_MobileCountryDiallingCode:$q["PCI_MobileCountryDiallingCode"]);
            $PCI_MobileAreaDiallingCode=($PCI_MobileAreaDiallingCode?$PCI_MobileAreaDiallingCode:$q["PCI_MobileAreaDiallingCode"]);
            $PCI_MobileNumber=($PCI_MobileNumber?$PCI_MobileNumber:$q["PCI_MobileNumber"]);
            $PCI_FAXCountryDiallingCode=($PCI_FAXCountryDiallingCode?$PCI_FAXCountryDiallingCode:$q["PCI_FAXCountryDiallingCode"]);
            $PCI_FAXAreaDiallingCode=($PCI_FAXAreaDiallingCode?$PCI_FAXAreaDiallingCode:$q["PCI_FAXAreaDiallingCode"]);
            $PCI_FAXNumber=($PCI_FAXNumber?$PCI_FAXNumber:$q["PCI_FAXNumber"]);
            $PCI_EmailAddress=($PCI_EmailAddress?$PCI_EmailAddress:$q["PCI_EmailAddress"]);
            $PCI_Website=($PCI_Website?$PCI_Website:$q["PCI_Website"]);
            $SCI_UnitNumber=($SCI_UnitNumber?$SCI_UnitNumber:$q["SCI_UnitNumber"]);
            $SCI_UnitName=($SCI_UnitName?$SCI_UnitName:$q["SCI_UnitName"]);
            $SCI_FloorNumber=($SCI_FloorNumber?$SCI_FloorNumber:$q["SCI_FloorNumber"]);
            $SCI_PlotorStreetNumber=($SCI_PlotorStreetNumber?$SCI_PlotorStreetNumber:$q["SCI_StreetNumber"]);
            $SCI_LCorStreetName=($SCI_LCorStreetName?$SCI_LCorStreetName:$q["SCI_StreetName"]);
            $SCI_Parish="";
            $SCI_Suburb=($SCI_Suburb?$SCI_Suburb:$q["SCI_VillageOrSuburb"]);
            $SCI_Village=($SCI_Village?$SCI_Village:$q["SCI_VillageOrSuburb"]);
            $SCI_CountyorTown=($SCI_CountyorTown?$SCI_CountyorTown:$q["SCI_Town"]);
            $SCI_District=($SCI_District?$SCI_District:$q["SCI_District"]);
            $SCI_Region=($SCI_Region?$SCI_Region:$q["SCI_Region"]);
            $SCI_POBoxNumber=($SCI_POBoxNumber?$SCI_POBoxNumber:$q["SCI_PostalAddressNumber"]);
            $SCI_PostOfficeTown=($SCI_PostOfficeTown?$SCI_PostOfficeTown:$q["SCI_PostalAddressNumber"]);
            $SCI_CountryCode=($SCI_CountryCode?$SCI_CountryCode:$q["SCI_CountryCode"]);
            $SCI_PeriodAtAddress=($SCI_PeriodAtAddress?$SCI_PeriodAtAddress:$q["SCI_PeriodAtAddress"]);
            $SCI_FlagofOwnership=($SCI_FlagofOwnership?$SCI_FlagofOwnership:$q["SCI_Flagforownership"]);
            $SCI_PrimaryCountryDiallingCode=($SCI_PrimaryCountryDiallingCode?$SCI_PrimaryCountryDiallingCode:$q["SCI_PrimaryCountryDiallingCode"]);
            $SCI_PrimaryAreaDiallingCode=($SCI_PrimaryAreaDiallingCode?$SCI_PrimaryAreaDiallingCode:$q["SCI_PrimaryAreaDiallingCode"]);
            $SCI_PrimaryTelephoneNumber=($SCI_PrimaryTelephoneNumber?$SCI_PrimaryTelephoneNumber:$q["SCI_PrimaryTelephoneNumber"]);
            $SCI_OtherCountryDiallingCode=($SCI_OtherCountryDiallingCode?$SCI_OtherCountryDiallingCode:$q["SCI_OtherCountryDiallingCode"]);
            $SCI_OtherAreaDiallingCode=($SCI_OtherAreaDiallingCode?$SCI_OtherAreaDiallingCode:$q["SCI_OtherAreaDiallingCode"]);
            $SCI_OtherTelephoneNumber=($SCI_OtherTelephoneNumber?$SCI_OtherTelephoneNumber:$q["SCI_OtherTelephoneNumber"]);
            $SCI_MobileCountryDiallingCode=($SCI_MobileCountryDiallingCode?$SCI_MobileCountryDiallingCode:$q["SCI_MobileCountryDiallingCode"]);
            $SCI_MobileAreaDiallingCode=($SCI_MobileAreaDiallingCode?$SCI_MobileAreaDiallingCode:$q["SCI_MobileAreaDiallingCode"]);
            $SCI_MobileNumber=($SCI_MobileNumber?$SCI_MobileNumber:$q["SCI_MobileNumber"]);
            $SCI_FAXCountryDiallingCode=($SCI_FAXCountryDiallingCode?$SCI_FAXCountryDiallingCode:$q["SCI_FAXCountryDiallingCode"]);
            $SCI_FAXAreaDiallingCode=($SCI_FAXAreaDiallingCode?$SCI_FAXAreaDiallingCode:$q["SCI_FAXAreaDiallingCode"]);
            $SCI_FAXNumber=($SCI_FAXNumber?$SCI_FAXNumber:$q["SCI_FAXNumber"]);
            $SCI_EmailAddress=($SCI_EmailAddress?$SCI_EmailAddress:$q["SCI_EmailAddress"]);
            $SCI_Website=($SCI_Website?$SCI_Website:$q["SCI_Website"]);
        }
	}
	
	//split up the date of birth
	$GSCAFB_DateofBirthDay=0;
	$GSCAFB_DateofBirthMonth=0;
	$GSCAFB_DateofBirthYear=0;
	$GSCAFB_DateRegisteredDay=0;
	$GSCAFB_DateRegisteredMonth=0;
	$GSCAFB_DateRegisteredYear=0;
	
	if($GSCAFB_DateofBirth || $GSCAFB_DateRegistered) {
		$GSCAFB_DateofBirthDay=getFormattedDateDay($GSCAFB_DateofBirth);
		$GSCAFB_DateofBirthMonth=getFormattedDateMonth($GSCAFB_DateofBirth);
		$GSCAFB_DateofBirthYear=getFormattedDateYear($GSCAFB_DateofBirth);
		$GSCAFB_DateRegisteredDay=getFormattedDateDay($GSCAFB_DateRegistered);
		$GSCAFB_DateRegisteredMonth=getFormattedDateMonth($GSCAFB_DateRegistered);
		$GSCAFB_DateRegisteredYear=getFormattedDateYear($GSCAFB_DateRegistered);
	}

    if($datafound) {
		$objResponse = new vlDCResponse();
		if($id) {
			//clear ClientNumberTDID
			$objResponse->addAssign("ClientNumberTDID$id","innerHTML","<a href=\"#\" class=\"vls_grey\" onclick=\"iDisplayMessage('/profiles/cap/select/$version/$id/$customerType/')\"><img src=\"/images/arrow_right.gif\" border=\"0\"> Load from Existing Customers</a>");
			//display the print options
			$print=0;
			$print="<table border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"border:1px solid #d5d5d5\" width=\"100%\">
					  <tr>
						<td style=\"padding:10px\"><table width=\"100%\" border=\"0\" class=\"vl\">
						  <tr>
							<td><strong>PRINT&nbsp;FORM</strong></td>
						  </tr>
						  <tr>
							<td>&nbsp;</td>
						  </tr>
						  <tr>
							<td bgcolor=\"#d5d5d5\" style=\"padding:10px\"><p>Use&nbsp;the&nbsp;options&nbsp;below&nbsp;to:</p></td>
						  </tr>
						  <tr>
							<td style=\"padding:10px 10px 0px 10px\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
							  <tr>
								<td><a name=\"print$i\"><a href=\"#print$i\" onClick=\"window.open('/print.$customerType/$clientNumber/$version/', 'printForm$i', 'width=950, height=600, resizable=no, status=yes, scrollbars=yes');\"><img src=\"/images/print.report.gif\" border=\"0\" /></a></td>
								<td width=\"100%\" style=\"padding-left:10px\"><a href=\"#print$i\" onClick=\"window.open('/print.$customerType/$clientNumber/$version/', 'printForm$i', 'width=950, height=600, resizable=no, status=yes, scrollbars=yes');\">Print&nbsp;Form</a></td>
							  </tr>
							</table></td>
						  </tr>
						</table></td>
					  </tr>
					</table>";
			//display print options
			$objResponse->addAssign("printID$id","innerHTML",$print);
		}
        
		//fill out fields based on customer type
        if($customerType=="nonindividual") { //non individuals
            if($id) { $objResponse->addScript("document.$formname.oldClientNumber$id.value=\"$clientNumber\""); }
			$objResponse->addScript("document.$formname.II_RegistrationCertificateNumber$id.value=\"$II_RegistrationCertificateNumber\"");
            $objResponse->addScript("document.$formname.II_TaxIdentificationNumber$id.value=\"$II_TaxIdentificationNumber\"");
            $objResponse->addScript("document.$formname.II_ValueAddedTaxNumber$id.value=\"$II_ValueAddedTaxNumber\"");
            $objResponse->addScript("document.$formname.II_FCSNumber$id.value=\"$II_FCSNumber\"");
            $objResponse->addScript("document.$formname.II_CountryIssuingAuthority$id.value=\"$II_CountryIssuingAuthority\"");
            $objResponse->addScript("document.$formname.II_KACITALicenseNumber$id.value=\"$II_KACITALicenseNumber\"");
            $objResponse->addScript("document.$formname.GSCAFB_BusinessName$id.value=\"$GSCAFB_BusinessName\"");
            $objResponse->addScript("document.$formname.GSCAFB_TradingName$id.value=\"$GSCAFB_TradingName\"");
            $objResponse->addScript("document.$formname.GSCAFB_ActivityDescription$id.value=\"$GSCAFB_ActivityDescription\"");
            $objResponse->addScript("document.$formname.GSCAFB_IndustrySectorCode$id.value=\"$GSCAFB_IndustrySectorCode\"");
            $objResponse->addScript("document.$formname.GSCAFB_DateRegisteredDay$id.value=\"$GSCAFB_DateRegisteredDay\"");
            $objResponse->addScript("document.$formname.GSCAFB_DateRegisteredMonth$id.value=\"$GSCAFB_DateRegisteredMonth\"");
            $objResponse->addScript("document.$formname.GSCAFB_DateRegisteredYear$id.value=\"$GSCAFB_DateRegisteredYear\"");
            $objResponse->addScript("document.$formname.GSCAFB_BusinessTypeCode$id.value=\"$GSCAFB_BusinessTypeCode\"");
            $objResponse->addScript("document.$formname.PCI_UnitNumber$id.value=\"".($PCI_UnitNumber?$PCI_UnitNumber:"Number")."\"");
            $objResponse->addScript("document.$formname.PCI_UnitName$id.value=\"".($PCI_UnitName?$PCI_UnitName:"Name")."\"");
            $objResponse->addScript("document.$formname.PCI_FloorNumber$id.value=\"".($PCI_FloorNumber?$PCI_FloorNumber:"Floor")."\"");
            $objResponse->addScript("document.$formname.PCI_PlotorStreetNumber$id.value=\"".($PCI_PlotorStreetNumber?$PCI_PlotorStreetNumber:"Number")."\"");
            $objResponse->addScript("document.$formname.PCI_LCorStreetName$id.value=\"".($PCI_LCorStreetName?$PCI_LCorStreetName:"Street Name")."\"");
            $objResponse->addScript("document.$formname.PCI_Parish$id.value=\"$PCI_Parish\"");
            $objResponse->addScript("document.$formname.PCI_Suburb$id.value=\"$PCI_Suburb\"");
            $objResponse->addScript("document.$formname.PCI_Village$id.value=\"$PCI_Village\"");
            $objResponse->addScript("document.$formname.PCI_CountyorTown$id.value=\"$PCI_CountyorTown\"");
            $objResponse->addScript("document.$formname.PCI_District$id.value=\"$PCI_District\"");
            $objResponse->addScript("document.$formname.PCI_Region$id.value=\"$PCI_Region\"");
            $objResponse->addScript("document.$formname.PCI_POBoxNumber$id.value=\"".($PCI_POBoxNumber?$PCI_POBoxNumber:"Number")."\"");
            $objResponse->addScript("document.$formname.PCI_PostOfficeTown$id.value=\"".($PCI_PostOfficeTown?$PCI_PostOfficeTown:"Town")."\"");
            $objResponse->addScript("document.$formname.PCI_CountryCode$id.value=\"$PCI_CountryCode\"");
			if($PCI_PeriodAtAddress) {
				$lastDate=0;
				$lastDate=subtractFromDate($datetime,(($PCI_PeriodAtAddress-1)*30));
				$objResponse->addScript("document.$formname.PCI_PeriodAtAddressMonth$id.value=\"".getFormattedDateMonth($lastDate)."\"");
				$objResponse->addScript("document.$formname.PCI_PeriodAtAddressYear$id.value=\"".getFormattedDateYear($lastDate)."\"");
			} else {
				$objResponse->addScript("document.$formname.PCI_PeriodAtAddressMonth$id.value=\"1\"");
				$objResponse->addScript("document.$formname.PCI_PeriodAtAddressYear$id.value=\"".getCurrentYear()."\"");
			}
            $objResponse->addScript("document.$formname.PCI_FlagofOwnership$id.value=\"$PCI_FlagofOwnership\"");
            $objResponse->addScript("document.$formname.PCI_PrimaryCountryDiallingCode$id.value=\"$PCI_PrimaryCountryDiallingCode\"");
            $objResponse->addScript("document.$formname.PCI_PrimaryAreaDiallingCode$id.value=\"$PCI_PrimaryAreaDiallingCode\"");
            $objResponse->addScript("document.$formname.PCI_PrimaryTelephoneNumber$id.value=\"$PCI_PrimaryTelephoneNumber\"");
            $objResponse->addScript("document.$formname.PCI_OtherCountryDiallingCode$id.value=\"$PCI_OtherCountryDiallingCode\"");
            $objResponse->addScript("document.$formname.PCI_OtherAreaDiallingCode$id.value=\"$PCI_OtherAreaDiallingCode\"");
            $objResponse->addScript("document.$formname.PCI_OtherTelephoneNumber$id.value=\"$PCI_OtherTelephoneNumber\"");
            $objResponse->addScript("document.$formname.PCI_MobileCountryDiallingCode$id.value=\"$PCI_MobileCountryDiallingCode\"");
            $objResponse->addScript("document.$formname.PCI_MobileAreaDiallingCode$id.value=\"$PCI_MobileAreaDiallingCode\"");
            $objResponse->addScript("document.$formname.PCI_MobileNumber$id.value=\"$PCI_MobileNumber\"");
            $objResponse->addScript("document.$formname.PCI_FAXCountryDiallingCode$id.value=\"$PCI_FAXCountryDiallingCode\"");
            $objResponse->addScript("document.$formname.PCI_FAXAreaDiallingCode$id.value=\"$PCI_FAXAreaDiallingCode\"");
            $objResponse->addScript("document.$formname.PCI_FAXNumber$id.value=\"$PCI_FAXNumber\"");
            $objResponse->addScript("document.$formname.PCI_EmailAddress$id.value=\"$PCI_EmailAddress\"");
            $objResponse->addScript("document.$formname.PCI_Website$id.value=\"$PCI_Website\"");
            $objResponse->addScript("document.$formname.SCI_UnitNumber$id.value=\"".($SCI_UnitNumber?$SCI_UnitNumber:"Number")."\"");
            $objResponse->addScript("document.$formname.SCI_UnitName$id.value=\"".($SCI_UnitName?$SCI_UnitName:"Name")."\"");
            $objResponse->addScript("document.$formname.SCI_FloorNumber$id.value=\"".($SCI_FloorNumber?$SCI_FloorNumber:"Floor")."\"");
            $objResponse->addScript("document.$formname.SCI_PlotorStreetNumber$id.value=\"".($SCI_PlotorStreetNumber?$SCI_PlotorStreetNumber:"Number")."\"");
            $objResponse->addScript("document.$formname.SCI_LCorStreetName$id.value=\"".($SCI_LCorStreetName?$SCI_LCorStreetName:"Street Name")."\"");
            $objResponse->addScript("document.$formname.SCI_Parish$id.value=\"$SCI_Parish\"");
            $objResponse->addScript("document.$formname.SCI_Suburb$id.value=\"$SCI_Suburb\"");
            $objResponse->addScript("document.$formname.SCI_Village$id.value=\"$SCI_Village\"");
            $objResponse->addScript("document.$formname.SCI_CountyorTown$id.value=\"$SCI_CountyorTown\"");
            $objResponse->addScript("document.$formname.SCI_District$id.value=\"$SCI_District\"");
            $objResponse->addScript("document.$formname.SCI_Region$id.value=\"$SCI_Region\"");
            $objResponse->addScript("document.$formname.SCI_POBoxNumber$id.value=\"".($SCI_POBoxNumber?$SCI_POBoxNumber:"Number")."\"");
            $objResponse->addScript("document.$formname.SCI_PostOfficeTown$id.value=\"".($SCI_PostOfficeTown?$SCI_PostOfficeTown:"Town")."\"");
            $objResponse->addScript("document.$formname.SCI_CountryCode$id.value=\"$SCI_CountryCode\"");
			if($SCI_PeriodAtAddress) {
				$lastDate=0;
				$lastDate=subtractFromDate($datetime,($SCI_PeriodAtAddress*30));
				$objResponse->addScript("document.$formname.SCI_PeriodAtAddressMonth$id.value=\"".getFormattedDateMonth($lastDate)."\"");
				$objResponse->addScript("document.$formname.SCI_PeriodAtAddressYear$id.value=\"".getFormattedDateYear($lastDate)."\"");
			} else {
				$objResponse->addScript("document.$formname.SCI_PeriodAtAddressMonth$id.value=\"1\"");
				$objResponse->addScript("document.$formname.SCI_PeriodAtAddressYear$id.value=\"".getCurrentYear()."\"");
			}
            $objResponse->addScript("document.$formname.SCI_FlagofOwnership$id.value=\"$SCI_FlagofOwnership\"");
            $objResponse->addScript("document.$formname.SCI_PrimaryCountryDiallingCode$id.value=\"$SCI_PrimaryCountryDiallingCode\"");
            $objResponse->addScript("document.$formname.SCI_PrimaryAreaDiallingCode$id.value=\"$SCI_PrimaryAreaDiallingCode\"");
            $objResponse->addScript("document.$formname.SCI_PrimaryTelephoneNumber$id.value=\"$SCI_PrimaryTelephoneNumber\"");
            $objResponse->addScript("document.$formname.SCI_OtherCountryDiallingCode$id.value=\"$SCI_OtherCountryDiallingCode\"");
            $objResponse->addScript("document.$formname.SCI_OtherAreaDiallingCode$id.value=\"$SCI_OtherAreaDiallingCode\"");
            $objResponse->addScript("document.$formname.SCI_OtherTelephoneNumber$id.value=\"$SCI_OtherTelephoneNumber\"");
            $objResponse->addScript("document.$formname.SCI_MobileCountryDiallingCode$id.value=\"$SCI_MobileCountryDiallingCode\"");
            $objResponse->addScript("document.$formname.SCI_MobileAreaDiallingCode$id.value=\"$SCI_MobileAreaDiallingCode\"");
            $objResponse->addScript("document.$formname.SCI_MobileNumber$id.value=\"$SCI_MobileNumber\"");
            $objResponse->addScript("document.$formname.SCI_FAXCountryDiallingCode$id.value=\"$SCI_FAXCountryDiallingCode\"");
            $objResponse->addScript("document.$formname.SCI_FAXAreaDiallingCode$id.value=\"$SCI_FAXAreaDiallingCode\"");
            $objResponse->addScript("document.$formname.SCI_FAXNumber$id.value=\"$SCI_FAXNumber\"");
            $objResponse->addScript("document.$formname.SCI_EmailAddress$id.value=\"$SCI_EmailAddress\"");
            $objResponse->addScript("document.$formname.SCI_Website$id.value=\"$SCI_Website\"");
        } else { //individuals
            if($id) { $objResponse->addScript("document.$formname.oldClientNumber$id.value=\"$clientNumber\""); }
            $objResponse->addScript("document.$formname.II_TaxIdentificationNumber$id.value=\"$II_TaxIdentificationNumber\"");
            $objResponse->addScript("document.$formname.II_FCSNumber$id.value=\"$II_FCSNumber\"");
            $objResponse->addScript("document.$formname.II_PassportNumber$id.value=\"$II_PassportNumber\"");
            $objResponse->addScript("document.$formname.II_DriversLicenceIDNumber$id.value=\"$II_DriversLicenceIDNumber\"");
            $objResponse->addScript("document.$formname.II_VotersPERNO$id.value=\"$II_VotersPERNO\"");
            $objResponse->addScript("document.$formname.II_DriversLicensePermitNumber$id.value=\"$II_DriversLicensePermitNumber\"");
            $objResponse->addScript("document.$formname.II_NSSFNumber$id.value=\"$II_NSSFNumber\"");
            $objResponse->addScript("document.$formname.II_CountryID$id.value=\"$II_CountryID\"");
            $objResponse->addScript("document.$formname.II_Nationality$id.value=\"$II_Nationality\"");
            $objResponse->addScript("document.$formname.II_PoliceIDNumber$id.value=\"$II_PoliceIDNumber\"");
            $objResponse->addScript("document.$formname.II_UPDFNumber$id.value=\"$II_UPDFNumber\"");
            $objResponse->addScript("document.$formname.II_CountryOfIssue$id.value=\"$II_CountryOfIssue\"");
            $objResponse->addScript("document.$formname.GSCAFB_Surname$id.value=\"".($GSCAFB_Surname?$GSCAFB_Surname:"Surname")."\"");
            $objResponse->addScript("document.$formname.GSCAFB_Forename1$id.value=\"".($GSCAFB_Forename1?$GSCAFB_Forename1:"Firstname")."\"");
            $objResponse->addScript("document.$formname.GSCAFB_Forename2$id.value=\"".($GSCAFB_Forename2?$GSCAFB_Forename2:"Othernames")."\"");
            $objResponse->addScript("document.$formname.GSCAFB_Gender$id.value=\"$GSCAFB_Gender\"");
            $objResponse->addScript("document.$formname.GSCAFB_MaritalStatus$id.value=\"$GSCAFB_MaritalStatus\"");
			$objResponse->addScript("document.$formname.GSCAFB_DateofBirthDay$id.value=\"$GSCAFB_DateofBirthDay\"");
            $objResponse->addScript("document.$formname.GSCAFB_DateofBirthMonth$id.value=\"$GSCAFB_DateofBirthMonth\"");
            $objResponse->addScript("document.$formname.GSCAFB_DateofBirthYear$id.value=\"$GSCAFB_DateofBirthYear\"");
			$objResponse->addScript("document.$formname.EI_EmploymentType$id.value=\"$EI_EmploymentType\"");
			//employment type
			if(getUserInfoByClientNumber($clientNumber,"EI_EmploymentType")==getDetailedTableInfo2("vl_appendix_employmenttypes","appendixvalue like '%self%' and version='$version'","appendixcode")) {
				$objResponse->addAssign("periodAtEmployerTDID$id","innerHTML","Began Self Employment:");
				$objResponse->addAssign("salaryTDID$id","innerHTML","Turnover:");
				$objResponse->addAssign("salaryFrequencyTDID$id","innerHTML","Revenue Frequency:");
			}
            $objResponse->addScript("document.$formname.EI_PrimaryOccupation$id.value=\"$EI_PrimaryOccupation\"");
            $objResponse->addScript("document.$formname.EI_EmployerName$id.value=\"$EI_EmployerName\"");
            $objResponse->addScript("document.$formname.EI_EmployeeNumber$id.value=\"$EI_EmployeeNumber\"");
			if($EI_PeriodAtEmployer) {
				$lastDate=0;
				$lastDate=subtractFromDate($datetime,(($EI_PeriodAtEmployer-1)*30));
				$objResponse->addScript("document.$formname.EI_PeriodAtEmployerMonth$id.value=\"".getFormattedDateMonth($lastDate)."\"");
				$objResponse->addScript("document.$formname.EI_PeriodAtEmployerYear$id.value=\"".getFormattedDateYear($lastDate)."\"");
			} else {
				$objResponse->addScript("document.$formname.EI_PeriodAtEmployerMonth$id.value=\"1\"");
				$objResponse->addScript("document.$formname.EI_PeriodAtEmployerYear$id.value=\"".getCurrentYear()."\"");
			}
            $objResponse->addScript("document.$formname.EI_IncomeBand$id.value=\"$EI_IncomeBand\"");
            $objResponse->addScript("document.$formname.EI_SalaryFrequency$id.value=\"$EI_SalaryFrequency\"");
            $objResponse->addScript("document.$formname.PCI_UnitNumber$id.value=\"".($PCI_UnitNumber?$PCI_UnitNumber:"Number")."\"");
            $objResponse->addScript("document.$formname.PCI_UnitName$id.value=\"".($PCI_UnitName?$PCI_UnitName:"Name")."\"");
            $objResponse->addScript("document.$formname.PCI_FloorNumber$id.value=\"".($PCI_FloorNumber?$PCI_FloorNumber:"Floor")."\"");
            $objResponse->addScript("document.$formname.PCI_PlotorStreetNumber$id.value=\"".($PCI_PlotorStreetNumber?$PCI_PlotorStreetNumber:"Number")."\"");
            $objResponse->addScript("document.$formname.PCI_LCorStreetName$id.value=\"".($PCI_LCorStreetName?$PCI_LCorStreetName:"Street Name")."\"");
            $objResponse->addScript("document.$formname.PCI_Parish$id.value=\"$PCI_Parish\"");
            $objResponse->addScript("document.$formname.PCI_Suburb$id.value=\"$PCI_Suburb\"");
            $objResponse->addScript("document.$formname.PCI_Village$id.value=\"$PCI_Village\"");
            $objResponse->addScript("document.$formname.PCI_CountyorTown$id.value=\"$PCI_CountyorTown\"");
            $objResponse->addScript("document.$formname.PCI_District$id.value=\"$PCI_District\"");
            $objResponse->addScript("document.$formname.PCI_Region$id.value=\"$PCI_Region\"");
            $objResponse->addScript("document.$formname.PCI_POBoxNumber$id.value=\"".($PCI_POBoxNumber?$PCI_POBoxNumber:"Number")."\"");
            $objResponse->addScript("document.$formname.PCI_PostOfficeTown$id.value=\"".($PCI_PostOfficeTown?$PCI_PostOfficeTown:"Town")."\"");
            $objResponse->addScript("document.$formname.PCI_CountryCode$id.value=\"$PCI_CountryCode\"");
			if($PCI_PeriodAtAddress) {
				$lastDate=0;
				$lastDate=subtractFromDate($datetime,(($PCI_PeriodAtAddress-1)*30));
				$objResponse->addScript("document.$formname.PCI_PeriodAtAddressMonth$id.value=\"".getFormattedDateMonth($lastDate)."\"");
				$objResponse->addScript("document.$formname.PCI_PeriodAtAddressYear$id.value=\"".getFormattedDateYear($lastDate)."\"");
			} else {
				$objResponse->addScript("document.$formname.PCI_PeriodAtAddressMonth$id.value=\"1\"");
				$objResponse->addScript("document.$formname.PCI_PeriodAtAddressYear$id.value=\"".getCurrentYear()."\"");
			}
            $objResponse->addScript("document.$formname.PCI_FlagofOwnership$id.value=\"$PCI_FlagofOwnership\"");
            $objResponse->addScript("document.$formname.PCI_PrimaryCountryDiallingCode$id.value=\"$PCI_PrimaryCountryDiallingCode\"");
            $objResponse->addScript("document.$formname.PCI_PrimaryAreaDiallingCode$id.value=\"$PCI_PrimaryAreaDiallingCode\"");
            $objResponse->addScript("document.$formname.PCI_PrimaryTelephoneNumber$id.value=\"$PCI_PrimaryTelephoneNumber\"");
            $objResponse->addScript("document.$formname.PCI_OtherCountryDiallingCode$id.value=\"$PCI_OtherCountryDiallingCode\"");
            $objResponse->addScript("document.$formname.PCI_OtherAreaDiallingCode$id.value=\"$PCI_OtherAreaDiallingCode\"");
            $objResponse->addScript("document.$formname.PCI_OtherTelephoneNumber$id.value=\"$PCI_OtherTelephoneNumber\"");
            $objResponse->addScript("document.$formname.PCI_MobileCountryDiallingCode$id.value=\"$PCI_MobileCountryDiallingCode\"");
            $objResponse->addScript("document.$formname.PCI_MobileAreaDiallingCode$id.value=\"$PCI_MobileAreaDiallingCode\"");
            $objResponse->addScript("document.$formname.PCI_MobileNumber$id.value=\"$PCI_MobileNumber\"");
            $objResponse->addScript("document.$formname.PCI_FAXCountryDiallingCode$id.value=\"$PCI_FAXCountryDiallingCode\"");
            $objResponse->addScript("document.$formname.PCI_FAXAreaDiallingCode$id.value=\"$PCI_FAXAreaDiallingCode\"");
            $objResponse->addScript("document.$formname.PCI_FAXNumber$id.value=\"$PCI_FAXNumber\"");
            $objResponse->addScript("document.$formname.PCI_EmailAddress$id.value=\"$PCI_EmailAddress\"");
            $objResponse->addScript("document.$formname.PCI_Website$id.value=\"$PCI_Website\"");
            $objResponse->addScript("document.$formname.SCI_UnitNumber$id.value=\"".($SCI_UnitNumber?$SCI_UnitNumber:"Number")."\"");
            $objResponse->addScript("document.$formname.SCI_UnitName$id.value=\"".($SCI_UnitName?$SCI_UnitName:"Name")."\"");
            $objResponse->addScript("document.$formname.SCI_FloorNumber$id.value=\"".($SCI_FloorNumber?$SCI_FloorNumber:"Floor")."\"");
            $objResponse->addScript("document.$formname.SCI_PlotorStreetNumber$id.value=\"".($SCI_PlotorStreetNumber?$SCI_PlotorStreetNumber:"Number")."\"");
            $objResponse->addScript("document.$formname.SCI_LCorStreetName$id.value=\"".($SCI_LCorStreetName?$SCI_LCorStreetName:"Street Name")."\"");
            $objResponse->addScript("document.$formname.SCI_Parish$id.value=\"$SCI_Parish\"");
            $objResponse->addScript("document.$formname.SCI_Suburb$id.value=\"$SCI_Suburb\"");
            $objResponse->addScript("document.$formname.SCI_Village$id.value=\"$SCI_Village\"");
            $objResponse->addScript("document.$formname.SCI_CountyorTown$id.value=\"$SCI_CountyorTown\"");
            $objResponse->addScript("document.$formname.SCI_District$id.value=\"$SCI_District\"");
            $objResponse->addScript("document.$formname.SCI_Region$id.value=\"$SCI_Region\"");
            $objResponse->addScript("document.$formname.SCI_POBoxNumber$id.value=\"".($SCI_POBoxNumber?$SCI_POBoxNumber:"Number")."\"");
            $objResponse->addScript("document.$formname.SCI_PostOfficeTown$id.value=\"".($SCI_PostOfficeTown?$SCI_PostOfficeTown:"Town")."\"");
            $objResponse->addScript("document.$formname.SCI_CountryCode$id.value=\"$SCI_CountryCode\"");
			if($SCI_PeriodAtAddress) {
				$lastDate=0;
				$lastDate=subtractFromDate($datetime,($SCI_PeriodAtAddress*30));
				$objResponse->addScript("document.$formname.SCI_PeriodAtAddressMonth$id.value=\"".getFormattedDateMonth($lastDate)."\"");
				$objResponse->addScript("document.$formname.SCI_PeriodAtAddressYear$id.value=\"".getFormattedDateYear($lastDate)."\"");
			} else {
				$objResponse->addScript("document.$formname.SCI_PeriodAtAddressMonth$id.value=\"1\"");
				$objResponse->addScript("document.$formname.SCI_PeriodAtAddressYear$id.value=\"".getCurrentYear()."\"");
			}
            $objResponse->addScript("document.$formname.SCI_FlagofOwnership$id.value=\"$SCI_FlagofOwnership\"");
            $objResponse->addScript("document.$formname.SCI_PrimaryCountryDiallingCode$id.value=\"$SCI_PrimaryCountryDiallingCode\"");
            $objResponse->addScript("document.$formname.SCI_PrimaryAreaDiallingCode$id.value=\"$SCI_PrimaryAreaDiallingCode\"");
            $objResponse->addScript("document.$formname.SCI_PrimaryTelephoneNumber$id.value=\"$SCI_PrimaryTelephoneNumber\"");
            $objResponse->addScript("document.$formname.SCI_OtherCountryDiallingCode$id.value=\"$SCI_OtherCountryDiallingCode\"");
            $objResponse->addScript("document.$formname.SCI_OtherAreaDiallingCode$id.value=\"$SCI_OtherAreaDiallingCode\"");
            $objResponse->addScript("document.$formname.SCI_OtherTelephoneNumber$id.value=\"$SCI_OtherTelephoneNumber\"");
            $objResponse->addScript("document.$formname.SCI_MobileCountryDiallingCode$id.value=\"$SCI_MobileCountryDiallingCode\"");
            $objResponse->addScript("document.$formname.SCI_MobileAreaDiallingCode$id.value=\"$SCI_MobileAreaDiallingCode\"");
            $objResponse->addScript("document.$formname.SCI_MobileNumber$id.value=\"$SCI_MobileNumber\"");
            $objResponse->addScript("document.$formname.SCI_FAXCountryDiallingCode$id.value=\"$SCI_FAXCountryDiallingCode\"");
            $objResponse->addScript("document.$formname.SCI_FAXAreaDiallingCode$id.value=\"$SCI_FAXAreaDiallingCode\"");
            $objResponse->addScript("document.$formname.SCI_FAXNumber$id.value=\"$SCI_FAXNumber\"");
            $objResponse->addScript("document.$formname.SCI_EmailAddress$id.value=\"$SCI_EmailAddress\"");
            $objResponse->addScript("document.$formname.SCI_Website$id.value=\"$SCI_Website\"");
        }
		return $objResponse->getXML();
    } else {
		$objResponse = new vlDCResponse();
		if($id) { 
			$objResponse->addAssign("ClientNumberTDID$id","innerHTML","<a href=\"#\" class=\"vls_grey\" onclick=\"iDisplayMessage('/profiles/cap/select/$version/$id/$customerType/')\"><img src=\"/images/arrow_right.gif\" border=\"0\"> Load from Existing Customers</a>"); 
		}
		/*
        //clear fields based on customer type
		if($customerType=="nonindividual") { //non individuals
            if($id) { $objResponse->addScript("document.$formname.oldClientNumber$id.value=\"$clientNumber\""); }
			$objResponse->addScript("document.$formname.II_RegistrationCertificateNumber$id.value=\"".getFieldDefaultValue("II_RegistrationCertificateNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.II_TaxIdentificationNumber$id.value=\"".getFieldDefaultValue("II_TaxIdentificationNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.II_ValueAddedTaxNumber$id.value=\"".getFieldDefaultValue("II_ValueAddedTaxNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.II_FCSNumber$id.value=\"".getFieldDefaultValue("II_FCSNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.II_CountryIssuingAuthority$id.value=\"".getFieldDefaultValue("II_CountryIssuingAuthority",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.II_Nationality$id.value=\"".getFieldDefaultValue("II_Nationality",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.II_KACITALicenseNumber$id.value=\"".getFieldDefaultValue("II_KACITALicenseNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.GSCAFB_BusinessName$id.value=\"Business Name\"");
			$objResponse->addScript("document.$formname.GSCAFB_TradingName$id.value=\"Trading Name\"");
			$objResponse->addScript("document.$formname.GSCAFB_ActivityDescription$id.value=\"".getFieldDefaultValue("GSCAFB_ActivityDescription",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.GSCAFB_IndustrySectorCode$id.value=\"".getFieldDefaultValue("GSCAFB_IndustrySectorCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.GSCAFB_DateRegisteredDay$id.value=\"1\"");
			$objResponse->addScript("document.$formname.GSCAFB_DateRegisteredMonth$id.value=\"1\"");
			$objResponse->addScript("document.$formname.GSCAFB_DateRegisteredYear$id.value=\"".(getCurrentYear()-100)."\"");
			$objResponse->addScript("document.$formname.GSCAFB_BusinessTypeCode$id.value=\"".getFieldDefaultValue("GSCAFB_BusinessTypeCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_UnitNumber$id.value=\"Number\"");
			$objResponse->addScript("document.$formname.PCI_UnitName$id.value=\"Name\"");
			$objResponse->addScript("document.$formname.PCI_FloorNumber$id.value=\"Floor\"");
			$objResponse->addScript("document.$formname.PCI_PlotorStreetNumber$id.value=\"Number\"");
			$objResponse->addScript("document.$formname.PCI_LCorStreetName$id.value=\"Street Name\"");
			$objResponse->addScript("document.$formname.PCI_Parish$id.value=\"".getFieldDefaultValue("PCI_Parish",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_Suburb$id.value=\"".getFieldDefaultValue("PCI_Suburb",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_Village$id.value=\"".getFieldDefaultValue("PCI_Village",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_CountyorTown$id.value=\"".getFieldDefaultValue("PCI_CountyorTown",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_District$id.value=\"".getFieldDefaultValue("PCI_District",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_Region$id.value=\"".getFieldDefaultValue("PCI_Region",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_POBoxNumber$id.value=\"Number\"");
			$objResponse->addScript("document.$formname.PCI_PostOfficeTown$id.value=\"Town\"");
			$objResponse->addScript("document.$formname.PCI_CountryCode$id.value=\"".getFieldDefaultValue("PCI_CountryCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_PeriodAtAddressMonth$id.value=\"1\"");
			$objResponse->addScript("document.$formname.PCI_PeriodAtAddressYear$id.value=\"".getCurrentYear()."\"");
			$objResponse->addScript("document.$formname.PCI_FlagofOwnership$id.value=\"".getFieldDefaultValue("PCI_FlagofOwnership",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_PrimaryCountryDiallingCode$id.value=\"".getFieldDefaultValue("PCI_PrimaryCountryDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_PrimaryAreaDiallingCode$id.value=\"".getFieldDefaultValue("PCI_PrimaryAreaDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_PrimaryTelephoneNumber$id.value=\"".getFieldDefaultValue("PCI_PrimaryTelephoneNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_OtherCountryDiallingCode$id.value=\"".getFieldDefaultValue("PCI_OtherCountryDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_OtherAreaDiallingCode$id.value=\"".getFieldDefaultValue("PCI_OtherAreaDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_OtherTelephoneNumber$id.value=\"".getFieldDefaultValue("PCI_OtherTelephoneNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_MobileCountryDiallingCode$id.value=\"".getFieldDefaultValue("PCI_MobileCountryDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_MobileAreaDiallingCode$id.value=\"".getFieldDefaultValue("PCI_MobileAreaDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_MobileNumber$id.value=\"".getFieldDefaultValue("PCI_MobileNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_FAXCountryDiallingCode$id.value=\"".getFieldDefaultValue("PCI_FAXCountryDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_FAXAreaDiallingCode$id.value=\"".getFieldDefaultValue("PCI_FAXAreaDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_FAXNumber$id.value=\"".getFieldDefaultValue("PCI_FAXNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_EmailAddress$id.value=\"".getFieldDefaultValue("PCI_EmailAddress",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_Website$id.value=\"".getFieldDefaultValue("PCI_Website",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_UnitNumber$id.value=\"Number\"");
			$objResponse->addScript("document.$formname.SCI_UnitName$id.value=\"Name\"");
			$objResponse->addScript("document.$formname.SCI_FloorNumber$id.value=\"Floor\"");
			$objResponse->addScript("document.$formname.SCI_PlotorStreetNumber$id.value=\"Number\"");
			$objResponse->addScript("document.$formname.SCI_LCorStreetName$id.value=\"Street Name\"");
			$objResponse->addScript("document.$formname.SCI_Parish$id.value=\"".getFieldDefaultValue("SCI_Parish",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_Suburb$id.value=\"".getFieldDefaultValue("SCI_Suburb",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_Village$id.value=\"".getFieldDefaultValue("SCI_Village",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_CountyorTown$id.value=\"".getFieldDefaultValue("SCI_CountyorTown",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_District$id.value=\"".getFieldDefaultValue("SCI_District",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_Region$id.value=\"".getFieldDefaultValue("SCI_Region",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_POBoxNumber$id.value=\"Number\"");
			$objResponse->addScript("document.$formname.SCI_PostOfficeTown$id.value=\"Town\"");
			$objResponse->addScript("document.$formname.SCI_CountryCode$id.value=\"".getFieldDefaultValue("SCI_CountryCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_PeriodAtAddressMonth$id.value=\"1\"");
			$objResponse->addScript("document.$formname.SCI_PeriodAtAddressYear$id.value=\"".getCurrentYear()."\"");
			$objResponse->addScript("document.$formname.SCI_FlagofOwnership$id.value=\"".getFieldDefaultValue("SCI_FlagofOwnership",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_PrimaryCountryDiallingCode$id.value=\"".getFieldDefaultValue("SCI_PrimaryCountryDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_PrimaryAreaDiallingCode$id.value=\"".getFieldDefaultValue("SCI_PrimaryAreaDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_PrimaryTelephoneNumber$id.value=\"".getFieldDefaultValue("SCI_PrimaryTelephoneNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_OtherCountryDiallingCode$id.value=\"".getFieldDefaultValue("SCI_OtherCountryDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_OtherAreaDiallingCode$id.value=\"".getFieldDefaultValue("SCI_OtherAreaDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_OtherTelephoneNumber$id.value=\"".getFieldDefaultValue("SCI_OtherTelephoneNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_MobileCountryDiallingCode$id.value=\"".getFieldDefaultValue("SCI_MobileCountryDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_MobileAreaDiallingCode$id.value=\"".getFieldDefaultValue("SCI_MobileAreaDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_MobileNumber$id.value=\"".getFieldDefaultValue("SCI_MobileNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_FAXCountryDiallingCode$id.value=\"".getFieldDefaultValue("SCI_FAXCountryDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_FAXAreaDiallingCode$id.value=\"".getFieldDefaultValue("SCI_FAXAreaDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_FAXNumber$id.value=\"".getFieldDefaultValue("SCI_FAXNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_EmailAddress$id.value=\"".getFieldDefaultValue("SCI_EmailAddress",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_Website$id.value=\"".getFieldDefaultValue("SCI_Website",$version,"CAP")."\"");
		} else { //individuals
            if($id) { $objResponse->addScript("document.$formname.oldClientNumber$id.value=\"$clientNumber\""); }
			$objResponse->addScript("document.$formname.II_TaxIdentificationNumber$id.value=\"".getFieldDefaultValue("II_TaxIdentificationNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.II_FCSNumber$id.value=\"".getFieldDefaultValue("II_FCSNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.II_PassportNumber$id.value=\"".getFieldDefaultValue("II_PassportNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.II_DriversLicenceIDNumber$id.value=\"".getFieldDefaultValue("II_DriversLicenceIDNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.II_VotersPERNO$id.value=\"".getFieldDefaultValue("II_VotersPERNO",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.II_DriversLicensePermitNumber$id.value=\"".getFieldDefaultValue("II_DriversLicensePermitNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.II_NSSFNumber$id.value=\"".getFieldDefaultValue("II_NSSFNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.II_CountryID$id.value=\"".getFieldDefaultValue("II_CountryID",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.II_Nationality$id.value=\"".getFieldDefaultValue("II_Nationality",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.II_PoliceIDNumber$id.value=\"".getFieldDefaultValue("II_PoliceIDNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.II_UPDFNumber$id.value=\"".getFieldDefaultValue("II_UPDFNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.II_CountryOfIssue$id.value=\"".getFieldDefaultValue("II_CountryOfIssue",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.GSCAFB_Surname$id.value=\"Surname\"");
			$objResponse->addScript("document.$formname.GSCAFB_Forename1$id.value=\"Firstname\"");
			$objResponse->addScript("document.$formname.GSCAFB_Forename2$id.value=\"Othernames\"");
			$objResponse->addScript("document.$formname.GSCAFB_Gender$id.value=\"".getFieldDefaultValue("GSCAFB_Gender",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.GSCAFB_MaritalStatus$id.value=\"".getFieldDefaultValue("GSCAFB_MaritalStatus",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.GSCAFB_DateofBirthDay$id.value=\"1\"");
			$objResponse->addScript("document.$formname.GSCAFB_DateofBirthMonth$id.value=\"1\"");
			$objResponse->addScript("document.$formname.GSCAFB_DateofBirthYear$id.value=\"".(getCurrentYear()-100)."\"");
			$objResponse->addScript("document.$formname.EI_EmploymentType$id.value=\"".getFieldDefaultValue("EI_EmploymentType",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.EI_PrimaryOccupation$id.value=\"".getFieldDefaultValue("EI_PrimaryOccupation",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.EI_EmployerName$id.value=\"".getFieldDefaultValue("EI_EmployerName",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.EI_EmployeeNumber$id.value=\"".getFieldDefaultValue("EI_EmployeeNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.EI_PeriodAtEmployerMonth$id.value=\"1\"");
			$objResponse->addScript("document.$formname.EI_PeriodAtEmployerYear$id.value=\"".getCurrentYear()."\"");
			$objResponse->addScript("document.$formname.EI_IncomeBand$id.value=\"".getFieldDefaultValue("EI_IncomeBand",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.EI_SalaryFrequency$id.value=\"".getFieldDefaultValue("EI_SalaryFrequency",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_UnitNumber$id.value=\"Number\"");
			$objResponse->addScript("document.$formname.PCI_UnitName$id.value=\"Name\"");
			$objResponse->addScript("document.$formname.PCI_FloorNumber$id.value=\"Floor\"");
			$objResponse->addScript("document.$formname.PCI_PlotorStreetNumber$id.value=\"Number\"");
			$objResponse->addScript("document.$formname.PCI_LCorStreetName$id.value=\"Street Name\"");
			$objResponse->addScript("document.$formname.PCI_Parish$id.value=\"".getFieldDefaultValue("PCI_Parish",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_Suburb$id.value=\"".getFieldDefaultValue("PCI_Suburb",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_Village$id.value=\"".getFieldDefaultValue("PCI_Village",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_CountyorTown$id.value=\"".getFieldDefaultValue("PCI_CountyorTown",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_District$id.value=\"".getFieldDefaultValue("PCI_District",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_Region$id.value=\"".getFieldDefaultValue("PCI_Region",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_POBoxNumber$id.value=\"Number\"");
			$objResponse->addScript("document.$formname.PCI_PostOfficeTown$id.value=\"Town\"");
			$objResponse->addScript("document.$formname.PCI_CountryCode$id.value=\"".getFieldDefaultValue("PCI_CountryCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_PeriodAtAddressMonth$id.value=\"1\"");
			$objResponse->addScript("document.$formname.PCI_PeriodAtAddressYear$id.value=\"".getCurrentYear()."\"");
			$objResponse->addScript("document.$formname.PCI_FlagofOwnership$id.value=\"".getFieldDefaultValue("PCI_FlagofOwnership",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_PrimaryCountryDiallingCode$id.value=\"".getFieldDefaultValue("PCI_PrimaryCountryDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_PrimaryAreaDiallingCode$id.value=\"".getFieldDefaultValue("PCI_PrimaryAreaDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_PrimaryTelephoneNumber$id.value=\"".getFieldDefaultValue("PCI_PrimaryTelephoneNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_OtherCountryDiallingCode$id.value=\"".getFieldDefaultValue("PCI_OtherCountryDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_OtherAreaDiallingCode$id.value=\"".getFieldDefaultValue("PCI_OtherAreaDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_OtherTelephoneNumber$id.value=\"".getFieldDefaultValue("PCI_OtherTelephoneNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_MobileCountryDiallingCode$id.value=\"".getFieldDefaultValue("PCI_MobileCountryDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_MobileAreaDiallingCode$id.value=\"".getFieldDefaultValue("PCI_MobileAreaDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_MobileNumber$id.value=\"".getFieldDefaultValue("PCI_MobileNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_FAXCountryDiallingCode$id.value=\"".getFieldDefaultValue("PCI_FAXCountryDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_FAXAreaDiallingCode$id.value=\"".getFieldDefaultValue("PCI_FAXAreaDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_FAXNumber$id.value=\"".getFieldDefaultValue("PCI_FAXNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_EmailAddress$id.value=\"".getFieldDefaultValue("PCI_EmailAddress",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.PCI_Website$id.value=\"".getFieldDefaultValue("PCI_Website",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_UnitNumber$id.value=\"Number\"");
			$objResponse->addScript("document.$formname.SCI_UnitName$id.value=\"Name\"");
			$objResponse->addScript("document.$formname.SCI_FloorNumber$id.value=\"Floor\"");
			$objResponse->addScript("document.$formname.SCI_PlotorStreetNumber$id.value=\"Number\"");
			$objResponse->addScript("document.$formname.SCI_LCorStreetName$id.value=\"Street Name\"");
			$objResponse->addScript("document.$formname.SCI_Parish$id.value=\"".getFieldDefaultValue("SCI_Parish",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_Suburb$id.value=\"".getFieldDefaultValue("SCI_Suburb",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_Village$id.value=\"".getFieldDefaultValue("SCI_Village",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_CountyorTown$id.value=\"".getFieldDefaultValue("SCI_CountyorTown",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_District$id.value=\"".getFieldDefaultValue("SCI_District",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_Region$id.value=\"".getFieldDefaultValue("SCI_Region",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_POBoxNumber$id.value=\"Number\"");
			$objResponse->addScript("document.$formname.SCI_PostOfficeTown$id.value=\"Town\"");
			$objResponse->addScript("document.$formname.SCI_CountryCode$id.value=\"".getFieldDefaultValue("SCI_CountryCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_PeriodAtAddressMonth$id.value=\"1\"");
			$objResponse->addScript("document.$formname.SCI_PeriodAtAddressYear$id.value=\"".getCurrentYear()."\"");
			$objResponse->addScript("document.$formname.SCI_FlagofOwnership$id.value=\"".getFieldDefaultValue("SCI_FlagofOwnership",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_PrimaryCountryDiallingCode$id.value=\"".getFieldDefaultValue("SCI_PrimaryCountryDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_PrimaryAreaDiallingCode$id.value=\"".getFieldDefaultValue("SCI_PrimaryAreaDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_PrimaryTelephoneNumber$id.value=\"".getFieldDefaultValue("SCI_PrimaryTelephoneNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_OtherCountryDiallingCode$id.value=\"".getFieldDefaultValue("SCI_OtherCountryDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_OtherAreaDiallingCode$id.value=\"".getFieldDefaultValue("SCI_OtherAreaDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_OtherTelephoneNumber$id.value=\"".getFieldDefaultValue("SCI_OtherTelephoneNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_MobileCountryDiallingCode$id.value=\"".getFieldDefaultValue("SCI_MobileCountryDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_MobileAreaDiallingCode$id.value=\"".getFieldDefaultValue("SCI_MobileAreaDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_MobileNumber$id.value=\"".getFieldDefaultValue("SCI_MobileNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_FAXCountryDiallingCode$id.value=\"".getFieldDefaultValue("SCI_FAXCountryDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_FAXAreaDiallingCode$id.value=\"".getFieldDefaultValue("SCI_FAXAreaDiallingCode",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_FAXNumber$id.value=\"".getFieldDefaultValue("SCI_FAXNumber",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_EmailAddress$id.value=\"".getFieldDefaultValue("SCI_EmailAddress",$version,"CAP")."\"");
			$objResponse->addScript("document.$formname.SCI_Website$id.value=\"".getFieldDefaultValue("SCI_Website",$version,"CAP")."\"");
			//display print options
			if($id) { $objResponse->addAssign("printID$id","innerHTML",""); }
		}
		*/
		return $objResponse->getXML();
	}
}

/**
* function to copy PCI to SCI data
*/
function XcopyPCI2SCI($formname,$field,$id) {
	$objResponse = new vlDCResponse();
	$objResponse->addScript("document.$formname.S$field$id.value=document.$formname.P$field$id.value");
	return $objResponse->getXML();
}

/**
* update application number
*/
function XupdateApplicationNumber($formname,$id,$value,$version) {
	$objResponse = new vlDCResponse();
	for($i=1;$i<=$id;$i++) {
		if($value==getDetailedTableInfo2("vl_appendix_creditapplicationstatus","appendixvalue like '%approved%' and version='$version'","appendixcode")) {
			$objResponse->addAssign("CreditApplicationReferenceTDID$i","innerHTML","Loan&nbsp;Account&nbsp;or&nbsp;MG&nbsp;Number&nbsp;<font color=\"#FF0000\">*</font>");
			$objResponse->addScript("document.$formname.CreditApplicationReference$i.value=''");
		} else {
			$objResponse->addAssign("CreditApplicationReferenceTDID$i","innerHTML","Loan&nbsp;Application&nbsp;Number&nbsp;<font color=\"#FF0000\">*</font>");
			$objResponse->addScript("document.$formname.CreditApplicationReference$i.value='".generateCreditApplicationReference($i)."'");
		}
	}
	return $objResponse->getXML();
}

/**
* update employer
*/
function XupdateEmployer($formname,$id,$value,$version) {
	$objResponse = new vlDCResponse();
	if($value==getDetailedTableInfo2("vl_appendix_employmenttypes","appendixvalue like '%self%' and version='$version'","appendixcode")) {
		$objResponse->addScript("document.$formname.EI_EmployerName$id.value='SELF EMPLOYED'");
		$objResponse->addAssign("periodAtEmployerTDID$id","innerHTML","Began Self Employment&nbsp;<font color=\"#FF0000\">*</font>");
		$objResponse->addAssign("salaryTDID$id","innerHTML","Turnover&nbsp;<font color=\"#FF0000\">*</font>");
		$objResponse->addAssign("salaryFrequencyTDID$id","innerHTML","Revenue Frequency&nbsp;<font color=\"#FF0000\">*</font>");
	} else {
		$objResponse->addScript("document.$formname.EI_EmployerName$id.value=''");
		$objResponse->addAssign("periodAtEmployerTDID$id","innerHTML","Joined Current Employer&nbsp;<font color=\"#FF0000\">*</font>");
		$objResponse->addAssign("salaryTDID$id","innerHTML","Salary&nbsp;<font color=\"#FF0000\">*</font>");
		$objResponse->addAssign("salaryFrequencyTDID$id","innerHTML","Salary Frequency&nbsp;<font color=\"#FF0000\">*</font>");
	}
	return $objResponse->getXML();
}

/**
* function to populate ClientNumber&id field with data
*/
function XpopulateClientNumber($clientNumberValue,$formname,$id) {
	$objResponse = new vlDCResponse();
	$objResponse->addScript("document.$formname.ClientNumber$id.value=$clientNumberValue");
	$objResponse->addScript("document.$formname.oldClientNumber$id.value=\"$clientNumberValue\"");
	return $objResponse->getXML();
}
?>