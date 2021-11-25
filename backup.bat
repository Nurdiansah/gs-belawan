@echo off



:start

SET @str1=GREG
SET @str2=OR

REM echo %date% %time% 

php "backup.exe"

timeout 3600 > nul
goto start