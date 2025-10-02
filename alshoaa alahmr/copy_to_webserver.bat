@echo off
echo This script will copy your website files to the Apache document root
echo.

REM Try to detect common Apache installations
if exist "C:\xampp\htdocs" (
    set "DEST_DIR=C:\xampp\htdocs\alshoaa_alahmr"
    echo XAMPP detected, will copy to %DEST_DIR%
) else if exist "C:\wamp64\www" (
    set "DEST_DIR=C:\wamp64\www\alshoaa_alahmr"
    echo WAMP detected, will copy to %DEST_DIR%
) else if exist "C:\wamp\www" (
    set "DEST_DIR=C:\wamp\www\alshoaa_alahmr"
    echo WAMP detected, will copy to %DEST_DIR%
) else if exist "C:\inetpub\wwwroot" (
    set "DEST_DIR=C:\inetpub\wwwroot\alshoaa_alahmr"
    echo IIS detected, will copy to %DEST_DIR%
) else (
    echo Could not detect Apache document root.
    echo Please enter the path to your Apache document root:
    set /p DEST_DIR="Path: "
    set "DEST_DIR=%DEST_DIR%\alshoaa_alahmr"
)

echo.
echo Will copy files from "%~dp0" to "%DEST_DIR%"
echo.

if not exist "%DEST_DIR%" mkdir "%DEST_DIR%"

echo Copying files...
xcopy "%~dp0*.*" "%DEST_DIR%\" /E /H /C /I /Y

echo.
echo Done!
echo.
echo You can now access your website at: http://localhost/alshoaa_alahmr/
echo.

pause
