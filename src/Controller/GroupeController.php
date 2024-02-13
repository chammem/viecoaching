<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\GroupeType;
use App\Entity\Groupe;
use App\Repository\GroupeRepository;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
class GroupeController extends AbstractController
{
    #[Route('/groupe', name:'app_groupe')]
    public function index(): Response
    {
        return $this->render('groupe/index.html.twig', [
            'controller_name' => 'GroupeController',
        ]);
    }
    #[Route('/showgroupe', name:'showgroupe')]
    public function showgroupe(GroupeRepository $x): Response
    {
        $groupe=$x->findAll();
        return $this->renderForm('groupe/showgroupe.html.twig', [
            'groupe'=> $groupe,
        ]);
}


#[Route('/creategroupe', name: 'creategroupe')]

public function creategroupe(ManagerRegistry $m ,Request $req): Response
{
    $em=$m->getManager();
   $groupe = new groupe();
   $form = $this->createForm(GroupeType::class, $groupe);
   $form->handleRequest($req);

   if ($form->isSubmitted() && $form->isValid()) {
       $em->persist($groupe);
       $em->flush();
       return $this->redirectToRoute('showgroupe');


   }
   return $this->renderForm('groupe/creategroupe.html.twig', [
    'f' =>$form
]);
}

#[Route('/editgroupep/{id}', name: 'editgroupep')]
    public function editgroupep($id,ManagerRegistry $m,GroupeRepository $groupeRepository ,Request $req): Response
    {
        $em=$m->getManager();
        $dataid=$groupeRepository->find($id);
        $form=$this->createForm(GroupeType::class,$dataid);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()){
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('showgroupe');
        }

        return $this->renderForm('groupe/editgroupep.html.twig', [
        'form' =>$form  ]);
    }
    #[Route('/deletegroupe/{id}', name: 'deletegroupe')]
    public function deletegroupe($id,ManagerRegistry $m, GroupeRepository $groupeRepository): Response
    {
        $em=$m->getManager();
        $id=$groupeRepository->find($id);
        $em->remove($id);
        $em->flush();
        return $this->redirectToRoute('showgroupe');
    }
}