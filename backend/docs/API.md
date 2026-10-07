# UIU Research Portal — REST API Specification

## Base URL
```
http://localhost:8000/api
```

## Authentication
Authentication is handled using Bearer JWT tokens. To access protected endpoints, provide the header:
```http
Authorization: Bearer <your_jwt_token>
```

Tokens are valid for 24 hours (`86400` seconds) by default.

---

## Standard Response Format

### Success Response (`200 OK`, `201 Created`)
```json
{
  "success": true,
  "status": 200,
  "message": "Operation completed successfully",
  "data": { ... },
  "pagination": {
    "page": 1,
    "per_page": 20,
    "total": 54,
    "pages": 3,
    "has_more": true
  },
  "timestamp": "2026-10-07T13:30:00+06:00"
}
```

### Error Response (`400`, `401`, `403`, `404`, `422`, `500`)
```json
{
  "success": false,
  "status": 422,
  "error": {
    "message": "Validation failed",
    "status": 422,
    "timestamp": "2026-10-07T13:30:00+06:00",
    "details": {
      "email": ["The email must be a valid email address."]
    }
  }
}
```

---

## API Summary Table

| Category | Method | Endpoint | Auth | Description |
|---|---|---|---|---|
| **Health** | `GET` | `/health` | No | API service health check |
| **Auth** | `POST` | `/auth/register` | No | Register new student/faculty account |
| **Auth** | `POST` | `/auth/login` | No | Authenticate user & get JWT |
| **Auth** | `GET` | `/auth/me` | Optional | Current session user details |
| **Auth** | `POST` | `/auth/logout` | No | Terminate session |
| **Users** | `GET` | `/users` | No | List and filter researchers |
| **Users** | `GET` | `/users/{id}` | No | User profile with skills & stats |
| **Users** | `PUT` | `/users/{id}` | Yes | Update profile info |
| **Users** | `GET` | `/users/leaderboard` | No | Reputation leaderboard |
| **Projects** | `GET` | `/projects` | No | List projects with filters |
| **Projects** | `GET` | `/projects/{id}` | No | Project detail, members, task counts |
| **Projects** | `POST` | `/projects` | Yes | Create new collaborative project |
| **Projects** | `PUT` | `/projects/{id}` | Yes | Update project info |
| **Projects** | `DELETE`| `/projects/{id}` | Yes | Soft-delete project |
| **Projects** | `POST` | `/projects/{id}/members` | Yes | Add member to project |
| **Projects** | `DELETE`| `/projects/{id}/members/{uid}` | Yes | Remove member from project |
| **Tasks** | `GET` | `/projects/{pid}/tasks` | No | Kanban task board (todo, in_progress, done) |
| **Tasks** | `POST` | `/projects/{pid}/tasks` | Yes | Create Kanban task |
| **Tasks** | `PUT` | `/tasks/{id}` | Yes | Update task |
| **Tasks** | `PATCH`| `/tasks/{id}/status` | Yes | Drag & drop status move |
| **Tasks** | `DELETE`| `/tasks/{id}` | Yes | Delete task |
| **Messages**| `GET` | `/conversations` | Optional | User's conversations |
| **Messages**| `POST` | `/conversations/dm` | Optional | Open direct message |
| **Messages**| `GET` | `/conversations/{id}/messages`| Optional | Conversation message thread |
| **Messages**| `POST` | `/conversations/{id}/messages`| Optional | Send new message |
| **Resources**| `GET`| `/resources` | No | List papers, datasets, study materials |
| **Resources**| `GET`| `/resources/{id}` | No | Resource metadata & tags |
| **Resources**| `POST`| `/resources` | Yes | Upload/publish resource |
| **Resources**| `POST`| `/resources/{id}/download`| No | Increment download counter |
| **Blogs** | `GET` | `/blogs` | No | List blog articles |
| **Blogs** | `GET` | `/blogs/{id}` | No | Read article with comments |
| **Blogs** | `POST` | `/blogs` | Yes | Publish article |
| **Blogs** | `POST` | `/blogs/{id}/like` | Yes | Toggle like |
| **Blogs** | `POST` | `/blogs/{id}/comments` | Yes | Post comment |
| **Ideas** | `GET` | `/ideas` | No | List innovation ideas |
| **Ideas** | `POST` | `/ideas` | Yes | Post research idea |
| **Ideas** | `POST` | `/ideas/{id}/upvote` | Yes | Upvote idea |
| **Ideas** | `POST` | `/ideas/{id}/comments` | Yes | Comment on idea |
| **Events** | `GET` | `/events` | No | Hackathons, workshops, conferences |
| **Events** | `POST` | `/events/{id}/register` | Yes | Register for event |
| **Gamification**| `GET` | `/reputation/breakdown` | Optional | User points history |
| **Gamification**| `GET` | `/badges` | Optional | Badges list & user earned status |
| **Notifications**| `GET`| `/notifications` | Optional | User notifications |
| **Notifications**| `PATCH`| `/notifications/{id}/read`| Optional| Mark alert as read |
| **Dashboard**| `GET`| `/dashboard` | Optional | Consolidated home dashboard metrics |
| **Search** | `GET` | `/search?q={query}` | No | Global full-text search across portal |
