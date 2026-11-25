# Olive Learnings - Mobile App & Frontend API Documentation

Official REST API documentation for the **Olive Learnings** platform. Designed for mobile app (Flutter / React Native / Kotlin / Swift) and web frontend integration.

---

## 🌐 Environments & Base URLs

| Environment | Base URL |
| :--- | :--- |
| **Local Development** | `http://127.0.0.1:8000/api/v1` |
| **Production Server** | `https://eduapp.hkrdn.in/api/v1` |

---

## 🔐 Global Request Headers

All API requests must include these standard headers:

```http
Accept: application/json
Content-Type: application/json
```

For **protected endpoints**, include the Bearer authentication token received after login/registration:

```http
Authorization: Bearer <SANCTUM_TOKEN>
```

---

## 📑 Table of Contents
1. [Authentication & Account (MSG91 OTP)](#1-authentication--account-msg91-otp)
   - [Register - Step 1: Send OTP](#11-register---step-1-send-otp)
   - [Register - Step 2: Verify OTP & Complete Account](#12-register---step-2-verify-otp--complete-account)
   - [Password Login](#13-password-login)
   - [OTP Login - Step 1: Request Login OTP](#14-otp-login---step-1-request-login-otp)
   - [OTP Login - Step 2: Verify Login OTP](#15-otp-login---step-2-verify-login-otp)
   - [Forgot Password Flow (3 Steps)](#16-forgot-password-flow)
   - [User Profile Management](#17-user-profile-management)
2. [Courses & Curriculum](#2-courses--curriculum)
   - [Get All Courses (Catalog)](#21-get-all-courses-catalog)
   - [Get Course Detail by Slug](#22-get-course-detail-by-slug)
   - [Get Course Videos with Lock Status](#23-get-course-videos-with-lock-status)
   - [Get My Enrolled Courses](#24-get-my-enrolled-courses)
3. [Video Streaming & Playback](#3-video-streaming--playback)
   - [Stream Video (Access Check)](#31-stream-video-access-check)
4. [Test Series & Assessments Engine](#4-test-series--assessments-engine)
   - [Get All Test Series](#41-get-all-test-series)
   - [Get Test Series Detail](#42-get-test-series-detail)
   - [Get Subjects in a Test Series](#43-get-subjects-in-a-test-series)
   - [Get Child Tests for a Subject](#44-get-child-tests-for-a-subject)
   - [Get Questions for a Test](#45-get-questions-for-a-test)
   - [Start Test Attempt](#46-start-test-attempt)
   - [Save Answer (Real-Time)](#47-save-answer-real-time)
   - [Submit / Finish Test](#48-submit--finish-test)
   - [Get Detailed Scorecard / Report](#49-get-detailed-scorecard--report)
   - [Get My Test History](#410-get-my-test-history)
5. [Purchases & Checkout Flow](#5-purchases--checkout-flow)
   - [Initiate Purchase / Create Order](#51-initiate-purchase--create-order)
   - [Verify Payment Gateway Callback](#52-verify-payment-gateway-callback)
   - [Get My Purchase History](#53-get-my-purchase-history)
6. [Standard Error Responses](#6-standard-error-responses)

---

## 1. Authentication & Account (MSG91 OTP)

### 1.1 Register - Step 1: Send OTP
Validates the student's mobile number, temporarily caches registration details, and sends a 6-digit OTP via MSG91 SMS (DLT Sender `CIS015`).

- **Endpoint:** `POST /auth/register`
- **Auth Required:** No

#### Request Body
```json
{
  "name": "Aman Sharma",
  "phone": "9876543210",
  "password": "SecretPassword@123",
  "password_confirmation": "SecretPassword@123"
}
```

#### Success Response `200 OK`
```json
{
  "success": true,
  "message": "OTP sent successfully to your mobile number. Please verify to complete registration.",
  "data": {
    "phone": "9876543210",
    "expires_in": "10 minutes"
  }
}
```

---

### 1.2 Register - Step 2: Verify OTP & Complete Account
Verifies the 6-digit OTP received via SMS, creates the student record in database, and issues an active API token.

- **Endpoint:** `POST /auth/verify-registration-otp`
- **Auth Required:** No

#### Request Body
```json
{
  "phone": "9876543210",
  "otp": "458921"
}
```

#### Success Response `201 Created`
```json
{
  "success": true,
  "message": "Registration successful! You are now logged in.",
  "data": {
    "user": {
      "id": 15,
      "name": "Aman Sharma",
      "phone": "9876543210",
      "role": "student"
    },
    "token": "1|sanctum_plain_text_token_hash..."
  }
}
```

---

### 1.3 Password Login
Direct password login using registered mobile number and password.

- **Endpoint:** `POST /auth/login`
- **Auth Required:** No

#### Request Body
```json
{
  "phone": "9876543210",
  "password": "SecretPassword@123"
}
```

#### Success Response `200 OK`
```json
{
  "success": true,
  "message": "Login successful!",
  "data": {
    "user": {
      "id": 15,
      "name": "Aman Sharma",
      "phone": "9876543210",
      "email": "user9876543210@eduapp.com",
      "city": null,
      "state": null,
      "role": "student"
    },
    "token": "2|sanctum_plain_text_token_hash..."
  }
}
```

---

### 1.4 OTP Login - Step 1: Request Login OTP
Passwordless authentication: student enters phone number to receive a 6-digit login OTP via MSG91.

- **Endpoint:** `POST /auth/login-otp`
- **Auth Required:** No

#### Request Body
```json
{
  "phone": "9876543210"
}
```

#### Success Response `200 OK`
```json
{
  "success": true,
  "message": "Login OTP sent successfully to your mobile number.",
  "data": {
    "phone": "9876543210",
    "expires_in": "10 minutes"
  }
}
```

---

### 1.5 OTP Login - Step 2: Verify Login OTP
Submits the OTP to complete login and retrieve the auth token.

- **Endpoint:** `POST /auth/verify-login-otp`
- **Auth Required:** No

#### Request Body
```json
{
  "phone": "9876543210",
  "otp": "458921"
}
```

#### Success Response `200 OK`
```json
{
  "success": true,
  "message": "Login successful!",
  "data": {
    "user": {
      "id": 15,
      "name": "Aman Sharma",
      "phone": "9876543210",
      "role": "student"
    },
    "token": "3|sanctum_plain_text_token_hash..."
  }
}
```

---

### 1.6 Forgot Password Flow

1. **Send Reset OTP:** `POST /auth/forgot-password`
   - Body: `{"phone": "9876543210"}`
   - Returns: OTP sent to mobile.

2. **Verify Reset OTP:** `POST /auth/verify-otp`
   - Body: `{"phone": "9876543210", "otp": "458921"}`
   - Returns: `{"reset_token": "a1b2c3d4..."}`

3. **Set New Password:** `POST /auth/reset-password`
   - Body:
     ```json
     {
       "phone": "9876543210",
       "reset_token": "a1b2c3d4...",
       "password": "NewSecretPassword@123",
       "password_confirmation": "NewSecretPassword@123"
     }
     ```
   - Returns: Password reset confirmation.

---

### 1.7 User Profile Management

- **Get Profile:** `GET /auth/profile` *(Requires Bearer Token)*
- **Update Profile:** `PUT /auth/profile` *(Requires Bearer Token)*
  ```json
  {
    "name": "Aman Sharma",
    "email": "aman@gmail.com",
    "city": "Jaipur",
    "state": "Rajasthan"
  }
  ```
- **Change Password:** `POST /auth/change-password` *(Requires Bearer Token)*
  ```json
  {
    "current_password": "OldPassword123",
    "password": "NewPassword123",
    "password_confirmation": "NewPassword123"
  }
  ```
- **Logout:** `POST /auth/logout` *(Revokes current session token)*
- **Logout All Devices:** `POST /auth/logout-all` *(Revokes all tokens)*

---

## 2. Courses & Curriculum

### 2.1 Get All Courses (Catalog)
Retrieves all published courses, exam boards, attached subjects, video lecture counts, and thumbnail images.

- **Endpoint:** `GET /courses`
- **Auth Required:** Optional (if token provided, purchase status is included)
- **Query Filters:**
  - `?exam_id=1` (Filter by target exam)
  - `?subject_id=2` (Filter by subject)
  - `?search=Physics` (Search by title)

#### Success Response `200 OK`
```json
{
  "success": true,
  "message": "Courses retrieved successfully",
  "data": [
    {
      "id": 1,
      "title": "Complete Physics for JEE Main 2026",
      "slug": "complete-physics-jee-main-2026",
      "description": "Comprehensive masterclass covering Class 11 and 12 Physics.",
      "price": "1999.00",
      "currency": "INR",
      "is_published": true,
      "thumbnail_path": "https://images.unsplash.com/photo-1636466497217-26a8cbeaf0aa?...",
      "exam_board": {
        "id": 4,
        "name": "National Testing Agency & UPSC",
        "slug": "nta-upsc-competitive"
      },
      "subjects": [
        {
          "id": 1,
          "name": "Physics",
          "slug": "physics-jee-neet",
          "videos_count": 3
        }
      ]
    }
  ]
}
```

---

### 2.2 Get Course Detail by Slug
Fetches complete syllabus, subject breakdowns, notes, and curriculum outline for a course.

- **Endpoint:** `GET /courses/{slug}`
- **Example:** `GET /courses/complete-physics-jee-main-2026`
- **Auth Required:** Optional

---

### 2.3 Get Course Videos with Lock Status
Lists all video lectures for a course. Clearly indicates whether each video is a **Free Preview** (`is_preview: true`) or **Locked** (`can_play: false`, `requires_purchase: true`).

- **Endpoint:** `GET /courses/{id}/videos`
- **Example:** `GET /courses/1/videos`
- **Auth Required:** Optional (pass Bearer token to check student purchase status)

#### Success Response `200 OK`
```json
{
  "success": true,
  "message": "Videos retrieved successfully",
  "data": {
    "course_id": 1,
    "course_title": "Complete Physics for JEE Main 2026",
    "has_purchased": false,
    "total_videos": 3,
    "videos": [
      {
        "id": 1,
        "title": "01. Introduction to Vectors & Coordinate Systems",
        "description": "Fundamental concepts of vectors and scalar quantities.",
        "duration_seconds": 1800,
        "duration_formatted": "30 mins",
        "is_preview": true,
        "is_free": true,
        "can_play": true,
        "view_limit": 3
      },
      {
        "id": 2,
        "title": "02. Kinematics: Motion in One Dimension",
        "description": "Displacement, instantaneous velocity, and acceleration.",
        "duration_seconds": 2400,
        "duration_formatted": "40 mins",
        "is_preview": false,
        "is_free": false,
        "can_play": false,
        "requires_purchase": true
      }
    ]
  }
}
```

---

### 2.4 Get My Enrolled Courses
Lists all courses currently purchased by the logged-in student.

- **Endpoint:** `GET /my-courses`
- **Auth Required:** Yes (`Authorization: Bearer <TOKEN>`)

---

## 3. Video Streaming & Playback

### 3.1 Stream Video (Access Check)
Validates whether the user is eligible to stream the video (Free preview video or purchased course). Returns stream URL and playback metadata.

- **Endpoint:** `GET /videos/{id}/stream`
- **Example:** `GET /videos/1/stream`
- **Auth Required:** Yes

#### Success Response `200 OK`
```json
{
  "success": true,
  "message": "Video access granted",
  "data": {
    "video": {
      "id": 1,
      "title": "01. Introduction to Vectors & Coordinate Systems",
      "video_url": "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4",
      "duration_seconds": 1800,
      "is_free": true,
      "can_play": true
    }
  }
}
```

#### Denied Response `403 Forbidden` (If not purchased)
```json
{
  "success": false,
  "message": "You need to purchase this course to watch this video",
  "data": {
    "course_id": 1,
    "course_title": "Complete Physics for JEE Main 2026",
    "requires_purchase": true
  }
}
```

---

## 4. Test Series & Assessments Engine

### 4.1 Get All Test Series
Retrieves the catalog of all parent test series packages with pricing, duration, and question quotas.

- **Endpoint:** `GET /test-series`
- **Auth Required:** Optional (if token sent, returns `is_purchased` and `is_accessible`)
- **Query Filters:** `?course_id=1`

#### Success Response `200 OK`
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 3,
        "title": "All India JEE Main 2026 Grand Test Series",
        "description": "5 Complete simulated mock exams on exact NTA CBT interface.",
        "duration_minutes": 180,
        "total_marks": 300,
        "price": "799.00",
        "is_free": false,
        "is_test_series": true,
        "is_purchased": false,
        "is_accessible": false,
        "subjects": [
          { "id": 1, "name": "Physics" },
          { "id": 2, "name": "Chemistry" },
          { "id": 3, "name": "Mathematics" }
        ]
      }
    ]
  }
}
```

---

### 4.2 Get Test Series Detail
Shows complete information for a specific test series along with attached subjects and question distributions.

- **Endpoint:** `GET /test-series/{id}`
- **Example:** `GET /test-series/3`
- **Auth Required:** Optional

---

### 4.3 Get Subjects in a Test Series
Returns the subjects included in this test series along with the count of mock papers available under each subject.

- **Endpoint:** `GET /test-series/{testId}/subjects`
- **Example:** `GET /test-series/3/subjects`
- **Auth Required:** Optional

#### Success Response `200 OK`
```json
{
  "success": true,
  "data": {
    "test_series_id": 3,
    "title": "All India JEE Main 2026 Grand Test Series",
    "is_accessible": true,
    "subjects": [
      {
        "id": 1,
        "name": "Physics",
        "tests_count": 1,
        "question_count": 5
      },
      {
        "id": 2,
        "name": "Chemistry",
        "tests_count": 1,
        "question_count": 5
      }
    ]
  }
}
```

---

### 4.4 Get Child Tests for a Subject
Retrieves the actual test papers available under a subject within the test series.

- **Endpoint:** `GET /test-series/{testId}/subjects/{subjectId}/tests`
- **Example:** `GET /test-series/3/subjects/1/tests`
- **Auth Required:** Optional

#### Success Response `200 OK`
```json
{
  "success": true,
  "data": {
    "parent_test": {
      "id": 3,
      "title": "All India JEE Main 2026 Grand Test Series"
    },
    "subject": {
      "id": 1,
      "name": "Physics"
    },
    "is_accessible": true,
    "tests": [
      {
        "id": 4,
        "title": "Mock Paper 01: Physics Mechanics & Thermodynamics",
        "test_number": 1,
        "duration": 60,
        "duration_minutes": 60,
        "total_marks": 20,
        "negative_marking": "1.00",
        "questions_count": 2
      }
    ]
  }
}
```

---

### 4.5 Get Questions for a Test
Retrieves the questions (without correct keys) for an authorized student to take the test.

- **Endpoint:** `GET /tests/{testId}/questions`
- **Example:** `GET /tests/4/questions`
- **Auth Required:** Yes (`Authorization: Bearer <TOKEN>`)

#### Success Response `200 OK`
```json
{
  "success": true,
  "data": {
    "test": {
      "id": 4,
      "title": "Mock Paper 01: Physics Mechanics & Thermodynamics",
      "test_number": 1,
      "duration": 60,
      "total_marks": 20,
      "negative_marking": "1.00",
      "instructions": "Time allowed: 60 minutes. Marks: +4 for correct, -1 for incorrect."
    },
    "questions": [
      {
        "id": 11,
        "question_text": "The moment of inertia of a uniform solid sphere of mass M and radius R about its diametrical axis is:",
        "question_image_url": null,
        "options": {
          "a": "(1/2) M R²",
          "b": "(2/5) M R²",
          "c": "(2/3) M R²",
          "d": "(7/5) M R²"
        },
        "marks": "4.00",
        "negative_marks": "1.00"
      }
    ],
    "total_questions": 1
  }
}
```

---

### 4.6 Start Test Attempt
Initializes a new attempt record and returns the question set.

- **Endpoint:** `POST /tests/{id}/start`
- **Example:** `POST /tests/4/start`
- **Auth Required:** Yes

#### Success Response `201 Created`
```json
{
  "success": true,
  "message": "Test started successfully",
  "data": {
    "attempt": {
      "id": 5,
      "user_id": 15,
      "test_id": 4,
      "started_at": "2026-11-25T10:45:00.000000Z",
      "status": "started"
    },
    "questions": [ ... ]
  }
}
```

---

### 4.7 Save Answer (Real-Time)
Saves the student's selected option for a question as they navigate through the test.

- **Endpoint:** `POST /attempts/{id}/answer`
- **Example:** `POST /attempts/5/answer`
- **Auth Required:** Yes

#### Request Body
```json
{
  "question_id": 11,
  "answer": "b"
}
```

#### Success Response `200 OK`
```json
{
  "success": true,
  "message": "Answer saved successfully",
  "data": {
    "attempt_id": 5,
    "answers": {
      "11": "b"
    }
  }
}
```

---

### 4.8 Submit / Finish Test
Finalizes the attempt, auto-evaluates against answer keys, deducts negative marks, and saves the final score.

- **Endpoint:** `POST /attempts/{id}/finish`
- **Example:** `POST /attempts/5/finish`
- **Auth Required:** Yes

#### Success Response `200 OK`
```json
{
  "success": true,
  "message": "Test submitted successfully",
  "data": {
    "attempt_id": 5,
    "score": 4,
    "total_questions": 1,
    "correct_count": 1,
    "incorrect_count": 0,
    "unanswered_count": 0,
    "duration_minutes": 12,
    "detailed_results": [
      {
        "question_id": 11,
        "question_text": "The moment of inertia of a uniform solid sphere...",
        "user_answer": "b",
        "correct_answer": "b",
        "is_correct": true,
        "marks_obtained": 4,
        "explanation": "For a solid sphere about a central axis through its diameter, I = (2/5) M R²."
      }
    ]
  }
}
```

---

### 4.9 Get Detailed Scorecard / Report
Fetches the full test analysis, question explanations, and marks summary for any completed attempt.

- **Endpoint:** `GET /attempts/{id}/report`
- **Example:** `GET /attempts/5/report`
- **Auth Required:** Yes

---

### 4.10 Get My Test History
Retrieves past attempts, test titles, scores, and dates for the authenticated student.

- **Endpoint:** `GET /my-attempts`
- **Auth Required:** Yes

---

## 5. Purchases & Checkout Flow

### 5.1 Initiate Purchase / Create Order
Initializes a payment intent for a course or a test series. If the price is ₹0.00, instant free access is granted immediately.

- **Endpoint:** `POST /purchase`
- **Auth Required:** Yes

#### Request Body
```json
{
  "purchasable_type": "course",
  "purchasable_id": 1,
  "payment_provider": "razorpay"
}
```
*(For test series, pass `"purchasable_type": "test"`)*

#### Success Response `201 Created`
```json
{
  "success": true,
  "message": "Purchase initiated successfully",
  "data": {
    "order_id": "ORD-RZP-984120",
    "amount": "1999.00",
    "currency": "INR",
    "item_title": "Complete Physics for JEE Main 2026",
    "payment_provider": "razorpay",
    "status": "pending"
  }
}
```

---

### 5.2 Verify Payment Gateway Callback
Once the mobile payment SDK (Razorpay / Cashfree / Stripe) finishes on the device, send the returned IDs to verify and activate student access.

- **Endpoint:** `POST /payment/verify`
- **Auth Required:** Yes

#### Request Body
```json
{
  "order_id": "ORD-RZP-984120",
  "payment_id": "pay_Nz9Y7hK8L2vB01",
  "signature": "optional_gateway_signature_hash"
}
```

#### Success Response `200 OK`
```json
{
  "success": true,
  "message": "Payment verified successfully! Access granted.",
  "data": {
    "order_id": "ORD-RZP-984120",
    "payment_id": "pay_Nz9Y7hK8L2vB01",
    "payment_status": "completed",
    "amount": "1999.00",
    "currency": "INR"
  }
}
```

---

### 5.3 Get My Purchase History
Lists all transaction receipts, statuses (`completed`, `pending`, `refunded`), and invoices.

- **Endpoint:** `GET /my-purchases`
- **Auth Required:** Yes

---

## 6. Standard Error Responses

### Validation Error `422 Unprocessable Entity`
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "phone": [
      "Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9"
    ]
  }
}
```

### Unauthorized `401 Unauthorized`
```json
{
  "message": "Unauthenticated."
}
```

### Access Denied `403 Forbidden`
```json
{
  "success": false,
  "message": "Please purchase this test series to access the questions",
  "requires_purchase": true
}
```

### Resource Not Found `404 Not Found`
```json
{
  "success": false,
  "message": "Test series not found"
}
```

---

## 📱 Mobile App Integration Checklist

1. **Base URL Setup:** Store `https://eduapp.hkrdn.in/api/v1` in your app's environment configuration.
2. **Auth Interceptor:** Automatically attach `Authorization: Bearer <TOKEN>` to all requests once logged in.
3. **OTP Handling:** Use `POST /auth/register` and `POST /auth/verify-registration-otp` for sign-ups.
4. **Test Taking Flow:**
   - Call `GET /test-series` to show test series cards.
   - On tap, call `GET /test-series/{id}/subjects` and `GET /test-series/{testId}/subjects/{subjectId}/tests`.
   - On starting test, call `POST /tests/{id}/start` to get questions and attempt ID.
   - On selecting options, call `POST /attempts/{id}/answer` in background.
   - On timer expiry or submit button, call `POST /attempts/{id}/finish` to show score.
5. **Checkout Flow:**
   - Call `POST /purchase` to get `order_id` and amount.
   - Open native payment gateway checkout sheet with `order_id`.
   - On success callback, call `POST /payment/verify` to unlock course/test instantly.
