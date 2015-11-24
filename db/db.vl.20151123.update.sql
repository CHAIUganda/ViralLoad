create index otherIDIndex on vl_patients (otherID);
drop table if exists vl_logs_warnings;
CREATE TABLE vl_logs_warnings (
	id double unsigned NOT NULL AUTO_INCREMENT,
	logCategory varchar(250) NOT NULL,
	logDetails text NOT NULL,
	logTableID double unsigned NOT NULL,
	created datetime NOT NULL,
	createdby varchar(250) NOT NULL,
	PRIMARY KEY (id),
	KEY logCategoryIndex (logCategory),
	KEY logTableIDIndex (logTableID) 
) ENGINE=InnoDB;
