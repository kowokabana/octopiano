<?php

namespace App\Controller;

use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SongController extends AbstractController
{
    /**
     * @Route("/songs/new", name="new_song", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function new(Request $request): JsonResponse
    {
        return new JsonResponse(['status' => 'Song created!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/songs/{title}", name="new_song", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getSong(Request $request): JsonResponse
    {

    }
}
