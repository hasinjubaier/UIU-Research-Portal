# UIU Research Portal — Complete Endpoints Reference

All endpoints return JSON responses wrapped in the standard envelope.

---

## 1. Authentication

### `POST /api/auth/register`
Create a new user profile.
- **Request:**
  ```json
  {
    "name": "Sharif Rahman",
    "email": "sharif@uiu.ac.bd",
    "password": "password123",
    "department": "CSE",
    "academic_year": "2nd Year",
    "skills": ["Python", "TensorFlow"]
  }
  ```
- **Response (`201 Created`):**
  ```json
  {
    "success": true,
    "status": 201,
    "data": {
      "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
      "user": {
        "id": 9,
        "name": "Sharif Rahman",
        "email": "sharif@uiu.ac.bd",
        "role": "Student",
        "skills": ["Python", "TensorFlow"]
      }
    }
  }
  ```

### `POST /api/auth/login`
- **Request:**
  ```json
  {
    "email": "rafsan.ahmed@uiu.ac.bd",
    "password": "password123"
  }
  ```
- **Response (`200 OK`):**
  ```json
  {
    "success": true,
    "status": 200,
    "data": {
      "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
      "user": { ... }
    }
  }
  ```

### `GET /api/auth/me`
Returns current session profile. Requires `Authorization: Bearer <token>`.

---

## 2. Projects & Workspace

### `GET /api/projects`
Query parameters: `page`, `per_page`, `status`, `visibility`, `domain`, `q`.

### `GET /api/projects/{id}`
Returns full project with member details, tags, and task breakdown.

### `POST /api/projects`
- **Headers:** `Authorization: Bearer <token>`
- **Request:**
  ```json
  {
    "title": "Autonomous Drone Navigation",
    "description": "Vision-based collision avoidance using edge computing.",
    "domain": "AI / Robotics",
    "visibility": "public",
    "tags": ["Computer Vision", "PyTorch", "ROS"]
  }
  ```

### `GET /api/projects/{projectId}/tasks`
Returns Kanban board grouped by column:
```json
{
  "backlog": [],
  "todo": [
    { "id": 1, "title": "Collect dataset", "priority": "high", "assignee": 7, "due": "May 20" }
  ],
  "inProgress": [
    { "id": 4, "title": "Train CNN baseline", "priority": "high", "assignee": 5, "due": "May 18" }
  ],
  "review": [],
  "done": [
    { "id": 6, "title": "Project setup", "priority": "medium", "assignee": 1, "due": "Apr 10" }
  ]
}
```

### `PATCH /api/tasks/{id}/status`
Moves task to another column.
- **Request:**
  ```json
  { "status": "done" }
  ```

---

## 3. Resources & Datasets

### `GET /api/resources`
Query parameters: `type` (Paper, Dataset, Code, etc.), `category`, `q`.

### `POST /api/resources`
Multipart form upload or JSON metadata.
- **Fields:** `title`, `type`, `category`, `tags`, `author_name`, `file` (optional binary).

### `POST /api/resources/{id}/download`
Increments download count and returns new total.

---

## 4. Innovation Hub (Ideas)

### `GET /api/ideas`
Returns ideas sorted by upvotes.

### `POST /api/ideas/{id}/upvote`
Toggles user upvote on idea:
```json
{
  "success": true,
  "data": {
    "upvoted": true,
    "upvotes": 135
  }
}
```

---

## 5. Blog & Discussions

### `GET /api/blogs`
List articles with authors and tags.

### `POST /api/blogs/{id}/like`
Toggles like status.

### `POST /api/blogs/{id}/comments`
- **Request:**
  ```json
  { "comment": "Great methodology section!" }
  ```

---

## 6. Messaging & Chat

### `GET /api/conversations`
List user DM and group chats with unread counts.

### `GET /api/conversations/{id}/messages`
Get message thread.

### `POST /api/conversations/{id}/messages`
- **Request:**
  ```json
  { "message": "Hi, let's sync at 5pm." }
  ```

---

## 7. Global Search & Dashboard

### `GET /api/dashboard`
Aggregated home screen payload with stats, projects, notifications, and leaderboard.

### `GET /api/search?q=machine`
Unified search returning matched projects, resources, researchers, and ideas.
