# UIU Academic Research Collaboration Portal

**University:** United International University (UIU)
**Type:** Web-based Multi-user Academic Platform
**Stack:** HTML5 · CSS3 · Vanilla JavaScript · PHP/MySQL *(Phase 2)*
**Version:** 1.0.0 — Frontend Complete

---

## Table of Contents

1. [Project Overview](#1-project-overview)
2. [Features & Modules](#2-features--modules)
3. [File Structure](#3-file-structure)
4. [How to Run](#4-how-to-run)
5. [Page Reference Guide](#5-page-reference-guide)
6. [Design System](#6-design-system)
7. [Technologies Used](#7-technologies-used)
8. [Recent Updates](#8-recent-updates)
9. [Backend Plan (Phase 2)](#9-backend-plan-phase-2)

---

## 1. Project Overview

The **UIU Research Collaboration Portal** is a centralized web platform designed for students of United International University to:

- Find research collaborators across all departments
- Manage research projects using a full workspace toolkit (Kanban, tasks, files, chat)
- Write and co-author research papers with a collaborative rich-text editor
- Share academic resources, datasets, and code
- Discover and post research ideas in the Innovation Hub
- Build academic portfolios through blogs, badges, and a reputation system
- Track team contributions fairly and transparently with visual analytics

### Target Users

| Role | Description |
|---|---|
| Undergraduate Students | Find collaborators, join projects, access resources |
| Graduate Students | Lead projects, post ideas, publish research |
| Faculty *(Phase 2)* | Mentor students, supervise projects |

---

## 2. Features & Modules

| # | Module | Page | Status |
|---|---|---|---|
| 1 | User Management | `auth/login.html`, `auth/register.html`, `profile/` | Frontend Complete |
| 2 | Collaboration Finder | `collaborators/` | Frontend Complete |
| 3 | Project Workspace | `projects/` | Frontend Complete |
| 4 | Research Editor | `editor/` | Frontend Complete |
| 5 | Resource Hub | `resources/` | Frontend Complete |
| 6 | Messaging | `messages/` | Frontend Complete |
| 7 | Blog & Knowledge Sharing | `blog/` | Frontend Complete |
| 8 | Contribution Tracking | `contributions/` | Frontend Complete |
| 9 | Reputation & Leaderboard | `reputation/` | Frontend Complete |
| 10 | Security & Trust | UIU email validation, public/private projects | Frontend Complete |
| 11 | Notification System | `notifications/` | Frontend Complete |
| 12 | Events & Opportunities | `events/` | Frontend Complete |
| 13 | Innovation Hub | `ideas/` | Frontend Complete |
| 14 | Dataset Hub | `datasets/` | Frontend Complete |

---

## 3. File Structure

```
Project/
│
├── index.html                     # Landing / Marketing page
│
├── auth/
│   ├── login.html                 # Sign in page
│   └── register.html              # 3-step registration wizard
│
├── dashboard/
│   └── index.html                 # Main dashboard (post-login home)
│
├── profile/
│   └── index.html                 # User profile, skills, badges, heatmap
│
├── collaborators/
│   └── index.html                 # Find research collaborators
│
├── projects/
│   ├── index.html                 # Project listing
│   └── workspace.html             # Project workspace (Kanban, chat, files)
│
├── editor/
│   └── index.html                 # Collaborative research editor (Quill.js)
│
├── resources/
│   └── index.html                 # Academic resource hub
│
├── datasets/
│   └── index.html                 # Dataset & experiment hub
│
├── messages/
│   └── index.html                 # Direct & group messaging
│
├── blog/
│   ├── index.html                 # Blog listing
│   └── post.html                  # Full blog post view + comments
│
├── contributions/
│   └── index.html                 # Contribution charts and analytics
│
├── reputation/
│   └── index.html                 # Leaderboard & reputation points
│
├── events/
│   └── index.html                 # Events, hackathons, conferences
│
├── ideas/
│   └── index.html                 # Innovation Hub (research ideas)
│
├── notifications/
│   └── index.html                 # Notification center
│
├── README.md                      # This file
│
└── assets/
    ├── css/
    │   ├── global.css             # Design tokens, typography, layout, reset
    │   ├── components.css         # Buttons, cards, sidebar, forms, modals, kanban
    │   └── animations.css         # Keyframes, scroll reveal, transitions
    └── js/
        ├── app.js                 # Global logic — theme, sidebar (SVG icons),
        │                          #   modals, toasts, tabs, counters
        └── data.js                # Mock data store for all 14 modules
```

---

## 4. How to Run

### Phase 1 — Frontend Only (Current State)

> No server, no installation, no terminal required.

**Step 1.** Download or unzip the project folder.

**Step 2.** Open File Explorer and navigate to the `Project/` folder.

**Step 3.** Double-click **`index.html`** — it opens directly in Chrome or Edge.

**Step 4.** Click **"Get Started Free"** or **"View Demo Dashboard"** to explore all pages.

> Tested browsers: Google Chrome, Microsoft Edge, Firefox
> Internet connection is only needed to load Google Fonts and CDN libraries (Chart.js, Quill.js).

---

### Phase 2 — Full Stack with PHP + MySQL

When the backend is ready, use a local server environment:

**Requirements:**
- PHP 8.1+
- MySQL 8.0+
- XAMPP / WAMP / Laragon (Windows)

**Setup Steps:**

```
1. Copy the Project/ folder to your server root:
   XAMPP  →  C:/xampp/htdocs/uiu-portal/
   Laragon →  C:/laragon/www/uiu-portal/

2. Open phpMyAdmin:
   - Create a database named: uiu_portal
   - Import: database/uiu_portal.sql

3. Edit config/db.php with your credentials:
   $host = 'localhost';
   $db   = 'uiu_portal';
   $user = 'root';
   $pass = '';

4. Start Apache + MySQL in XAMPP/Laragon.

5. Open browser: http://localhost/uiu-portal/
```

---

## 5. Page Reference Guide

| Page | File Path | Key Features |
|---|---|---|
| Landing | `index.html` | Hero, features grid, how-it-works, CTA |
| Login | `auth/login.html` | UIU email login, demo access |
| Register | `auth/register.html` | 3-step wizard, tag-input skills, UIU email validation |
| Dashboard | `dashboard/index.html` | Welcome banner, stats, quick actions, activity feed |
| Profile | `profile/index.html` | Cover photo, contribution heatmap, badges (earned/locked), tabs |
| Find Collaborators | `collaborators/index.html` | Filter sidebar, search, online status, send request |
| Projects | `projects/index.html` | Tab-filtered listing, stat cards, create project modal |
| Workspace | `projects/workspace.html` | Kanban board, milestone timeline, file sharing, team chat |
| Research Editor | `editor/index.html` | Quill.js editor, auto-save, citation insert, version history |
| Resource Hub | `resources/index.html` | Category filter, search, download cards, upload modal |
| Dataset Hub | `datasets/index.html` | Dataset cards, fork/star buttons, upload modal |
| Messages | `messages/index.html` | Split-panel chat, conversation list, unread badges |
| Blog | `blog/index.html` | Featured post, category tabs, like toggle, write modal |
| Blog Post | `blog/post.html` | Full post, table of contents, comment system, related posts |
| Innovation Hub | `ideas/index.html` | Upvote system, join requests, post idea modal |
| Events | `events/index.html` | Live countdown timer, type filter, register button |
| Contributions | `contributions/index.html` | Chart.js bar & doughnut charts, per-member breakdown |
| Reputation | `reputation/index.html` | Visual podium, full leaderboard, points chart |
| Notifications | `notifications/index.html` | Unread highlight, mark-all-read, filter by type |

---

## 6. Design System

### Color Palette

| Token | Hex | Usage |
|---|---|---|
| `--primary` | `#1A2B6B` | UIU Navy — brand primary |
| `--accent` | `#4F8EF7` | Electric Blue — links, buttons, active states |
| `--purple` | `#7C3AED` | Secondary actions, badges |
| `--green` | `#10B981` | Success states, online indicators |
| `--orange` | `#F59E0B` | Warnings, event badges |
| `--red` | `#EF4444` | Errors, unread counts |
| `--teal` | `#06B6D4` | Accent variant |
| `--bg-base` | `#0A0E1A` | Page background (dark mode) |
| `--bg-card` | `#131d35` | Card & panel backgrounds |
| `--bg-surface` | `#0f1628` | Sidebar, secondary panels |

### Typography

| Role | Font | Weight |
|---|---|---|
| Headings, Logo | Space Grotesk | 700 – 800 |
| Body, UI | Inter | 400 – 600 |

Both loaded from Google Fonts CDN.

### Themes

- **Dark Mode** — default on all pages
- **Light Mode** — toggled via the sun/moon icon in the topbar
- Preference saved in `localStorage` and persists across sessions

### Key UI Patterns

| Pattern | Implementation |
|---|---|
| Sidebar icons | Inline SVG stroke icons (feather style) — no emoji |
| Glassmorphism | `backdrop-filter: blur(16px)` on topbar and modals |
| Animated counters | IntersectionObserver + `requestAnimationFrame` |
| Scroll reveal | IntersectionObserver `.reveal` class + CSS `translateY` |
| Gradient text | `background-clip: text` with accent-to-purple gradient |
| Kanban board | CSS grid columns, drag-ready task cards |
| Live countdown | `setInterval` updating Days / Hours / Mins / Secs |

---

## 7. Technologies Used

### Frontend

| Technology | Version | Purpose |
|---|---|---|
| HTML5 | — | Semantic page structure |
| CSS3 | — | Design system, animations, responsive layout |
| JavaScript | ES6+ | Interactivity, dynamic rendering, state |
| Chart.js | 4.x (CDN) | Bar, doughnut, and line charts |
| Quill.js | 1.3.7 (CDN) | Collaborative rich text editor |
| Google Fonts | — | Inter + Space Grotesk typography |

### Backend (Phase 2 — Planned)

| Technology | Purpose |
|---|---|
| PHP 8.1 | Server-side routing and API logic |
| MySQL 8.0 | Relational database for all modules |
| PDO | Secure parameterized database queries |
| PHPMailer | UIU email verification system |
| WebSockets / Polling | Real-time chat and notifications |

---

## 8. Recent Updates

### v1.0.0 — May 2025

- **Professional SVG icons** — Replaced all emoji navigation icons with clean stroke-based SVG icons (feather style). Icons are monochrome and change color on hover/active states.
- **Sidebar reorganized** — Sections renamed more logically: *Overview*, *Collaboration*, *Research*, *Community*, *Analytics*.
- **"Idea Marketplace" renamed** — Now called **Innovation Hub** throughout the entire platform.
- **Select dropdown fix** — Department and Academic Year dropdowns in registration now correctly show dark background in dark mode. Each `<option>` has explicit background and color applied.
- **Theme toggle icons** — Sun and moon emoji replaced with proper SVG sun/moon icons.
- **Stat card icons** — Updated to show proper accent colors matching their category.

---

## 9. Full Stack & Backend Implementation Plan (Phase 2)

### Core Technology Stack (Backend)
- **Server-side Language:** PHP 8.1+ (Object-Oriented structure, MVC pattern)
- **Database:** MySQL 8.0+ (Relational Database Management System)
- **Database Connection:** PDO (PHP Data Objects) for secure, parameterized queries
- **Authentication:** PHP Sessions, `password_hash()`, JWT (JSON Web Tokens) for API routes
- **Real-time Communication:** Ratchet (PHP WebSockets) or Pusher API for live chat, notifications, and active states
- **Email Service:** PHPMailer via SMTP for UIU domain (`@std.uiu.ac.bd`) email verification and notifications
- **File Storage:** Local server file system with optimized directory structure for datasets, documents, and resources

### Detailed Feature Specifications & Technologies

#### 1. User Management & Authentication
- **Description:** Secure registration, login wizard, profile management, and UIU email verification.
- **Technologies:** PHP Sessions for state, `password_hash()` for security. PHPMailer integrated with UIU's SMTP (or Google Workspace SMTP) to send one-time OTPs and verification links.
- **Data Models:** `users`, `user_skills`, `password_resets`.

#### 2. Collaboration Finder
- **Description:** Search and filter engine to find peers by department, skills, and research interests.
- **Technologies:** MySQL Full-Text Search indexing. Frontend Fetch API (AJAX) calls PHP endpoints to retrieve JSON data and update the UI in real-time without page reloads.
- **Data Models:** `users`, `skills`.

#### 3. Project Workspace (Kanban & Management)
- **Description:** Central hub for projects featuring a Kanban board, timeline, and member management.
- **Technologies:** RESTful PHP API. When a user drags a Kanban card, JS sends an AJAX `PUT` request to update the task status in MySQL.
- **Data Models:** `projects`, `project_members`, `tasks` (status: todo, in_progress, review, done).

#### 4. Research Editor
- **Description:** Collaborative rich-text editor for co-authoring papers.
- **Technologies:** Quill.js (frontend). PHP handles periodic AJAX auto-saving. For real-time co-authoring visibility (seeing who is typing), WebSockets (Ratchet PHP) will broadcast cursor positions and deltas.
- **Data Models:** `documents`, `document_versions` (saving deltas for version history).

#### 5. Resource & Dataset Hubs
- **Description:** Repositories for academic papers, datasets, and code.
- **Technologies:** PHP `$_FILES` handling, strict MIME type validation, file size limits. Files stored securely outside the public web root or accessed via read-scripts to prevent unauthorized access.
- **Data Models:** `resources`, `datasets`, `file_metadata`.

#### 6. Messaging System
- **Description:** Direct and group messaging for project teams.
- **Technologies:** WebSockets (Ratchet) for instant message delivery. Messages are appended to the DOM immediately and asynchronously saved to MySQL via PHP. Fallback to AJAX long-polling if WebSockets fail.
- **Data Models:** `conversations`, `messages`, `conversation_members`.

#### 7. Blog & Knowledge Sharing
- **Description:** Platform for publishing research articles, tutorials, and comments.
- **Technologies:** PHP CRUD operations. A backend Markdown parser (like Parsedown) safely renders user content while preventing XSS attacks.
- **Data Models:** `blog_posts`, `blog_comments`, `post_likes`.

#### 8. Contribution Tracking & Analytics
- **Description:** Visual representation of member contributions within a project.
- **Technologies:** PHP backend algorithms aggregate data (tasks completed, files uploaded, messages sent). This data is passed as a JSON object to Chart.js to render dynamic bar and doughnut charts.
- **Data Models:** `contributions`, `task_history`.

#### 9. Reputation & Leaderboard
- **Description:** Gamification system awarding points for academic activities.
- **Technologies:** Event-driven PHP architecture. Completing actions triggers functions that increment the user's score in the database and unlock badges based on thresholds.
- **Data Models:** `reputation_log`, `badges`, `user_badges`.

#### 10. Innovation Hub
- **Description:** Platform to post, upvote research ideas, and request to join them.
- **Technologies:** PHP endpoints handling upvotes. Unique constraints in MySQL prevent double voting. AJAX ensures a seamless "like" animation.
- **Data Models:** `ideas`, `idea_upvotes`, `idea_join_requests`.

#### 11. Notification System
- **Description:** Global alerts for messages, invites, and mentions.
- **Technologies:** WebSockets push notifications instantly to connected clients. If offline, notifications are stored in the database and fetched via AJAX on next login.
- **Data Models:** `notifications` (boolean `is_read`).

#### 12. Events & Opportunities
- **Description:** Directory of hackathons, workshops, and conferences.
- **Technologies:** PHP `DateTime` objects calculate countdowns. Background cron jobs can automatically archive past events.
- **Data Models:** `events`, `event_registrations`.

### Planned Database Schema Overview

```sql
users               (id, name, email, password_hash, dept, reputation, created_at)
projects            (id, title, description, status, created_by)
project_members     (project_id, user_id, role, joined_at)
tasks               (id, project_id, title, status, assignee_id, due_date)
resources           (id, title, category, file_path, uploaded_by)
datasets            (id, title, size, format, file_path, uploaded_by)
blog_posts          (id, author_id, title, content, published_at)
ideas               (id, author_id, title, description, upvotes)
messages            (id, conversation_id, sender_id, content, sent_at)
notifications       (id, user_id, type, message, is_read, created_at)
```

### Implementation Roadmap

| Phase | Focus | Tasks |
|---|---|---|
| **2A** | Database & Architecture | Design normalized schema, setup MVC folder structure, configure PDO |
| **2B** | Authentication | Register, login, session management, UIU email verification (PHPMailer) |
| **2C** | User Profiles & Dashboard | Fetch dynamic user data, display stats, calculate reputation points |
| **2D** | Projects & Tasks | CRUD for projects, Kanban drag-and-drop state saving via AJAX API |
| **2E** | Content Hubs | Secure file uploads for Resources/Datasets, Blog parsing logic |
| **2F** | Interaction Systems | Innovation Hub upvoting, commenting, sending project invites |
| **2G** | Real-time Features | Implement WebSockets for instant Messaging and live Notifications |
| **2H** | Analytics & Leaderboard | Calculate contribution scores, generate JSON for Chart.js, issue badges |
| **2I** | Security & Optimization | SQL injection prevention, XSS filtering, CSRF tokens, indexing |
| **2J** | Deployment | Move to VPS hosting, configure domain (`portal.uiu.ac.bd`), SSL setup |

---

## Credits

| | |
|---|---|
| University | United International University (UIU) |
| Platform | Academic Research Collaboration Portal |
| Version | 1.0.0 (Frontend) |
| Year | 2025 |

---

> **Quick Start:** Open `index.html` in Chrome and click **"Get Started Free"** to explore all 18 pages of the platform.
