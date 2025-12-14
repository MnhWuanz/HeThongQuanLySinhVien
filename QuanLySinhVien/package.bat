@echo off
chcp 65001 >nul
title Đóng gói Source Code Bàn Giao

echo ========================================
echo   ĐÓNG GÓI SOURCE CODE BÀN GIAO
echo ========================================
echo.

REM Kiểm tra PowerShell
where powershell >nul 2>&1
if %errorlevel% neq 0 (
    echo LỖI: Không tìm thấy PowerShell!
    echo Vui lòng cài đặt PowerShell.
    pause
    exit /b 1
)

echo Đang chạy script PowerShell...
echo.

REM Chạy script PowerShell
powershell -ExecutionPolicy Bypass -File "%~dp0package.ps1"

if %errorlevel% neq 0 (
    echo.
    echo Đóng gói thất bại!
    pause
    exit /b 1
)

exit /b 0
