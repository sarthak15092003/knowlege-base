@echo off
REM Quick Deploy to Live Server (Windows)
REM This script commits and pushes your changes to trigger automatic deployment

echo ========================================
echo    Deploy to Live Server (GitHub)
echo ========================================
echo.

REM Check if there are changes
git status --short > temp.txt
set /p changes=<temp.txt
del temp.txt

if "%changes%"=="" (
    echo No changes to deploy!
    pause
    exit /b 0
)

REM Show changes
echo Changes to be deployed:
git status -s
echo.

REM Get commit message
set /p commit_msg="Enter commit message (or press Enter for default): "
if "%commit_msg%"=="" (
    for /f "tokens=1-3 delims=/ " %%a in ('date /t') do set mydate=%%c-%%a-%%b
    for /f "tokens=1-2 delims=: " %%a in ('time /t') do set mytime=%%a:%%b
    set commit_msg=Deploy to production - %mydate% %mytime%
)

REM Confirm deployment
echo.
echo WARNING: This will deploy to your LIVE server!
set /p confirm="Continue? (yes/no): "

if not "%confirm%"=="yes" (
    echo Deployment cancelled.
    pause
    exit /b 0
)

REM Deploy
echo.
echo Committing changes...
git add .
git commit -m "%commit_msg%"

echo Pushing to GitHub...
git push origin main

echo.
echo ========================================
echo Deployment triggered successfully!
echo ========================================
echo.
echo Monitor deployment progress at:
echo https://github.com/sarthak15092003/knowlege-base/actions
echo.
echo Deployment typically takes 2-5 minutes.
echo.
pause
