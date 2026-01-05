<?php

namespace App\Controller;

use App\Entity\User;
use App\ResponseModel\UserMeModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\SerializerInterface;

class SecurityController extends AbstractController
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/api/me', name: 'api_me')]
    public function showMe(): JsonResponse
    {
        $user = $this->getUser();
        $userModel = new UserMeModel(email: $user->getEmail());

        return $this->json($userModel);
    }

    #[Route(path: '/api/login', name: 'api_login', methods: ['POST'])]
    public function apiLogin(#[CurrentUser] ?User $user): Response
    {
        if (null === $user) {
            return $this->json(['message' => 'missing credentials'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'user' => $user->getUserIdentifier(),
            'token' => $user->getApiToken(),
        ]);
    }
}
