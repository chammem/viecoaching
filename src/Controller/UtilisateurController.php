<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Utilisateur;
use App\Form\EditMdpType;
use App\Form\ProfilType;
use App\Form\UtilisateurType;
use App\Repository\RoleRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class UtilisateurController extends AbstractController
{
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

#[Route('/editProfil/{id}', name: 'editProfil', methods:['GET','POST'])]
public function editProfil(ManagerRegistry $managerRegistry , Request $req, $id,
 UtilisateurRepository $utilisateurRepository)
{
    $em = $managerRegistry->getManager();
    $dataid=$utilisateurRepository->find($id);

    if (!$this->getUser())
    {
        return $this->redirectToRoute('security.login');
    }

    if ($this->getUser() !== $dataid)
    {
        return $this->redirectToRoute('addUtilisateur');
    }
    
    $form=$this->createForm(ProfilType::class,$dataid);
 
  
    $form->handleRequest($req);

    if ($form->isSubmitted() && $form->isValid()) { 
        $em->persist($dataid);
        $em->flush();
        return $this->redirectToRoute('showuser');
    }
    
    return $this->renderForm('utilisateur/editProfil.html.twig', [
        'form' => $form,
        'dataid' => $dataid,
    ]);
}

#[Route('/editMdp/{id}', name: 'editMdp')]
public function editMdp(ManagerRegistry $managerRegistry, Request $request, UserPasswordHasherInterface $passwordHasher, $id, UtilisateurRepository $utilisateurRepository): Response {
    $user = $this->getUser();
    $dataid = $utilisateurRepository->find($id);
    $em = $managerRegistry->getManager();

    if (!$user) {
        return $this->redirectToRoute('security.login');
    }
    if ($user !== $dataid) {
        return $this->redirectToRoute('addUtilisateur');
    }

    $form = $this->createForm(EditMdpType::class, $dataid);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $newPassword = $form->get('newMdp')->getData();
       
        $dataid->setMdp($newPassword);
            
        $em->persist($dataid);
        $em->flush();

        // Redirect to a success page or wherever appropriate
        return $this->redirectToRoute('showuser');
    }

    // Render the form template if not submitted or invalid
    return $this->renderForm('utilisateur/editMdp.html.twig', [
        'form' => $form,
        'dataid' => $dataid,
       
    ]);
}
}
