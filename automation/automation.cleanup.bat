:: Starting Backup of Mysql Database on server 
:: Define Date Variable
For /f "tokens=2-4 delims=/ " %%a in ('date /t') do (set dt=%%c%%a%%b)
:: Define Time Variable
For /f "tokens=1-4 delims=:." %%a in ('echo %time%') do (set tm=%%a%%b%%c%%d)

:: Ran a cleanup exercise every 15 mins
"C:\Program Files (x86)\GnuWin32\bin\wget.exe" -q -O - http://192.168.0.43/index.lnx.php
