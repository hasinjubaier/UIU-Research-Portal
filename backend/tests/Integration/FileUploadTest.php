<?php

namespace Tests\Integration;

use App\Exceptions\ValidationException;
use App\Services\FileService;
use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\UploadedFile;

class FileUploadTest extends TestCase
{
    private FileService $fileService;
    private string $tempFilePath;

    protected function setUp(): void
    {
        $this->fileService = new FileService();
        $this->tempFilePath = tempnam(sys_get_temp_dir(), 'test_upload_');
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempFilePath)) {
            @unlink($this->tempFilePath);
        }
    }

    public function testUploadSuccessWithAllowedExtension(): void
    {
        file_put_contents($this->tempFilePath, 'Sample PDF content for research paper');
        $stream = (new StreamFactory())->createStreamFromFile($this->tempFilePath);

        $uploadedFile = new UploadedFile(
            $stream,
            'research_paper.pdf',
            'application/pdf',
            filesize($this->tempFilePath),
            UPLOAD_ERR_OK
        );

        $result = $this->fileService->handleUpload($uploadedFile, 'test');

        $this->assertNotEmpty($result['filename']);
        $this->assertEquals('research_paper.pdf', $result['original_name']);
        $this->assertFileExists($result['file_path']);

        // Clean up created test file
        if (file_exists($result['file_path'])) {
            @unlink($result['file_path']);
        }
    }

    public function testUploadFailsWithDisallowedExtension(): void
    {
        file_put_contents($this->tempFilePath, 'Malicious script content');
        $stream = (new StreamFactory())->createStreamFromFile($this->tempFilePath);

        $uploadedFile = new UploadedFile(
            $stream,
            'exploit.exe',
            'application/x-msdownload',
            filesize($this->tempFilePath),
            UPLOAD_ERR_OK
        );

        $this->expectException(ValidationException::class);
        $this->fileService->handleUpload($uploadedFile, 'test');
    }

    public function testUploadFailsWithExcessiveSize(): void
    {
        $stream = (new StreamFactory())->createStream('Mock content');

        // Claim file size is 100MB (exceeds 50MB limit)
        $uploadedFile = new UploadedFile(
            $stream,
            'large_dataset.csv',
            'text/csv',
            104857600, // 100MB
            UPLOAD_ERR_OK
        );

        $this->expectException(ValidationException::class);
        $this->fileService->handleUpload($uploadedFile, 'test');
    }
}
