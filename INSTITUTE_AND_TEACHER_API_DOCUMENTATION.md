# Institute & Teacher System — Master API & System Documentation

## 1. System Overview & Hierarchy

The application supports an **Institute & Teacher Management Ecosystem**:
1. **Super/Main Admin (Web Portal)**:
   - Creates and manages **Institutes & Coaching Centers** (`/admin/institutes`).
   - Automatically generates unique institute codes (no manual entry required).
   - Manages **Institute Validity** (Lifetime Access or custom Expiry Date).
   - If an Institute is marked **Inactive**, its Institute Admin and all teachers are **automatically deactivated** and their mobile app login tokens are revoked.
   - **Complete Content Separation**:
     - **Student Content**: Uploaded via `/admin/courses`, `/admin/subjects`, `/admin/playlists`, `/admin/videos` (only visible to student app users).
     - **Teacher / Institute Content**: Uploaded via `/admin/institute-courses`, `/admin/institute-subjects`, `/admin/institute-playlists`, `/admin/institute-videos` (only visible to institute teachers).
2. **Institute Admin (Mobile App / API Only)**:
   - Logs into the mobile app using their email/phone and password.
   - **Blocked from Web Admin Portal**: Institute Admins cannot log into the web admin portal (`/admin/login`).
   - Adds, views, updates, activates/deactivates, and removes **Teachers & Staff** under their institute.
3. **Institute Teachers (Mobile App / API Only)**:
   - Logs into the mobile app using credentials set by their Institute Admin.
   - Gets **100% Free Access** to all Teacher Courses, Subjects, Playlists, and Video Streaming published by the Main Admin.
   - Strictly isolated from student content: teachers do not see student courses, and students do not see teacher courses.

---

## 2. API Endpoints Quick Reference

### Institute Admin APIs (`/api/v1/institute/*`)
| Method | Endpoint | Description | Auth Required |
|---|---|---|---|
| `POST` | `/api/v1/institute/login` | Login (Email/Phone + Password) | No |
| `GET` | `/api/v1/institute/profile` | Institute & Admin details + staff counts | Yes (Bearer) |
| `PUT` | `/api/v1/institute/profile` | Update profile (Name, phone, password) | Yes (Bearer) |
| `POST` | `/api/v1/institute/logout` | Logout & revoke token | Yes (Bearer) |
| `GET` | `/api/v1/institute/teachers` | List institute teachers (search, status, pagination) | Yes (Bearer) |
| `POST` | `/api/v1/institute/teachers` | **Add new teacher** | Yes (Bearer) |
| `GET` | `/api/v1/institute/teachers/{id}` | View single teacher details | Yes (Bearer) |
| `PUT` | `/api/v1/institute/teachers/{id}` | Update teacher details | Yes (Bearer) |
| `POST` | `/api/v1/institute/teachers/{id}/toggle-status` | Toggle active/inactive status | Yes (Bearer) |
| `DELETE` | `/api/v1/institute/teachers/{id}` | Remove teacher | Yes (Bearer) |

### Teacher APIs (`/api/v1/teacher/*`)
| Method | Endpoint | Description | Auth Required |
|---|---|---|---|
| `POST` | `/api/v1/teacher/login` | Teacher login (Email/Phone + Password) | No |
| `GET` | `/api/v1/teacher/profile` | Teacher profile & institute details | Yes (Bearer) |
| `PUT` | `/api/v1/teacher/profile` | Update teacher profile | Yes (Bearer) |
| `POST` | `/api/v1/teacher/logout` | Teacher logout | Yes (Bearer) |
| `GET` | `/api/v1/teacher/courses` | **Browse all courses (100% Free)** | Yes (Bearer) |
| `GET` | `/api/v1/teacher/courses/{id}` | Course details, subjects & playlists | Yes (Bearer) |
| `GET` | `/api/v1/teacher/courses/{courseId}/subjects/{subjectId}` | Subject playlists & video lessons | Yes (Bearer) |
| `GET` | `/api/v1/teacher/playlists/{id}` | Playlist details with videos | Yes (Bearer) |
| `GET` | `/api/v1/teacher/videos/{id}` | Video streaming URL (Free access) | Yes (Bearer) |

---

For full JSON payloads, field validations, and frontend implementation guide, see:
- **[INSTITUTE_API_FRONTEND_DOCS.md](file:///e:/SAMTA/INSTITUTE_API_FRONTEND_DOCS.md)**
