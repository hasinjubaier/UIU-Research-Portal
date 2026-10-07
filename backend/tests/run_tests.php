<?php
/**
 * Test Runner Script - Executes Unit and Integration test suites
 */

require_once __DIR__ . '/bootstrap.php';

use Dotenv\Dotenv;
use App\Utils\JWT;
use App\Utils\Validator;
use App\Services\AuthService;
use App\Services\UserService;
use App\Services\ProjectService;
use App\Services\TaskService;
use App\Database\Connection;
use Tests\Integration\AuthApiTest;
use Tests\Integration\ProjectApiTest;
use Tests\Integration\FileUploadTest;

$pdo = Connection::getInstance();
$passed = 0;
$failed = 0;

$testCase = function(string $category, string $description, callable $test) use (&$passed, &$failed) {
    try {
        $test();
        echo sprintf("  [PASS] [%s] %s\n", $category, $description);
        $passed++;
    } catch (\Throwable $e) {
        echo sprintf("  [FAIL] [%s] %s: %s\n", $category, $description, $e->getMessage());
        $failed++;
    }
};

$runMethodOnTestCase = function(object $testInstance, string $methodName) {
    $refSetup = new ReflectionMethod($testInstance, 'setUp');
    $refSetup->setAccessible(true);
    $refSetup->invoke($testInstance);

    $expectedEx = null;
    $thrown = null;

    try {
        $testInstance->$methodName();
    } catch (\Throwable $e) {
        $thrown = $e;
    }

    if (method_exists($testInstance, 'getExpectedException')) {
        $expectedEx = $testInstance->getExpectedException();
    }

    if (method_exists($testInstance, 'tearDown')) {
        $refTeardown = new ReflectionMethod($testInstance, 'tearDown');
        $refTeardown->setAccessible(true);
        $refTeardown->invoke($testInstance);
    }

    if ($expectedEx !== null) {
        if ($thrown === null) {
            throw new \AssertionError("Expected exception {$expectedEx} was not thrown.");
        }
        if (!($thrown instanceof $expectedEx)) {
            throw new \AssertionError("Expected exception {$expectedEx}, but got " . get_class($thrown) . ": " . $thrown->getMessage());
        }
    } else {
        if ($thrown !== null) {
            throw $thrown;
        }
    }
};

echo "\n=========================================\n";
echo "   UIU Research Portal - Backend Tests   \n";
echo "=========================================\n\n";

echo "--- 1. Unit Tests ---\n";

$testCase('UNIT', 'JWT Token Encoding & Decoding', function() {
    $payload = ['sub' => 123, 'role' => 'Researcher'];
    $token = JWT::encode($payload, 3600);
    $decoded = JWT::decode($token);
    assert($decoded !== null, 'Token should decode');
    assert($decoded['sub'] === 123, 'Subject should match');
});

$testCase('UNIT', 'Validator Rules Validation', function() {
    $v = Validator::make(['email' => 'valid@uiu.ac.bd'], ['email' => 'required|email']);
    $res = $v->validate();
    assert($res['email'] === 'valid@uiu.ac.bd');
});

$testCase('UNIT', 'AuthService Login with seeded credentials', function() use ($pdo) {
    $auth = new AuthService($pdo);
    $res = $auth->login('rafsan.ahmed@uiu.ac.bd', 'password123');
    assert(!empty($res['token']), 'Should return JWT token');
    assert($res['user']['name'] === 'Rafsan Ahmed', 'Should return Rafsan profile');
});

$testCase('UNIT', 'UserService Profile Retrieval', function() use ($pdo) {
    $user = new UserService($pdo);
    $profile = $user->getProfile(1);
    assert($profile['name'] === 'Rafsan Ahmed', 'User 1 should be Rafsan');
    assert($profile['department'] === 'Computer Science & Engineering');
});

$testCase('UNIT', 'ProjectService List and Filter', function() use ($pdo) {
    $proj = new ProjectService($pdo);
    $list = $proj->list(1, 10);
    assert(count($list['data']) > 0, 'Should return projects');
});

$testCase('UNIT', 'TaskService Kanban Board Retrieval', function() use ($pdo) {
    $task = new TaskService($pdo);
    $kanban = $task->getKanban(1);
    assert(isset($kanban['todo']), 'Kanban should have todo column');
    assert(isset($kanban['inProgress']), 'Kanban should have inProgress column');
    assert(isset($kanban['done']), 'Kanban should have done column');
});

echo "\n--- 2. Integration Tests ---\n";

$testCase('INTEGRATION', 'Auth API Login Endpoint (POST /api/auth/login)', function() use ($runMethodOnTestCase) {
    $runMethodOnTestCase(new AuthApiTest(), 'testLoginSuccessEndpoint');
});

$testCase('INTEGRATION', 'Auth API Auth Failure (Invalid Credentials)', function() use ($runMethodOnTestCase) {
    $runMethodOnTestCase(new AuthApiTest(), 'testLoginInvalidPasswordEndpoint');
});

$testCase('INTEGRATION', 'Auth API Current User Endpoint (GET /api/auth/me)', function() use ($runMethodOnTestCase) {
    $runMethodOnTestCase(new AuthApiTest(), 'testGetMeWithValidToken');
});

$testCase('INTEGRATION', 'Project API List Endpoint (GET /api/projects)', function() use ($runMethodOnTestCase) {
    $runMethodOnTestCase(new ProjectApiTest(), 'testGetProjectsListEndpoint');
});

$testCase('INTEGRATION', 'Project API Single Project Detail (GET /api/projects/1)', function() use ($runMethodOnTestCase) {
    $runMethodOnTestCase(new ProjectApiTest(), 'testGetProjectDetailEndpoint');
});

$testCase('INTEGRATION', 'Project API Create Project with Bearer Token', function() use ($runMethodOnTestCase) {
    $runMethodOnTestCase(new ProjectApiTest(), 'testCreateProjectSuccessWithAuth');
});

$testCase('INTEGRATION', 'File Upload Handling & Validation', function() use ($runMethodOnTestCase) {
    $runMethodOnTestCase(new FileUploadTest(), 'testUploadSuccessWithAllowedExtension');
});

$testCase('INTEGRATION', 'File Upload Rejection on Invalid Extension', function() use ($runMethodOnTestCase) {
    $runMethodOnTestCase(new FileUploadTest(), 'testUploadFailsWithDisallowedExtension');
});

$testCase('INTEGRATION', 'File Upload Rejection on Excessive Size', function() use ($runMethodOnTestCase) {
    $runMethodOnTestCase(new FileUploadTest(), 'testUploadFailsWithExcessiveSize');
});

echo "\n=========================================\n";
echo "Test Summary: {$passed} passed, {$failed} failed.\n";
echo "=========================================\n\n";

exit($failed > 0 ? 1 : 0);
