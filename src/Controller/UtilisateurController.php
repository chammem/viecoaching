<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Utilisateur;
use App\Form\EditMdpType;
use App\Form\ProfilType;
use App\Form\SearchUserType;
use App\Form\UtilisateurType;
use App\Repository\RoleRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class UtilisateurController extends AbstractController
{
    #[Route('/showuser', name: 'showuser')]
   // #[IsGranted("ROLE_ADMIN", statusCode:403)]
   public function showuser(UtilisateurRepository $utilisateurRepository, Request $request): Response
{
    $form = $this->createForm(SearchUserType::class);
    $form->handleRequest($request);

    $users = [];

    if ($form->isSubmitted() && $form->isValid()) {
        $searchTerm = $form->getData()['searchTerm'];
        $users = $utilisateurRepository->findByNom($searchTerm);
    } else {
        $users = $utilisateurRepository->findAll();
    }
    
    

    return $this->renderForm('utilisateur/showuser.html.twig', [
        'form' => $form,
        'users' => $users,
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
public function editProfil(ManagerRegistry $managerRegistry ,Security $security, Request $req, $id,
 UtilisateurRepository $utilisateurRepository)
{
    $user = $security->getUser();
    $id = $user->getId();
    $em = $managerRegistry->getManager();
    $dataid=$utilisateurRepository->find($id);

    if (!$this->getUser())
    {
        return $this->redirectToRoute('security.login');
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
        'u' => $dataid,
    ]);
}

#[Route('/editMdp/{id}', name: 'editMdp')]
public function editMdp(ManagerRegistry $managerRegistry, Request $request, Security $security, UtilisateurRepository $utilisateurRepository): Response 
{
    $user = $security->getUser();
    $id = $user->getId();
    $dataid = $utilisateurRepository->find($id);
    $em = $managerRegistry->getManager();

    if (!$user) {
        return $this->redirectToRoute('security.login');
    }

    $form = $this->createForm(EditMdpType::class, $dataid);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $newPassword = $form->get('newMdp')->getData();
       
        $dataid->setMdp($newPassword);
            
        $em->persist($dataid);
        $em->flush();

        return $this->redirectToRoute('showuser');
    }

    return $this->renderForm('utilisateur/editMdp.html.twig', [
        'form' => $form,
        'mot' => $dataid,
       
    ]);
}

#[Route('/etatCompte/{id}/{action}', name: 'etatCompte')]
public function etatCompte(ManagerRegistry $managerRegistry,$id, $action, UtilisateurRepository $utilisateurRepository): Response
{
   
    $dataid=$utilisateurRepository->find($id);
    $em = $managerRegistry->getManager();

    if (!$dataid) {
        throw $this->createNotFoundException('Utilisateur non trouvé');
    }

    if ($action === 'disable') {
        $dataid->setActive(false);
    } elseif ($action === 'enable') {
        $dataid->setActive(true);
    }

    $em->flush();

    return $this->redirectToRoute('showuser');
}
}
