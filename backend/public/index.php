<?php
/**
 * UIU Research Portal - Front Controller
 */

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Middleware\CorsMiddleware;
use App\Middleware\ErrorHandlerMiddleware;
use App\Middleware\RateLimitMiddleware;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

// Instantiate Slim App
$app = AppFactory::create();

// Add request body parser (json, form-data)
$app->addBodyParsingMiddleware();

// Add custom routing and error handling middleware
$app->addRoutingMiddleware();

// Add middlewares (LIFO order: ErrorHandler wraps RateLimit wraps CORS)
$app->add(new RateLimitMiddleware(200, 60));
$app->add(new CorsMiddleware());
$app->add(new ErrorHandlerMiddleware());

// Register API Routes
$routes = require __DIR__ . '/../routes/api.php';
$routes($app);

// Run Application
$app->run();
