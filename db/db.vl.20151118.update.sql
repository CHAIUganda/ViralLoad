alter table vl_output_samplescsv add IP varchar(250) not null after Hub;
alter table vl_facilities add active int(1) unsigned not null default '1' after returnAddress;
update vl_facilities set active=1;
