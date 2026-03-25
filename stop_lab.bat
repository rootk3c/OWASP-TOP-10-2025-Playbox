@echo off
echo [!] STOPPING ALL CONTAINERS...

cd dashboard
docker-compose down
cd ..

cd "challenges/A01_A06_Broken_Access+Insecure_Design"
docker-compose down
cd ..\..


cd "challenges/A02_2025_Security_Misconfigurations"
docker-compose down
cd ..\..

cd "challenges/A04_2025_Cryptographic_Failures"
docker-compose down
cd ..\..

cd "challenges/A05_2025_Injection"
docker-compose down -v 
cd ..\..

cd "challenges/A07_2025_Authentication_Failures"
docker-compose down
cd ..\..

cd "challenges/A08_2025_Software_and_Data_Integrity_Failures"
docker-compose down
cd ..\..

echo [-] All stopped.
pause
