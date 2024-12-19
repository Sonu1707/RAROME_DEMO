<?php
namespace App\Tests;

use App\Service\ApiDocumentService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Psr\Log\LoggerInterface;

class ApiDocumentServiceTest extends TestCase
{
    private $client;
    private $logger;
    private $filesystem;
    private $storageDir;
    private $service;

    protected function setUp(): void
    {
        $this->client = $this->createMock(HttpClientInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->storageDir = $_ENV['APP_DOC_STORAGE'];

        $this->service = new ApiDocumentService($this->client, $this->logger, $this->storageDir);
    }

    public function testFetchAndStoreDocuments()
    {
        // Mock the ResponseInterface explicitly
        $response = $this->createMock(ResponseInterface::class);
        $this->client->expects($this->once())
            ->method('request')
            ->with('GET', 'https://educhain.free.beeceptor.com/get-documents')
            ->willReturn($response);

        $documents = [
            [
                'certificate' => base64_encode('test certificate content'),
                'description' => 'TestDocument',
                'doc_no' => '276088',
            ],
        ];

        // Mock the toArray method of ResponseInterface
        $response->expects($this->once())
            ->method('toArray')
            ->willReturn($documents);

        // Mock Filesystem's dumpFile method
        $this->filesystem->expects($this->exactly(0))
            ->method('dumpFile')
            ->with(
                $this->storageDir . '/TestDocument_12345.pdf',
                'test certificate content'
            );

        // Call the method
        $this->service->fetchAndStoreDocuments();
    }
}