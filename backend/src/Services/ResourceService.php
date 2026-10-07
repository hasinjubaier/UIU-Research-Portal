<?php

namespace App\Services;

use App\Exceptions\AuthorizationException;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Resource;
use App\Utils\Logger;
use PDO;
use Psr\Http\Message\UploadedFileInterface;

class ResourceService
{
    private PDO $db;
    private Resource $resourceModel;
    private FileService $fileService;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->resourceModel = new Resource($db);
        $this->fileService = new FileService();
    }

    public function list(int $page = 1, int $perPage = 20, array $filters = []): array
    {
        return $this->resourceModel->listWithDetails($page, $perPage, $filters);
    }

    public function get(int $id): array
    {
        $resource = $this->resourceModel->findById($id);
        if (!$resource) {
            throw new ResourceNotFoundException("Resource not found with ID {$id}");
        }

        $stmt = $this->db->prepare("SELECT tag FROM resource_tags WHERE resource_id = ?");
        $stmt->execute([$id]);
        $resource['tags'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return $resource;
    }

    public function create(array $data, ?UploadedFileInterface $file, int $userId): array
    {
        $data['user_id'] = $userId;

        if ($file && $file->getError() === UPLOAD_ERR_OK) {
            $uploadResult = $this->fileService->handleUpload($file, 'resources');
            $data['file_path'] = $uploadResult['file_path'];
            $data['file_size'] = $uploadResult['file_size'];
        }

        $resourceId = (int)$this->resourceModel->create($data);

        if (!empty($data['tags']) && is_array($data['tags'])) {
            $this->resourceModel->syncTags($resourceId, $data['tags']);
        }

        Logger::info("Resource published: ID {$resourceId} by User {$userId}");
        return $this->get($resourceId);
    }

    public function download(int $id, ?int $userId = null): array
    {
        $resource = $this->get($id);
        $this->resourceModel->incrementDownloads($id, $userId);
        $resource['downloads_count'] = (int)$resource['downloads_count'] + 1;
        return $resource;
    }

    public function delete(int $id, int $userId): bool
    {
        $resource = $this->get($id);

        if ((int)$resource['user_id'] !== $userId) {
            throw new AuthorizationException("You can only delete your own resources");
        }

        if (!empty($resource['file_path']) && file_exists($resource['file_path'])) {
            @unlink($resource['file_path']);
        }

        Logger::info("Resource deleted: ID {$id} by User {$userId}");
        return $this->resourceModel->delete($id);
    }
}
