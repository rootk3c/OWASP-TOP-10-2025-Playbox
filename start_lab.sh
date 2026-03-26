#!/bin/bash

echo "[!] STARTING OWASP LAB (FORCE RECREATE MODE)..."

# --- 1. Dashboard ---
echo ""
echo "[+] Recreating Dashboard (Port 8000)..."
cd dashboard
docker-compose up -d --force-recreate --build
cd ..

# --- 2. Challenge A01 ---

echo ""
echo "[+] Recreating A02 (Port 8002)..."
cd "challenges/A01_A06_Broken_Access+Insecure_Design"
docker-compose up -d --force-recreate --build
cd ../..

echo ""
echo "[+] Recreating A02 (Port 8002)..."
cd "challenges/A02_2025_Security_Misconfigurations"
docker-compose up -d --force-recreate --build
cd ../..

echo ""
echo "[+] Recreating A04 (Port 8004)..."
cd "challenges/A03_2025_Software_Supply_Chain_Failures"
docker-compose up -d --force-recreate --build
cd ../..

echo ""
echo "[+] Recreating A04 (Port 8004)..."
cd "challenges/A04_2025_Cryptographic_Failures"
docker-compose up -d --force-recreate --build
cd ../..

echo ""
echo "[+] Recreating A05 (Port 8005)..."
cd "challenges/A05_2025_Injection"
docker-compose up -d --force-recreate --build
cd ../..

# --- 3. Challenge A07 ---
echo ""
echo "[+] Recreating A07 (Port 8007)..."
cd "challenges/A07_2025_Authentication_Failures"
docker-compose up -d --force-recreate --build
cd ../..

echo ""
echo "[+] Recreating A08 (Port 8008)..."
cd "challenges/A08_2025_Software_and_Data_Integrity_Failures"
docker-compose up -d --force-recreate --build
cd ../..

echo ""
echo "===================================================="
echo " SYSTEMS RECREATED AND ONLINE"
echo " Dashboard: http://localhost:8000"
echo "===================================================="

