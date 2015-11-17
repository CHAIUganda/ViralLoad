update vl_facilities set facility=trim(facility);
update vl_districts set district=trim(district);
update vl_regions set region=trim(region);
create index regionIndex on vl_regions (region);
create index createdbyIndex on vl_regions (createdby);
create index districtIndex on vl_districts (district);
create index createdbyIndex on vl_districts (createdby);
create index facilityIndex on vl_facilities (facility);
create index createdbyIndex on vl_facilities (createdby);
create index hubIDIndex on vl_facilities (hubID);
create index hubIndex on vl_hubs (hub);
create index createdbyIndex on vl_hubs (createdby);
create index appendixIndex on vl_appendix_sampletype (appendix);
create index phoneIndex on vl_patients_phone (phone);
create index appendixIndex on vl_appendix_regimen (appendix);
create index createdbyIndex on vl_appendix_regimen (createdby);
create index appendixIndex on vl_appendix_treatmentinitiation (appendix);
create index createdbyIndex on vl_appendix_treatmentinitiation (createdby);
create index appendixIndex on vl_appendix_treatmentstatus (appendix);
create index createdbyIndex on vl_appendix_treatmentstatus (createdby);
create index appendixIndex on vl_appendix_failurereason (appendix);
create index createdbyIndex on vl_appendix_failurereason (createdby);
create index appendixIndex on vl_appendix_tbtreatmentphase (appendix);
create index createdbyIndex on vl_appendix_tbtreatmentphase (createdby);
create index appendixIndex on vl_appendix_arvadherence (appendix);
create index createdbyIndex on vl_appendix_arvadherence (createdby);

drop table if exists vl_ips;
CREATE TABLE vl_ips (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  ip varchar(250) NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  KEY ipIndex (ip),
  KEY createdIndex (created) 
) ENGINE=InnoDB;

alter table vl_hubs change implementingPartner ipID mediumint unsigned not null;
alter table vl_facilities add ipID mediumint unsigned not null after hubID;
