@echo off
title OWASP Lab Launcher
echo [!] STARTING OWASP LAB (FORCE RECREATE MODE)...

REM --- 1. Dashboard ---
echo.
echo [+] Recreating Dashboard (Port 8000)...
cd dashboard
docker-compose up -d --force-recreate --build
cd ..

REM --- 2. Challenge A01 ---
echo.
echo [+] Recreating A01: Broken Access (Port 8001)...
cd "challenges/A01_2025_Broken_Access_Control"
docker-compose up -d --force-recreate --build
cd ..\..

REM --- 3. Challenge A07 ---
echo.
echo [+] Recreating A07: Auth Failure (Port 8002)...
cd "challenges/A07_2025_Authentication_Failure"
docker-compose up -d --force-recreate --build
cd ..\..

echo.
echo ====================================================
echo  SYSTEMS RECREATED & ONLINE
echo  Dashboard: http://localhost:8000
echo ====================================================
pause