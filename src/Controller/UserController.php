<?php


namespace App\Controller;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UserController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/test/{id}", name="testapi", methods={"GET"})
     * @param $id
     * @return JsonResponse
     */
    public function test($id): JsonResponse
    {
        $data = [
            'id' => $id,
            'payload' => 'some payload'
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/users/{id}", name="get_one_user", methods={"GET"})
     * @param $id
     * @return JsonResponse
     */
    public function get($id): JsonResponse
    {
        $customer = $this->userRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $customer->getId(),
            'firstName' => $customer->getFirstName(),
            'lastName' => $customer->getLastName(),
            'email' => $customer->getEmail(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }
}