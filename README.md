# Overview

The ViralLoad system was setup to support CPHL's efforts in the testing of patients' Viral Load using either Abbott or Roche machines

ViralLoad sits within CPHL's data center and can be accessed with a web browser from within the CPHL network by visiting http://192.168.0.43/

The ViralLoad system is used by the following key CPHL staff:

1.	Data Entrants: these input sample data for testing into the ViralLoad system.
2.	Quality Control: these check for discrepancies within the input data and if none are found, they approve the data for further processing.
3.	Lab Technicians: these process the data further by assigning it to worksheets for testing within either Abbott or Roche machines.
4.	Other Staff: other staff are responsible for the following tasks;
	a.	Receipt of samples from the facilities and organization of these samples into envelopes for entry by the data entrants
	b.	Logging of generated forms and the facilities to which they have been dispatched. The ViralLoad system will not accept a Form Number
		unless the Form Number has been previously logged within the ViralLoad system.
5.	IT Support: these support the other users by enabling the removal of samples from worksheets, linking/unlinking of patients to/from samples
	as well as assignment of rights to users based on the nature of the work the user is to undertake.

# Technologies
 _______________________________________________________________________________
|	Component					| Role          				| Version		|
|-------------------------------|-------------------------------|---------------|
| PHP 							| Programming Language 			| 5.4.28		|
|[MySQL](https://www.mysql.com)	| Database						| 5.6.25		|
|[Apache](http://apache.org/)	| Web Server					| 2.0			|
|_______________________________|_______________________________|_______________|

The base image for the application container is Windows Server 2008 R2 Standard. 

# System Requirements

For the server, recommended specifications are 8GB of RAM, 100GB available hard drive space, and a Core 2 Duo class 
processor or better. 
To access ViralLoad, a recent version of Google Chrome or Mozilla Firefox is recommended with JavaScript enabled.

# System Architecture

The ViralLoad system is designed as a 3 tiered application with the following layers;

1.	Apache Web Server
	The apache web server serves HTML pages to user's browsers upon receiving requests for service
	e.g when a user visits http://192.168.0.43/ or more precisely, http://192.168.0.43/index.php
	
2.	PHP Application Logic
	The requests for service from the apache web server are passed on to PHP application scripts
	which handle the logic e.g which function to call, which address to return or which data to process
	
3.	Database
	Data processing e.g authentication of users is done by comparing data stored within a MySQL database
	with data provided to the application through the HTML interfaces. PHP uses pre-compiled functions
	to parse queries to the MySQL database as well as retrieve results from the same.

# System Architecture: User Authentication

The ViralLoad system authenticates user accounts by check with the ViralLoad local database to gauge the 
authenticity of the user credentials supplied

# System Architecture: Roles/Permissions

The ViralLoad system restricts access to key features based on whether or not, a user has been granted the relevant
permissions. Permissions are managed by an administrative user at 
http://192.168.0.43/admin/?act=permissions&nav=configuration

The key permissions on the ViralLoad system include the following:

1.	Input/Manage Samples
	When checked, permits a user to input and edit samples on ViralLoad.
	Usually assigned to Data Entrants.
	
2.	Verify Samples
	When checked, permits a user to approve a sample entered by a data entrant.
	Usually assigned to Quality Control Staff.
	
3.	Reverse Approval of Samples
	When checked, permits a user to reverse the approval of a sample. Doing so implies the sample is removed from any worksheet it may previously have been assigned to, or any set of results it may have been included within.
	Usually assigned to specific Quality Control Staff as this feature is meant to be used sparingly.
	
4.	Generate Worksheets
	When checked, permits a user to generate worksheets on ViralLoad.
	Usually assigned to Lab Technicians.
	
5.	Generate Clinical Request Forms
	When checked, permits a user to generate clinical request forms, log the facility to which a range of booklets/printed forms have been dispatched etc

6.	View Results
	When checked, permits a user to view and search within the results on ViralLoad.
	
7.	View Reports
	When checked, permits a user to view the reports on ViralLoad
	
8.	View Reports (Quality Control)
	When checked, permits a user to view the reports on ViralLoad, but more specifically, download a list of "Patients with multiple, and different results"
		
# Deployment

System is designed to operate within the following server scope;

1.	mod_rewrite should be set to on within the apache httpd.conf configuration file

2.	The loaded PHP configuration file is at C:\xampp\apache\bin\php.ini
	The following PHP options should be configured accordingly within the php.ini configuration file
	max_execution_time = 2880000     ; Maximum execution time of each script, in seconds
	max_input_time = 720000	; Maximum amount of time each script may spend parsing request data
	memory_limit = 2048M      ; Maximum amount of memory a script may consume
	post_max_size = 1024M; (permits uploads of files or form post submissions of up to 1GB in size)
	file_uploads = On; (turning this to off prevents uploads of files e.g data templates onto the system)
	upload_max_filesize = 1024M;  (permits uploads of files up to 1024Mb in size)
	display_errors = Off; (only because this is a production server)
	log_errors = Off; (may be turned on to enable logging of errors during maintenance)

3.	The loaded MySQL configuration file is at C:\xampp\mysql\bin\my.ini
	The following MySQL variables (within the my.ini or my.cnf configuration file) should be configured accordingly;
	innodb_buffer_pool_size = 5G # (for a server with 16GB of RAM, 5GB may be allocated to the database)
	innodb_log_file_size = 256M
	max_connections = 151 (increasing this increases system processing speeds but may have the adverse effect of drawing more resources than the server can afford)
	expire_logs_days = 5 # (database logs are flushed after 5 days. Increase this to keep logs for longer period but only if server disk space permits)

3.	Wget, Lynx or Links should be installed on the server and a cron job set up as follows (assuming wget has been installed);

	1.	Go to Windows Task Scheduler
	2. 	Create a task which runs at 11:30PM every day and which calls the batch script at C:\xampp\htdocs\viralload\automation\automation.db.backups.bat
		This task will be responsible for creating a daily database backup/dump from which the ViralLoad system may be restored incase of a total data loss
	3. 	Create a task which runs every 15 minutes and which calls the batch script at C:\xampp\htdocs\viralload\automation\automation.cleanup.bat
		This task will be responsible for sending mail as well as removing worksheets that may not have been properly deleted from the ViralLoad system.

# Contributors

- Isaac Ssewanyana (CPHL) - High Level Process Analysis
- Prossy Mbabazi (CPHL) - High Level Process Analysis and Integration
- Logan Smith (CHAI Uganda) - High Level Process Analysis and Integration
- Mimano Geoffrey (CHAI Uganda) - High Level Process Analysis and Integration
- Paul Ekudu (CHAI Uganda) - High Level Process Analysis
- Meghan Wareham (CHAI Uganda) - High Level Process Analysis
- Otim Samuel (Trail Analytics) - System Design and Implementation
