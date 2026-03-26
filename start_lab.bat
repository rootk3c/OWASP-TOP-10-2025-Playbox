@echo off
title OWASP Lab Launcher
echo [!] STARTING OWASP LAB (FORCE RECREATE MODE)...

REM --- 1. Dashboard ---
echo.
echo [+] Recreating Dashboard (Port 8000)...
cd dashboard
docker-compose up -d --force-recreate --build
cd ..

REM --- 2. Challenge A01 + A06 ---
echo.
echo [+] Recreating A01_A06_Broken_Access (Port 8001)...
cd "challenges/A01_A06_Broken_Access+Insecure_Design"
docker-compose up -d --force-recreate --build
cd ..\..

REM --- 3. Challenge A02 ---
echo.
echo [+] Recreating A02: Security Misconfigurations (Port 8002)...
cd "challenges/A02_2025_Security_Misconfigurations"
docker-compose up -d --force-recreate --build
cd ..\..

echo.
echo [+] Recreating A02: Security Misconfigurations (Port 8002)...
cd "challenges/A03_2025_Software_Supply_Chain_Failures"
docker-compose up -d --force-recreate --build
cd ..\..

echo.
echo [+] Recreating A02: Security Misconfigurations (Port 8004)...
cd "challenges/A04_2025_Cryptographic_Failures"
docker-compose up -d --force-recreate --build
cd ..\..

REM --- 3. Challenge A05 ---
echo.
echo [+] Recreating A05: Injections (Port 8005)...
cd "challenges/A05_2025_Injection"
docker-compose up -d --force-recreate --build
cd ..\..

REM --- 3. Challenge A07 ---
echo.
echo [+] Recreating A07: Auth Failure (Port 8007)...
cd "challenges/A07_2025_Authentication_Failures"
docker-compose up -d --force-recreate --build
cd ..\..

echo.
echo [+] Recreating A08: Software_and_Data_Integrity_Failures (Port 8008)...
cd "challenges/A08_2025_Software_and_Data_Integrity_Failures"
docker-compose up -d --force-recreate --build
cd ..\..

echo.
echo [+] Recreating A09: Security_Logging_and_Alerting_Failures (Port 8009)...
cd "challenges/A09_2025_Security_Logging_and_Alerting_Failures"
docker-compose up -d --force-recreate --build
cd ..\..

echo.
echo [+] Recreating A10: Mishandling_of_Exceptional_Conditions (Port 8010)...
cd "challenges/A10_2025_Mishandling_of_Exceptional_Conditions"
docker-compose up -d --force-recreate --build
cd ..\..

echo.
echo ====================================================
echo  SYSTEMS RECREATED AND ONLINE
echo  Dashboard: http://localhost:8000
echo ====================================================
pause

