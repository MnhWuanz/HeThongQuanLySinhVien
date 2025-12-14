# 🔐 Hệ thống Phân quyền Role-Based Access Control (RBAC)

## 📋 Tổng quan

Hệ thống đã được cấu hình với 3 vai trò (roles) chính:

1. **Super_Admin** - Quản trị viên cao nhất
2. **Teacher** - Giảng viên
3. **Student** - Sinh viên

## 🎯 Phân quyền chi tiết

### 1️⃣ Super_Admin (Quản trị viên)

**Quyền truy cập:**

-   ✅ Đăng nhập Admin Panel (`/admin`)
-   ✅ Xem tất cả resources
-   ✅ Tạo/Sửa/Xóa tất cả dữ liệu
-   ✅ Quản lý Users
-   ✅ Xem Activity Logs
-   ✅ Tất cả widgets và báo cáo

**Resources có quyền:**

-   📚 Students (CRUD)
-   📊 Scores (CRUD)
-   📖 Subjects (CRUD)
-   🏫 Classes (CRUD)
-   🏛️ Departments (CRUD)
-   👥 Users (CRUD)
-   📝 Activity Logs (View only)

### 2️⃣ Teacher (Giảng viên)

**Quyền truy cập:**

-   ✅ Đăng nhập Admin Panel (`/admin`)
-   ✅ Xem lớp của mình (Global Scope)
-   ✅ Xem/Sửa điểm số sinh viên
-   ✅ Xem thông tin sinh viên
-   ❌ KHÔNG quản lý Users
-   ❌ KHÔNG tạo/xóa lớp, môn học, khoa
-   ❌ KHÔNG xem Activity Logs

**Resources có quyền:**

-   📚 Students (View only)
-   📊 Scores (View, Create, Edit) - Chỉ lớp của mình
-   📖 Subjects (View only)
-   🏫 Classes (View only) - Chỉ lớp của mình
-   🏛️ Departments (View only)
-   ❌ Users (Hidden)
-   ❌ Activity Logs (Hidden)

**Widgets hiển thị:**

-   TeacherStatsOverview
-   TeacherClassesTable

### 3️⃣ Student (Sinh viên)

**Quyền truy cập:**

-   ✅ Đăng nhập Student Panel (`/student`)
-   ✅ Xem thông tin cá nhân
-   ✅ Xem bảng điểm
-   ✅ Đổi mật khẩu
-   ❌ KHÔNG đăng nhập Admin Panel

**Pages có quyền:**

-   Dashboard (Trang chủ)
-   Profile (Hồ sơ cá nhân)
-   Scoreboard (Bảng điểm)

## 🔒 Middleware Security

### Admin Panel

File: `app/Http/Middleware/EnsureUserIsAdminOrTeacher.php`

```php
// Chỉ cho phép Super_Admin và Teacher
if (!$user->hasRole(['Super_Admin', 'Teacher'])) {
    // Đăng xuất và redirect về login
    auth()->logout();
    return redirect()->route('filament.admin.auth.login');
}
```

### Student Panel

File: `app/Http/Middleware/EnsureUserIsStudent.php`

```php
// Chỉ cho phép Student
if (!$user->hasRole('Student')) {
    // Đăng xuất và redirect về login
    auth()->logout();
    return redirect()->route('filament.student.auth.login');
}
```

## 🚀 Tài khoản mặc định

### Admin

-   **Email:** admin@sms.edu.vn
-   **Password:** password
-   **Role:** Super_Admin

### Teacher (ví dụ)

-   **Email:** nva@university.edu.vn
-   **Password:** password
-   **Role:** Teacher
-   **Lớp:** CNTT2021A

### Student (ví dụ)

-   **Email:** sv2021001@student.edu.vn
-   **Password:** password
-   **Role:** Student

## 📊 Navigation Groups

Resources được nhóm thành 3 nhóm:

### 1. Quản lý học vụ

-   Students
-   Scores
-   Subjects
-   Classes
-   Departments

### 2. Quản lý người dùng

-   Users (Chỉ Super_Admin)

### 3. Hệ thống

-   Activity Logs (Chỉ Super_Admin)

## 🎨 Permissions Matrix

| Resource          | Super_Admin | Teacher      | Student   |
| ----------------- | ----------- | ------------ | --------- |
| **Users**         | CRUD        | ❌ Hidden    | ❌ Hidden |
| **Students**      | CRUD        | View         | ❌        |
| **Scores**        | CRUD        | Create, Edit | ❌        |
| **Subjects**      | CRUD        | View         | ❌        |
| **Classes**       | CRUD        | View (own)   | ❌        |
| **Departments**   | CRUD        | View         | ❌        |
| **Activity Logs** | View        | ❌ Hidden    | ❌        |

## 🔧 Cách thêm permissions

### 1. Thêm permission check vào Resource:

```php
public static function canCreate(): bool
{
    return auth()->user()?->hasRole('Super_Admin') ?? false;
}

public static function canEdit($record): bool
{
    return auth()->user()?->hasRole(['Super_Admin', 'Teacher']) ?? false;
}

public static function canDelete($record): bool
{
    return auth()->user()?->hasRole('Super_Admin') ?? false;
}
```

### 2. Ẩn menu với shouldRegisterNavigation():

```php
public static function shouldRegisterNavigation(): bool
{
    return auth()->user()?->hasRole('Super_Admin') ?? false;
}
```

### 3. Hạn chế truy cập với canViewAny():

```php
public static function canViewAny(): bool
{
    return auth()->user()?->hasRole(['Super_Admin', 'Teacher']) ?? false;
}
```

## 🔍 Global Scope - Teacher chỉ thấy lớp của mình

File: `app/Models/ClassModel.php`

```php
protected static function booted()
{
    static::addGlobalScope('teacher', function (Builder $builder) {
        $user = Auth::user();
        if (Auth::check() && $user && $user->hasRole('Teacher')) {
            $teacher = Teacher::where('user_id', Auth::id())->first();
            if ($teacher) {
                $builder->where('teacher_id', $teacher->id);
            }
        }
    });
}
```

## ⚠️ Lưu ý bảo mật

1. **Luôn check role** trước khi cho phép thực hiện action
2. **Sử dụng middleware** để bảo vệ panel
3. **Global Scope** tự động lọc dữ liệu theo teacher
4. **Không hardcode** permissions trong view - dùng `can()` methods
5. **Log activity** cho các thay đổi quan trọng

## 🧪 Cách test

### Test Admin:

```bash
# Login với admin@sms.edu.vn / password
# Kiểm tra: Thấy tất cả menu, có thể CRUD tất cả
```

### Test Teacher:

```bash
# Login với nva@university.edu.vn / password
# Kiểm tra:
# - Không thấy menu Users, Activity Logs
# - Chỉ thấy lớp CNTT2021A (lớp của mình)
# - Có thể sửa điểm sinh viên
# - Không thể tạo/xóa lớp, môn học
```

### Test Student:

```bash
# Login vào /student với sv2021001@student.edu.vn / password
# Thử truy cập /admin -> Bị logout và redirect về /student/login
# Kiểm tra: Chỉ thấy Dashboard, Profile, Scoreboard
```

## 📝 Migration & Seeding

Tất cả roles đã được seed tự động trong:

-   `database/seeders/RoleSeeder.php` - Tạo 3 roles
-   `database/seeders/AdminSeeder.php` - Tạo Super_Admin
-   `database/seeders/TeacherSeeder.php` - Tạo Teacher với role
-   `database/seeders/EnhancedStudentSeeder.php` - Tạo Student với role

---

**Version:** 2.0  
**Ngày cập nhật:** {{ date('d/m/Y') }}  
**Branch:** BUG/V2.0_Role_Phan_Quyen
