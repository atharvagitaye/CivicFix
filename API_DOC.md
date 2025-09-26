# CivicFix API Documentation

## Authentication

### Register
- **POST** `/api/register`
- **Body:** `{ name, email, password }`
- **Response:** `201 Created`, `{ access_token, token_type, user }`
- **Errors:** `422` (validation)

### Login
- **POST** `/api/login`
- **Body:** `{ email, password }`
- **Response:** `200 OK`, `{ access_token, token_type, user }`
- **Errors:** `401` (invalid credentials), `422` (validation)

---

## Issues

### Report Issue
- **POST** `/api/issues` (auth required)
- **Body:** `{ description, latitude, longitude, media[] (optional) }`
- **Response:** `201 Created`, `{ message, issue }`
- **Errors:** `401` (unauthenticated), `422` (validation)

### List Issues
- **GET** `/api/issues` (auth required)
- **Query:** `status`, `user_id`, `category_id`, `latitude`, `longitude`
- **Response:** `200 OK`, paginated issues
- **Errors:** `401` (unauthenticated)

### Update Issue Status
- **PATCH** `/api/issues/{id}/status` (staff/admin only)
- **Body:** `{ status }` (`created`, `under_process`, `resolved`)
- **Response:** `200 OK`, `{ message, issue }`
- **Errors:** `401` (unauthenticated), `403` (forbidden), `404` (not found), `422` (validation)

---

## Categories & Subcategories (admin/staff only)

### Categories
- **GET** `/api/categories`
- **POST** `/api/categories` `{ name }`
- **GET** `/api/categories/{id}`
- **PUT** `/api/categories/{id}` `{ name }`
- **DELETE** `/api/categories/{id}`
- **Errors:** `401`, `403`, `404`, `422`

### Subcategories
- **GET** `/api/sub-categories`
- **POST** `/api/sub-categories` `{ name, category_id }`
- **GET** `/api/sub-categories/{id}`
- **PUT** `/api/sub-categories/{id}` `{ name, category_id }`
- **DELETE** `/api/sub-categories/{id}`
- **Errors:** `401`, `403`, `404`, `422`

---

## User Profile

### Get Profile
- **GET** `/api/user/profile` (auth required)
- **Response:** `200 OK`, user object

### Update Profile
- **PUT** `/api/user/profile` (auth required)
- **Body:** `{ name, email, password (optional) }`
- **Response:** `200 OK`, updated user
- **Errors:** `401`, `422`

### My Issues
- **GET** `/api/user/issues` (auth required)
- **Response:** `200 OK`, user's issues

---

## Staff/Admin Management (admin/staff only)

### Staff
- **GET/POST/PUT/DELETE** `/api/staffs` (standard REST)
- **Body:** `{ name, email, password }`
- **Errors:** `401`, `403`, `404`, `422`

### Admins
- **GET/POST/PUT/DELETE** `/api/admins` (standard REST)
- **Body:** `{ name, email, password }`
- **Errors:** `401`, `403`, `404`, `422`

---

## Password Reset

### Request Reset
- **POST** `/api/password/forgot` `{ email }`
- **Response:** `200 OK`, `{ message }`
- **Errors:** `422`, `404`

### Reset Password
- **POST** `/api/password/reset` `{ email, token, password, password_confirmation }`
- **Response:** `200 OK`, `{ message }`
- **Errors:** `400` (invalid/expired token), `422`

---

## Analytics (auth required)

### Issues by Status
- **GET** `/api/analytics/issues-by-status`
- **Response:** `200 OK`, array of `{ status, total }`

### Issues by Category
- **GET** `/api/analytics/issues-by-category`
- **Response:** `200 OK`, array of `{ category, total }`

### Issues by Date
- **GET** `/api/analytics/issues-by-date?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD`
- **Response:** `200 OK`, array of `{ date, total }`

---

## Error Codes
- `401 Unauthorized`: Not logged in or token missing/invalid
- `403 Forbidden`: Not enough permissions (e.g., not staff/admin)
- `404 Not Found`: Resource does not exist
- `422 Unprocessable Entity`: Validation failed
- `400 Bad Request`: Invalid token or request

---

## Notes
- All endpoints prefixed with `/api/`
- Most endpoints require Bearer token in `Authorization` header
- File uploads use `multipart/form-data`
- All responses are JSON
