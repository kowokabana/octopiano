<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use PhpParser\Node\Expr\Empty_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Intl\Exception\NotImplementedException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/auth", name="auth")
     */
    public function index(): Response
    {
        return $this->render('auth/index.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }

    /**
     * @Route("/auth/register", name="register", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        array_key_exists('firstName', $data) ? $firstName = $data['firstName'] : $firstName = '';
        array_key_exists('lastName', $data) ? $lastName = $data['lastName'] : $lastName = '';
        array_key_exists('username', $data) ? $username = $data['username'] : $username = '';
        array_key_exists('password', $data) ? $password = $data['password'] : $password = '';
        array_key_exists('email', $data) ? $email = $data['email'] : $email = '';

        if (empty($email) || empty($password)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->userRepository->saveUser($firstName, $lastName, $username, $email, $password);

        return new JsonResponse(['status' => 'User created!'], Response::HTTP_CREATED);
    }

    private function createToken(string $username): string
    {
        $payload = [
            "user" => $username,
            "exp"  => (new \DateTime())->modify("+15 minutes")->getTimestamp(),
        ];

        $jwt = JWT::encode($payload, $this->getParameter('jwt_secret'), 'HS256');
    }

    /**
     * @Route("/auth/refresh", name="refresh", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function refresh(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if(!array_key_exists('token', $data)) throw new NotFoundHttpException('Access token must be set');
        $access_token = $data['token'];
        // TODO: get user from token, even if token is invalid
        $username = '';
        $user = $this->userRepository->findOneBy(['username'=> $username]);

        if(empty($user->getRoles()))
            throw new NotFoundHttpException('user is deactivated');

        $jwt = $this->createToken($username);

        return $this->json([
            'message' => 'success!',
            'token' => sprintf('Bearer %s', $jwt),
        ]);
    }


    /**
     * @Route("/auth/login", name="login", methods={"POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return JsonResponse
     */
    public function login(Request $request, UserPasswordEncoderInterface $encoder): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if(!array_key_exists('email', $data)) throw new NotFoundHttpException('Email must be set');
        if(!array_key_exists('password', $data)) throw new NotFoundHttpException('Password must be set');

        $user = $this->userRepository->findOneBy(['email'=> $data['email']]);

        if (!$user || !$encoder->isPasswordValid($user, $data['password'])) {
            return $this->json([
                'message' => 'email or password is wrong.',
            ]);
        }

        $jwt = $this->createToken($user->getUsername());

        return $this->json([
            'message' => 'success!',
            'token' => sprintf('Bearer %s', $jwt),
        ]);
    }
}
