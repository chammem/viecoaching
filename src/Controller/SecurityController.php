<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Utilisateur;
use App\Form\LoginType;
use App\Form\RegistreType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
   

    #[Route('/connexion', name: 'security.login', methods:['GET','POST'])]
    public function connexion(AuthenticationUtils $authenticationUtils,Request $req): Response
   {  
    
   
        return $this->render('security/login.html.twig', [
            'last_email' => $authenticationUtils->getLastUsername(),
            'error'=> $authenticationUtils->getLastAuthenticationError(),
            
        ]);
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
            $rolePatient = $roleRepository->findOneBy(['nom_role' => 'Patient']);
            $user->setRole($rolePatient);
    
           
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('security.login');
        }
    
        return $this->renderForm('security/registration.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/deconnexion', name: 'security.logout')]
    public function deconnexion()
    {
        
       
    }
}