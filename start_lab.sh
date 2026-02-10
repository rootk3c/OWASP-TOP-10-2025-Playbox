#!/bin/bash

echo "[*] STARTING OWASP LAB (FORCE RECREATE MODE)..."

# 1. Dashboard
echo "[+] Recreating Dashboard..."
cd dashboard
docker-compose up -d --force-recreate --build
cd ..

# 2. Challenge A01
echo "[+] Recreating A01: Broken Access..."
cd "challenges/A01_2025_Broken_Access_Control"
docker-compose up -d --force-recreate --build
cd ../..

# 3. Challenge A07
echo "[+] Recreating A07: Auth Failure..."
cd "challenges/A07_2025_Authentication_Failure"
docker-compose up -d --force-recreate --build
cd ../..

echo "===================================================="
echo " SYSTEMS RECREATED & ONLINE: http://localhost:8000"
echo "===================================================="