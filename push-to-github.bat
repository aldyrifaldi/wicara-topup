@echo off
REM Wicara Top-up - Create GitHub Repo and Push
REM Run this after creating the repo on GitHub

echo ========================================
echo  Wicara Top-up - Git Push Script
echo ========================================
echo.

cd /d "C:\Users\aldyr\Documents\projects\wicara\wicara-topup-new"

echo Step 1: Checking SSH connection...
ssh -T git@github.com 2>nul
if %errorlevel% neq 0 (
    echo SSH authentication failed. Please check your SSH keys.
    pause
    exit /b 1
)

echo.
echo Step 2: Pushing to GitHub...
git push -u origin main

if %errorlevel% equ 0 (
    echo.
    echo ========================================
    echo  SUCCESS! Repository pushed to GitHub.
    echo ========================================
    echo URL: https://github.com/aldyrifaldi/wicara-topup
) else (
    echo.
    echo Push failed. Make sure the repository exists on GitHub.
    echo Create it at: https://github.com/new
    echo Name it: wicara-topup
)

pause
