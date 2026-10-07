<?php
/**
 * Standalone WebSocket Server Runner
 * Usage: php bin/websocket.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\WebSocket\ChatHandler;

echo "====================================================\n";
echo " UIU Research Portal - Real-Time WebSocket Server\n";
echo " Listening on port 8080 (ws://localhost:8080)\n";
echo "====================================================\n";

$chatHandler = new ChatHandler();

// Basic socket server wrapper
$server = @stream_socket_server("tcp://0.0.0.0:8080", $errno, $errstr);
if (!$server) {
    echo "WebSocket server startup error: {$errstr} ({$errno})\n";
    exit(1);
}

echo "WebSocket server successfully initiated on port 8080.\n";
