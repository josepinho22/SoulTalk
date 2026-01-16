<?php

namespace App\Controller;

use App\Services\GeminiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    private GeminiService $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    #[Route('/ask', methods: ['POST'])]
    public function ask(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        return new JsonResponse($this->geminiService->request($data['contents']));
    }
}