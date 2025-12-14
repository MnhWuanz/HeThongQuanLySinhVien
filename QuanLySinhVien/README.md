# HỆ THỐNG QUẢN LÝ SINH VIÊN

Hệ thống quản lý sinh viên được xây dựng bằng Laravel Framework và Filament Admin Panel, giúp quản lý thông tin sinh viên, điểm số, lớp học, khoa và môn học một cách hiệu quả.

## 📋 Mục lục

- [Tính năng](#tính-năng)
- [Yêu cầu hệ thống](#yêu-cầu-hệ-thống)
- [Công nghệ sử dụng](#công-nghệ-sử-dụng)
- [Hướng dẫn cài đặt](#hướng-dẫn-cài-đặt)
- [Cấu hình](#cấu-hình)
- [Sử dụng](#sử-dụng)
- [Tài khoản mặc định](#tài-khoản-mặc-định)
- [Cấu trúc dự án](#cấu-trúc-dự-án)
- [Xử lý sự cố](#xử-lý-sự-cố)

## ✨ Tính năng

### Quản lý sinh viên
- Thêm, sửa, xóa thông tin sinh viên
- Tìm kiếm và lọc sinh viên theo nhiều tiêu chí
- Quản lý thông tin cá nhân: Mã sinh viên, họ tên, ngày sinh, giới tính, địa chỉ, email, số điện thoại
- Phân lớp và phân khoa cho sinh viên

### Quản lý điểm số
- Nhập và cập nhật điểm số cho từng môn học
- Tính toán điểm trung bình tự động
- Xuất danh sách điểm ra file Excel
- Xem lịch sử điểm của sinh viên

### Quản lý lớp học
- Quản lý thông tin lớp học
- Xem danh sách sinh viên trong lớp
- Thống kê số lượng sinh viên theo lớp

### Quản lý khoa
- Quản lý các khoa trong trường
- Phân loại lớp học theo khoa
- Thống kê số lượng sinh viên và lớp theo khoa

### Quản lý môn học
- Quản lý danh sách môn học
- Quản lý số tín chỉ cho mỗi môn
- Phân loại môn học theo khoa

### Xuất báo cáo
- Xuất danh sách sinh viên kèm điểm ra Excel
- Báo cáo thống kê theo nhiều tiêu chí
- Export dữ liệu định dạng phong phú

## 💻 Yêu cầu hệ thống

Để cài đặt và chạy hệ thống, bạn cần:

- **PHP**: >= 8.2
- **Composer**: >= 2.0
- **Node.js**: >= 18.x
- **NPM**: >= 9.x
- **MySQL**: >= 8.0 hoặc MariaDB >= 10.3
- **Web Server**: Apache hoặc Nginx
- **Extensions PHP cần thiết**:
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - Tokenizer
  - XML
  - Zip

## 🚀 Công nghệ sử dụng

- **Backend Framework**: Laravel 12.x
- **Admin Panel**: Filament 3.3
- **Database**: MySQL
- **Frontend**: Livewire, Alpine.js, Tailwind CSS
- **Excel Export**: Maatwebsite Excel 3.1

## 📦 Hướng dẫn cài đặt

### Bước 1: Clone hoặc giải nén source code

```bash
# Nếu sử dụng Git
git clone <repository-url>

# Hoặc giải nén file zip vào thư mục dự án
```

### Bước 2: Cài đặt dependencies

```bash
# Di chuyển vào thư mục dự án
cd QuanLySinhVien

# Cài đặt PHP dependencies
composer install

# Cài đặt Node.js dependencies
npm install
```

### Bước 3: Cấu hình môi trường

```bash
# Sao chép file môi trường mẫu
copy .env.example .env

# Hoặc trên Linux/Mac
cp .env.example .env

# Tạo application key
php artisan key:generate
```

### Bước 4: Cấu hình database

Mở file `.env` và cập nhật thông tin database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=student
DB_USERNAME=root
DB_PASSWORD=
```

### Bước 5: Tạo database

Tạo database trong MySQL:

```sql
CREATE DATABASE student CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Hoặc sử dụng command:

```bash
mysql -u root -p -e "CREATE DATABASE student CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### Bước 6: Chạy migrations và seeders

```bash
# Chạy migrations để tạo các bảng
php artisan migrate

# Chạy seeders để tạo dữ liệu mẫu
php artisan db:seed
```

### Bước 7: Build assets

```bash
# Build assets cho production
npm run build

# Hoặc chạy ở chế độ development
npm run dev
```

### Bước 8: Tạo symbolic link cho storage

```bash
php artisan storage:link
```

### Bước 9: Clear cache

```bash
php artisan optimize:clear
```

### Bước 10: Khởi động server

```bash
# Chạy development server
php artisan serve

# Server sẽ chạy tại: http://127.0.0.1:8000
```

## ⚙️ Cấu hình

### Cấu hình email (tùy chọn)

Nếu cần gửi email, cập nhật trong file `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Cấu hình timezone

Trong file `.env`:

```env
APP_TIMEZONE=Asia/Ho_Chi_Minh
```

### Cấu hình ngôn ngữ

```env
APP_LOCALE=vi
APP_FALLBACK_LOCALE=en
```

## 📖 Sử dụng

### Truy cập hệ thống

Sau khi khởi động server, truy cập:

- **Trang chủ**: http://127.0.0.1:8000
- **Admin Panel**: http://127.0.0.1:8000/admin

### Tài khoản mặc định

Sau khi chạy seeder, hệ thống tạo tài khoản admin mặc định:

```
Email: admin@example.com
Password: password
```

**⚠️ Lưu ý**: Đổi mật khẩu ngay sau khi đăng nhập lần đầu!

### Các chức năng chính

1. **Dashboard**: Xem tổng quan thống kê
2. **Quản lý sinh viên**: Thêm/sửa/xóa sinh viên
3. **Quản lý điểm**: Nhập và cập nhật điểm số
4. **Quản lý lớp học**: Quản lý các lớp học
5. **Quản lý khoa**: Quản lý các khoa
6. **Quản lý môn học**: Quản lý danh sách môn học
7. **Xuất Excel**: Xuất danh sách sinh viên kèm điểm

## 📁 Cấu trúc dự án

```
QuanLySinhVien/
├── app/
│   ├── Exports/              # Xử lý export Excel
│   ├── Filament/             # Filament resources và pages
│   ├── Http/Controllers/     # Controllers
│   ├── Models/               # Eloquent models
│   └── Providers/            # Service providers
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── public/                   # Public assets
├── resources/
│   ├── css/                  # CSS files
│   ├── js/                   # JavaScript files
│   └── views/                # Blade templates
├── routes/                   # Route definitions
├── storage/                  # Storage files
└── vendor/                   # Composer dependencies
```

## 🔧 Xử lý sự cố

### Lỗi: Class "Maatwebsite\Excel\ExcelServiceProvider" not found

```bash
composer install
composer dump-autoload
php artisan optimize:clear
```

### Lỗi: SQLSTATE[HY000] [1045] Access denied

- Kiểm tra lại thông tin database trong file `.env`
- Đảm bảo MySQL service đang chạy
- Kiểm tra username và password MySQL

### Lỗi: Permission denied

```bash
# Windows (chạy với quyền Administrator)
icacls storage /grant Users:F /T
icacls bootstrap/cache /grant Users:F /T

# Linux/Mac
chmod -R 775 storage bootstrap/cache
```

### Lỗi: npm install thất bại

```bash
# Xóa node_modules và cài lại
rm -rf node_modules
rm package-lock.json
npm cache clean --force
npm install
```

### Lỗi: Composer install thất bại

```bash
# Xóa vendor và cài lại
rm -rf vendor
rm composer.lock
composer clear-cache
composer install
```

### Reset lại toàn bộ database

```bash
php artisan migrate:fresh --seed
```

## 📞 Hỗ trợ

Nếu gặp vấn đề trong quá trình cài đặt hoặc sử dụng, vui lòng liên hệ:

- **Email**: support@example.com
- **Documentation**: [Laravel Documentation](https://laravel.com/docs)
- **Filament Documentation**: [Filament Documentation](https://filamentphp.com/docs)

## 📝 License

Dự án này được phát triển cho mục đích giáo dục và quản lý nội bộ.

---

**Phát triển bởi**: [Tên nhóm/cá nhân]  
**Ngày cập nhật**: December 2025  
**Phiên bản**: 1.0.0

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
