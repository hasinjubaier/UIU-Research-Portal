<?php

use App\Controllers\AuthController;
use App\Controllers\BlogController;
use App\Controllers\CollaboratorController;
use App\Controllers\ContributionController;
use App\Controllers\DashboardController;
use App\Controllers\EventController;
use App\Controllers\IdeaController;
use App\Controllers\MessageController;
use App\Controllers\NotificationController;
use App\Controllers\ProjectController;
use App\Controllers\ReputationController;
use App\Controllers\ResourceController;
use App\Controllers\TaskController;
use App\Controllers\UserController;
use App\Middleware\AuthMiddleware;
use App\Utils\Response;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->group('/api', function (RouteCollectorProxy $api) {

        // Health check endpoint
        $api->get('/health', function ($request, $response) {
            return Response::json($response, [
                'status'    => 'ok',
                'service'   => 'UIU Research Portal API',
                'timestamp' => date('c'),
            ], 200);
        });

        // ── Authentication ──
        $api->group('/auth', function (RouteCollectorProxy $auth) {
            $auth->post('/register', [AuthController::class, 'register']);
            $auth->post('/login', [AuthController::class, 'login']);
            $auth->post('/forgot-password', [AuthController::class, 'forgotPassword']);
            $auth->post('/reset-password', [AuthController::class, 'resetPassword']);
            $auth->get('/verify-email', [AuthController::class, 'verifyEmail']);
            $auth->get('/me', [AuthController::class, 'me'])->add(new AuthMiddleware(true));
            $auth->post('/logout', [AuthController::class, 'logout']);
        });

        // ── Users / Students ──
        $api->group('/users', function (RouteCollectorProxy $users) {
            $users->get('', [UserController::class, 'list']);
            $users->get('/leaderboard', [UserController::class, 'leaderboard']);
            $users->get('/{id}', [UserController::class, 'show']);
            $users->put('/{id}', [UserController::class, 'update'])->add(new AuthMiddleware(false));
        });

        // ── Projects ──
        $api->group('/projects', function (RouteCollectorProxy $projects) {
            $projects->get('', [ProjectController::class, 'list']);
            $projects->get('/{id}', [ProjectController::class, 'show']);
            $projects->post('', [ProjectController::class, 'create'])->add(new AuthMiddleware(false));
            $projects->put('/{id}', [ProjectController::class, 'update'])->add(new AuthMiddleware(false));
            $projects->delete('/{id}', [ProjectController::class, 'delete'])->add(new AuthMiddleware(false));
            $projects->post('/{id}/members', [ProjectController::class, 'addMember'])->add(new AuthMiddleware(false));
            $projects->delete('/{id}/members/{userId}', [ProjectController::class, 'removeMember'])->add(new AuthMiddleware(false));

            // Tasks nested under project
            $projects->get('/{projectId}/tasks', [TaskController::class, 'listByProject']);
            $projects->post('/{projectId}/tasks', [TaskController::class, 'create'])->add(new AuthMiddleware(false));
        });

        // ── Tasks Direct ──
        $api->group('/tasks', function (RouteCollectorProxy $tasks) {
            $tasks->put('/{id}', [TaskController::class, 'update'])->add(new AuthMiddleware(false));
            $tasks->patch('/{id}/status', [TaskController::class, 'updateStatus'])->add(new AuthMiddleware(false));
            $tasks->delete('/{id}', [TaskController::class, 'delete'])->add(new AuthMiddleware(false));
        });

        // ── Messaging & Conversations ──
        $api->group('/conversations', function (RouteCollectorProxy $conv) {
            $conv->get('', [MessageController::class, 'listConversations']);
            $conv->post('/dm', [MessageController::class, 'startDm']);
            $conv->get('/{id}/messages', [MessageController::class, 'getMessages']);
            $conv->post('/{id}/messages', [MessageController::class, 'sendMessage']);
            $conv->post('/{id}/read', [MessageController::class, 'markRead']);
        })->add(new AuthMiddleware(true));

        // ── Resources & Datasets ──
        $api->group('/resources', function (RouteCollectorProxy $resources) {
            $resources->get('', [ResourceController::class, 'list']);
            $resources->get('/{id}', [ResourceController::class, 'show']);
            $resources->post('', [ResourceController::class, 'create'])->add(new AuthMiddleware(false));
            $resources->post('/{id}/download', [ResourceController::class, 'download']);
            $resources->delete('/{id}', [ResourceController::class, 'delete'])->add(new AuthMiddleware(false));
        });

        // ── Blog Posts ──
        $api->group('/blogs', function (RouteCollectorProxy $blogs) {
            $blogs->get('', [BlogController::class, 'list']);
            $blogs->get('/{id}', [BlogController::class, 'show']);
            $blogs->post('', [BlogController::class, 'create'])->add(new AuthMiddleware(false));
            $blogs->post('/{id}/like', [BlogController::class, 'toggleLike'])->add(new AuthMiddleware(false));
            $blogs->post('/{id}/comments', [BlogController::class, 'addComment'])->add(new AuthMiddleware(false));
            $blogs->delete('/{id}', [BlogController::class, 'delete'])->add(new AuthMiddleware(false));
        });

        // ── Research Ideas / Innovation Hub ──
        $api->group('/ideas', function (RouteCollectorProxy $ideas) {
            $ideas->get('', [IdeaController::class, 'list']);
            $ideas->get('/{id}', [IdeaController::class, 'show']);
            $ideas->post('', [IdeaController::class, 'create'])->add(new AuthMiddleware(false));
            $ideas->post('/{id}/upvote', [IdeaController::class, 'toggleUpvote'])->add(new AuthMiddleware(false));
            $ideas->post('/{id}/comments', [IdeaController::class, 'addComment'])->add(new AuthMiddleware(false));
            $ideas->delete('/{id}', [IdeaController::class, 'delete'])->add(new AuthMiddleware(false));
        });

        // ── Contributions ──
        $api->get('/contributions/project/{projectId}', [ContributionController::class, 'getByProject']);

        // ── Reputation & Badges ──
        $api->get('/reputation/breakdown', [ReputationController::class, 'getBreakdown'])->add(new AuthMiddleware(true));
        $api->get('/badges', [ReputationController::class, 'getBadges'])->add(new AuthMiddleware(true));
        $api->post('/badges/{badgeId}/award', [ReputationController::class, 'awardBadge'])->add(new AuthMiddleware(false));

        // ── Notifications ──
        $api->group('/notifications', function (RouteCollectorProxy $notifs) {
            $notifs->get('', [NotificationController::class, 'list']);
            $notifs->patch('/{id}/read', [NotificationController::class, 'markRead']);
            $notifs->post('/read-all', [NotificationController::class, 'markAllRead']);
        })->add(new AuthMiddleware(true));

        // ── Events ──
        $api->group('/events', function (RouteCollectorProxy $events) {
            $events->get('', [EventController::class, 'list']);
            $events->get('/{id}', [EventController::class, 'show']);
            $events->post('', [EventController::class, 'create'])->add(new AuthMiddleware(false));
            $events->post('/{id}/register', [EventController::class, 'register'])->add(new AuthMiddleware(false));
            $events->delete('/{id}', [EventController::class, 'delete'])->add(new AuthMiddleware(false));
        });

        // ── Collaborators ──
        $api->get('/collaborators', [CollaboratorController::class, 'list']);
        $api->post('/collaborators/request', [CollaboratorController::class, 'request'])->add(new AuthMiddleware(true));

        // ── Dashboard & Global Search ──
        $api->get('/dashboard', [DashboardController::class, 'index'])->add(new AuthMiddleware(true));
        $api->get('/search', [DashboardController::class, 'search']);
    });
};
