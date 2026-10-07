# UIU Research Portal: Technical Documentation & Logic Workflow

This document provides a comprehensive A-Z breakdown of the **UIU Research Portal**'s logic, workflow, data flow, and technical architecture. It is designed to help you understand exactly how the project works behind the scenes so you can easily answer faculty questions during your presentation.

---

## Table of Contents
1. [Architecture & Data Flow](#1-architecture--data-flow)
2. [Global Application Logic (app.js)](#2-global-application-logic-appjs)
3. [Mock Database Structure (data.js)](#3-mock-database-structure-datajs)
4. [Module Workflows (Feature by Feature)](#4-module-workflows-feature-by-feature)
5. [UI/UX & Design Logic](#5-uiux--design-logic)
6. [Phase 2: Backend Transition Plan](#6-phase-2-backend-transition-plan)

---

## 1. Architecture & Data Flow

Currently, the application operates strictly as a **Client-Side Application** (Frontend only). 

### How Data Flows (Current State Phase 1)
1. **The Data Store:** All application data is stored in memory via a global JavaScript object named `DATA` inside `assets/js/data.js`. This acts as a mock database.
2. **The Logic Controller:** `assets/js/app.js` acts as the primary controller. It listens for user interactions (clicks, scrolls, typing) and updates the UI accordingly.
3. **The View (HTML):** The HTML files load the structure. When a page loads, JavaScript reads the `DATA` object and dynamically injects content (like sidebar user info, notification counts, or project lists) into the DOM (Document Object Model).
4. **State Persistence:** Because there is no real database yet, changes made by the user (like sending a message or creating a task) are only saved temporarily in the browser's memory. If the page is refreshed, the data resets to the original state in `data.js`. The only exception is the **Dark/Light Theme preference**, which is saved persistently in the browser's `localStorage`.

---

## 2. Global Application Logic (`app.js`)

The `app.js` file handles all the core mechanics that make the portal interactive. If your faculty asks *how* a specific UI element works, refer to this section.

### Core Managers & Functions:
*   **`ThemeManager`**: Checks `localStorage` on page load to see if the user prefers dark or light mode. It applies a `data-theme` attribute to the `<html>` tag, which CSS uses to swap color variables.
*   **`Sidebar` & `buildSidebar()`**: Dynamically generates the sidebar HTML based on who is logged in (reads from `DATA.currentUser`) and how many unread notifications they have. It also checks the current URL path to automatically highlight the active navigation link.
*   **`Modal` Manager**: A generic system for popup modals. Instead of writing custom JS for every popup, any button with `data-modal-open="id"` will automatically find and display the modal with that ID. It also locks the background scroll (`document.body.style.overflow = 'hidden'`).
*   **`Tabs` Logic**: Listens for clicks on elements with the `.tab-item` class. When clicked, it hides all content panels and only displays the panel that matches the clicked tab's `data-tab` attribute.
*   **`ScrollReveal` & `Counter`**: Uses the modern JavaScript `IntersectionObserver` API. Instead of running a heavy scroll event listener, the browser mathematically calculates when an element enters the screen. When it does, it triggers CSS animations or starts counting numbers up from zero.
*   **`IC` (Icon Library)**: Instead of loading external font icons (like FontAwesome), all icons are stored as SVG strings inside a JavaScript object. This makes the site load incredibly fast and ensures icons always look sharp.

---

## 3. Mock Database Structure (`data.js`)

This file simulates what the MySQL database will eventually look like. It contains JSON-formatted arrays representing different database tables.

### Key Data Entities:
*   **`currentUser`**: Simulates the active session. Contains the logged-in user's stats, badges, and profile info.
*   **`students`**: An array of other users on the platform. Used for the "Find Collaborators" page. Includes properties like `skills`, `interests`, and `status` (online/offline).
*   **`projects` & `tasks`**: Projects contain metadata (title, domain, progress). Tasks are separated into `todo`, `inProgress`, and `done` arrays, simulating a Kanban board workflow.
*   **`resources` & `datasets`**: Simulates uploaded files, tracking file sizes, download counts, and ratings.
*   **`leaderboard` & `badges`**: Drives the gamification engine, ranking users by reputation points earned through platform activity.

---

## 4. Module Workflows (Feature by Feature)

If your faculty points to a specific page and asks "What is the logic here?", use these explanations:

### A. Authentication (Login/Register)
*   **Logic:** Currently standard HTML forms.
*   **Future Logic:** Will use PHP sessions. The registration will require a valid `@uiu.ac.bd` email address, enforced via Regex on the frontend and server-side validation.

### B. Find Collaborators
*   **Workflow:** Users search or filter a list of students. 
*   **Logic:** The `filterCards()` function in `app.js` listens to the search input. Every time a key is pressed, it converts the input to lowercase and checks if the student cards contain that text. If not, it applies `style="display: none"` to hide the card.

### C. Project Workspace (Kanban)
*   **Workflow:** A dashboard for managing a specific research project.
*   **Logic:** The Kanban board visually represents the `tasks` object from `data.js`. Currently, it uses CSS Grid for layout. In the future, HTML5 Drag-and-Drop API will be implemented to let users drag tasks between columns.

### D. Innovation Hub (Ideas)
*   **Workflow:** Students post research ideas, and others can upvote them.
*   **Data Flow:** Ideas are pulled from the `DATA.ideas` array. Each idea has an `upvotes` integer. 

### E. Contributions & Analytics
*   **Logic:** This module relies on **Chart.js**. The HTML canvas elements are targeted by JS, which pulls raw numbers from `DATA.contributions` (e.g., how many edits, uploads, or tasks a student completed) and renders them into interactive Bar and Doughnut charts.

---

## 5. Directory & File Breakdown

Based on the project structure, here is exactly what each folder and its HTML file does:

*   **`assets/`**: The core engine of the frontend.
    *   `css/`: Contains styling. `global.css` for variables, `components.css` for reusable cards/buttons, `animations.css` for keyframes.
    *   `js/app.js`: Controls sidebar toggles, modals, theme switching, and global interactivity.
    *   `js/data.js`: The mock database storing arrays of users, projects, and ideas.
*   **`auth/`**: Authentication module.
    *   `login.html`: The entry point for students to log into the portal.
    *   `register.html`: A multi-step registration wizard requiring UIU credentials and skill tagging.
*   **`blog/`**: Knowledge Sharing module.
    *   `index.html`: Lists academic articles, tutorials, and research summaries written by students.
*   **`collaborators/`**: The matchmaking engine.
    *   `index.html`: Displays student profiles with filters for department, skills, and online status to find project partners.
*   **`contributions/`**: The analytics engine.
    *   `index.html`: Uses Chart.js to visually display how much work each team member has done on a project (edits, uploads, tasks).
*   **`dashboard/`**: The post-login landing page.
    *   `index.html`: Provides a personalized overview, showing recent notifications, ongoing project progress, and quick action buttons.
*   **`datasets/`**: Research Data hub.
    *   `index.html`: A repository where students upload, share, and download datasets (e.g., CSVs, model weights) for experiments.
*   **`editor/`**: Collaborative Writing module.
    *   `index.html`: Integrates a rich-text editor (Quill.js) allowing multiple students to draft research papers together.
*   **`events/`**: Academic Calendar.
    *   `index.html`: Lists upcoming hackathons, workshops, and conferences with countdown timers and registration options.
*   **`ideas/`**: Innovation Hub.
    *   `index.html`: A Reddit-style board where students pitch research ideas, and others can upvote or request to join the project.
*   **`messages/`**: Communication module.
    *   `index.html`: A split-panel chat interface for direct messaging and group project chats.
*   **`notifications/`**: Alerts center.
    *   `index.html`: A feed of system alerts, project updates, and collaboration requests.
*   **`profile/`**: User Identity.
    *   `index.html`: Displays a student's portfolio, earned badges, skills, and a GitHub-style activity heatmap.
*   **`projects/`**: Project Management.
    *   `index.html`: Lists all active/completed research projects the user is involved in.
    *   `workspace.html`: The core Kanban board and file-sharing interface for managing a specific project.
*   **`reputation/`**: Gamification system.
    *   `index.html`: Displays a podium and leaderboard ranking students based on their contribution points.
*   **`resources/`**: Academic Library.
    *   `index.html`: A hub for sharing PDFs, research papers, and study materials.
*   **`index.html`** (Root): The marketing landing page for the portal, explaining features and offering login/registration CTAs to unauthenticated users.

---

## 6. UI/UX & Design Logic

The project follows modern design principles to ensure a premium feel:
*   **Glassmorphism:** The top bar and modals use CSS `backdrop-filter: blur(16px)` to create a frosted glass effect over the background.
*   **CSS Variables (Tokens):** All colors are defined at the top of `global.css` as variables (e.g., `--primary`, `--bg-base`). When dark mode is toggled, these variables simply swap values, instantly changing the entire site's color scheme without needing new CSS classes.
*   **Responsive Grid:** Pages like "Projects" and "Resources" use CSS `grid-template-columns: repeat(auto-fill, minmax(300px, 1fr))`. This mathematical formula tells the browser to automatically fit as many cards as possible on a row, making it perfectly responsive on mobile, tablet, and desktop without complex media queries.

---

## 7. Phase 2: Backend Transition Plan

When asked *how* you will connect this to a database, you can explain this roadmap:

1.  **Database Creation:** Convert the arrays in `data.js` into actual relational MySQL tables (e.g., a `users` table, a `projects` table, and a junction table `project_members` for many-to-many relationships).
2.  **API Endpoints:** Create PHP scripts (like `get_projects.php` or `create_task.php`) that connect to the database using secure PDO queries.
3.  **AJAX/Fetch Implementation:** Modify the Vanilla JS to use the `fetch()` API. Instead of reading `DATA.projects`, the JS will ping `get_projects.php`, receive JSON data from the server, and then inject it into the HTML exactly like it does now.
4.  **Session Management:** Implement PHP `$_SESSION` variables to remember who logged in and ensure secure routing.
