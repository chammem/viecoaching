<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Utilisateur;
use App\Form\LoginType;
use App\Form\RegistreType;
use App\Repository\UtilisateurRepository;
use App\Security\LoginSuccessHandler;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface as StorageTokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $passwordHasher;
    private $jwtManager;
    private $session;
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage,UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $jwtManager, SessionInterface $session)
    { 
        $this->tokenStorage = $tokenStorage;
        $this->passwordHasher = $passwordHasher;
        $this->jwtManager = $jwtManager;
        $this->session = $session;
    }
    #[Route('/connexion', name: 'security.login', methods:['GET','POST'])]
    public function connexion(StorageTokenStorageInterface $tokenStorage,LoginSuccessHandler $loginSuccessHandler,Request $req, AuthenticationUtils $authenticationUtils, UtilisateurRepository $utilisateurRepository): Response
    {$email = $req->request->get('email');
        $password = $req->request->get('mdp');
    
        $user = $utilisateurRepository->findOneByEmail($email);
    
        // Vérification si l'utilisateur existe et si le mot de passe est correct
        if (!$user || !$this->passwordHasher->isPasswordValid($user, $password)) {
            // Retourner un message d'erreur
            $errorMessage = 'Identifiants invalides';
    
            // Afficher un message d'erreur dans l'interface utilisateur (par exemple à l'aide d'un toaster)
            $this->addFlash('error', $errorMessage);
    
            // Rediriger vers la page de connexion avec le message d'erreur
            return $this->render('security/login.html.twig', [
                'last_email' => $email,
                'error' => $errorMessage,
            ]);
        }
    
        // Utiliser le gestionnaire de succès de connexion pour gérer la réussite de l'authentification
        return $loginSuccessHandler->onAuthenticationSuccess($req, $tokenStorage->getToken());
    
    
    }

    #[Route('/deconnexion', name: 'security.logout')]
    public function deconnexion()
    {    $securityToken = $this->tokenStorage->getToken();
        if ($securityToken) {
            // Supprimer le token de la réponse
            $response = new RedirectResponse($this->generateUrl('homepage'));
            $response->headers->clearCookie('BEARER');
    
            return $response;
        }
    
        // Si aucun token n'est trouvé, rediriger simplement vers la page d'accueil
        return new RedirectResponse($this->generateUrl('homepage'));
    }

    #[Route('/inscription', name: 'security.registration', methods: ['GET', 'POST'])]
    public function registration(ManagerRegistry $managerRegistry, Request $request): Response
    {
        $em = $managerRegistry->getManager();
        $user = new Utilisateur();
        $form = $this->createForm(RegistreType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('imageFile')->getData() === null) {
                $user->setImage('default_image.jpg');
            }
            // Attribuer automatiquement le rôle "patient" à l'utilisateur
            $roleRepository = $em->getRepository(Role::class);
            $rolePatient = $roleRepository->findOneBy(['nom_role' => 'ROLE_PATIENT']);
            $user->setRole($rolePatient);
    
           
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('security.login');
        }
    
        return $this->renderForm('security/registration.html.twig', [
            'form' => $form,
        ]);
    }

   
}