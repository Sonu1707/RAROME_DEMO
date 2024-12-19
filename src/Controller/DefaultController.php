<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\ApiDocumentService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;


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
        return new JsonResponse($documents);
        }catch(Exception $e){
            print_r($e->getMessage());
        }
       
    }

}
