@echo off
echo ============================================================
echo   UIU Research Portal - Unified Server
echo   Running Frontend + Backend REST API on http://localhost:8000
echo ============================================================
"C:\xampp\php\php.exe" -d browscap="C:\xampp\php\extras\browscap.ini" -d extension_dir="C:\xampp\php\ext" -S 127.0.0.1:8000 router.php
pause
