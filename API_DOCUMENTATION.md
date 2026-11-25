# Mobile App API Documentation

## Base URL
```
http://127.0.0.1:8000/api/v1
```

**Production URL:** `https://yourdomain.com/api/v1`

---

## Authentication

All protected endpoints require Bearer token in headers:
```
Authorization: Bearer YOUR_TOKEN_HERE
```

---

## 📱 Authentication Endpoints

### 1. Register New User
**Endpoint:** `POST /auth/register`

**Headers:**
```json
{
  "Content-Type": "application/json",
  "Accept": "application/json"
}
```

**Request Body:**
```json
{
  "name": "Rahul Kumar",
  "email": "rahul@example.com",
  "phone": "9876543210",
  "city": "Delhi",
  "state": "Delhi"
}
```

**Success Response (201):**
```json
{
  "success": true,
  "message": "Registration successful!",
  "data": {
    "user": {
      "id": 1,
      "name": "Rahul Kumar",
      "email": "rahul@example.com",
      "phone": "9876543210",
      "city": "Delhi",
      "state": "Delhi"
    },
    "token": "1|abcdefghijklmnopqrstuvwxyz123456"
  }
}
```

**Error Response (422):**
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "email": ["The email has already been taken."],
    "phone": ["The phone has already been taken."]
  }
}
```

---

### 2. Send OTP
**Endpoint:** `POST /auth/send-otp`

**Request Body:**
```json
{
  "phone": "9876543210"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "OTP sent successfully to your mobile number",
  "data": {
    "otp": "1234",
    "phone": "9876543210",
    "expires_in": "10 minutes"
  }
}
```

**Note:** OTP is currently fixed at `1234` for testing. Remove `otp` field from response in production.

**Error Response (404):**
```json
{
  "success": false,
  "message": "Mobile number not registered"
}
```

---

### 3. Verify OTP & Login
**Endpoint:** `POST /auth/verify-otp`

**Request Body:**
```json
{
  "phone": "9876543210",
  "otp": "1234"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Login successful!",
  "data": {
    "user": {
      "id": 1,
      "name": "Rahul Kumar",
      "email": "rahul@example.com",
      "phone": "9876543210",
      "city": "Delhi",
      "state": "Delhi",
      "role": "student"
    },
    "token": "2|abcdefghijklmnopqrstuvwxyz123456"
  }
}
```

**Error Response (401):**
```json
{
  "success": false,
  "message": "Invalid OTP. Please try again."
}
```

---

## 👤 Profile Endpoints (Protected)

### 4. Get User Profile
**Endpoint:** `GET /auth/profile`

**Headers:**
```json
{
  "Authorization": "Bearer YOUR_TOKEN",
  "Accept": "application/json"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "Rahul Kumar",
      "email": "rahul@example.com",
      "phone": "9876543210",
      "city": "Delhi",
      "state": "Delhi",
      "role": "student",
      "is_active": true,
      "created_at": "2025-12-04 10:30:00"
    }
  }
}
```

---

### 5. Update Profile
**Endpoint:** `PUT /auth/profile`

**Headers:**
```json
{
  "Authorization": "Bearer YOUR_TOKEN",
  "Content-Type": "application/json",
  "Accept": "application/json"
}
```

**Request Body:** (All fields optional)
```json
{
  "name": "Rahul Kumar Singh",
  "email": "rahulnew@example.com",
  "city": "Mumbai",
  "state": "Maharashtra"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Profile updated successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "Rahul Kumar Singh",
      "email": "rahulnew@example.com",
      "phone": "9876543210",
      "city": "Mumbai",
      "state": "Maharashtra"
    }
  }
}
```

**Note:** Phone number cannot be updated.

---

### 6. Logout (Current Device)
**Endpoint:** `POST /auth/logout`

**Headers:**
```json
{
  "Authorization": "Bearer YOUR_TOKEN",
  "Accept": "application/json"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

---

### 7. Logout (All Devices)
**Endpoint:** `POST /auth/logout-all`

**Headers:**
```json
{
  "Authorization": "Bearer YOUR_TOKEN",
  "Accept": "application/json"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Logged out from all devices successfully"
}
```

---

## 📚 Course Endpoints

### 8. Get All Courses (Public)
**Endpoint:** `GET /courses`

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "courses": [
      {
        "id": 1,
        "title": "Complete Web Development",
        "slug": "complete-web-development",
        "description": "Learn HTML, CSS, JavaScript, React",
        "price": 999,
        "currency": "INR",
        "thumbnail": "http://127.0.0.1:8000/storage/courses/thumb.jpg",
        "is_published": true
      }
    ]
  }
}
```

---

### 9. Get Course Details (Public)
**Endpoint:** `GET /courses/{slug}`

**Example:** `GET /courses/complete-web-development`

---

### 10. Get My Purchased Courses (Protected)
**Endpoint:** `GET /my-courses`

**Headers:**
```json
{
  "Authorization": "Bearer YOUR_TOKEN"
}
```

---

### 11. Get Course Videos (Protected)
**Endpoint:** `GET /courses/{id}/videos`

**Headers:**
```json
{
  "Authorization": "Bearer YOUR_TOKEN"
}
```

---

## 🎥 Video Endpoints

### 12. Stream Video (Protected)
**Endpoint:** `GET /videos/{id}/stream`

**Headers:**
```json
{
  "Authorization": "Bearer YOUR_TOKEN"
}
```

---

## 📝 Test Endpoints

### 13. Get All Tests (Public)
**Endpoint:** `GET /tests`

---

### 14. Get Test Details (Public)
**Endpoint:** `GET /tests/{id}`

---

### 15. Start Test Attempt (Protected)
**Endpoint:** `POST /tests/{id}/start`

**Headers:**
```json
{
  "Authorization": "Bearer YOUR_TOKEN"
}
```

---

### 16. Save Answer (Protected)
**Endpoint:** `POST /attempts/{id}/answer`

**Request Body:**
```json
{
  "question_id": 1,
  "selected_option": "a"
}
```

---

### 17. Finish Test (Protected)
**Endpoint:** `POST /attempts/{id}/finish`

---

### 18. Get Test Report (Protected)
**Endpoint:** `GET /attempts/{id}/report`

---

### 19. Get My Test Attempts (Protected)
**Endpoint:** `GET /my-attempts`

**Headers:**
```json
{
  "Authorization": "Bearer YOUR_TOKEN"
}
```

---

## 💳 Purchase Endpoints

### 20. Create Purchase (Protected)
**Endpoint:** `POST /purchase`

**Request Body:**
```json
{
  "course_id": 1,
  "payment_method": "razorpay"
}
```

**Payment Methods:** `razorpay`, `paytm`, `phonepe`, `upi`, `card`

---

### 21. Verify Payment (Protected)
**Endpoint:** `POST /payment/verify`

**Request Body:**
```json
{
  "purchase_id": 1,
  "transaction_id": "pay_abc123xyz",
  "payment_status": "success"
}
```

---

### 22. Get My Purchases (Protected)
**Endpoint:** `GET /my-purchases`

**Headers:**
```json
{
  "Authorization": "Bearer YOUR_TOKEN"
}
```

---

### 23. Payment Webhook (Public)
**Endpoint:** `POST /payment/webhook/{provider}`

**Example:** `POST /payment/webhook/razorpay`

**Note:** For payment gateway callbacks only.

---

## 🔐 Error Codes

| Status Code | Description |
|-------------|-------------|
| 200 | Success |
| 201 | Created Successfully |
| 401 | Unauthorized (Invalid/Missing Token) |
| 403 | Forbidden (Account Inactive) |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Server Error |

---

## 📝 Important Notes

1. **Authentication:**
   - Store token securely in app after login/register
   - Send token in all protected API calls
   - Token format: `Bearer YOUR_TOKEN`

2. **OTP System:**
   - Current OTP is fixed: `1234`
   - Production mein SMS gateway integrate karenge
   - OTP expires in 10 minutes (future implementation)

3. **User Roles:**
   - All registered users have role: `student`
   - Admin panel access only for role: `admin`

4. **File Uploads:**
   - Images/videos returned as full URL
   - Example: `http://127.0.0.1:8000/storage/path/file.jpg`

5. **Response Format:**
   - All responses in JSON format
   - Success responses have `success: true`
   - Error responses have `success: false`

6. **Pagination:**
   - List endpoints may include pagination
   - Use `page` query parameter: `?page=2`

---

## 🧪 Testing with Postman/Thunder Client

### Test User Credentials:
```
Phone: 9876543210
OTP: 1234
```

### Sample Workflow:

1. **Register:**
   ```
   POST http://127.0.0.1:8000/api/v1/auth/register
   Body: {"name":"Test User","email":"test@test.com","phone":"9999999999","city":"Delhi","state":"Delhi"}
   ```

2. **Send OTP:**
   ```
   POST http://127.0.0.1:8000/api/v1/auth/send-otp
   Body: {"phone":"9999999999"}
   ```

3. **Verify OTP & Login:**
   ```
   POST http://127.0.0.1:8000/api/v1/auth/verify-otp
   Body: {"phone":"9999999999","otp":"1234"}
   ```

4. **Get Profile:** (Copy token from login response)
   ```
   GET http://127.0.0.1:8000/api/v1/auth/profile
   Headers: Authorization: Bearer YOUR_TOKEN
   ```

---

## 📞 Support

For any API issues or queries, contact:
- **Email:** support@yourapp.com
- **Phone:** +91-XXXXXXXXXX

---

**Last Updated:** December 4, 2025  
**API Version:** v1  
**Framework:** Laravel 11 + Sanctum
