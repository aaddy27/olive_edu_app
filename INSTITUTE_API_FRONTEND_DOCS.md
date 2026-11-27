# Institute & Teacher Management System — Complete Frontend Developer Guide & API Documentation

> **Version**: 2.0 (Production Ready)  
> **Target Audience**: Frontend Developers (Flutter / React Native / React.js / Vue / Android / iOS)  
> **Auth Type**: Bearer Token (Laravel Sanctum)  
> **Base URL**: `http://https://eduapp.hkrdn.in/api/v1`

---

## 1. System Architecture & Workflows

### Role Types
1. **Institute Admin (`institute_admin`)**:
   - Created from the Web Portal by the Super Admin.
   - Logs in via the Mobile App / Web Frontend using Email or Mobile Number + Password.
   - Manages institute staff/teachers (Add, View, Update, Toggle Active/Inactive, Delete).
   - Views institute profile, address, logo, and active teacher count.
   - **Restriction**: Institute Admins **cannot** log into the Main Web Admin Portal.

2. **Institute Teacher (`teacher`)**:
   - Added by their Institute Admin via the app.
   - Logs in via Email or Mobile Number + Password.
   - Gets **100% Free Access** to all Courses, Subjects, Playlists, and Video Lessons uploaded by the Super Admin.
   - **No payment, no purchase, and no view restrictions**.

### Status & Validity Rules
- **Active / Inactive Cascade**: If the Super Admin sets an institute to **Inactive**, the Institute Admin and all teachers of that institute are automatically deactivated and their active tokens are revoked.
- **Validity / Expiry**: If an institute has an expiry date that has passed (and is not set to Lifetime), logins and requests return `403 Forbidden` with a subscription expired message.

---

## 2. Global Headers & Responses

### Request Headers
Every request must include:
```http
Accept: application/json
Content-Type: application/json
```

For protected routes, include:
```http
Authorization: Bearer <ACCESS_TOKEN>
```

### Standard Response Format
#### A. Success Response (`200 OK` or `201 Created`)
```json
{
  "success": true,
  "message": "Action completed successfully", // optional
  "data": { ... }
}
```

#### B. Validation Error (`422 Unprocessable Entity`)
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "email": ["The email has already been taken."],
    "password": ["The password field must be at least 6 characters."]
  }
}
```

#### C. Authentication / Permission Error (`401` or `403`)
```json
{
  "success": false,
  "message": "Your institute subscription/validity has expired. Please contact administration."
}
```

---

## 3. Module 1: Institute Admin API Reference

Base Prefix: `/api/v1/institute`

---

### 3.1. Institute Admin Login
- **Endpoint**: `POST /api/v1/institute/login`
- **Access**: Public
- **Description**: Authenticates the Institute Administrator and returns the Bearer token and Institute profile.

#### Request Body
```json
{
  "login": "admin@apexacademy.com",
  "password": "password123"
}
```
> **Tip for Frontend**: The `login` field accepts either the admin's **Email** OR **Mobile Number**.

#### Success Response (`200 OK`)
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 14,
      "name": "Dr. Rajesh Sharma",
      "email": "admin@apexacademy.com",
      "phone": "9876543210",
      "role": "institute_admin",
      "designation": null
    },
    "institute": {
      "id": 1,
      "name": "Apex Educational Academy",
      "code": "INST-A7B2X9",
      "email": "contact@apexacademy.com",
      "phone": "022-28491234",
      "city": "Mumbai",
      "state": "Maharashtra",
      "logo_url": "http://yourdomain.com/storage/institutes/logos/sample.png"
    },
    "token": "10|ec8CZsa7BBg73xK9...",
    "token_type": "Bearer"
  }
}
```

#### Error Scenarios
- `401 Unauthorized`: Invalid email/password.
- `403 Forbidden`: Admin account inactive OR Institute inactive OR Institute subscription expired.

---

### 3.2. Get Institute Admin Profile & Stats
- **Endpoint**: `GET /api/v1/institute/profile`
- **Access**: Protected (`Bearer <token>`)
- **Description**: Returns detailed info about the Institute, the logged-in admin, and staff counts.

#### Success Response (`200 OK`)
```json
{
  "success": true,
  "data": {
    "admin": {
      "id": 14,
      "name": "Dr. Rajesh Sharma",
      "email": "admin@apexacademy.com",
      "phone": "9876543210",
      "role": "institute_admin",
      "designation": null,
      "created_at": "2026-11-27T11:00:00.000000Z"
    },
    "institute": {
      "id": 1,
      "name": "Apex Educational Academy",
      "code": "INST-A7B2X9",
      "email": "contact@apexacademy.com",
      "phone": "022-28491234",
      "address": "Plot 42, Sector 17, Vashi",
      "city": "Mumbai",
      "state": "Maharashtra",
      "pincode": "400703",
      "logo_url": "http://yourdomain.com/storage/institutes/logos/sample.png",
      "is_active": true
    },
    "stats": {
      "total_teachers": 12,
      "active_teachers": 11
    }
  }
}
```

---

### 3.3. Update Institute Admin Profile
- **Endpoint**: `PUT /api/v1/institute/profile`
- **Access**: Protected (`Bearer <token>`)

#### Request Body
```json
{
  "name": "Dr. Rajesh V. Sharma",
  "phone": "9876543219",
  "password": "newpassword123" 
}
```
*(All fields are optional. Only send fields you wish to change).*

#### Success Response (`200 OK`)
```json
{
  "success": true,
  "message": "Profile updated successfully",
  "data": {
    "id": 14,
    "name": "Dr. Rajesh V. Sharma",
    "email": "admin@apexacademy.com",
    "phone": "9876543219"
  }
}
```

---

### 3.4. Institute Admin Logout
- **Endpoint**: `POST /api/v1/institute/logout`
- **Access**: Protected (`Bearer <token>`)

#### Success Response (`200 OK`)
```json
{
  "success": true,
  "message": "Successfully logged out"
}
```

---

### 3.5. List Teachers / Staff
- **Endpoint**: `GET /api/v1/institute/teachers`
- **Access**: Protected (`Bearer <token>`)
- **Description**: Returns all teachers belonging to this institute with pagination, search, and status filtering.

#### Query Parameters
| Parameter | Type | Required | Description | Example |
|---|---|---|---|---|
| `search` | String | No | Search by name, email, phone, employee ID, or designation | `?search=verma` |
| `status` | String | No | Filter by active status (`active` or `inactive`) | `?status=active` |
| `page` | Integer | No | Page number (default: `1`) | `?page=1` |
| `per_page` | Integer | No | Number of records per page (default: `15`) | `?per_page=20` |

#### Success Response (`200 OK`)
```json
{
  "success": true,
  "data": {
    "teachers": [
      {
        "id": 18,
        "institute_id": 1,
        "name": "Amit Kumar Verma",
        "email": "amit.verma@apexacademy.com",
        "phone": "9820011223",
        "role": "teacher",
        "designation": "Senior Physics Faculty",
        "employee_id": "TCH-001",
        "is_active": true,
        "created_at": "2026-11-27T11:15:00.000000Z",
        "updated_at": "2026-11-27T11:15:00.000000Z"
      }
    ],
    "pagination": {
      "current_page": 1,
      "last_page": 1,
      "per_page": 15,
      "total": 1
    }
  }
}
```

---

### 3.6. Add New Teacher / Staff
- **Endpoint**: `POST /api/v1/institute/teachers`
- **Access**: Protected (`Bearer <token>`)
- **Description**: Adds a new teacher under this institute.

#### Request Body
```json
{
  "name": "Sunita Rao",
  "email": "sunita.rao@apexacademy.com",
  "phone": "9819988776",
  "password": "teacherpass123",
  "designation": "Mathematics Head",
  "employee_id": "TCH-002"
}
```

#### Field Specifications & Validation
| Field | Type | Required | Validation Rules | Description |
|---|---|---|---|---|
| `name` | String | **Yes** | Max 255 chars | Full name of teacher |
| `email` | String | **Yes** | Valid email, unique across system | Login email |
| `phone` | String | No | Max 15 chars, unique if provided | Mobile number |
| `password` | String | **Yes** | Min 6 chars | Login password |
| `designation` | String | No | Max 100 chars | Subject / role (e.g. Physics Lead) |
| `employee_id` | String | No | Max 50 chars | Institute Staff code |

#### Success Response (`201 Created`)
```json
{
  "success": true,
  "message": "Teacher added successfully to your institute.",
  "data": {
    "id": 19,
    "name": "Sunita Rao",
    "email": "sunita.rao@apexacademy.com",
    "phone": "9819988776",
    "role": "teacher",
    "designation": "Mathematics Head",
    "employee_id": "TCH-002",
    "is_active": true,
    "created_at": "2026-11-27T11:30:00.000000Z"
  }
}
```

---

### 3.7. View Single Teacher Details
- **Endpoint**: `GET /api/v1/institute/teachers/{id}`
- **Access**: Protected (`Bearer <token>`)

#### Success Response (`200 OK`)
```json
{
  "success": true,
  "data": {
    "id": 19,
    "name": "Sunita Rao",
    "email": "sunita.rao@apexacademy.com",
    "phone": "9819988776",
    "role": "teacher",
    "designation": "Mathematics Head",
    "employee_id": "TCH-002",
    "is_active": true,
    "created_at": "2026-11-27T11:30:00.000000Z"
  }
}
```

---

### 3.8. Update Teacher
- **Endpoint**: `PUT /api/v1/institute/teachers/{id}`
- **Access**: Protected (`Bearer <token>`)

#### Request Body
```json
{
  "name": "Sunita K. Rao",
  "phone": "9819988770",
  "designation": "Lead Mathematics Faculty",
  "employee_id": "TCH-002",
  "password": "newpassword123",
  "is_active": true
}
```
*(All fields are optional. Only send what you want to update).*

#### Success Response (`200 OK`)
```json
{
  "success": true,
  "message": "Teacher updated successfully.",
  "data": {
    "id": 19,
    "name": "Sunita K. Rao",
    "email": "sunita.rao@apexacademy.com",
    "phone": "9819988770",
    "role": "teacher",
    "designation": "Lead Mathematics Faculty",
    "employee_id": "TCH-002",
    "is_active": true,
    "updated_at": "2026-11-27T11:35:00.000000Z"
  }
}
```

---

### 3.9. Quick Toggle Teacher Status (Active/Inactive)
- **Endpoint**: `POST /api/v1/institute/teachers/{id}/toggle-status`
- **Access**: Protected (`Bearer <token>`)
- **Description**: 1-click active/inactive toggle. Inactive teachers cannot log in.

#### Success Response (`200 OK`)
```json
{
  "success": true,
  "message": "Teacher status toggled to inactive.",
  "data": {
    "id": 19,
    "name": "Sunita K. Rao",
    "is_active": false
  }
}
```

---

### 3.10. Delete Teacher
- **Endpoint**: `DELETE /api/v1/institute/teachers/{id}`
- **Access**: Protected (`Bearer <token>`)
- **Description**: Removes the teacher from the institute and wipes their active app sessions.

#### Success Response (`200 OK`)
```json
{
  "success": true,
  "message": "Teacher removed successfully."
}
```

---

## 4. Module 2: Teacher App API Reference (Free Content Access)

Base Prefix: `/api/v1/teacher`

---

### 4.1. Teacher Login
- **Endpoint**: `POST /api/v1/teacher/login`
- **Access**: Public

#### Request Body
```json
{
  "login": "sunita.rao@apexacademy.com",
  "password": "teacherpass123"
}
```
*(Accepts Email or Mobile Number)*

#### Success Response (`200 OK`)
```json
{
  "success": true,
  "message": "Teacher login successful",
  "data": {
    "teacher": {
      "id": 19,
      "name": "Sunita Rao",
      "email": "sunita.rao@apexacademy.com",
      "phone": "9819988776",
      "role": "teacher",
      "designation": "Mathematics Head",
      "employee_id": "TCH-002"
    },
    "institute": {
      "id": 1,
      "name": "Apex Educational Academy",
      "code": "INST-A7B2X9",
      "email": "contact@apexacademy.com",
      "phone": "022-28491234",
      "city": "Mumbai",
      "state": "Maharashtra",
      "logo_url": null
    },
    "token": "11|K8smd192jdj2k1...",
    "token_type": "Bearer"
  }
}
```

---

### 4.2. Teacher Profile
- **Endpoint**: `GET /api/v1/teacher/profile`
- **Access**: Protected (`Bearer <token>`)

#### Success Response (`200 OK`)
```json
{
  "success": true,
  "data": {
    "teacher": {
      "id": 19,
      "name": "Sunita Rao",
      "email": "sunita.rao@apexacademy.com",
      "phone": "9819988776",
      "role": "teacher",
      "designation": "Mathematics Head",
      "employee_id": "TCH-002",
      "city": null,
      "state": null,
      "created_at": "2026-11-27T11:30:00.000000Z"
    },
    "institute": {
      "id": 1,
      "name": "Apex Educational Academy",
      "code": "INST-A7B2X9",
      "email": "contact@apexacademy.com",
      "phone": "022-28491234",
      "address": "Plot 42, Sector 17, Vashi",
      "city": "Mumbai",
      "state": "Maharashtra",
      "logo_url": null
    }
  }
}
```

---

### 4.3. Update Teacher Profile
- **Endpoint**: `PUT /api/v1/teacher/profile`
- **Access**: Protected (`Bearer <token>`)

#### Request Body
```json
{
  "name": "Sunita K. Rao",
  "phone": "9819988776",
  "password": "mynewpassword123"
}
```

---

### 4.4. Teacher Logout
- **Endpoint**: `POST /api/v1/teacher/logout`
- **Access**: Protected (`Bearer <token>`)

---

### 4.5. Browse Courses (All 100% Free for Teachers)
- **Endpoint**: `GET /api/v1/teacher/courses`
- **Access**: Protected (`Bearer <token>`)
- **Description**: Returns all courses published by the portal. Notice: `"is_free": true` is guaranteed for all institute teachers.

#### Query Parameters
- `search`: Search course by title (optional)
- `exam_board_id`: Filter by exam board ID (optional)

#### Success Response (`200 OK`)
```json
{
  "success": true,
  "message": "Courses retrieved successfully",
  "data": [
    {
      "id": 1,
      "title": "Complete Class 10 Science & Mathematics",
      "slug": "complete-class-10-science-mathematics",
      "description": "Full academic syllabus coverage...",
      "thumbnail_url": "http://yourdomain.com/storage/courses/thumbnails/sample.jpg",
      "is_free": true,
      "exam_board": {
        "id": 1,
        "name": "CBSE"
      },
      "subjects_count": 2,
      "playlists_count": 4,
      "videos_count": 25,
      "subjects": [
        {
          "id": 1,
          "name": "Physics",
          "slug": "physics",
          "videos_count": 12
        },
        {
          "id": 2,
          "name": "Mathematics",
          "slug": "mathematics",
          "videos_count": 13
        }
      ]
    }
  ]
}
```

---

### 4.6. Course Hierarchy & Playlists Details
- **Endpoint**: `GET /api/v1/teacher/courses/{id}`
- **Access**: Protected (`Bearer <token>`)
- **Description**: Returns course details, subjects, structured playlists, and standalone lessons.

#### Success Response (`200 OK`)
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Complete Class 10 Science & Mathematics",
    "slug": "complete-class-10-science-mathematics",
    "description": "Comprehensive course description...",
    "thumbnail_url": "http://yourdomain.com/storage/courses/thumbnails/sample.jpg",
    "is_free": true,
    "exam_board": {
      "id": 1,
      "name": "CBSE"
    },
    "subjects": [
      {
        "id": 2,
        "name": "Mathematics",
        "slug": "mathematics",
        "description": "Standard 10 Mathematics syllabus",
        "playlists_count": 2,
        "videos_count": 13
      }
    ],
    "playlists": [
      {
        "id": 1,
        "subject_id": 2,
        "title": "Unit 1: Real Numbers & Polynomials",
        "slug": "unit-1-real-numbers-polynomials",
        "description": "Complete unit lectures and practice questions",
        "thumbnail_url": null,
        "sort_order": 1,
        "videos_count": 5
      }
    ],
    "standalone_videos": [
      {
        "id": 10,
        "subject_id": 2,
        "title": "Course Orientation & Syllabus Overview",
        "description": "Introductory video",
        "duration_seconds": 600,
        "duration_formatted": "10:00",
        "sort_order": 0,
        "video_url": "https://vimeo.com/12345678",
        "vimeo_video_id": "12345678"
      }
    ]
  }
}
```

---

### 4.7. Subject Content in Course
- **Endpoint**: `GET /api/v1/teacher/courses/{courseId}/subjects/{subjectId}`
- **Access**: Protected (`Bearer <token>`)
- **Description**: Returns all playlists and videos for a specific subject within a course.

#### Success Response (`200 OK`)
```json
{
  "success": true,
  "data": {
    "course": {
      "id": 1,
      "title": "Complete Class 10 Science & Mathematics"
    },
    "subject": {
      "id": 2,
      "name": "Mathematics",
      "description": "Standard 10 Mathematics syllabus"
    },
    "playlists": [
      {
        "id": 1,
        "title": "Unit 1: Real Numbers & Polynomials",
        "description": "Lectures 1 to 5",
        "thumbnail_url": null,
        "videos": [
          {
            "id": 11,
            "title": "Lecture 1: Introduction to Real Numbers",
            "description": "Foundational concepts",
            "duration_seconds": 1820,
            "duration_formatted": "30:20",
            "video_url": "https://vimeo.com/23456789",
            "vimeo_video_id": "23456789",
            "sort_order": 1
          }
        ]
      }
    ],
    "standalone_videos": []
  }
}
```

---

### 4.8. Single Playlist Details
- **Endpoint**: `GET /api/v1/teacher/playlists/{id}`
- **Access**: Protected (`Bearer <token>`)

#### Success Response (`200 OK`)
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Unit 1: Real Numbers & Polynomials",
    "slug": "unit-1-real-numbers-polynomials",
    "description": "Lectures 1 to 5",
    "thumbnail_url": null,
    "course": {
      "id": 1,
      "title": "Complete Class 10 Science & Mathematics"
    },
    "subject": {
      "id": 2,
      "name": "Mathematics"
    },
    "videos_count": 3,
    "videos": [
      {
        "id": 11,
        "title": "Lecture 1: Introduction to Real Numbers",
        "description": "Foundational concepts",
        "duration_seconds": 1820,
        "duration_formatted": "30:20",
        "video_url": "https://vimeo.com/23456789",
        "vimeo_video_id": "23456789",
        "sort_order": 1
      }
    ]
  }
}
```

---

### 4.9. Video Player / Stream Info
- **Endpoint**: `GET /api/v1/teacher/videos/{id}`
- **Access**: Protected (`Bearer <token>`)
- **Description**: Returns video playback streaming URL / Vimeo ID. **No purchase check** is applied for teachers.

#### Success Response (`200 OK`)
```json
{
  "success": true,
  "message": "Video streaming details loaded.",
  "data": {
    "id": 11,
    "title": "Lecture 1: Introduction to Real Numbers",
    "description": "Foundational concepts",
    "video_url": "https://vimeo.com/23456789",
    "vimeo_video_id": "23456789",
    "duration_seconds": 1820,
    "duration_formatted": "30:20",
    "course": {
      "id": 1,
      "title": "Complete Class 10 Science & Mathematics"
    },
    "subject": {
      "id": 2,
      "name": "Mathematics"
    },
    "playlist": {
      "id": 1,
      "title": "Unit 1: Real Numbers & Polynomials"
    },
    "is_free": true
  }
}
```

---

## 5. Frontend Developer Implementation Best Practices

### 5.1. Token Storage & Interceptor Setup (Axios / Fetch)
Store `token` securely:
- **Flutter**: `flutter_secure_storage`
- **React Native**: `expo-secure-store` or `react-native-keychain`
- **Web**: `localStorage` / `sessionStorage` or HTTP-only cookies

#### Axios Interceptor Example
```javascript
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://your-server-ip/api/v1',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  },
});

// Auto-attach Bearer Token
api.interceptors.request.use((config) => {
  const token = getSavedToken(); // your token getter
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Global Error Handler
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Token expired or invalid -> logout & navigate to login screen
      clearSessionAndRedirectToLogin();
    } else if (error.response?.status === 403) {
      // Account deactivated or Institute expired -> show alert & redirect
      showToast(error.response.data.message);
      clearSessionAndRedirectToLogin();
    }
    return Promise.reject(error);
  }
);

export default api;
```

---

## 6. Quick cURL Test Suite

```bash
# 1. Institute Admin Login
curl -X POST http://localhost:8000/api/v1/institute/login \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"login":"admin@apexacademy.com","password":"password123"}'

# 2. Get Institute Admin Profile
curl -X GET http://localhost:8000/api/v1/institute/profile \
  -H "Accept: application/json" \
  -H "Authorization: Bearer <ADMIN_TOKEN>"

# 3. Add Teacher (by Institute Admin)
curl -X POST http://localhost:8000/api/v1/institute/teachers \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <ADMIN_TOKEN>" \
  -d '{
    "name": "Sunita Rao",
    "email": "sunita.rao@apexacademy.com",
    "phone": "9819988776",
    "password": "teacherpass123",
    "designation": "Mathematics Head",
    "employee_id": "TCH-002"
  }'

# 4. Teacher Login
curl -X POST http://localhost:8000/api/v1/teacher/login \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"login":"sunita.rao@apexacademy.com","password":"teacherpass123"}'

# 5. Teacher View Free Courses
curl -X GET http://localhost:8000/api/v1/teacher/courses \
  -H "Accept: application/json" \
  -H "Authorization: Bearer <TEACHER_TOKEN>"

# 6. Stream Video (Free access)
curl -X GET http://localhost:8000/api/v1/teacher/videos/1 \
  -H "Accept: application/json" \
  -H "Authorization: Bearer <TEACHER_TOKEN>"
```
