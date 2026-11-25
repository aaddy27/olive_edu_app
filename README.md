# EduApp - शैक्षिक ऐप Backend (Laravel 11)

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-Proprietary-green.svg)]()

## 📚 प्रोजेक्ट विवरण

यह एक पूर्ण रूप से विकसित **शैक्षिक प्लेटफॉर्म** का backend है जो **Laravel 11** पर आधारित है। यह mobile apps (iOS/Android) के लिए secure REST API प्रदान करता है और एक powerful admin panel भी शामिल करता है।

### ✨ मुख्य फीचर्स

- 🔐 **Multi-method Authentication** - OTP, Email/Password, Sanctum API tokens
- 📖 **Hierarchical Course Structure** - Exam Board → Exam → Subject → Course
- 🎥 **Secure Video Streaming** - Vimeo integration with view limits
- 📝 **MCQ Test Series** - Auto-grading, detailed reports, negative marking
- 💳 **Payment Integration** - Razorpay with webhook support
- 👨‍💼 **Admin Panel** - Complete content and user management
- 📊 **Analytics** - User progress tracking and reporting

---

## 🛠️ Tech Stack

| Component | Technology |
|-----------|-----------|
| Framework | Laravel 11 |
| Database | MySQL/MariaDB |
| Authentication | Laravel Sanctum |
| Storage | AWS S3 / Vimeo |
| Payments | Razorpay |
| Queue | Redis + Horizon |
| Cache | Redis |
| Frontend | Blade + Tailwind CSS |

---

## 📦 Installation

### Prerequisites

- PHP >= 8.2
- Composer
- MySQL/MariaDB 
- Redis (optional but recommended)
- Node.js & NPM

### Quick Start

```bash
# 1. Install dependencies
composer install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Configure database in .env
DB_DATABASE=eduapp
DB_USERNAME=root
DB_PASSWORD=

# 4. Run migrations
php artisan migrate

# 5. Start server
php artisan serve
```

**API Base URL:** `http://localhost:8000/api/v1`

---

## 🗄️ Database Schema

### Core Tables Structure

```
exam_boards (CBSE, ICSE, etc.)
  ├── exams (JEE, NEET, Board Exams)
      ├── subjects (Physics, Chemistry, Math)
          ├── courses
          │   ├── videos
          │   └── tests
          ├── videos (standalone)
          └── subject_notes
```

### All Tables

- `users` - Students and admins
- `exam_boards` - Educational boards
- `exams` - Examination types
- `subjects` - Subject hierarchy
- `courses` - Course content
- `videos` - Video lectures (Vimeo/S3)
- `subject_notes` - PDF study materials
- `tests` - Test series
- `questions` - MCQ questions
- `test_attempts` - Student submissions
- `purchases` - Payment records
- `video_view_limits` - View tracking
- `settings` - Configuration
- `otps` - Phone verification

---

## 🔌 API Documentation

### Authentication Endpoints

#### Send OTP
```http
POST /api/v1/auth/send-otp
Content-Type: application/json

{
  "phone": "9876543210"
}

Response:
{
  "success": true,
  "message": "OTP sent successfully",
  "otp": "123456"  // Only in debug mode
}
```

#### Verify OTP & Login/Register
```http
POST /api/v1/auth/verify-otp
Content-Type: application/json

{
  "phone": "9876543210",
  "otp": "123456",
  "name": "John Doe"  // Optional, for new users
}

Response:
{
  "success": true,
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "John Doe",
    "phone": "9876543210",
    "role": "student"
  },
  "token": "1|abcdef..."
}
```

#### Email/Password Login
```http
POST /api/v1/auth/login

{
  "email": "user@example.com",
  "password": "password123"
}
```

#### Register New User
```http
POST /api/v1/auth/register

{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "9876543210",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### Logout
```http
POST /api/v1/auth/logout
Authorization: Bearer {token}
```

#### Get Current User
```http
GET /api/v1/auth/me
Authorization: Bearer {token}
```

---

### Course Endpoints

#### List All Courses
```http
GET /api/v1/courses?search=math&subject_id=1&free=true

Response:
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "Complete Mathematics",
        "slug": "complete-mathematics",
        "price": 999.00,
        "currency": "INR",
        "is_published": true,
        "thumbnail_path": "...",
        "subject": {...}
      }
    ],
    "per_page": 20,
    "total": 50
  }
}
```

#### Get Course Details
```http
GET /api/v1/courses/{slug}

Response:
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Complete Mathematics",
    "description": "...",
    "price": 999.00,
    "has_purchased": false,
    "videos": [...preview videos...],
    "subject": {...}
  }
}
```

#### Get Course Videos (Auth Required)
```http
GET /api/v1/courses/{id}/videos
Authorization: Bearer {token}

Response:
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Introduction to Algebra",
      "duration_seconds": 3600,
      "sort_order": 1,
      "is_preview": false,
      "status": "ready"
    }
  ]
}
```

#### My Purchased Courses
```http
GET /api/v1/my-courses
Authorization: Bearer {token}
```

---

### Video Endpoints

#### Get Streaming URL
```http
GET /api/v1/videos/{id}/stream
Authorization: Bearer {token}

Response:
{
  "success": true,
  "url": "https://player.vimeo.com/video/123456789",
  "type": "vimeo",
  "expires_at": "2025-12-03T20:00:00+00:00"
}
```

---

### Test Endpoints

#### List Tests
```http
GET /api/v1/tests
```

#### Get Test Details
```http
GET /api/v1/tests/{id}
```

#### Start Test
```http
POST /api/v1/tests/{id}/start
Authorization: Bearer {token}

Response:
{
  "success": true,
  "attempt": {
    "id": 1,
    "test_id": 1,
    "started_at": "2025-12-03T18:00:00",
    "status": "started"
  },
  "questions": [
    {
      "id": 1,
      "question_text": "What is 2+2?",
      "options": {
        "A": "3",
        "B": "4",
        "C": "5",
        "D": "6"
      },
      "marks": 1.0
    }
  ]
}
```

#### Save Answer
```http
POST /api/v1/attempts/{id}/answer
Authorization: Bearer {token}

{
  "question_id": 1,
  "answer": "B"
}
```

#### Finish Test
```http
POST /api/v1/attempts/{id}/finish
Authorization: Bearer {token}

Response:
{
  "success": true,
  "attempt_id": 1,
  "score": 85.5,
  "total_questions": 100,
  "correct_count": 90,
  "incorrect_count": 8,
  "unanswered_count": 2,
  "duration_minutes": 58,
  "detailed_results": [...]
}
```

#### Get Test Report
```http
GET /api/v1/attempts/{id}/report
Authorization: Bearer {token}
```

#### My Test Attempts
```http
GET /api/v1/my-attempts
Authorization: Bearer {token}
```

---

### Purchase Endpoints

#### Create Purchase
```http
POST /api/v1/purchase
Authorization: Bearer {token}

{
  "purchasable_type": "course",  // or "test"
  "purchasable_id": 1,
  "payment_method": "razorpay"
}

Response:
{
  "success": true,
  "purchase": {
    "id": 1,
    "order_id": "ORD-ABC123",
    "amount": 999.00,
    "currency": "INR",
    "payment_status": "pending"
  },
  "payment_data": {
    "razorpay_order_id": "order_xyz",
    "razorpay_key": "rzp_test_...",
    "amount": 99900,  // in paise
    "currency": "INR"
  }
}
```

#### Verify Payment
```http
POST /api/v1/payment/verify
Authorization: Bearer {token}

{
  "razorpay_order_id": "order_xyz",
  "razorpay_payment_id": "pay_abc",
  "razorpay_signature": "..."
}
```

#### My Purchases
```http
GET /api/v1/my-purchases
Authorization: Bearer {token}
```

#### Payment Webhook (No Auth)
```http
POST /api/v1/payment/webhook/{provider}
```

---

## 🔧 Configuration

### Environment Variables

```env
# App
APP_NAME=EduApp
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eduapp
DB_USERNAME=root
DB_PASSWORD=

# Razorpay
RAZORPAY_KEY=rzp_test_your_key
RAZORPAY_SECRET=your_secret

# Vimeo
VIMEO_ACCESS_TOKEN=your_vimeo_token

# AWS S3 (Optional)
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=ap-south-1
AWS_BUCKET=
```

Add to `config/services.php`:

```php
'razorpay' => [
    'key' => env('RAZORPAY_KEY'),
    'secret' => env('RAZORPAY_SECRET'),
],

'vimeo' => [
    'access_token' => env('VIMEO_ACCESS_TOKEN'),
],
```

---

## 🏗️ Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       ├── AuthController.php
│   │       ├── CourseController.php
│   │       ├── VideoController.php
│   │       ├── TestController.php
│   │       ├── PurchaseController.php
│   │       └── TestAttemptController.php
│   └── Requests/
│       └── Api/
│           ├── SendOtpRequest.php
│           ├── VerifyOtpRequest.php
│           ├── LoginRequest.php
│           ├── RegisterRequest.php
│           └── CreatePurchaseRequest.php
├── Models/
│   ├── User.php
│   ├── ExamBoard.php
│   ├── Exam.php
│   ├── Subject.php
│   ├── Course.php
│   ├── Video.php
│   ├── SubjectNote.php
│   ├── Test.php
│   ├── Question.php
│   ├── Purchase.php
│   ├── TestAttempt.php
│   ├── VideoViewLimit.php
│   ├── Setting.php
│   └── Otp.php
└── Services/
    ├── PaymentService.php
    ├── VideoService.php
    └── TestService.php
```

---

## 🔐 Security Features

✅ **Password Hashing** - Bcrypt encryption  
✅ **API Token Auth** - Laravel Sanctum  
✅ **Input Validation** - Form Requests  
✅ **SQL Injection Protection** - Eloquent ORM  
✅ **XSS Protection** - Blade templating  
✅ **CSRF Protection** - Token verification  
✅ **Rate Limiting** - API throttling  
✅ **Secure URLs** - Presigned video links  
✅ **CORS Configuration** - Proper headers  

---

## 🚀 Deployment

### Production Checklist

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure proper `APP_URL`
- [ ] Setup SSL (HTTPS)
- [ ] Run optimizations:
  ```bash
  php artisan optimize
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```
- [ ] Setup queue workers (Supervisor)
- [ ] Configure cron for scheduler
- [ ] Setup Redis for cache/sessions
- [ ] Configure CDN
- [ ] Setup monitoring (Sentry)
- [ ] Database backups

### Queue Workers

```bash
# Development
php artisan queue:work

# Production (Supervisor config)
[program:eduapp-worker]
command=php /path/to/artisan queue:work --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=4
```

### Scheduler Cron

```bash
* * * * * cd /path/to/eduapp && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/AuthTest.php

# With coverage
php artisan test --coverage
```

---

## 📝 Common Commands

```bash
# Clear all caches
php artisan optimize:clear

# Create migration
php artisan make:migration create_table_name

# Create model with migration
php artisan make:model ModelName -m

# Create controller
php artisan make:controller ControllerName

# Create seeder
php artisan make:seeder TableSeeder

# Run seeders
php artisan db:seed

# Tinker (REPL)
php artisan tinker
```

---

## 🎨 Admin Panel

```bash
# Install frontend dependencies
npm install

# Development
npm run dev

# Production build
npm run build
```

Access: `http://localhost:8000/admin`

---

## 📊 Models & Relationships

### User Model
- `hasMany`: purchases, testAttempts, videoViewLimits
- Methods: `isAdmin()`, `isStudent()`, `hasPurchased()`

### Course Model
- `belongsTo`: subject
- `hasMany`: videos, tests
- `morphMany`: purchases
- Scopes: `published()`, `free()`, `paid()`

### Video Model
- `belongsTo`: subject, course
- `hasMany`: viewLimits
- Methods: `canView()`, `hasViewsRemaining()`

### Test Model
- `belongsTo`: course, subject
- `hasMany`: questions, attempts
- `morphMany`: purchases

---

## 🤝 Contributing

This is a proprietary project. Contact the development team for contribution guidelines.

---

## 📄 License

Proprietary - All rights reserved

---

## 👥 Credits

**Developed by:** EduApp Team  
**Framework:** Laravel 11  
**Language:** PHP 8.2+

---

## 📞 Support

- **Email:** support@eduapp.com
- **Documentation:** https://docs.eduapp.com
- **Issues:** Contact development team

---

**Built with ❤️ using Laravel**
