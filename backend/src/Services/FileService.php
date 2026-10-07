<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use App\Utils\Logger;
use Psr\Http\Message\UploadedFileInterface;

class FileService
{
    private string $uploadDir;
    private int $maxSize;
    private array $allowedExtensions;

    public function __construct()
    {
        $settings = require __DIR__ . '/../../config/settings.php';
        $this->uploadDir = $settings['upload']['directory'] ?? (__DIR__ . '/../../storage/uploads');
        $this->maxSize = $settings['upload']['max_size'] ?? 52428800; // 50MB
        $this->allowedExtensions = $settings['upload']['allowed_extensions'] ?? ['pdf', 'doc', 'docx', 'zip', 'csv', 'png', 'jpg'];

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function handleUpload(UploadedFileInterface $file, string $subfolder = ''): array
    {
        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new ValidationException('Upload failed with error code: ' . $file->getError());
        }

        $this->validateFile($file);

        $clientFilename = $file->getClientFilename();
        $ext = strtolower(pathinfo($clientFilename, PATHINFO_EXTENSION));
        $uniqueName = sprintf('%s_%s.%s', uniqid('file_', true), date('YmdHis'), $ext);

        $targetDirectory = $this->uploadDir;
        if ($subfolder !== '') {
            $targetDirectory .= '/' . trim($subfolder, '/');
            if (!is_dir($targetDirectory)) {
                mkdir($targetDirectory, 0777, true);
            }
        }

        $targetPath = $targetDirectory . '/' . $uniqueName;
        $file->moveTo($targetPath);

        // Format human readable size
        $bytes = $file->getSize();
        $sizeStr = $this->formatBytes($bytes);

        Logger::info("File uploaded successfully: {$uniqueName} ({$sizeStr})");

        return [
            'filename'      => $uniqueName,
            'original_name' => $clientFilename,
            'file_path'     => $targetPath,
            'file_size'     => $sizeStr,
            'bytes'         => $bytes,
            'mime_type'     => $file->getClientMediaType(),
        ];
    }

    private function validateFile(UploadedFileInterface $file): void
    {
        $size = $file->getSize();
        if ($size > $this->maxSize) {
            throw new ValidationException('File exceeds maximum allowed size of ' . $this->formatBytes($this->maxSize));
        }

        $ext = strtolower(pathinfo($file->getClientFilename(), PATHINFO_EXTENSION));
        if (!in_array($ext, $this->allowedExtensions)) {
            throw new ValidationException('File extension .' . $ext . ' is not permitted. Allowed: ' . implode(', ', $this->allowedExtensions));
        }
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        }
        return $bytes . ' B';
    }
}
