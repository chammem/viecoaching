<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\TypegroupeType;
use App\Entity\Typegroupe;
use App\Repository\GroupeRepository;
use App\Repository\TypegroupeRepository;


use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

class TypegroupeController extends AbstractController
{
    #[Route('/typegroupe', name: 'app_typegroupe')]
    public function index(): Response
    {
        return $this->render('typegroupe/index.html.twig', [
            'controller_name' => 'TypegroupeController',
        ]);
    }
    #[Route('/showtype', name: 'showtype')]
    public function showtype(TypegroupeRepository $x): Response
    {
        $type=$x->findAll();
        return $this->renderForm('typegroupe/showtype.html.twig', [
            'typegroupe'=> $type,
        ]);
}
#[Route('/createtypegroupe', name: 'createtypegroupe')]

public function createtypegroupe(ManagerRegistry $m ,Request $req): Response
{
    $em=$m->getManager();
   $typegroupe = new Typegroupe();
   $form = $this->createForm(TypegroupeType::class, $typegroupe);
   $form->handleRequest($req);

   if ($form->isSubmitted() && $form->isValid()) {
       $em->persist($typegroupe);
       $em->flush();
       return $this->redirectToRoute('showtype');


   }

   return $this->renderForm('typegroupe/create.html.twig', [
       'f' =>$form
   ]);

}
#[Route('/editgroupe/{id}', name: 'editgroupe')]
    public function editgroupe($id,ManagerRegistry $m,TypegroupeRepository $typeRepository ,Request $req): Response
    {
        $em=$m->getManager();
        $dataid=$typeRepository->find($id);
        $form=$this->createForm(TypegroupeType::class,$dataid);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()){
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('showtype');
        }

        return $this->renderForm('typegroupe/editgroupe.html.twig', [
        'form' =>$form  ]);
    }
    #[Route('/deletegroupe/{id}', name: 'deletegroupe')]
    public function deletegroupe($id,ManagerRegistry $m, GroupeRepository $typeRepository,Request $req): Response
    {
        $em=$m->getManager();
        $id=$typeRepository->find($id);
        $em->remove($id);
        $em->flush();
        return $this->redirectToRoute('showgroupe');
    }
    
}

