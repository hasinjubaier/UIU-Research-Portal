# UIU Research Portal — Backend API

Modern RESTful API powering the academic research collaboration portal for United International University (UIU).

## Tech Stack
- **PHP:** 8.0+
- **Framework:** Slim 4 Framework (PSR-7 / PSR-15 architecture)
- **Database:** MySQL 8.0+ / MariaDB 10.4+
- **Authentication:** JWT Bearer Token (HMAC-SHA256)
- **Database Abstraction:** PDO with prepared statements & transactions

## Directory Structure
```
backend/
├── bin/
│   ├── migrate.php       # CLI migration runner
│   └── seed.php          # Database seeder runner
├── config/
│   ├── constants.php     # Application enums & constants
│   ├── database.php      # PDO database configuration
│   └── settings.php      # Application settings (JWT, CORS, uploads)
├── docs/
│   ├── API.md            # API documentation overview
│   ├── DATABASE.md       # ER diagram & schema reference
│   ├── ENDPOINTS.md      # Complete endpoints reference
│   └── INSTALLATION.md   # Setup instructions
├── public/
│   └── index.php         # Single entry point front controller
├── routes/
│   └── api.php           # REST API routes definition
├── src/
│   ├── Controllers/      # 14 REST API Controllers
│   ├── Database/         # 11 Migrations, Seeders & Connection class
│   ├── Exceptions/       # Domain exception hierarchy
│   ├── Middleware/       # Auth, CORS, RateLimit, ErrorHandler middlewares
│   ├── Models/           # 15 Data models with BaseModel
│   ├── Services/         # 9 Business logic services
│   └── Utils/            # JWT, Logger, Validator, Response, Email
├── storage/              # Logs, cache, and uploaded files
├── tests/                # Unit and Integration tests
├── .env                  # Environment configuration
├── composer.json         # PHP dependencies
├── phpunit.xml           # PHPUnit configuration
└── postman_collection.json # Postman API testing collection
```

## Quick Start
1. Ensure MySQL is running (e.g. from XAMPP).
2. Run database migrations and seed demo data:
   ```bash
   php bin/migrate.php --fresh
   ```
3. Start the PHP server:
   ```bash
   php -S localhost:8000 -t public
   ```
4. Verify by opening `http://localhost:8000/api/health` in your browser.
