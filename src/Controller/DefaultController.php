<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\ApiDocumentService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController  extends AbstractController
{
    private $documentService;
    public $documents=null;
    //Construct for use the API document service

    public function __construct(ApiDocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('base.html.twig', [
        ]);
    }


     
    #[Route("/documents", name: 'documents')]
    public function documents(): JsonResponse
    {
        try{
        $documents = $this->documentService->fetchAndStoreDocuments();
        return new JsonResponse(['message' => 'file download sucessfull .'], 200);
        }catch(Exception $e){
            return new JsonResponse(['error' => 'An error occurred while fetching documents.'], 500);
        }
       
    }

    #[Route("/api/documents_consume", name: 'documents_consume', methods:['GET'])]
    public function getresult(): JsonResponse
    {
        try {
            $response = $this->documentService->api_consumption();
        
            // Get the data from the JsonResponse
            $data = $response->getContent();
    
            return new JsonResponse($data);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'An error occurred while fetching documents.',
                'details' => $e->getMessage(), // Optional: include exception details for debugging
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
