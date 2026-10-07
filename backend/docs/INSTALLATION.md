# UIU Research Portal — Backend Installation & Setup Guide

## Requirements
- **PHP:** 8.0 or higher (with `pdo_mysql`, `curl`, `openssl`, `mbstring` enabled)
- **MySQL / MariaDB:** 10.4 or higher (e.g., via XAMPP)
- **Composer:** 2.x

---

## 1. Environment Configuration

Copy the example environment file:
```bash
cd backend
copy .env.example .env
```

Ensure your `.env` contains the correct database credentials:
```ini
APP_NAME=UIU_Research_Portal
APP_ENV=development
APP_URL=http://localhost:8000
APP_DEBUG=true

DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=uiu_research_portal
DB_USER=root
DB_PASSWORD=

JWT_SECRET=your-secret-key-change-this-in-production
```

---

## 2. Install Dependencies

Using Composer:
```bash
composer install
```

---

## 3. Database Migration & Seeding

Ensure MySQL is running (e.g. start MySQL in the XAMPP Control Panel).

Run migrations to automatically create the database and all 31 tables:
```bash
php bin/migrate.php --fresh
```

This runs all 11 migration files and seeds demo data matching the frontend (`data.js`):
- 8 Demo researchers (including current student Rafsan Ahmed)
- 4 Projects (AI Healthcare, Smart Campus, Blockchain Credentials, NLP Summarizer)
- 8 Kanban tasks
- 10 Resources & Datasets
- 5 Blog articles with comments
- 5 Innovation Hub ideas
- 5 Real-time conversations
- 8 Notifications
- 5 Campus events & hackathons
- 8 Achievement badges & reputation points

---

## 4. Run Development Server

Start the PHP built-in web server pointing to the `public/` directory:
```bash
php -S localhost:8000 -t public
```

Test the API health endpoint:
```bash
curl http://localhost:8000/api/health
```

Expected Response:
```json
{
  "success": true,
  "status": 200,
  "data": {
    "status": "ok",
    "service": "UIU Research Portal API",
    "timestamp": "2026-10-07T13:30:00+06:00"
  }
}
```

---

## 5. Demo Credentials

| Role | Email | Password |
|---|---|---|
| Graduate Student / Lead | `rafsan.ahmed@uiu.ac.bd` | `password123` |
| Student | `nusrat.jahan@uiu.ac.bd` | `password123` |
| Student | `tanvir.hossain@uiu.ac.bd` | `password123` |
| Student | `rakibul.islam@uiu.ac.bd` | `password123` |
