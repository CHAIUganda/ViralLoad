:: Starting Backup of Mysql Database on server 
:: Define Date Variable
For /f "tokens=2-4 delims=/ " %%a in ('date /t') do (set dt=%%c%%a%%b)
:: Define Time Variable
For /f "tokens=1-4 delims=:." %%a in ('echo %time%') do (set tm=%%a%%b%%c%%d)

:: set bkupfilename=%1 %dt% %tm%.sql
set bkupfilename=%dt%.sql

:: Backing up to file: %bkupfilename%
"C:\xampp\mysql\bin\mysqldump.exe" -u root -proot#VL eid_uganda_vl > D:\Backups\Database\"db.vl.%bkupfilename%"

:: Ran a cleanup exercise on downloads.forms
"C:\Program Files (x86)\GnuWin32\bin\wget.exe" -q -O - http://192.168.0.43/index.cleanup.php
