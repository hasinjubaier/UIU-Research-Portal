# Backend Code Generation Templates & Quick Reference

**For AI Agent:** Use these templates as boilerplate when generating code for each module.

---

## MODEL TEMPLATE (All Models Follow This Pattern)

```php
<?php
// src/Models/ModelName.php

namespace App\Models;

class ModelName {
  protected $table = 'table_name';
  protected $fillable = ['field1', 'field2', 'field3'];
  protected $primaryKey = 'id';
  
  private $connection;
  
  public function __construct($database) {
    $this->connection = $database;
  }
  
  /**
   * Find by primary key
   */
  public function findById($id) {
    $stmt = $this->connection->prepare("
      SELECT * FROM {$this->table} 
      WHERE id = ? AND deleted_at IS NULL
    ");
    $stmt->execute([$id]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }
  
  /**
   * Create new record
   */
  public function create($data) {
    $fields = array_intersect_key($data, array_flip($this->fillable));
    $fields = array_merge($fields, ['created_at' => date('Y-m-d H:i:s')]);
    
    $columns = implode(', ', array_keys($fields));
    $placeholders = implode(', ', array_fill(0, count($fields), '?'));
    
    $stmt = $this->connection->prepare("
      INSERT INTO {$this->table} ({$columns}) 
      VALUES ({$placeholders})
    ");
    
    $stmt->execute(array_values($fields));
    
    return $this->connection->lastInsertId();
  }
  
  /**
   * Update record
   */
  public function update($id, $data) {
    $fields = array_intersect_key($data, array_flip($this->fillable));
    $fields['updated_at'] = date('Y-m-d H:i:s');
    
    $sets = [];
    $values = [];
    
    foreach ($fields as $column => $value) {
      $sets[] = "{$column} = ?";
      $values[] = $value;
    }
    
    $values[] = $id;
    
    $stmt = $this->connection->prepare("
      UPDATE {$this->table} 
      SET " . implode(', ', $sets) . "
      WHERE id = ?
    ");
    
    return $stmt->execute($values);
  }
  
  /**
   * Soft delete
   */
  public function delete($id) {
    $stmt = $this->connection->prepare("
      UPDATE {$this->table} 
      SET deleted_at = ? 
      WHERE id = ?
    ");
    
    return $stmt->execute([date('Y-m-d H:i:s'), $id]);
  }
  
  /**
   * List with pagination
   */
  public function paginate($page = 1, $perPage = 20) {
    $offset = ($page - 1) * $perPage;
    
    $stmt = $this->connection->prepare("
      SELECT * FROM {$this->table} 
      WHERE deleted_at IS NULL
      ORDER BY created_at DESC
      LIMIT ? OFFSET ?
    ");
    
    $stmt->execute([$perPage, $offset]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }
  
  /**
   * Count total records
   */
  public function count() {
    $stmt = $this->connection->prepare("
      SELECT COUNT(*) FROM {$this->table} 
      WHERE deleted_at IS NULL
    ");
    $stmt->execute();
    return (int)$stmt->fetchColumn();
  }
}
```

---

## SERVICE TEMPLATE (Business Logic)

```php
<?php
// src/Services/ServiceName.php

namespace App\Services;

use App\Models\ModelName;
use App\Utils\Logger;
use Exception;

class ServiceName {
  private $database;
  private $model;
  
  public function __construct($database) {
    $this->database = $database;
    $this->model = new ModelName($database);
  }
  
  /**
   * List with business logic
   */
  public function list($page = 1, $perPage = 20, $filters = []) {
    try {
      $records = $this->model->paginate($page, $perPage);
      $total = $this->model->count();
      
      return [
        'data' => $records,
        'pagination' => [
          'page' => $page,
          'per_page' => $perPage,
          'total' => $total,
          'pages' => ceil($total / $perPage)
        ]
      ];
    } catch (Exception $e) {
      Logger::error('List operation failed', $e);
      throw $e;
    }
  }
  
  /**
   * Create with validation
   */
  public function create($data) {
    try {
      // Validate
      $this->validate($data);
      
      // Create
      $id = $this->model->create($data);
      
      // Log
      Logger::info("Record created: {$id}");
      
      return $id;
    } catch (Exception $e) {
      Logger::error('Create operation failed', $e);
      throw $e;
    }
  }
  
  /**
   * Update with validation
   */
  public function update($id, $data) {
    try {
      // Check exists
      $record = $this->model->findById($id);
      if (!$record) {
        throw new Exception('Record not found', 404);
      }
      
      // Validate
      $this->validate($data);
      
      // Update
      $this->model->update($id, $data);
      
      Logger::info("Record updated: {$id}");
      
      return true;
    } catch (Exception $e) {
      Logger::error('Update operation failed', $e);
      throw $e;
    }
  }
  
  /**
   * Delete with checks
   */
  public function delete($id) {
    try {
      $record = $this->model->findById($id);
      if (!$record) {
        throw new Exception('Record not found', 404);
      }
      
      $this->model->delete($id);
      
      Logger::info("Record deleted: {$id}");
      
      return true;
    } catch (Exception $e) {
      Logger::error('Delete operation failed', $e);
      throw $e;
    }
  }
  
  /**
   * Validate input
   */
  protected function validate($data) {
    $errors = [];
    
    // Add specific validations here
    
    if (!empty($errors)) {
      throw new Exception(json_encode($errors), 422);
    }
  }
}
```

---

## CONTROLLER TEMPLATE (Request Handlers)

```php
<?php
// src/Controllers/ControllerName.php

namespace App\Controllers;

use App\Services\ServiceName;
use App\Utils\Response;
use Exception;

class ControllerName {
  private $service;
  
  public function __construct($database) {
    $this->service = new ServiceName($database);
  }
  
  /**
   * GET /api/resource
   */
  public function list($request, $response) {
    try {
      $params = $request->getQueryParams();
      $page = (int)($params['page'] ?? 1);
      $perPage = (int)($params['per_page'] ?? 20);
      
      $result = $this->service->list($page, $perPage);
      
      return Response::json($response, $result, 200);
    } catch (Exception $e) {
      return $this->handleError($response, $e);
    }
  }
  
  /**
   * POST /api/resource
   */
  public function create($request, $response) {
    try {
      $data = $request->getParsedBody();
      $userId = $request->getAttribute('userId');
      
      $data['user_id'] = $userId;
      
      $id = $this->service->create($data);
      
      return Response::json($response, ['id' => $id], 201);
    } catch (Exception $e) {
      return $this->handleError($response, $e);
    }
  }
  
  /**
   * GET /api/resource/:id
   */
  public function show($request, $response, $args) {
    try {
      $id = $args['id'];
      
      $resource = $this->service->get($id);
      
      if (!$resource) {
        return Response::error($response, 'Not found', 404);
      }
      
      return Response::json($response, $resource, 200);
    } catch (Exception $e) {
      return $this->handleError($response, $e);
    }
  }
  
  /**
   * PUT /api/resource/:id
   */
  public function update($request, $response, $args) {
    try {
      $id = $args['id'];
      $data = $request->getParsedBody();
      $userId = $request->getAttribute('userId');
      
      // Check authorization
      if (!$this->service->canUpdate($id, $userId)) {
        return Response::error($response, 'Unauthorized', 403);
      }
      
      $this->service->update($id, $data);
      
      return Response::json($response, ['message' => 'Updated'], 200);
    } catch (Exception $e) {
      return $this->handleError($response, $e);
    }
  }
  
  /**
   * DELETE /api/resource/:id
   */
  public function delete($request, $response, $args) {
    try {
      $id = $args['id'];
      $userId = $request->getAttribute('userId');
      
      // Check authorization
      if (!$this->service->canDelete($id, $userId)) {
        return Response::error($response, 'Unauthorized', 403);
      }
      
      $this->service->delete($id);
      
      return Response::json($response, ['message' => 'Deleted'], 200);
    } catch (Exception $e) {
      return $this->handleError($response, $e);
    }
  }
  
  /**
   * Centralized error handling
   */
  protected function handleError($response, Exception $e) {
    $status = (int)$e->getCode() ?: 500;
    $message = $e->getMessage();
    
    // Log error
    \App\Utils\Logger::error($message, $e);
    
    return Response::error($response, $message, $status);
  }
}
```

---

## COMMON PATTERNS FOR AI GENERATION

### Pattern 1: Search/Filter Endpoint

```php
public function search($request, $response) {
  try {
    $params = $request->getQueryParams();
    $query = $params['q'] ?? '';
    $filters = [
      'status' => $params['status'] ?? '',
      'category' => $params['category'] ?? ''
    ];
    $page = (int)($params['page'] ?? 1);
    
    $results = $this->service->search($query, $filters, $page);
    
    return Response::json($response, $results, 200);
  } catch (Exception $e) {
    return Response::error($response, $e->getMessage(), 500);
  }
}
```

### Pattern 2: Many-to-Many Relationship

```php
/**
 * Add related record
 */
public function addRelated($request, $response, $args) {
  try {
    $parentId = $args['parentId'];
    $data = $request->getParsedBody();
    $relatedId = $data['related_id'];
    
    // Check parent exists
    if (!$this->service->exists($parentId)) {
      return Response::error($response, 'Parent not found', 404);
    }
    
    // Add relationship
    $junction = new JunctionModel($this->database);
    $junction->create([
      'parent_id' => $parentId,
      'related_id' => $relatedId
    ]);
    
    return Response::json($response, ['message' => 'Added'], 201);
  } catch (Exception $e) {
    return Response::error($response, $e->getMessage(), 500);
  }
}
```

### Pattern 3: Toggle Action (Like/Upvote)

```php
public function toggle($request, $response, $args) {
  try {
    $resourceId = $args['id'];
    $userId = $request->getAttribute('userId');
    
    $toggle = new ToggleModel($this->database);
    $exists = $toggle->findByResourceAndUser($resourceId, $userId);
    
    if ($exists) {
      $toggle->delete($exists['id']);
      $count = $this->service->decrementCount($resourceId);
      return Response::json($response, ['toggled_off' => true, 'count' => $count], 200);
    } else {
      $toggle->create(['resource_id' => $resourceId, 'user_id' => $userId]);
      $count = $this->service->incrementCount($resourceId);
      return Response::json($response, ['toggled_on' => true, 'count' => $count], 201);
    }
  } catch (Exception $e) {
    return Response::error($response, $e->getMessage(), 500);
  }
}
```

### Pattern 4: File Upload Handler

```php
public function upload($request, $response) {
  try {
    $uploadedFiles = $request->getUploadedFiles();
    $file = $uploadedFiles['file'] ?? null;
    
    if (!$file || $file->getError() !== UPLOAD_ERR_OK) {
      return Response::error($response, 'File upload failed', 400);
    }
    
    // Validate file
    $this->validateFile($file);
    
    // Move to storage
    $filename = $this->generateFilename($file);
    $file->moveTo($this->storagePath . $filename);
    
    // Save metadata
    $resourceId = $this->service->saveFile([
      'filename' => $filename,
      'original_name' => $file->getClientFilename(),
      'size' => $file->getSize(),
      'mime_type' => $file->getClientMediaType()
    ]);
    
    return Response::json($response, ['id' => $resourceId], 201);
  } catch (Exception $e) {
    return Response::error($response, $e->getMessage(), 400);
  }
}

private function validateFile($file) {
  $allowed = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
  $ext = strtolower(pathinfo($file->getClientFilename(), PATHINFO_EXTENSION));
  
  if (!in_array($ext, $allowed)) {
    throw new Exception('File type not allowed', 400);
  }
  
  if ($file->getSize() > 50 * 1024 * 1024) {
    throw new Exception('File too large', 400);
  }
}
```

### Pattern 5: Pagination with Metadata

```php
public function list($request, $response) {
  try {
    $params = $request->getQueryParams();
    $page = max(1, (int)($params['page'] ?? 1));
    $perPage = min(100, (int)($params['per_page'] ?? 20));
    
    $data = $this->service->paginate($page, $perPage);
    
    $response = Response::json($response, [
      'data' => $data['records'],
      'pagination' => [
        'page' => $page,
        'per_page' => $perPage,
        'total' => $data['total'],
        'last_page' => ceil($data['total'] / $perPage),
        'has_more' => $page < ceil($data['total'] / $perPage)
      ]
    ], 200);
    
    return $response;
  } catch (Exception $e) {
    return Response::error($response, $e->getMessage(), 500);
  }
}
```

### Pattern 6: Authorization Check

```php
private function checkAuthorization($resourceId, $userId, $permission = 'edit') {
  $resource = $this->service->get($resourceId);
  
  if (!$resource) {
    throw new Exception('Resource not found', 404);
  }
  
  // Owner always has access
  if ($resource['user_id'] === $userId) {
    return true;
  }
  
  // Check role-based access
  if ($permission === 'edit') {
    return in_array($resource['access_level'], ['owner', 'editor']);
  }
  
  return false;
}
```

### Pattern 7: Soft Deletes Query

```php
public function findById($id) {
  $stmt = $this->connection->prepare("
    SELECT * FROM {$this->table}
    WHERE id = ? AND deleted_at IS NULL
  ");
  $stmt->execute([$id]);
  return $stmt->fetch(\PDO::FETCH_ASSOC);
}

public function restore($id) {
  $stmt = $this->connection->prepare("
    UPDATE {$this->table}
    SET deleted_at = NULL
    WHERE id = ?
  ");
  return $stmt->execute([$id]);
}
```

### Pattern 8: Audit Log Entry

```php
private function logAction($userId, $action, $resourceType, $resourceId, $details = []) {
  $auditLog = new AuditLog($this->database);
  $auditLog->create([
    'user_id' => $userId,
    'action' => $action,
    'resource_type' => $resourceType,
    'resource_id' => $resourceId,
    'details' => json_encode($details),
    'ip_address' => $_SERVER['REMOTE_ADDR'],
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
    'created_at' => date('Y-m-d H:i:s')
  ]);
}
```

---

## DATABASE QUERY PATTERNS FOR AI GENERATION

### Pattern: Full-Text Search

```php
public function searchByName($query) {
  $stmt = $this->connection->prepare("
    SELECT *, MATCH(name, description) AGAINST(? IN BOOLEAN MODE) as relevance
    FROM {$this->table}
    WHERE MATCH(name, description) AGAINST(? IN BOOLEAN MODE)
    AND deleted_at IS NULL
    ORDER BY relevance DESC
  ");
  
  $stmt->execute([$query, $query]);
  return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}
```

### Pattern: Aggregation Query

```php
public function getStats($projectId) {
  $stmt = $this->connection->prepare("
    SELECT 
      COUNT(DISTINCT user_id) as member_count,
      COUNT(CASE WHEN status = 'done' THEN 1 END) as completed_tasks,
      COUNT(*) as total_tasks,
      AVG(DATEDIFF(updated_at, created_at)) as avg_completion_days
    FROM tasks
    WHERE project_id = ?
    AND deleted_at IS NULL
  ");
  
  $stmt->execute([$projectId]);
  return $stmt->fetch(\PDO::FETCH_ASSOC);
}
```

### Pattern: Hierarchical Query

```php
public function getCommentThread($parentId) {
  $stmt = $this->connection->prepare("
    WITH RECURSIVE comment_tree AS (
      SELECT * FROM comments 
      WHERE parent_id = ?
      
      UNION ALL
      
      SELECT c.* FROM comments c
      INNER JOIN comment_tree ct ON c.parent_id = ct.id
    )
    SELECT * FROM comment_tree
    ORDER BY created_at ASC
  ");
  
  $stmt->execute([$parentId]);
  return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}
```

### Pattern: Conditional Aggregation

```php
public function getContributionsByType($projectId) {
  $stmt = $this->connection->prepare("
    SELECT 
      user_id,
      SUM(CASE WHEN action_type = 'task_completed' THEN points_awarded ELSE 0 END) as tasks,
      SUM(CASE WHEN action_type = 'file_uploaded' THEN points_awarded ELSE 0 END) as uploads,
      SUM(CASE WHEN action_type = 'comment_added' THEN points_awarded ELSE 0 END) as comments,
      SUM(points_awarded) as total
    FROM contribution_logs
    WHERE project_id = ?
    GROUP BY user_id
    ORDER BY total DESC
  ");
  
  $stmt->execute([$projectId]);
  return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}
```

---

## ERROR HANDLING PATTERNS

```php
/**
 * Standard error codes
 */
const ERRORS = [
  'VALIDATION_FAILED' => 422,
  'UNAUTHORIZED' => 401,
  'FORBIDDEN' => 403,
  'NOT_FOUND' => 404,
  'CONFLICT' => 409,
  'RATE_LIMITED' => 429,
  'SERVER_ERROR' => 500
];

/**
 * Structured error response
 */
public static function error($response, $message, $code, $details = []) {
  $body = [
    'error' => [
      'message' => $message,
      'code' => $code,
      'timestamp' => date('c')
    ]
  ];
  
  if (!empty($details)) {
    $body['error']['details'] = $details;
  }
  
  return Response::json($response, $body, self::ERRORS[$code] ?? 500);
}
```

---

## FOR AI AGENT: HOW TO USE THESE TEMPLATES

**When generating a new controller:**
```
1. Use CONTROLLER TEMPLATE as base
2. Replace "ControllerName" with actual name
3. Implement list(), create(), show(), update(), delete()
4. Use Pattern 1, 2, 3 as needed
5. Add error handling via handleError()
```

**When generating a service:**
```
1. Use SERVICE TEMPLATE as base
2. Implement list(), create(), update(), delete()
3. Add validate() method specific to the service
4. Use Logger for all actions
5. Handle all exceptions consistently
```

**When generating a model:**
```
1. Use MODEL TEMPLATE as base
2. Update $table and $fillable arrays
3. Add specific query methods as needed
4. Use DATABASE PATTERNS for complex queries
5. Always include soft deletes
```

---

## TESTING TEMPLATES

### Service Test Template

```php
<?php
// tests/Unit/ServiceNameTest.php

use PHPUnit\Framework\TestCase;
use App\Services\ServiceName;

class ServiceNameTest extends TestCase {
  private $service;
  private $mockDb;
  
  protected function setUp(): void {
    $this->mockDb = $this->createMock(PDO::class);
    $this->service = new ServiceName($this->mockDb);
  }
  
  public function testCreate() {
    $data = ['name' => 'Test'];
    $result = $this->service->create($data);
    
    $this->assertIsInt($result);
    $this->assertGreaterThan(0, $result);
  }
  
  public function testValidationFails() {
    $this->expectException(Exception::class);
    $this->service->create([]);  // Empty data should fail validation
  }
}
```

### Controller Test Template

```php
<?php
// tests/Integration/ControllerNameTest.php

use PHPUnit\Framework\TestCase;
use Slim\Factory\AppFactory;

class ControllerNameTest extends TestCase {
  private $app;
  
  protected function setUp(): void {
    $this->app = AppFactory::create();
    // Set up routes and middleware
  }
  
  public function testListEndpoint() {
    $request = $this->createRequest('GET', '/api/resource');
    $response = $this->app->handle($request);
    
    $this->assertEquals(200, $response->getStatusCode());
    $body = json_decode((string)$response->getBody(), true);
    $this->assertArrayHasKey('data', $body);
  }
}
```

---

**Ready to generate backend code? Use these templates to ensure consistency and quality!**
