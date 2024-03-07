<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Utilisateur;
use App\Form\LoginType;
use App\Form\RegistreType;
use App\Form\ResetPasswordRequestType;
use App\Form\ResetPasswordType;
use App\Repository\UtilisateurRepository;
use App\Security\LoginSuccessHandler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface as StorageTokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Mime\Email;


class SecurityController extends AbstractController
{
    private $passwordHasher;
    private $jwtManager;
    private $session;
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage,UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $jwtManager, SessionInterface $session)
    {  $this->tokenStorage = $tokenStorage;
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
    public function deconnexion(TokenStorageInterface $tokenStorage)
    {     $securityToken = $tokenStorage->getToken('BEARER'); // Passer l'identifiant du token
        if ($securityToken) {
            // Supprimer le token de la réponse
            $response = new RedirectResponse($this->generateUrl('homepage'));
            $response->headers->clearCookie('BEARER');
        
            return $response;
        }
        
        // Si aucun token n'est trouvé, rediriger simplement vers la page d'accueil
        return $this->redirectToRoute('homepage');
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

    #[Route('/oubli-pass', name: 'forgotten_password')]
    public function forgottenpassword(Request $req, UtilisateurRepository $utilisateurRepository,
    TokenGeneratorInterface $TokenGenerator,ManagerRegistry $managerRegistry,MailerInterface $mailer): Response
    {
        $em = $managerRegistry->getManager();
        $form = $this->createForm(ResetPasswordRequestType::class);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $utilisateurRepository->findOneByEmail($form->get('email')->getData());
            //on verifie si on a un utilisateur 
            if($user){
               // Générer un token de réinitialisation
           $token = $TokenGenerator->generateToken();
           $user->setResetToken($token);
            $user->setResetTokenExpiresAt(new \DateTime('+1 hour')); // Exemple : le token expire dans un jour
            $em->persist($user);
            $em->flush();
            // on génere un lien de réinitialisation du mot de passe
           $url=$this->generateUrl('reset_pass', ['token'=>$token],UrlGenerator::ABSOLUTE_URL);
           
           //envoi du mail 

           $email = (new Email())
           ->from('admin@viecoaching.com') // Remplacez par votre adresse e-mail
           ->to($user->getEmail()) // Envoyer à l'adresse e-mail de l'utilisateur
           ->subject('Réinitialisation de votre mot de passe')
           ->html("Pour réinitialiser votre mot de passe, veuillez cliquer sur le lien suivant : <a href='{$url}'>Réinitialiser le mot de passe</a>");

         $mailer->send($email);
       $this->addFlash('succes','email envoyer avec succès');
            return $this->redirectToRoute('security.login');
            } 
            //user est null
            $this->addFlash('danger','un probléme est survenu');
            return $this->redirectToRoute('reset_pass');
        }
        return $this->renderForm('security/rest_password_request.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/oubli-pass/{token}', name: 'reset_pass')]
    public function resetPass(Request $request, string $token, UtilisateurRepository $utilisateurRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $utilisateurRepository->findOneBy(['reset_token' => $token]);
    
        if (!$user) {
            // Gérer le cas où aucun utilisateur n'est trouvé avec le token donné
            // Redirection, affichage d'un message d'erreur, etc.
        }
    
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Réinitialisation du mot de passe
            $user->setResetToken('');
            $user->setMdp($form->get('password')->getData());
    
            $entityManager->persist($user);
            $entityManager->flush();
    
            // Redirection vers la page de connexion
            return $this->redirectToRoute('security.login');
        }
    
        return $this->render('security/rest_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}