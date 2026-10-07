# UIU Research Portal - Complete Backend Implementation Agent Prompt

**Version:** 1.0  
**Date:** October 7, 2026  
**Status:** Ready for AI Agent Execution  
**Estimated Duration:** 2-4 weeks (with continuous AI agent iterations)

---

## SYSTEM INSTRUCTIONS FOR AI AGENT

You are an **expert full-stack PHP/MySQL developer** tasked with building the complete backend for the UIU Research Portal. Your goal is to create a **production-ready, scalable, secure REST API** that connects the existing frontend to a real database.

### Core Principles
1. **Follow PHP best practices** (OOP, design patterns, type hints)
2. **Security first** (input validation, authentication, authorization)
3. **Database normalization** (proper schemas, relationships, indexes)
4. **RESTful API design** (consistent endpoints, error handling, documentation)
5. **Testability** (code structure allows for unit/integration tests)
6. **Scalability** (prepared for 10,000+ users, optimize queries)
7. **Maintainability** (clear code, comprehensive documentation, comments)

---

## PROJECT CONTEXT

### Frontend Status
- ✅ 19 HTML pages complete
- ✅ Professional design system implemented
- ✅ 14 feature modules with UI
- ✅ Mock data in `assets/js/data.js`
- ❌ **No backend connection yet** — All data is client-side

### What Needs to Be Built
- ✅ Complete REST API for all 14 modules
- ✅ MySQL database with normalized schema
- ✅ User authentication (registration, login, JWT tokens)
- ✅ Authorization system (role-based access control)
- ✅ File upload handling and storage
- ✅ Real-time features prep (WebSocket framework)
- ✅ Error handling and logging system
- ✅ Database migration scripts
- ✅ Comprehensive API documentation
- ✅ Unit and integration tests
- ✅ Postman collection for testing

### Tech Stack
- **Language:** PHP 8.1+
- **Framework:** Laravel 11 OR Slim 4 (choose one)
- **Database:** MySQL 8.0+
- **Authentication:** JWT tokens + PHP sessions
- **Validation:** Built-in or dedicated library
- **Testing:** PHPUnit
- **Documentation:** OpenAPI 3.0 (Swagger)

---

## PHASE 1: PROJECT STRUCTURE & SETUP

### 1.1 Directory Structure

Create this exact structure:

```
uiu-research-portal-backend/
│
├── config/                          # Configuration files
│   ├── database.php                 # DB connection settings
│   ├── settings.php                 # App config (JWT secret, email, etc.)
│   └── constants.php                # App constants & enums
│
├── src/
│   ├── Controllers/                 # Request handlers
│   │   ├── AuthController.php       # Login, register, refresh token
│   │   ├── UserController.php       # User CRUD, profiles, skills
│   │   ├── ProjectController.php    # Project management
│   │   ├── TaskController.php       # Task Kanban operations
│   │   ├── MessageController.php    # Messaging system
│   │   ├── ResourceController.php   # File uploads (papers, datasets)
│   │   ├── BlogController.php       # Blog posts, comments
│   │   ├── IdeaController.php       # Innovation Hub ideas, upvotes
│   │   ├── ContributionController.php # Analytics & tracking
│   │   ├── ReputationController.php # Leaderboard, gamification
│   │   ├── NotificationController.php # Alerts & notifications
│   │   ├── EventController.php      # Events, registrations
│   │   ├── CollaboratorController.php # Find collaborators
│   │   └── DashboardController.php  # Main dashboard data
│   │
│   ├── Models/                      # Data models (ORM entities)
│   │   ├── User.php
│   │   ├── Project.php
│   │   ├── ProjectMember.php
│   │   ├── Task.php
│   │   ├── Message.php
│   │   ├── Conversation.php
│   │   ├── Resource.php
│   │   ├── BlogPost.php
│   │   ├── BlogComment.php
│   │   ├── Idea.php
│   │   ├── IdeaUpvote.php
│   │   ├── Notification.php
│   │   ├── Event.php
│   │   ├── UserBadge.php
│   │   └── Contribution.php
│   │
│   ├── Services/                    # Business logic layer
│   │   ├── AuthService.php          # Auth logic (register, login, JWT)
│   │   ├── UserService.php          # User operations
│   │   ├── ProjectService.php       # Project operations
│   │   ├── TaskService.php          # Task operations
│   │   ├── FileService.php          # File upload/storage
│   │   ├── ReputationService.php    # Points calculation, badges
│   │   ├── NotificationService.php  # Notification generation
│   │   ├── SearchService.php        # Full-text search
│   │   └── AnalyticsService.php     # Stats generation
│   │
│   ├── Middleware/                  # Request/response processing
│   │   ├── AuthMiddleware.php       # Validate JWT token
│   │   ├── ValidationMiddleware.php # Input validation
│   │   ├── RateLimitMiddleware.php  # Rate limiting
│   │   ├── CorsMiddleware.php       # CORS headers
│   │   └── ErrorHandlerMiddleware.php # Error handling
│   │
│   ├── Database/
│   │   ├── migrations/              # Database version control
│   │   │   ├── 001_create_users_table.php
│   │   │   ├── 002_create_projects_table.php
│   │   │   ├── 003_create_tasks_table.php
│   │   │   ├── 004_create_messages_table.php
│   │   │   ├── 005_create_resources_table.php
│   │   │   ├── 006_create_blog_posts_table.php
│   │   │   ├── 007_create_ideas_table.php
│   │   │   ├── 008_create_notifications_table.php
│   │   │   ├── 009_create_events_table.php
│   │   │   └── 010_create_badges_table.php
│   │   │
│   │   ├── seeds/                   # Test data
│   │   │   ├── UserSeeder.php       # Demo users
│   │   │   ├── ProjectSeeder.php    # Demo projects
│   │   │   └── TaskSeeder.php       # Demo tasks
│   │   │
│   │   └── Connection.php           # PDO connection class
│   │
│   ├── Utils/                       # Utility classes
│   │   ├── JWT.php                  # JWT token handling
│   │   ├── Logger.php               # Error & activity logging
│   │   ├── Validator.php            # Input validation rules
│   │   ├── Response.php             # Standardized API responses
│   │   ├── Constants.php            # App enums/constants
│   │   └── EmailService.php         # Email sending (PHPMailer)
│   │
│   └── Exceptions/                  # Custom exceptions
│       ├── ValidationException.php
│       ├── AuthenticationException.php
│       ├── AuthorizationException.php
│       ├── ResourceNotFoundException.php
│       └── ServerException.php
│
├── routes/
│   └── api.php                      # All API endpoints definition
│
├── tests/
│   ├── Unit/
│   │   ├── AuthServiceTest.php
│   │   ├── UserServiceTest.php
│   │   ├── ProjectServiceTest.php
│   │   └── ValidationTest.php
│   │
│   ├── Integration/
│   │   ├── AuthApiTest.php
│   │   ├── ProjectApiTest.php
│   │   └── FileUploadTest.php
│   │
│   └── bootstrap.php                # Test configuration
│
├── storage/
│   ├── logs/                        # Application logs
│   ├── uploads/                     # User-uploaded files (outside webroot)
│   └── cache/                       # Cache files
│
├── public/
│   └── index.php                    # Single entry point (front controller)
│
├── docs/
│   ├── API.md                       # OpenAPI/Swagger documentation
│   ├── DATABASE.md                  # Database schema documentation
│   ├── INSTALLATION.md              # Setup instructions
│   └── ENDPOINTS.md                 # Complete endpoint reference
│
├── .env.example                     # Environment variables template
├── .gitignore                       # Git ignore rules
├── composer.json                    # PHP dependencies
├── docker-compose.yml               # Docker setup
├── Dockerfile                       # Docker image
├── phpunit.xml                      # PHPUnit configuration
├── postman_collection.json          # Postman API testing
└── README.md                        # Project readme

```

### 1.2 Core Dependencies (composer.json)

Create `composer.json` with these dependencies:

```json
{
  "name": "uiu/research-portal-backend",
  "description": "UIU Academic Research Collaboration Portal - Backend API",
  "type": "project",
  "require": {
    "php": "^8.1",
    "slim/slim": "^4.11",
    "slim/psr7": "^1.6",
    "firebase/php-jwt": "^6.8",
    "vlucas/phpdotenv": "^5.5",
    "phpmailer/phpmailer": "^6.8",
    "ralouphie/getallheaders": "^3.3"
  },
  "require-dev": {
    "phpunit/phpunit": "^10.0",
    "squizlabs/php_codesniffer": "^3.7",
    "phpstan/phpstan": "^1.9"
  },
  "autoload": {
    "psr-4": {
      "App\\": "src/"
    },
    "files": [
      "config/constants.php"
    ]
  },
  "scripts": {
    "serve": "php -S localhost:8000 -t public",
    "test": "phpunit",
    "lint": "phpcs src/",
    "migrate": "php bin/migrate.php"
  }
}
```

---

## PHASE 2: DATABASE DESIGN & MIGRATIONS

### 2.1 Complete Database Schema

**Create all migration files** with this structure:

#### Migration 001: Users Table
```php
<?php
// src/Database/migrations/001_create_users_table.php

class CreateUsersTable {
  public static function up($connection) {
    $sql = "
      CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        full_name VARCHAR(255) NOT NULL,
        department VARCHAR(100),
        academic_year ENUM('1st', '2nd', '3rd', '4th', 'Masters', 'PhD'),
        bio TEXT,
        skills JSON,
        avatar_url VARCHAR(255),
        cover_image_url VARCHAR(255),
        status ENUM('online', 'offline', 'away') DEFAULT 'offline',
        reputation INT DEFAULT 0,
        uiu_verified BOOLEAN DEFAULT FALSE,
        verification_token VARCHAR(255),
        verification_token_expires TIMESTAMP NULL,
        password_reset_token VARCHAR(255),
        password_reset_expires TIMESTAMP NULL,
        last_login_at TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL,
        
        INDEX idx_email (email),
        INDEX idx_reputation (reputation DESC),
        INDEX idx_status (status),
        FULLTEXT INDEX ft_search (full_name, bio, skills)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $connection->exec($sql);
    return true;
  }
  
  public static function down($connection) {
    $connection->exec("DROP TABLE IF EXISTS users");
    return true;
  }
}
```

#### Migration 002: Projects Table
```php
<?php
// src/Database/migrations/002_create_projects_table.php

class CreateProjectsTable {
  public static function up($connection) {
    $sql = "
      CREATE TABLE projects (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description LONGTEXT,
        created_by INT NOT NULL,
        status ENUM('planning', 'active', 'completed', 'archived') DEFAULT 'planning',
        visibility ENUM('public', 'private') DEFAULT 'public',
        category VARCHAR(100),
        cover_image_url VARCHAR(255),
        start_date DATE,
        end_date DATE,
        progress INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL,
        
        FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_status (status),
        INDEX idx_created_by (created_by),
        FULLTEXT INDEX ft_search (title, description)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $connection->exec($sql);
    return true;
  }
  
  public static function down($connection) {
    $connection->exec("DROP TABLE IF EXISTS projects");
    return true;
  }
}
```

#### Migration 003: Tasks Table
```php
<?php
// src/Database/migrations/003_create_tasks_table.php

class CreateTasksTable {
  public static function up($connection) {
    $sql = "
      CREATE TABLE tasks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        project_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        description LONGTEXT,
        status ENUM('todo', 'in_progress', 'review', 'done') DEFAULT 'todo',
        priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
        assignee_id INT,
        due_date DATE,
        position INT DEFAULT 0,
        created_by INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL,
        
        FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
        FOREIGN KEY (assignee_id) REFERENCES users(id) ON DELETE SET NULL,
        FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_project_status (project_id, status),
        INDEX idx_assignee (assignee_id),
        INDEX idx_due_date (due_date)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $connection->exec($sql);
    return true;
  }
  
  public static function down($connection) {
    $connection->exec("DROP TABLE IF EXISTS tasks");
    return true;
  }
}
```

#### Migration 004: Project Members Table
```php
<?php
// src/Database/migrations/004_create_project_members_table.php

class CreateProjectMembersTable {
  public static function up($connection) {
    $sql = "
      CREATE TABLE project_members (
        id INT AUTO_INCREMENT PRIMARY KEY,
        project_id INT NOT NULL,
        user_id INT NOT NULL,
        role ENUM('owner', 'lead', 'member', 'viewer') DEFAULT 'member',
        joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        
        FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        UNIQUE KEY unique_member (project_id, user_id),
        INDEX idx_user (user_id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $connection->exec($sql);
    return true;
  }
  
  public static function down($connection) {
    $connection->exec("DROP TABLE IF EXISTS project_members");
    return true;
  }
}
```

#### Migration 005: Messages Table
```php
<?php
// src/Database/migrations/005_create_messages_table.php

class CreateMessagesTable {
  public static function up($connection) {
    $sql = "
      CREATE TABLE conversations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255),
        type ENUM('direct', 'group', 'project') DEFAULT 'direct',
        project_id INT,
        created_by INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        
        FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
        FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $connection->exec($sql);
    
    $sql = "
      CREATE TABLE messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        conversation_id INT NOT NULL,
        sender_id INT NOT NULL,
        content LONGTEXT NOT NULL,
        is_edited BOOLEAN DEFAULT FALSE,
        edited_at TIMESTAMP NULL,
        is_deleted BOOLEAN DEFAULT FALSE,
        deleted_at TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        
        FOREIGN KEY (conversation_id) REFERENCES conversations(id) ON DELETE CASCADE,
        FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_conversation_date (conversation_id, created_at DESC),
        INDEX idx_sender (sender_id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $connection->exec($sql);
    
    $sql = "
      CREATE TABLE conversation_members (
        id INT AUTO_INCREMENT PRIMARY KEY,
        conversation_id INT NOT NULL,
        user_id INT NOT NULL,
        joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_read_at TIMESTAMP NULL,
        
        FOREIGN KEY (conversation_id) REFERENCES conversations(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        UNIQUE KEY unique_member (conversation_id, user_id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $connection->exec($sql);
    return true;
  }
  
  public static function down($connection) {
    $connection->exec("DROP TABLE IF EXISTS conversation_members");
    $connection->exec("DROP TABLE IF EXISTS messages");
    $connection->exec("DROP TABLE IF EXISTS conversations");
    return true;
  }
}
```

#### Migration 006: Resources Table
```php
<?php
// src/Database/migrations/006_create_resources_table.php

class CreateResourcesTable {
  public static function up($connection) {
    $sql = "
      CREATE TABLE resources (
        id INT AUTO_INCREMENT PRIMARY KEY,
        project_id INT,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        category ENUM('paper', 'dataset', 'code', 'documentation', 'other') DEFAULT 'other',
        file_path VARCHAR(255) NOT NULL,
        file_name VARCHAR(255) NOT NULL,
        file_size INT,
        mime_type VARCHAR(100),
        uploaded_by INT NOT NULL,
        download_count INT DEFAULT 0,
        is_public BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        
        FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
        FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_category (category),
        INDEX idx_uploaded_by (uploaded_by),
        FULLTEXT INDEX ft_search (title, description)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $connection->exec($sql);
    return true;
  }
  
  public static function down($connection) {
    $connection->exec("DROP TABLE IF EXISTS resources");
    return true;
  }
}
```

#### Migration 007: Blog Posts Table
```php
<?php
// src/Database/migrations/007_create_blog_posts_table.php

class CreateBlogPostsTable {
  public static function up($connection) {
    $sql = "
      CREATE TABLE blog_posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        slug VARCHAR(255) UNIQUE,
        content LONGTEXT NOT NULL,
        excerpt TEXT,
        author_id INT NOT NULL,
        category VARCHAR(100),
        cover_image_url VARCHAR(255),
        status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
        likes_count INT DEFAULT 0,
        views_count INT DEFAULT 0,
        published_at TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        
        FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_status (status),
        INDEX idx_published (published_at),
        FULLTEXT INDEX ft_search (title, content)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $connection->exec($sql);
    
    $sql = "
      CREATE TABLE blog_comments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        blog_post_id INT NOT NULL,
        author_id INT NOT NULL,
        content TEXT NOT NULL,
        parent_comment_id INT,
        status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        
        FOREIGN KEY (blog_post_id) REFERENCES blog_posts(id) ON DELETE CASCADE,
        FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (parent_comment_id) REFERENCES blog_comments(id) ON DELETE CASCADE,
        INDEX idx_post (blog_post_id),
        INDEX idx_status (status)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $connection->exec($sql);
    
    $sql = "
      CREATE TABLE blog_post_likes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        blog_post_id INT NOT NULL,
        user_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        
        FOREIGN KEY (blog_post_id) REFERENCES blog_posts(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        UNIQUE KEY unique_like (blog_post_id, user_id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $connection->exec($sql);
    return true;
  }
  
  public static function down($connection) {
    $connection->exec("DROP TABLE IF EXISTS blog_post_likes");
    $connection->exec("DROP TABLE IF EXISTS blog_comments");
    $connection->exec("DROP TABLE IF EXISTS blog_posts");
    return true;
  }
}
```

#### Migration 008: Ideas Table
```php
<?php
// src/Database/migrations/008_create_ideas_table.php

class CreateIdeasTable {
  public static function up($connection) {
    $sql = "
      CREATE TABLE ideas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description LONGTEXT NOT NULL,
        author_id INT NOT NULL,
        category VARCHAR(100),
        status ENUM('open', 'in_progress', 'completed', 'closed') DEFAULT 'open',
        upvotes_count INT DEFAULT 0,
        joins_count INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        
        FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_status (status),
        INDEX idx_upvotes (upvotes_count DESC),
        FULLTEXT INDEX ft_search (title, description)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $connection->exec($sql);
    
    $sql = "
      CREATE TABLE idea_upvotes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        idea_id INT NOT NULL,
        user_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        
        FOREIGN KEY (idea_id) REFERENCES ideas(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        UNIQUE KEY unique_upvote (idea_id, user_id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $connection->exec($sql);
    
    $sql = "
      CREATE TABLE idea_join_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        idea_id INT NOT NULL,
        user_id INT NOT NULL,
        status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
        requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        
        FOREIGN KEY (idea_id) REFERENCES ideas(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        UNIQUE KEY unique_request (idea_id, user_id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $connection->exec($sql);
    return true;
  }
  
  public static function down($connection) {
    $connection->exec("DROP TABLE IF EXISTS idea_join_requests");
    $connection->exec("DROP TABLE IF EXISTS idea_upvotes");
    $connection->exec("DROP TABLE IF EXISTS ideas");
    return true;
  }
}
```

#### Migration 009: Notifications Table
```php
<?php
// src/Database/migrations/009_create_notifications_table.php

class CreateNotificationsTable {
  public static function up($connection) {
    $sql = "
      CREATE TABLE notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        type ENUM('message', 'invite', 'mention', 'achievement', 'update', 'join_request') DEFAULT 'update',
        title VARCHAR(255) NOT NULL,
        message TEXT,
        related_type VARCHAR(50),
        related_id INT,
        action_url VARCHAR(255),
        is_read BOOLEAN DEFAULT FALSE,
        read_at TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_user_read (user_id, is_read),
        INDEX idx_created (created_at DESC)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $connection->exec($sql);
    return true;
  }
  
  public static function down($connection) {
    $connection->exec("DROP TABLE IF EXISTS notifications");
    return true;
  }
}
```

#### Migration 010: Events Table
```php
<?php
// src/Database/migrations/010_create_events_table.php

class CreateEventsTable {
  public static function up($connection) {
    $sql = "
      CREATE TABLE events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description LONGTEXT,
        type ENUM('workshop', 'hackathon', 'conference', 'seminar', 'webinar') DEFAULT 'workshop',
        start_date DATETIME NOT NULL,
        end_date DATETIME NOT NULL,
        location VARCHAR(255),
        organizer_id INT NOT NULL,
        cover_image_url VARCHAR(255),
        max_attendees INT,
        status ENUM('upcoming', 'ongoing', 'completed', 'cancelled') DEFAULT 'upcoming',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        
        FOREIGN KEY (organizer_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_status (status),
        INDEX idx_start_date (start_date)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $connection->exec($sql);
    
    $sql = "
      CREATE TABLE event_registrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        event_id INT NOT NULL,
        user_id INT NOT NULL,
        registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        
        FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        UNIQUE KEY unique_registration (event_id, user_id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $connection->exec($sql);
    return true;
  }
  
  public static function down($connection) {
    $connection->exec("DROP TABLE IF EXISTS event_registrations");
    $connection->exec("DROP TABLE IF EXISTS events");
    return true;
  }
}
```

#### Migration 011: Badges & Reputation Tables
```php
<?php
// src/Database/migrations/011_create_badges_table.php

class CreateBadgesTable {
  public static function up($connection) {
    $sql = "
      CREATE TABLE badges (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) UNIQUE NOT NULL,
        description TEXT,
        icon_url VARCHAR(255),
        requirement_type ENUM('reputation', 'milestone', 'achievement') DEFAULT 'achievement',
        requirement_value INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $connection->exec($sql);
    
    $sql = "
      CREATE TABLE user_badges (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        badge_id INT NOT NULL,
        unlocked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (badge_id) REFERENCES badges(id) ON DELETE CASCADE,
        UNIQUE KEY unique_badge (user_id, badge_id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $connection->exec($sql);
    
    $sql = "
      CREATE TABLE contribution_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        project_id INT NOT NULL,
        user_id INT NOT NULL,
        action_type ENUM('task_completed', 'file_uploaded', 'comment_added', 'message_sent', 'review_given') DEFAULT 'task_completed',
        points_awarded INT DEFAULT 0,
        related_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        
        FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_user_project (user_id, project_id),
        INDEX idx_action (action_type)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $connection->exec($sql);
    return true;
  }
  
  public static function down($connection) {
    $connection->exec("DROP TABLE IF EXISTS contribution_logs");
    $connection->exec("DROP TABLE IF EXISTS user_badges");
    $connection->exec("DROP TABLE IF EXISTS badges");
    return true;
  }
}
```

---

## PHASE 3: CORE AUTHENTICATION & USER MANAGEMENT

### 3.1 JWT Token Manager (src/Utils/JWT.php)

```php
<?php
namespace App\Utils;

use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;
use Exception;

class JWT {
  private static $secret;
  private static $algorithm = 'HS256';
  
  public static function init($secret) {
    self::$secret = $secret;
  }
  
  /**
   * Generate JWT token for user
   */
  public static function generate($userId, $email, $expiresIn = 86400) {
    $issuedAt = time();
    $expire = $issuedAt + $expiresIn;
    
    $payload = [
      'iat' => $issuedAt,
      'exp' => $expire,
      'userId' => $userId,
      'email' => $email,
      'iss' => $_ENV['APP_URL'] ?? 'localhost',
      'aud' => $_ENV['APP_URL'] ?? 'localhost'
    ];
    
    return FirebaseJWT::encode($payload, self::$secret, self::$algorithm);
  }
  
  /**
   * Verify and decode JWT token
   */
  public static function verify($token) {
    try {
      $decoded = FirebaseJWT::decode($token, new Key(self::$secret, self::$algorithm));
      return $decoded;
    } catch (Exception $e) {
      throw new Exception('Invalid token: ' . $e->getMessage());
    }
  }
  
  /**
   * Extract token from Authorization header
   */
  public static function getTokenFromHeader($headers) {
    if (isset($headers['Authorization'])) {
      $matches = [];
      if (preg_match('/Bearer\s+([^\s]+)/', $headers['Authorization'], $matches)) {
        return $matches[1];
      }
    }
    return null;
  }
}
```

### 3.2 Auth Service (src/Services/AuthService.php)

```php
<?php
namespace App\Services;

use App\Utils\JWT;
use App\Utils\Validator;
use App\Models\User;
use Exception;

class AuthService {
  private $db;
  
  public function __construct($database) {
    $this->db = $database;
  }
  
  /**
   * Register new user with email verification
   */
  public function register($data) {
    // Validate input
    $validator = new Validator();
    $errors = [];
    
    if (!$validator->isValidEmail($data['email'] ?? '')) {
      $errors['email'] = 'Invalid email format';
    }
    
    // Check UIU email domain
    if (!str_ends_with($data['email'], '@std.uiu.ac.bd') && 
        !str_ends_with($data['email'], '@uiu.ac.bd')) {
      $errors['email'] = 'Must use UIU email address';
    }
    
    if (strlen($data['password'] ?? '') < 8) {
      $errors['password'] = 'Password must be at least 8 characters';
    }
    
    if (!$validator->isStrongPassword($data['password'] ?? '')) {
      $errors['password'] = 'Password must contain uppercase, lowercase, number, and special character';
    }
    
    if (empty($data['full_name'])) {
      $errors['full_name'] = 'Full name is required';
    }
    
    if (!empty($errors)) {
      throw new Exception(json_encode($errors), 422);
    }
    
    // Check if email already exists
    $user = new User($this->db);
    if ($user->findByEmail($data['email'])) {
      throw new Exception('Email already registered', 409);
    }
    
    // Create user
    $verificationToken = bin2hex(random_bytes(32));
    $userId = $user->create([
      'email' => $data['email'],
      'password_hash' => password_hash($data['password'], PASSWORD_ARGON2ID),
      'full_name' => $data['full_name'],
      'department' => $data['department'] ?? null,
      'academic_year' => $data['academic_year'] ?? null,
      'skills' => json_encode($data['skills'] ?? []),
      'verification_token' => $verificationToken,
      'verification_token_expires' => date('Y-m-d H:i:s', time() + 3600 * 24) // 24 hours
    ]);
    
    // Send verification email
    $this->sendVerificationEmail($data['email'], $verificationToken);
    
    return [
      'message' => 'User registered successfully. Check email for verification link.',
      'userId' => $userId
    ];
  }
  
  /**
   * Login user and return JWT token
   */
  public function login($email, $password) {
    $user = new User($this->db);
    $userData = $user->findByEmail($email);
    
    if (!$userData) {
      throw new Exception('Invalid credentials', 401);
    }
    
    if (!password_verify($password, $userData['password_hash'])) {
      throw new Exception('Invalid credentials', 401);
    }
    
    if (!$userData['uiu_verified']) {
      throw new Exception('Email not verified. Check your inbox for verification link.', 403);
    }
    
    // Update last login
    $user->update($userData['id'], ['last_login_at' => date('Y-m-d H:i:s')]);
    
    // Generate token
    $token = JWT::generate($userData['id'], $userData['email']);
    
    return [
      'token' => $token,
      'user' => [
        'id' => $userData['id'],
        'email' => $userData['email'],
        'full_name' => $userData['full_name'],
        'avatar_url' => $userData['avatar_url'],
        'reputation' => $userData['reputation']
      ]
    ];
  }
  
  /**
   * Verify email with token
   */
  public function verifyEmail($token) {
    $user = new User($this->db);
    $userData = $user->findByVerificationToken($token);
    
    if (!$userData) {
      throw new Exception('Invalid verification token', 400);
    }
    
    if (strtotime($userData['verification_token_expires']) < time()) {
      throw new Exception('Verification token expired', 400);
    }
    
    $user->update($userData['id'], [
      'uiu_verified' => true,
      'verification_token' => null,
      'verification_token_expires' => null
    ]);
    
    return ['message' => 'Email verified successfully'];
  }
  
  /**
   * Refresh JWT token
   */
  public function refreshToken($currentToken) {
    try {
      $decoded = JWT::verify($currentToken);
      return [
        'token' => JWT::generate($decoded->userId, $decoded->email)
      ];
    } catch (Exception $e) {
      throw new Exception('Invalid or expired token', 401);
    }
  }
  
  /**
   * Send verification email
   */
  private function sendVerificationEmail($email, $token) {
    // Use PHPMailer or other email service
    // This is a placeholder
    $verificationLink = $_ENV['APP_URL'] . '/auth/verify-email?token=' . $token;
    // Send email with link
  }
}
```

### 3.3 Auth Controller (src/Controllers/AuthController.php)

```php
<?php
namespace App\Controllers;

use App\Services\AuthService;
use App\Utils\Response;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController {
  private $authService;
  
  public function __construct($database) {
    $this->authService = new AuthService($database);
  }
  
  /**
   * POST /api/auth/register
   */
  public function register(Request $request, Response $response) {
    try {
      $data = $request->getParsedBody();
      
      $result = $this->authService->register($data);
      
      return Response::json($response, $result, 201);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), $e->getCode());
    }
  }
  
  /**
   * POST /api/auth/login
   */
  public function login(Request $request, Response $response) {
    try {
      $data = $request->getParsedBody();
      
      if (empty($data['email']) || empty($data['password'])) {
        return Response::error($response, 'Email and password required', 400);
      }
      
      $result = $this->authService->login($data['email'], $data['password']);
      
      return Response::json($response, $result, 200);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), $e->getCode());
    }
  }
  
  /**
   * POST /api/auth/verify-email
   */
  public function verifyEmail(Request $request, Response $response) {
    try {
      $data = $request->getParsedBody();
      
      if (empty($data['token'])) {
        return Response::error($response, 'Token required', 400);
      }
      
      $result = $this->authService->verifyEmail($data['token']);
      
      return Response::json($response, $result, 200);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), $e->getCode());
    }
  }
  
  /**
   * POST /api/auth/refresh
   */
  public function refreshToken(Request $request, Response $response) {
    try {
      $header = $request->getHeader('Authorization')[0] ?? '';
      $token = str_replace('Bearer ', '', $header);
      
      if (!$token) {
        return Response::error($response, 'Token required', 400);
      }
      
      $result = $this->authService->refreshToken($token);
      
      return Response::json($response, $result, 200);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), $e->getCode());
    }
  }
}
```

---

## PHASE 4: API ENDPOINTS STRUCTURE

### 4.1 Routes Definition (routes/api.php)

```php
<?php
// routes/api.php

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

$app = AppFactory::create();

// Middleware
$app->add(CorsMiddleware::class);
$app->add(ErrorHandlerMiddleware::class);

// Health check (no auth needed)
$app->get('/health', function ($request, $response) {
  return Response::json($response, ['status' => 'ok'], 200);
});

// ============ AUTHENTICATION (No Auth Needed) ============
$app->group('/auth', function (RouteCollectorProxy $group) {
  $group->post('/register', 'AuthController:register');
  $group->post('/login', 'AuthController:login');
  $group->post('/verify-email', 'AuthController:verifyEmail');
  $group->post('/refresh', 'AuthController:refreshToken');
  $group->post('/forgot-password', 'AuthController:forgotPassword');
  $group->post('/reset-password', 'AuthController:resetPassword');
});

// ============ PROTECTED ROUTES (Auth Required) ============
$app->group('', function (RouteCollectorProxy $group) {
  
  // ---- USERS ----
  $group->group('/users', function (RouteCollectorProxy $sub) {
    $sub->get('', 'UserController:search');           // Search all users
    $sub->get('/{id}', 'UserController:show');        // Get user profile
    $sub->put('/{id}', 'UserController:update');      // Update profile
    $sub->get('/{id}/reputation', 'UserController:getReputation');
    $sub->get('/{id}/activity', 'UserController:getActivity');
  });
  
  // ---- PROJECTS ----
  $group->group('/projects', function (RouteCollectorProxy $sub) {
    $sub->get('', 'ProjectController:list');          // List all projects
    $sub->post('', 'ProjectController:create');       // Create project
    $sub->get('/{id}', 'ProjectController:show');     // Get project details
    $sub->put('/{id}', 'ProjectController:update');   // Update project
    $sub->delete('/{id}', 'ProjectController:delete'); // Delete project
    
    // Project members
    $sub->post('/{id}/members', 'ProjectController:addMember');
    $sub->get('/{id}/members', 'ProjectController:getMembers');
    $sub->delete('/{id}/members/{userId}', 'ProjectController:removeMember');
  });
  
  // ---- TASKS (Kanban) ----
  $group->group('/tasks', function (RouteCollectorProxy $sub) {
    $sub->get('/project/{projectId}', 'TaskController:listByProject');
    $sub->post('', 'TaskController:create');
    $sub->get('/{id}', 'TaskController:show');
    $sub->put('/{id}', 'TaskController:update');   // Including status updates
    $sub->delete('/{id}', 'TaskController:delete');
  });
  
  // ---- MESSAGES ----
  $group->group('/messages', function (RouteCollectorProxy $sub) {
    $sub->get('/conversations', 'MessageController:listConversations');
    $sub->post('/conversations', 'MessageController:createConversation');
    $sub->get('/conversations/{id}', 'MessageController:getConversation');
    $sub->post('/conversations/{id}/messages', 'MessageController:sendMessage');
    $sub->get('/conversations/{id}/messages', 'MessageController:getMessages');
  });
  
  // ---- RESOURCES (Files) ----
  $group->group('/resources', function (RouteCollectorProxy $sub) {
    $sub->get('', 'ResourceController:list');
    $sub->post('', 'ResourceController:upload');
    $sub->get('/{id}', 'ResourceController:show');
    $sub->delete('/{id}', 'ResourceController:delete');
    $sub->post('/{id}/download', 'ResourceController:download');
  });
  
  // ---- BLOG ----
  $group->group('/blog', function (RouteCollectorProxy $sub) {
    $sub->get('', 'BlogController:list');
    $sub->post('', 'BlogController:create');
    $sub->get('/{id}', 'BlogController:show');
    $sub->put('/{id}', 'BlogController:update');
    $sub->delete('/{id}', 'BlogController:delete');
    $sub->post('/{id}/comments', 'BlogController:addComment');
    $sub->post('/{id}/like', 'BlogController:like');
  });
  
  // ---- IDEAS (Innovation Hub) ----
  $group->group('/ideas', function (RouteCollectorProxy $sub) {
    $sub->get('', 'IdeaController:list');
    $sub->post('', 'IdeaController:create');
    $sub->get('/{id}', 'IdeaController:show');
    $sub->put('/{id}', 'IdeaController:update');
    $sub->post('/{id}/upvote', 'IdeaController:upvote');
    $sub->post('/{id}/join', 'IdeaController:requestToJoin');
  });
  
  // ---- NOTIFICATIONS ----
  $group->group('/notifications', function (RouteCollectorProxy $sub) {
    $sub->get('', 'NotificationController:list');
    $sub->put('/{id}/read', 'NotificationController:markRead');
    $sub->put('/read-all', 'NotificationController:markAllRead');
  });
  
  // ---- EVENTS ----
  $group->group('/events', function (RouteCollectorProxy $sub) {
    $sub->get('', 'EventController:list');
    $sub->post('', 'EventController:create');
    $sub->get('/{id}', 'EventController:show');
    $sub->post('/{id}/register', 'EventController:register');
  });
  
  // ---- CONTRIBUTIONS (Analytics) ----
  $group->group('/contributions', function (RouteCollectorProxy $sub) {
    $sub->get('/project/{projectId}', 'ContributionController:getProjectStats');
    $sub->get('/user/{userId}', 'ContributionController:getUserContributions');
  });
  
  // ---- REPUTATION ----
  $group->group('/reputation', function (RouteCollectorProxy $sub) {
    $sub->get('/leaderboard', 'ReputationController:getLeaderboard');
    $sub->get('/user/{userId}', 'ReputationController:getUserReputation');
  });
  
  // ---- DASHBOARD ----
  $group->get('/dashboard', 'DashboardController:getDashboard');
  
  // ---- CURRENT USER ----
  $group->get('/me', 'UserController:getCurrentUser');
  $group->put('/me', 'UserController:updateCurrentUser');
  
})->add(AuthMiddleware::class);  // Apply auth to all protected routes

$app->run();
```

---

## PHASE 5: IMPLEMENT ALL CONTROLLERS

### 5.1 User Controller (Complete)

```php
<?php
namespace App\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Utils\Response;

class UserController {
  private $userService;
  private $database;
  
  public function __construct($database) {
    $this->database = $database;
    $this->userService = new UserService($database);
  }
  
  /**
   * GET /api/users/:id
   */
  public function show($request, $response, $args) {
    try {
      $userId = $args['id'];
      $user = new User($this->database);
      $userData = $user->findById($userId);
      
      if (!$userData) {
        return Response::error($response, 'User not found', 404);
      }
      
      // Remove sensitive data
      unset($userData['password_hash']);
      
      return Response::json($response, $userData, 200);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), 500);
    }
  }
  
  /**
   * GET /api/users
   */
  public function search($request, $response) {
    try {
      $params = $request->getQueryParams();
      
      $query = $params['q'] ?? '';
      $department = $params['department'] ?? '';
      $page = (int)($params['page'] ?? 1);
      $perPage = (int)($params['per_page'] ?? 20);
      
      $results = $this->userService->search($query, $department, $page, $perPage);
      
      return Response::json($response, $results, 200);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), 500);
    }
  }
  
  /**
   * PUT /api/users/:id
   */
  public function update($request, $response, $args) {
    try {
      $userId = $args['id'];
      $data = $request->getParsedBody();
      
      // Check authorization
      if ($request->getAttribute('userId') != $userId) {
        return Response::error($response, 'Unauthorized', 403);
      }
      
      $user = new User($this->database);
      $user->update($userId, $data);
      
      $updated = $user->findById($userId);
      unset($updated['password_hash']);
      
      return Response::json($response, $updated, 200);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), 500);
    }
  }
  
  /**
   * GET /api/me
   */
  public function getCurrentUser($request, $response) {
    try {
      $userId = $request->getAttribute('userId');
      $user = new User($this->database);
      $userData = $user->findById($userId);
      
      unset($userData['password_hash']);
      
      return Response::json($response, $userData, 200);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), 500);
    }
  }
  
  /**
   * PUT /api/me
   */
  public function updateCurrentUser($request, $response) {
    try {
      $userId = $request->getAttribute('userId');
      $data = $request->getParsedBody();
      
      // Don't allow changing email or password through this endpoint
      unset($data['email']);
      unset($data['password_hash']);
      
      $user = new User($this->database);
      $user->update($userId, $data);
      
      $updated = $user->findById($userId);
      unset($updated['password_hash']);
      
      return Response::json($response, $updated, 200);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), 500);
    }
  }
  
  /**
   * GET /api/users/:id/reputation
   */
  public function getReputation($request, $response, $args) {
    try {
      $userId = $args['id'];
      $reputation = $this->userService->getReputationDetails($userId);
      
      return Response::json($response, $reputation, 200);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), 500);
    }
  }
  
  /**
   * GET /api/users/:id/activity
   */
  public function getActivity($request, $response, $args) {
    try {
      $userId = $args['id'];
      $params = $request->getQueryParams();
      $days = (int)($params['days'] ?? 30);
      
      $activity = $this->userService->getActivityHeatmap($userId, $days);
      
      return Response::json($response, $activity, 200);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), 500);
    }
  }
}
```

### 5.2 Project Controller (Complete)

```php
<?php
namespace App\Controllers;

use App\Models\Project;
use App\Models\ProjectMember;
use App\Services\ProjectService;
use App\Utils\Response;

class ProjectController {
  private $projectService;
  private $database;
  
  public function __construct($database) {
    $this->database = $database;
    $this->projectService = new ProjectService($database);
  }
  
  /**
   * GET /api/projects
   */
  public function list($request, $response) {
    try {
      $params = $request->getQueryParams();
      $userId = $request->getAttribute('userId');
      
      $status = $params['status'] ?? '';
      $page = (int)($params['page'] ?? 1);
      $perPage = (int)($params['per_page'] ?? 20);
      
      $projects = $this->projectService->listUserProjects($userId, $status, $page, $perPage);
      
      return Response::json($response, $projects, 200);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), 500);
    }
  }
  
  /**
   * POST /api/projects
   */
  public function create($request, $response) {
    try {
      $data = $request->getParsedBody();
      $userId = $request->getAttribute('userId');
      
      // Validate required fields
      if (empty($data['title'])) {
        return Response::error($response, 'Title is required', 400);
      }
      
      $data['created_by'] = $userId;
      
      $projectId = $this->projectService->createProject($data);
      
      // Add creator as owner
      $projectMember = new ProjectMember($this->database);
      $projectMember->create([
        'project_id' => $projectId,
        'user_id' => $userId,
        'role' => 'owner'
      ]);
      
      $project = (new Project($this->database))->findById($projectId);
      
      return Response::json($response, $project, 201);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), 500);
    }
  }
  
  /**
   * GET /api/projects/:id
   */
  public function show($request, $response, $args) {
    try {
      $projectId = $args['id'];
      $userId = $request->getAttribute('userId');
      
      $project = new Project($this->database);
      $projectData = $project->findById($projectId);
      
      if (!$projectData) {
        return Response::error($response, 'Project not found', 404);
      }
      
      // Check access permission
      if ($projectData['visibility'] === 'private') {
        if (!$this->projectService->isMember($projectId, $userId)) {
          return Response::error($response, 'Unauthorized', 403);
        }
      }
      
      return Response::json($response, $projectData, 200);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), 500);
    }
  }
  
  /**
   * PUT /api/projects/:id
   */
  public function update($request, $response, $args) {
    try {
      $projectId = $args['id'];
      $userId = $request->getAttribute('userId');
      $data = $request->getParsedBody();
      
      // Check authorization (owner only)
      if (!$this->projectService->isOwner($projectId, $userId)) {
        return Response::error($response, 'Only project owner can update', 403);
      }
      
      $this->projectService->updateProject($projectId, $data);
      
      $project = (new Project($this->database))->findById($projectId);
      
      return Response::json($response, $project, 200);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), 500);
    }
  }
  
  /**
   * DELETE /api/projects/:id
   */
  public function delete($request, $response, $args) {
    try {
      $projectId = $args['id'];
      $userId = $request->getAttribute('userId');
      
      // Check authorization
      if (!$this->projectService->isOwner($projectId, $userId)) {
        return Response::error($response, 'Only project owner can delete', 403);
      }
      
      $this->projectService->deleteProject($projectId);
      
      return Response::json($response, ['message' => 'Project deleted'], 200);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), 500);
    }
  }
  
  /**
   * POST /api/projects/:id/members
   */
  public function addMember($request, $response, $args) {
    try {
      $projectId = $args['id'];
      $userId = $request->getAttribute('userId');
      $data = $request->getParsedBody();
      
      // Check authorization
      if (!$this->projectService->isOwner($projectId, $userId)) {
        return Response::error($response, 'Only project owner can add members', 403);
      }
      
      $newMemberId = $data['user_id'];
      
      $projectMember = new ProjectMember($this->database);
      $projectMember->create([
        'project_id' => $projectId,
        'user_id' => $newMemberId,
        'role' => $data['role'] ?? 'member'
      ]);
      
      return Response::json($response, ['message' => 'Member added'], 201);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), 500);
    }
  }
  
  /**
   * GET /api/projects/:id/members
   */
  public function getMembers($request, $response, $args) {
    try {
      $projectId = $args['id'];
      
      $members = $this->projectService->getProjectMembers($projectId);
      
      return Response::json($response, $members, 200);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), 500);
    }
  }
  
  /**
   * DELETE /api/projects/:id/members/:userId
   */
  public function removeMember($request, $response, $args) {
    try {
      $projectId = $args['id'];
      $memberId = $args['userId'];
      $userId = $request->getAttribute('userId');
      
      // Check authorization
      if (!$this->projectService->isOwner($projectId, $userId)) {
        return Response::error($response, 'Only project owner can remove members', 403);
      }
      
      $projectMember = new ProjectMember($this->database);
      $projectMember->delete($projectId, $memberId);
      
      return Response::json($response, ['message' => 'Member removed'], 200);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), 500);
    }
  }
}
```

### 5.3 Task Controller (Complete)

```php
<?php
namespace App\Controllers;

use App\Models\Task;
use App\Services\TaskService;
use App\Utils\Response;

class TaskController {
  private $taskService;
  private $database;
  
  public function __construct($database) {
    $this->database = $database;
    $this->taskService = new TaskService($database);
  }
  
  /**
   * GET /api/tasks/project/:projectId
   */
  public function listByProject($request, $response, $args) {
    try {
      $projectId = $args['projectId'];
      $userId = $request->getAttribute('userId');
      
      // Check access
      if (!$this->taskService->canAccessProject($projectId, $userId)) {
        return Response::error($response, 'Unauthorized', 403);
      }
      
      $tasks = $this->taskService->getProjectTasks($projectId);
      
      return Response::json($response, $tasks, 200);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), 500);
    }
  }
  
  /**
   * POST /api/tasks
   */
  public function create($request, $response) {
    try {
      $data = $request->getParsedBody();
      $userId = $request->getAttribute('userId');
      
      if (empty($data['project_id']) || empty($data['title'])) {
        return Response::error($response, 'Project ID and title are required', 400);
      }
      
      // Check access
      if (!$this->taskService->canAccessProject($data['project_id'], $userId)) {
        return Response::error($response, 'Unauthorized', 403);
      }
      
      $data['created_by'] = $userId;
      
      $taskId = $this->taskService->createTask($data);
      $task = (new Task($this->database))->findById($taskId);
      
      return Response::json($response, $task, 201);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), 500);
    }
  }
  
  /**
   * GET /api/tasks/:id
   */
  public function show($request, $response, $args) {
    try {
      $taskId = $args['id'];
      $userId = $request->getAttribute('userId');
      
      $task = new Task($this->database);
      $taskData = $task->findById($taskId);
      
      if (!$taskData) {
        return Response::error($response, 'Task not found', 404);
      }
      
      // Check access
      if (!$this->taskService->canAccessProject($taskData['project_id'], $userId)) {
        return Response::error($response, 'Unauthorized', 403);
      }
      
      return Response::json($response, $taskData, 200);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), 500);
    }
  }
  
  /**
   * PUT /api/tasks/:id
   */
  public function update($request, $response, $args) {
    try {
      $taskId = $args['id'];
      $userId = $request->getAttribute('userId');
      $data = $request->getParsedBody();
      
      $task = new Task($this->database);
      $taskData = $task->findById($taskId);
      
      if (!$taskData) {
        return Response::error($response, 'Task not found', 404);
      }
      
      // Check access
      if (!$this->taskService->canAccessProject($taskData['project_id'], $userId)) {
        return Response::error($response, 'Unauthorized', 403);
      }
      
      $this->taskService->updateTask($taskId, $data);
      
      $updated = $task->findById($taskId);
      
      return Response::json($response, $updated, 200);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), 500);
    }
  }
  
  /**
   * DELETE /api/tasks/:id
   */
  public function delete($request, $response, $args) {
    try {
      $taskId = $args['id'];
      $userId = $request->getAttribute('userId');
      
      $task = new Task($this->database);
      $taskData = $task->findById($taskId);
      
      if (!$taskData) {
        return Response::error($response, 'Task not found', 404);
      }
      
      // Check access
      if (!$this->taskService->canAccessProject($taskData['project_id'], $userId)) {
        return Response::error($response, 'Unauthorized', 403);
      }
      
      $this->taskService->deleteTask($taskId);
      
      return Response::json($response, ['message' => 'Task deleted'], 200);
    } catch (Exception $e) {
      return Response::error($response, $e->getMessage(), 500);
    }
  }
}
```

---

## PHASE 6: REMAINING CONTROLLERS (Outline)

### 6.1 Message Controller
```php
- listConversations(userId) → List user conversations
- createConversation(title, participantIds) → Create new conv
- getConversation(conversationId) → Full conversation details
- sendMessage(conversationId, content) → Send message
- getMessages(conversationId, page) → Paginated messages
```

### 6.2 Resource Controller
```php
- list(page, filter) → List resources
- upload(file, project_id, title) → Upload file with validation
- show(id) → Get resource details
- delete(id, userId) → Delete resource (owner only)
- download(id) → Increment download count
```

### 6.3 Blog Controller
```php
- list(page, category) → Paginated blog posts
- create(title, content, userId) → Create post
- show(id) → Get full post + comments
- update(id, userId, data) → Update post
- addComment(id, content, userId) → Add comment
- like(id, userId) → Toggle like
```

### 6.4 Idea Controller
```php
- list(page, status) → List ideas
- create(title, description, userId) → Create idea
- upvote(id, userId) → Toggle upvote
- requestToJoin(id, userId) → Request to join idea
```

### 6.5 Notification Controller
```php
- list(userId, page) → User notifications
- markRead(id) → Mark as read
- markAllRead(userId) → Mark all read
```

### 6.6 Reputation Controller
```php
- getLeaderboard(limit) → Top users by reputation
- getUserReputation(userId) → User's reputation details
- getBadges(userId) → User's badges
```

### 6.7 Contribution Controller
```php
- getProjectStats(projectId) → Project contribution stats
- getUserContributions(userId) → User's contributions
```

### 6.8 Dashboard Controller
```php
- getDashboard(userId) → All dashboard stats
```

---

## PHASE 7: MIDDLEWARE & UTILITIES

### 7.1 Auth Middleware (src/Middleware/AuthMiddleware.php)

```php
<?php
namespace App\Middleware;

use App\Utils\JWT;
use App\Utils\Response;

class AuthMiddleware {
  public function __invoke($request, $handler) {
    $headers = getallheaders();
    $token = JWT::getTokenFromHeader($headers);
    
    if (!$token) {
      $response = new \Slim\Psr7\Response();
      return Response::error($response, 'Token required', 401);
    }
    
    try {
      $decoded = JWT::verify($token);
      $request = $request
        ->withAttribute('userId', $decoded->userId)
        ->withAttribute('email', $decoded->email);
    } catch (\Exception $e) {
      $response = new \Slim\Psr7\Response();
      return Response::error($response, 'Invalid token', 401);
    }
    
    return $handler->handle($request);
  }
}
```

### 7.2 Response Utility (src/Utils/Response.php)

```php
<?php
namespace App\Utils;

class Response {
  public static function json($response, $data, $status = 200) {
    $response->getBody()->write(json_encode($data));
    return $response
      ->withHeader('Content-Type', 'application/json')
      ->withStatus($status);
  }
  
  public static function error($response, $message, $status = 400) {
    return self::json($response, ['error' => $message], $status);
  }
}
```

### 7.3 Validator Utility (src/Utils/Validator.php)

```php
<?php
namespace App\Utils;

class Validator {
  public static function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
  }
  
  public static function isStrongPassword($password) {
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password);
  }
  
  public static function isValidUrl($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
  }
  
  public static function validateProjectData($data) {
    $errors = [];
    
    if (empty($data['title'])) {
      $errors['title'] = 'Title is required';
    }
    
    if (strlen($data['title'] ?? '') > 255) {
      $errors['title'] = 'Title too long';
    }
    
    return empty($errors) ? true : $errors;
  }
}
```

---

## PHASE 8: TESTING FRAMEWORK

### 8.1 PHPUnit Configuration (phpunit.xml)

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         bootstrap="tests/bootstrap.php"
         colors="true"
         stopOnFailure="false">
  <testsuites>
    <testsuite name="Unit">
      <directory>tests/Unit</directory>
    </testsuite>
    <testsuite name="Integration">
      <directory>tests/Integration</directory>
    </testsuite>
  </testsuites>
  
  <coverage processUncoveredFiles="true">
    <include>
      <directory suffix=".php">src</directory>
    </include>
    <exclude>
      <directory>tests</directory>
    </exclude>
  </coverage>
</phpunit>
```

### 8.2 Sample Unit Test

```php
<?php
// tests/Unit/AuthServiceTest.php

use PHPUnit\Framework\TestCase;
use App\Services\AuthService;

class AuthServiceTest extends TestCase {
  private $authService;
  
  protected function setUp(): void {
    // Mock database
    $this->authService = new AuthService($mockDb);
  }
  
  public function testRegisterValidation() {
    $result = $this->authService->register([
      'email' => 'invalid-email',
      'password' => 'weak'
    ]);
    
    $this->assertIsArray($result['errors']);
  }
  
  public function testLoginWithValidCredentials() {
    $result = $this->authService->login('user@uiu.ac.bd', 'ValidPassword123!');
    
    $this->assertArrayHasKey('token', $result);
    $this->assertArrayHasKey('user', $result);
  }
}
```

---

## PHASE 9: DOCKER & DEPLOYMENT

### 9.1 Dockerfile

```dockerfile
FROM php:8.1-fpm-alpine

# Install extensions
RUN docker-php-ext-install pdo_mysql bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev

EXPOSE 9000

CMD ["php-fpm"]
```

### 9.2 docker-compose.yml

```yaml
version: '3.8'

services:
  web:
    build: .
    ports:
      - "8000:80"
    environment:
      - DB_HOST=db
      - DB_USER=root
      - DB_PASSWORD=secret
      - DB_NAME=uiu_portal
      - JWT_SECRET=your-secret-key-here
    depends_on:
      - db
    volumes:
      - .:/app
      - ./storage/uploads:/app/storage/uploads

  db:
    image: mysql:8.0
    environment:
      - MYSQL_ROOT_PASSWORD=secret
      - MYSQL_DATABASE=uiu_portal
    ports:
      - "3306:3306"
    volumes:
      - db_data:/var/lib/mysql

  phpmyadmin:
    image: phpmyadmin
    ports:
      - "8080:80"
    environment:
      - PMA_HOST=db
      - PMA_USER=root
      - PMA_PASSWORD=secret
    depends_on:
      - db

volumes:
  db_data:
```

---

## PHASE 10: IMPLEMENTATION CHECKLIST

### Core Implementation Tasks

**Phase 1 - Setup (Week 1)**
- [ ] Create project directory structure
- [ ] Set up Git repository
- [ ] Create composer.json with dependencies
- [ ] Set up .env configuration
- [ ] Create database connection class

**Phase 2 - Database (Week 2)**
- [ ] Create all migration files (10 migrations)
- [ ] Create database seeders
- [ ] Create migration runner script
- [ ] Verify database schema

**Phase 3 - Authentication (Week 2-3)**
- [ ] Implement JWT utility class
- [ ] Create Auth service
- [ ] Create Auth controller
- [ ] Implement Auth middleware
- [ ] Test registration/login flow
- [ ] Set up email verification

**Phase 4 - User Management (Week 3)**
- [ ] Create User model
- [ ] Create UserService
- [ ] Implement UserController
- [ ] Create search/filter functionality
- [ ] Set up user profile endpoints

**Phase 5 - Projects & Tasks (Week 4)**
- [ ] Create Project model
- [ ] Create ProjectService
- [ ] Implement ProjectController
- [ ] Create Task model
- [ ] Create TaskService
- [ ] Implement TaskController
- [ ] Set up Kanban board endpoints

**Phase 6 - Messaging (Week 5)**
- [ ] Create Message models
- [ ] Implement MessageController
- [ ] Create conversation logic
- [ ] Set up pagination for messages

**Phase 7 - Resources & Files (Week 5)**
- [ ] Create file upload handling
- [ ] Implement storage service
- [ ] Create ResourceController
- [ ] Set up file validation

**Phase 8 - Blog & Ideas (Week 6)**
- [ ] Implement BlogController
- [ ] Create comment system
- [ ] Implement IdeaController
- [ ] Set up upvoting system

**Phase 9 - Analytics & Gamification (Week 6-7)**
- [ ] Create ReputationService
- [ ] Implement badge system
- [ ] Create ContributionController
- [ ] Implement dashboard endpoints

**Phase 10 - Testing & Polish (Week 7-8)**
- [ ] Write unit tests
- [ ] Write integration tests
- [ ] Set up CI/CD pipeline
- [ ] Performance optimization
- [ ] Security audit
- [ ] API documentation

---

## NEXT STEPS FOR AI AGENT

**To execute this prompt effectively:**

1. **Start with Phase 1-2:** Set up the project structure and database migrations
2. **Test the setup:** Run migrations and verify database schema
3. **Build Authentication:** Implement JWT and auth controller
4. **Implement Users:** Create User model and controller
5. **Build Projects & Tasks:** Core feature implementation
6. **Implement Remaining Features:** Message, Blog, Ideas, Resources
7. **Add Services:** Create business logic layer
8. **Write Tests:** Unit and integration tests
9. **Document:** Create API documentation
10. **Deploy:** Set up Docker and CI/CD

**Each iteration should:**
- Include complete, working code
- Have proper error handling
- Follow security best practices
- Include database constraints
- Be thoroughly tested
- Have clear documentation

---

**This prompt is designed to be executed iteratively. Start with Phase 1-2, test thoroughly, then move to next phases.**

**Total Estimated Duration:** 4-6 weeks of continuous development  
**Team Size:** 1-2 senior PHP developers  
**Success Metric:** All 14 feature modules with working REST API endpoints

