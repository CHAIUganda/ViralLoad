ALTER TABLE `vl_results_merged` ADD `suppressed` ENUM( 'YES', 'NO', 'UNKNOWN' ) NOT NULL DEFAULT 'UNKNOWN' AFTER `resultNumeric` ;


ALTER TABLE `vl_samples` ADD `verified` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `suspectedTreatmentFailureSampleTypeID` ;