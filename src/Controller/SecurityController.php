<?php

namespace App\Controller;

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

    #[Route('/deconnexion', name: 'security.logout')]
    public function deconnexion()
    {
        
       
    }

    #[Route('/inscription', name: 'security.registration', methods:['GET','POST'])]
public function registration(ManagerRegistry $managerRegistry , Request $req): Response
{
    $em=$managerRegistry->getManager();
    $user = new Utilisateur();
    $form=$this->createForm(RegistreType::class,$user);
    $form->handleRequest($req);
   
    if($form->isSubmitted() and $form->isValid())
    {   
        if ($form->get('image')->getData() === null) {
            $user->setImage('default_image.jpg');
        }
        $user=$form->getData();
        $this->addFlash(
            'success',
            'votre compte a bien été crée'
        );
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('security.login');
    }
    return $this->render('security/registration.html.twig', [
        'form' => $form->createView(),
    ]);
}
}
