<?php
/**
 * UIU Research Portal — Unified Server Router
 * 
 * Serves both Frontend (HTML/CSS/JS) and Backend (REST API) on a single port.
 * Example URL: http://localhost:8000
 * - Frontend: http://localhost:8000/
 * - Backend API: http://localhost:8000/api/...
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// ── 1. Backend REST API Routing ──
if (str_starts_with($uri, '/api') || $uri === '/api') {
    require __DIR__ . '/backend/public/index.php';
    return;
}

// ── 2. Static File & Page Routing ──
$filePath = __DIR__ . $uri;

// Exact file exists (css, js, images, html, etc.)
if ($uri !== '/' && file_exists($filePath) && !is_dir($filePath)) {
    return false; // Let PHP built-in server serve the static file directly
}

// URL without .html extension (e.g. /projects/workspace -> /projects/workspace.html)
if ($uri !== '/' && file_exists($filePath . '.html')) {
    header('Content-Type: text/html; charset=UTF-8');
    readfile($filePath . '.html');
    return;
}

// Directory requested with index.html inside (e.g. /dashboard or /dashboard/)
if (is_dir($filePath)) {
    $indexFile = rtrim($filePath, '/') . '/index.html';
    if (file_exists($indexFile)) {
        header('Content-Type: text/html; charset=UTF-8');
        readfile($indexFile);
        return;
    }
}

// Root page
if ($uri === '/' || $uri === '') {
    header('Content-Type: text/html; charset=UTF-8');
    readfile(__DIR__ . '/index.html');
    return;
}

// 404 Fallback
http_response_code(404);
echo "<!DOCTYPE html><html><head><title>404 Not Found</title></head><body><h1>404 - Page Not Found</h1><p>The requested URL <code>" . htmlspecialchars($uri) . "</code> was not found on this server.</p><a href='/'>Return to Home</a></body></html>";
