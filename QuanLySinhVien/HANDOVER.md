# TÀI LIỆU BÀN GIAO DỰ ÁN

## THÔNG TIN DỰ ÁN

**Tên dự án**: Hệ Thống Quản Lý Sinh Viên  
**Ngày bàn giao**: December 14, 2025  
**Phiên bản**: 1.0.0  
**Người bàn giao**: [Tên người bàn giao]  
**Người nhận**: [Tên người nhận]

---

## 1. TỔNG QUAN DỰ ÁN

### 1.1. Mô tả
Hệ thống quản lý sinh viên được xây dựng để hỗ trợ quản lý thông tin sinh viên, điểm số, lớp học, khoa và môn học trong trường học một cách hiệu quả và chuyên nghiệp.

### 1.2. Mục tiêu
- Số hóa quy trình quản lý sinh viên
- Tự động hóa việc tính toán điểm trung bình
- Hỗ trợ xuất báo cáo và thống kê nhanh chóng
- Giao diện thân thiện, dễ sử dụng

### 1.3. Phạm vi
- Quản lý thông tin sinh viên
- Quản lý điểm số theo môn học
- Quản lý lớp học và khoa
- Xuất báo cáo Excel
- Thống kê và báo cáo

---

## 2. CÔNG NGHỆ SỬ DỤNG

### 2.1. Backend
- **Framework**: Laravel 12.x
- **Admin Panel**: Filament 3.3
- **Language**: PHP 8.2+
- **Database**: MySQL 8.0

### 2.2. Frontend
- **Framework**: Livewire
- **UI Framework**: Tailwind CSS
- **JavaScript**: Alpine.js

### 2.3. Thư viện bên thứ ba
- Maatwebsite Excel 3.1 - Export Excel
- Laravel Sanctum - Authentication
- Spatie Laravel Permission - Quản lý quyền

---

## 3. CẤU TRÚC DATABASE

### 3.1. Các bảng chính

#### Bảng `users`
- Lưu trữ thông tin tài khoản admin
- Trường chính: id, name, email, password

#### Bảng `departments`
- Quản lý thông tin khoa
- Trường chính: id, name, code, description

#### Bảng `classes`
- Quản lý thông tin lớp học
- Trường chính: id, name, code, department_id
- Liên kết: departments (n-1)

#### Bảng `subjects`
- Quản lý môn học
- Trường chính: id, name, code, credits, department_id
- Liên kết: departments (n-1)

#### Bảng `students`
- Quản lý thông tin sinh viên
- Trường chính: id, student_code, full_name, birth_date, gender, address, email, phone, class_id
- Liên kết: classes (n-1)

#### Bảng `scores`
- Quản lý điểm số
- Trường chính: id, student_id, subject_id, midterm_score, final_score, average_score
- Liên kết: students (n-1), subjects (n-1)

### 3.2. Relationships
```
departments (1) ─────< (n) classes
departments (1) ─────< (n) subjects
classes (1) ─────< (n) students
students (1) ─────< (n) scores
subjects (1) ─────< (n) scores
```

---

## 4. TÍNH NĂNG CHÍNH

### 4.1. Quản lý Sinh viên
- ✅ Thêm sinh viên mới
- ✅ Sửa thông tin sinh viên
- ✅ Xóa sinh viên
- ✅ Tìm kiếm và lọc sinh viên
- ✅ Xem chi tiết sinh viên
- ✅ Xuất danh sách sinh viên

### 4.2. Quản lý Điểm số
- ✅ Nhập điểm giữa kỳ và cuối kỳ
- ✅ Tự động tính điểm trung bình
- ✅ Xem lịch sử điểm của sinh viên
- ✅ Xuất bảng điểm ra Excel

### 4.3. Quản lý Lớp học
- ✅ Thêm/sửa/xóa lớp học
- ✅ Xem danh sách sinh viên trong lớp
- ✅ Thống kê số lượng sinh viên

### 4.4. Quản lý Khoa
- ✅ Thêm/sửa/xóa khoa
- ✅ Xem danh sách lớp thuộc khoa
- ✅ Thống kê theo khoa

### 4.5. Quản lý Môn học
- ✅ Thêm/sửa/xóa môn học
- ✅ Quản lý số tín chỉ
- ✅ Phân loại theo khoa

### 4.6. Xuất báo cáo
- ✅ Xuất danh sách sinh viên có điểm
- ✅ Xuất theo lớp, khoa
- ✅ Định dạng Excel chuyên nghiệp

---

## 5. TÀI KHOẢN MẶC ĐỊNH

### Admin Account
```
Email: admin@example.com
Password: password
```

**⚠️ QUAN TRỌNG**: Đổi mật khẩu ngay sau khi đăng nhập lần đầu!

---

## 6. FILE VÀ THỨ MỤC QUAN TRỌNG

### 6.1. File cấu hình
- `.env` - File cấu hình môi trường (không được commit vào Git)
- `.env.example` - File mẫu cấu hình
- `config/` - Thư mục chứa các file config

### 6.2. Code chính
- `app/Models/` - Eloquent Models
- `app/Filament/Resources/` - Filament Resources (CRUD interfaces)
- `app/Exports/` - Excel Export classes
- `database/migrations/` - Database migrations
- `database/seeders/` - Database seeders

### 6.3. File quan trọng
- `composer.json` - PHP dependencies
- `package.json` - Node.js dependencies
- `README.md` - Hướng dẫn cài đặt và sử dụng

---

## 7. HƯỚNG DẪN CÀI ĐẶT

Xem chi tiết trong file [README.md](README.md)

### Tóm tắt các bước:
1. Clone/giải nén source code
2. Cài đặt dependencies: `composer install` và `npm install`
3. Copy file `.env.example` thành `.env`
4. Cấu hình database trong file `.env`
5. Tạo database MySQL
6. Chạy migrations: `php artisan migrate`
7. Chạy seeders: `php artisan db:seed`
8. Build assets: `npm run build`
9. Khởi động server: `php artisan serve`

---

## 8. HƯỚNG DẪN ĐÓNG GÓI SOURCE CODE

### 8.1. Sử dụng script tự động (Khuyến nghị)

#### Windows:
```batch
# Chạy file .bat
package.bat

# Hoặc chạy file PowerShell
powershell -ExecutionPolicy Bypass -File package.ps1
```

Script sẽ tự động:
- Loại bỏ các thư mục không cần thiết (node_modules, vendor, cache...)
- Tạo file .env.example
- Nén thành file ZIP với timestamp
- Lưu vào thư mục cha

### 8.2. Đóng gói thủ công

**Files/folders cần loại bỏ**:
- `node_modules/` - Node.js dependencies (sẽ cài lại)
- `vendor/` - Composer dependencies (sẽ cài lại)
- `.env` - File cấu hình (chứa thông tin nhạy cảm)
- `storage/logs/*.log` - Log files
- `storage/framework/cache/` - Cache files
- `storage/framework/sessions/` - Session files
- `storage/framework/views/` - Compiled views
- `bootstrap/cache/*.php` - Bootstrap cache
- `public/build/` - Compiled assets
- `.git/` - Git repository
- `.idea/`, `.vscode/` - IDE settings

**Files cần giữ lại**:
- `.env.example` - File mẫu cấu hình
- `README.md` - Hướng dẫn
- `HANDOVER.md` - Tài liệu bàn giao (file này)
- Toàn bộ source code khác

---

## 9. CHECKLIST BÀN GIAO

### 9.1. Source Code
- [ ] Source code đầy đủ, không thiếu file
- [ ] File .env.example đã được tạo
- [ ] README.md đã được cập nhật
- [ ] HANDOVER.md đã hoàn thiện
- [ ] Code đã được format chuẩn
- [ ] Không có file .env trong package

### 9.2. Database
- [ ] Migrations hoàn thiện và chạy được
- [ ] Seeders đã được kiểm tra
- [ ] Database schema đã được document

### 9.3. Documentation
- [ ] Hướng dẫn cài đặt đầy đủ
- [ ] Hướng dẫn sử dụng cơ bản
- [ ] Tài liệu API (nếu có)
- [ ] Thông tin tài khoản mặc định

### 9.4. Testing
- [ ] Đã test cài đặt trên máy sạch
- [ ] Các chức năng chính hoạt động tốt
- [ ] Không có lỗi nghiêm trọng

---

## 10. HƯỚNG DẪN TRIỂN KHAI PRODUCTION

### 10.1. Yêu cầu Server
- VPS/Server với Ubuntu 20.04+ hoặc CentOS 7+
- PHP 8.2+
- MySQL 8.0+
- Nginx hoặc Apache
- Composer
- Node.js & NPM

### 10.2. Các bước triển khai

#### Bước 1: Chuẩn bị Server
```bash
# Cập nhật hệ thống
sudo apt update && sudo apt upgrade -y

# Cài đặt PHP 8.2 và extensions
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-zip php8.2-curl

# Cài đặt MySQL
sudo apt install mysql-server

# Cài đặt Nginx
sudo apt install nginx

# Cài đặt Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Cài đặt Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs
```

#### Bước 2: Upload Source Code
```bash
# Upload source code lên server
scp HeThongQuanLySinhVien_*.zip user@server:/var/www/

# SSH vào server
ssh user@server

# Giải nén
cd /var/www/
unzip HeThongQuanLySinhVien_*.zip
cd QuanLySinhVien
```

#### Bước 3: Cài đặt Dependencies
```bash
# Cài đặt PHP dependencies
composer install --no-dev --optimize-autoloader

# Cài đặt Node.js dependencies
npm install

# Build assets cho production
npm run build
```

#### Bước 4: Cấu hình
```bash
# Copy file .env
cp .env.example .env

# Sửa file .env
nano .env
```

Cập nhật:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=student_production
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password
```

#### Bước 5: Setup Database và Application
```bash
# Tạo application key
php artisan key:generate

# Chạy migrations
php artisan migrate --force

# Chạy seeders (nếu cần)
php artisan db:seed --force

# Tạo symbolic link
php artisan storage:link

# Optimize
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
sudo chown -R www-data:www-data /var/www/QuanLySinhVien
sudo chmod -R 755 /var/www/QuanLySinhVien
sudo chmod -R 775 /var/www/QuanLySinhVien/storage
sudo chmod -R 775 /var/www/QuanLySinhVien/bootstrap/cache
```

#### Bước 6: Cấu hình Nginx
```bash
sudo nano /etc/nginx/sites-available/quanlysinhvien
```

Nội dung:
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/QuanLySinhVien/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/quanlysinhvien /etc/nginx/sites-enabled/

# Test config
sudo nginx -t

# Restart Nginx
sudo systemctl restart nginx
```

#### Bước 7: Setup SSL (Khuyến nghị)
```bash
# Cài đặt Certbot
sudo apt install certbot python3-certbot-nginx

# Lấy SSL certificate
sudo certbot --nginx -d yourdomain.com
```

### 10.3. Bảo mật

**Các điểm cần lưu ý**:
- Đổi mật khẩu admin mặc định
- Sử dụng mật khẩu mạnh cho database
- Bật HTTPS
- Set `APP_DEBUG=false` trong production
- Backup database định kỳ
- Cập nhật Laravel và dependencies thường xuyên
- Giới hạn quyền truy cập file/folder

---

## 11. BACKUP VÀ RESTORE

### 11.1. Backup Database
```bash
# Backup database
mysqldump -u username -p student > backup_$(date +%Y%m%d_%H%M%S).sql

# Backup với nén
mysqldump -u username -p student | gzip > backup_$(date +%Y%m%d_%H%M%S).sql.gz
```

### 11.2. Restore Database
```bash
# Restore từ backup
mysql -u username -p student < backup_20251214_120000.sql

# Restore từ file nén
gunzip < backup_20251214_120000.sql.gz | mysql -u username -p student
```

### 11.3. Backup Files
```bash
# Backup toàn bộ source code
tar -czf backup_source_$(date +%Y%m%d_%H%M%S).tar.gz /var/www/QuanLySinhVien

# Backup chỉ storage và database
tar -czf backup_data_$(date +%Y%m%d_%H%M%S).tar.gz /var/www/QuanLySinhVien/storage
```

---

## 12. XỬ LÝ SỰ CỐ

### 12.1. Lỗi thường gặp

#### Lỗi: 500 Internal Server Error
**Nguyên nhân**: Lỗi PHP, thiếu quyền file
**Giải pháp**:
```bash
# Check logs
tail -f storage/logs/laravel.log

# Fix permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Clear cache
php artisan cache:clear
php artisan config:clear
```

#### Lỗi: Database Connection Failed
**Nguyên nhân**: Cấu hình database sai
**Giải pháp**:
- Kiểm tra thông tin DB trong file .env
- Kiểm tra MySQL service: `sudo systemctl status mysql`
- Test connection: `mysql -u username -p`

#### Lỗi: Class not found
**Nguyên nhân**: Autoload không được refresh
**Giải pháp**:
```bash
composer dump-autoload
php artisan optimize:clear
```

### 12.2. Liên hệ hỗ trợ
- **Email**: support@example.com
- **Phone**: [Số điện thoại]
- **Support hours**: 9:00 - 17:00 (Thứ 2 - Thứ 6)

---

## 13. GHI CHÚ THÊM

### 13.1. Tính năng có thể mở rộng
- Tích hợp hệ thống chấm công sinh viên
- Module quản lý học phí
- Hệ thống thông báo qua email/SMS
- Mobile app cho sinh viên
- API cho các hệ thống bên ngoài

### 13.2. Giới hạn hiện tại
- Chưa có module quản lý giáo viên
- Chưa hỗ trợ đa ngôn ngữ
- Chưa có API public

### 13.3. Đề xuất cải tiến
- Implement caching cho hiệu suất tốt hơn
- Thêm unit tests và integration tests
- Tối ưu hóa queries database
- Implement queue cho các tác vụ nặng

---

## 14. KÝ NHẬN BÀN GIAO

### Bên giao (Người phát triển)
**Họ tên**: ___________________________  
**Chữ ký**: ___________________________  
**Ngày**: ___________________________

### Bên nhận (Khách hàng/Quản lý)
**Họ tên**: ___________________________  
**Chữ ký**: ___________________________  
**Ngày**: ___________________________

---

**Ghi chú**: Tài liệu này là tài sản của dự án và cần được lưu trữ cẩn thận. Mọi thắc mắc vui lòng liên hệ bộ phận hỗ trợ.

---

*Tài liệu được tạo ngày December 14, 2025*  
*Phiên bản: 1.0.0*
