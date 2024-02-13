<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(): Response
    {
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }
     //function affiche categorie
    
     #[Route('/affichecategorie', name: 'affichecategorie')]
     public function affichecategorie(CategorieRepository $x): Response
     {
         $cat = $x->findAll();
         return $this->render('categorie/affichecategorie.html.twig', [
             'cat'=> $cat
         ]);
             
         }
        //function ajout categorie
        #[Route('/addcategorie', name: 'addcategorie')]
    public function addcategorie(ManagerRegistry $managerRegistry, Request $req): Response
    {
        $em=$managerRegistry->getManager();
        $cat=new Categorie;
        $form=$this->createForm(CategorieType::class,$cat);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid())
        
        {   $em->persist($cat);
            $em->flush();
            return $this->redirectToRoute('affichecategorie');
        }
        return $this->renderForm('categorie/addcategorie.html.twig', [
            'form' => $form ,
        ]);
    }
    //modifier categorie
    #[Route('/editcategorie/{id}', name: 'editcategorie')]
    public function editcategorie($id,CategorieRepository $categorieRepository,ManagerRegistry $managerRegistry,Request $req): Response
    {
        
        $em=$managerRegistry->getManager();
        $dataid=$categorieRepository->find($id);
        $form= $this->createForm(CategorieType::class,$dataid);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('affichecategorie');
        }
        return $this->renderForm('categorie/editcategorie.html.twig', [
            'form' => $form 
        ]);
    }
     //supprimer categorie
     #[Route('/deletcategorie/{id}', name: 'deletcategorie')]
    public function deletcategorie($id,CategorieRepository $categorieRepository ,ManagerRegistry $managerRegistry): Response
    {
        $em=$managerRegistry->getManager();
        $id=$categorieRepository->find($id);
        $em->remove($id);
        $em->flush();
        return $this->redirectToRoute('affichecategorie');
        
    }
}
