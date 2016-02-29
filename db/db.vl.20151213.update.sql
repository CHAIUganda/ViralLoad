drop table if exists vl_samples_otherregimen;
CREATE TABLE vl_samples_otherregimen (
	id double unsigned NOT NULL AUTO_INCREMENT,
	sampleID double unsigned NOT NULL,
	currentRegimenID mediumint unsigned NOT NULL,
	otherRegimen varchar(250) NOT NULL,
	created datetime NOT NULL,
	createdby varchar(250) NOT NULL,
	PRIMARY KEY (id),
	UNIQUE KEY uniqueIndex (sampleID,currentRegimenID,otherRegimen),
	KEY sampleIDIndex (sampleID),
	KEY currentRegimenIDIndex (currentRegimenID),
	KEY otherRegimenIndex (otherRegimen)
) ENGINE=InnoDB;
