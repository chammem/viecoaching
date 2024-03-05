<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Form\RechercheType;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
     public function affichecategorie(CategorieRepository $x,  Request $req): Response
     {
        $cat = $x->trie();
       //  $cat = $x->findAll();
       $form=$this->createForm(RechercheType::class);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $data=$form->get('nomCategorie')->getData();
            $Ref=$x->recherche($data);
            return $this->renderForm('categorie/affichecategorie.html.twig', [
                'cat'=> $Ref,
                'form'=> $form,
            ]);
        }
         return $this->renderForm('categorie/affichecategorie.html.twig', [
             'cat'=> $cat,
             'form'=> $form,

             
         ]);
             
         }
         //affiche paritie patient 
         #[Route('/afficheCatP', name: 'afficheCatP')]
         public function afficheCatP(CategorieRepository $x, Request $req,PaginatorInterface $paginator): Response
         {
             $cat = $x->findAll();
             $pagination = $paginator->paginate(
                $cat, 
                $req->query->getInt('page',1),
                2
            );
             return $this->render('categorie/afficheCatP.html.twig', [
                 'cat' => $pagination ,

             ]);
         }
         //Ajout ressource 
         #[Route('/addcategorie', name: 'addcategorie')]
         public function addressource(ManagerRegistry $managerRegistry, Request $req,SluggerInterface $slugger): Response
         {
             $em=$managerRegistry->getManager();
             $categorie=new Categorie;
             $form=$this->createForm(CategorieType::class,$categorie);
             $form->handleRequest($req);
             if($form->isSubmitted() && $form->isValid())
             {  
                 $file = $form->get('image')->getData();
                 if($file){
                     $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                     $safeFilename = $slugger->slug($originalFilename);
                     $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
                     try {
                         $file->move(
                             $this->getParameter('uploads_directory'),
                             $newFilename
                         );
                     } catch (FileException $e) {
                     }
     
                     $categorie->setImage($newFilename);
 
                 } 
                 $em->persist($categorie);
                 $em->flush();
                 return $this->redirectToRoute('affichecategorie');
             }
             return $this->renderForm('categorie/addcategorie.html.twig', [
                 'form' => $form ,
             ]);
         }
         
    //modifier categorie
    #[Route('/editcategorie/{id}', name: 'editcategorie')]
    public function editcategorie($id,CategorieRepository $categorieRepository,ManagerRegistry $managerRegistry,Request $req,SluggerInterface $slugger): Response
    {
        
        $em=$managerRegistry->getManager();
        $e=$categorieRepository->find($id);
        $form= $this->createForm(CategorieType::class,$e);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
            $file = $form->get('image')->getData();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
    
                $e->setImage($newFilename);
            }

            $em->persist($e);
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
    #[Route('/stats', name: 'stats')]
    public function stats(CategorieRepository $c): Response
    {
        $statisticsByCategory = $c->getStatisticsByCategory();
        return $this->render('categorie/stats.html.twig', [
            'statisticsByCategory' => $statisticsByCategory,
        ]);
    }
}
