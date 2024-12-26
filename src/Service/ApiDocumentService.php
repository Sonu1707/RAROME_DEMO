<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Filesystem\Filesystem;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiDocumentService
{
    private $client;
    private $filesystem;
    private $logger;
    private $storageDir;

    public function __construct(HttpClientInterface $client, LoggerInterface $logger, string $storageDir)
    {
        $this->client = $client;
        $this->logger = $logger;
        $this->filesystem = new Filesystem();
        $this->storageDir = $storageDir;
    }

    public function fetchAndStoreDocuments()
    {
        try {
            $response = $this->client->request('GET', 'https://educhain.free.beeceptor.com/get-documents');
            $documents = $response->toArray();
            foreach ($documents as $doc) {
                $this->storeDocument($doc);
            }
           
        } catch (\Exception $e) {
            $this->logger->error('Error fetching documents: ' . $e->getMessage());
        }
    }

    private function storeDocument(array $doc)
    {
        if (!isset($doc['certificate'], $doc['description'], $doc['doc_no'])) {
            return  $this->logger->error('Invalid document data: ' . json_encode($doc));
            
        }

        $filename = sprintf('%s_%s.pdf', $doc['description'], $doc['doc_no']);
         $filePath = $this->storageDir . '/' . $filename;

        try {
            $decodedFile = base64_decode($doc['certificate']);
            if ($decodedFile === false) {
                throw new \Exception('Base64 decode failed for document: ' . json_encode($doc));
            }
            $this->filesystem->dumpFile($filePath, $decodedFile);
        } catch (\Exception $e) {
            $this->logger->error('Error storing document: ' . $e->getMessage());
        }
    }

    public function api_consumption(): JsonResponse
    {
        try {
            $response = $this->client->request('GET', 'https://raw.githubusercontent.com/RashitKhamidullin/Educhain-Assignment/refs/heads/main/get-documents');
        $documents = $response->toArray();
        $return_final_array = [];

        foreach ($documents as $doc) {
            $return_final_array[] = [
                'app_no' => $doc['app_no'] ?? null, // Use null if keys are missing
                'doc_no' => $doc['doc_no'] ?? null,
                'ver_no' => $doc['ver_no'] ?? null,
                'cert_dt' => $doc['cert_dt'] ?? null,
            ];
        }
     
        return new JsonResponse($return_final_array);
        } catch (\Exception $e) {
            $this->logger->error('Error fetching documents: ' . $e->getMessage());
        }
    }
}
