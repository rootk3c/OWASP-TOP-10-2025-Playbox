#!/bin/bash
echo "[*] STOPPING ALL..."

cd dashboard && docker-compose down && cd ..
cd "challenges/A01_2025_Broken_Access_Control" && docker-compose down && cd ../..
cd "challenges/A04_Cryptographic_Failures" && docker-compose down && cd ../..
cd "challenges/A07_2025_Authentication_Failures" && docker-compose down && cd ../..

echo "[-] Done."

