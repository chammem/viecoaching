<?php
namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $router;
    private $jwtManager;

    public function __construct(UrlGeneratorInterface $router, JWTTokenManagerInterface $jwtManager)
    {
        $this->router = $router;
        $this->jwtManager = $jwtManager;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
       // Récupérer l'utilisateur à partir du token
       $user = $token->getUser();

       // Récupérer l'e-mail de l'utilisateur
       $email = $user->getUserIdentifier();

       // Récupérer les rôles de l'utilisateur
       $roles = $user->getRoles();

       // Créer le token JWT avec l'utilisateur
       $jwt = $this->jwtManager->create($user);

       // Créer le tableau de données à retourner dans la réponse
       $responseData = [
           'token' => $jwt,
           'user' => [
            'email' => $email,
            'roles' => $roles,
        ],
           // Vous pouvez ajouter d'autres informations sur l'utilisateur ici si nécessaire
       ];

       return new JsonResponse($responseData);
   }
}