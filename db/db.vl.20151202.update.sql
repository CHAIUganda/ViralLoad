drop table if exists vl_results_merged;
CREATE TABLE vl_results_merged (
	id double unsigned NOT NULL AUTO_INCREMENT,
	machine varchar(20) NOT NULL,
	worksheetID double unsigned NOT NULL,
	vlSampleID varchar(100) NOT NULL,
	resultAlphanumeric text NOT NULL,
	resultNumeric double not null,
	created datetime NOT NULL,
	createdby varchar(250) NOT NULL,
	PRIMARY KEY (id),
	UNIQUE KEY uniqueIndex (machine,worksheetID,vlSampleID),
	KEY machineIndex (machine),
	KEY worksheetIDIndex (worksheetID),
	KEY vlSampleIDIndex (vlSampleID) 
) ENGINE=InnoDB;
