<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\RoleRepository;
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
    #[Route('/showuser', name: 'showuser')]
    public function showuser(UtilisateurRepository $utilisateurRepository): Response
    {
        $user=$utilisateurRepository->findAll();
        return $this->render('utilisateur/showuser.html.twig', [
            'user' =>  $user,
        ]);
    }

    #[Route('/addUtilisateur', name: 'addUtilisateur')]
    public function addUtilisateur(ManagerRegistry $managerRegistry , Request $req, RoleRepository $roleRepository ): Response
    {
        $em=$managerRegistry->getManager();
        $utilisateur=new Utilisateur;

       /* if (!$utilisateur->getRole()) {
            $defaultRole = $roleRepository->findOneBy(['NomRole' => 'user']);
            if (!$defaultRole) {
                // Créer le rôle ROLE_USER s'il n'existe pas déjà
                $defaultRole = new Role();
                $defaultRole->setNomRole('user');
                $em->persist($defaultRole);
            }
            $utilisateur->setRole($defaultRole);
        */
    
        $form=$this->createForm(UtilisateurType::class,$utilisateur);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid())
        {    
            
            $em->persist($utilisateur);
            $em->flush();
            return $this->redirectToRoute('showuser');
        }
        return $this->renderForm('utilisateur/addUtilisateur.html.twig', [
            'form' => $form ,
        ]);
    }

    #[Route('/editUtilisateur/{id}', name: 'editUtilisateur')]
public function editUtilisateur(ManagerRegistry $managerRegistry , Request $req, $id, UtilisateurRepository $utilisateurRepository): Response
{

    $em = $managerRegistry->getManager();
    $dataid=$utilisateurRepository->find($id);
    
    $form=$this->createForm(UtilisateurType::class,$dataid);
 
    $form->handleRequest($req);

    if ($form->isSubmitted() && $form->isValid()) { 
        $em->persist($dataid);   
        $em->flush();
        return $this->redirectToRoute('showuser');
    }
    return $this->renderForm('utilisateur/editUtilisateur.html.twig', [
        'form' => $form,
    ]);
}
#[Route('/deleteUtilisateur/{id}', name: 'deleteUtilisateur')]
public function deleteUtilisateur(ManagerRegistry $managerRegistry , $id, UtilisateurRepository $utilisateurRepository): Response
{

    $em = $managerRegistry->getManager();
    $dataid=$utilisateurRepository->find($id);
    
        $em->remove($dataid);   
        $em->flush();
        return $this->redirectToRoute('showuser');
    
}
}
