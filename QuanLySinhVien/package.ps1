# Script đóng gói source code để bàn giao
# Sử dụng: .\package.ps1

$ErrorActionPreference = "Stop"

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  ĐÓNG GÓI SOURCE CODE BÀN GIAO" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Tên file output
$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$outputFileName = "HeThongQuanLySinhVien_$timestamp.zip"
$outputPath = Join-Path $PSScriptRoot "..\$outputFileName"

# Danh sách các file/folder cần loại trừ
$excludeItems = @(
    "node_modules",
    "vendor",
    ".env",
    ".env.backup",
    ".env.production",
    "storage\logs\*",
    "storage\framework\cache\*",
    "storage\framework\sessions\*",
    "storage\framework\views\*",
    "bootstrap\cache\*.php",
    "public\hot",
    "public\build",
    ".git",
    ".idea",
    ".vscode",
    "*.log",
    ".DS_Store",
    "Thumbs.db",
    ".phpunit.result.cache",
    "package.ps1",
    "package.bat"
)

Write-Host "Đang chuẩn bị đóng gói..." -ForegroundColor Yellow
Write-Host "File output: $outputFileName" -ForegroundColor Green
Write-Host ""

# Kiểm tra xem có Compress-Archive không (PowerShell 5.0+)
if (Get-Command Compress-Archive -ErrorAction SilentlyContinue) {
    
    Write-Host "Đang nén file..." -ForegroundColor Yellow
    
    # Tạo file tạm thời chứa danh sách file cần nén
    $tempListFile = [System.IO.Path]::GetTempFileName()
    
    # Lấy tất cả file trong thư mục hiện tại
    $allItems = Get-ChildItem -Path $PSScriptRoot -Recurse -Force | Where-Object {
        $item = $_
        $shouldExclude = $false
        
        foreach ($exclude in $excludeItems) {
            $excludePath = Join-Path $PSScriptRoot $exclude
            if ($item.FullName -like "$excludePath*") {
                $shouldExclude = $true
                break
            }
        }
        
        return -not $shouldExclude
    }
    
    # Nén file
    try {
        # Tạo thư mục tạm
        $tempDir = Join-Path $env:TEMP "HeThongQuanLySinhVien_Package"
        if (Test-Path $tempDir) {
            Remove-Item $tempDir -Recurse -Force
        }
        New-Item -ItemType Directory -Path $tempDir | Out-Null
        
        # Copy các file cần thiết
        Write-Host "Đang copy file..." -ForegroundColor Yellow
        $sourceDir = $PSScriptRoot
        $destDir = Join-Path $tempDir "QuanLySinhVien"
        
        # Copy toàn bộ thư mục
        Copy-Item -Path $sourceDir -Destination $destDir -Recurse -Force
        
        # Xóa các file không cần thiết
        foreach ($exclude in $excludeItems) {
            $excludePath = Join-Path $destDir $exclude
            if (Test-Path $excludePath) {
                Remove-Item $excludePath -Recurse -Force -ErrorAction SilentlyContinue
                Write-Host "  ✓ Đã xóa: $exclude" -ForegroundColor DarkGray
            }
        }
        
        # Tạo file .env.example nếu chưa có
        $envExample = Join-Path $destDir ".env.example"
        $envFile = Join-Path $sourceDir ".env"
        if ((Test-Path $envFile) -and -not (Test-Path $envExample)) {
            Copy-Item $envFile $envExample
            Write-Host "  ✓ Đã tạo .env.example" -ForegroundColor Green
        }
        
        Write-Host ""
        Write-Host "Đang nén thành file ZIP..." -ForegroundColor Yellow
        
        # Nén thư mục
        Compress-Archive -Path "$tempDir\*" -DestinationPath $outputPath -Force
        
        # Xóa thư mục tạm
        Remove-Item $tempDir -Recurse -Force
        
        Write-Host ""
        Write-Host "========================================" -ForegroundColor Green
        Write-Host "  ĐÓNG GÓI THÀNH CÔNG!" -ForegroundColor Green
        Write-Host "========================================" -ForegroundColor Green
        Write-Host ""
        Write-Host "File đã được lưu tại:" -ForegroundColor Cyan
        Write-Host $outputPath -ForegroundColor Yellow
        Write-Host ""
        Write-Host "Kích thước file:" -ForegroundColor Cyan
        $fileSize = (Get-Item $outputPath).Length
        $fileSizeMB = [math]::Round($fileSize / 1MB, 2)
        Write-Host "$fileSizeMB MB" -ForegroundColor Yellow
        Write-Host ""
        
    } catch {
        Write-Host ""
        Write-Host "LỖI: Không thể nén file!" -ForegroundColor Red
        Write-Host $_.Exception.Message -ForegroundColor Red
        exit 1
    }
    
} else {
    Write-Host "LỖI: Không tìm thấy lệnh Compress-Archive" -ForegroundColor Red
    Write-Host "Vui lòng sử dụng PowerShell 5.0 trở lên" -ForegroundColor Yellow
    exit 1
}

Write-Host "Nhấn phím bất kỳ để đóng..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
