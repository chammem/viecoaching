<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    #[Route('/utilisateur', name: 'app_utilisateur')]
    public function index(): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }


    #[Route('/addUtilisateur', name: 'addUtilisateur')]
    public function addUtilisateur(ManagerRegistry $managerRegistry , Request $req ): Response
    {
        $em=$managerRegistry->getManager();
        $utilisateur=new Utilisateur;
        $form=$this->createForm(UtilisateurType::class,$utilisateur);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid())
        {    
            
            $em->persist($utilisateur);
            $em->flush();
            return $this->redirectToRoute('addUtilisateur');
        }
        return $this->renderForm('utilisateur/addUtilisateur.html.twig', [
            'form' => $form ,
        ]);
    }
}
