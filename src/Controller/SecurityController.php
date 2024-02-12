<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class SecurityController extends AbstractController
{
    #[Route('/connexion', name: 'security.login', methods:['GET','POST'])]
    public function connexion(Security $security): Response
    {if ($security->getUser()) {
        return $this->redirectToRoute('addUtilisateur');
    }
        
        return $this->render('security/login.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route('/deconnexion', name: 'security.logout')]
    public function deconnexion()
    {
        
       
    }

}
