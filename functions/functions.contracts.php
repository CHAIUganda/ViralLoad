<?
/**
* CONTRACT FUNCTIONS
*/

/**
* log contract activity/updates
*/
function getContractEndDate($contractStartDate,$contractTerm) {
	switch(strtolower($contractTerm)) {
		case "6 months":
			return addToDate($contractStartDate,180);
		break;
		case "1 year":
			return addToDate($contractStartDate,365);
		break;
		case "2 years":
			return addToDate($contractStartDate,730);
		break;
		case "3 years":
			return addToDate($contractStartDate,1095);
		break;
		case "4 years":
			return addToDate($contractStartDate,1460);
		break;
		case "indefinite":
			return "";
		break;
	}
}
?>