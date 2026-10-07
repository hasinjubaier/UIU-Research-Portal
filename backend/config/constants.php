<?php
/**
 * Application Constants & Enums
 */

// ── User ──
if (!defined('ACADEMIC_YEARS')) {
    define('ACADEMIC_YEARS', ['1st', '2nd', '3rd', '4th', 'Masters', 'PhD']);
}
if (!defined('USER_STATUSES')) {
    define('USER_STATUSES', ['online', 'offline', 'away']);
}
if (!defined('USER_ROLES')) {
    define('USER_ROLES', ['student', 'faculty', 'researcher', 'admin']);
}

// ── Projects ──
if (!defined('PROJECT_STATUSES')) {
    define('PROJECT_STATUSES', ['planning', 'active', 'writing', 'under_review', 'completed']);
}
if (!defined('PROJECT_TYPES')) {
    define('PROJECT_TYPES', ['thesis', 'capstone', 'journal', 'conference', 'independent']);
}
if (!defined('MEMBER_ROLES')) {
    define('MEMBER_ROLES', ['lead', 'co-author', 'contributor', 'advisor']);
}

// ── Tasks ──
if (!defined('TASK_STATUSES')) {
    define('TASK_STATUSES', ['backlog', 'todo', 'in_progress', 'review', 'done']);
}
if (!defined('TASK_PRIORITIES')) {
    define('TASK_PRIORITIES', ['low', 'medium', 'high', 'urgent']);
}

// ── Resources ──
if (!defined('RESOURCE_TYPES')) {
    define('RESOURCE_TYPES', ['paper', 'dataset', 'code', 'presentation', 'proposal', 'other']);
}

// ── Blog ──
if (!defined('BLOG_CATEGORIES')) {
    define('BLOG_CATEGORIES', ['Research Methods', 'Machine Learning', 'Academic Writing', 'Events', 'General']);
}

// ── Innovation Hub / Ideas ──
if (!defined('IDEA_CATEGORIES')) {
    define('IDEA_CATEGORIES', ['AI/ML', 'IoT', 'Healthcare', 'Blockchain', 'Cybersecurity', 'NLP', 'Robotics', 'Other']);
}
if (!defined('IDEA_STATUSES')) {
    define('IDEA_STATUSES', ['open', 'in_discussion', 'forming_team', 'converted_to_project']);
}

// ── Events ──
if (!defined('EVENT_TYPES')) {
    define('EVENT_TYPES', ['workshop', 'seminar', 'hackathon', 'conference', 'webinar', 'defense']);
}

// ── Notifications ──
if (!defined('NOTIFICATION_TYPES')) {
    define('NOTIFICATION_TYPES', ['task_assigned', 'message_received', 'project_invite', 'comment_added', 'badge_earned', 'event_reminder', 'system']);
}
