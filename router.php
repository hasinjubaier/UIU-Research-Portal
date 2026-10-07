<?php
/**
 * UIU Research Portal — Unified Server Router
 * 
 * Serves both Frontend (HTML/CSS/JS) and Backend (REST API) on a single port.
 * Cleanly decouples:
 * - Frontend: /frontend (served at root http://localhost:8000/ and subpages)
 * - Backend API: /backend/public (served at http://localhost:8000/api/...)
 * - Database: /database (isolated SQL schema & seeds)
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// ── 1. Backend REST API Routing ──
if (str_starts_with($uri, '/api') || $uri === '/api') {
    require __DIR__ . '/backend/public/index.php';
    return;
}

// ── 2. Security Shield: Block sensitive files, dotfiles, database and backend internals ──
if (
    str_starts_with($uri, '/backend') ||
    str_starts_with($uri, '/database') ||
    str_starts_with($uri, '/.') ||
    str_contains($uri, '/.') ||
    str_starts_with($uri, '/vendor') ||
    str_starts_with($uri, '/tests') ||
    preg_match('/\.(env|git|lock|sql|bat|ps1|md)$/i', $uri)
) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Forbidden']);
    return;
}

// ── 3. Frontend Static File & Page Routing ──
$frontendDir = __DIR__ . '/frontend';

// If URI starts with /frontend/, normalize it
$cleanUri = preg_replace('#^/frontend#', '', $uri);
if ($cleanUri === '') {
    $cleanUri = '/';
}

$filePath = $frontendDir . $cleanUri;

// Exact static file exists in frontend/ (css, js, images, html, etc.)
if ($cleanUri !== '/' && file_exists($filePath) && !is_dir($filePath)) {
    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $mimes = [
        'css'   => 'text/css; charset=UTF-8',
        'js'    => 'application/javascript; charset=UTF-8',
        'json'  => 'application/json; charset=UTF-8',
        'png'   => 'image/png',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'gif'   => 'image/gif',
        'svg'   => 'image/svg+xml',
        'ico'   => 'image/x-icon',
        'woff'  => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf'   => 'font/ttf',
        'html'  => 'text/html; charset=UTF-8',
    ];
    if (isset($mimes[$ext])) {
        header("Content-Type: {$mimes[$ext]}");
    }
    readfile($filePath);
    return;
}

// URL without .html extension (e.g. /projects/workspace -> /projects/workspace.html)
if ($cleanUri !== '/' && file_exists($filePath . '.html')) {
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
if ($cleanUri === '/' || $cleanUri === '') {
    header('Content-Type: text/html; charset=UTF-8');
    readfile($frontendDir . '/index.html');
    return;
}

// 404 Fallback
http_response_code(404);
echo "<!DOCTYPE html><html><head><title>404 Not Found</title></head><body><h1>404 - Page Not Found</h1><p>The requested URL <code>" . htmlspecialchars($uri) . "</code> was not found on this server.</p><a href='/'>Return to Home</a></body></html>";
