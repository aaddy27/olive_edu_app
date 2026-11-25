# EduApp - Quick Start Guide

यह guide आपको 5 मिनट में EduApp को locally run करने में मदद करेगा।

---

## Prerequisites Check

सुनिश्चित करें कि आपके पास ये installed हैं:
- ✅ PHP >= 8.2
- ✅ Composer
- ✅ MySQL/MariaDB
- ✅ (Optional) Redis

---

## Quick Setup (5 मिनट)

### 1. Database बनाएं
```bash
# MySQL में login करें
mysql -u root -p

# Database create करें
CREATE DATABASE eduapp;
EXIT;
```

### 2. Environment Configure करें
```bash
cd d:\CIS - Projects\edu-app

# .env file बनाएं
copy .env.example .env

# App key generate करें
php artisan key:generate
```

### 3. .env में Database credentials update करें
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eduapp
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Migrations Run करें
```bash
php artisan migrate
```

### 5. Server Start करें
```bash
php artisan serve
```

**✨ Done! API running at:** `http://localhost:8000/api/v1`

---

## Test करें

### Postman/Insomnia में:

**1. Send OTP**
```http
POST http://localhost:8000/api/v1/auth/send-otp
Content-Type: application/json

{
  "phone": "9876543210"
}
```

**2. Verify OTP (response से OTP copy करें)**
```http
POST http://localhost:8000/api/v1/auth/verify-otp
Content-Type: application/json

{
  "phone": "9876543210",
  "otp": "123456",
  "name": "Test User"
}
```

Response में आपको `token` मिलेगा - इसे save करें!

**3. Get Courses (token के साथ)**
```http
GET http://localhost:8000/api/v1/courses
Authorization: Bearer YOUR_TOKEN_HERE
```

---

## Sample Data Create करें (Optional)

### Admin User बनाएं
```bash
php artisan tinker
```

```php
User::create([
    'name' => 'Admin',
    'email' => 'admin@eduapp.com',
    'phone' => '9999999999',
    'password' => Hash::make('password'),
    'role' => 'admin',
    'is_active' => true
]);
```

### Exam Board बनाएं
```php
$board = ExamBoard::create([
    'name' => 'CBSE',
    'slug' => 'cbse',
    'description' => 'Central Board of Secondary Education'
]);
```

### Exam बनाएं
```php
$exam = Exam::create([
    'exam_board_id' => $board->id,
    'name' => 'Class 12 Board Exam',
    'slug' => 'class-12-board',
    'description' => 'Class 12 Final Examination'
]);
```

### Subject बनाएं
```php
$subject = Subject::create([
    'exam_id' => $exam->id,
    'name' => 'Mathematics',
    'slug' => 'mathematics',
    'description' => 'Mathematics for Class 12'
]);
```

### Course बनाएं
```php
$course = Course::create([
    'subject_id' => $subject->id,
    'title' => 'Complete Mathematics',
    'slug' => 'complete-mathematics',
    'description' => 'Full course covering all topics',
    'price' => 999.00,
    'currency' => 'INR',
    'is_published' => true,
    'created_by' => 1
]);
```

---

## Common Issues & Solutions

### Issue: Migration fails
**Solution:**
```bash
# Database check करें
php artisan db:show

# Migrations reset करें
php artisan migrate:fresh
```

### Issue: "Class not found"
**Solution:**
```bash
composer dump-autoload
```

### Issue: Permission denied (storage)
**Solution:**
```bash
# Windows PowerShell
icacls storage /grant Everyone:F /t
icacls bootstrap\cache /grant Everyone:F /t

# Or simply:
chmod -R 777 storage bootstrap/cache
```

### Issue: Token not working
**Solution:**
- Headers में `Accept: application/json` add करें
- `Authorization: Bearer {token}` format check करें

---

## Next Steps

1. ✅ **API Documentation** पढ़ें: `API_DOCUMENTATION.md`
2. ✅ **Postman Collection** import करें
3. ✅ Sample data के साथ test करें
4. ✅ Mobile app integrate करें

---

## Useful Commands

```bash
# Cache clear करें
php artisan optimize:clear

# Routes देखें
php artisan route:list

# Tinker (REPL) open करें
php artisan tinker

# New migration बनाएं
php artisan make:migration create_table_name

# New controller बनाएं
php artisan make:controller ControllerName
```

---

## Help & Support

- 📖 **Full Documentation:** `README.md`
- 🔌 **API Docs:** `API_DOCUMENTATION.md`
- 🚀 **Deployment:** `DEPLOYMENT_GUIDE.md`

---

**Happy Coding! 🎉**
